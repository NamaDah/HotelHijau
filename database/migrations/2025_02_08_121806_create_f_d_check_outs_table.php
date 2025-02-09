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
        Schema::create('f_d_check_outs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ReservationID')->constrained('reservations')->onDelete('cascade');
            $table->foreignId('FDID')->constrained('foods_And_drinks')->onDelete('cascade');
            $table->string('Qty');
            $table->decimal('TotalPrice', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('f_d_check_outs');
    }
};
