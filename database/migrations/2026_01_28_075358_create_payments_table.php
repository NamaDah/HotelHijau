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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade'); // Reservasi terkait
            $table->decimal('amount', 10, 2); // Jumlah yang dibayarkan
            $table->enum('payment_status', ['pending', 'completed', 'failed'])->default('pending'); // Status pembayaran
            $table->string('payment_method')->nullable(); // Metode pembayaran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
