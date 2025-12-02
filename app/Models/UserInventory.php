<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInventory extends Model
{
    protected $fillable = [

        'user_id',
        'inventory_item_id',
        'quantity'
        
    ];
}
