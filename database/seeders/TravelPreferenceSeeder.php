<?php

namespace Database\Seeders;

use App\Models\TravelPreference;
use Illuminate\Database\Seeder;

class TravelPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TravelPreference::insert([
            ['name' => 'Solo travel'],
            ['name' => 'Family travel'],
            ['name' => 'Group travel'],
            ['name' => 'Last minute travel'],
            ['name' => 'Train travel'],
            ['name' => 'Bike travel'],
            ['name' => 'Car travel'],
            ['name' => 'Air travel'],
            ['name' => 'Sea travel'],
            ['name' => 'Hiking'],
            ['name' => 'Recreation travel'],
            ['name' => 'Cultural travel'],
            ['name' => 'Natural areas'],
            ['name' => 'Mountains'],
            ['name' => 'Lakes'],
            ['name' => 'Seas'],
            ['name' => 'National Parks'],
            ['name' => 'Deserts'],
            ['name' => 'Islands'],
            ['name' => 'Cities'],
            ['name' => 'Villages'],
        ]);
    }
}
