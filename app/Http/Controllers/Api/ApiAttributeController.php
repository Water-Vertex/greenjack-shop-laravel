<?php
// app/Http/Controllers/Api/ApiAttributeController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiAttributeController extends Controller
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
            $attribute = Attribute::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'type' => $request->type,
                'is_visible' => $request->is_visible ?? true,
                'is_filterable' => $request->is_filterable ?? true,
                'is_variation' => $request->is_variation ?? false
            ]);

            foreach ($request->values as $value) {
                $value = trim((string) $value);
                if (empty($value)) continue;

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
     * ✅ SMART UPDATE — Sirf nayi values add karo, purani ko touch mat karo
     *
     * Isme:
     *   - Existing values → Preserve (IDs same rahengi)
     *   - Nayi values → Create (naye IDs)
     *   - Jo UI se hatai gayi → DB me rahengi (delete nahi hongi)
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
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
            // ✅ 1. Attribute meta update karo
            $attribute->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'type' => $request->type,
                'is_visible' => $request->is_visible ?? true,
                'is_filterable' => $request->is_filterable ?? true,
                'is_variation' => $request->is_variation ?? false
            ]);

            // ✅ 2. Existing values ko fetch karo
            $existingValues = $attribute->values()->get()->keyBy('value');

            // ✅ 3. Submitted values normalize karo
            $submittedValues = collect($request->values)
                ->map(fn($v) => trim((string) $v))
                ->filter(fn($v) => !empty($v))
                ->unique()
                ->values()
                ->toArray();

            $log = [
                'attribute_id' => $attribute->id,
                'preserved' => [],
                'created' => [],
                'orphaned_in_db' => [],   // Ye values DB me rahengi lekin UI me nahi
            ];

            // ✅ 4. Har submitted value ke liye:
            //    - Agar exists → kuch mat karo (ID preserve)
            //    - Agar nahi exists → create karo
            foreach ($submittedValues as $valueString) {
                if ($existingValues->has($valueString)) {
                    // ✅ Already exists — ID preserve
                    $existingValue = $existingValues->get($valueString);
                    $log['preserved'][] = [
                        'id' => $existingValue->id,
                        'value' => $valueString,
                    ];
                    continue;
                }

                // ✅ Nayi value — create karo
                $newValue = AttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'value' => $valueString,
                    'slug' => Str::slug($valueString),
                ]);

                $log['created'][] = [
                    'id' => $newValue->id,
                    'value' => $valueString,
                ];
            }

            // ✅ 5. Jo values UI me nahi hain — DB me rahengi (delete NAHI karenge)
            //    Sirf log kar rahe hain debug ke liye
            $valuesNotInUI = $existingValues->filter(function ($existingValue) use ($submittedValues) {
                return !in_array($existingValue->value, $submittedValues, true);
            });

            foreach ($valuesNotInUI as $value) {
                $log['orphaned_in_db'][] = [
                    'id' => $value->id,
                    'value' => $value->value,
                    'note' => 'Preserved in DB, hidden from UI',
                ];
            }

            Log::info('✅ Attribute updated (no-delete mode)', $log);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $attribute->load('values'),
                'message' => 'Attribute updated successfully',
                'log' => $log,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Attribute update error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update attribute: ' . $e->getMessage()
            ], 500);
        }
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
            // ✅ Check if attribute is used anywhere
            $valueIds = $attribute->values()->pluck('id')->toArray();

            $isUsedInVariations = DB::table('variation_attribute_values')
                ->whereIn('attribute_value_id', $valueIds)
                ->exists();

            $isUsedInProducts = DB::table('product_attribute_values')
                ->whereIn('attribute_value_id', $valueIds)
                ->exists();

            if ($isUsedInVariations || $isUsedInProducts) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete attribute — it is in use by products or variations'
                ], 422);
            }

            // Safe to delete
            $attribute->values()->delete();
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