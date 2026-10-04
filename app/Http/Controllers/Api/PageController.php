<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Page::query();

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('meta_title', 'like', '%' . $search . '%')
                  ->orWhere('meta_description', 'like', '%' . $search . '%');
            });
        }

        // Sorting
        $sortField = $request->get('sort_field', 'title');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $pages = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $pages,
            'message' => 'Pages retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'page_category' => 'nullable|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'content_right' => 'nullable|string',
            'content_left' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'section_image_left' => 'nullable|image|max:2048',
            'section_image_right' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'schema_markup' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $slug = $request->slug ?: Str::slug($request->title);

            // Check if slug exists
            if (Page::where('slug', $slug)->exists()) {
                $slug = $slug . '-' . uniqid();
            }

            $featuredImagePath = null;
            $sectionImageLeftPath = null;
            $sectionImageRightPath = null;

            if ($request->hasFile('featured_image')) {
                $featuredImagePath = $request->file('featured_image')->store('pages', 'public');
            }

            if ($request->hasFile('section_image_left')) {
                $sectionImageLeftPath = $request->file('section_image_left')->store('pages', 'public');
            }

            if ($request->hasFile('section_image_right')) {
                $sectionImageRightPath = $request->file('section_image_right')->store('pages', 'public');
            }

            $data = [
                'title' => $request->title,
                'slug' => $slug,
                'page_category' => $request->page_category,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'content_right' => $request->content_right,
                'content_left' => $request->content_left,
                'featured_image' => $featuredImagePath,
                'section_image_left' => $sectionImageLeftPath,
                'section_image_right' => $sectionImageRightPath,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'schema_markup' => $request->schema_markup,
            ];

            $page = Page::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $page,
                'message' => 'Page created successfully'
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Page save error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Page save failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $page = Page::find($id);

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $page,
            'message' => 'Page retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $page = Page::find($id);

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $id,
            'page_category' => 'nullable|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'content_right' => 'nullable|string',
            'content_left' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'section_image_left' => 'nullable|image|max:2048',
            'section_image_right' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'schema_markup' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $slug = $request->slug ?: Str::slug($request->title);

            // Check if slug exists (excluding current page)
            if (Page::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $slug . '-' . uniqid();
            }

            $featuredImagePath = $page->featured_image;
            $sectionImageLeftPath = $page->section_image_left;
            $sectionImageRightPath = $page->section_image_right;

            if ($request->hasFile('featured_image')) {
                if ($page->featured_image) {
                    Storage::disk('public')->delete($page->featured_image);
                }
                $featuredImagePath = $request->file('featured_image')->store('pages', 'public');
            }

            if ($request->hasFile('section_image_left')) {
                if ($page->section_image_left) {
                    Storage::disk('public')->delete($page->section_image_left);
                }
                $sectionImageLeftPath = $request->file('section_image_left')->store('pages', 'public');
            }

            if ($request->hasFile('section_image_right')) {
                if ($page->section_image_right) {
                    Storage::disk('public')->delete($page->section_image_right);
                }
                $sectionImageRightPath = $request->file('section_image_right')->store('pages', 'public');
            }

            $data = [
                'title' => $request->title,
                'slug' => $slug,
                'page_category' => $request->page_category,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'content_right' => $request->content_right,
                'content_left' => $request->content_left,
                'featured_image' => $featuredImagePath,
                'section_image_left' => $sectionImageLeftPath,
                'section_image_right' => $sectionImageRightPath,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'schema_markup' => $request->schema_markup,
            ];

            $page->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $page->fresh(),
                'message' => 'Page updated successfully'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Page update error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Page update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $page = Page::find($id);

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found'
            ], 404);
        }

        DB::beginTransaction();

        try {
            // Delete all images
            if ($page->featured_image) {
                Storage::disk('public')->delete($page->featured_image);
            }
            if ($page->section_image_left) {
                Storage::disk('public')->delete($page->section_image_left);
            }
            if ($page->section_image_right) {
                Storage::disk('public')->delete($page->section_image_right);
            }

            $page->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Page deleted successfully'
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
     * Remove page image.
     */
    public function removeImage(Request $request, string $id, string $imageType)
    {
        $page = Page::find($id);

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found'
            ], 404);
        }

        $imageField = null;
        if ($imageType === 'featured') {
            $imageField = 'featured_image';
        } elseif ($imageType === 'section_left') {
            $imageField = 'section_image_left';
        } elseif ($imageType === 'section_right') {
            $imageField = 'section_image_right';
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid image type'
            ], 422);
        }

        if ($page->$imageField) {
            Storage::disk('public')->delete($page->$imageField);
            $page->update([$imageField => null]);

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
     * Analyze SEO for page.
     */
    public function analyzeSeo(Request $request, string $id = null)
    {
        $data = $request->only([
            'title', 'slug', 'description',
            'meta_title', 'meta_description'
        ]);

        $seoMetrics = [];
        $totalWeight = 0;
        $achievedWeight = 0;

        // Analyze Page Title
        $titleLength = strlen($data['title'] ?? '');
        $titleStatus = $titleLength >= 10 && $titleLength <= 60;
        $seoMetrics[] = [
            'name' => 'Page Title',
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
