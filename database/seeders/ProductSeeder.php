<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Realistic example data
        $products = [
            [
                'name' => 'Apple iPhone 15',
                'price' => 999.99,
                'description' => 'Latest Apple iPhone 15 with a 6.1-inch display, A16 Bionic chip, and 5G support.',
                // 'category' => 'Smartphones',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Samsung Galaxy S23',
                'price' => 849.99,
                'description' => 'The Samsung Galaxy S23 features a 6.2-inch Dynamic AMOLED display and a powerful Snapdragon 8 Gen 2 chipset.',
                // 'category' => 'Smartphones',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'price' => 349.99,
                'description' => 'Premium noise-canceling over-ear headphones from Sony, with industry-leading sound quality and battery life.',
                // 'category' => 'Headphones',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dell XPS 13',
                'price' => 1199.99,
                'description' => 'A powerful and compact 13-inch laptop with Intel Core i7, 16GB RAM, and a 512GB SSD.',
                // 'category' => 'Laptops',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nike Air Max 270',
                'price' => 149.99,
                'description' => 'Stylish and comfortable sneakers from Nike, featuring a large Air unit for maximum comfort.',
                // 'category' => 'Footwear',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'LG 55-inch OLED TV',
                'price' => 1399.99,
                'description' => '55-inch OLED Smart TV with stunning picture quality, built-in Google Assistant, and 4K resolution.',
                // 'category' => 'Electronics',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Instant Pot Duo 7-in-1',
                'price' => 89.99,
                'description' => 'Versatile kitchen appliance that functions as a pressure cooker, slow cooker, rice cooker, and more.',
                // 'category' => 'Kitchen Appliances',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fitbit Charge 5',
                'price' => 129.99,
                'description' => 'Advanced fitness tracker with built-in GPS, heart rate monitor, and sleep tracking features.',
                // 'category' => 'Wearables',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'GoPro HERO11 Black',
                'price' => 499.99,
                'description' => 'Action camera with 5.3K video, HyperSmooth stabilization, and rugged waterproof design.',
                // 'category' => 'Cameras',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bose SoundLink Revolve+',
                'price' => 299.99,
                'description' => 'Portable Bluetooth speaker with 360-degree sound and up to 16 hours of battery life.',
                // 'category' => 'Speakers',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert data into the products table
        foreach ($products as $product) {
            DB::table('products')->insert($product);
        }
    }
}
