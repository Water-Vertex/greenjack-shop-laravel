<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sitemaps', function (Blueprint $table) {
            $table->id();

            $table->string('url')->unique();
            $table->string('title')->nullable();

            $table->enum('type', ['static', 'blog', 'category', 'product', 'custom'])->default('custom');

            $table->string('changefreq')->default('weekly');
            $table->decimal('priority', 2, 1)->default(0.5);

            $table->timestamp('lastmod')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sitemaps');
    }
};
