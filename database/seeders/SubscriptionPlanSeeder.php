<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'name_bn' => 'ফ্রি',
                'slug' => 'free',
                'description' => 'Perfect for getting started. List up to 10 products with basic features.',
                'description_bn' => 'শুরু করার জন্য উপযুক্ত। ১০টি পর্যন্ত পণ্য তালিকাভুক্ত করুন।',
                'price' => 0,
                'annual_price' => 0,
                'commission_rate' => 15.00,
                'commission_type' => 'percentage',
                'product_limit' => 10,
                'image_per_product' => 5,
                'storage_limit_mb' => 100,
                'features' => json_encode([
                    'Up to 10 products',
                    '5 images per product',
                    'Basic analytics',
                    'Email support',
                    '15% commission per sale',
                    '100MB storage for digital products',
                ]),
                'features_bn' => json_encode([
                    '১০টি পর্যন্ত পণ্য',
                    'প্রতি পণ্যে ৫টি ছবি',
                    'মৌলিক বিশ্লেষণ',
                    'ইমেইল সহায়তা',
                    'প্রতি বিক্রয়ে ১৫% কমিশন',
                    'ডিজিটাল পণ্যের জন্য ১০০এমবি স্টোরেজ',
                ]),
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 1,
                'trial_days' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Basic',
                'name_bn' => 'বেসিক',
                'slug' => 'basic',
                'description' => 'Great for growing businesses. List up to 100 products with advanced features.',
                'description_bn' => 'ক্রমবর্ধমান ব্যবসার জন্য দুর্দান্ত। ১০০টি পর্যন্ত পণ্য তালিকাভুক্ত করুন।',
                'price' => 500,
                'annual_price' => 5000, // ৳500 discount on annual
                'commission_rate' => 10.00,
                'commission_type' => 'percentage',
                'product_limit' => 100,
                'image_per_product' => 10,
                'storage_limit_mb' => 1000,
                'features' => json_encode([
                    'Up to 100 products',
                    '10 images per product',
                    'Advanced analytics',
                    'Priority email support',
                    '10% commission per sale',
                    '1GB storage for digital products',
                    'Featured store badge',
                    'Custom store URL',
                ]),
                'features_bn' => json_encode([
                    '১০০টি পর্যন্ত পণ্য',
                    'প্রতি পণ্যে ১০টি ছবি',
                    'উন্নত বিশ্লেষণ',
                    'অগ্রাধিকার ইমেইল সহায়তা',
                    'প্রতি বিক্রয়ে ১০% কমিশন',
                    'ডিজিটাল পণ্যের জন্য ১জিবি স্টোরেজ',
                    'ফিচারড স্টোর ব্যাজ',
                    'কাস্টম স্টোর URL',
                ]),
                'is_active' => true,
                'is_popular' => true, // Most popular badge
                'sort_order' => 2,
                'trial_days' => 14, // 14-day free trial
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pro',
                'name_bn' => 'প্রো',
                'slug' => 'pro',
                'description' => 'For established businesses. Unlimited products with premium features.',
                'description_bn' => 'প্রতিষ্ঠিত ব্যবসার জন্য। প্রিমিয়াম বৈশিষ্ট্য সহ সীমাহীন পণ্য।',
                'price' => 1500,
                'annual_price' => 15000, // ৳3,000 discount on annual
                'commission_rate' => 8.00,
                'commission_type' => 'percentage',
                'product_limit' => null, // Unlimited
                'image_per_product' => 15,
                'storage_limit_mb' => 5000,
                'features' => json_encode([
                    'Unlimited products',
                    '15 images per product',
                    'Real-time analytics & reports',
                    '24/7 phone + email support',
                    '8% commission per sale',
                    '5GB storage for digital products',
                    'Featured store placement',
                    'Custom domain support',
                    'Auto-approve products',
                    'Bulk upload tools',
                    'API access',
                ]),
                'features_bn' => json_encode([
                    'সীমাহীন পণ্য',
                    'প্রতি পণ্যে ১৫টি ছবি',
                    'রিয়েল-টাইম বিশ্লেষণ ও রিপোর্ট',
                    '২৪/৭ ফোন + ইমেইল সহায়তা',
                    'প্রতি বিক্রয়ে ৮% কমিশন',
                    'ডিজিটাল পণ্যের জন্য ৫জিবি স্টোরেজ',
                    'ফিচারড স্টোর প্লেসমেন্ট',
                    'কাস্টম ডোমেইন সাপোর্ট',
                    'স্বয়ংক্রিয় পণ্য অনুমোদন',
                    'বাল্ক আপলোড টুলস',
                    'API অ্যাক্সেস',
                ]),
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 3,
                'trial_days' => 30, // 30-day free trial
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Enterprise',
                'name_bn' => 'এন্টারপ্রাইজ',
                'slug' => 'enterprise',
                'description' => 'Custom solutions for large businesses. Contact us for pricing.',
                'description_bn' => 'বড় ব্যবসার জন্য কাস্টম সমাধান। মূল্যের জন্য আমাদের সাথে যোগাযোগ করুন।',
                'price' => 0, // Custom pricing
                'annual_price' => null,
                'commission_rate' => 5.00,
                'commission_type' => 'percentage',
                'product_limit' => null, // Unlimited
                'image_per_product' => 20,
                'storage_limit_mb' => null, // Unlimited
                'features' => json_encode([
                    'Everything in Pro',
                    'Custom commission rates',
                    'Dedicated account manager',
                    'Custom integrations',
                    'White-label options',
                    'Advanced API access',
                    'Custom analytics dashboards',
                    'Training & onboarding',
                    'SLA guarantee',
                ]),
                'features_bn' => json_encode([
                    'প্রো-তে সব কিছু',
                    'কাস্টম কমিশন রেট',
                    'ডেডিকেটেড অ্যাকাউন্ট ম্যানেজার',
                    'কাস্টম ইন্টিগ্রেশন',
                    'হোয়াইট-লেবেল অপশন',
                    'উন্নত API অ্যাক্সেস',
                    'কাস্টম অ্যানালিটিক্স ড্যাশবোর্ড',
                    'প্রশিক্ষণ ও অনবোর্ডিং',
                    'SLA গ্যারান্টি',
                ]),
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 4,
                'trial_days' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('subscription_plans')->insert($plans);

        $this->command->info('✅ Subscription plans seeded successfully!');
    }
}