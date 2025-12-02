<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
      protected $fillable = [
        'name',
        'description',
        'growth_hours',
        'water_need_per_day',
        'fertilizer_effect',
        'price'

    ];
}
