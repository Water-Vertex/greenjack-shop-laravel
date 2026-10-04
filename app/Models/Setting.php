<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'favicon',
        'main_logo',
        'footer_logo',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'pintrest',
        'youtube',
        'email',
        'about',
        'site_title',
        'meta_title',
        'meta_tags',
        'meta_desc',
        'meta_keywords',
        'head_tags',
        'body_tags',
        'phone',
        'address',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get social media links as array
     */
    public function getSocialLinksAttribute()
    {
        return [
            'facebook' => $this->facebook,
            'twitter' => $this->twitter,
            'linkedin' => $this->linkedin,
            'instagram' => $this->instagram,
            'pintrest' => $this->pintrest,
            'youtube' => $this->youtube,
        ];
    }

    /**
     * Get contact information
     */
    public function getContactInfoAttribute()
    {
        return [
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
        ];
    }

    /**
     * Get SEO data
     */
    public function getSeoDataAttribute()
    {
        return [
            'site_title' => $this->site_title,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_desc,
            'meta_keywords' => $this->meta_keywords,
            'meta_tags' => $this->meta_tags,
        ];
    }

    /**
     * Get the main logo URL
     */
    public function getMainLogoUrlAttribute()
    {
        return $this->main_logo ? asset('storage/' . $this->main_logo) : null;
    }

    /**
     * Get the footer logo URL
     */
    public function getFooterLogoUrlAttribute()
    {
        return $this->footer_logo ? asset('storage/' . $this->footer_logo) : null;
    }

    /**
     * Get the favicon URL
     */
    public function getFaviconUrlAttribute()
    {
        return $this->favicon ? asset('storage/' . $this->favicon) : null;
    }

    /**
     * Check if social media links exist
     */
    public function hasSocialLinks()
    {
        return !empty(array_filter($this->social_links));
    }

    /**
     * Get instance (singleton pattern)
     */
    public static function getSettings()
    {
        return static::first() ?? new static();
    }
}