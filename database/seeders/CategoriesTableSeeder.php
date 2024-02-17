<?php

namespace Database\Seeders;

use App\Models\Category;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Add two manual records
        Category::create([
            'name' => 'Electronics',
        ]);

        Category::create([
            'name' => 'Clothing',
        ]);

        Category::create([
            'name' => 'Accessories',
        ]);
    }
}
