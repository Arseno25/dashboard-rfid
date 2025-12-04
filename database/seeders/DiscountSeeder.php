<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Discount::updateOrCreate(
            ['name' => 'Promo Musim Hujan'],
            ['percentage' => 15, 'status' => 'active']
        );

        Discount::updateOrCreate(
            ['name' => 'Flash Sale Weekend'],
            ['percentage' => 25, 'status' => 'scheduled']
        );
    }
}
