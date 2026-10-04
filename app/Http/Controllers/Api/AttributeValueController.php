<?php
// app/Http/Controllers/Api/AttributeValueController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AttributeValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AttributeValue::with('attribute');

        if ($request->has('attribute_id')) {
            $query->where('attribute_id', $request->attribute_id);
        }

        $values = $query->orderBy('position')->orderBy('value')->get();

        return response()->json([
            'success' => true,
            'data' => $values,
            'message' => 'Attribute values retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'position' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = [
                'attribute_id' => $request->attribute_id,
                'value' => $request->value,
                'slug' => Str::slug($request->value),
                'color_code' => $request->color_code,
                'position' => $request->position ?? 0
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('attributes', 'public');
            }

            $attributeValue = AttributeValue::create($data);

            return response()->json([
                'success' => true,
                'data' => $attributeValue,
                'message' => 'Attribute value created successfully'
            ], 201);

        } catch (\Throwable $e) {
            Log::error('Attribute value creation error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create attribute value: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $attributeValue = AttributeValue::with('attribute')->find($id);

        if (!$attributeValue) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute value not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $attributeValue,
            'message' => 'Attribute value retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $attributeValue = AttributeValue::find($id);

        if (!$attributeValue) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute value not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'value' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'position' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = [
                'value' => $request->value,
                'slug' => Str::slug($request->value),
                'color_code' => $request->color_code,
                'position' => $request->position ?? 0
            ];

            if ($request->hasFile('image')) {
                // Delete old image
                if ($attributeValue->image) {
                    Storage::disk('public')->delete($attributeValue->image);
                }
                $data['image'] = $request->file('image')->store('attributes', 'public');
            }

            $attributeValue->update($data);

            return response()->json([
                'success' => true,
                'data' => $attributeValue,
                'message' => 'Attribute value updated successfully'
            ]);

        } catch (\Throwable $e) {
            Log::error('Attribute value update error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update attribute value: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $attributeValue = AttributeValue::find($id);

        if (!$attributeValue) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute value not found'
            ], 404);
        }

        try {
            // Delete image if exists
            if ($attributeValue->image) {
                Storage::disk('public')->delete($attributeValue->image);
            }

            $attributeValue->delete();

            return response()->json([
                'success' => true,
                'message' => 'Attribute value deleted successfully'
            ]);

        } catch (\Throwable $e) {
            Log::error('Attribute value deletion error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete attribute value: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove attribute value image.
     */
    public function removeImage($id)
    {
        $attributeValue = AttributeValue::find($id);

        if (!$attributeValue) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute value not found'
            ], 404);
        }

        if ($attributeValue->image) {
            Storage::disk('public')->delete($attributeValue->image);
            $attributeValue->update(['image' => null]);

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
