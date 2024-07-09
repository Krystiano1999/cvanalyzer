<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'logo',
        'location',
        'nip',
        'recruiter_id',
    ];

    public function jobPosts()
    {
        return $this->hasMany(JobPost::class);
    }

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }
}
