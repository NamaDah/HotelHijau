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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            // $table->date('report_date'); // Tanggal laporan
            // $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('cascade'); // Pembayaran terkait
            // $table->integer('reservation_count')->default(0); // Jumlah reservasi pada tanggal tertentu
            // $table->decimal('total_revenue', 10, 2)->default(0); // Total pendapatan pada tanggal tertentu
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
