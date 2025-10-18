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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            
            // Plan details
            $table->string('name'); // Free, Basic, Pro, Enterprise
            $table->string('name_bn')->nullable(); // Bengali name
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('description_bn')->nullable();
            
            // Pricing
            $table->decimal('price', 10, 2)->default(0); // Monthly price in BDT
            $table->decimal('annual_price', 10, 2)->nullable(); // Yearly discount price
            
            // Commission
            $table->decimal('commission_rate', 5, 2); // Percentage (e.g., 10.00 = 10%)
            $table->enum('commission_type', ['percentage', 'flat'])->default('percentage');
            
            // Limits
            $table->integer('product_limit')->nullable(); // NULL = unlimited
            $table->integer('image_per_product')->default(10);
            $table->integer('storage_limit_mb')->nullable(); // For digital products
            
            // Features (JSON)
            $table->json('features')->nullable(); // List of features
            $table->json('features_bn')->nullable(); // Bengali features
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false); // Badge: "Most Popular"
            $table->integer('sort_order')->default(0);
            
            // Trial
            $table->integer('trial_days')->default(0); // Free trial period
            
            $table->timestamps();
            
            // Indexes
            $table->index('slug');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};