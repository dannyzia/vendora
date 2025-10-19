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
            $table->string('business_registration_number')->nullable();
            
            // Address
            $table->string('country')->default('Bangladesh');
            
            // Contact
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            
            // Step 2: Documents
            $table->string('nid_front_image')->nullable();
            $table->string('nid_back_image')->nullable();
            $table->string('trade_license_number')->nullable();
            $table->date('trade_license_expiry')->nullable();
            
            // Step 3: Verification
            $table->string('phone_verified_at')->nullable();
            $table->string('otp_code')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            
            // Admin Review
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('rejected_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            
            // Shop Settings (Step 5: Complete Profile)
            $table->string('shop_logo')->nullable();
            $table->string('shop_banner')->nullable();
            $table->json('business_hours')->nullable();
            $table->boolean('is_featured')->default(false);
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
                'business_registration_number',
                'country',
                'contact_person',
                'contact_phone',
                'contact_email',
                'nid_front_image',
                'nid_back_image',
                'trade_license_number',
                'trade_license_expiry',
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
            ]);
        });
    }
};
