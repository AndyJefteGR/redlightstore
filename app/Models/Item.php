<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'image',
        'type',
    ];

    /**
     * Get the user inventories for this item
     */
    public function userInventories()
    {
        return $this->hasMany(UserInventory::class);
    }
}
