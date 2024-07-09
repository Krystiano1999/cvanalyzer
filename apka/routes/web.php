<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\JobApplicationController;
use App\Models\JobPost;

Route::get('/', function () {
    return redirect()->route('oferty');
});

Route::get('/oferty', [JobPostController::class, 'getJobPosts'])->name('oferty');

Route::middleware('auth')->group(function () {

});

Route::middleware(['auth', 'jobseeker'])->group(function () {
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profil', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profil/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.upload.photo');
    Route::delete('/profil/remove-photo', [ProfileController::class, 'removePhoto'])->name('profile.remove-photo');
    Route::get('/profil/existing-photo', [ProfileController::class, 'getPhoto'])->name('profile.photo');
    Route::post('/profil/upload-cv', [ProfileController::class, 'uploadCv'])->name('profile.upload.cv');
    Route::delete('/profil/remove-cv', [ProfileController::class, 'removeCv'])->name('profile.remove-cv');
    Route::get('/profil/get-cv', [ProfileController::class, 'getCv'])->name('profile.get-cv');
    Route::post('/profil/applications', [ProfileController::class, 'showApplications'])->name('panel.showApplications');
});

Route::middleware(['auth', 'employer'])->group(function () {
    Route::get('/panel', [PanelController::class, 'edit'])->name('panel.edit');
    Route::post('/panel/data', [PanelController::class, 'getPanelData'])->name('panel.data');
    Route::post('/panel/update', [PanelController::class, 'updatePanelData'])->name('panel.update');
    Route::post('/panel/remove-logo', [PanelController::class, 'removeLogo']);
    Route::post('/panel/job-posts', [PanelController::class, 'getJobPosts']);
    Route::delete('/panel/job-posts/{id}', [PanelController::class, 'deleteJobPost']);
    Route::put('/panel/job-posts/{id}', [PanelController::class, 'updateJobPost'])->name('job-post.update');
    Route::post('/panel/job-posts/add', [PanelController::class, 'addJobPost'])->name('job-post.add');
    Route::post('/panel/job-applications/{jobPostId}', [PanelController::class, 'getJobApplications'])->name('panel.job-applications');
    Route::post('/upload-cv', [PanelController::class, 'analyzePdf']);
});

Route::post('/oferty/szukaj', [JobPostController::class, 'searchJobPosts'])->name('oferty.szukaj');
Route::post('/applications', [JobApplicationController::class, 'store']);


require __DIR__.'/auth.php';
