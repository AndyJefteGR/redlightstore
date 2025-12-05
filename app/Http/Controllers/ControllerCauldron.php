<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\UserInventory;
use Illuminate\Http\Request;

class ControllerCauldron extends Controller
{
    public function brewPotion(Request $request)
    {
        try {
            $userId = auth()->id();
            $potionId = $request->input('potion_id');
            $ingredients = $request->input('ingredients', []);
            
            if (!$potionId) {
                return response()->json(['error' => 'Potion ID is required'], 400);
            }
            
            // Obtener la poción
            $potion = Item::find($potionId);
            if (!$potion) {
                return response()->json(['error' => 'Potion not found'], 404);
            }
            
            if (empty($ingredients)) {
                return response()->json(['error' => 'No ingredients provided'], 400);
            }
            
            // Validar que el jugador tenga todos los ingredientes
            foreach ($ingredients as $ingredient) {
                $ingredientId = $ingredient['id'] ?? $ingredient['item'] ?? null;
                $requiredQuantity = (int)($ingredient['quantity'] ?? 1);
                
                if (!$ingredientId) continue;
                
                $userInventory = UserInventory::where('user_id', $userId)
                    ->where('item_id', $ingredientId)
                    ->first();
                
                if (!$userInventory || $userInventory->quantity < $requiredQuantity) {
                    $itemName = Item::find($ingredientId)->name ?? "Unknown";
                    return response()->json([
                        'error' => "Insufficient $itemName. Need $requiredQuantity, have " . ($userInventory->quantity ?? 0)
                    ], 400);
                }
            }
            
            // Restar ingredientes del inventario
            foreach ($ingredients as $ingredient) {
                $ingredientId = $ingredient['id'] ?? $ingredient['item'] ?? null;
                $requiredQuantity = (int)($ingredient['quantity'] ?? 1);
                
                if (!$ingredientId) continue;
                
                UserInventory::where('user_id', $userId)
                    ->where('item_id', $ingredientId)
                    ->decrement('quantity', $requiredQuantity);
            }
            
            // Agregar poción al inventario
            $potionInventory = UserInventory::firstOrCreate(
                ['user_id' => $userId, 'item_id' => $potionId],
                ['quantity' => 0]
            );
            $potionInventory->increment('quantity');
            
            return response()->json([
                'success' => true,
                'message' => 'Potion brewed successfully',
                'potion' => $potion->name
            ], 200);
            
        } catch (\Exception $e) {
            \Log::error('Brew potion error: ' . $e->getMessage());
            return response()->json(['error' => 'Error brewing potion: ' . $e->getMessage()], 500);
        }
    }
}
