<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'name',
        'phone',
        'email',
        'address_line_1',
        'address_line_2',
        'landmark',
        'area',
        'thana',
        'district',
        'division',
        'postal_code',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeShipping($query)
    {
        return $query->whereIn('type', ['shipping', 'both']);
    }

    public function scopeBilling($query)
    {
        return $query->whereIn('type', ['billing', 'both']);
    }

    public function makeDefault()
    {
        $this->user->addresses()->update(['is_default' => false]);
        $this->update(['is_default' => true]);
    }

    public function getFullAddressAttribute()
    {
        return trim(implode(', ', array_filter([
            $this->address_line_1,
            $this->address_line_2,
            $this->landmark,
            $this->area,
            $this->thana,
            $this->district,
            $this->division,
            $this->postal_code,
        ])));
    }
}
