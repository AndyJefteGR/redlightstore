<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GardenPlot extends Model
{
    protected $fillable = [

        'user_id',
        'status'
        
    ];
}
