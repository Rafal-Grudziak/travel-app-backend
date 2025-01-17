<?php

namespace Database\Seeders;

use App\Models\TravelCategory;
use Illuminate\Database\Seeder;

class TravelCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TravelCategory::insert([
            ['name' => 'Mountains'],
            ['name' => 'Lakes'],
            ['name' => 'Forests'],
            ['name' => 'Beaches'],
            ['name' => 'Deserts'],
            ['name' => 'Islands'],
            ['name' => 'National Parks'],
            ['name' => 'Natural Reserves'],
            ['name' => 'Jungles'],
            ['name' => 'Waterfalls'],
            ['name' => 'Seas'],
            ['name' => 'Cities'],
            ['name' => 'Villages'],
            ['name' => 'Museums'],
            ['name' => 'Theatres'],
            ['name' => 'Historic places'],
            ['name' => 'Monuments'],
            ['name' => 'Temples'],
            ['name' => 'Churches'],
            ['name' => 'Palaces'],
            ['name' => 'Castles'],
            ['name' => 'Art Galleries'],
            ['name' => 'Festivals'],
            ['name' => 'Cathedrals'],
            ['name' => 'Main Square'],
            ['name' => 'Cafe'],
            ['name' => 'Restaurant'],
            ['name' => 'Bars'],
            ['name' => 'Bakeries'],
            ['name' => 'Zoos'],
            ['name' => 'Gardens'],
            ['name' => 'Sports Arenas'],
            ['name' => 'Beaches'],
            ['name' => 'Parks'],
            ['name' => 'Hiking Trails'],
            ['name' => 'Train Stations'],
            ['name' => 'Ports'],
            ['name' => 'Bus stations'],
        ]);
    }
}
