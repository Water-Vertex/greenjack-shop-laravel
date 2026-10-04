<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Series extends Model
{
    use HasFactory;

    protected $table = 'series';

    protected $fillable = [
        'category_id',
        'brand_id',
        'brand_model_id',
        'name',
        'slug'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($series) {
            if (empty($series->slug)) {
                $series->slug = Str::slug($series->name);
            }
        });

        static::updating(function ($series) {
            if ($series->isDirty('name')) {
                $series->slug = Str::slug($series->name);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function brandModel()
    {
        return $this->belongsTo(BrandModel::class, 'brand_model_id');
    }

    // This is the relationship we need for models
    public function models()
    {
        return $this->belongsToMany(BrandModel::class, 'series_models', 'series_id', 'brand_model_id')
                    ->withTimestamps();
    }

    // Alias for seriesModels for backward compatibility
    public function seriesModels()
    {
        return $this->belongsToMany(BrandModel::class, 'series_models', 'series_id', 'brand_model_id')
                    ->withTimestamps();
    }
}
