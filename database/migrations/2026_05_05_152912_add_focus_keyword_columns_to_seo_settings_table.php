<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            // Focus keywords for each page
            $table->string('home_focus_keyword')->nullable()->after('home_meta_keywords');
            $table->string('about_focus_keyword')->nullable()->after('about_meta_keywords');
            $table->string('contact_focus_keyword')->nullable()->after('contact_meta_keywords');
            $table->string('service_focus_keyword')->nullable()->after('service_meta_keywords');
            $table->string('course_focus_keyword')->nullable()->after('course_meta_keywords');
            $table->string('faq_focus_keyword')->nullable()->after('faq_meta_keywords');
        });
    }

    public function down(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            $table->dropColumn([
                'home_focus_keyword',
                'about_focus_keyword',
                'contact_focus_keyword',
                'service_focus_keyword',
                'course_focus_keyword',
                'faq_focus_keyword',
            ]);
        });
    }
};