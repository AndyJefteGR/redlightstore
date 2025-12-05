<?php

namespace App\Console\Commands;

use App\Models\GameState;
use App\Models\Plant;
use App\Models\Plot;
use Illuminate\Console\Command;

class GameTickCommand extends Command
{
    protected $signature = 'game:tick';
    protected $description = 'Executes a game tick every 15 seconds';

    public function handle()
    {

        // 1. Obtener o crear el estado del juego
        $gameState = GameState::firstOrCreate(
            ['id' => 1],
            [
                'tick_count' => 0,
                'current_cycle' => 'day',
                'day_duration' => 2,
                'night_duration' => 2,
            ]
        );

        // 2. Incrementar tick
        $gameState->tick_count++;

        // 3. Calcular ciclo día/noche
        $cycleLength = $gameState->day_duration + $gameState->night_duration;
        $positionInCycle = $gameState->tick_count % $cycleLength;

        $isCycleDay = $positionInCycle < $gameState->day_duration;
        $gameState->current_cycle = $isCycleDay ? 'day' : 'night';

        // Si es NOCHE: hacer crecer todas las plantas
        if ($gameState->current_cycle === 'night') {
            $this->growAllPlants();
        }

        // 5. Si es el primer DÍA del ciclo: reiniciar contadores
        if ($gameState->current_cycle === 'day' && $positionInCycle === 0) {
            $this->resetDailyCounters();
        }

        // Guardamos el estado del juego
        $gameState->save();
    }

    /**
     * Grow ALL plants
     */
    private function growAllPlants()
    {
        // obtienemos todas las plantas que no están en la etapa final
        $plants = Plant::where('stage', '<', 3)->get();

        foreach ($plants as $plant) {
            // Actualizamos el desarrollo basado en el agua
            $this->updatePlantDevelopment($plant);

            // Verificamos si debe avanzar de etapa
            $this->checkPlantGrowth($plant);
        }
    }

    /**
     * Update plant development
     */
    private function updatePlantDevelopment(Plant $plant)
    {
        // Incrementamos 1 día de desarrollo
        $plant->days_developed += 1;

        // Si no fue regada hoy, penalizamos
        if (!$plant->watered_today) {
            $plant->days_developed -= 2;
            $plant->days_without_water += 1;

            // Si 4+ días sin agua, muere
            if ($plant->days_without_water >= 4) {
                $this->killPlant($plant);
                return;
            }
        } else {
            // Reiniciamos el contador de días sin agua
            $plant->days_without_water = 0;
            $plant->watered_today = false;
        }

        // Nos aseguramos que no sea negativo
        if ($plant->days_developed < 0) {
            $plant->days_developed = 0;
        }

        $plant->save();
    }

    /**
     * Check if plant should advance stage
     */
    private function checkPlantGrowth(Plant $plant)
    {
        // Si los días de desarrollo alcanzaron el requisito
        if ($plant->days_developed >= $plant->days_required) {
            // Avanzar a la siguiente etapa
            $plant->stage += 1;

            // Reiniciar los días de desarrollo
            $plant->days_developed = 0;

            // Actualizar imagen si no es la etapa final
            if ($plant->stage < 3) {
                $plant->current_image = "./img/{$plant->plant_type}_stage{$plant->stage}.png";
            }

            $plant->save();
        }
    }

    /**
     * Kill a plant
     */
    private function killPlant(Plant $plant)
    {
        $plotId = $plant->plot_id;

        // Reiniciamos la parcela
        $plot = Plot::find($plotId);
        $plot->update([
            'planted' => false,
            'stage' => 0,
            'current_image' => './img/plot_empty.png',
            'watered_today' => false,
            'fertilized_today' => false,
        ]);

        // Eliminamos la planta
        $plant->delete();
    }

    /**
     * Reset daily counters
     */
    private function resetDailyCounters()
    {
        Plant::query()->update([
            'watered_today' => false,
            'fertilized_today' => false,
        ]);

        Plot::query()->update([
            'watered_today' => false,
            'fertilized_today' => false,
        ]);
    }
}