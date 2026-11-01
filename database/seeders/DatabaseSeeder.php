<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['name' => 'Owner Toko', 'email' => 'owner@tokoritai.com', 'password' => Hash::make('password123'), 'role' => 'owner']);
        User::create(['name' => 'Kasir Staff', 'email' => 'staff@tokoritai.com', 'password' => Hash::make('password123'), 'role' => 'staff']);
        User::create(['name' => 'Budi Customer', 'email' => 'budi@gmail.com', 'password' => Hash::make('password123'), 'role' => 'customer']);

        $makanan = Category::create(['name' => 'Makanan & Camilan']);
        $minuman = Category::create(['name' => 'Minuman']);
        $elektronik = Category::create(['name' => 'Aksesoris Digital']);
        $sembako = Category::create(['name' => 'Sembako']);

        $products = [
            ['category_id' => $makanan->id, 'sku' => 'SNK-001', 'name' => 'Keripik Kentang Lays 68g', 'purchase_price' => 8000, 'selling_price' => 12500, 'stock' => 45, 'minimum_stock' => 10, 'image' => 'https://images.unsplash.com/photo-1566478989037-eec170784d0b?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $makanan->id, 'sku' => 'SNK-002', 'name' => 'Biskuit Oreo Original 133g', 'purchase_price' => 7000, 'selling_price' => 10500, 'stock' => 30, 'minimum_stock' => 8, 'image' => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $minuman->id, 'sku' => 'DRK-001', 'name' => 'Teh Botol Sosro 350ml', 'purchase_price' => 3000, 'selling_price' => 5000, 'stock' => 4, 'minimum_stock' => 10, 'image' => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $minuman->id, 'sku' => 'DRK-002', 'name' => 'Kopi Nescafe Can 240ml', 'purchase_price' => 8500, 'selling_price' => 11000, 'stock' => 25, 'minimum_stock' => 5, 'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $minuman->id, 'sku' => 'DRK-003', 'name' => 'Air Mineral Le Minerale 600ml', 'purchase_price' => 2000, 'selling_price' => 3500, 'stock' => 100, 'minimum_stock' => 20, 'image' => 'https://images.unsplash.com/photo-1523362628745-0c100150b504?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $elektronik->id, 'sku' => 'ELC-001', 'name' => 'Kabel Data Type-C Fast Charge', 'purchase_price' => 15000, 'selling_price' => 35000, 'stock' => 3, 'minimum_stock' => 5, 'image' => 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $elektronik->id, 'sku' => 'ELC-002', 'name' => 'Earphone Bass Boost 3.5mm', 'purchase_price' => 22000, 'selling_price' => 45000, 'stock' => 12, 'minimum_stock' => 5, 'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&auto=format&fit=crop&q=80'],
            ['category_id' => $sembako->id, 'sku' => 'SBK-001', 'name' => 'Minyak Goreng Bimoli 1L', 'purchase_price' => 16500, 'selling_price' => 20000, 'stock' => 15, 'minimum_stock' => 5, 'image' => 'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?w=400&auto=format&fit=crop&q=80'],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(['sku' => $p['sku']], $p);
        }
    }
}