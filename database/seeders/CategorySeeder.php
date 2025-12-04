<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Pakan Kucing Premium',
            'Pakan Anjing Aktif',
            'Vitamin & Suplemen',
            'Aksesori Grooming',
            'Mainan Interaktif',
            'Perawatan Kesehatan',
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(['name' => $name]);
        }
    }
}
