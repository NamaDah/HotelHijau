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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Pelanggan yang memesan
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade'); // Kamar yang dipesan
            $table->date('check_in_date'); // Tanggal check-in
            $table->date('check_out_date'); // Tanggal check-out
            $table->integer('guest_count'); // Jumlah tamu
            $table->decimal('total_cost', 10, 2); // Total biaya
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending'); // Status reservasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
