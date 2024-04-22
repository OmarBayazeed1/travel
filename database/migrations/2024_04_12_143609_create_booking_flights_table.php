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
        Schema::create('booking_flights', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['active','cancelled'])->default('active');
            $table->enum('flightClass', ['first','business','economy'])->default('economy');
            $table->foreignId('flight_id')->constrained('flights')->cascadeOnDelete;
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_flights');
    }
};
