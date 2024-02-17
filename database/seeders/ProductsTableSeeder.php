<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {// Add two manual records
        Product::create([
            'name' => 'Smartphone',
            'description' => 'High-end smartphone',
            'price' => 799.99,
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'Laptop',
            'description' => 'Powerful laptop for professionals',
            'price' => 1499.99,
            'category_id' => 1,
        ]);

    }
}
