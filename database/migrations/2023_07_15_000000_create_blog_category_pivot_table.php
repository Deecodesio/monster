<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blog_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('blog_categories')->onDelete('cascade');

            $table->unique(['blog_id', 'category_id']);
        });

        // Add category_id to blogs table for direct relationship
        Schema::table('blogs', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('excerpt');
            $table->foreign('category_id')->references('id')->on('blog_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::dropIfExists('blog_categories');
    }
};
