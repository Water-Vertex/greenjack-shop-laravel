<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BrandModel;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BrandModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BrandModel::with(['category', 'brand']);

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('blog_description', 'like', '%' . $search . '%')
                  ->orWhere('meta_title', 'like', '%' . $search . '%')
                  ->orWhere('meta_description', 'like', '%' . $search . '%');
            });
        }

        // Filter by category
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by brand
        if ($request->has('brand_id') && !empty($request->brand_id)) {
            $query->where('brand_id', $request->brand_id);
        }

        // Sorting
        $sortField = $request->get('sort_field', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $brandModels = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $brandModels,
            'message' => 'Brand models retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'brand_id' => 'required|integer|exists:brands,id',
            'slug' => 'nullable|string|max:255|unique:brand_models,slug',
            'description' => 'nullable|string',
            'blog_description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $slug = $request->slug ?: Str::slug($request->name);

            // Check if slug exists
            if (BrandModel::where('slug', $slug)->exists()) {
                $slug = $slug . '-' . uniqid();
            }

            $imagePath = null;

            if ($request->hasFile('featured_image')) {
                $imagePath = $request->file('featured_image')->store('brand_models', 'public');
            }

            $data = [
                'name' => $request->name,
                'slug' => $slug,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'description' => $request->description,
                'blog_description' => $request->blog_description,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'featured_image' => $imagePath,
            ];

            $brandModel = BrandModel::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $brandModel->load(['category', 'brand']),
                'message' => 'Brand model created successfully'
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Brand model save error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Brand model save failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brandModel = BrandModel::with(['category', 'brand'])->find($id);

        if (!$brandModel) {
            return response()->json([
                'success' => false,
                'message' => 'Brand model not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $brandModel,
            'message' => 'Brand model retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $brandModel = BrandModel::find($id);

        if (!$brandModel) {
            return response()->json([
                'success' => false,
                'message' => 'Brand model not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'brand_id' => 'required|integer|exists:brands,id',
            'slug' => 'nullable|string|max:255|unique:brand_models,slug,' . $id,
            'description' => 'nullable|string',
            'blog_description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $slug = $request->slug ?: Str::slug($request->name);

            // Check if slug exists (excluding current model)
            if (BrandModel::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $slug . '-' . uniqid();
            }

            $imagePath = $brandModel->featured_image;

            if ($request->hasFile('featured_image')) {
                // Delete old image
                if ($brandModel->featured_image) {
                    Storage::disk('public')->delete($brandModel->featured_image);
                }
                $imagePath = $request->file('featured_image')->store('brand_models', 'public');
            } elseif ($request->has('remove_image') && $request->remove_image) {
                // Handle image removal
                if ($brandModel->featured_image) {
                    Storage::disk('public')->delete($brandModel->featured_image);
                }
                $imagePath = null;
            }

            $data = [
                'name' => $request->name,
                'slug' => $slug,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'description' => $request->description,
                'blog_description' => $request->blog_description,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'featured_image' => $imagePath,
            ];

            $brandModel->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $brandModel->fresh()->load(['category', 'brand']),
                'message' => 'Brand model updated successfully'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Brand model update error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Brand model update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brandModel = BrandModel::find($id);

        if (!$brandModel) {
            return response()->json([
                'success' => false,
                'message' => 'Brand model not found'
            ], 404);
        }

        DB::beginTransaction();

        try {
            // Delete featured image
            if ($brandModel->featured_image) {
                Storage::disk('public')->delete($brandModel->featured_image);
            }

            $brandModel->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Brand model deleted successfully'
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
     * Remove brand model featured image.
     */
    public function removeImage(string $id)
    {
        $brandModel = BrandModel::find($id);

        if (!$brandModel) {
            return response()->json([
                'success' => false,
                'message' => 'Brand model not found'
            ], 404);
        }

        if ($brandModel->featured_image) {
            Storage::disk('public')->delete($brandModel->featured_image);
            $brandModel->update(['featured_image' => null]);

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

    /**
     * Get brands by category for dropdown.
     */
    public function getBrandsByCategory(Request $request)
    {
        $categoryId = $request->get('category_id');

        if (!$categoryId) {
            return response()->json([
                'success' => false,
                'message' => 'Category ID is required'
            ], 422);
        }

        $brands = Brand::whereHas('categories', function ($query) use ($categoryId) {
            $query->where('categories.id', $categoryId);
        })
        ->orderBy('name')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $brands,
            'message' => 'Brands retrieved successfully'
        ]);
    }

    /**
     * Get categories for dropdown.
     */
    public function getCategories(Request $request)
    {
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
            'message' => 'Categories retrieved successfully'
        ]);
    }

    /**
     * Get all brands (for dropdown without category filter).
     */
    public function getAllBrands(Request $request)
    {
        $brands = Brand::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $brands,
            'message' => 'All brands retrieved successfully'
        ]);
    }

    /**
     * Check if slug is available.
     */
    public function checkSlug(Request $request)
    {
        $slug = Str::slug($request->get('name', ''));
        $id = $request->get('id', null);

        if (empty($slug)) {
            return response()->json([
                'success' => false,
                'message' => 'Name is required to generate slug'
            ], 422);
        }

        $exists = BrandModel::where('slug', $slug)
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json([
            'success' => true,
            'data' => [
                'slug' => $slug,
                'available' => !$exists
            ],
            'message' => $exists ? 'Slug is already taken' : 'Slug is available'
        ]);
    }

    /**
     * Bulk delete brand models.
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:brand_models,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $count = 0;
            foreach ($request->ids as $id) {
                $brandModel = BrandModel::find($id);
                if ($brandModel) {
                    if ($brandModel->featured_image) {
                        Storage::disk('public')->delete($brandModel->featured_image);
                    }
                    $brandModel->delete();
                    $count++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => ['deleted_count' => $count],
                'message' => "{$count} brand models deleted successfully"
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Bulk delete failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
