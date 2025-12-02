<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantedCrop extends Model
{
     protected $fillable = [
        'plant_id',
        'garden_plot_id',
        'health',
        'last_watered_at',
        'last_fertilized_at',
        'stage',
        'harvested_at',
        'sell_to_market'

    ];

}
