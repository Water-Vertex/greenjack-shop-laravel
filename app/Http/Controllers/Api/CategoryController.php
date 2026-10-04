<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Category::with(['parent', 'children']);

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

        // Sorting
        $sortField = $request->get('sort_field', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $categories = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $categories,
            'message' => 'Categories retrieved successfully'
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
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'meta_tags' => 'nullable|string',
            'page_schemas' => 'nullable|string',
            'position' => 'integer|min:0',
            'is_active' => 'boolean',
            'is_repairing_category' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if slug already exists
        $slug = Str::slug($request->name);
        if (Category::where('slug', $slug)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'A category with this name already exists. Please use a different name.'
            ], 422);
        }

        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'parent_id' => $request->parent_id ?: null,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'meta_tags' => $request->meta_tags,
            'page_schemas' => $request->page_schemas,
            'position' => $request->position ?? 0,
            'is_active' => $request->is_active ?? true,
            'is_repairing_category' => $request->is_repairing_category ?? false,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $data['image'] = $imagePath;
        }

        $category = Category::create($data);

        return response()->json([
            'success' => true,
            'data' => $category->load(['parent', 'children']),
            'message' => 'Category created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::with(['parent', 'children'])->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'Category retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'meta_tags' => 'nullable|string',
            'page_schemas' => 'nullable|string',
            'position' => 'integer|min:0',
            'is_active' => 'boolean',
            'is_repairing_category' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if slug already exists (excluding current category)
        $slug = Str::slug($request->name);
        if (Category::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'A category with this name already exists. Please use a different name.'
            ], 422);
        }

        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'parent_id' => $request->parent_id ?: null,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'meta_tags' => $request->meta_tags,
            'page_schemas' => $request->page_schemas,
            'position' => $request->position ?? 0,
            'is_active' => $request->is_active ?? true,
            'is_repairing_category' => $request->is_repairing_category ?? false,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $imagePath = $request->file('image')->store('categories', 'public');
            $data['image'] = $imagePath;
        }

        $category->update($data);

        return response()->json([
            'success' => true,
            'data' => $category->fresh()->load(['parent', 'children']),
            'message' => 'Category updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        // Check if category has subcategories
        if ($category->children()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with subcategories. Please delete subcategories first.'
            ], 422);
        }

        // Check if category has products
        if ($category->products()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with associated products.'
            ], 422);
        }

        // Delete image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    /**
     * Toggle category status.
     */
    public function toggleStatus(Request $request, string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $category->update(['is_active' => !$category->is_active]);

        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'Category status updated successfully'
        ]);
    }

    /**
     * Get parent categories.
     */
    public function getParentCategories(Request $request)
    {
        $query = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('name');

        // Exclude current category in edit mode
        if ($request->has('exclude_id')) {
            $query->where('id', '!=', $request->exclude_id);
        }

        $parentCategories = $query->get();

        return response()->json([
            'success' => true,
            'data' => $parentCategories,
            'message' => 'Parent categories retrieved successfully'
        ]);
    }

    /**
     * Analyze SEO for a category.
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
     * Remove image from category.
     */
    public function removeImage(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
            $category->update(['image' => null]);

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
