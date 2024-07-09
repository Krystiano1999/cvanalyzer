<?php

namespace App\Http\Controllers;

use App\Models\JobApplications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JobPost;

class JobApplicationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'jobId' => 'required|exists:job_posts,id',
            'cv' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $userId = Auth::check() ? Auth::id() : null;
        $cvPath = null;

        if ($userId) {
            $user = User::find($userId);
            $cvPath = $user->cv ?? null;
        }

        if ($request->hasFile('cv')) {
            $folderPath = 'cvs/' . $request->jobId;
            $cvPath = $request->file('cv')->store($folderPath, 'public'); 
        }

        //print_r($cvPath);
        $analysisResults = $this->analyzePdf($cvPath);

        $phone = $analysisResults['entities']['PHONE'][0] ?? '';
        $hard_skills = array_unique($analysisResults['entities']['HARD_SKILL'] ?? []);
        $soft_skills = array_unique($analysisResults['entities']['SOFT_SKILL'] ?? []);
        $languages = array_unique($analysisResults['entities']['LANGUAGES'] ?? []);

        $jobPost = JobPost::find($request->jobId);
        $max_points = $this->calculateMaxPoints($jobPost);
        $user_points = $this->calculateUserPoints($hard_skills, $soft_skills, $jobPost, $max_points);

        $points = round(($user_points * 100 / $max_points), 2);

        $application = JobApplications::create([
            'name' => $request->name,
            'email' => $request->email,
            'job_post_id' => $request->jobId,
            'user_id' => $userId,
            'cv_path' => $cvPath, 
            'user_points' => $points ?? 0,
            'phone' => $phone ?? [],
            'soft_skills' => $soft_skills ?? [],
            'hard_skills' => $hard_skills ?? [],
            'languages' => $languages ?? [],
        ]);

        return response()->json([
            'message' => 'Aplikacja została pomyślnie złożona!',
            'application' => $application,
            'analysisResults' => $analysisResults ?? null,
        ], 201);
    }

    protected function analyzePdf($filePath)
    {
        $fullPath = storage_path('app/public/' . $filePath);
    
        if (!file_exists($fullPath)) {
            throw new \Exception('File does not exist.');
        }
    
        $response = Http::attach(
            'file', file_get_contents($fullPath), basename($fullPath)
        )->post('http://localhost:8888/analyze', [
            [
                'name'     => 'file',
                'contents' => fopen($fullPath, 'r'),
                'filename' => basename($fullPath)
            ]
        ]);
    
        return $response->json();
    }    

    protected function calculateMaxPoints($jobPost)
    {
        $max_points = 0;
        $max_points += count(is_array($jobPost->required_skills) ? $jobPost->required_skills : []) * 3;
        $max_points += count(is_array($jobPost->skills) ? $jobPost->skills : []) * 2;
        $max_points += count(is_array($jobPost->soft_skills) ? $jobPost->soft_skills : []) * 1;
        return $max_points;
    }

    protected function calculateUserPoints($hard_skills, $soft_skills, $jobPost, $max_points)
    {
        $points = 0;
        $hard_skills = $hard_skills ?? [];
        $soft_skills = $soft_skills ?? [];

        if ($jobPost->required_skills !== null) {
            $points += count(array_intersect($hard_skills, $jobPost->required_skills)) * 3;
        }
        if ($jobPost->skills !== null) {
            $points += count(array_intersect($hard_skills, $jobPost->skills)) * 2;
        }
        if ($jobPost->soft_skills !== null) {
            $points += count(array_intersect($soft_skills, $jobPost->soft_skills)) * 1;
        }

        return $points;
    }

}
