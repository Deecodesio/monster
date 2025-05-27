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
        Schema::create('career_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_name');
            $table->text('job_details');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('created_by');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('location_id')->references('id')->on('add_city')->onDelete('cascade');
            $table->foreign('state_id')->references('state_id')->on('add_city')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('roles')->onDelete('cascade');

            // Indexes for better performance
            $table->index(['status', 'created_at']);
            $table->index('location_id');
            $table->index('state_id');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_jobs');
    }
};
