<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruiter_id',
        'company_id',
        'title',
        'description',
        'salary_min',
        'salary_max',
        'required_skills',
        'skills',
        'employment_type',
        'experience',
        'contract_type',
        'job_mode',
        'foreign_language',
    ];

    protected $casts = [
        'required_skills' => 'array',
        'skills' => 'array',
        'employment_type' => 'array',
        'experience' => 'array',
        'contract_type' => 'array',
        'foreign_language' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Companies::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }
}
