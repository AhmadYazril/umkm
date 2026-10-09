<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            ['icon' => 'zap',       'label' => 'Banyak Colokan',       'sort_order' => 1],
            ['icon' => 'wifi',      'label' => 'WiFi Kencang',         'sort_order' => 2],
            ['icon' => 'bath',      'label' => 'Toilet Bersih',        'sort_order' => 3],
            ['icon' => 'moon',      'label' => 'Mushola',              'sort_order' => 4],
            ['icon' => 'laptop',    'label' => 'Cocok untuk WFC',      'sort_order' => 5],
            ['icon' => 'coffee',    'label' => 'Minuman Variatif',     'sort_order' => 6],
            ['icon' => 'parking',   'label' => 'Area Parkir',         'sort_order' => 7],
        ];

        foreach ($facilities as $facility) {
            Facility::updateOrCreate(
                ['label' => $facility['label']],
                ['icon' => $facility['icon'], 'sort_order' => $facility['sort_order'], 'is_active' => true]
            );
        }
    }
}
