<?php

namespace App\Http\Controllers;

use App\Models\Plot;
use App\Models\Plant;
use Illuminate\Http\Request;

class PlotController extends Controller
{
    // GET /api/plots
    // Obtener todos los plots del usuario
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $plots = Plot::where('user_id', $userId)
            ->with('plant')
            ->orderBy('plot_number')
            ->get();

        return response()->json([
            'success' => true,
            'plots' => $plots,
        ]);
    }

    // POST /api/plots/{id}/plant
    // Plantar una nueva semilla
    public function plant(Request $request, $id)
    {
        $userId = $request->user()->id;
        $plantType = $request->input('plant_type');

        // Validar que el plot existe y pertenece al usuario
        $plot = Plot::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Validar que el plot no tiene planta
        if ($plot->planted) {
            return response()->json([
                'success' => false,
                'error' => 'This plot already has a plant',
            ], 422);
        }

        // Configuración de plantas
        $plantConfigs = [
            'spideyFlower' => 4,
            'squidPumpkin' => 5,
            'plantaPiranha' => 6,
        ];

        // Validar tipo de planta
        if (!isset($plantConfigs[$plantType])) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid plant type',
            ], 422);
        }

        // Crear planta
        $plant = Plant::create([
            'user_id' => $userId,
            'plot_id' => $id,
            'plant_type' => $plantType,
            'days_required' => $plantConfigs[$plantType],
            'days_developed' => 0,
            'stage' => 0,
            'current_image' => "./img/{$plantType}_stage0.png",
            'watered_today' => false,
            'fertilized_today' => false,
            'days_without_water' => 0,
            'last_watered_at' => now(),
        ]);

        // Actualizar plot
        $plot->update([
            'planted' => true,
            'stage' => 0,
            'current_image' => "./img/{$plantType}_stage0.png",
        ]);

        return response()->json([
            'success' => true,
            'message' => "{$plantType} planted successfully",
            'plant' => $plant,
            'plot' => $plot,
        ], 201);
    }

    // POST /api/plots/{id}/water
    // Regar una planta
    public function water(Request $request, $id)
    {
        $userId = $request->user()->id;

        // Validar que el plot existe y pertenece al usuario
        $plot = Plot::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Validar que hay planta
        if (!$plot->planted || !$plot->plant) {
            return response()->json([
                'success' => false,
                'error' => 'No plant in this plot',
            ], 422);
        }

        $plant = $plot->plant;

        // Validar que no fue regada hoy
        if ($plant->watered_today) {
            return response()->json([
                'success' => false,
                'error' => 'Already watered today. Only 1 watering per day',
            ], 422);
        }

        // Regar planta
        $plant->update([
            'watered_today' => true,
            'last_watered_at' => now(),
            'days_without_water' => 0,
        ]);

        $plot->update(['watered_today' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Plant watered successfully',
            'plant' => $plant,
        ]);
    }

    // POST /api/plots/{id}/fertilize
    // Abonar una planta
    public function fertilize(Request $request, $id)
    {
        $userId = $request->user()->id;

        // Validar que el plot existe y pertenece al usuario
        $plot = Plot::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Validar que hay planta
        if (!$plot->planted || !$plot->plant) {
            return response()->json([
                'success' => false,
                'error' => 'No plant in this plot',
            ], 422);
        }

        $plant = $plot->plant;

        // Validar que no fue abonada hoy
        if ($plant->fertilized_today) {
            return response()->json([
                'success' => false,
                'error' => 'Already fertilized today. Only 1 fertilization per day',
            ], 422);
        }

        // Abonar planta: +1 desarrollo
        $plant->update([
            'fertilized_today' => true,
            'last_fertilized_at' => now(),
            'days_developed' => $plant->days_developed + 1,
        ]);

        $plot->update(['fertilized_today' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Plant fertilized (+1 development)',
            'plant' => $plant,
        ]);
    }

    // DELETE /api/plots/{id}/harvest
    // Cosechar una planta
    public function harvest(Request $request, $id)
    {
        $userId = $request->user()->id;

        // Validar que el plot existe y pertenece al usuario
        $plot = Plot::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Validar que hay planta y está adulta (etapa 3)
        if (!$plot->plant || $plot->plant->stage < 3) {
            return response()->json([
                'success' => false,
                'error' => 'Plant is not ready. Must reach stage 3',
            ], 422);
        }

        $plant = $plot->plant;
        $plantType = $plant->plant_type;

        // Eliminar planta
        $plant->delete();

        // Resetear plot
        $plot->update([
            'planted' => false,
            'stage' => 0,
            'current_image' => './img/plot_empty.png',
            'watered_today' => false,
            'fertilized_today' => false,
        ]);

        // Dar dinero al usuario
        $user = $request->user();
        $rewardMoney = 100;
        $user->increment('cash', $rewardMoney);

        return response()->json([
            'success' => true,
            'message' => "{$plantType} harvested! +{$rewardMoney} cash",
            'user' => $user,
        ]);
    }

    // POST /api/plots/{id}/remove
    // Eliminar una planta
    public function remove(Request $request, $id)
    {
        $userId = $request->user()->id;

        // Validar que el plot existe y pertenece al usuario
        $plot = Plot::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Si hay planta, eliminarla
        if ($plot->plant) {
            $plot->plant->delete();
        }

        // Resetear plot
        $plot->update([
            'planted' => false,
            'stage' => 0,
            'current_image' => './img/plot_empty.png',
            'watered_today' => false,
            'fertilized_today' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Plant removed',
            'plot' => $plot,
        ]);
    }
}