<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PageMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PageMetaController extends Controller
{
    /**
     * Get page meta settings.
     */
    public function index()
    {
        $settings = PageMeta::first();

        if (!$settings) {
            $settings = PageMeta::create([]);
        }

        return response()->json([
            'success' => true,
            'data' => $settings,
            'message' => 'Page meta settings retrieved successfully'
        ]);
    }

    /**
     * Update page meta settings.
     */
    public function update(Request $request)
    {
        $settings = PageMeta::first();

        if (!$settings) {
            $settings = PageMeta::create([]);
        }

        $validator = Validator::make($request->all(), [
            // Home Page
            'home_meta_title' => 'nullable|string|max:255',
            'home_meta_description' => 'nullable|string',
            'home_meta_keywords' => 'nullable|string',
            'home_meta_tags' => 'nullable|string',
            'home_title' => 'nullable|string|max:255',
            'home_page_schema' => 'nullable|string',
            'home_focus_keyword' => 'nullable|string|max:255',

            // About Page
            'about_meta_title' => 'nullable|string|max:255',
            'about_meta_description' => 'nullable|string',
            'about_meta_keywords' => 'nullable|string',
            'about_meta_tags' => 'nullable|string',
            'about_title' => 'nullable|string|max:255',
            'about_page_schema' => 'nullable|string',
            'about_focus_keyword' => 'nullable|string|max:255',

            // Contact Page
            'contact_meta_title' => 'nullable|string|max:255',
            'contact_meta_description' => 'nullable|string',
            'contact_meta_keywords' => 'nullable|string',
            'contact_meta_tags' => 'nullable|string',
            'contact_title' => 'nullable|string|max:255',
            'contact_page_schema' => 'nullable|string',
            'contact_focus_keyword' => 'nullable|string|max:255',

            // Service Page
            'service_meta_title' => 'nullable|string|max:255',
            'service_meta_description' => 'nullable|string',
            'service_meta_keywords' => 'nullable|string',
            'service_meta_tags' => 'nullable|string',
            'service_title' => 'nullable|string|max:255',
            'service_page_schema' => 'nullable|string',
            'service_focus_keyword' => 'nullable|string|max:255',

            // Products Page
            'products_meta_title' => 'nullable|string|max:255',
            'products_meta_description' => 'nullable|string',
            'products_meta_keywords' => 'nullable|string',
            'products_meta_tags' => 'nullable|string',
            'product_page_schema' => 'nullable|string',
            'products_focus_keyword' => 'nullable|string|max:255',

            // FAQ Page
            'faq_meta_title' => 'nullable|string|max:255',
            'faq_meta_description' => 'nullable|string',
            'faq_meta_keywords' => 'nullable|string',
            'faq_meta_tags' => 'nullable|string',
            'faq_focus_keyword' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $settings->update($request->all());

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $settings->fresh(),
                'message' => 'Page meta settings updated successfully'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Page meta update error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Page meta update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Analyze SEO for a specific page.
     */
    public function analyzeSeo(Request $request, string $page)
    {
        $data = $request->only([
            'focus_keyword', 'meta_title', 'meta_description'
        ]);

        $focusKeyword = $data['focus_keyword'] ?? '';
        $metaTitle = $data['meta_title'] ?? '';
        $metaDescription = $data['meta_description'] ?? '';

        $metrics = [];
        $score = 0;
        $totalWeight = 0;

        // 1. Focus Keyword Check
        $hasFocusKeyword = !empty($focusKeyword);
        $metrics[] = [
            'name' => '🎯 Focus Keyword',
            'status' => $hasFocusKeyword,
            'message' => $hasFocusKeyword ? '✓ Focus keyword is set' : '✗ Add a focus keyword',
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
        } elseif (!$hasFocusKeyword) {
            $metrics[] = [
                'name' => '🔑 Keyword in Title',
                'status' => false,
                'message' => '✗ Set a focus keyword first',
                'weight' => 15
            ];
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
        } elseif (!$hasFocusKeyword) {
            $metrics[] = [
                'name' => '📝 Keyword in Description',
                'status' => false,
                'message' => '✗ Set a focus keyword first',
                'weight' => 15
            ];
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

        return response()->json([
            'success' => true,
            'data' => [
                'score' => $finalScore,
                'metrics' => $metrics
            ],
            'message' => 'SEO analysis completed'
        ]);
    }

    /**
     * Get overall health of all pages.
     */
    public function getOverallHealth()
    {
        $settings = PageMeta::first();

        if (!$settings) {
            $settings = PageMeta::create([]);
        }

        $pages = ['home', 'about', 'contact', 'service', 'products', 'faq'];
        $pageScores = [];

        foreach ($pages as $page) {
            $focusKeyword = $settings->{$page . '_focus_keyword'} ?? '';
            $metaTitle = $settings->{$page . '_meta_title'} ?? '';
            $metaDescription = $settings->{$page . '_meta_description'} ?? '';

            $score = 0;
            $total = 0;

            if (!empty($focusKeyword)) { $score += 25; $total += 25; }
            if (!empty($metaTitle) && strlen($metaTitle) >= 10 && strlen($metaTitle) <= 60) { $score += 35; $total += 35; }
            if (!empty($metaDescription) && strlen($metaDescription) >= 50 && strlen($metaDescription) <= 160) { $score += 40; $total += 40; }

            $pageScores[$page] = $total > 0 ? round(($score / $total) * 100) : 0;
        }

        $overallScore = count($pageScores) > 0 ? round(array_sum($pageScores) / count($pageScores)) : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'overall_score' => $overallScore,
                'total_pages' => count($pages),
                'page_scores' => $pageScores
            ],
            'message' => 'Overall health retrieved successfully'
        ]);
    }
}
