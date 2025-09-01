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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_intent_id')->nullable()->after('booking_ref');
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded'])->nullable()->after('payment_intent_id');
            $table->timestamp('confirmed_at')->nullable()->after('payment_status');
            $table->timestamp('cancelled_at')->nullable()->after('confirmed_at');
            $table->string('cancellation_reason')->nullable()->after('cancelled_at');
            $table->decimal('refund_amount', 10, 2)->nullable()->after('cancellation_reason');
            $table->enum('refund_status', ['pending', 'processed', 'failed', 'not_applicable'])->nullable()->after('refund_amount');
            $table->integer('fraud_score')->nullable()->after('refund_status');
            $table->json('fraud_flags')->nullable()->after('fraud_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'payment_intent_id',
                'payment_status',
                'confirmed_at',
                'cancelled_at',
                'cancellation_reason',
                'refund_amount',
                'refund_status',
                'fraud_score',
                'fraud_flags'
            ]);
        });
    }
};
