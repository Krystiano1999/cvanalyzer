<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Models\Companies;
use App\Models\JobPost;
use App\Models\JobApplications;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class PanelController extends Controller
{
    /**
     * Display the employer's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Panel/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Pobiera dane rekrutera i firmy.
     */
    public function getPanelData(Request $request)
    {
        $user = Auth::user();
        $company = Companies::where('recruiter_id', $user->id)->firstOrFail();

        return response()->json([
            'user' => $user,
            'company' => $company,
        ]);
    }

    /**
     * Aktualizuje dane rekrutera i firmy.
     */
    public function updatePanelData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|email',
            'companyName' => 'required|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'location' => 'nullable|string',
            'nip' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
        ]);

        $company = Companies::where('recruiter_id', $user->id)->firstOrFail();
        $companyData = [
            'name' => $request->companyName,
            'description' => $request->description,
            'location' => $request->location,
            'nip' => $request->nip,
        ];

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $folderPath = "company/{$company->id}";
            if (!Storage::exists($folderPath)) {
                Storage::makeDirectory($folderPath);
            }
            $path = $file->storeAs($folderPath, $filename, 'public');
            $companyData['logo'] = $path;
        }

        $company->update($companyData);

        return response()->json([
            'message' => 'Dane panelu zaktualizowane pomyślnie.',
            'user' => $user,
            'company' => $company,
        ]);
    }

    /**
     * Usuwa logo firmy
     */
    public function removeLogo(Request $request)
    {
        $user = Auth::user();
        $company = Companies::where('recruiter_id', $user->id)->firstOrFail();

        if ($company->logo) {
            Storage::delete($company->logo); 
            $company->logo = null; 
            $company->save(); 

            return response()->json(['message' => 'Logo zostało usunięte.'], 200);
        }

        return response()->json(['message' => 'Logo nie istnieje.'], 404);
    }

    public function getJobPosts(Request $request)
    {
        $user = Auth::user();
        $jobPosts = JobPost::where('recruiter_id', $user->id)->get();

        return response()->json($jobPosts);
    }

    public function deleteJobPost(Request $request, $id)
    {
        $jobPost = JobPost::findOrFail($id);
        
        if ($jobPost->recruiter_id != Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $jobPost->delete();

        return response()->json(['message' => 'Oferta pracy została usunięta.']);
    }

    /**
     * Dodaje nową ofertę pracy.
     */
    public function addJobPost(Request $request)
    {
        // Walidacja danych wejściowych
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary_min' => 'required|numeric',
            'salary_max' => 'required|numeric',
            'required_skills' => 'required|array',
            'skills' => 'nullable|array',
            'employment_type' => 'required|array',
            'experience' => 'required|array',
            'contract_type' => 'required|array',
            'job_mode' => 'required|string',
            'foreign_language' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Pobieranie zalogowanego użytkownika
        $user = Auth::user();

        // Wyszukiwanie firmy należącej do użytkownika
        $company = Companies::where('recruiter_id', $user->id)->first();

        if (!$company) {
            return response()->json(['message' => 'Firma nie znaleziona.'], 404);
        }

        // Tworzenie nowej oferty pracy z przypisaniem company_id
        $jobPost = new JobPost($request->all());
        $jobPost->recruiter_id = $user->id;
        $jobPost->company_id = $company->id; // Przypisanie id firmy

        $jobPost->save();

        return response()->json([
            'message' => 'Nowa oferta pracy została dodana.',
            'jobPost' => $jobPost
        ]);
    }

    /**
     * Aktualizuje ofertę pracy.
     */
    public function updateJobPost(Request $request, $id)
    {
        // Walidacja danych przychodzących w żądaniu
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary_min' => 'required|numeric',
            'salary_max' => 'required|numeric',
            'required_skills' => 'required|array',
            'skills' => 'nullable|array',
            'employment_type' => 'required|array',
            'experience' => 'required|array',
            'contract_type' => 'required|array',
            'job_mode' => 'required|string',
            'foreign_language' => 'nullable|array',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        // Sprawdzenie, czy zalogowany użytkownik jest właścicielem oferty pracy
        $jobPost = JobPost::where('id', $id)->where('recruiter_id', Auth::id())->first();
    
        if (!$jobPost) {
            return response()->json(['message' => 'Oferta pracy nie znaleziona lub brak dostępu.'], 404);
        }
    
        // Aktualizacja oferty pracy
        $jobPost->update($request->all());
    
        return response()->json([
            'message' => 'Oferta pracy została zaktualizowana.',
            'jobPost' => $jobPost
        ]);
    }
    
    /**
     * Pobiera aplikacje dla konkretnej oferty pracy.
     *
     * @param  Request $request
     * @param  int     $jobPostId ID oferty pracy
     * @return \Illuminate\Http\Response
     */
    public function getJobApplications(Request $request, $jobPostId)
    {
        $user = Auth::user();

        // Sprawdzenie, czy oferta pracy należy do zalogowanego rekrutera
        $jobPost = JobPost::where('id', $jobPostId)->where('recruiter_id', $user->id)->first();

        if (!$jobPost) {
            return response()->json(['message' => 'Nie znaleziono oferty pracy lub brak dostępu.'], 404);
        }

        // Pobranie aplikacji dla oferty pracy
        $applications = JobApplications::where('job_post_id', $jobPostId)
                                    ->orderBy('user_points', 'desc')
                                    ->get();

        return response()->json($applications);
    }

    public function analyzePdf(Request $request)
    {
        if (!$request->hasFile('cv')) {
            return response()->json(['error' => 'Brak przesłanego pliku'], 400);
        }

        $file = $request->file('cv');

        // Walidacja pliku
        $validator = Validator::make($request->all(), [
            'cv' => 'required|file|mimes:pdf|max:10240', // Maksymalny rozmiar pliku to 10MB
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $filePath = $file->storeAs('temp', uniqid() . '_' . $file->getClientOriginalName());

        $fullPath = storage_path('app/' . $filePath);

        if (!file_exists($fullPath)) {
            Storage::delete($filePath);
            return response()->json(['error' => 'Problem z zapisem pliku na serwerze'], 500);
        }

        try {
            $response = Http::attach(
                'file', file_get_contents($fullPath), basename($fullPath)
            )->post('http://localhost:8888/analyze');

            Storage::delete($filePath);

            return $response->json();
        } catch (\Exception $e) {
            Storage::delete($filePath);
            return response()->json(['error' => 'Problem z analizą pliku: ' . $e->getMessage()], 500);
        }
    }   
}
