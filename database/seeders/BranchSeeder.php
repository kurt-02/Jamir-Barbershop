<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Branch::create([
            'name' => 'Branch 1',
            'address' => 'SLF Building',
        ]);

        Branch::create([
            'name' => 'Branch 2',
            'address' => 'Carmella',
        ]);
    }
}
