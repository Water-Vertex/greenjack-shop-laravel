<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Series;
use App\Models\Category;
use App\Models\Brand;
use App\Models\BrandModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SeriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Series::with(['category', 'brand', 'brandModel', 'seriesModels']);

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
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
        $series = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $series,
            'message' => 'Series retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:series,slug',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'brand_model_id' => 'nullable|exists:brand_models,id',
            'selectedModels' => 'required|array|min:1',
            'selectedModels.*' => 'exists:brand_models,id'
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
            if (Series::where('slug', $slug)->exists()) {
                $slug = $slug . '-' . uniqid();
            }

            $data = [
                'name' => $request->name,
                'slug' => $slug,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'brand_model_id' => $request->brand_model_id,
            ];

            $series = Series::create($data);

            // Attach selected models
            if ($request->has('selectedModels')) {
                $series->seriesModels()->attach($request->selectedModels);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $series->load(['category', 'brand', 'brandModel', 'seriesModels']),
                'message' => 'Series created successfully'
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Series save error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Series save failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $series = Series::with(['category', 'brand', 'brandModel', 'seriesModels'])->find($id);

        if (!$series) {
            return response()->json([
                'success' => false,
                'message' => 'Series not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $series,
            'message' => 'Series retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $series = Series::find($id);

        if (!$series) {
            return response()->json([
                'success' => false,
                'message' => 'Series not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:series,slug,' . $id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'brand_model_id' => 'nullable|exists:brand_models,id',
            'selectedModels' => 'required|array|min:1',
            'selectedModels.*' => 'exists:brand_models,id'
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

            // Check if slug exists (excluding current series)
            if (Series::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $slug . '-' . uniqid();
            }

            $data = [
                'name' => $request->name,
                'slug' => $slug,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'brand_model_id' => $request->brand_model_id,
            ];

            $series->update($data);

            // Sync selected models
            if ($request->has('selectedModels')) {
                $series->seriesModels()->sync($request->selectedModels);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $series->fresh()->load(['category', 'brand', 'brandModel', 'seriesModels']),
                'message' => 'Series updated successfully'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Series update error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Series update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $series = Series::find($id);

        if (!$series) {
            return response()->json([
                'success' => false,
                'message' => 'Series not found'
            ], 404);
        }

        DB::beginTransaction();

        try {
            // Detach all models first
            $series->seriesModels()->detach();

            $series->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Series deleted successfully'
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
     * Get brands with models for a category.
     */
    public function getBrandsWithModels(Request $request)
    {
        $categoryId = $request->get('category_id');

        if (!$categoryId) {
            return response()->json([
                'success' => false,
                'message' => 'Category ID is required'
            ], 422);
        }

        // Get distinct brand IDs that have models in this category
        $brandIds = BrandModel::where('category_id', $categoryId)
            ->distinct()
            ->pluck('brand_id');

        $brands = Brand::whereIn('id', $brandIds)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $brands,
            'message' => 'Brands retrieved successfully'
        ]);
    }

    

    public function getModelsByCategoryAndBrand(Request $request)
    {
        $categoryId = $request->get('category_id');
        $brandId = $request->get('brand_id');

        if (!$categoryId || !$brandId) {
            return response()->json([
                'success' => false,
                'message' => 'Category ID and Brand ID are required'
            ], 422);
        }

        $models = BrandModel::where('category_id', $categoryId)
            ->where('brand_id', $brandId)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $models, // Return directly, not wrapped in data.data
            'message' => 'Models retrieved successfully'
        ]);
    }
}
