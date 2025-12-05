<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'Mysterious Potion',
                'price' => 1000,
                'image' => 'img/item-potion1.png',
                'type' => 'potion',
            ],
            [
                'name' => 'Healing Potion',
                'price' => 250,
                'image' => 'img/item-potion2.png',
                'type' => 'potion',
            ],
            [
                'name' => 'Resistance Potion',
                'price' => 80,
                'image' => 'img/item-potion3.png',
                'type' => 'potion',
            ],
            [
                'name' => 'Speed Potion',
                'price' => 125,
                'image' => 'img/item-potion4.png',
                'type' => 'potion',
            ],
            [
                'name' => 'Growth Potion',
                'price' => 150,
                'image' => 'img/item-potion5.png',
                'type' => 'potion',
            ],
            [
                'name' => 'Water Potion',
                'price' => 25,
                'image' => 'img/item-potion6.png',
                'type' => 'potion',
            ],
            [
                'name' => 'item 1',
                'price' => 75,
                'image' => 'img/item1.png',
                'type' => 'potion',
            ],
            [
                'name' => 'item 2',
                'price' => 75,
                'image' => 'img/item2.png',
                'type' => 'potion',
            ],
            [
                'name' => 'item 3',
                'price' => 75,
                'image' => 'img/item3.png',
                'type' => 'potion',
            ],
            [
                'name' => 'item 4',
                'price' => 75,
                'image' => 'img/item4.png',
                'type' => 'potion',
            ],
            [
                'name' => 'spideyFlower',
                'price' => 100,
                'image' => 'img/spideyFlower.png',
                'type' => 'seed',
            ],
            [
                'name' => 'squidPumpkin',
                'price' => 100,
                'image' => 'img/seed2.png',
                'type' => 'seed',
            ],
            [
                'name' => 'plantaPiranha',
                'price' => 150,
                'image' => 'img/seed3.png',
                'type' => 'seed',
            ],
            [
                'name' => 'bucket',
                'price' => 100,
                'image' => 'img/BucketBtn.png',
                'type' => 'tool',
            ],
            [
                'name' => 'fertilizer',
                'price' => 150,
                'image' => 'img/FertilizerBtn.png',
                'type' => 'tool',
            ],
            [
                'name' => 'spiderweb',
                'price' => 50,
                'image' => 'img/item-spiderweb.png',
                'type' => 'potion',
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}