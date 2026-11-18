<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Table as RestaurantTable;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@restaurant.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        // Create Kasir User
        User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@restaurant.com',
            'password' => Hash::make('kasir123'),
            'role' => 'kasir',
            'phone' => '081234567891',
            'is_active' => true,
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Makanan', 'description' => 'Menu makanan utama', 'is_active' => true],
            ['name' => 'Minuman', 'description' => 'Menu minuman', 'is_active' => true],
            ['name' => 'Snack', 'description' => 'Menu snack dan cemilan', 'is_active' => true],
            ['name' => 'Dessert', 'description' => 'Menu penutup', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create Products
        $products = [
            ['category_id' => 1, 'name' => 'Nasi Goreng', 'description' => 'Nasi goreng spesial dengan telur', 'price' => 25000, 'cost' => 10000, 'stock' => 100, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Mie Goreng', 'description' => 'Mie goreng pedas', 'price' => 20000, 'cost' => 8000, 'stock' => 100, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Ayam Bakar', 'description' => 'Ayam bakar dengan sambal', 'price' => 30000, 'cost' => 15000, 'stock' => 50, 'is_available' => true],
            ['category_id' => 2, 'name' => 'Es Teh Manis', 'description' => 'Teh manis dingin', 'price' => 5000, 'cost' => 2000, 'stock' => 200, 'is_available' => true],
            ['category_id' => 2, 'name' => 'Es Jeruk', 'description' => 'Jeruk peras segar', 'price' => 8000, 'cost' => 3000, 'stock' => 150, 'is_available' => true],
            ['category_id' => 2, 'name' => 'Kopi Hitam', 'description' => 'Kopi hitam panas', 'price' => 10000, 'cost' => 4000, 'stock' => 100, 'is_available' => true],
            ['category_id' => 3, 'name' => 'Kentang Goreng', 'description' => 'Kentang goreng crispy', 'price' => 15000, 'cost' => 6000, 'stock' => 80, 'is_available' => true],
            ['category_id' => 4, 'name' => 'Es Krim', 'description' => 'Es krim vanilla', 'price' => 12000, 'cost' => 5000, 'stock' => 60, 'is_available' => true],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Create Tables
        for ($i = 1; $i <= 10; $i++) {
            RestaurantTable::create([
                'table_number' => 'T' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'capacity' => ($i % 3 == 0) ? 6 : (($i % 2 == 0) ? 2 : 4),
                'status' => 'available',
            ]);
        }
    }
}
