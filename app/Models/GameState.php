<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameState extends Model
{
    protected $fillable = [
        'tick_count',
        'current_cycle',
        'day_duration',
        'night_duration',
    ];

    protected $table = 'game_states';
}
