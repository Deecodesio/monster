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
        Schema::create('franchise', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_1');
            $table->string('phone_2')->nullable();
            $table->string('email_id');
            $table->text('shop_location_plan');
            $table->text('message')->nullable();
            $table->text('address');
            $table->string('pincode');
            $table->string('taluk');
            $table->string('district');
            $table->string('state');
            $table->timestamps();

            // Indexes for better performance
            $table->index(['state', 'district']);
            $table->index('pincode');
            $table->index('email_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franchise');
    }
};
