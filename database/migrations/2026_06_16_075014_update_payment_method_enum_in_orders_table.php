<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // If the column is ENUM, we need to modify it
        Schema::table('orders', function (Blueprint $table) {
            // For MySQL, if it's an ENUM column, use raw SQL
            DB::statement("ALTER TABLE orders MODIFY payment_method ENUM('cod', 'stripe', 'paypal', 'bank_transfer') DEFAULT 'cod'");
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            DB::statement("ALTER TABLE orders MODIFY payment_method ENUM('cod', 'paypal', 'bank_transfer') DEFAULT 'cod'");
        });
    }
};
