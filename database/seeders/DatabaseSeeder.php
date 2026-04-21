<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a default admin user
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        // Create 5 categories, each with 3 products
        \App\Models\Category::factory(5)->create()->each(function ($category) {
            \App\Models\Product::factory(3)->create([
                'category_id' => $category->id,
            ]);
        });

        // Create 10 glasses and their stocks
        \App\Models\Glass::factory(10)->create()->each(function ($glass) {
            \App\Models\Stock::factory()->create([
                'glass_id' => $glass->id,
            ]);
        });

        // Create some sales (SellingProducts and Items)
        \App\Models\SellingProduct::factory(20)->create();

        // Create some glass usage records
        \App\Models\GlassUse::factory(15)->create();

        // Create some general images
        \App\Models\Image::factory(10)->create();
    }
}
