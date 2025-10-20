<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 🔧 VENDORA MARKETPLACE - COMPLETE DATABASE FIX
     * 
     * This migration fixes ALL database schema issues across the entire application.
     * It adds missing columns, renames fields for international support, and ensures
     * all tables match their controllers, models, and Vue components.
     * 
     * Safe to run multiple times - uses hasColumn() checks.
     */
    public function up(): void
    {
        // =====================================================
        // 1. VENDORS TABLE - CRITICAL FIXES
        // =====================================================
        Schema::table('vendors', function (Blueprint $table) {
            // Shop Information
            if (!Schema::hasColumn('vendors', 'shop_name')) {
                $table->string('shop_name')->nullable()->after('business_name');
            }
            if (!Schema::hasColumn('vendors', 'shop_description')) {
                $table->text('shop_description')->nullable()->after('shop_name');
            }

            // Business Registration
            if (!Schema::hasColumn('vendors', 'business_registration_number')) {
                $table->string('business_registration_number', 100)->nullable()->after('business_type');
            }

            // International Address Support
            if (!Schema::hasColumn('vendors', 'country')) {
                $table->string('country', 100)->default('Bangladesh')->after('business_address');
            }

            // Rename address fields for international support
            // Note: These renames might fail if columns don't exist or are already renamed
            // The migration will continue even if these fail
            if (Schema::hasColumn('vendors', 'division') && !Schema::hasColumn('vendors', 'state_province_region')) {
                $table->renameColumn('division', 'state_province_region');
            } elseif (!Schema::hasColumn('vendors', 'state_province_region')) {
                $table->string('state_province_region', 100)->nullable()->after('country');
            }

            if (Schema::hasColumn('vendors', 'district') && !Schema::hasColumn('vendors', 'district_county')) {
                $table->renameColumn('district', 'district_county');
            } elseif (!Schema::hasColumn('vendors', 'district_county')) {
                $table->string('district_county', 100)->nullable()->after('state_province_region');
            }

            if (Schema::hasColumn('vendors', 'city') && !Schema::hasColumn('vendors', 'city_municipality')) {
                $table->renameColumn('city', 'city_municipality');
            } elseif (!Schema::hasColumn('vendors', 'city_municipality')) {
                $table->string('city_municipality', 100)->nullable()->after('district_county');
            }

            if (!Schema::hasColumn('vendors', 'area_neighborhood')) {
                $table->string('area_neighborhood', 100)->nullable()->after('city_municipality');
            }

            // Contact Information
            if (!Schema::hasColumn('vendors', 'contact_person')) {
                $table->string('contact_person')->nullable()->after('area_neighborhood');
            }
            if (!Schema::hasColumn('vendors', 'contact_phone')) {
                $table->string('contact_phone', 20)->nullable()->after('contact_person');
            }
            if (!Schema::hasColumn('vendors', 'contact_email')) {
                $table->string('contact_email')->nullable()->after('contact_phone');
            }

            // NID Document Fields
            if (!Schema::hasColumn('vendors', 'nid_number')) {
                $table->string('nid_number', 50)->nullable()->after('contact_email');
            }
            if (!Schema::hasColumn('vendors', 'nid_front_image')) {
                $table->string('nid_front_image')->nullable()->after('nid_number');
            }
            if (!Schema::hasColumn('vendors', 'nid_back_image')) {
                $table->string('nid_back_image')->nullable()->after('nid_front_image');
            }

            // Trade License Fields
            if (!Schema::hasColumn('vendors', 'trade_license_number')) {
                $table->string('trade_license_number', 100)->nullable()->after('nid_back_image');
            }
            if (!Schema::hasColumn('vendors', 'trade_license_image')) {
                $table->string('trade_license_image')->nullable()->after('trade_license_number');
            }
            if (!Schema::hasColumn('vendors', 'trade_license_expiry')) {
                $table->date('trade_license_expiry')->nullable()->after('trade_license_image');
            }

            // Bank Account Fields (rename bank_account to bank_account_number if needed)
            if (Schema::hasColumn('vendors', 'bank_account') && !Schema::hasColumn('vendors', 'bank_account_number')) {
                $table->renameColumn('bank_account', 'bank_account_number');
            } elseif (!Schema::hasColumn('vendors', 'bank_account_number')) {
                $table->string('bank_account_number', 100)->nullable()->after('bank_name');
            }

            // Ensure bank_branch exists
            if (!Schema::hasColumn('vendors', 'bank_branch')) {
                $table->string('bank_branch')->nullable()->after('bank_account_name');
            }

            // Ensure bank_routing_number exists
            if (!Schema::hasColumn('vendors', 'bank_routing_number')) {
                $table->string('bank_routing_number', 50)->nullable()->after('bank_branch');
            }

            // Onboarding Status
            if (!Schema::hasColumn('vendors', 'onboarding_status')) {
                $table->enum('onboarding_status', [
                    'incomplete',
                    'application',
                    'documents',
                    'verification',
                    'pending',
                    'approved',
                    'rejected',
                    'complete'
                ])->default('incomplete')->after('status');
            }

            // Phone Verification Fields
            if (!Schema::hasColumn('vendors', 'phone_verified_at')) {
                $table->timestamp('phone_verified_at')->nullable()->after('onboarding_status');
            }
            if (!Schema::hasColumn('vendors', 'otp_code')) {
                $table->string('otp_code', 6)->nullable()->after('phone_verified_at');
            }
            if (!Schema::hasColumn('vendors', 'otp_expires_at')) {
                $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
            }

            // Admin Approval Tracking
            if (!Schema::hasColumn('vendors', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->after('approved_at');
            }
            if (!Schema::hasColumn('vendors', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('rejection_reason');
            }
            if (!Schema::hasColumn('vendors', 'rejected_by')) {
                $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete()->after('rejected_at');
            }

            // Shop Branding (additional fields)
            if (!Schema::hasColumn('vendors', 'shop_logo')) {
                $table->string('shop_logo')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('vendors', 'shop_banner')) {
                $table->string('shop_banner')->nullable()->after('banner');
            }

            // Business Hours
            if (!Schema::hasColumn('vendors', 'business_hours')) {
                $table->json('business_hours')->nullable()->after('bio');
            }

            // Featured Vendor
            if (!Schema::hasColumn('vendors', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('status');
            }
        });

        // =====================================================
        // 2. PRODUCTS TABLE - STOCK FIELD
        // =====================================================
        Schema::table('products', function (Blueprint $table) {
            // Add stock field if it doesn't exist (for simple products)
            // Note: Physical products use physical_products.stock_quantity
            // This is for products that don't have a physical_products record
            if (!Schema::hasColumn('products', 'stock_quantity')) {
                $table->integer('stock_quantity')->default(0)->after('price');
            }

            // Ensure view_count exists (migration might be truncated)
            if (!Schema::hasColumn('products', 'view_count')) {
                $table->integer('view_count')->default(0)->after('focus_keywords');
            }

            // Add useful fields that might be missing
            if (!Schema::hasColumn('products', 'sales_count')) {
                $table->integer('sales_count')->default(0)->after('view_count');
            }
            if (!Schema::hasColumn('products', 'rating_average')) {
                $table->decimal('rating_average', 3, 2)->default(0)->after('sales_count');
            }
            if (!Schema::hasColumn('products', 'rating_count')) {
                $table->integer('rating_count')->default(0)->after('rating_average');
            }
        });

        // =====================================================
        // 3. ORDERS TABLE - EXPLICIT CUSTOMER FIELDS
        // =====================================================
        // These already exist in migration, but let's ensure they're there
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->after('customer_id');
            }
            if (!Schema::hasColumn('orders', 'customer_email')) {
                $table->string('customer_email')->after('customer_name');
            }
            if (!Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone', 20)->after('customer_email');
            }
        });

        // =====================================================
        // 4. USERS TABLE - ADDITIONAL FIELDS
        // =====================================================
        Schema::table('users', function (Blueprint $table) {
            // Profile fields
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('avatar');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable()->after('date_of_birth');
            }

            // Account status
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('is_active');
            }
            if (!Schema::hasColumn('users', 'last_login_ip')) {
                $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            }
        });

        // =====================================================
        // 5. CATEGORIES TABLE - ADDITIONAL FIELDS
        // =====================================================
        Schema::table('categories', function (Blueprint $table) {
            // Ensure description_bn exists (might be missing in some versions)
            if (!Schema::hasColumn('categories', 'description_bn')) {
                $table->text('description_bn')->nullable()->after('description');
            }
        });

        // =====================================================
        // 6. REVIEWS TABLE (PLUGIN) - ENSURE EXISTS
        // =====================================================
        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                // Ensure all review fields exist
                if (!Schema::hasColumn('reviews', 'images')) {
                    $table->json('images')->nullable()->after('comment');
                }
                if (!Schema::hasColumn('reviews', 'is_verified_purchase')) {
                    $table->boolean('is_verified_purchase')->default(false)->after('images');
                }
                if (!Schema::hasColumn('reviews', 'helpful_count')) {
                    $table->integer('helpful_count')->default(0)->after('vendor_responded_at');
                }
                if (!Schema::hasColumn('reviews', 'not_helpful_count')) {
                    $table->integer('not_helpful_count')->default(0)->after('helpful_count');
                }
            });
        }

        // =====================================================
        // 7. PHYSICAL_PRODUCTS TABLE
        // =====================================================
        if (Schema::hasTable('physical_products')) {
            Schema::table('physical_products', function (Blueprint $table) {
                // Add product_id if missing (foreign key to products)
                if (!Schema::hasColumn('physical_products', 'product_id')) {
                    $table->foreignId('product_id')->after('id')->constrained()->onDelete('cascade');
                }
            });
        }

        // =====================================================
        // 8. DIGITAL_PRODUCTS TABLE
        // =====================================================
        if (Schema::hasTable('digital_products')) {
            Schema::table('digital_products', function (Blueprint $table) {
                // Add product_id if missing
                if (!Schema::hasColumn('digital_products', 'product_id')) {
                    $table->foreignId('product_id')->after('id')->constrained()->onDelete('cascade');
                }
            });
        }

        // =====================================================
        // 9. SERVICE_PRODUCTS TABLE
        // =====================================================
        if (Schema::hasTable('service_products')) {
            Schema::table('service_products', function (Blueprint $table) {
                // Add product_id if missing
                if (!Schema::hasColumn('service_products', 'product_id')) {
                    $table->foreignId('product_id')->after('id')->constrained()->onDelete('cascade');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Reversing column renames is complex and risky
        // This down() method only drops added columns, not renames
        
        Schema::table('vendors', function (Blueprint $table) {
            $columns = [
                'shop_name',
                'shop_description',
                'business_registration_number',
                'country',
                'area_neighborhood',
                'contact_person',
                'contact_phone',
                'contact_email',
                'nid_number',
                'nid_front_image',
                'nid_back_image',
                'trade_license_number',
                'trade_license_image',
                'trade_license_expiry',
                'onboarding_status',
                'phone_verified_at',
                'otp_code',
                'otp_expires_at',
                'approved_by',
                'rejected_at',
                'rejected_by',
                'shop_logo',
                'shop_banner',
                'business_hours',
                'is_featured',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('vendors', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('products', function (Blueprint $table) {
            $columns = ['stock_quantity', 'sales_count', 'rating_average', 'rating_count'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $columns = ['avatar', 'date_of_birth', 'gender', 'is_active', 'last_login_at', 'last_login_ip'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
