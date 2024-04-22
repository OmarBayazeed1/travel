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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('flight_number')->unique();
            $table->string('airline');
            $table->string('origin');
            $table->string('destination');
            $table->dateTime('boarding_time');
            $table->dateTime('arrival_time');
            $table->unsignedInteger('price');
            $table->unsignedInteger('distanceInKilo');
            $table->unsignedInteger('capacity');
            $table->foreignId('serviceOwner_id')->constrained('service_owners')->cascadeOnDelete;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
