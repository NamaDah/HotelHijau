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
        Schema::create('reservation_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ReservationID')->constrained('reservations')->onDelete('cascade');
            $table->foreignId('RoomID')->constrained('rooms')->onDelete('cascade');
            $table->dateTime('StartDateTime');
            $table->string('DurationNights');
            $table->string('RoomPrice');
            $table->dateTime('CheckInDateTime');
            $table->dateTime('CheckOutDateTime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_rooms');
    }
};
