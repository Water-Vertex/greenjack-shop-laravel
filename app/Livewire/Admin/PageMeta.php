<?php

namespace App\Livewire\Admin;

use App\Models\PageMeta as PageMetaModel;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Str;

#[Layout('components.admin-layout')]
class PageMeta extends Component
{
    public $settings;
    public $activeTab = 'home';
    public $seoScore = 0;
    public $seoMetrics = [];

    // Home Page
    public $home_meta_title = '';
    public $home_meta_description = '';
    public $home_meta_keywords = '';
    public $home_meta_tags = '';
    public $home_title = '';
    public $home_page_schema = '';
    public $home_focus_keyword = '';

    // About Page
    public $about_meta_title = '';
    public $about_meta_description = '';
    public $about_meta_keywords = '';
    public $about_meta_tags = '';
    public $about_title = '';
    public $about_page_schema = '';
    public $about_focus_keyword = '';

    // Contact Page
    public $contact_meta_title = '';
    public $contact_meta_description = '';
    public $contact_meta_keywords = '';
    public $contact_meta_tags = '';
    public $contact_title = '';
    public $contact_page_schema = '';
    public $contact_focus_keyword = '';

    // Service Page
    public $service_meta_title = '';
    public $service_meta_description = '';
    public $service_meta_keywords = '';
    public $service_meta_tags = '';
    public $service_title = '';
    public $service_page_schema = '';
    public $service_focus_keyword = '';

    // Products Page
    public $products_meta_title = '';
    public $products_meta_description = '';
    public $products_meta_keywords = '';
    public $products_meta_tags = '';
    public $product_page_schema = '';
    public $products_focus_keyword = '';

    // FAQ Page
    public $faq_meta_title = '';
    public $faq_meta_description = '';
    public $faq_meta_keywords = '';
    public $faq_meta_tags = '';
    public $faq_focus_keyword = '';

    protected $rules = [
        'home_meta_title' => 'nullable|string|max:255',
        'home_meta_description' => 'nullable|string',
        'home_meta_keywords' => 'nullable|string',
        'home_meta_tags' => 'nullable|string',
        'home_title' => 'nullable|string|max:255',
        'home_page_schema' => 'nullable|string',
        'home_focus_keyword' => 'nullable|string|max:255',

        'about_meta_title' => 'nullable|string|max:255',
        'about_meta_description' => 'nullable|string',
        'about_meta_keywords' => 'nullable|string',
        'about_meta_tags' => 'nullable|string',
        'about_title' => 'nullable|string|max:255',
        'about_page_schema' => 'nullable|string',
        'about_focus_keyword' => 'nullable|string|max:255',

        'contact_meta_title' => 'nullable|string|max:255',
        'contact_meta_description' => 'nullable|string',
        'contact_meta_keywords' => 'nullable|string',
        'contact_meta_tags' => 'nullable|string',
        'contact_title' => 'nullable|string|max:255',
        'contact_page_schema' => 'nullable|string',
        'contact_focus_keyword' => 'nullable|string|max:255',

        'service_meta_title' => 'nullable|string|max:255',
        'service_meta_description' => 'nullable|string',
        'service_meta_keywords' => 'nullable|string',
        'service_meta_tags' => 'nullable|string',
        'service_title' => 'nullable|string|max:255',
        'service_page_schema' => 'nullable|string',
        'service_focus_keyword' => 'nullable|string|max:255',

        'products_meta_title' => 'nullable|string|max:255',
        'products_meta_description' => 'nullable|string',
        'products_meta_keywords' => 'nullable|string',
        'products_meta_tags' => 'nullable|string',
        'product_page_schema' => 'nullable|string',
        'products_focus_keyword' => 'nullable|string|max:255',

        'faq_meta_title' => 'nullable|string|max:255',
        'faq_meta_description' => 'nullable|string',
        'faq_meta_keywords' => 'nullable|string',
        'faq_meta_tags' => 'nullable|string',
        'faq_focus_keyword' => 'nullable|string|max:255',
    ];

    public function mount()
    {
        $this->settings = PageMetaModel::first();

        if (!$this->settings) {
            $this->settings = PageMetaModel::create([]);
        }

        $this->loadSettings();
        $this->analyzeCurrentTab();
    }

    public function loadSettings()
    {
        $this->home_meta_title = $this->settings->home_meta_title;
        $this->home_meta_description = $this->settings->home_meta_description;
        $this->home_meta_keywords = $this->settings->home_meta_keywords;
        $this->home_meta_tags = $this->settings->home_meta_tags;
        $this->home_title = $this->settings->home_title;
        $this->home_page_schema = $this->settings->home_page_schema;
        $this->home_focus_keyword = $this->settings->home_focus_keyword;

        $this->about_meta_title = $this->settings->about_meta_title;
        $this->about_meta_description = $this->settings->about_meta_description;
        $this->about_meta_keywords = $this->settings->about_meta_keywords;
        $this->about_meta_tags = $this->settings->about_meta_tags;
        $this->about_title = $this->settings->about_title;
        $this->about_page_schema = $this->settings->about_page_schema;
        $this->about_focus_keyword = $this->settings->about_focus_keyword;

        $this->contact_meta_title = $this->settings->contact_meta_title;
        $this->contact_meta_description = $this->settings->contact_meta_description;
        $this->contact_meta_keywords = $this->settings->contact_meta_keywords;
        $this->contact_meta_tags = $this->settings->contact_meta_tags;
        $this->contact_title = $this->settings->contact_title;
        $this->contact_page_schema = $this->settings->contact_page_schema;
        $this->contact_focus_keyword = $this->settings->contact_focus_keyword;

        $this->service_meta_title = $this->settings->service_meta_title;
        $this->service_meta_description = $this->settings->service_meta_description;
        $this->service_meta_keywords = $this->settings->service_meta_keywords;
        $this->service_meta_tags = $this->settings->service_meta_tags;
        $this->service_title = $this->settings->service_title;
        $this->service_page_schema = $this->settings->service_page_schema;
        $this->service_focus_keyword = $this->settings->service_focus_keyword;

        $this->products_meta_title = $this->settings->products_meta_title;
        $this->products_meta_description = $this->settings->products_meta_description;
        $this->products_meta_keywords = $this->settings->products_meta_keywords;
        $this->products_meta_tags = $this->settings->products_meta_tags;
        $this->product_page_schema = $this->settings->product_page_schema;
        $this->products_focus_keyword = $this->settings->products_focus_keyword;

        $this->faq_meta_title = $this->settings->faq_meta_title;
        $this->faq_meta_description = $this->settings->faq_meta_description;
        $this->faq_meta_keywords = $this->settings->faq_meta_keywords;
        $this->faq_meta_tags = $this->settings->faq_meta_tags;
        $this->faq_focus_keyword = $this->settings->faq_focus_keyword;
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->analyzeCurrentTab();
    }

    public function analyzeCurrentTab()
    {
        $analysis = $this->performSeoAnalysis();
        $this->seoScore = $analysis['score'];
        $this->seoMetrics = $analysis['metrics'];
    }

    private function performSeoAnalysis()
    {
        $metrics = [];
        $score = 0;
        $totalWeight = 0;

        // Get current tab's fields
        $focusKeyword = $this->{$this->activeTab . '_focus_keyword'} ?? '';
        $metaTitle = $this->{$this->activeTab . '_meta_title'} ?? '';
        $metaDescription = $this->{$this->activeTab . '_meta_description'} ?? '';
        $metaKeywords = $this->{$this->activeTab . '_meta_keywords'} ?? '';

        // 1. Focus Keyword Check
        $hasFocusKeyword = !empty($focusKeyword);
        $metrics[] = [
            'name' => '🎯 Focus Keyword',
            'status' => $hasFocusKeyword,
            'message' => $hasFocusKeyword,
            'weight' => 25
        ];
        if ($hasFocusKeyword) $score += 25;
        $totalWeight += 25;

        // 2. Keyword in Meta Title
        if ($hasFocusKeyword && !empty($metaTitle)) {
            $keywordInTitle = stripos($metaTitle, $focusKeyword) !== false;
            $metrics[] = [
                'name' => '🔑 Keyword in Title',
                'status' => $keywordInTitle,
                'message' => $keywordInTitle ? '✓ Keyword found in meta title' : '✗ Add keyword to meta title',
                'weight' => 15
            ];
            if ($keywordInTitle) $score += 15;
            $totalWeight += 15;
        }

        // 3. Keyword in Meta Description
        if ($hasFocusKeyword && !empty($metaDescription)) {
            $keywordInDesc = stripos($metaDescription, $focusKeyword) !== false;
            $metrics[] = [
                'name' => '📝 Keyword in Description',
                'status' => $keywordInDesc,
                'message' => $keywordInDesc ? '✓ Keyword found in description' : '✗ Add keyword to description',
                'weight' => 15
            ];
            if ($keywordInDesc) $score += 15;
            $totalWeight += 15;
        }

        // 4. Meta Title Length
        $titleLength = strlen($metaTitle);
        $titleValid = $titleLength >= 10 && $titleLength <= 60;
        $metrics[] = [
            'name' => '📄 Meta Title',
            'status' => $titleValid,
            'message' => $titleValid ? "✓ {$titleLength}/60 chars" : "✗ {$titleLength}/60 chars (10-60 recommended)",
            'weight' => 20
        ];
        if ($titleValid) $score += 20;
        $totalWeight += 20;

        // 5. Meta Description Length
        $descLength = strlen($metaDescription);
        $descValid = $descLength >= 50 && $descLength <= 160;
        $metrics[] = [
            'name' => '📋 Meta Description',
            'status' => $descValid,
            'message' => $descValid ? "✓ {$descLength}/160 chars" : "✗ {$descLength}/160 chars (50-160 recommended)",
            'weight' => 25
        ];
        if ($descValid) $score += 25;
        $totalWeight += 25;

        // Calculate final score
        $finalScore = $totalWeight > 0 ? round(($score / $totalWeight) * 100) : 0;

        return [
            'score' => $finalScore,
            'metrics' => $metrics
        ];
    }

    public function updated($propertyName)
    {
        $this->analyzeCurrentTab();
    }

    public function save()
    {
        $this->validate();

        $this->settings->update([
            // Home Page
            'home_meta_title' => $this->home_meta_title,
            'home_meta_description' => $this->home_meta_description,
            'home_meta_keywords' => $this->home_meta_keywords,
            'home_meta_tags' => $this->home_meta_tags,
            'home_title' => $this->home_title,
            'home_page_schema' => $this->home_page_schema,
            'home_focus_keyword' => $this->home_focus_keyword,

            // About Page
            'about_meta_title' => $this->about_meta_title,
            'about_meta_description' => $this->about_meta_description,
            'about_meta_keywords' => $this->about_meta_keywords,
            'about_meta_tags' => $this->about_meta_tags,
            'about_title' => $this->about_title,
            'about_page_schema' => $this->about_page_schema,
            'about_focus_keyword' => $this->about_focus_keyword,

            // Contact Page
            'contact_meta_title' => $this->contact_meta_title,
            'contact_meta_description' => $this->contact_meta_description,
            'contact_meta_keywords' => $this->contact_meta_keywords,
            'contact_meta_tags' => $this->contact_meta_tags,
            'contact_title' => $this->contact_title,
            'contact_page_schema' => $this->contact_page_schema,
            'contact_focus_keyword' => $this->contact_focus_keyword,

            // Service Page
            'service_meta_title' => $this->service_meta_title,
            'service_meta_description' => $this->service_meta_description,
            'service_meta_keywords' => $this->service_meta_keywords,
            'service_meta_tags' => $this->service_meta_tags,
            'service_title' => $this->service_title,
            'service_page_schema' => $this->service_page_schema,
            'service_focus_keyword' => $this->service_focus_keyword,

            // Products Page
            'products_meta_title' => $this->products_meta_title,
            'products_meta_description' => $this->products_meta_description,
            'products_meta_keywords' => $this->products_meta_keywords,
            'products_meta_tags' => $this->products_meta_tags,
            'product_page_schema' => $this->product_page_schema,
            'products_focus_keyword' => $this->products_focus_keyword,

            // FAQ Page
            'faq_meta_title' => $this->faq_meta_title,
            'faq_meta_description' => $this->faq_meta_description,
            'faq_meta_keywords' => $this->faq_meta_keywords,
            'faq_meta_tags' => $this->faq_meta_tags,
            'faq_focus_keyword' => $this->faq_focus_keyword,
        ]);

        session()->flash('success', 'SEO settings updated successfully.');
    }

    public function render()
    {
        $overallHealth = $this->getOverallHealth();

        return view('livewire.admin.page-meta', [
            'overallHealth' => $overallHealth
        ]);
    }

    private function getOverallHealth()
    {
        $pages = ['home', 'about', 'contact', 'service', 'products', 'faq'];
        $pageScores = [];

        foreach ($pages as $page) {
            $focusKeyword = $this->{$page . '_focus_keyword'} ?? '';
            $metaTitle = $this->{$page . '_meta_title'} ?? '';
            $metaDescription = $this->{$page . '_meta_description'} ?? '';

            $score = 0;
            $total = 0;

            // Calculate page score
            if (!empty($focusKeyword)) { $score += 25; $total += 25; }
            if (!empty($metaTitle) && strlen($metaTitle) >= 10 && strlen($metaTitle) <= 60) { $score += 35; $total += 35; }
            if (!empty($metaDescription) && strlen($metaDescription) >= 50 && strlen($metaDescription) <= 160) { $score += 40; $total += 40; }

            $pageScores[$page] = $total > 0 ? round(($score / $total) * 100) : 0;
        }

        $overallScore = count($pageScores) > 0 ? round(array_sum($pageScores) / count($pageScores)) : 0;

        return [
            'overall_score' => $overallScore,
            'total_pages' => count($pages),
            'page_scores' => $pageScores
        ];
    }
}
