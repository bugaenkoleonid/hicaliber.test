<?php

namespace Database\Seeders;

use App\Models\House;
use Illuminate\Database\Seeder;

class HouseSeeder extends Seeder
{
    public function run()
    {
        $houses = [
            [
                'name' => 'The Victoria',
                'price' => 374662,
                'bedrooms' => 4,
                'bathrooms' => 2,
                'storeys' => 2,
                'garages' => 2,
            ],
            [
                'name' => 'The Xavier',
                'price' => 513268,
                'bedrooms' => 4,
                'bathrooms' => 2,
                'storeys' => 1,
                'garages' => 2,
            ],
            // ... добавьте остальные дома
        ];

        foreach ($houses as $house) {
            House::create($house);
        }
    }
} 