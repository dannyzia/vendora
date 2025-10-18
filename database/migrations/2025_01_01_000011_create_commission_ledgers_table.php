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
        Schema::create('commission_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained()->onDelete('restrict');
            
            // Order details
            $table->decimal('order_total', 12, 2); // Product price only (not shipping)
            
            // Commission calculation
            $table->decimal('commission_rate', 5, 2); // 15.00 = 15%
            $table->enum('commission_type', ['percentage', 'flat', 'hybrid', 'tiered']);
            $table->decimal('commission_amount', 12, 2); // Platform's commission
            $table->decimal('vendor_earnings', 12, 2); // Vendor receives
            
            // Additional fees
            $table->decimal('platform_fee', 12, 2)->default(0);
            $table->decimal('payment_gateway_fee', 12, 2)->default(0);
            
            // Status & Escrow
            $table->enum('status', ['pending', 'confirmed', 'reversed'])->default('pending');
            $table->timestamp('escrow_released_at')->nullable();
            $table->timestamp('calculated_at')->useCurrent();
            $table->timestamp('confirmed_at')->nullable();
            
            // Adjustment tracking (for refunds/disputes)
            $table->boolean('is_adjustment')->default(false);
            $table->foreignId('original_ledger_id')->nullable()->constrained('commission_ledgers')->nullOnDelete();
            $table->text('adjustment_reason')->nullable();
            
            // Payout tracking
            $table->foreignId('payout_id')->nullable()->constrained('payouts')->nullOnDelete();
            $table->boolean('is_paid_out')->default(false);
            $table->timestamp('paid_out_at')->nullable();
            
            $table->timestamps();

            // Indexes
            $table->index('order_id');
            $table->index('vendor_id');
            $table->index('status');
            $table->index('is_paid_out');
            $table->index('payout_id');
            $table->index('calculated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_ledgers');
    }
};