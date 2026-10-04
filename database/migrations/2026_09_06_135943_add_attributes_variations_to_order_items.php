<?php
// database/migrations/2026_01_01_000010_add_attributes_variations_to_order_items.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Add variation support
            $table->foreignId('variation_id')->nullable()->after('product_id')->constrained()->nullOnDelete();

            // Add SKU
            $table->string('product_sku')->nullable()->after('product_name');

            // Add discounted price
            $table->decimal('discounted_price', 10, 2)->nullable()->after('price');

            // Add attributes as JSON
            $table->json('attributes')->nullable()->after('subtotal');

            // Add human-readable attribute string
            $table->string('attribute_string')->nullable()->after('attributes');

            // Add image
            $table->string('image')->nullable()->after('attribute_string');
        });

        
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['variation_id']);
            $table->dropColumn([
                'variation_id',
                'product_sku',
                'discounted_price',
                'attributes',
                'attribute_string',
                'image'
            ]);
        });


    }
};
