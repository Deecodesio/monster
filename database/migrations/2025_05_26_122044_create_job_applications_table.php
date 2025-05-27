<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('contact_number');
            $table->string('attach_resume')->nullable(); // File path will be stored
            $table->unsignedBigInteger('career_job_id');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('career_job_id')->references('id')->on('career_jobs')->onDelete('cascade');

            // Indexes for better performance
            $table->index('career_job_id');
            $table->index('email');
            $table->index('created_at');
            $table->index(['career_job_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
