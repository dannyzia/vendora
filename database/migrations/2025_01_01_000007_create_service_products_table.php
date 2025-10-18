<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_products', function (Blueprint $table) {
            $table->id();
            
            // Service Duration
            $table->integer('duration_minutes');
            $table->integer('preparation_time_minutes')->default(0);
            
            // Availability
            $table->json('available_days')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->json('time_slots')->nullable();
            $table->integer('max_bookings_per_day')->default(10);
            $table->integer('max_bookings_per_slot')->default(1);
            
            // Location
            $table->enum('location_type', ['virtual', 'in_person', 'both'])->default('virtual');
            $table->text('physical_address')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Virtual Meeting
            $table->string('meeting_platform')->nullable();
            $table->text('meeting_instructions')->nullable();
            
            // Cancellation Policy
            $table->boolean('allow_cancellation')->default(true);
            $table->integer('cancellation_hours_before')->default(24);
            $table->decimal('cancellation_fee_percentage', 5, 2)->default(0);
            $table->text('cancellation_policy')->nullable();
            
            // Requirements
            $table->text('requirements')->nullable();
            $table->text('what_included')->nullable();
            $table->text('what_excluded')->nullable();
            
            // Team/Staff
            $table->string('provider_name')->nullable();
            $table->text('provider_bio')->nullable();
            $table->json('provider_qualifications')->nullable();
            
            // Booking Settings
            $table->boolean('instant_booking')->default(false);
            $table->integer('advance_booking_days')->default(1);
            $table->integer('max_advance_booking_days')->default(90);
            
            // Reminders
            $table->boolean('send_reminder_24h')->default(true);
            $table->boolean('send_reminder_1h')->default(true);
            
            $table->timestamps();

            // Indexes
            $table->index('location_type');
            $table->index('instant_booking');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_products');
    }
};
