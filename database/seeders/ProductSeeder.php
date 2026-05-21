<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Kategori Awal secara Dinamis
        $categories = [
            'Dimsum' => Category::updateOrCreate(['slug' => 'dimsum'], ['name' => 'Dimsum']),
            'Minuman' => Category::updateOrCreate(['slug' => 'minuman'], ['name' => 'Minuman']),
            'Snack' => Category::updateOrCreate(['slug' => 'snack'], ['name' => 'Snack']),
        ];

        // 2. Buat Daftar Produk yang Dihubungkan ke Kategori di atas
        $products = [
            [
                'name' => 'Siomay Ayam Premium (4pcs)',
                'price' => 22000.00,
                'stock' => 50,
                'category_id' => $categories['Dimsum']->id,
                'image_url' => 'https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=500&q=80',
            ],
            [
                'name' => 'Hakau Udang Kristal (4pcs)',
                'price' => 26000.00,
                'stock' => 40,
                'category_id' => $categories['Dimsum']->id,
                'image_url' => 'https://images.unsplash.com/photo-1496116211227-7d31975e8841?auto=format&fit=crop&w=500&q=80',
            ],
            [
                'name' => 'Lumpia Kulit Tahu Goreng (3pcs)',
                'price' => 21000.00,
                'stock' => 45,
                'category_id' => $categories['Dimsum']->id,
                'image_url' => 'https://images.unsplash.com/photo-1617093727343-374698b1b08d?auto=format&fit=crop&w=500&q=80',
            ],
            [
                'name' => 'Es Teh Liang / Liang Cha',
                'price' => 8000.00,
                'stock' => 100,
                'category_id' => $categories['Minuman']->id,
                'image_url' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=500&q=80',
            ],
            [
                'name' => 'Oolong Tea Hangat (Pot)',
                'price' => 15000.00,
                'stock' => 80,
                'category_id' => $categories['Minuman']->id,
                'image_url' => 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?auto=format&fit=crop&w=500&q=80',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => Str::slug($product['name'])],
                [
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'category_id' => $product['category_id'],
                    'image_url' => $product['image_url'],
                    'is_active' => true,
                ]
            );
        }
    }
}
