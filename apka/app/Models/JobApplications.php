<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplications extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_post_id',
        'user_id',
        'cv_path',
        'user_points',
        'soft_skills',
        'hard_skills',
        'name',
        'email',
        'phone',
        'languages',
    ];

    protected $casts = [
        'soft_skills' => 'array',
        'hard_skills' => 'array',
        'languages' => 'array',
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
