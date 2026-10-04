<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Get settings.
     */
    public function index()
    {
        $settings = Setting::first();

        if (!$settings) {
            $settings = Setting::create([]);
        }

        return response()->json([
            'success' => true,
            'data' => $settings,
            'message' => 'Settings retrieved successfully'
        ]);
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $settings = Setting::first();

        if (!$settings) {
            $settings = Setting::create([]);
        }

        $validator = Validator::make($request->all(), [
            'favicon' => 'nullable|max:1024',
            'main_logo' => 'nullable|max:2048',
            'footer_logo' => 'nullable|max:2048',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'instagram' => 'nullable|url',
            'pintrest' => 'nullable|url',
            'youtube' => 'nullable|url',
            'email' => 'nullable|email',
            'about' => 'nullable|string',
            'site_title' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_tags' => 'nullable|string',
            'meta_desc' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'head_tags' => 'nullable|string',
            'body_tags' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $data = $request->only([
                'facebook', 'twitter', 'linkedin', 'instagram', 'pintrest', 'youtube',
                'email', 'about', 'site_title', 'meta_title', 'meta_tags', 'meta_desc',
                'meta_keywords', 'head_tags', 'body_tags', 'phone', 'address'
            ]);

            // Handle file uploads
            if ($request->hasFile('favicon')) {
                if ($settings->favicon) {
                    Storage::disk('public')->delete($settings->favicon);
                }
                $data['favicon'] = $request->file('favicon')->store('settings', 'public');
            }

            if ($request->hasFile('main_logo')) {
                if ($settings->main_logo) {
                    Storage::disk('public')->delete($settings->main_logo);
                }
                $data['main_logo'] = $request->file('main_logo')->store('settings', 'public');
            }

            if ($request->hasFile('footer_logo')) {
                if ($settings->footer_logo) {
                    Storage::disk('public')->delete($settings->footer_logo);
                }
                $data['footer_logo'] = $request->file('footer_logo')->store('settings', 'public');
            }

            $settings->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $settings->fresh(),
                'message' => 'Settings updated successfully'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Settings update error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Settings update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove image.
     */
    public function removeImage(Request $request, string $type)
    {
        $settings = Setting::first();

        if (!$settings) {
            return response()->json([
                'success' => false,
                'message' => 'Settings not found'
            ], 404);
        }

        $field = match($type) {
            'favicon' => 'favicon',
            'main_logo' => 'main_logo',
            'footer_logo' => 'footer_logo',
            default => null
        };

        if (!$field || !$settings->$field) {
            return response()->json([
                'success' => false,
                'message' => 'No image found to remove'
            ], 404);
        }

        try {
            Storage::disk('public')->delete($settings->$field);
            $settings->update([$field => null]);

            return response()->json([
                'success' => true,
                'message' => ucfirst(str_replace('_', ' ', $type)) . ' removed successfully'
            ]);

        } catch (\Throwable $e) {
            Log::error('Image removal error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Image removal failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
