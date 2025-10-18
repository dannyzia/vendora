<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            
            // Basic Information
            $table->string('title', 500);
            $table->string('title_bn', 500)->nullable();
            $table->string('slug', 500)->unique();
            $table->string('sku', 100)->unique()->nullable();
            $table->enum('type', ['physical', 'digital', 'service']);
            
            // Content
            $table->longText('description')->nullable();
            $table->longText('description_bn')->nullable();
            $table->text('short_description')->nullable();
            
            // Pricing
            $table->decimal('price', 12, 2);
            $table->decimal('compare_at_price', 12, 2)->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();
            
            // Discount
            $table->decimal('discount_amount', 12, 2)->nullable();
            $table->enum('discount_type', ['percentage', 'fixed'])->nullable();
            $table->timestamp('discount_start_at')->nullable();
            $table->timestamp('discount_end_at')->nullable();
            
            // Images
            $table->json('images')->nullable();
            $table->string('thumbnail')->nullable();
            
            // Status & Approval
            $table->enum('status', ['draft', 'pending', 'active', 'inactive', 'rejected'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            
            // SEO
            $table->string('meta_title', 60)->nullable();
            $table->text('meta_description')->nullable();
            $table->text('focus_keywords')->nullable();
            
            // Statistics
            $table->integer('view_count')->default(0);
            $table->integer('sales_count')->default(0);
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);
            
            // Polymorphic relationship
            $table->string('productable_type');
            $table->unsignedBigInteger('productable_id');
            
            // Featured & Display
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('vendor_id');
            $table->index('category_id');
            $table->index('slug');
            $table->index('type');
            $table->index('status');
            $table->index(['productable_type', 'productable_id']);
            $table->index('is_featured');
            $table->index('created_at');
            
            // Full-text search
            $table->fullText(['title', 'description']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
