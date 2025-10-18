<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'duration_minutes',
        'preparation_time_minutes',
        'available_days',
        'start_time',
        'end_time',
        'time_slots',
        'max_bookings_per_day',
        'max_bookings_per_slot',
        'location_type',
        'physical_address',
        'city',
        'district',
        'latitude',
        'longitude',
        'meeting_platform',
        'meeting_instructions',
        'allow_cancellation',
        'cancellation_hours_before',
        'cancellation_fee_percentage',
        'cancellation_policy',
        'requirements',
        'what_included',
        'what_excluded',
        'provider_name',
        'provider_bio',
        'provider_qualifications',
        'instant_booking',
        'advance_booking_days',
        'max_advance_booking_days',
        'send_reminder_24h',
        'send_reminder_1h',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'preparation_time_minutes' => 'integer',
        'available_days' => 'array',
        'time_slots' => 'array',
        'max_bookings_per_day' => 'integer',
        'max_bookings_per_slot' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'allow_cancellation' => 'boolean',
        'cancellation_hours_before' => 'integer',
        'cancellation_fee_percentage' => 'decimal:2',
        'provider_qualifications' => 'array',
        'instant_booking' => 'boolean',
        'advance_booking_days' => 'integer',
        'max_advance_booking_days' => 'integer',
        'send_reminder_24h' => 'boolean',
        'send_reminder_1h' => 'boolean',
    ];

    // Polymorphic relationship
    public function product()
    {
        return $this->morphOne(Product::class, 'productable');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Location checks
    public function isVirtual()
    {
        return in_array($this->location_type, ['virtual', 'both']);
    }

    public function isInPerson()
    {
        return in_array($this->location_type, ['in_person', 'both']);
    }

    // Availability
    public function isAvailableOn($day)
    {
        return in_array(strtolower($day), array_map('strtolower', $this->available_days ?? []));
    }

    public function getAvailableTimeSlots($date)
    {
        // If custom time slots defined, return them
        if ($this->time_slots) {
            return $this->time_slots;
        }

        // Generate slots from start_time to end_time
        $slots = [];
        $current = strtotime($this->start_time);
        $end = strtotime($this->end_time);
        $duration = $this->duration_minutes + $this->preparation_time_minutes;

        while ($current < $end) {
            $slots[] = date('H:i', $current);
            $current = strtotime("+{$duration} minutes", $current);
        }

        return $slots;
    }

    public function isSlotAvailable($date, $time)
    {
        // Check if already booked
        $existingBookings = Booking::where('service_product_id', $this->id)
            ->where('booking_date', $date)
            ->where('booking_time', $time)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->count();

        return $existingBookings < $this->max_bookings_per_slot;
    }

    public function canBookOn($date)
    {
        $targetDate = is_string($date) ? \Carbon\Carbon::parse($date) : $date;
        $now = now();

        // Check advance booking limits
        $daysFromNow = $now->diffInDays($targetDate);

        if ($daysFromNow < $this->advance_booking_days) {
            return false;
        }

        if ($daysFromNow > $this->max_advance_booking_days) {
            return false;
        }

        // Check if day is available
        $dayName = $targetDate->format('l');
        if (!$this->isAvailableOn($dayName)) {
            return false;
        }

        return true;
    }

    // Cancellation
    public function canCancelBooking($bookingDate, $bookingTime)
    {
        if (!$this->allow_cancellation) {
            return false;
        }

        $bookingDateTime = \Carbon\Carbon::parse($bookingDate . ' ' . $bookingTime);
        $hoursUntilBooking = now()->diffInHours($bookingDateTime, false);

        return $hoursUntilBooking >= $this->cancellation_hours_before;
    }

    public function calculateCancellationFee($bookingAmount)
    {
        if ($this->cancellation_fee_percentage > 0) {
            return ($bookingAmount * $this->cancellation_fee_percentage) / 100;
        }
        return 0;
    }
}
