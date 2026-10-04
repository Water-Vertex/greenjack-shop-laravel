<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();

            // Home
            $table->string('home_meta_title')->nullable();
            $table->text('home_meta_description')->nullable();
            $table->text('home_meta_keywords')->nullable();
            $table->text('home_meta_tags')->nullable();
            $table->string('home_title')->nullable();
            $table->json('home_page_schema')->nullable();

            // About
            $table->string('about_meta_title')->nullable();
            $table->text('about_meta_description')->nullable();
            $table->text('about_meta_keywords')->nullable();
            $table->text('about_meta_tags')->nullable();
            $table->string('about_title')->nullable();
            $table->json('about_page_schema')->nullable();

            // Contact
            $table->string('contact_meta_title')->nullable();
            $table->text('contact_meta_description')->nullable();
            $table->text('contact_meta_keywords')->nullable();
            $table->text('contact_meta_tags')->nullable();
            $table->string('contact_title')->nullable();
            $table->json('contact_page_schema')->nullable();

            // Service
            $table->string('service_meta_title')->nullable();
            $table->text('service_meta_description')->nullable();
            $table->text('service_meta_keywords')->nullable();
            $table->text('service_meta_tags')->nullable();
            $table->string('service_title')->nullable();
            $table->json('service_page_schema')->nullable();

            // Course 
            $table->string('course_meta_title')->nullable();
            $table->text('course_meta_description')->nullable();
            $table->text('course_meta_keywords')->nullable();
            $table->text('course_meta_tags')->nullable();
            $table->json('course_page_schema')->nullable();

            // FAQ
            $table->string('faq_meta_title')->nullable();
            $table->text('faq_meta_description')->nullable();
            $table->text('faq_meta_keywords')->nullable();
            $table->text('faq_meta_tags')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('seo_settings');
    }
};