<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';

    protected $fillable = [
        'name', 'slug', 'description', 'image', 'meta_title',
        'meta_description', 'meta_keywords', 'meta_tags', 'page_schemas', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'page_schemas' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->name);
            }
        });

        static::updating(function ($blog) {
            if ($blog->isDirty('name')) {
                $blog->slug = Str::slug($blog->name);
            }
        });
    }

    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->description), 150);
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
