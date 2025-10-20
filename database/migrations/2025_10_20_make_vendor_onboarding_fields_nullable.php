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
            // Make business_name nullable (will be filled during onboarding)
            $table->string('business_name')->nullable()->change();

            // Make business_type nullable (will be filled during onboarding)
            $table->enum('business_type', ['individual', 'proprietorship', 'partnership', 'limited_company'])
                  ->nullable()
                  ->change();

            // Make slug nullable initially (will be generated during registration or onboarding)
            $table->string('slug')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            // Revert back to NOT NULL (be careful - only if you have data)
            $table->string('business_name')->nullable(false)->change();
            $table->enum('business_type', ['individual', 'proprietorship', 'partnership', 'limited_company'])
                  ->nullable(false)
                  ->change();
            $table->string('slug')->nullable(false)->change();
        });
    }
};
