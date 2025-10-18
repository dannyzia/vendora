<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('order_number', 50)->unique();
            
            // Customer Information
            $table->foreignId('customer_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('vendor_id')->constrained()->onDelete('restrict');
            
            // Pricing Breakdown
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('cod_fee', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            
            // Order Status
            $table->enum('status', [
                'pending',
                'payment_pending',
                'processing',
                'shipped',
                'delivered',
                'completed',
                'cancelled',
                'refunded'
            ])->default('pending');
            
            // Payment Information
            $table->enum('payment_status', [
                'unpaid',
                'paid',
                'partially_refunded',
                'refunded',
                'failed'
            ])->default('unpaid');
            $table->string('payment_method', 50)->nullable();
            $table->string('payment_gateway')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            // Shipping Information
            $table->json('shipping_address');
            $table->string('shipping_method')->nullable();
            $table->string('shipping_zone')->nullable();
            
            // Courier/Tracking
            $table->string('courier_name', 100)->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('tracking_url')->nullable();
            $table->text('courier_response')->nullable();
            
            // Customer Contact
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone', 20);
            
            // Notes & Instructions
            $table->text('customer_note')->nullable();
            $table->text('vendor_note')->nullable();
            $table->text('admin_note')->nullable();
            
            // Timestamps
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            // COD Specific
            $table->boolean('is_cod')->default(false);
            $table->boolean('cod_verified')->default(false);
            $table->timestamp('cod_verified_at')->nullable();
            
            // Fraud Prevention
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->integer('risk_score')->default(0);
            $table->boolean('requires_verification')->default(false);
            $table->boolean('flagged_for_review')->default(false);
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('uuid');
            $table->index('order_number');
            $table->index('customer_id');
            $table->index('vendor_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index('payment_method');
            $table->index('is_cod');
            $table->index('tracking_number');
            $table->index('created_at');
            $table->index('flagged_for_review');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
