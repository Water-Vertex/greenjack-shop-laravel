<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductImage;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Variation;
use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with([
            'category',
            'subCategory',
            'productImages',
            'attributes.attribute',
            'variations'
        ]);

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%')
                  ->orWhere('upc', 'like', '%' . $search . '%')
                  ->orWhere('manufacturer', 'like', '%' . $search . '%')
                  ->orWhere('meta_title', 'like', '%' . $search . '%');
            });
        }

        // Filter by category
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by sub-category
        if ($request->has('sub_category_id') && !empty($request->sub_category_id)) {
            $query->where('sub_category_id', $request->sub_category_id);
        }

        // Filter by status
        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->is_active);
        }

        // Filter by type
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        // Filter by attributes
        if ($request->has('attributes') && is_array($request->attributes)) {
            foreach ($request->attributes as $attributeId => $valueIds) {
                $query->whereHas('attributes', function($q) use ($attributeId, $valueIds) {
                    $q->where('attribute_id', $attributeId)
                      ->whereIn('attribute_value_id', (array) $valueIds);
                });
            }
        }

        // Filter by has variations
        if ($request->has('has_variations') && $request->has_variations !== '') {
            if ($request->has_variations) {
                $query->has('variations');
            } else {
                $query->doesntHave('variations');
            }
        }

        // Filter by price range
        if ($request->has('min_price') && $request->has('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        // Sorting
        $sortField = $request->get('sort_field', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');

        if ($sortField === 'price' && $request->has('has_variations')) {
            $query->orderByRaw("CASE WHEN has_variations = 1 THEN (SELECT MIN(price) FROM variations WHERE variations.product_id = products.id) ELSE price END {$sortDirection}");
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $perPage = $request->get('per_page', 10);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Products retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'galleryImages.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'price' => 'nullable|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'upc' => 'nullable|string|max:50',
            'sku' => 'nullable|string|max:100',
            'asin' => 'nullable|string|max:50',
            'manufacturer' => 'nullable|string|max:255',
            'moq' => 'nullable|integer|min:1',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'meta_tags' => 'nullable|string',
            'page_schemas' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'focus_keyword' => 'nullable|string|max:255',
            'type' => 'nullable|in:featured,best_seller',
            'deal_of_the_day' => 'nullable|string',
            'attributes' => 'nullable|array',
            'attributes.*' => 'nullable|integer|exists:attribute_values,id',
            'variations' => 'nullable|array',
            'variations.*.sku' => 'nullable|string|max:100',
            'variations.*.upc' => 'nullable|string|max:50',
            'variations.*.asin' => 'nullable|string|max:50',
            'variations.*.price' => 'nullable|numeric|min:0',
            'variations.*.discounted_price' => 'nullable|numeric|min:0',
            'variations.*.stock' => 'nullable|integer|min:0',
            'variations.*.moq' => 'nullable|integer|min:1',
            'variations.*.attributes' => 'nullable|array',
            'variations.*.attributes.*' => 'nullable|integer|exists:attribute_values,id',
            'variations.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'has_variations' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // ============================================
            // STEP 1: Create Product using DB facade
            // ============================================
            $slug = $request->slug ?: Str::slug($request->name);

            $existingSlug = DB::table('products')->where('slug', $slug)->exists();
            if ($existingSlug) {
                $slug = $slug . '-' . uniqid();
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            }

            // ✅ Better has_variations calculation
            $hasVariations = ($request->has('variations') && is_array($request->variations) && count($request->variations) > 0)
                || filter_var($request->has_variations, FILTER_VALIDATE_BOOLEAN);

            $productId = DB::table('products')->insertGetId([
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'image' => $imagePath,
                'price' => $hasVariations ? null : $request->price,
                'discounted_price' => $hasVariations ? null : $request->discounted_price,
                'stock' => $hasVariations ? null : $request->stock,
                'is_active' => $request->is_active ?? true,
                'upc' => $request->upc,
                'sku' => $request->sku,
                'asin' => $request->asin,
                'manufacturer' => $request->manufacturer,
                'moq' => $request->moq,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'meta_tags' => $request->meta_tags,
                'page_schemas' => $request->page_schemas,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'focus_keyword' => $request->focus_keyword,
                'type' => $request->type,
                'deal_of_the_day' => $request->deal_of_the_day,
                'has_variations' => $hasVariations,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // ============================================
            // STEP 2: Sync Product Attributes — STRICT FILTER
            // ============================================
            if ($request->has('attributes')) {
                $attributesData = $request->input('attributes');

                if (is_array($attributesData) && count($attributesData) > 0) {
                    $attributes = [];
                    foreach ($attributesData as $value) {
                        $value = (int) $value;
                        if ($value <= 0) {
                            continue;
                        }

                        $exists = DB::table('attribute_values')->where('id', $value)->exists();
                        if (!$exists) {
                            Log::warning('Store - ❌ Skipping unknown product attribute_value_id', [
                                'product_id' => $productId,
                                'attribute_value_id' => $value,
                            ]);
                            continue;
                        }

                        $attributes[] = $value;
                    }
                    $attributes = array_values(array_unique($attributes));

                    foreach ($attributes as $attributeValueId) {
                        DB::table('product_attribute_values')->insert([
                            'product_id' => $productId,
                            'attribute_value_id' => $attributeValueId,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        Log::info('Store - ✅ Product attribute inserted:', [
                            'product_id' => $productId,
                            'attribute_value_id' => $attributeValueId
                        ]);
                    }
                }
            }

            // ============================================
            // STEP 3: Create Variations — STRICT FILTER
            // ============================================
            if ($request->has('variations') && is_array($request->variations) && count($request->variations) > 0) {

                // Product ke valid attribute_ids nikaal lo
                $productAttributeIds = DB::table('product_attribute_values')
                    ->join('attribute_values', 'attribute_values.id', '=', 'product_attribute_values.attribute_value_id')
                    ->where('product_attribute_values.product_id', $productId)
                    ->pluck('attribute_values.attribute_id')
                    ->unique()
                    ->toArray();

                foreach ($request->variations as $index => $variationData) {
                    if (!is_array($variationData) || empty($variationData)) {
                        continue;
                    }

                    $price = isset($variationData['price']) && $variationData['price'] !== '' && $variationData['price'] !== null
                        ? floatval($variationData['price'])
                        : null;

                    $stock = isset($variationData['stock']) && $variationData['stock'] !== '' && $variationData['stock'] !== null
                        ? intval($variationData['stock'])
                        : null;

                    if ($price === null || $stock === null) {
                        continue;
                    }

                    $variationImagePath = null;
                    if (isset($variationData['image']) && $request->hasFile("variations.{$index}.image")) {
                        try {
                            $variationImagePath = $request->file("variations.{$index}.image")->store('variations', 'public');
                        } catch (\Exception $e) {
                            Log::warning('Variation image upload failed', ['error' => $e->getMessage()]);
                        }
                    }

                    // ✅ STRICT FILTER — sirf valid attribute_value_ids
                    $attributes = [];
                    if (isset($variationData['attributes']) && is_array($variationData['attributes'])) {
                        foreach ($variationData['attributes'] as $value) {
                            $value = (int) $value;
                            if ($value <= 0) {
                                continue;
                            }

                            $attrValue = DB::table('attribute_values')->where('id', $value)->first();
                            if (!$attrValue) {
                                Log::warning('Store - ❌ Skipping orphan variation attribute_value_id', [
                                    'variation_index' => $index,
                                    'attribute_value_id' => $value,
                                ]);
                                continue;
                            }

                            if (!in_array($attrValue->attribute_id, $productAttributeIds)) {
                                Log::warning('Store - ❌ Skipping attribute not part of product', [
                                    'variation_index' => $index,
                                    'attribute_value_id' => $value,
                                    'attribute_id' => $attrValue->attribute_id,
                                    'product_id' => $productId,
                                ]);
                                continue;
                            }

                            $attributes[] = $value;
                        }
                        $attributes = array_values(array_unique($attributes));
                    }

                    // Insert variation
                    $variationId = DB::table('variations')->insertGetId([
                        'product_id' => $productId,
                        'sku' => $variationData['sku'] ?? null,
                        'upc' => $variationData['upc'] ?? null,
                        'asin' => $variationData['asin'] ?? null,
                        'price' => $price,
                        'discounted_price' => isset($variationData['discounted_price']) && $variationData['discounted_price'] !== ''
                            ? floatval($variationData['discounted_price'])
                            : null,
                        'stock' => $stock,
                        'moq' => isset($variationData['moq']) && $variationData['moq'] !== ''
                            ? intval($variationData['moq'])
                            : null,
                        'image' => $variationImagePath,
                        'is_active' => true,
                        'attributes' => json_encode($attributes),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    Log::info('Store - ✅ Variation created:', [
                        'variation_id' => $variationId,
                        'product_id' => $productId,
                        'attributes_count' => count($attributes),
                    ]);

                    // Insert variation attributes into pivot table
                    if (!empty($attributes)) {
                        foreach ($attributes as $attributeValueId) {
                            try {
                                $attributeValue = DB::table('attribute_values')->where('id', $attributeValueId)->first();
                                if ($attributeValue && isset($attributeValue->attribute_id) && $attributeValue->attribute_id > 0) {
                                    DB::table('variation_attribute_values')->insert([
                                        'variation_id' => $variationId,
                                        'attribute_value_id' => $attributeValueId,
                                        'attribute_id' => $attributeValue->attribute_id,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]);
                                    Log::info('Store - ✅ Variation attribute saved:', [
                                        'variation_id' => $variationId,
                                        'attribute_value_id' => $attributeValueId
                                    ]);
                                }
                            } catch (\Exception $e) {
                                Log::error('Store - Pivot insert error', [
                                    'variation_id' => $variationId,
                                    'attribute_value_id' => $attributeValueId,
                                    'error' => $e->getMessage()
                                ]);
                            }
                        }
                    }
                }

                // Update product price and stock
                $minPrice = DB::table('variations')->where('product_id', $productId)->min('price');
                $totalStock = DB::table('variations')->where('product_id', $productId)->sum('stock');

                DB::table('products')->where('id', $productId)->update([
                    'price' => $minPrice,
                    'stock' => $totalStock,
                    'has_variations' => true,
                    'updated_at' => now()
                ]);
            }

            // ============================================
            // STEP 4: Handle Gallery Images
            // ============================================
            if ($request->hasFile('galleryImages')) {
                foreach ($request->file('galleryImages') as $galleryImage) {
                    $path = $galleryImage->store('products/gallery', 'public');

                    DB::table('product_images')->insert([
                        'product_id' => $productId,
                        'images' => json_encode([$path]),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            DB::commit();

            // ============================================
            // STEP 5: Get Product with all relationships
            // ============================================
            $product = Product::with([
                'category',
                'subCategory',
                'productImages',
                'attributes.attribute',
                'variations'
            ])->find($productId);

            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Product created successfully'
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Product save error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Product save failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with([
            'category',
            'subCategory',
            'productImages',
            'attributes.attribute',
            'variations'
        ])->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'galleryImages.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'price' => 'nullable|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'upc' => 'nullable|string|max:50',
            'sku' => 'nullable|string|max:100',
            'asin' => 'nullable|string|max:50',
            'manufacturer' => 'nullable|string|max:255',
            'moq' => 'nullable|integer|min:1',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'meta_tags' => 'nullable|string',
            'page_schemas' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'focus_keyword' => 'nullable|string|max:255',
            'type' => 'nullable|in:featured,best_seller',
            'deal_of_the_day' => 'nullable|string',
            'attributes' => 'nullable|array',
            'attributes.*' => 'nullable|integer|exists:attribute_values,id',
            'variations' => 'nullable|array',
            'variations.*.id' => 'nullable|exists:variations,id',
            'variations.*.sku' => 'nullable|string|max:100',
            'variations.*.upc' => 'nullable|string|max:50',
            'variations.*.asin' => 'nullable|string|max:50',
            'variations.*.price' => 'nullable|numeric|min:0',
            'variations.*.discounted_price' => 'nullable|numeric|min:0',
            'variations.*.stock' => 'nullable|integer|min:0',
            'variations.*.moq' => 'nullable|integer|min:1',
            'variations.*.attributes' => 'nullable|array',
            'variations.*.attributes.*' => 'nullable|integer|exists:attribute_values,id',
            'variations.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'has_variations' => 'boolean'
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
            $existingSlug = DB::table('products')->where('slug', $slug)->where('id', '!=', $id)->exists();
            if ($existingSlug) {
                $slug = $slug . '-' . uniqid();
            }

            $imagePath = $product->image;
            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $imagePath = $request->file('image')->store('products', 'public');
            }

            // ✅ Better has_variations calculation
            $hasVariations = ($request->has('variations') && is_array($request->variations) && count($request->variations) > 0)
                || filter_var($request->has_variations, FILTER_VALIDATE_BOOLEAN);

            // Update product
            DB::table('products')->where('id', $id)->update([
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'image' => $imagePath,
                'price' => $hasVariations ? null : $request->price,
                'discounted_price' => $hasVariations ? null : $request->discounted_price,
                'stock' => $hasVariations ? null : $request->stock,
                'is_active' => $request->is_active ?? true,
                'upc' => $request->upc,
                'sku' => $request->sku,
                'asin' => $request->asin,
                'manufacturer' => $request->manufacturer,
                'moq' => $request->moq,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'meta_tags' => $request->meta_tags,
                'page_schemas' => $request->page_schemas,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'focus_keyword' => $request->focus_keyword,
                'type' => $request->type,
                'deal_of_the_day' => $request->deal_of_the_day,
                'has_variations' => $hasVariations,
                'updated_at' => now()
            ]);

            // ============================================
            // SYNC PRODUCT ATTRIBUTES — STRICT FILTER
            // ============================================
            DB::table('product_attribute_values')->where('product_id', $id)->delete();

            if ($request->has('attributes')) {
                $attributesData = $request->input('attributes');

                if (is_array($attributesData) && count($attributesData) > 0) {
                    $attributes = [];
                    foreach ($attributesData as $value) {
                        $value = (int) $value;
                        if ($value <= 0) {
                            continue;
                        }

                        $exists = DB::table('attribute_values')->where('id', $value)->exists();
                        if (!$exists) {
                            Log::warning('❌ Skipping unknown product attribute_value_id', [
                                'product_id' => $id,
                                'attribute_value_id' => $value,
                            ]);
                            continue;
                        }

                        $attributes[] = $value;
                    }
                    $attributes = array_values(array_unique($attributes));

                    foreach ($attributes as $attributeValueId) {
                        DB::table('product_attribute_values')->insert([
                            'product_id' => $id,
                            'attribute_value_id' => $attributeValueId,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        Log::info('✅ Product attribute inserted:', [
                            'product_id' => $id,
                            'attribute_value_id' => $attributeValueId
                        ]);
                    }
                }
            }

            // ============================================
            // HANDLE VARIATIONS UPDATE — STRICT FILTER
            // ============================================
            if ($request->has('variations') && is_array($request->variations) && count($request->variations) > 0) {
                $existingVariationIds = DB::table('variations')->where('product_id', $id)->pluck('id')->toArray();
                $updatedVariationIds = [];

                // Product ke valid attribute_ids nikaal lo (ek baar)
                $productAttributeIds = DB::table('product_attribute_values')
                    ->join('attribute_values', 'attribute_values.id', '=', 'product_attribute_values.attribute_value_id')
                    ->where('product_attribute_values.product_id', $id)
                    ->pluck('attribute_values.attribute_id')
                    ->unique()
                    ->toArray();

                foreach ($request->variations as $index => $variationData) {
                    if (!is_array($variationData) || empty($variationData)) {
                        continue;
                    }

                    $price = isset($variationData['price']) && $variationData['price'] !== '' && $variationData['price'] !== null
                        ? floatval($variationData['price'])
                        : null;

                    $stock = isset($variationData['stock']) && $variationData['stock'] !== '' && $variationData['stock'] !== null
                        ? intval($variationData['stock'])
                        : null;

                    if ($price === null || $stock === null) {
                        Log::warning('Skipping variation: price or stock missing', [
                            'index' => $index,
                            'variationData' => $variationData
                        ]);
                        continue;
                    }

                    // IMAGE HANDLING — preserve existing
                    $variationImagePath = null;
                    $existingVariation = null;
                    $isExistingVariation = isset($variationData['id']) && in_array($variationData['id'], $existingVariationIds);

                    if ($isExistingVariation) {
                        $existingVariation = DB::table('variations')->where('id', $variationData['id'])->first();
                        $variationImagePath = $existingVariation->image ?? null;
                    }

                    if ($request->hasFile("variations.{$index}.image")) {
                        try {
                            if ($existingVariation && $existingVariation->image) {
                                Storage::disk('public')->delete($existingVariation->image);
                            }
                            $variationImagePath = $request->file("variations.{$index}.image")->store('variations', 'public');
                        } catch (\Exception $e) {
                            Log::warning('Variation image upload failed', ['error' => $e->getMessage()]);
                        }
                    }

                    // ============================================
                    // ✅ ATTRIBUTES FILTER — skip unknown/orphan
                    // ============================================
                    $attributes = [];
                    if (isset($variationData['attributes']) && is_array($variationData['attributes'])) {
                        foreach ($variationData['attributes'] as $value) {
                            $value = (int) $value;
                            if ($value <= 0) {
                                continue;
                            }

                            $attrValue = DB::table('attribute_values')->where('id', $value)->first();
                            if (!$attrValue) {
                                Log::warning('❌ Skipping unknown variation attribute_value_id', [
                                    'variation_id' => $variationData['id'] ?? null,
                                    'variation_index' => $index,
                                    'attribute_value_id' => $value,
                                ]);
                                continue;
                            }

                            if (!in_array($attrValue->attribute_id, $productAttributeIds)) {
                                Log::warning('❌ Skipping attribute not part of product', [
                                    'variation_id' => $variationData['id'] ?? null,
                                    'attribute_value_id' => $value,
                                    'attribute_id' => $attrValue->attribute_id,
                                    'product_id' => $id,
                                ]);
                                continue;
                            }

                            $attributes[] = $value;
                        }
                        $attributes = array_values(array_unique($attributes));
                    }

                    if ($isExistingVariation) {
                        DB::table('variations')->where('id', $variationData['id'])->update([
                            'sku' => $variationData['sku'] ?? null,
                            'upc' => $variationData['upc'] ?? null,
                            'asin' => $variationData['asin'] ?? null,
                            'price' => $price,
                            'discounted_price' => isset($variationData['discounted_price']) && $variationData['discounted_price'] !== ''
                                ? floatval($variationData['discounted_price'])
                                : null,
                            'stock' => $stock,
                            'moq' => isset($variationData['moq']) && $variationData['moq'] !== ''
                                ? intval($variationData['moq'])
                                : null,
                            'image' => $variationImagePath,
                            'attributes' => json_encode($attributes),
                            'updated_at' => now()
                        ]);

                        Log::info('✅ Variation updated:', [
                            'variation_id' => $variationData['id'],
                            'attributes_count' => count($attributes),
                        ]);

                        DB::table('variation_attribute_values')->where('variation_id', $variationData['id'])->delete();

                        foreach ($attributes as $attributeValueId) {
                            $attributeValue = DB::table('attribute_values')->where('id', $attributeValueId)->first();
                            if ($attributeValue && isset($attributeValue->attribute_id) && $attributeValue->attribute_id > 0) {
                                DB::table('variation_attribute_values')->insert([
                                    'variation_id' => $variationData['id'],
                                    'attribute_value_id' => $attributeValueId,
                                    'attribute_id' => $attributeValue->attribute_id,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ]);
                            }
                        }

                        $updatedVariationIds[] = (int) $variationData['id'];
                    } else {
                        $variationId = DB::table('variations')->insertGetId([
                            'product_id' => $id,
                            'sku' => $variationData['sku'] ?? null,
                            'upc' => $variationData['upc'] ?? null,
                            'asin' => $variationData['asin'] ?? null,
                            'price' => $price,
                            'discounted_price' => isset($variationData['discounted_price']) && $variationData['discounted_price'] !== ''
                                ? floatval($variationData['discounted_price'])
                                : null,
                            'stock' => $stock,
                            'moq' => isset($variationData['moq']) && $variationData['moq'] !== ''
                                ? intval($variationData['moq'])
                                : null,
                            'image' => $variationImagePath,
                            'is_active' => true,
                            'attributes' => json_encode($attributes),
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);

                        Log::info('✅ New variation created:', [
                            'variation_id' => $variationId,
                            'attributes_count' => count($attributes),
                        ]);

                        foreach ($attributes as $attributeValueId) {
                            $attributeValue = DB::table('attribute_values')->where('id', $attributeValueId)->first();
                            if ($attributeValue && isset($attributeValue->attribute_id) && $attributeValue->attribute_id > 0) {
                                DB::table('variation_attribute_values')->insert([
                                    'variation_id' => $variationId,
                                    'attribute_value_id' => $attributeValueId,
                                    'attribute_id' => $attributeValue->attribute_id,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ]);
                            }
                        }

                        $updatedVariationIds[] = (int) $variationId;
                    }
                }

                // Delete removed variations
                $variationsToDelete = array_diff($existingVariationIds, $updatedVariationIds);
                if (!empty($variationsToDelete)) {
                    $variationsToDeleteData = DB::table('variations')->whereIn('id', $variationsToDelete)->get();
                    foreach ($variationsToDeleteData as $varToDelete) {
                        if ($varToDelete->image) {
                            Storage::disk('public')->delete($varToDelete->image);
                        }
                    }

                    DB::table('variation_attribute_values')->whereIn('variation_id', $variationsToDelete)->delete();
                    DB::table('variations')->whereIn('id', $variationsToDelete)->delete();
                    Log::info('✅ Variations deleted:', ['variation_ids' => $variationsToDelete]);
                }

                // Update product price/stock
                $minPrice = DB::table('variations')->where('product_id', $id)->min('price');
                $totalStock = DB::table('variations')->where('product_id', $id)->sum('stock');

                if ($minPrice !== null) {
                    DB::table('products')->where('id', $id)->update([
                        'price' => $minPrice,
                        'stock' => $totalStock,
                        'has_variations' => true,
                        'updated_at' => now()
                    ]);
                }
            }

            // Gallery images
            if ($request->hasFile('galleryImages')) {
                foreach ($request->file('galleryImages') as $galleryImage) {
                    $path = $galleryImage->store('products/gallery', 'public');

                    DB::table('product_images')->insert([
                        'product_id' => $id,
                        'images' => json_encode([$path]),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            DB::commit();

            $product = Product::with([
                'category',
                'subCategory',
                'productImages',
                'attributes.attribute',
                'variations'
            ])->find($id);

            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Product updated successfully'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Product update error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Product update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::with(['productImages', 'variations.images'])->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        DB::beginTransaction();

        try {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            foreach ($product->productImages as $image) {
                $images = json_decode($image->images, true);
                if (is_array($images)) {
                    foreach ($images as $img) {
                        Storage::disk('public')->delete($img);
                    }
                }
                $image->delete();
            }

            foreach ($product->variations as $variation) {
                if ($variation->image) {
                    Storage::disk('public')->delete($variation->image);
                }
                foreach ($variation->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                }
                DB::table('variation_attribute_values')->where('variation_id', $variation->id)->delete();
                $variation->delete();
            }

            $product->attributes()->detach();
            $product->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Product delete error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle product status.
     */
    public function toggleStatus(Request $request, string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $product->update(['is_active' => !$product->is_active]);

        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product status updated successfully'
        ]);
    }

    /**
     * Remove product main image.
     */
    public function removeImage(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
            $product->update(['image' => null]);

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
     * Remove gallery image.
     */
    public function removeGalleryImage(string $id, string $imageId)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $image = ProductImage::where('product_id', $id)->where('id', $imageId)->first();

        if (!$image) {
            return response()->json([
                'success' => false,
                'message' => 'Gallery image not found'
            ], 404);
        }

        $images = json_decode($image->images, true);
        if (is_array($images)) {
            foreach ($images as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gallery image removed successfully'
        ]);
    }

    /**
     * Download import template.
     */
    public function downloadTemplate()
    {
        $headers = [
            'name',
            'description',
            'stock',
            'discounted_price',
            'price',
            'category_id',
            'is_active',
            'upc',
            'asin',
            'sku',
            'manufacturer',
            'moq',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'meta_tags',
            'page_schemas',
            'sub_category_id',
            'focus_keyword',
            'type',
        ];

        return response()->streamDownload(function () use ($headers) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $headers);

            fputcsv($file, [
                'Premium Laptop',
                'High-performance laptop',
                '50',
                '999.99',
                '1299.99',
                '1',
                '1',
                '123456789012',
                'B08N5WRWNW',
                'LAP-001',
                'Dell',
                '5',
                'Premium Laptop - Best Deal',
                'Buy premium laptop online',
                'laptop, premium',
                'premium-laptop',
                '{"@context":"https://schema.org"}',
                '1',
                'premium laptop',
                'featured',
            ]);

            fclose($file);
        }, 'product_import_template.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Import products from Excel/CSV.
     */
    public function importProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'excelFile' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $import = new ProductsImport();
            Excel::import($import, $request->file('excelFile'));

            $count = method_exists($import, 'getSuccessCount')
                ? $import->getSuccessCount()
                : 0;

            return response()->json([
                'success' => true,
                'message' => "Successfully imported {$count} products!",
                'data' => ['count' => $count]
            ]);

        } catch (\Throwable $e) {
            Log::error('Import error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product variations for a specific product.
     */
    public function getVariations(string $id)
    {
        $product = Product::with(['variations'])->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product->variations,
            'message' => 'Variations retrieved successfully'
        ]);
    }

    /**
     * Get product attributes for a specific product.
     */
    public function getAttributes(string $id)
    {
        $product = Product::with(['attributes.attribute'])->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product->attributes,
            'message' => 'Attributes retrieved successfully'
        ]);
    }

    /**
     * Update variation stock.
     */
    public function updateVariationStock(Request $request, string $variationId)
    {
        $validator = Validator::make($request->all(), [
            'stock' => 'required|integer|min:0',
            'operation' => 'nullable|in:set,add,subtract'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $variation = Variation::find($variationId);

        if (!$variation) {
            return response()->json([
                'success' => false,
                'message' => 'Variation not found'
            ], 404);
        }

        DB::beginTransaction();

        try {
            $operation = $request->operation ?? 'set';
            $stock = $request->stock;

            switch ($operation) {
                case 'add':
                    $variation->stock += $stock;
                    break;
                case 'subtract':
                    $variation->stock = max(0, $variation->stock - $stock);
                    break;
                default:
                    $variation->stock = $stock;
            }

            $variation->save();

            $totalStock = DB::table('variations')->where('product_id', $variation->product_id)->sum('stock');
            DB::table('products')->where('id', $variation->product_id)->update([
                'stock' => $totalStock,
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $variation,
                'message' => 'Variation stock updated successfully'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Stock update error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Stock update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove variation image.
     */
    public function removeVariationImage(string $variationId)
    {
        $variation = Variation::find($variationId);

        if (!$variation) {
            return response()->json([
                'success' => false,
                'message' => 'Variation not found'
            ], 404);
        }

        if ($variation->image) {
            Storage::disk('public')->delete($variation->image);
            $variation->update(['image' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Variation image removed successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No image found to remove'
        ], 404);
    }

    /**
     * Get all available attributes for product creation.
     */
    public function getAvailableAttributes()
    {
        $attributes = Attribute::with('values')->where('is_visible', true)->get();

        return response()->json([
            'success' => true,
            'data' => $attributes,
            'message' => 'Attributes retrieved successfully'
        ]);
    }

    /**
     * Delete a single variation.
     */
    public function deleteVariation(string $id, string $variationId)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $variation = Variation::where('id', $variationId)
            ->where('product_id', $id)
            ->first();

        if (!$variation) {
            return response()->json([
                'success' => false,
                'message' => 'Variation not found'
            ], 404);
        }

        DB::beginTransaction();

        try {
            if ($variation->image) {
                Storage::disk('public')->delete($variation->image);
            }

            DB::table('variation_attribute_values')
                ->where('variation_id', $variationId)
                ->delete();

            $variation->delete();

            $minPrice = DB::table('variations')
                ->where('product_id', $id)
                ->min('price');

            $totalStock = DB::table('variations')
                ->where('product_id', $id)
                ->sum('stock');

            DB::table('products')
                ->where('id', $id)
                ->update([
                    'price' => $minPrice,
                    'stock' => $totalStock,
                    'updated_at' => now()
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Variation deleted successfully'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Variation delete error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete variation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete all variations for a product.
     */
    public function deleteAllVariations(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        DB::beginTransaction();

        try {
            $variationIds = DB::table('variations')
                ->where('product_id', $id)
                ->pluck('id')
                ->toArray();

            if (empty($variationIds)) {
                return response()->json([
                    'success' => true,
                    'message' => 'No variations to delete'
                ]);
            }

            $variations = Variation::where('product_id', $id)->get();
            foreach ($variations as $variation) {
                if ($variation->image) {
                    Storage::disk('public')->delete($variation->image);
                }
            }

            DB::table('variation_attribute_values')
                ->whereIn('variation_id', $variationIds)
                ->delete();

            DB::table('variations')
                ->where('product_id', $id)
                ->delete();

            DB::table('products')
                ->where('id', $id)
                ->update([
                    'price' => null,
                    'stock' => null,
                    'has_variations' => false,
                    'updated_at' => now()
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'All variations deleted successfully',
                'data' => ['deleted_count' => count($variationIds)]
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Delete all variations error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete variations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle variation status.
     */
    public function toggleVariationStatus(Request $request, string $variationId)
    {
        $variation = Variation::find($variationId);

        if (!$variation) {
            return response()->json([
                'success' => false,
                'message' => 'Variation not found'
            ], 404);
        }

        $variation->update(['is_active' => !$variation->is_active]);

        return response()->json([
            'success' => true,
            'data' => $variation,
            'message' => 'Variation status updated successfully'
        ]);
    }

    /**
     * Sync product attributes.
     */
    public function syncAttributes(Request $request, string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'attributes' => 'required|array',
            'attributes.*' => 'exists:attribute_values,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            DB::table('product_attribute_values')
                ->where('product_id', $id)
                ->delete();

            $attributes = array_values(array_unique(array_map('intval', $request->attributes)));

            foreach ($attributes as $attributeValueId) {
                DB::table('product_attribute_values')->insert([
                    'product_id' => $id,
                    'attribute_value_id' => $attributeValueId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            $product = Product::with(['attributes.attribute'])->find($id);

            return response()->json([
                'success' => true,
                'data' => $product->attributes,
                'message' => 'Attributes synced successfully'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Attribute sync error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to sync attributes: ' . $e->getMessage()
            ], 500);
        }
    }
}