<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RepairRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RepairRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = RepairRequest::with(['category', 'brand', 'model']);

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('mobile', 'like', '%' . $search . '%')
                  ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Date range filters
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
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
            'message' => 'Repair requests retrieved successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $request = RepairRequest::with(['category', 'brand', 'model'])->find($id);

        if (!$request) {
            return response()->json([
                'success' => false,
                'message' => 'Repair request not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $request,
            'message' => 'Repair request retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $repairRequest = RepairRequest::find($id);

        if (!$repairRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Repair request not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
            'admin_notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $repairRequest->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes
            ]);

            return response()->json([
                'success' => true,
                'data' => $repairRequest->fresh()->with(['category', 'brand', 'model']),
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
        $repairRequest = RepairRequest::find($id);

        if (!$repairRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Repair request not found'
            ], 404);
        }

        try {
            $repairRequest->delete();

            return response()->json([
                'success' => true,
                'message' => 'Repair request deleted successfully'
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
     * Get status counts.
     */
    public function getStatusCounts()
    {
        $counts = [
            'total' => RepairRequest::count(),
            'pending' => RepairRequest::where('status', 'pending')->count(),
            'confirmed' => RepairRequest::where('status', 'confirmed')->count(),
            'in_progress' => RepairRequest::where('status', 'in_progress')->count(),
            'completed' => RepairRequest::where('status', 'completed')->count(),
            'cancelled' => RepairRequest::where('status', 'cancelled')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $counts,
            'message' => 'Status counts retrieved successfully'
        ]);
    }
}
