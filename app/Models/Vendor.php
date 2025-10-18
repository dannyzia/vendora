<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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
        'business_address',
        'city',
        'district',
        'division',
        'postal_code',
        'total_sales',
        'total_orders',
        'available_balance',
        'pending_balance',
        'on_hold_balance',
        'approved_at',
        'last_active_at',
    ];

    protected $casts = [
        'kyc_documents' => 'array',
        'trust_score' => 'integer',
        'commission_rate' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'total_orders' => 'integer',
        'available_balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
        'on_hold_balance' => 'decimal:2',
        'subscription_started_at' => 'datetime',
        'subscription_expires_at' => 'datetime',
        'approved_at' => 'datetime',
        'last_active_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($vendor) {
            if (empty($vendor->slug)) {
                $vendor->slug = Str::slug($vendor->business_name);
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

    public function commissionLedgers()
    {
        return $this->hasMany(CommissionLedger::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    public function disputes()
    {
        return $this->hasMany(Dispute::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function trustScoreLogs()
    {
        return $this->hasMany(TrustScoreLog::class);
    }

    // Status checks
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isUnderReview()
    {
        return $this->status === 'under_review';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    // Subscription checks
    public function hasActiveSubscription()
    {
        return $this->subscription_expires_at && $this->subscription_expires_at->isFuture();
    }

    public function isFreePlan()
    {
        return $this->subscription_plan === 'free';
    }

    public function isBasicPlan()
    {
        return $this->subscription_plan === 'basic';
    }

    public function isProPlan()
    {
        return $this->subscription_plan === 'pro';
    }

    // Trust score
    public function getTrustBadgeAttribute()
    {
        if ($this->trust_score >= 80) {
            return 'verified_seller';
        } elseif ($this->trust_score >= 60) {
            return 'trusted_seller';
        } elseif ($this->trust_score >= 40) {
            return 'standard_seller';
        }
        return 'new_seller';
    }

    // Balance calculations
    public function getTotalBalanceAttribute()
    {
        return $this->available_balance + $this->pending_balance + $this->on_hold_balance;
    }

    // Commission
    public function getCommissionRate()
    {
        // Priority: vendor-specific > subscription plan > default
        if ($this->commission_rate) {
            return $this->commission_rate;
        }

        return config("vendora.subscription_plans.{$this->subscription_plan}.features.commission_rate", 15);
    }
}
