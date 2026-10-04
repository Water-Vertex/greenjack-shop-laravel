<?php
// app/Http/Controllers/Api/AttributeController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Attribute::with('values');

        if ($request->has('is_variation')) {
            $query->where('is_variation', $request->is_variation);
        }

        if ($request->has('is_visible')) {
            $query->where('is_visible', $request->is_visible);
        }

        $attributes = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $attributes,
            'message' => 'Attributes retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:select,color,size,text',
            'is_visible' => 'boolean',
            'is_filterable' => 'boolean',
            'is_variation' => 'boolean',
            'values' => 'required|array|min:1',
            'values.*' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Create attribute
            $attribute = Attribute::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'type' => $request->type,
                'is_visible' => $request->is_visible ?? true,
                'is_filterable' => $request->is_filterable ?? true,
                'is_variation' => $request->is_variation ?? false
            ]);

            // Create attribute values
            foreach ($request->values as $value) {
                AttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'value' => $value,
                    'slug' => Str::slug($value)
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $attribute->load('values'),
                'message' => 'Attribute created successfully'
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Attribute creation error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create attribute: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $attribute = Attribute::with('values')->find($id);

        if (!$attribute) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $attribute,
            'message' => 'Attribute retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $attribute = Attribute::find($id);

        if (!$attribute) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:select,color,size,text',
            'is_visible' => 'boolean',
            'is_filterable' => 'boolean',
            'is_variation' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $attribute->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => $request->type,
            'is_visible' => $request->is_visible ?? true,
            'is_filterable' => $request->is_filterable ?? true,
            'is_variation' => $request->is_variation ?? false
        ]);

        return response()->json([
            'success' => true,
            'data' => $attribute,
            'message' => 'Attribute updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $attribute = Attribute::find($id);

        if (!$attribute) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute not found'
            ], 404);
        }

        DB::beginTransaction();

        try {
            // Delete attribute values first
            $attribute->values()->delete();
            // Delete attribute
            $attribute->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Attribute deleted successfully'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Attribute deletion error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete attribute: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle attribute visibility.
     */
    public function toggleStatus($id)
    {
        $attribute = Attribute::find($id);

        if (!$attribute) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute not found'
            ], 404);
        }

        $attribute->update(['is_visible' => !$attribute->is_visible]);

        return response()->json([
            'success' => true,
            'data' => $attribute,
            'message' => 'Attribute status updated successfully'
        ]);
    }

    /**
     * Get attribute values.
     */
    public function getValues($id)
    {
        $attribute = Attribute::with('values')->find($id);

        if (!$attribute) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $attribute->values,
            'message' => 'Attribute values retrieved successfully'
        ]);
    }
}
