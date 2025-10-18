<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('restrict');
            
            // Payout details
            $table->string('payout_number')->unique();
            $table->decimal('amount', 12, 2);
            $table->decimal('fee', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2);
            
            // Payment method
            $table->enum('method', ['bank_transfer', 'bkash', 'nagad', 'manual']);
            $table->json('method_details')->nullable();
            
            // Status
            $table->enum('status', [
                'pending',
                'processing',
                'completed',
                'failed',
                'cancelled',
                'on_hold'
            ])->default('pending');
            
            // Processing details
            $table->string('transaction_reference')->nullable();
            $table->text('processing_note')->nullable();
            $table->text('failure_reason')->nullable();
            
            // Schedule
            $table->enum('schedule_type', ['manual', 'weekly', 'biweekly', 'monthly'])->default('manual');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            
            // Hold scenarios
            $table->boolean('is_on_hold')->default(false);
            $table->text('hold_reason')->nullable();
            $table->timestamp('hold_until')->nullable();
            
            // Included commissions
            $table->integer('commission_count')->default(0);
            $table->json('commission_ids')->nullable();
            
            // Period covered
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            
            $table->timestamps();

            // Indexes
            $table->index('vendor_id');
            $table->index('payout_number');
            $table->index('status');
            $table->index('method');
            $table->index('scheduled_at');
            $table->index('is_on_hold');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
