<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            // NID Document fields
            if (!Schema::hasColumn('vendors', 'nid_number')) {
                $table->string('nid_number', 50)->nullable()->after('contact_email');
            }
            if (!Schema::hasColumn('vendors', 'nid_front_image')) {
                $table->string('nid_front_image')->nullable()->after('nid_number');
            }
            if (!Schema::hasColumn('vendors', 'nid_back_image')) {
                $table->string('nid_back_image')->nullable()->after('nid_front_image');
            }

            // Trade License fields
            if (!Schema::hasColumn('vendors', 'trade_license_number')) {
                $table->string('trade_license_number', 100)->nullable()->after('nid_back_image');
            }
            if (!Schema::hasColumn('vendors', 'trade_license_image')) {
                $table->string('trade_license_image')->nullable()->after('trade_license_number');
            }
            if (!Schema::hasColumn('vendors', 'trade_license_expiry')) {
                $table->date('trade_license_expiry')->nullable()->after('trade_license_image');
            }

            // Bank Account fields (additional ones that might be missing)
            if (!Schema::hasColumn('vendors', 'bank_branch')) {
                $table->string('bank_branch')->nullable()->after('bank_account_name');
            }
            if (!Schema::hasColumn('vendors', 'bank_routing_number')) {
                $table->string('bank_routing_number', 50)->nullable()->after('bank_branch');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn([
                'nid_number',
                'nid_front_image',
                'nid_back_image',
                'trade_license_number',
                'trade_license_image',
                'trade_license_expiry',
                'bank_branch',
                'bank_routing_number',
            ]);
        });
    }
};
