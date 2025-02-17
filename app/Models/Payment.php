<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Payment extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'amount',
        'payment_status',
        'payment_method',
    ];

    public function reservation() {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }
}
