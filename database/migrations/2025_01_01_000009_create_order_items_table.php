<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->foreignId('vendor_id')->constrained()->onDelete('restrict');
            
            // Product snapshot
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->enum('product_type', ['physical', 'digital', 'service']);
            $table->string('product_image')->nullable();
            $table->json('variant_details')->nullable();
            
            // Pricing
            $table->decimal('unit_price', 12, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            
            // Digital Product Specific
            $table->string('download_url')->nullable();
            $table->integer('download_count')->default(0);
            $table->integer('download_limit')->nullable();
            $table->timestamp('download_expires_at')->nullable();
            $table->string('license_key')->nullable();
            
            // Service/Booking Specific
            $table->timestamp('booking_date')->nullable();
            $table->time('booking_time')->nullable();
            $table->integer('booking_duration_minutes')->nullable();
            $table->enum('booking_status', [
                'pending',
                'confirmed',
                'in_progress',
                'completed',
                'cancelled',
                'no_show'
            ])->nullable();
            $table->text('booking_notes')->nullable();
            $table->string('meeting_link')->nullable();
            
            // Status tracking
            $table->enum('fulfillment_status', [
                'pending',
                'processing',
                'shipped',
                'delivered',
                'completed',
                'cancelled',
                'refunded',
                'returned'
            ])->default('pending');
            
            // Return/Refund
            $table->boolean('is_returnable')->default(false);
            $table->boolean('is_returned')->default(false);
            $table->decimal('refund_amount', 12, 2)->nullable();
            $table->timestamp('refunded_at')->nullable();
            
            $table->timestamps();

            // Indexes
            $table->index('order_id');
            $table->index('product_id');
            $table->index('vendor_id');
            $table->index('product_type');
            $table->index('fulfillment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
