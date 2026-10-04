<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'blog_description',
        'meta_title',
        'meta_description',
        'featured_image',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'brand_category', 'brand_id', 'category_id')
                    ->withTimestamps();
    }

    /**
     * Boot function for automatic slug generation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });

        static::updating(function ($brand) {
            if ($brand->isDirty('name') && empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }

    /**
     * Get the excerpt of the description.
     */
    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->description), 100);
    }

    /**
     * Get the excerpt of the blog description.
     */
    public function getBlogExcerptAttribute()
    {
        return Str::limit(strip_tags($this->blog_description), 100);
    }

    /**
     * Get the featured image URL.
     */
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return Storage::url($this->featured_image);
        }
        return null;
    }

    /**
     * Get SEO meta data as array.
     */
    public function getSeoMetaAttribute(): array
    {
        return [
            'title' => $this->meta_title ?: $this->name,
            'description' => $this->meta_description ?: $this->excerpt,
        ];
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Scope a query to search brands.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('blog_description', 'like', "%{$search}%")
              ->orWhere('meta_title', 'like', "%{$search}%")
              ->orWhere('meta_description', 'like', "%{$search}%");
        });
    }

    /**
     * Check if brand has featured image.
     */
    public function hasFeaturedImage(): bool
    {
        return !empty($this->featured_image);
    }

    /**
     * Get products count (if you have products table with brand_id)
     */
   
}