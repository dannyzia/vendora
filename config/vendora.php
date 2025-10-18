<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Platform Information
    |--------------------------------------------------------------------------
    */

    'platform' => [
        'name' => 'Vendora Bangladesh',
        'version' => '2.0',
        'launch_date' => '2025-10-16',
        'support_email' => env('SUPPORT_EMAIL', 'support@vendora.com.bd'),
        'support_phone' => env('SUPPORT_PHONE', '01700000000'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Commission Configuration
    |--------------------------------------------------------------------------
    | Based on PRD Section 5.8 - Commissions & Payouts
    */

    'commissions' => [
        // Default commission rate (percentage)
        'default_rate' => 15.0,

        // Commission types supported
        'types' => [
            'percentage' => true,   // 15% of sale price
            'flat' => true,         // Fixed amount per transaction
            'hybrid' => true,       // Combination (e.g., 10% + ৳20)
            'tiered' => true,       // Volume-based rates
        ],

        // Tiered commission structure (monthly sales volume)
        'tiers' => [
            ['min' => 0, 'max' => 10000, 'rate' => 15.0],      // 0-10K: 15%
            ['min' => 10001, 'max' => 50000, 'rate' => 12.0],  // 10K-50K: 12%
            ['min' => 50001, 'max' => null, 'rate' => 10.0],   // 50K+: 10%
        ],

        // Category-specific commission rates (override default)
        'categories' => [
            'electronics' => 12.0,
            'fashion' => 15.0,
            'digital_products' => 10.0,
            'services' => 20.0,
            'books' => 8.0,
        ],

        // Commission applies to
        'applies_to' => 'product_price_only', // Not shipping cost

        // Minimum commission per transaction
        'minimum_amount' => 10.0, // ৳10
    ],

    /*
    |--------------------------------------------------------------------------
    | Escrow Configuration
    |--------------------------------------------------------------------------
    | Money held before release to vendor
    */

    'escrow' => [
        // Escrow period by product type (days)
        'periods' => [
            'physical' => 7,    // 7 days after delivery
            'digital' => 3,     // 3 days after download
            'service' => 1,     // 24 hours after booking completion
        ],

        // Extended escrow if dispute/return pending
        'extended_period' => 30, // days

        // Auto-release after period with no disputes
        'auto_release' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Payout Configuration
    |--------------------------------------------------------------------------
    | Based on PRD Section 5.8
    */

    'payouts' => [
        // Payout methods
        'methods' => [
            'bank_transfer' => [
                'enabled' => true,
                'min_amount' => 1000,  // ৳1,000
                'max_amount' => null,   // No limit
                'fee_percentage' => 0,
                'processing_days' => 3, // 3-5 days
                'label' => 'Bank Transfer',
            ],
            'bkash' => [
                'enabled' => true,
                'min_amount' => 500,    // ৳500
                'max_amount' => 25000,  // ৳25,000 per transaction
                'fee_percentage' => 1.5,
                'processing_days' => 0, // Instant-24h
                'label' => 'bKash',
            ],
            'nagad' => [
                'enabled' => false,     // Future
                'min_amount' => 500,
                'max_amount' => 25000,
                'fee_percentage' => 1.5,
                'processing_days' => 0,
                'label' => 'Nagad',
            ],
        ],

        // Payout schedule options
        'schedules' => [
            'manual' => true,       // On-demand (max 2 per week)
            'weekly' => true,       // Every Monday
            'biweekly' => true,     // Every 1st and 15th
            'monthly' => true,      // Every 1st of month
        ],

        // Manual payout limits
        'manual_limit_per_week' => 2,

        // New vendor payout restrictions
        'new_vendor_restrictions' => [
            'enabled' => true,
            'period_days' => 30,            // First 30 days
            'max_amount_per_week' => 5000,  // ৳5,000/week
        ],

        // Hold payout scenarios
        'hold_scenarios' => [
            'account_under_investigation',
            'pending_disputes',
            'negative_balance',
            'kyc_incomplete',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Vendor Subscription Plans
    |--------------------------------------------------------------------------
    | Based on PRD Section 3.2
    */

    'subscription_plans' => [
        'free' => [
            'name' => 'Free',
            'name_bn' => 'ফ্রি',
            'price' => 0,
            'currency' => 'BDT',
            'billing_cycle' => 'monthly',
            'features' => [
                'max_products' => 10,
                'commission_rate' => 20.0,
                'analytics' => 'basic',
                'payout_frequency' => 'weekly',
                'priority_support' => false,
                'marketing_tools' => false,
                'auto_approve_products' => false,
            ],
        ],
        'basic' => [
            'name' => 'Basic',
            'name_bn' => 'বেসিক',
            'price' => 1500,
            'currency' => 'BDT',
            'billing_cycle' => 'monthly',
            'features' => [
                'max_products' => 50,
                'commission_rate' => 15.0,
                'analytics' => 'standard',
                'payout_frequency' => 'twice_weekly',
                'priority_support' => false,
                'marketing_tools' => false,
                'auto_approve_products' => false,
            ],
        ],
        'pro' => [
            'name' => 'Pro',
            'name_bn' => 'প্রো',
            'price' => 5000,
            'currency' => 'BDT',
            'billing_cycle' => 'monthly',
            'features' => [
                'max_products' => null, // Unlimited
                'commission_rate' => 10.0,
                'analytics' => 'advanced',
                'payout_frequency' => 'daily',
                'priority_support' => true,
                'marketing_tools' => true,
                'auto_approve_products' => true,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Trust Score System
    |--------------------------------------------------------------------------
    | Based on PRD Section 3.2
    */

    'trust_score' => [
        // Starting score for new vendors
        'starting_score' => 50,

        // Score range
        'min_score' => 0,
        'max_score' => 100,

        // Score adjustments
        'adjustments' => [
            'on_time_delivery' => 2,        // +2 per order
            'positive_review_5_star' => 5,  // +5 per 5-star review
            'quick_response' => 1,          // +1 for <2 hour response
            'low_dispute_rate' => 10,       // +10 monthly if <1%
            'late_shipping' => -3,          // -3 per late order
            'order_cancellation' => -5,     // -5 per cancellation
            'negative_review_1_star' => -5, // -5 per 1-star review
            'dispute_opened' => -10,        // -10 per dispute
        ],

        // Update frequency
        'update_frequency' => 'daily',

        // Score thresholds and badges
        'thresholds' => [
            [
                'min' => 80,
                'badge' => 'verified_seller',
                'badge_bn' => 'যাচাইকৃত বিক্রেতা',
                'benefits' => [
                    'featured_placement' => true,
                    'auto_approve_products' => true,
                ],
            ],
            [
                'min' => 60,
                'max' => 79,
                'badge' => 'trusted_seller',
                'badge_bn' => 'বিশ্বস্ত বিক্রেতা',
                'benefits' => [],
            ],
            [
                'min' => 40,
                'max' => 59,
                'badge' => 'standard_seller',
                'badge_bn' => 'স্ট্যান্ডার্ড বিক্রেতা',
                'benefits' => [],
            ],
            [
                'min' => 0,
                'max' => 39,
                'badge' => 'new_seller',
                'badge_bn' => 'নতুন বিক্রেতা',
                'benefits' => [],
                'restrictions' => [
                    'all_products_need_approval' => true,
                ],
            ],
        ],

        // Display publicly
        'public_display' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Product Configuration
    |--------------------------------------------------------------------------
    | Based on PRD Section 5.3
    */

    'products' => [
        // Product types
        'types' => ['physical', 'digital', 'service'],

        // Image requirements
        'images' => [
            'min_count' => 3,
            'max_count' => 10,
            'min_resolution' => [800, 800], // width, height
            'max_size_mb' => 5,
            'formats' => ['jpg', 'jpeg', 'png', 'webp'],
            'thumbnails' => [
                'small' => [200, 200],
                'medium' => [600, 600],
                'large' => [1200, 1200],
            ],
        ],

        // Auto-approval conditions
        'auto_approval' => [
            'enabled' => true,
            'conditions' => [
                'vendor_trust_score' => 75,     // Score >= 75
                'vendor_approved_products' => 20, // >= 20 approved products
                'max_price' => 5000,            // Price < ৳5,000
                'low_risk_categories' => ['books', 'stationery'],
            ],
        ],

        // Manual approval required
        'manual_approval_required' => [
            'first_products_count' => 10,       // First 10 from new vendors
            'high_value_threshold' => 20000,    // >= ৳20,000
            'sensitive_categories' => ['electronics', 'branded_items', 'health', 'cosmetics', 'food'],
        ],

        // Prohibited items
        'prohibited_items' => [
            'weapons', 'ammunition', 'explosives',
            'counterfeit', 'replica',
            'illegal_drugs',
            'adult_content',
            'live_animals',
            'tobacco',
            'prescription_medical',
            'stolen_goods',
            'hate_violence',
        ],

        // Digital product settings
        'digital' => [
            'max_file_size_mb' => 500,
            'default_download_limit' => 5,
            'default_access_period_days' => 30,
            'download_url_expiry_hours' => 1,
            'encryption_enabled' => true,
        ],

        // Physical product settings
        'physical' => [
            'track_inventory' => true,
            'auto_hide_out_of_stock' => true,
            'low_stock_threshold' => 5,
            'allow_backorders' => false,
        ],

        // Service/Booking settings
        'service' => [
            'require_time_slots' => true,
            'max_bookings_per_day' => 10,
            'cancellation_policy_required' => true,
            'reminder_hours' => [24, 1], // 24 hours and 1 hour before
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Gateway Configuration
    |--------------------------------------------------------------------------
    | Based on PRD Section 5.5
    */

    'payments' => [
        'gateways' => [
            'sslcommerz' => [
                'enabled' => env('SSLCOMMERZ_ENABLED', true),
                'name' => 'SSLCommerz',
                'fee_percentage' => 2.0,
                'supports' => ['cards', 'mobile_banking', 'internet_banking'],
                'emi_available' => true,
                'emi_months' => [3, 6, 9, 12],
                'settlement_days' => 1, // T+1
            ],
            'bkash' => [
                'enabled' => env('BKASH_ENABLED', true),
                'name' => 'bKash',
                'fee_percentage' => 1.5,
                'max_amount' => 25000,
                'settlement_days' => 1,
                'users_in_bd' => '60M+',
            ],
            'cod' => [
                'enabled' => true,
                'name' => 'Cash on Delivery',
                'name_bn' => 'ক্যাশ অন ডেলিভারি',
                'fee_flat' => 50,
                'fee_percentage' => 0,
                'max_amount' => 10000,
                'verification_required' => true,
                'confirmation_timeout_hours' => 24,
                'risk_mitigation' => [
                    'new_customer_max_orders' => 1,
                    'high_value_advance_percentage' => 20, // 20% advance for > ৳5,000
                    'high_value_threshold' => 5000,
                    'blacklist_after_fake_orders' => 3,
                ],
            ],
        ],

        // Payment failure recovery
        'failure_recovery' => [
            'stock_reserve_hours' => 24,
            'reminder_after_hours' => 6,
            'max_retry_attempts' => 3,
            'allow_method_change' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Shipping Configuration
    |--------------------------------------------------------------------------
    | Based on PRD Sections 5.16 and 7.4
    */

    'shipping' => [
        // Bangladesh shipping zones
        'zones' => [
            'dhaka_metro' => [
                'name' => 'Inside Dhaka',
                'name_bn' => 'ঢাকার ভিতরে',
                'base_rate' => 60,
                'delivery_days' => [1, 2],
                'areas' => ['Dhaka City Corporation areas'],
            ],
            'dhaka_suburbs' => [
                'name' => 'Dhaka Suburbs',
                'name_bn' => 'ঢাকা শহরতলী',
                'base_rate' => 80,
                'delivery_days' => [2, 3],
                'areas' => ['Gazipur', 'Narayanganj', 'Savar', 'Keraniganj'],
            ],
            'divisional_cities' => [
                'name' => 'Divisional Cities',
                'name_bn' => 'বিভাগীয় শহর',
                'base_rate' => 100,
                'delivery_days' => [3, 5],
                'cities' => ['Chittagong', 'Sylhet', 'Rajshahi', 'Khulna', 'Barisal', 'Rangpur', 'Mymensingh'],
            ],
            'district_towns' => [
                'name' => 'District Towns',
                'name_bn' => 'জেলা শহর',
                'base_rate' => 120,
                'delivery_days' => [4, 7],
            ],
            'remote_areas' => [
                'name' => 'Remote Areas',
                'name_bn' => 'প্রত্যন্ত এলাকা',
                'base_rate' => 150,
                'delivery_days' => [5, 10],
            ],
        ],

        // Weight-based pricing (incremental)
        'weight_pricing' => [
            ['max_kg' => 1, 'additional_cost' => 0],      // Up to 1kg: Base rate
            ['min_kg' => 1, 'max_kg' => 2, 'additional_cost' => 20],  // 1-2kg: +৳20
            ['min_kg' => 2, 'max_kg' => 5, 'additional_cost' => 50],  // 2-5kg: +৳50
            ['min_kg' => 5, 'additional_cost' => 100],    // 5+kg: +৳100
        ],

        // Supported couriers (Bangladesh)
        'couriers' => [
            'pathao' => [
                'name' => 'Pathao Courier',
                'enabled' => true,
                'api_integration' => true,
                'coverage' => 'nationwide',
            ],
            'redx' => [
                'name' => 'Redx',
                'enabled' => true,
                'api_integration' => true,
                'coverage' => 'nationwide',
            ],
            'paperfly' => [
                'name' => 'Paperfly',
                'enabled' => true,
                'api_integration' => true,
                'coverage' => 'major_cities',
            ],
            'sundarban' => [
                'name' => 'Sundarban Courier',
                'enabled' => true,
                'api_integration' => false,
                'coverage' => 'nationwide',
            ],
            'sa_paribahan' => [
                'name' => 'SA Paribahan',
                'enabled' => true,
                'api_integration' => false,
                'coverage' => 'selective',
            ],
            'manual' => [
                'name' => 'Manual/Custom',
                'enabled' => true,
                'api_integration' => false,
            ],
        ],

        // Vendor options
        'vendor_options' => [
            'custom_rates_per_zone' => true,
            'free_shipping_threshold' => true,
            'flat_rate_option' => true,
            'local_pickup' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Dispute Resolution
    |--------------------------------------------------------------------------
    | Based on PRD Section 5.13
    */

    'disputes' => [
        // Types of disputes
        'types' => [
            'not_received' => 'Product not received',
            'defective' => 'Product defective/damaged',
            'wrong_item' => 'Wrong item delivered',
            'not_as_described' => 'Not as described',
            'vendor_unresponsive' => 'Vendor unresponsive',
        ],

        // Timeline
        'customer_can_open_within_days' => 30, // After delivery
        'vendor_response_deadline_hours' => 48,
        'admin_decision_deadline_hours' => 72,

        // Auto-escalation
        'auto_escalate_if_no_vendor_response' => true,

        // Resolution types
        'resolution_types' => [
            'full_refund',
            'partial_refund',
            'no_refund',
            'replacement',
        ],

        // Impact on trust score
        'trust_score_impact' => -10, // Per dispute
    ],

    /*
    |--------------------------------------------------------------------------
    | Fraud Prevention
    |--------------------------------------------------------------------------
    | Based on PRD Section 5.14
    */

    'fraud_prevention' => [
        // Velocity checks
        'velocity' => [
            'max_orders_per_user_per_hour' => 5,
            'max_orders_same_address_per_day' => 3,
            'high_value_alert_threshold' => 50000, // ৳50,000 from new account
        ],

        // Risk scoring (0-100)
        'risk_thresholds' => [
            'green' => [0, 30],      // Auto-approve
            'yellow' => [31, 60],    // Optional review
            'orange' => [61, 80],    // Manual review required
            'red' => [81, 100],      // Block transaction
        ],

        // Vendor verification
        'vendor_verification' => [
            'email_required' => true,
            'phone_required' => true,
            'bank_account_verification' => true, // Micro-deposit
            'trade_license_verification' => true, // RJSC
            'new_vendor_payout_limit' => 5000,    // ৳5,000/week first month
        ],

        // Product listing fraud detection
        'product_listing' => [
            'ai_image_verification' => false,   // Future feature
            'price_anomaly_detection' => true,
            'prohibited_keyword_detection' => true,
            'brand_protection' => true,
        ],

        // Monitoring
        'monitor' => [
            'multiple_accounts_same_device' => true,
            'same_card_multiple_accounts' => true,
            'bulk_orders_same_item' => true,
            'unusual_location_changes' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Order Configuration
    |--------------------------------------------------------------------------
    */

    'orders' => [
        // Order number format
        'number_format' => 'VBD-{year}-{number}', // VBD-2025-00001

        // Order statuses
        'statuses' => [
            'pending',
            'payment_pending',
            'processing',
            'shipped',
            'delivered',
            'completed',
            'cancelled',
            'refunded',
        ],

        // Auto-cancel unpaid orders after
        'auto_cancel_unpaid_hours' => 24,

        // Customer can cancel within (minutes after order)
        'customer_cancel_window_minutes' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Review & Rating Configuration
    |--------------------------------------------------------------------------
    */

    'reviews' => [
        // Only verified purchases can review
        'verified_purchase_only' => true,

        // Review after delivery
        'can_review_after_days' => 0,  // Immediate
        'review_window_days' => 90,    // Within 90 days

        // Moderation
        'auto_approve' => false,
        'require_admin_approval' => true,

        // Rating scale
        'min_rating' => 1,
        'max_rating' => 5,

        // Review incentive
        'reward_points' => 10, // Future: loyalty points
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Configuration
    |--------------------------------------------------------------------------
    */

    'notifications' => [
        'channels' => [
            'email' => true,
            'sms' => true,
            'push' => false, // Phase 6
            'whatsapp' => false, // Future
        ],

        // SMS provider
        'sms_provider' => env('SMS_PROVIDER', 'ssl_wireless'), // or 'grameenphone'

        // Email templates language support
        'email_languages' => ['en', 'bn'],

        // Critical notifications (always send)
        'critical' => [
            'order_placed',
            'payment_received',
            'order_shipped',
            'order_delivered',
            'vendor_approved',
            'payout_processed',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Localization (Bangladesh Specific)
    |--------------------------------------------------------------------------
    | Based on PRD Section 7.2
    */

    'localization' => [
        // Supported languages
        'languages' => [
            'en' => ['name' => 'English', 'native' => 'English', 'flag' => '🇬🇧'],
            'bn' => ['name' => 'Bengali', 'native' => 'বাংলা', 'flag' => '🇧🇩'],
        ],

        // Default language
        'default' => 'en',

        // Admin panel language (English only)
        'admin_language' => 'en',

        // Date format
        'date_format' => 'd/m/Y', // DD/MM/YYYY

        // Time format
        'time_format' => 'h:i A', // 12-hour with AM/PM

        // Number format
        'number_formats' => [
            'en' => ['decimal' => '.', 'thousands' => ','],
            'bn' => ['decimal' => '.', 'thousands' => ','], // Bengali numerals handled in frontend
        ],

        // Currency
        'currency' => [
            'code' => 'BDT',
            'symbol' => '৳',
            'position' => 'before', // ৳ 1,234 or 1,234 ৳
            'decimals' => 2,
            'hide_decimals_for_whole' => true, // ৳ 1,234 instead of ৳ 1,234.00
        ],

        // Phone format
        'phone_format' => '01XXXXXXXXX', // 11 digits starting with 01
        'phone_validation_regex' => '/^01[3-9]\d{8}$/',

        // Address requirements
        'address_requires_landmark' => true, // Critical for Bangladesh
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    | Based on PRD Section 9
    */

    'security' => [
        // Password requirements
        'password' => [
            'min_length' => 8,
            'require_uppercase' => true,
            'require_lowercase' => true,
            'require_numbers' => true,
            'require_special_chars' => false,
            'prevent_common' => true,
            'prevent_reuse_last' => 3,
        ],

        // Session management
        'session' => [
            'idle_timeout_minutes' => 120,      // 2 hours
            'absolute_timeout_minutes' => 720,  // 12 hours
            'remember_me_days' => 30,
        ],

        // 2FA
        'two_factor' => [
            'optional_for_customers' => true,
            'mandatory_for_vendors_after_days' => 30,
        ],

        // Rate limiting
        'rate_limiting' => [
            'login_attempts' => 5,
            'lockout_minutes' => 15,
            'api_guest_per_minute' => 60,
            'api_customer_per_minute' => 120,
            'api_vendor_per_minute' => 300,
        ],

        // HTTPS
        'force_https' => env('APP_ENV') === 'production',

        // File upload validation
        'upload' => [
            'max_file_size_mb' => 10,
            'allowed_extensions' => ['jpg', 'jpeg', 'png', 'pdf', 'webp'],
            'scan_for_malware' => false, // Future feature
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance & Limits
    |--------------------------------------------------------------------------
    */

    'limits' => [
        // Database
        'products_per_page' => 24,
        'orders_per_page' => 20,
        'max_cart_items' => 50,
        'max_wishlist_items' => 100,

        // Search
        'search_results_per_page' => 24,
        'max_search_query_length' => 100,

        // Product attributes
        'max_product_variants' => 50,
        'max_product_attributes' => 10,

        // Initial platform capacity
        'target_vendors' => 1000,
        'target_products' => 100000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Platform Targets & Metrics
    |--------------------------------------------------------------------------
    | Based on PRD Section 11
    */

    'targets' => [
        // By month 12
        'active_vendors' => 1000,
        'listed_products' => 100000,
        'monthly_orders' => 10000,
        'gmv_bdt' => 50000000, // ৳50M

        // Technical
        'uptime_percentage' => 99.9,
        'page_load_seconds' => 2,
        'api_response_ms' => 200,

        // Business
        'conversion_rate_percentage' => 3,
        'cart_abandonment_percentage' => 65,
        'payout_success_rate_percentage' => 98,
    ],

];
