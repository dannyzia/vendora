<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Carts
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable()->index();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->json('variant_details')->nullable();
            $table->decimal('price', 12, 2);
            $table->timestamps();

            $table->index(['user_id', 'product_id']);
        });

        // Addresses
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['shipping', 'billing', 'both'])->default('shipping');
            $table->string('name');
            $table->string('phone', 20);
            $table->string('email')->nullable();
            $table->text('address_line_1');
            $table->text('address_line_2')->nullable();
            $table->string('landmark');
            $table->string('area')->nullable();
            $table->string('thana')->nullable();
            $table->string('district');
            $table->string('division');
            $table->string('postal_code', 10)->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->index('user_id');
            $table->index('is_default');
        });

        // Product variants
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('sku')->unique()->nullable();
            $table->json('attributes');
            $table->decimal('price', 12, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('product_id');
            $table->index('sku');
        });

        // Product images
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('path');
            $table->string('thumbnail_path')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('product_id');
            $table->index('is_primary');
        });

        // Downloads
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('order_item_id');
            $table->index('customer_id');
        });

        // Bookings
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_product_id')->constrained()->onDelete('restrict');
            $table->foreignId('customer_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('vendor_id')->constrained()->onDelete('restrict');
            $table->date('booking_date');
            $table->time('booking_time');
            $table->integer('duration_minutes');
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show'])->default('pending');
            $table->text('customer_notes')->nullable();
            $table->text('vendor_notes')->nullable();
            $table->string('meeting_link')->nullable();
            $table->timestamp('reminded_24h_at')->nullable();
            $table->timestamp('reminded_1h_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('service_product_id');
            $table->index('customer_id');
            $table->index('booking_date');
            $table->index('status');
        });

        // Trust score logs
        Schema::create('trust_score_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->integer('old_score');
            $table->integer('new_score');
            $table->integer('change');
            $table->string('reason');
            $table->morphs('related');
            $table->timestamps();

            $table->index('vendor_id');
        });

        // Notification queue
        Schema::create('notification_queue', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['sms', 'email', 'push']);
            $table->string('recipient');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->json('metadata')->nullable();
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->integer('attempts')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('type');
        });

        // Settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->string('group')->nullable();
            $table->timestamps();

            $table->index('key');
            $table->index('group');
        });

        // Pages
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('slug');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('notification_queue');
        Schema::dropIfExists('trust_score_logs');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('downloads');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('carts');
    }
};
