<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrintingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PrintingRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PrintingRequest::query();

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('service', 'like', '%' . $search . '%')
                  ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $requests = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $requests,
            'message' => 'Printing requests retrieved successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $request = PrintingRequest::find($id);

        if (!$request) {
            return response()->json([
                'success' => false,
                'message' => 'Printing request not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $request,
            'message' => 'Printing request retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $printingRequest = PrintingRequest::find($id);

        if (!$printingRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Printing request not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,in_progress,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $printingRequest->update([
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'data' => $printingRequest->fresh(),
                'message' => 'Status updated successfully'
            ]);

        } catch (\Throwable $e) {
            Log::error('Status update error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Status update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $printingRequest = PrintingRequest::find($id);

        if (!$printingRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Printing request not found'
            ], 404);
        }

        try {
            $printingRequest->delete();

            return response()->json([
                'success' => true,
                'message' => 'Printing request deleted successfully'
            ]);

        } catch (\Throwable $e) {
            Log::error('Delete error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update status only.
     */
    public function updateStatus(Request $request, string $id)
    {
        $printingRequest = PrintingRequest::find($id);

        if (!$printingRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Printing request not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,in_progress,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $printingRequest->update([
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'data' => $printingRequest->fresh(),
                'message' => 'Status updated successfully'
            ]);

        } catch (\Throwable $e) {
            Log::error('Status update error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Status update failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
