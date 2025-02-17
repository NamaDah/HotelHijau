<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'ReservationID',
        'ItemID',
        'Qty',
        'TotalPrice',
    ];

    public function Item() {
       return $this->belongsTo(Item::class, 'ReservationID');
    }
}
