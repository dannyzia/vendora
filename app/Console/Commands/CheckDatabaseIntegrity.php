<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckDatabaseIntegrity extends Command
{
    protected $signature = 'check:database
                          {--table= : Check specific table only}
                          {--fix : Suggest fix commands}';

    protected $description = 'Check database integrity across ALL tables - finds missing columns and mismatches';

    /**
     * 🔍 Complete table definitions
     * Maps table names to their expected columns
     */
    protected $expectedColumns = [
        'vendors' => [
            'id', 'user_id', 'slug', 'business_name', 'business_type',
            'shop_name', 'shop_description', 'business_registration_number',
            'logo', 'shop_logo', 'banner', 'shop_banner', 'bio', 'business_hours',
            'trade_license', 'tin', 'nid', 'kyc_documents',
            'nid_number', 'nid_front_image', 'nid_back_image',
            'trade_license_number', 'trade_license_image', 'trade_license_expiry',
            'bank_name', 'bank_account_number', 'bank_account_name', 'bank_branch', 'bank_routing_number',
            'bkash_number', 'nagad_number',
            'status', 'onboarding_status', 'rejection_reason', 'trust_score', 'is_featured',
            'subscription_plan', 'subscription_started_at', 'subscription_expires_at',
            'commission_rate', 'commission_type',
            'business_address', 'country',
            'state_province_region', 'district_county', 'city_municipality', 'area_neighborhood', 'postal_code',
            'contact_person', 'contact_phone', 'contact_email',
            'phone_verified_at', 'otp_code', 'otp_expires_at',
            'total_sales', 'total_orders',
            'available_balance', 'pending_balance', 'on_hold_balance',
            'approved_at', 'approved_by', 'rejected_at', 'rejected_by', 'last_active_at',
            'created_at', 'updated_at', 'deleted_at',
        ],
        'products' => [
            'id', 'vendor_id', 'category_id',
            'title', 'title_bn', 'slug', 'sku', 'type',
            'description', 'description_bn', 'short_description',
            'price', 'compare_at_price', 'cost_price', 'stock_quantity',
            'discount_amount', 'discount_type', 'discount_start_at', 'discount_end_at',
            'images', 'thumbnail',
            'status', 'rejection_reason', 'approved_at', 'approved_by',
            'meta_title', 'meta_description', 'focus_keywords',
            'view_count', 'sales_count', 'rating_average', 'rating_count',
            'created_at', 'updated_at', 'deleted_at',
        ],
        'users' => [
            'id', 'uuid', 'name', 'email', 'phone', 'password', 'role',
            'avatar', 'date_of_birth', 'gender',
            'email_verified_at', 'phone_verified_at',
            'two_factor_enabled', 'two_factor_secret', 'two_factor_recovery_codes',
            'is_active', 'last_login_at', 'last_login_ip',
            'remember_token', 'created_at', 'updated_at', 'deleted_at',
        ],
        'orders' => [
            'id', 'uuid', 'order_number',
            'customer_id', 'vendor_id',
            'customer_name', 'customer_email', 'customer_phone',
            'subtotal', 'tax', 'shipping_cost', 'discount', 'cod_fee', 'total',
            'status', 'payment_status', 'payment_method', 'payment_gateway',
            'transaction_id', 'paid_at',
            'shipping_address', 'shipping_method', 'shipping_zone',
            'courier_name', 'tracking_number', 'tracking_url', 'courier_response',
            'customer_note', 'vendor_note', 'admin_note',
            'confirmed_at', 'shipped_at', 'delivered_at', 'completed_at',
            'cancelled_at', 'cancellation_reason',
            'is_cod', 'cod_verified', 'cod_verified_at',
            'ip_address', 'user_agent', 'risk_score',
            'requires_verification', 'flagged_for_review',
            'created_at', 'updated_at', 'deleted_at',
        ],
        'payments' => [
            'id', 'order_id', 'amount', 'currency',
            'transaction_id', 'status', 'payment_gateway',
            'created_at', 'updated_at',
        ],
        'categories' => [
            'id', 'parent_id', 'name', 'name_bn', 'slug',
            'description', 'description_bn', 'icon', 'image',
            'is_active', 'sort_order', 'commission_rate',
            'meta_title', 'meta_description',
            'show_on_homepage', 'products_count',
            'created_at', 'updated_at', 'deleted_at',
        ],
        // Add more tables as needed
    ];

    public function handle()
    {
        $this->info('🔍 VENDORA DATABASE INTEGRITY CHECK');
        $this->info('====================================');
        $this->newLine();

        $specificTable = $this->option('table');
        $verbose = $this->option('verbose');
        $fix = $this->option('fix');

        $tablesToCheck = $specificTable
            ? [$specificTable => $this->expectedColumns[$specificTable] ?? []]
            : $this->expectedColumns;

        $totalIssues = 0;
        $tablesWithIssues = [];

        foreach ($tablesToCheck as $table => $expectedCols) {
            if (!Schema::hasTable($table)) {
                $this->warn("⚠️  Table '{$table}' does not exist!");
                $totalIssues++;
                continue;
            }

            $actualColumns = $this->getTableColumns($table);
            $missing = array_diff($expectedCols, $actualColumns->all());
            $extra = array_diff($actualColumns->all(), $expectedCols);

            if (empty($missing) && empty($extra)) {
                $this->info("✅ {$table}: ALL GOOD ({$actualColumns->count()} columns)");
                continue;
            }

            $this->warn("🔴 {$table}: ISSUES FOUND");
            $this->newLine();

            if (!empty($missing)) {
                $this->error("   ❌ Missing " . count($missing) . " columns:");
                foreach ($missing as $col) {
                    $this->line("      - {$col}");
                }
                $totalIssues += count($missing);
                $tablesWithIssues[] = $table;
            }

            if (!empty($extra) && $verbose) {
                $this->warn("   ⚡ Extra " . count($extra) . " columns (not in expected list):");
                foreach ($extra as $col) {
                    $this->line("      + {$col}");
                }
            }

            $this->newLine();
        }

        // Summary
        $this->info('====================================');
        if ($totalIssues === 0) {
            $this->info('✅ ALL TABLES ARE HEALTHY!');
        } else {
            $this->error("❌ Found {$totalIssues} issues across " . count($tablesWithIssues) . " tables");
            $this->newLine();
            $this->warn('   Tables with issues: ' . implode(', ', $tablesWithIssues));
        }

        // Show fix command
        if ($fix && $totalIssues > 0) {
            $this->newLine();
            $this->info('🔧 TO FIX ALL ISSUES:');
            $this->line('   php artisan migrate');
            $this->line('   (Make sure 2025_10_20_000000_complete_database_fix_all_tables.php is in database/migrations/)');
        }

        return $totalIssues === 0 ? 0 : 1;
    }

    /**
     * Get all column names for a table
     */
    protected function getTableColumns(string $table): \Illuminate\Support\Collection
    {
        $columns = DB::select("DESCRIBE {$table}");
        return collect($columns)->pluck('Field');
    }
}
