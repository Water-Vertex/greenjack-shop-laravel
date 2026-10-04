<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Brand::with(['categories']);

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
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        // Sorting
        $sortField = $request->get('sort_field', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $brands = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $brands,
            'message' => 'Brands retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:brands,slug',
            'description' => 'nullable|string',
            'blog_description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'selectedCategories' => 'required|array|min:1',
            'selectedCategories.*' => 'exists:categories,id'
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
            if (Brand::where('slug', $slug)->exists()) {
                $slug = $slug . '-' . uniqid();
            }

            $imagePath = null;

            if ($request->hasFile('featured_image')) {
                $imagePath = $request->file('featured_image')->store('brands', 'public');
            }

            $data = [
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'blog_description' => $request->blog_description,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'featured_image' => $imagePath,
            ];

            $brand = Brand::create($data);

            // Attach categories
            if ($request->has('selectedCategories')) {
                $brand->categories()->attach($request->selectedCategories);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $brand->load(['categories']),
                'message' => 'Brand created successfully'
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Brand save error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Brand save failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = Brand::with(['categories'])->find($id);

        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $brand,
            'message' => 'Brand retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:brands,slug,' . $id,
            'description' => 'nullable|string',
            'blog_description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'selectedCategories' => 'required|array|min:1',
            'selectedCategories.*' => 'exists:categories,id'
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

            // Check if slug exists (excluding current brand)
            if (Brand::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $slug . '-' . uniqid();
            }

            $imagePath = $brand->featured_image;

            if ($request->hasFile('featured_image')) {
                // Delete old image
                if ($brand->featured_image) {
                    Storage::disk('public')->delete($brand->featured_image);
                }
                $imagePath = $request->file('featured_image')->store('brands', 'public');
            }

            $data = [
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'blog_description' => $request->blog_description,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'featured_image' => $imagePath,
            ];

            $brand->update($data);

            // Sync categories
            if ($request->has('selectedCategories')) {
                $brand->categories()->sync($request->selectedCategories);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $brand->fresh()->load(['categories']),
                'message' => 'Brand updated successfully'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Brand update error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Brand update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::with(['categories'])->find($id);

        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found'
            ], 404);
        }

        DB::beginTransaction();

        try {
            // Delete featured image
            if ($brand->featured_image) {
                Storage::disk('public')->delete($brand->featured_image);
            }

            // Detach categories
            $brand->categories()->detach();

            // Delete brand
            $brand->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Brand deleted successfully'
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
     * Remove brand featured image.
     */
    public function removeImage(string $id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found'
            ], 404);
        }

        if ($brand->featured_image) {
            Storage::disk('public')->delete($brand->featured_image);
            $brand->update(['featured_image' => null]);

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
     * Get categories for brand selection.
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
}
