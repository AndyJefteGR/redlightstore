<?php

namespace App\Http\Controllers;

use App\Models\UserInventory;
use App\Models\Wallet;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Get the authenticated user's inventory
     */
    public function getInventory(Request $request)
    {
        $user = $request->user();

        $inventoryItems = UserInventory::where('user_id', $user->id)
            ->with('item')
            ->get()
            ->map(function ($inventoryItem) {
                return [
                    'id' => $inventoryItem->item->id,
                    'item' => $inventoryItem->item->name,
                    'quantity' => $inventoryItem->quantity,
                    'image' => $inventoryItem->item->image,
                    'type' => $inventoryItem->item->type,
                ];
            });

        return response()->json([
            'success' => true,
            'inventory' => $inventoryItems,
        ]);
    }

    /**
     * Get the user's wallet balance
     */
    public function getBalance(Request $request)
    {
        $user = $request->user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'balance' => $wallet->cash,
            'currency' => 'coins',
        ]);
    }
}