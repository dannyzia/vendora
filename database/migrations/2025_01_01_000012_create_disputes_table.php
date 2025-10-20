<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('restrict');
            $table->foreignId('customer_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('vendor_id')->constrained()->onDelete('restrict');
            
            // Dispute type
            $table->enum('dispute_type', [
                'not_received',
                'defective',
                'wrong_item',
                'not_as_described',
                'vendor_unresponsive'
            ]);
            
            // Status workflow
            $table->enum('status', [
                'open',
                'vendor_responded',
                'under_admin_review',
                'resolved',
                'closed'
            ])->default('open');
            
            // Customer's case
            $table->text('customer_description');
            $table->json('customer_evidence')->nullable();
            $table->enum('customer_resolution', [
                'full_refund',
                'partial_refund',
                'replacement',
                'other'
            ]);
            $table->decimal('customer_requested_amount', 12, 2)->nullable();
            
            // Vendor's response
            $table->text('vendor_response')->nullable();
            $table->json('vendor_evidence')->nullable();
            $table->enum('vendor_resolution', [
                'accept',
                'counter_offer',
                'dispute',
                'no_response'
            ])->nullable();
            $table->decimal('vendor_offered_amount', 12, 2)->nullable();
            $table->timestamp('vendor_responded_at')->nullable();
            
            // Admin mediation
            $table->text('admin_decision')->nullable();
            $table->enum('resolution_type', [
                'full_refund',
                'partial_refund',
                'no_refund',
                'replacement'
            ])->nullable();
            $table->decimal('refund_amount', 12, 2)->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            
            // Process rating
            $table->integer('customer_rating')->nullable();
            $table->integer('vendor_rating')->nullable();
            
            // Deadlines
            $table->timestamp('vendor_response_deadline')->nullable();
            $table->timestamp('admin_decision_deadline')->nullable();
            
            // Auto-escalation
            $table->boolean('auto_escalated')->default(false);
            $table->timestamp('escalated_at')->nullable();
            
            // Communication
            $table->integer('messages_count')->default(0);
            
            $table->timestamps();

            // Indexes
            $table->index('order_id');
            $table->index('customer_id');
            $table->index('vendor_id');
            $table->index('dispute_type');
            $table->index('status');
            $table->index('resolved_by');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};
