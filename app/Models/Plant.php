<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plant extends Model
{
  protected $fillable = [
    'user_id',
    'plot_id',
    'plant_type',
    'days_required',
    'days_developed',
    'stage',
    'current_image',
    'watered_today',
    'fertilized_today',
    'days_without_water',
    'last_watered_at',
    'last_fertilized_at',
  ];

  protected $casts = [
    'watered_today' => 'boolean',
    'fertilized_today' => 'boolean',
    'last_watered_at' => 'datetime',
    'last_fertilized_at' => 'datetime',
  ];

  public function plot(): BelongsTo
  {
    return $this->belongsTo(Plot::class);
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
