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
    Schema::create('shipping_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade');

        // Billing Address
        $table->string('address');
        $table->string('apartment')->nullable();
        $table->string('city');
        $table->string('state');
        $table->string('zip_code');
        $table->string('country', 2);

        // Different Shipping Address
        $table->boolean('different_shipping')->default(false);
        $table->string('shipping_address')->nullable();
        $table->string('shipping_apartment')->nullable();
        $table->string('shipping_city')->nullable();
        $table->string('shipping_state')->nullable();
        $table->string('shipping_zip_code')->nullable();
        $table->string('shipping_country', 2)->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_details');
    }
};
