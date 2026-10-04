<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'price',
        'discounted_price',
        'stock',
        'is_active',
        'upc',
        'sku',
        'asin',
        'manufacturer',
        'moq',
        'meta_description',
        'meta_title',
        'meta_keywords',
        'meta_tags',
        'page_schemas',
        'category_id',
        'sub_category_id',
        'focus_keyword',
        'type',
        'deal_of_the_day',
        'has_variations' // <-- Ye field add karna zaroori hai
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean',
        'moq' => 'integer',
        'has_variations' => 'boolean', // <-- Ye bhi add karein
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

   public function attributes()
{
    // ✅ FIXED: Remove 'using()' if ProductAttributeValue class doesn't exist
    return $this->belongsToMany(AttributeValue::class, 'product_attribute_values')
                ->withTimestamps();
}

    public function variations()
    {
        return $this->hasMany(Variation::class);
    }

    // Get variations with their attribute values
    public function variationsWithAttributes()
    {
        return $this->hasMany(Variation::class)->with(['attributeValues', 'attributeValues.attribute']);
    }

    // Get active variations only
    public function activeVariations()
    {
        return $this->hasMany(Variation::class)->where('is_active', true);
    }

    // Check if product has variations
    public function hasVariations()
    {
        return $this->variations()->count() > 0;
    }

    // Get minimum price across variations
    public function getMinPrice()
    {
        if ($this->hasVariations()) {
            return $this->variations()->min('price');
        }
        return $this->price;
    }

    // Get maximum price across variations
    public function getMaxPrice()
    {
        if ($this->hasVariations()) {
            return $this->variations()->max('price');
        }
        return $this->price;
    }

    // Get total stock including variations
    public function getTotalStock()
    {
        if ($this->hasVariations()) {
            return $this->variations()->sum('stock');
        }
        return $this->stock;
    }

    // Check if product is in stock
    public function isInStock()
    {
        if ($this->hasVariations()) {
            return $this->variations()->where('stock', '>', 0)->exists();
        }
        return $this->stock > 0;
    }

    // Get sale price (discounted price if available)
    public function getSalePrice()
    {
        return $this->discounted_price ?? $this->price;
    }

    // Get price range string for display
    public function getPriceRangeAttribute()
    {
        if ($this->hasVariations()) {
            $min = $this->variations()->min('price');
            $max = $this->variations()->max('price');

            if ($min == $max) {
                return number_format($min, 2);
            }
            return number_format($min, 2) . ' - ' . number_format($max, 2);
        }
        return number_format($this->price, 2);
    }

    // Get discount percentage
    public function getDiscountPercentageAttribute()
    {
        if ($this->discounted_price && $this->price > 0) {
            $discount = (($this->price - $this->discounted_price) / $this->price) * 100;
            return round($discount);
        }
        return 0;
    }

    // Scope for active products
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for featured products
    public function scopeFeatured($query)
    {
        return $query->where('type', 'featured');
    }

    // Scope for best sellers
    public function scopeBestSellers($query)
    {
        return $query->where('type', 'best_seller');
    }

    // Scope for products with variations
    public function scopeWithVariations($query)
    {
        return $query->where('has_variations', true);
    }

    // Scope for simple products (no variations)
    public function scopeSimple($query)
    {
        return $query->where('has_variations', false);
    }

    // Scope for products in stock
    public function scopeInStock($query)
    {
        return $query->where(function($q) {
            $q->where('has_variations', false)
              ->where('stock', '>', 0)
              ->orWhere('has_variations', true)
              ->whereHas('variations', function($sub) {
                  $sub->where('stock', '>', 0);
              });
        });
    }

    // Get formatted price
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2);
    }

    // Get formatted discounted price
    public function getFormattedDiscountedPriceAttribute()
    {
        return $this->discounted_price ? number_format($this->discounted_price, 2) : null;
    }

    // Accessor for type badge (HTML)
    public function getTypeBadgeAttribute()
    {
        return match ($this->type) {
            'featured' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800"><i class="fas fa-star mr-1"></i>Featured</span>',
            'best_seller' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800"><i class="fas fa-trophy mr-1"></i>Best Seller</span>',
            default => null,
        };
    }
}
