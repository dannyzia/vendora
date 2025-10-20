<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the business_type ENUM to match the form values
        DB::statement("ALTER TABLE `vendors` MODIFY `business_type` ENUM('individual', 'company', 'partnership') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original ENUM values
        DB::statement("ALTER TABLE `vendors` MODIFY `business_type` ENUM('individual', 'proprietorship', 'partnership', 'limited_company') NULL");
    }
};
