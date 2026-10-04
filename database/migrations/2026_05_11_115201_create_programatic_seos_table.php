<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programatic_seos', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_tags')->nullable();
            $table->longText('page_schema')->nullable();
            $table->string('focus_keyword')->nullable()->unique();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->text('h1_heading')->nullable();
            $table->longText('faqs')->nullable();
            $table->longText('section_content_left')->nullable();
            $table->longText('section_content_right')->nullable();
            $table->string('image_left')->nullable();
            $table->string('image_right')->nullable();
            $table->string('image_left_alt')->nullable();
            $table->string('image_right_alt')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programatic_seos');
    }
};
