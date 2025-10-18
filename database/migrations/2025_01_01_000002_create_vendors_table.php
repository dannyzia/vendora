<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('slug')->unique();
            $table->string('business_name');
            $table->enum('business_type', ['individual', 'proprietorship', 'partnership', 'limited_company']);
            
            // Store branding
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->text('bio')->nullable();
            
            // KYC Documents
            $table->string('trade_license')->nullable();
            $table->string('tin', 50)->nullable();
            $table->string('nid', 50)->nullable();
            $table->json('kyc_documents')->nullable();
            
            // Payout Information
            $table->string('bank_name')->nullable();
            $table->string('bank_account', 100)->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_routing_number', 50)->nullable();
            $table->string('bkash_number', 20)->nullable();
            $table->string('nagad_number', 20)->nullable();
            
            // Status & Verification
            $table->enum('status', ['pending', 'under_review', 'approved', 'suspended', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->integer('trust_score')->default(50);
            
            // Subscription
            $table->string('subscription_plan')->default('free');
            $table->timestamp('subscription_started_at')->nullable();
            $table->timestamp('subscription_expires_at')->nullable();
            
            // Commission override
            $table->decimal('commission_rate', 5, 2)->nullable();
            $table->enum('commission_type', ['percentage', 'flat', 'hybrid', 'tiered'])->nullable();
            
            // Business Address
            $table->text('business_address')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('division')->nullable();
            $table->string('postal_code', 10)->nullable();
            
            // Statistics
            $table->decimal('total_sales', 15, 2)->default(0);
            $table->integer('total_orders')->default(0);
            $table->decimal('available_balance', 12, 2)->default(0);
            $table->decimal('pending_balance', 12, 2)->default(0);
            $table->decimal('on_hold_balance', 12, 2)->default(0);
            
            // Timestamps
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('user_id');
            $table->index('slug');
            $table->index('status');
            $table->index('trust_score');
            $table->index('subscription_plan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
