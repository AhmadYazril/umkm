<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuOption;
use Illuminate\Database\Seeder;

class MenuOptionSeeder extends Seeder
{
    public function run(): void
    {
        // Opsi suhu untuk kopi & non-kopi
        $beverages = Menu::whereHas('category', fn($q) => $q->whereIn('slug', ['kopi', 'non-kopi']))->get();
        foreach ($beverages as $menu) {
            MenuOption::updateOrCreate(
                ['menu_id' => $menu->id, 'name' => 'Hot', 'type' => 'variant'],
                ['price' => 0, 'is_sample' => false]
            );
            MenuOption::updateOrCreate(
                ['menu_id' => $menu->id, 'name' => 'Iced', 'type' => 'variant'],
                ['price' => 0, 'is_sample' => false]
            );
        }

        // Level gula untuk semua minuman
        $sugarLevels = ['0% (Tanpa Gula)', '25% (Sedikit Manis)', '50% (Sedang)', '75% (Manis)', '100% (Penuh)'];
        foreach ($beverages as $menu) {
            foreach ($sugarLevels as $level) {
                MenuOption::updateOrCreate(
                    ['menu_id' => $menu->id, 'name' => $level, 'type' => 'addon'],
                    ['price' => 0, 'is_sample' => false]
                );
            }
        }

        // Extra shot espresso (untuk kopi)
        $coffees = Menu::whereHas('category', fn($q) => $q->where('slug', 'kopi'))->get();
        foreach ($coffees as $menu) {
            MenuOption::updateOrCreate(
                ['menu_id' => $menu->id, 'name' => 'Extra Shot', 'type' => 'addon'],
                ['price' => 5000, 'is_sample' => false]
            );
        }
    }
}
