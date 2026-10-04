<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BrandModel extends Model
{
    //
    protected $fillable = [
        'name',
        'brand_id',
        'category_id',
        'slug',
        'description',
        'blog_description',
        'meta_title',
        'meta_description',
        'featured_image',
    ];

     public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }


public function category()
{
    return $this->belongsTo(Category::class);
}

    // Accessor for excerpt
    public function getExcerptAttribute()
    {
        return Str::limit($this->description, 100);
    }

    // Boot method to auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brandModel) {
            if (empty($brandModel->slug)) {
                $brandModel->slug = Str::slug($brandModel->name);
            }
        });

        static::updating(function ($brandModel) {
            if ($brandModel->isDirty('name') && empty($brandModel->slug)) {
                $brandModel->slug = Str::slug($brandModel->name);
            }
        });
    }
}
