<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Payment extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'reservation_id',   // ID reservasi terkait
        'amount',           // Jumlah pembayaran
        'payment_status',   // Status pembayaran (pending, completed, failed)
        'payment_method',   // Metode pembayaran
    ];
}
