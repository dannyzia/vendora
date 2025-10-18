<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrustScoreLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'old_score',
        'new_score',
        'change',
        'reason',
        'related_type',
        'related_id',
    ];

    protected $casts = [
        'old_score' => 'integer',
        'new_score' => 'integer',
        'change' => 'integer',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function related()
    {
        return $this->morphTo();
    }

    public static function recordChange($vendorId, $oldScore, $newScore, $reason, $related = null)
    {
        return self::create([
            'vendor_id' => $vendorId,
            'old_score' => $oldScore,
            'new_score' => $newScore,
            'change' => $newScore - $oldScore,
            'reason' => $reason,
            'related_type' => $related ? get_class($related) : null,
            'related_id' => $related?->id,
        ]);
    }
}
