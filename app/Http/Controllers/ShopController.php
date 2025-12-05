<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\UserInventory;
use App\Models\Wallet;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Buy an item from the store
     */
    public function buyItem(Request $request)
    {
        $validatedData = $request->validate([
            'item_id' => 'required|integer|exists:items,id',
        ]);

        $user = $request->user();
        $item = Item::find($validatedData['item_id']);
        $wallet = Wallet::where('user_id', $user->id)->first();

        // Check if user has sufficient funds
        if (!$wallet || $wallet->cash < $item->price) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient funds',
            ], 400);
        }

        // Deduct from wallet
        $wallet->cash -= $item->price;
        $wallet->save();

        // Add to inventory or update quantity
        $inventoryItem = UserInventory::where('user_id', $user->id)
            ->where('item_id', $item->id)
            ->first();

        if ($inventoryItem) {
            $inventoryItem->quantity += 1;
            $inventoryItem->save();
        } else {
            UserInventory::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'quantity' => 1,
            ]);
        }

        // Return updated inventory
        $inventory = UserInventory::where('user_id', $user->id)
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
            'message' => 'Item purchased successfully',
            'balance' => $wallet->cash,
            'inventory' => $inventory,
        ], 200);
    }

    /**
     * Sell an item to the store
     */
    public function sellItem(Request $request)
    {
        $validatedData = $request->validate([
            'item_id' => 'required|integer|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();
        $item = Item::find($validatedData['item_id']);
        $quantity = $validatedData['quantity'];

        // Check if user has the item in inventory
        $inventoryItem = UserInventory::where('user_id', $user->id)
            ->where('item_id', $item->id)
            ->first();

        if (!$inventoryItem || $inventoryItem->quantity < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient quantity in inventory',
            ], 400);
        }

        // Calculate sell price (assume 50% of buy price)
        $sellPrice = intval($item->price * 0.5);
        $totalSellPrice = $sellPrice * $quantity;

        // Add to wallet
        $wallet = Wallet::where('user_id', $user->id)->first();
        $wallet->cash += $totalSellPrice;
        $wallet->save();

        // Update inventory
        $inventoryItem->quantity -= $quantity;
        if ($inventoryItem->quantity <= 0) {
            $inventoryItem->delete();
        } else {
            $inventoryItem->save();
        }

        // Return updated inventory
        $inventory = UserInventory::where('user_id', $user->id)
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
            'message' => 'Item sold successfully',
            'balance' => $wallet->cash,
            'inventory' => $inventory,
        ], 200);
    }
}