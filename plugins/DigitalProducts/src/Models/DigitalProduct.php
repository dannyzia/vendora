<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DigitalProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'requires_license_key',
        'license_key_prefix',
        'download_limit',
        'access_period_days',
        'instant_delivery',
        'download_url_expiry_hours',
        'version',
        'changelog',
        'last_updated_at',
        'system_requirements',
        'compatible_platforms',
        'sample_file_path',
        'preview_url',
        'is_external',
        'external_url',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'requires_license_key' => 'boolean',
        'download_limit' => 'integer',
        'access_period_days' => 'integer',
        'instant_delivery' => 'boolean',
        'download_url_expiry_hours' => 'integer',
        'last_updated_at' => 'datetime',
        'compatible_platforms' => 'array',
        'is_external' => 'boolean',
    ];

    // Polymorphic relationship
    public function product()
    {
        return $this->morphOne(Product::class, 'productable');
    }

    public function downloads()
    {
        return $this->hasMany(Download::class, 'product_id');
    }

    // File management
    public function getFileUrlAttribute()
    {
        if ($this->is_external) {
            return $this->external_url;
        }

        if ($this->file_path) {
            return Storage::url($this->file_path);
        }

        return null;
    }

    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    // License key generation
    public function generateLicenseKey()
    {
        $prefix = $this->license_key_prefix ?? 'VBD';
        $random = strtoupper(Str::random(16));
        
        // Format: PREFIX-XXXX-XXXX-XXXX-XXXX
        return sprintf(
            '%s-%s-%s-%s-%s',
            $prefix,
            substr($random, 0, 4),
            substr($random, 4, 4),
            substr($random, 8, 4),
            substr($random, 12, 4)
        );
    }

    // Download management
    public function generateDownloadUrl($orderItemId)
    {
        $token = Str::random(64);
        $expiry = now()->addHours($this->download_url_expiry_hours);

        // Store token in cache with order_item_id and expiry
        cache()->put(
            "download_token_{$token}",
            [
                'order_item_id' => $orderItemId,
                'product_id' => $this->product->id,
                'expires_at' => $expiry,
            ],
            $expiry
        );

        return route('download.file', ['token' => $token]);
    }

    public function canDownload($orderItemId)
    {
        $orderItem = OrderItem::find($orderItemId);

        if (!$orderItem) {
            return false;
        }

        // Check download limit
        if ($this->download_limit > 0) {
            $downloadCount = Download::where('order_item_id', $orderItemId)->count();
            if ($downloadCount >= $this->download_limit) {
                return false;
            }
        }

        // Check access period
        if ($orderItem->download_expires_at && $orderItem->download_expires_at->isPast()) {
            return false;
        }

        return true;
    }

    public function recordDownload($orderItemId, $customerId, $ipAddress, $userAgent)
    {
        return Download::create([
            'order_item_id' => $orderItemId,
            'customer_id' => $customerId,
            'product_id' => $this->product->id,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }
}
