<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SubCategory::with(['category']);

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('meta_title', 'like', '%' . $search . '%')
                  ->orWhere('meta_keywords', 'like', '%' . $search . '%');
            });
        }

        // Filter by category
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        // Sorting
        $sortField = $request->get('sort_field', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $subcategories = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $subcategories,
            'message' => 'Sub-categories retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'meta_tags' => 'nullable|string',
            'page_schemas' => 'nullable|string',
            'position' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if slug already exists
        $slug = Str::slug($request->name);
        if (SubCategory::where('slug', $slug)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'A sub-category with this name already exists. Please use a different name.'
            ], 422);
        }

        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'meta_tags' => $request->meta_tags,
            'page_schemas' => $request->page_schemas,
            'position' => $request->position ?? 0,
            'is_active' => $request->is_active ?? true,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('subcategories', 'public');
            $data['image'] = $imagePath;
        }

        $subcategory = SubCategory::create($data);

        return response()->json([
            'success' => true,
            'data' => $subcategory->load(['category']),
            'message' => 'Sub-category created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subcategory = SubCategory::with(['category'])->find($id);

        if (!$subcategory) {
            return response()->json([
                'success' => false,
                'message' => 'Sub-category not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $subcategory,
            'message' => 'Sub-category retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subcategory = SubCategory::find($id);

        if (!$subcategory) {
            return response()->json([
                'success' => false,
                'message' => 'Sub-category not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'meta_tags' => 'nullable|string',
            'page_schemas' => 'nullable|string',
            'position' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if slug already exists (excluding current subcategory)
        $slug = Str::slug($request->name);
        if (SubCategory::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'A sub-category with this name already exists. Please use a different name.'
            ], 422);
        }

        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'meta_tags' => $request->meta_tags,
            'page_schemas' => $request->page_schemas,
            'position' => $request->position ?? 0,
            'is_active' => $request->is_active ?? true,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($subcategory->image) {
                Storage::disk('public')->delete($subcategory->image);
            }
            $imagePath = $request->file('image')->store('subcategories', 'public');
            $data['image'] = $imagePath;
        }

        $subcategory->update($data);

        return response()->json([
            'success' => true,
            'data' => $subcategory->fresh()->load(['category']),
            'message' => 'Sub-category updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subcategory = SubCategory::find($id);

        if (!$subcategory) {
            return response()->json([
                'success' => false,
                'message' => 'Sub-category not found'
            ], 404);
        }

        // Check if sub-category has products
        if ($subcategory->products()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete sub-category with associated products.'
            ], 422);
        }

        // Delete image if exists
        if ($subcategory->image) {
            Storage::disk('public')->delete($subcategory->image);
        }

        $subcategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sub-category deleted successfully'
        ]);
    }

    /**
     * Toggle sub-category status.
     */
    public function toggleStatus(Request $request, string $id)
    {
        $subcategory = SubCategory::find($id);

        if (!$subcategory) {
            return response()->json([
                'success' => false,
                'message' => 'Sub-category not found'
            ], 404);
        }

        $subcategory->update(['is_active' => !$subcategory->is_active]);

        return response()->json([
            'success' => true,
            'data' => $subcategory,
            'message' => 'Sub-category status updated successfully'
        ]);
    }

    /**
     * Get categories for dropdown.
     */
    public function getCategories(Request $request)
    {
        $query = Category::where('is_active', true)
            ->orderBy('name');

        $categories = $query->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
            'message' => 'Categories retrieved successfully'
        ]);
    }

    /**
     * Analyze SEO for a sub-category.
     */
    public function analyzeSeo(Request $request, string $id = null)
    {
        $data = $request->only([
            'name', 'meta_title', 'meta_description',
            'description', 'meta_keywords', 'meta_tags'
        ]);

        $seoMetrics = [];
        $totalWeight = 0;
        $achievedWeight = 0;

        // Analyze Category Name
        $nameLength = strlen($data['name'] ?? '');
        $nameStatus = $nameLength >= 3 && $nameLength <= 60;
        $seoMetrics[] = [
            'name' => 'Category Name',
            'status' => $nameStatus,
            'message' => "{$nameLength}/60 chars",
            'weight' => 20
        ];
        $totalWeight += 20;
        if ($nameStatus) $achievedWeight += 20;

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
        $metaDescStatus = $metaDescLength >= 50 && $metaDescLength <= 160;
        $seoMetrics[] = [
            'name' => 'Meta Description',
            'status' => $metaDescStatus,
            'message' => "{$metaDescLength}/160 chars",
            'weight' => 20
        ];
        $totalWeight += 20;
        if ($metaDescStatus) $achievedWeight += 20;

        // Analyze Description Content
        $textContent = strip_tags($data['description'] ?? '');
        $wordCount = str_word_count($textContent);
        $descStatus = $wordCount >= 50;
        $seoMetrics[] = [
            'name' => 'Description Length',
            'status' => $descStatus,
            'message' => "{$wordCount}/50+ words",
            'weight' => 15
        ];
        $totalWeight += 15;
        if ($descStatus) $achievedWeight += 15;

        // Analyze Keywords
        $keywords = !empty($data['meta_keywords']) ? array_filter(array_map('trim', explode(',', $data['meta_keywords']))) : [];
        $keywordsCount = count($keywords);
        $keywordsStatus = $keywordsCount >= 3 && $keywordsCount <= 10;
        $seoMetrics[] = [
            'name' => 'Keywords',
            'status' => $keywordsStatus,
            'message' => "{$keywordsCount} keywords",
            'weight' => 15
        ];
        $totalWeight += 15;
        if ($keywordsStatus) $achievedWeight += 15;

        // Analyze Meta Tags
        $hasMetaTags = !empty($data['meta_tags']);
        $metaTagsStatus = $hasMetaTags;
        $seoMetrics[] = [
            'name' => 'Meta Tags',
            'status' => $metaTagsStatus,
            'message' => $hasMetaTags ? 'Added' : 'Missing',
            'weight' => 10
        ];
        $totalWeight += 10;
        if ($metaTagsStatus) $achievedWeight += 10;

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

    /**
     * Remove image from sub-category.
     */
    public function removeImage(string $id)
    {
        $subcategory = SubCategory::find($id);

        if (!$subcategory) {
            return response()->json([
                'success' => false,
                'message' => 'Sub-category not found'
            ], 404);
        }

        if ($subcategory->image) {
            Storage::disk('public')->delete($subcategory->image);
            $subcategory->update(['image' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Image removed successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No image found to remove'
        ], 404);
    }
}
