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
        Schema::table('orders', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('orders', 'paypal_order_id')) {
                $table->string('stripe_session_id')->nullable()->after('stripe_payment_intent_id');
                $table->string('paypal_order_id')->nullable()->after('stripe_session_id');
            }

            if (!Schema::hasColumn('orders', 'paypal_payer_id')) {
                $table->string('paypal_payer_id')->nullable()->after('paypal_order_id');
            }

            // Add payment_status if missing
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])
                    ->default('pending')
                    ->after('payment_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'paypal_order_id',
                'paypal_payer_id',
            ]);

            // Only drop payment_status if we added it
            if (Schema::hasColumn('orders', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
        });
    }
};
