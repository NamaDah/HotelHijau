<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'RoomTypeID',
        'RoomNumber',
        'RoomFloor',
        'Description',
    ];

    public function RoomType() {
        return $this->belongsTo(RoomType::class, 'RoomTypeID');
    }
}
