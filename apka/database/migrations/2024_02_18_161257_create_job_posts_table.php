<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recruiter_id');
            $table->unsignedBigInteger('company_id');
            $table->string('title');
            $table->text('description');
            $table->decimal('salary_min', 10, 2);
            $table->decimal('salary_max', 10, 2);
            $table->json('required_skills');
            $table->json('skills');
            $table->json('employment_type');
            $table->json('experience');
            $table->json('contract_type');
            $table->json('foreign_language');
            $table->timestamps();

            $table->foreign('recruiter_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
