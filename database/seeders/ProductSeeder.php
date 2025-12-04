<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Category::count() === 0) {
            $this->call(CategorySeeder::class);
        }

        if (Discount::count() === 0) {
            $this->call(DiscountSeeder::class);
        }

        $categories = Category::all();

        foreach ($categories as $category) {
            Product::factory()
                ->count(4)
                ->state([
                    'category_id' => $category->id,
                    'is_enabled' => true,
                ])
                ->create();
        }

        Product::factory()
            ->count(5)
            ->state([
                'category_id' => $categories->random()->id,
                'is_enabled' => false,
            ])
            ->create();
    }
}
