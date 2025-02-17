<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FDCheckOut extends Model
{
    use HasFactory;

    protected $fillable = [
        `ReservationID`,
        `FDID`,
        `Qty`,
        `TotalPrice`,
    ];

    public function reservation(){
        return $this->belongsTo(Reservation::class, 'ReservationID');
    }

    public function FDID(){
        return $this->belongsTo(FDCheckOut::class, 'FDID');
    }
}
