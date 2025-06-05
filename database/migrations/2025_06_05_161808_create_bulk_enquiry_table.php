<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBulkEnquiryTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bulk_enquiry', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('phone_1');
            $table->string('phone_2')->nullable();
            $table->string('email_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('business_category_id');
            $table->text('message')->nullable();
            $table->text('address')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->string('taluk', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('state', 100)->nullable();

            $table->timestamps();

            // Optional: Add foreign keys if these IDs reference other tables
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            // $table->foreign('business_category_id')->references('id')->on('business_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulk_enquiry');
    }
}
