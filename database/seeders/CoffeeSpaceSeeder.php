<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CoffeeSpace;

class CoffeeSpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CoffeeSpace::insert([
            ['name' => 'Espaço Café 1', 'capacity' => 15],
            ['name' => 'Espaço Café 2', 'capacity' => 15],
        ]);
    }
}
