<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'ReservationID',
        'RoomID',
        'StartDateTime',
        'DurationNights',
        'RoomPrice',
        'CheckInDateTime',
        'CheckOutDateTime',
    ];

    public function reservation() {
        return $this->belongsTo(Reservation::class, 'ReservationID');
    }
}
