<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            ['number' => 'A1', 'capacity' => 2],
            ['number' => 'A2', 'capacity' => 2],
            ['number' => 'A3', 'capacity' => 2],
            ['number' => 'B1', 'capacity' => 4],
            ['number' => 'B2', 'capacity' => 4],
            ['number' => 'B3', 'capacity' => 4],
            ['number' => 'C1', 'capacity' => 6],
            ['number' => 'C2', 'capacity' => 6],
            ['number' => 'VIP', 'capacity' => 8],
        ];

        foreach ($tables as $table) {
            Table::updateOrCreate(
                ['number' => $table['number']],
                ['capacity' => $table['capacity'], 'is_active' => true]
            );
        }
    }
}
