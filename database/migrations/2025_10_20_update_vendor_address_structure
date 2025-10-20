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
            // Rename existing address fields to international-friendly names
            $table->renameColumn('division', 'state_province_region');
            $table->renameColumn('district', 'district_county');
            $table->renameColumn('city', 'city_municipality');

            // Add new field for area/neighborhood
            $table->string('area_neighborhood')->nullable()->after('city_municipality');

            // Ensure country field exists (if not already added in onboarding migration)
            if (!Schema::hasColumn('vendors', 'country')) {
                $table->string('country')->default('Bangladesh')->after('contact_email');
            }

            // Keep postal_code and business_address as they are
            // business_address serves as full_address field
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            // Revert column names back to original
            $table->renameColumn('state_province_region', 'division');
            $table->renameColumn('district_county', 'district');
            $table->renameColumn('city_municipality', 'city');

            // Drop the new field
            $table->dropColumn('area_neighborhood');
        });
    }
};
