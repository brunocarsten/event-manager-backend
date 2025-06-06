<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Room::insert([
            ['name' => 'Sala A', 'capacity' => 10],
            ['name' => 'Sala B', 'capacity' => 8],
            ['name' => 'Sala C', 'capacity' => 12],
        ]);
    }
}
