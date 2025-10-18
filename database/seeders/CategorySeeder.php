<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $categories = [
            // Electronics & Gadgets
            [
                'name' => 'Electronics',
                'name_bn' => 'ইলেকট্রনিক্স',
                'slug' => 'electronics',
                'description' => 'Smartphones, laptops, tablets, cameras, and accessories',
                'icon' => 'laptop',
                'is_active' => true,
                'sort_order' => 1,
            ],
            
            // Fashion & Apparel
            [
                'name' => 'Fashion & Apparel',
                'name_bn' => 'ফ্যাশন ও পোশাক',
                'slug' => 'fashion-apparel',
                'description' => 'Clothing, shoes, bags, jewelry, and fashion accessories',
                'icon' => 'shirt',
                'is_active' => true,
                'sort_order' => 2,
            ],
            
            // Home & Living
            [
                'name' => 'Home & Living',
                'name_bn' => 'ঘর ও জীবনযাপন',
                'slug' => 'home-living',
                'description' => 'Furniture, home decor, kitchenware, and appliances',
                'icon' => 'home',
                'is_active' => true,
                'sort_order' => 3,
            ],
            
            // Health & Beauty
            [
                'name' => 'Health & Beauty',
                'name_bn' => 'স্বাস্থ্য ও সৌন্দর্য',
                'slug' => 'health-beauty',
                'description' => 'Skincare, makeup, fragrances, and personal care',
                'icon' => 'sparkles',
                'is_active' => true,
                'sort_order' => 4,
            ],
            
            // Books & Stationery
            [
                'name' => 'Books & Stationery',
                'name_bn' => 'বই ও স্টেশনারি',
                'slug' => 'books-stationery',
                'description' => 'Books, magazines, school supplies, and office items',
                'icon' => 'book',
                'is_active' => true,
                'sort_order' => 5,
            ],
            
            // Sports & Outdoor
            [
                'name' => 'Sports & Outdoor',
                'name_bn' => 'ক্রীড়া ও বহিরঙ্গন',
                'slug' => 'sports-outdoor',
                'description' => 'Sports equipment, fitness gear, and outdoor accessories',
                'icon' => 'dumbbell',
                'is_active' => true,
                'sort_order' => 6,
            ],
            
            // Toys & Kids
            [
                'name' => 'Toys & Kids',
                'name_bn' => 'খেলনা ও শিশু',
                'slug' => 'toys-kids',
                'description' => 'Toys, games, baby products, and kids clothing',
                'icon' => 'baby',
                'is_active' => true,
                'sort_order' => 7,
            ],
            
            // Food & Groceries
            [
                'name' => 'Food & Groceries',
                'name_bn' => 'খাদ্য ও মুদি',
                'slug' => 'food-groceries',
                'description' => 'Fresh produce, packaged foods, beverages, and snacks',
                'icon' => 'shopping-cart',
                'is_active' => true,
                'sort_order' => 8,
            ],
            
            // Automotive
            [
                'name' => 'Automotive',
                'name_bn' => 'অটোমোটিভ',
                'slug' => 'automotive',
                'description' => 'Car accessories, bike parts, and vehicle care products',
                'icon' => 'car',
                'is_active' => true,
                'sort_order' => 9,
            ],
            
            // Digital Products
            [
                'name' => 'Digital Products',
                'name_bn' => 'ডিজিটাল পণ্য',
                'slug' => 'digital-products',
                'description' => 'Software, ebooks, courses, music, and digital downloads',
                'icon' => 'download',
                'is_active' => true,
                'sort_order' => 10,
            ],
            
            // Services
            [
                'name' => 'Services',
                'name_bn' => 'সেবা',
                'slug' => 'services',
                'description' => 'Professional services, consultations, and bookings',
                'icon' => 'briefcase',
                'is_active' => true,
                'sort_order' => 11,
            ],
            
            // Handicrafts
            [
                'name' => 'Handicrafts',
                'name_bn' => 'হস্তশিল্প',
                'slug' => 'handicrafts',
                'description' => 'Handmade items, traditional crafts, and artisan products',
                'icon' => 'palette',
                'is_active' => true,
                'sort_order' => 12,
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert(array_merge($category, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✅ Categories seeded successfully!');
        $this->command->info('📦 Total categories: ' . count($categories));
    }
}