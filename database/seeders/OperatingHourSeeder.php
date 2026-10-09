<?php

namespace Database\Seeders;

use App\Models\OperatingHour;
use Illuminate\Database\Seeder;

class OperatingHourSeeder extends Seeder
{
    public function run(): void
    {
        // 0=Minggu, 1=Senin, 2=Selasa, 3=Rabu, 4=Kamis, 5=Jumat, 6=Sabtu
        $hours = [
            ['day_of_week' => 0, 'day_name' => 'Minggu',  'open_time' => '11:00', 'close_time' => '21:30', 'is_closed' => false],
            ['day_of_week' => 1, 'day_name' => 'Senin',   'open_time' => null,    'close_time' => null,    'is_closed' => true],
            ['day_of_week' => 2, 'day_name' => 'Selasa',  'open_time' => '11:00', 'close_time' => '20:30', 'is_closed' => false],
            ['day_of_week' => 3, 'day_name' => 'Rabu',    'open_time' => '11:00', 'close_time' => '20:30', 'is_closed' => false],
            ['day_of_week' => 4, 'day_name' => 'Kamis',   'open_time' => '11:00', 'close_time' => '20:30', 'is_closed' => false],
            ['day_of_week' => 5, 'day_name' => 'Jumat',   'open_time' => '11:00', 'close_time' => '20:30', 'is_closed' => false],
            ['day_of_week' => 6, 'day_name' => 'Sabtu',   'open_time' => '11:00', 'close_time' => '21:30', 'is_closed' => false],
        ];

        foreach ($hours as $hour) {
            OperatingHour::updateOrCreate(
                ['day_of_week' => $hour['day_of_week']],
                $hour
            );
        }
    }
}
