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
        Schema::create('series_models', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_model_id');
            $table->unsignedBigInteger('series_id');
            $table->timestamps();

            $table->foreign('brand_model_id')->references('id')->on('brand_models')->onDelete('cascade');
            $table->foreign('series_id')->references('id')->on('series')->onDelete('cascade');

            $table->unique(['brand_model_id', 'series_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series_models');
    }
};
