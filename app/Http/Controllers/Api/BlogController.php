<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Blog::query();

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('slug', 'like', '%' . $search . '%')
                  ->orWhere('meta_title', 'like', '%' . $search . '%')
                  ->orWhere('meta_description', 'like', '%' . $search . '%');
            });
        }

        // Sorting
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $blogs = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $blogs,
            'message' => 'Blogs retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'slug' => 'required|regex:/^[a-z0-9-]+$/|unique:blogs,slug',
            'description' => 'required|string|min:20',
            'image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|max:60',
            'meta_description' => 'nullable|max:160',
            'meta_keywords' => 'nullable|max:255',
            'meta_tags' => 'nullable|max:255',
            'page_schemas' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('blogs', 'public');
            }

            $data = [
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'image' => $imagePath,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'meta_tags' => $request->meta_tags,
                'page_schemas' => $request->page_schemas,
                'is_active' => $request->is_active ?? true,
            ];

            $blog = Blog::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $blog,
                'message' => 'Blog created successfully'
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Blog save error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Blog save failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $blog,
            'message' => 'Blog retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'slug' => 'required|regex:/^[a-z0-9-]+$/|unique:blogs,slug,' . $id,
            'description' => 'required|string|min:20',
            'image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|max:60',
            'meta_description' => 'nullable|max:160',
            'meta_keywords' => 'nullable|max:255',
            'meta_tags' => 'nullable|max:255',
            'page_schemas' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $imagePath = $blog->image;

            if ($request->hasFile('image')) {
                // Delete old image
                if ($blog->image) {
                    Storage::disk('public')->delete($blog->image);
                }
                $imagePath = $request->file('image')->store('blogs', 'public');
            }

            $data = [
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'image' => $imagePath,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'meta_tags' => $request->meta_tags,
                'page_schemas' => $request->page_schemas,
                'is_active' => $request->is_active ?? true,
            ];

            $blog->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $blog->fresh(),
                'message' => 'Blog updated successfully'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Blog update error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Blog update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found'
            ], 404);
        }

        DB::beginTransaction();

        try {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }

            $blog->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Blog deleted successfully'
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
     * Toggle blog status.
     */
    public function toggleStatus(string $id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found'
            ], 404);
        }

        $blog->is_active = !$blog->is_active;
        $blog->save();

        return response()->json([
            'success' => true,
            'data' => $blog,
            'message' => 'Status updated successfully'
        ]);
    }

    /**
     * Remove blog image.
     */
    public function removeImage(string $id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found'
            ], 404);
        }

        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
            $blog->update(['image' => null]);

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
     * Analyze SEO for blog.
     */
    public function analyzeSeo(Request $request, string $id = null)
    {
        $data = $request->only([
            'name', 'slug', 'description',
            'meta_title', 'meta_description',
            'meta_keywords', 'meta_tags'
        ]);

        $seoMetrics = [];
        $totalWeight = 0;
        $achievedWeight = 0;

        // Analyze Blog Title
        $titleLength = strlen($data['name'] ?? '');
        $titleStatus = $titleLength >= 10 && $titleLength <= 60;
        $seoMetrics[] = [
            'name' => 'Blog Title',
            'status' => $titleStatus,
            'message' => "{$titleLength}/60 chars",
            'weight' => 20
        ];
        $totalWeight += 20;
        if ($titleStatus) $achievedWeight += 20;

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
        $metaDescStatus = $metaDescLength >= 120 && $metaDescLength <= 160;
        $seoMetrics[] = [
            'name' => 'Meta Description',
            'status' => $metaDescStatus,
            'message' => "{$metaDescLength}/160 chars",
            'weight' => 15
        ];
        $totalWeight += 15;
        if ($metaDescStatus) $achievedWeight += 15;

        // Analyze Content
        $textContent = strip_tags($data['description'] ?? '');
        $wordCount = str_word_count($textContent);
        $contentStatus = $wordCount >= 300;
        $seoMetrics[] = [
            'name' => 'Content Length',
            'status' => $contentStatus,
            'message' => "{$wordCount}/300+ words",
            'weight' => 20
        ];
        $totalWeight += 20;
        if ($contentStatus) $achievedWeight += 20;

        // Analyze Slug
        $slugLength = strlen($data['slug'] ?? '');
        $slugStatus = $slugLength >= 3 && $slugLength <= 50;
        $seoMetrics[] = [
            'name' => 'URL Slug',
            'status' => $slugStatus,
            'message' => "{$slugLength}/50 chars",
            'weight' => 10
        ];
        $totalWeight += 10;
        if ($slugStatus) $achievedWeight += 10;

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
}
