<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'name',
        'name_bn',
        'slug',
        'description',
        'icon',
        'image',
        'commission_rate',
        'meta_title',
        'meta_description',
        'sort_order',
        'is_active',
        'show_on_homepage',
        'products_count',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'show_on_homepage' => 'boolean',
        'products_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // Relationships
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOnHomepage($query)
    {
        return $query->where('show_on_homepage', true);
    }

    public function scopeRootCategories($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Helpers
    public function isParent()
    {
        return is_null($this->parent_id);
    }

    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    public function getCommissionRate()
    {
        return $this->commission_rate ?? config('vendora.commissions.default_rate');
    }
}
