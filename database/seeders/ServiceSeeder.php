<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Service::create([
            'name' => 'Regular Haircut',
            'price' => '130',
            'duration_minutes' => 30,
            'dropdown_options' => ['High Fade', 'Mid Fade', 'Taper Fade', 'Burst Fade', 'Classic Haircut', 'Undecided'],
        ]);

        Service::create([
            'name' => 'Kids Haircut',
            'price' => '150',
            'duration_minutes' => 60,
            'dropdown_options' => ['High Fade', 'Mid Fade', 'Taper Fade', 'Burst Fade', 'Classic Haircut', 'Undecided'],
        ]);

        Service::create([
            'name' => 'Hair Color',
            'price' => '200',
            'duration_minutes' => 40,
            'dropdown_options' => ['Black', 'Brown', 'Blonde', 'Red', 'Undecided'],
        ]);

        Service::create([
            'name' => 'Beard Trim',
            'duration_minutes' => 10,
            'price' => '40',
        ]);

        Service::create([
            'name' => 'Full Shave',
            'duration_minutes' => 20,
            'price' => '70',
        ]);
    }
}
