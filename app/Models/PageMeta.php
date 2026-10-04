<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageMeta extends Model
{
    use HasFactory;
protected $table = 'seo_settings';
    protected $fillable = [
        'home_meta_title',
        'home_meta_description',
        'home_meta_keywords',
        'home_meta_tags',
        'home_title',
        'home_page_schema',
        
        'about_meta_title',
        'about_meta_description',
        'about_meta_keywords',
        'about_meta_tags',
        'about_title',
        'about_page_schema',
        
        'contact_meta_title',
        'contact_meta_description',
        'contact_meta_keywords',
        'contact_meta_tags',
        'contact_title',
        'contact_page_schema',
        
        'service_meta_title',
        'service_meta_description',
        'service_meta_keywords',
        'service_meta_tags',
        'service_title',
        'service_page_schema',
        
        'products_meta_title',
        'products_meta_description',
        'products_meta_keywords',
        'products_meta_tags',
        'product_page_schema',
        
        'faq_meta_title',
        'faq_meta_description',
        'faq_meta_keywords',
        'faq_meta_tags',

        'home_focus_keyword',
    'about_focus_keyword',
    'contact_focus_keyword',
    'service_focus_keyword',
    'products_focus_keyword',
    'faq_focus_keyword',
    ];

    protected $casts = [
        'home_page_schema' => 'array',
        'about_page_schema' => 'array',
        'contact_page_schema' => 'array',
        'service_page_schema' => 'array',
        'product_page_schema' => 'array',
    ];

    // Helper method to get settings for a specific page
    public function getPageSettings($page)
    {
        $prefix = $page . '_';
        
        return [
            'meta_title' => $this->{$prefix . 'meta_title'},
            'meta_description' => $this->{$prefix . 'meta_description'},
            'meta_keywords' => $this->{$prefix . 'meta_keywords'},
            'meta_tags' => $this->{$prefix . 'meta_tags'},
            'title' => $this->{$prefix . 'title'} ?? null,
            'page_schema' => $this->{$prefix . 'page_schema'} ?? null,
        ];
    }

    // SEO Analysis methods
    public function analyzeSeoForPage($page)
    {
        $settings = $this->getPageSettings($page);
        $score = 0;
        $metrics = [];

        // Meta Title Analysis
        $metaTitleLength = strlen($settings['meta_title'] ?? '');
        $metaTitleStatus = $metaTitleLength >= 10 && $metaTitleLength <= 60;
        $metrics[] = [
            'name' => 'Meta Title',
            'status' => $metaTitleStatus,
            'message' => "{$metaTitleLength}/60 chars",
            'weight' => 25
        ];
        if ($metaTitleStatus) $score += 25;

        // Meta Description Analysis
        $metaDescLength = strlen($settings['meta_description'] ?? '');
        $metaDescStatus = $metaDescLength >= 120 && $metaDescLength <= 160;
        $metrics[] = [
            'name' => 'Meta Description',
            'status' => $metaDescStatus,
            'message' => "{$metaDescLength}/160 chars",
            'weight' => 25
        ];
        if ($metaDescStatus) $score += 25;

        // Keywords Analysis
        $keywordsCount = !empty($settings['meta_keywords']) ? 
            count(array_filter(array_map('trim', explode(',', $settings['meta_keywords'])))) : 0;
        $keywordsStatus = $keywordsCount >= 3 && $keywordsCount <= 10;
        $metrics[] = [
            'name' => 'Keywords',
            'status' => $keywordsStatus,
            'message' => "{$keywordsCount} keywords",
            'weight' => 20
        ];
        if ($keywordsStatus) $score += 20;

        // Meta Tags Analysis
        $hasMetaTags = !empty($settings['meta_tags']);
        $metrics[] = [
            'name' => 'Meta Tags',
            'status' => $hasMetaTags,
            'message' => $hasMetaTags ? 'Added' : 'Missing',
            'weight' => 15
        ];
        if ($hasMetaTags) $score += 15;

        // Schema Analysis
        $hasSchema = !empty($settings['page_schema']);
        $metrics[] = [
            'name' => 'Schema Markup',
            'status' => $hasSchema,
            'message' => $hasSchema ? 'Added' : 'Missing',
            'weight' => 15
        ];
        if ($hasSchema) $score += 15;

        return [
            'score' => $score,
            'metrics' => $metrics,
            'settings' => $settings
        ];
    }

    // Get overall SEO health
    public function getOverallSeoHealth()
    {
        $pages = ['home', 'about', 'contact', 'service', 'products', 'faq'];
        $totalScore = 0;
        $pageScores = [];

        foreach ($pages as $page) {
            $analysis = $this->analyzeSeoForPage($page);
            $pageScores[$page] = $analysis['score'];
            $totalScore += $analysis['score'];
        }

        $overallScore = count($pages) > 0 ? round($totalScore / count($pages)) : 0;

        return [
            'overall_score' => $overallScore,
            'page_scores' => $pageScores,
            'total_pages' => count($pages)
        ];
    }
}