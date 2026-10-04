<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Problem;
use App\Models\Category;
use App\Models\Brand;
use App\Models\BrandModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProblemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Problem::with(['model']);

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
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

        // Filter by model
        if ($request->has('model_id') && !empty($request->model_id)) {
            $query->where('brand_model_id', $request->model_id);
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortField = $request->get('sort_field', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $problems = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $problems,
            'message' => 'Problems retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:problems,slug',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'model_id' => 'required|exists:brand_models,id',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,inactive',
            'is_featured' => 'nullable|boolean',
            'is_recommended' => 'nullable|boolean',
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
            if (Problem::where('slug', $slug)->exists()) {
                $slug = $slug . '-' . uniqid();
            }

            $data = [
                'name' => $request->name,
                'slug' => $slug,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'brand_model_id' => $request->model_id,
                'description' => $request->description,
                'price' => $request->price,
                'discounted_price' => $request->discounted_price,
                'status' => $request->status ?? 'active',
                'is_featured' => $request->is_featured ?? false,
                'is_recommended' => $request->is_recommended ?? false,
            ];

            $problem = Problem::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $problem->load(['model', 'brand', 'category']),
                'message' => 'Problem created successfully'
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Problem save error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Problem save failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $problem = Problem::with(['model'])->find($id);

        if (!$problem) {
            return response()->json([
                'success' => false,
                'message' => 'Problem not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $problem,
            'message' => 'Problem retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $problem = Problem::find($id);

        if (!$problem) {
            return response()->json([
                'success' => false,
                'message' => 'Problem not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:problems,slug,' . $id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'model_id' => 'required|exists:brand_models,id',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,inactive',
            'is_featured' => 'nullable|boolean',
            'is_recommended' => 'nullable|boolean',
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

            // Check if slug exists (excluding current problem)
            if (Problem::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $slug . '-' . uniqid();
            }

            $data = [
                'name' => $request->name,
                'slug' => $slug,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'brand_model_id' => $request->model_id,
                'description' => $request->description,
                'price' => $request->price,
                'discounted_price' => $request->discounted_price,
                'status' => $request->status ?? 'active',
                'is_featured' => $request->is_featured ?? false,
                'is_recommended' => $request->is_recommended ?? false,
            ];

            $problem->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $problem->fresh()->load(['model', 'brand', 'category']),
                'message' => 'Problem updated successfully'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Problem update error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Problem update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $problem = Problem::find($id);

        if (!$problem) {
            return response()->json([
                'success' => false,
                'message' => 'Problem not found'
            ], 404);
        }

        DB::beginTransaction();

        try {
            $problem->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Problem deleted successfully'
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
     * Toggle problem status.
     */
    public function toggleStatus(string $id)
    {
        $problem = Problem::find($id);

        if (!$problem) {
            return response()->json([
                'success' => false,
                'message' => 'Problem not found'
            ], 404);
        }

        $problem->status = $problem->status === 'active' ? 'inactive' : 'active';
        $problem->save();

        return response()->json([
            'success' => true,
            'data' => $problem,
            'message' => 'Problem status updated successfully'
        ]);
    }
}
