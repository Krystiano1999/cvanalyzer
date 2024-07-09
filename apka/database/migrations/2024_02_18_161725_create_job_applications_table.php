<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_post_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('user_points');
            $table->string('cv_path')->nullable();
            $table->json('soft_skills');
            $table->json('hard_skills');
            $table->json('languages')->nullable();
            $table->string('email');
            $table->string('name');
            $table->string('phone');
            $table->timestamps();

            $table->foreign('job_post_id')->references('id')->on('job_posts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
