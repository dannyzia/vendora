<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            
            // Rating
            $table->unsignedTinyInteger('rating');
            
            // Review content
            $table->string('title')->nullable();
            $table->text('comment')->nullable();
            $table->json('images')->nullable();
            
            // Verification
            $table->boolean('is_verified_purchase')->default(false);
            
            // Moderation
            $table->enum('status', ['pending', 'approved', 'rejected', 'flagged'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('moderated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('moderated_at')->nullable();
            
            // Vendor response
            $table->text('vendor_response')->nullable();
            $table->timestamp('vendor_responded_at')->nullable();
            
            // Helpful votes
            $table->integer('helpful_count')->default(0);
            $table->integer('not_helpful_count')->default(0);
            
            // Flags
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_reported')->default(false);
            $table->integer('report_count')->default(0);
            
            $table->timestamps();

            // Indexes
            $table->index('product_id');
            $table->index('customer_id');
            $table->index('vendor_id');
            $table->index('order_id');
            $table->index('rating');
            $table->index('status');
            $table->index('is_verified_purchase');
            $table->index('created_at');
            
            // Unique constraint
            $table->unique(['product_id', 'customer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
