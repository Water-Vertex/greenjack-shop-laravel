<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProgramaticSeo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProgramaticSeoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProgramaticSeo::query();

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('focus_keyword', 'like', '%' . $search . '%');
        }

        // Sorting
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $records = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $records,
            'message' => 'Records retrieved successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = ProgramaticSeo::find($id);

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $record,
            'message' => 'Record retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $record = ProgramaticSeo::find($id);

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'focus_keyword' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'meta_tags' => 'nullable|string',
            'page_schema' => 'nullable|string',
            'content' => 'nullable|string',
            'image' => 'nullable|url|max:500',
            'image_alt' => 'nullable|string|max:255',
            'h1_heading' => 'nullable|string|max:255',
            'faqs' => 'nullable|string',
            'section_content_left' => 'nullable|string',
            'section_content_right' => 'nullable|string',
            'image_left' => 'nullable|url|max:500',
            'image_right' => 'nullable|url|max:500',
            'image_left_alt' => 'nullable|string|max:255',
            'image_right_alt' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $data = $request->all();
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['focus_keyword'] ?? '');
            }

            $record->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $record->fresh(),
                'message' => 'Record updated successfully'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Record update error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Record update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = ProgramaticSeo::find($id);

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found'
            ], 404);
        }

        DB::beginTransaction();

        try {
            $record->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Record deleted successfully'
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
     * Import records from CSV/Excel file.
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csvFile' => 'required|file|mimes:csv,xls,xlsx,txt|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $path = $request->file('csvFile')->getRealPath();
            $extension = strtolower($request->file('csvFile')->getClientOriginalExtension());

            $rows = in_array($extension, ['xls', 'xlsx'])
                ? IOFactory::load($path)->getActiveSheet()->toArray(null, true, true, false)
                : array_map('str_getcsv', file($path));

            $rows = array_values(array_filter($rows, fn($row) => !empty(array_filter($row))));

            if (count($rows) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data found in file.'
                ], 422);
            }

            $header = array_map(fn($h) => strtolower(str_replace([' ', "\xEF\xBB\xBF"], ['_', ''], trim((string)$h))), $rows[0]);
            unset($rows[0]);

            $counts = ['new' => 0, 'updated' => 0, 'skipped' => 0];

            foreach ($rows as $r) {
                $r = array_values($r);
                if (count($header) !== count($r)) continue;
                $data = array_combine($header, $r);
                if (empty(trim($data['focus_keyword'] ?? ''))) continue;

                $mappedData = $this->mapImportRow($data);
                if (empty($mappedData['slug'])) {
                    $mappedData['slug'] = Str::slug($mappedData['focus_keyword']);
                }

                $existing = ProgramaticSeo::where('focus_keyword', $mappedData['focus_keyword'])->first();

                if ($existing) {
                    $isDifferent = false;
                    foreach ($mappedData as $key => $value) {
                        if (trim((string)($existing->$key ?? '')) !== trim((string)($value ?? ''))) {
                            $isDifferent = true;
                            break;
                        }
                    }
                    if ($isDifferent) {
                        $existing->update($mappedData);
                        $counts['updated']++;
                    } else {
                        $counts['skipped']++;
                    }
                } else {
                    ProgramaticSeo::create($mappedData);
                    $counts['new']++;
                }
            }

            $message = ($counts['new'] > 0 || $counts['updated'] > 0)
                ? "Import completed successfully!"
                : "No changes detected. This file is already uploaded.";

            return response()->json([
                'success' => true,
                'data' => [
                    'summary' => $counts,
                    'message' => $message
                ],
                'message' => $message
            ]);

        } catch (\Exception $e) {
            Log::error('Import error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download import template.
     */
    public function downloadTemplate()
    {
        $headers = [
            'focus_keyword',
            'title',
            'slug',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'meta_tags',
            'page_schema',
            'content',
            'image',
            'image_alt',
            'h1_heading',
            'faqs',
            'section_content_left',
            'section_content_right',
            'image_left',
            'image_right',
            'image_left_alt',
            'image_right_alt'
        ];

        return response()->streamDownload(function () use ($headers) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $headers);

            fputcsv($file, [
                'iphone repair',
                'iPhone Repair Services',
                'iphone-repair',
                'iPhone Repair - Expert Services',
                'Get professional iPhone repair services',
                'iphone, repair, apple',
                'iphone-repair',
                '{"@context":"https://schema.org"}',
                'Content about iPhone repair...',
                'https://example.com/images/iphone-repair.jpg',
                'iPhone Repair Image',
                'Professional iPhone Repair',
                '[{"question":"How long does iPhone repair take?","answer":"Most repairs are completed within 1-2 hours."}]',
                'Left section content...',
                'Right section content...',
                'https://example.com/images/left-image.jpg',
                'https://example.com/images/right-image.jpg',
                'Left image alt text',
                'Right image alt text'
            ]);

            fclose($file);
        }, 'programatic_seo_template.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function mapImportRow($data)
    {
        $fields = [
            'title', 'slug', 'meta_title', 'meta_description', 'meta_keywords',
            'meta_tags', 'page_schema', 'focus_keyword', 'content', 'image',
            'image_alt', 'h1_heading', 'faqs', 'section_content_left',
            'section_content_right', 'image_left', 'image_right',
            'image_left_alt', 'image_right_alt'
        ];

        $mapped = [];
        foreach ($fields as $field) {
            $mapped[$field] = isset($data[$field]) ? trim((string)$data[$field]) : null;
        }
        return $mapped;
    }
}
