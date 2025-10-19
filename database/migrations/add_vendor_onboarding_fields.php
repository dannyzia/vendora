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
            // Application Status
            $table->enum('onboarding_status', [
                'incomplete',      // Just registered, hasn't started
                'application',     // Step 1: Filling application
                'documents',       // Step 2: Uploading documents
                'verification',    // Step 3: Phone verification
                'pending',         // Waiting for admin approval
                'approved',        // Admin approved
                'rejected',        // Admin rejected
                'complete'         // Profile completed, ready to sell
            ])->default('incomplete')->after('status');
            
            // Step 1: Business Information
            $table->string('shop_name')->nullable()->after('onboarding_status');
            $table->text('shop_description')->nullable();
            $table->enum('business_type', [
                'individual',
                'company',
                'partnership'
            ])->nullable();
            $table->string('business_name')->nullable();
            $table->string('business_registration_number')->nullable();
            
            // Address
            $table->text('business_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Bangladesh');
            
            // Contact
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            
            // Step 2: Documents
            $table->string('nid_number')->nullable();
            $table->string('nid_front_image')->nullable();
            $table->string('nid_back_image')->nullable();
            $table->string('trade_license_number')->nullable();
            $table->string('trade_license_image')->nullable();
            $table->date('trade_license_expiry')->nullable();
            
            // Bank Details
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_routing_number')->nullable();
            
            // Step 3: Verification
            $table->string('phone_verified_at')->nullable();
            $table->string('otp_code')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            
            // Admin Review
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('rejected_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            
            // Shop Settings (Step 5: Complete Profile)
            $table->string('shop_logo')->nullable();
            $table->string('shop_banner')->nullable();
            $table->json('business_hours')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->decimal('commission_rate', 5, 2)->default(10.00); // Percentage
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn([
                'onboarding_status',
                'shop_name',
                'shop_description',
                'business_type',
                'business_name',
                'business_registration_number',
                'business_address',
                'city',
                'state',
                'postal_code',
                'country',
                'contact_person',
                'contact_phone',
                'contact_email',
                'nid_number',
                'nid_front_image',
                'nid_back_image',
                'trade_license_number',
                'trade_license_image',
                'trade_license_expiry',
                'bank_name',
                'bank_account_number',
                'bank_account_name',
                'bank_branch',
                'bank_routing_number',
                'phone_verified_at',
                'otp_code',
                'otp_expires_at',
                'rejection_reason',
                'approved_at',
                'approved_by',
                'rejected_at',
                'rejected_by',
                'shop_logo',
                'shop_banner',
                'business_hours',
                'is_featured',
                'commission_rate',
            ]);
        });
    }
};
