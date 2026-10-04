<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Policy::query();

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('meta_title', 'like', '%' . $search . '%')
                  ->orWhere('meta_description', 'like', '%' . $search . '%');
            });
        }

        // Sorting
        $sortField = $request->get('sort_field', 'title');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $policies = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $policies,
            'message' => 'Policies retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:policies,slug',
            'description' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'page_schema' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $slug = $request->slug ?: Str::slug($request->title);

            // Check if slug exists
            if (Policy::where('slug', $slug)->exists()) {
                $slug = $slug . '-' . uniqid();
            }

            $data = [
                'title' => $request->title,
                'slug' => $slug,
                'description' => $request->description,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'page_schema' => $request->page_schema,
            ];

            $policy = Policy::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $policy,
                'message' => 'Policy created successfully'
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Policy save error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Policy save failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $policy = Policy::find($id);

        if (!$policy) {
            return response()->json([
                'success' => false,
                'message' => 'Policy not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $policy,
            'message' => 'Policy retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $policy = Policy::find($id);

        if (!$policy) {
            return response()->json([
                'success' => false,
                'message' => 'Policy not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:policies,slug,' . $id,
            'description' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'page_schema' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $slug = $request->slug ?: Str::slug($request->title);

            // Check if slug exists (excluding current policy)
            if (Policy::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $slug . '-' . uniqid();
            }

            $data = [
                'title' => $request->title,
                'slug' => $slug,
                'description' => $request->description,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'page_schema' => $request->page_schema,
            ];

            $policy->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $policy->fresh(),
                'message' => 'Policy updated successfully'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Policy update error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Policy update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $policy = Policy::find($id);

        if (!$policy) {
            return response()->json([
                'success' => false,
                'message' => 'Policy not found'
            ], 404);
        }

        DB::beginTransaction();

        try {
            $policy->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Policy deleted successfully'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Analyze SEO for policy.
     */
    public function analyzeSeo(Request $request, string $id = null)
    {
        $data = $request->only([
            'title', 'slug', 'description',
            'meta_title', 'meta_description'
        ]);

        $seoMetrics = [];
        $totalWeight = 0;
        $achievedWeight = 0;

        // Analyze Page Title
        $titleLength = strlen($data['title'] ?? '');
        $titleStatus = $titleLength >= 10 && $titleLength <= 60;
        $seoMetrics[] = [
            'name' => 'Page Title',
            'status' => $titleStatus,
            'message' => "{$titleLength}/60 chars",
            'weight' => 20
        ];
        $totalWeight += 20;
        if ($titleStatus) $achievedWeight += 20;

        // Analyze Meta Title
        $metaTitleLength = strlen($data['meta_title'] ?? '');
        $metaTitleStatus = $metaTitleLength >= 10 && $metaTitleLength <= 60;
        $seoMetrics[] = [
            'name' => 'Meta Title',
            'status' => $metaTitleStatus,
            'message' => "{$metaTitleLength}/60 chars",
            'weight' => 20
        ];
        $totalWeight += 20;
        if ($metaTitleStatus) $achievedWeight += 20;

        // Analyze Meta Description
        $metaDescLength = strlen($data['meta_description'] ?? '');
        $metaDescStatus = $metaDescLength >= 120 && $metaDescLength <= 160;
        $seoMetrics[] = [
            'name' => 'Meta Description',
            'status' => $metaDescStatus,
            'message' => "{$metaDescLength}/160 chars",
            'weight' => 15
        ];
        $totalWeight += 15;
        if ($metaDescStatus) $achievedWeight += 15;

        // Analyze Content
        $textContent = strip_tags($data['description'] ?? '');
        $wordCount = str_word_count($textContent);
        $contentStatus = $wordCount >= 300;
        $seoMetrics[] = [
            'name' => 'Content Length',
            'status' => $contentStatus,
            'message' => "{$wordCount}/300+ words",
            'weight' => 20
        ];
        $totalWeight += 20;
        if ($contentStatus) $achievedWeight += 20;

        // Analyze Slug
        $slugLength = strlen($data['slug'] ?? '');
        $slugStatus = $slugLength >= 3 && $slugLength <= 50;
        $seoMetrics[] = [
            'name' => 'URL Slug',
            'status' => $slugStatus,
            'message' => "{$slugLength}/50 chars",
            'weight' => 10
        ];
        $totalWeight += 10;
        if ($slugStatus) $achievedWeight += 10;

        // Calculate overall score
        $seoScore = $totalWeight > 0 ? round(($achievedWeight / $totalWeight) * 100) : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'score' => $seoScore,
                'metrics' => $seoMetrics
            ],
            'message' => 'SEO analysis completed'
        ]);
    }
}
