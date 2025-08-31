<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->enum('type', ['bus','hotel','event','flight']);
            $table->decimal('price', 10, 2);
            $table->integer('total_seats')->default(0);
            $table->integer('available_seats')->default(0);
            $table->dateTime('start_time');
            $table->string('location');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
