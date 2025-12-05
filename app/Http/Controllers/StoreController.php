<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Get all items available in the store
     */
    public function getItems()
    {
        $items = Item::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'item' => $item->name,
                'price' => $item->price,
                'image' => $item->image,
                'type' => $item->type,
            ];
        });

        return response()->json([
            'success' => true,
            'items' => $items,
        ]);
    }
}