<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductImage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class PosApiController extends Controller
{
    // ===== PRODUCTS =====
    public function products(Request $request)
    {
        $query = Product::with(['category', 'subCategory', 'productImages']);

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

        // Sorting
        $sortField = $request->get('sort_field', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $products = $query->get();

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Products retrieved successfully'
        ]);
    }
    
    // ===== CATEGORIES =====
    public function categories(Request $request)
    {
        $query = Category::with(['parent', 'children']);

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('meta_title', 'like', '%' . $search . '%')
                  ->orWhere('meta_keywords', 'like', '%' . $search . '%');
            });
        }

        // Sorting
        $sortField = $request->get('sort_field', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $categories = $query->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
            'message' => 'Categories retrieved successfully'
        ]);
    }

    // ===== GET ALL ORDERS - UPDATED =====
    public function getOrders(Request $request)
    {
        try {
            // Added shippingDetail to eager loading
            $query = Order::with(['customer', 'items', 'shippingDetail']);

            // Filter by status
            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }

            // Filter by payment status
            if ($request->has('payment_status') && !empty($request->payment_status)) {
                $query->where('payment_status', $request->payment_status);
            }

            // Filter by payment method
            if ($request->has('payment_method') && !empty($request->payment_method)) {
                $query->where('payment_method', $request->payment_method);
            }

            // Search filter - added 'id' search
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('order_number', 'like', '%' . $search . '%')
                      ->orWhere('first_name', 'like', '%' . $search . '%')
                      ->orWhere('last_name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('phone', 'like', '%' . $search . '%')
                      ->orWhere('id', 'like', '%' . $search . '%'); // Added
                });
            }

            // Date range filter
            if ($request->has('date_from') && !empty($request->date_from)) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && !empty($request->date_to)) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Period filter
            if ($request->has('period') && !empty($request->period)) {
                $this->applyPeriodFilter($query, $request->period);
            }

            // Sorting
            $sortField = $request->get('sort_field', 'created_at');
            $sortDirection = $request->get('sort_direction', 'desc');
            $query->orderBy($sortField, $sortDirection);

            // Pagination - SAME as OrderController
            $perPage = $request->get('per_page', 10);
            $orders = $query->paginate($perPage);

            // SAME response format as OrderController
            return response()->json([
                'success' => true,
                'data' => $orders,
                'message' => 'Orders retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching orders: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch orders: ' . $e->getMessage()
            ], 500);
        }
    }

    // ===== GET SINGLE ORDER - UPDATED =====
    public function getOrder($id)
    {
        try {
            $order = Order::with(['customer', 'items', 'shippingDetail']) // Added shippingDetail
                ->find($id);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Order retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching order: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch order: ' . $e->getMessage()
            ], 500);
        }
    }

    // ===== UPDATE ORDER STATUS =====
    public function updateOrderStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|string|in:pending,processing,shipped,delivered,completed,cancelled'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed'
                ], 422);
            }

            $order = Order::find($id);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            $oldStatus = $order->status;
            $order->status = $request->status;
            $order->save();

            Log::info('Order status updated', [
                'order_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $order->id,
                    'status' => $order->status
                ],
                'message' => 'Order status updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating order status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order status: ' . $e->getMessage()
            ], 500);
        }
    }

    // ===== DELETE ORDER =====
    public function deleteOrder($id)
    {
        try {
            $order = Order::find($id);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            // Delete order items first
            $order->items()->delete();
            
            // Delete shipping detail
            if ($order->shippingDetail) {
                $order->shippingDetail()->delete();
            }
            
            // Delete the order
            $order->delete();

            Log::info('Order deleted', ['order_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting order: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order: ' . $e->getMessage()
            ], 500);
        }
    }

    // ===== GET ORDER STATISTICS =====
    public function getOrderStats(Request $request)
    {
        try {
            $period = $request->get('period', 'current_month');
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');

            $query = Order::query();

            if ($dateFrom && $dateTo) {
                $query->whereDate('created_at', '>=', $dateFrom)
                      ->whereDate('created_at', '<=', $dateTo);
            } else {
                $this->applyPeriodFilter($query, $period);
            }

            // Get statistics
            $stats = [
                'total_orders' => $query->count(),
                'total_revenue' => $query->sum('grand_total'),
                'average_order_value' => $query->avg('grand_total'),
                'pending_orders' => (clone $query)->where('status', 'pending')->count(),
                'processing_orders' => (clone $query)->where('status', 'processing')->count(),
                'completed_orders' => (clone $query)->where('status', 'completed')->count(),
                'cancelled_orders' => (clone $query)->where('status', 'cancelled')->count(),
            ];

            // Get daily breakdown
            $dailyQuery = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(grand_total) as revenue')
            )
            ->groupBy(DB::raw('DATE(created_at)'));

            if ($dateFrom && $dateTo) {
                $dailyQuery->whereDate('created_at', '>=', $dateFrom)
                           ->whereDate('created_at', '<=', $dateTo);
            } else {
                $this->applyPeriodFilter($dailyQuery, $period);
            }

            $dailyData = $dailyQuery->orderBy('date', 'asc')->get();

            // Get payment method breakdown
            $paymentQuery = Order::select(
                'payment_method',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(grand_total) as total')
            )
            ->groupBy('payment_method');

            if ($dateFrom && $dateTo) {
                $paymentQuery->whereDate('created_at', '>=', $dateFrom)
                             ->whereDate('created_at', '<=', $dateTo);
            } else {
                $this->applyPeriodFilter($paymentQuery, $period);
            }

            $paymentData = $paymentQuery->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats,
                    'daily_breakdown' => $dailyData,
                    'payment_breakdown' => $paymentData,
                    'period' => $period
                ],
                'message' => 'Order statistics retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching order stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch order statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    // ===== APPLY PERIOD FILTER =====
    private function applyPeriodFilter($query, $period)
    {
        switch ($period) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'current_week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'current_month':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month)
                      ->whereYear('created_at', Carbon::now()->subMonth()->year);
                break;
            default:
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
        }
    }
}