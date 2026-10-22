<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Espresso',
                'category' => 'Kopi',
                'price' => 12000,
                'description' => 'Espresso pekat dengan karakter rasa kuat.',
                'image_path' => 'images/main-2.jpg',
            ],
            [
                'name' => 'Americano',
                'category' => 'Kopi',
                'price' => 15000,
                'description' => 'Kopi hitam dengan rasa tegas dan menyegarkan.',
                'image_path' => 'images/main-5.jpg',
            ],
            [
                'name' => 'Kopi Susu Gula Aren',
                'category' => 'Kopi',
                'price' => 18000,
                'description' => 'Espresso, susu creamy, dan gula aren.',
                'image_path' => 'images/main-6.jpg',
            ],
            [
                'name' => 'Cafe Latte',
                'category' => 'Kopi',
                'price' => 20000,
                'description' => 'Espresso dengan susu lembut dan foam tipis.',
                'image_path' => 'images/main-3.jpg',
            ],
            [
                'name' => 'Cappuccino',
                'category' => 'Kopi',
                'price' => 20000,
                'description' => 'Espresso dengan foam susu yang lembut.',
                'image_path' => 'images/main-4.jpg',
            ],
        ];

        foreach ($products as $product) {
            Product::query()->firstOrCreate(
                ['name' => $product['name']],
                $product + ['is_available' => true],
            );
        }
    }
}
