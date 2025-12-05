<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Plot;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        // Crear 12 plots para cada usuario que ya existe
        $users = User::all();

        foreach ($users as $user) {
            for ($i = 1; $i <= 12; $i++) {
                Plot::firstOrCreate(
                    ['user_id' => $user->id, 'plot_number' => $i],
                    [
                        'planted' => false,
                        'stage' => 0,
                        'current_image' => './img/plot_empty.png',
                        'watered_today' => false,
                        'fertilized_today' => false,
                    ]
                );
            }
        }
    }
}