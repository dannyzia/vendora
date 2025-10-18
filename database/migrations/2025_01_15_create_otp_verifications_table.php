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
        Schema::create('otp_verifications', function (Blueprint $table) {
            $table->id();
            
            // Contact information
            $table->string('identifier'); // email or phone
            $table->enum('type', ['email', 'sms', 'whatsapp'])->default('email');
            
            // OTP details
            $table->string('otp', 6);
            $table->timestamp('expires_at');
            $table->timestamp('verified_at')->nullable();
            
            // Security
            $table->integer('attempts')->default(0);
            $table->integer('resend_count')->default(0);
            $table->timestamp('last_resend_at')->nullable();
            
            // Purpose tracking
            $table->string('purpose')->default('vendor_onboarding'); // vendor_onboarding, password_reset, etc.
            
            $table->timestamps();
            
            // Indexes
            $table->index('identifier');
            $table->index('expires_at');
            $table->index(['identifier', 'otp']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_verifications');
    }
};