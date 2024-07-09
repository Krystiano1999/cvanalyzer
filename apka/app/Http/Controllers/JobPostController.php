<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JobPostController extends Controller
{
    public function getJobPosts(Request $request)
    {
        $query = JobPost::query()->with('company');

        $jobPosts = $query->get();

        return Inertia::render('Job', ['jobPosts' => $jobPosts]);
    }

    public function searchJobPosts(Request $request)
    {
        $query = JobPost::query()->with('company');
    
        if ($search = $request->input('search')) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhereHas('company', function ($query) use ($search) {
                          $query->where('name', 'like', "%{$search}%");
                      });
            });
        }
    
        if ($location = $request->input('location')) {
            $query->whereHas('company', function ($query) use ($location) {
                $query->where('location', $location);
            });
        }
    
        if (null !== ($minSalary = $request->input('minSalary'))) {
            $query->where('salary_min', '>=', $minSalary);
        }
    
        if (null !== ($maxSalary = $request->input('maxSalary'))) {
            $query->where('salary_max', '<=', $maxSalary);
        }
    
        if ($contractTypes = $request->input('contractType')) {
            $query->where(function($query) use ($contractTypes) {
                foreach($contractTypes as $index => $type){
                    if($index == 0){
                        $query->whereRaw("JSON_CONTAINS(contract_type, '\"$type\"')");
                    }else{
                        $query->orWhereRaw("JSON_CONTAINS(contract_type, '\"$type\"')");
                    }
                }
            });
        }
    
        if ($experienceLevels = $request->input('experience')) {
            $query->where(function($query) use ($experienceLevels) {
                foreach($experienceLevels as $index => $type){
                    if($index == 0){
                        $query->whereRaw("JSON_CONTAINS(experience, '\"$type\"')");
                    }else {
                        $query->orWhereRaw("JSON_CONTAINS(experience, '\"$type\"')");
                    }
                }
            });
        }
    
        if ($workTypes = $request->input('workType')) {
            $query->where(function($query) use ($workTypes) {
                foreach ($workTypes as $index => $type) {
                    if ($index == 0) {
                        $query->whereRaw("JSON_CONTAINS(employment_type, '\"$type\"')");
                    } else {
                        $query->orWhereRaw("JSON_CONTAINS(employment_type, '\"$type\"')");
                    }
                }
            });
        }
        
        if ($languages = $request->input('languages')) {
            $query->where(function($query) use ($languages) {
                foreach ($languages as $index => $type) {
                    $type = mb_strtolower($type); 
                    if ($index == 0) {
                        $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(foreign_language, '$[0]'))) = ?", [$type]);
                    } else {
                        $query->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(foreign_language, '$[0]'))) = ?", [$type]);
                    }
                }
            });
        }
        
        $jobPosts = $query->get();
    
        return response()->json(['jobPosts' => $jobPosts]);
    }    
}
