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
        Schema::table('repair_requests', function (Blueprint $table) {
            //
            $table->string('payment_method')->nullable()->after('status');
            $table->string('payment_status')->nullable()->after('payment_method');
            $table->string('stripe_session_id')->nullable()->after('payment_status');
            $table->string('stripe_payment_intent_id')->nullable()->after('stripe_session_id');
            $table->string('paypal_order_id')->nullable()->after('stripe_payment_intent_id');
            $table->string('paypal_capture_id')->nullable()->after('paypal_order_id');
            $table->timestamp('paid_at')->nullable()->after('paypal_capture_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repair_requests', function (Blueprint $table) {
            //
        });
    }
};
