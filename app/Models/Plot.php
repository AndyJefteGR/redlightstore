<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Plot extends Model
{
    protected $fillable = [
        'user_id',
        'plot_number',
        'planted',
        'stage',
        'current_image',
        'watered_today',
        'fertilized_today',
    ];

    protected $casts = [
        'planted' => 'boolean',
        'watered_today' => 'boolean',
        'fertilized_today' => 'boolean',
    ];

    public function plant(): HasOne
    {
        return $this->hasOne(Plant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}