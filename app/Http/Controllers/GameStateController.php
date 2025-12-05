<?php

namespace App\Http\Controllers;

use App\Models\GameState;
use Illuminate\Http\Request;

class GameStateController extends Controller
{
    // GET /api/game/state
    // Obtener estado actual del juego
    public function state(Request $request)
    {
        $gameState = GameState::firstOrCreate(
            ['id' => 1],
            [
                'tick_count' => 0,
                'current_cycle' => 'day',
                'day_duration' => 2,
                'night_duration' => 2,
            ]
        );

        return response()->json([
            'success' => true,
            'tick_count' => $gameState->tick_count,
            'current_cycle' => $gameState->current_cycle,
            'day_duration' => $gameState->day_duration,
            'night_duration' => $gameState->night_duration,
        ]);
    }
}