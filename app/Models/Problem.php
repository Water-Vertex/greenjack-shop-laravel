<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Problem extends Model
{
    //
    protected $fillable = [
        'brand_model_id',
        'brand_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discounted_price',
        'is_featured',
        'is_recommended',
    ];


     // Relationships
    public function model()
    {
        return $this->belongsTo(BrandModel::class, 'brand_model_id');
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

        static::creating(function ($problem) {
            if (empty($problem->slug)) {
                $problem->slug = Str::slug($problem->name);
            }
        });

        static::updating(function ($problem) {
            if ($problem->isDirty('name') && empty($problem->slug)) {
                $problem->slug = Str::slug($problem->name);
            }
        });
    }
}
