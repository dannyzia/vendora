<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Plugins\DisputeResolution\Models\Dispute;
use Plugins\ReviewRating\Models\Review;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'slug',
        'business_name',
        'business_type',
        'logo',
        'banner',
        'bio',
        'trade_license',
        'tin',
        'nid',
        'kyc_documents',
        'bank_name',
        'bank_account',
        'bank_account_name',
        'bank_branch',
        'bank_routing_number',
        'bkash_number',
        'nagad_number',
        'status',
        'rejection_reason',
        'trust_score',
        'subscription_plan',
        'subscription_started_at',
        'subscription_expires_at',
        'commission_rate',
        'commission_type',

        // International Address Structure
        'business_address',
        'country',
        'state_province_region',
        'district_county',
        'city_municipality',
        'area_neighborhood',
        'postal_code',

        // Legacy fields (kept for backward compatibility, but not recommended)
        'city',           // Use city_municipality instead
        'district',       // Use district_county instead
        'division',       // Use state_province_region instead
        'state',          // Use state_province_region instead

        'total_sales',
        'total_orders',
        'available_balance',
        'pending_balance',
        'on_hold_balance',
        'approved_at',
        'last_active_at',

        // Onboarding fields
        'onboarding_status',
        'shop_name',
        'shop_description',
        'business_registration_number',
        'contact_person',
        'contact_phone',
        'contact_email',

        // KYC Document fields
        'nid_number',
        'nid_front_image',
        'nid_back_image',
        'trade_license_number',
        'trade_license_image',
        'trade_license_expiry',

        // Bank/Payment fields
        'bank_account_number',

        // Verification fields
        'phone_verified_at',
        'otp_code',
        'otp_expires_at',

        // Admin approval fields
        'approved_by',
        'rejected_at',
        'rejected_by',

        // Shop settings
        'shop_logo',
        'shop_banner',
        'business_hours',
        'is_featured',
    ];

    protected $casts = [
        'kyc_documents' => 'array',
        'total_sales' => 'decimal:2',
        'total_orders' => 'integer',
        'available_balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
        'on_hold_balance' => 'decimal:2',
        'subscription_started_at' => 'datetime',
        'subscription_expires_at' => 'datetime',
        'commission_rate' => 'decimal:2',
        'approved_at' => 'datetime',
        'last_active_at' => 'datetime',
        'trade_license_expiry' => 'date',
        'phone_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'rejected_at' => 'datetime',
        'business_hours' => 'array',
        'is_featured' => 'boolean',
        'trust_score' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vendor) {
            if (empty($vendor->slug)) {
                $vendor->slug = Str::slug($vendor->business_name ?? $vendor->shop_name ?? 'vendor-' . Str::random(6));
            }
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function disputes()
    {
        return $this->hasMany(Dispute::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    public function commissionLedgers()
    {
        return $this->hasMany(CommissionLedger::class);
    }

    public function trustScoreLogs()
    {
        return $this->hasMany(TrustScoreLog::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOnboarding($query, $status)
    {
        return $query->where('onboarding_status', $status);
    }

    // Status checks
    public function isActive()
    {
        return $this->status === 'approved' && $this->onboarding_status === 'complete';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    // Onboarding status checks
    public function isOnboardingComplete()
    {
        return $this->onboarding_status === 'complete';
    }

    public function isOnboardingIncomplete()
    {
        return $this->onboarding_status === 'incomplete';
    }

    public function isAwaitingApproval()
    {
        return $this->onboarding_status === 'pending';
    }

    // Helper methods
    public function getFullAddress()
    {
        $parts = array_filter([
            $this->business_address,
            $this->area_neighborhood,
            $this->city_municipality,
            $this->district_county,
            $this->state_province_region,
            $this->postal_code,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    public function getAverageRating()
    {
        return $this->reviews()->approved()->avg('rating') ?? 0;
    }

    public function getTotalReviews()
    {
        return $this->reviews()->approved()->count();
    }

    public function canSell()
    {
        return $this->isActive() && $this->isOnboardingComplete();
    }

    public function updateTrustScore($change, $reason, $related = null)
    {
        $oldScore = $this->trust_score;
        $newScore = max(0, min(100, $oldScore + $change));

        $this->update(['trust_score' => $newScore]);

        TrustScoreLog::recordChange($this->id, $oldScore, $newScore, $reason, $related);

        return $newScore;
    }

    // Commission calculation
    public function getCommissionRate()
    {
        if ($this->commission_rate !== null) {
            return $this->commission_rate;
        }

        return config('vendora.commissions.default_rate', 10);
    }

    public function calculateCommission($amount)
    {
        $rate = $this->getCommissionRate();
        return ($amount * $rate) / 100;
    }
}
