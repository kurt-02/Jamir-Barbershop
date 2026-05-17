<?php

namespace Database\Seeders;

use App\Models\Barber;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BarberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //branch 1
        Barber::create([
            'name' => 'Barber Archie',
            'branch_id' => 1,
            'photo' => 'images/barbers/BarberArchie.png',
            'days_off' => ['Tuesday'],
            'specialties' => ['Classic Haircut', 'Mid Fade'],
        ]);
        Barber::create([
            'name' => 'Barber Ricky',
            'branch_id' => 1,
            'photo' => 'images/barbers/BarberRicky.png',
            'days_off' => ['Thursday'],
            'specialties' => ['High Fade', 'Mid Fade', 'Taper Fade'],
        ]);

        //branch 2
        Barber::create([
            'name' => 'Barber Gian',
            'branch_id' => 2,
            'photo' => 'images/barbers/BarberGian.jpg',
            'days_off' => ['Monday'],
            'specialties' => ['Taper Fade', 'Burst Fade', 'Mid Fade'],
        ]);
        Barber::create([
            'name' => 'Barber Jerome',
            'branch_id' => 2,
            'photo' => 'images/barbers/BarberMark.jpg',
            'days_off' => ['Friday'],
        ]);

    }
}
