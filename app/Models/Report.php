<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Report extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'report_date',
        'report_id',
        'reservetaion_count',
        'total_revenue',
    ];
}
