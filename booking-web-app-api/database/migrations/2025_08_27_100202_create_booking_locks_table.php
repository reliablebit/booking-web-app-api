<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking_locks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('seat_number')->nullable(); // null for free seating
            $table->timestamp('expires_at');           // TTL for the hold
            $table->enum('status', ['held','released'])->default('held');
            $table->timestamps();

            // Speed up lookups
            $table->index(['listing_id', 'seat_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_locks');
    }
};
