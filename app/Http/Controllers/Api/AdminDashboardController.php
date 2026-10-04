<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function index(Request $request)
    {
        try {
            Log::info('Dashboard index called');

            $data = [
                // Order Stats
                'total_orders' => $this->getTotalOrders(),
                'total_revenue' => $this->getTotalRevenue(),
                'today_orders' => $this->getTodayOrders(),
                'today_revenue' => $this->getTodayRevenue(),
                'this_week_orders' => $this->getThisWeekOrders(),
                'this_week_revenue' => $this->getThisWeekRevenue(),
                'this_month_orders' => $this->getThisMonthOrders(),
                'this_month_revenue' => $this->getThisMonthRevenue(),
                'last_month_orders' => $this->getLastMonthOrders(),
                'last_month_revenue' => $this->getLastMonthRevenue(),

                // Payment Method Stats
                'paypal_orders' => $this->getPayPalOrders(),
                'paypal_revenue' => $this->getPayPalRevenue(),
                'stripe_orders' => $this->getStripeOrders(),
                'stripe_revenue' => $this->getStripeRevenue(),
                'bank_transfer_orders' => $this->getBankTransferOrders(),
                'bank_transfer_revenue' => $this->getBankTransferRevenue(),

                // Order Status
                'orders_by_status' => $this->getOrdersByStatus(),

                // Recent Orders
                'recent_orders' => $this->getRecentOrders(),

                // Chart Data
                'orders_chart_data' => $this->getOrdersChartData(),
                'revenue_chart_data' => $this->getRevenueChartData(),

                // Total Customers
                'total_customers' => $this->getTotalCustomers(),
            ];

            Log::info('Dashboard data fetched successfully');

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get quick stats for dashboard cards
     */
    public function getQuickStats()
    {
        try {
            Log::info('Quick stats called');

            $data = [
                // Order Stats
                'total_orders' => $this->getTotalOrders(),
                'total_revenue' => $this->getTotalRevenue(),
                'today_orders' => $this->getTodayOrders(),
                'today_revenue' => $this->getTodayRevenue(),
                'this_week_orders' => $this->getThisWeekOrders(),
                'this_week_revenue' => $this->getThisWeekRevenue(),
                'this_month_orders' => $this->getThisMonthOrders(),
                'this_month_revenue' => $this->getThisMonthRevenue(),
                'last_month_orders' => $this->getLastMonthOrders(),
                'last_month_revenue' => $this->getLastMonthRevenue(),

                // Payment Method Stats
                'paypal_orders' => $this->getPayPalOrders(),
                'paypal_revenue' => $this->getPayPalRevenue(),
                'stripe_orders' => $this->getStripeOrders(),
                'stripe_revenue' => $this->getStripeRevenue(),

                // Pending Orders
                'pending_orders' => Order::where('status', 'pending')->count(),

                // Total Customers
                'total_customers' => $this->getTotalCustomers(),
            ];

            // Calculate percentage changes
            $data['orders_growth'] = $this->calculateGrowth('orders');
            $data['revenue_growth'] = $this->calculateGrowth('revenue');

            Log::info('Quick stats fetched successfully');

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Quick stats error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch quick stats',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get revenue summary
     */
    public function getRevenueSummary(Request $request)
    {
        try {
            Log::info('Revenue summary called');

            $data = [
                'total_revenue' => $this->getTotalRevenue(),
                'today_revenue' => $this->getTodayRevenue(),
                'this_week_revenue' => $this->getThisWeekRevenue(),
                'this_month_revenue' => $this->getThisMonthRevenue(),
                'last_month_revenue' => $this->getLastMonthRevenue(),
                'average_order_value' => $this->getAverageOrderValue(),
                'paypal_revenue' => $this->getPayPalRevenue(),
                'stripe_revenue' => $this->getStripeRevenue(),
                'bank_transfer_revenue' => $this->getBankTransferRevenue(),
            ];

            Log::info('Revenue summary fetched successfully');

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Revenue summary error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch revenue summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ==================== ORDER QUERIES ====================

    public function getTotalOrders()
    {
        try {
            return Order::where('status', '!=', 'cancelled')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getTotalRevenue()
    {
        try {
            return Order::where('status', '!=', 'cancelled')->sum('grand_total') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getTodayOrders()
    {
        try {
            return Order::whereDate('created_at', Carbon::today())
                ->where('status', '!=', 'cancelled')
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getTodayRevenue()
    {
        try {
            return Order::whereDate('created_at', Carbon::today())
                ->where('status', '!=', 'cancelled')
                ->sum('grand_total') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getThisWeekOrders()
    {
        try {
            return Order::whereDate('created_at', '>=', Carbon::now()->startOfWeek())
                ->whereDate('created_at', '<=', Carbon::now()->endOfWeek())
                ->where('status', '!=', 'cancelled')
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getThisWeekRevenue()
    {
        try {
            return Order::whereDate('created_at', '>=', Carbon::now()->startOfWeek())
                ->whereDate('created_at', '<=', Carbon::now()->endOfWeek())
                ->where('status', '!=', 'cancelled')
                ->sum('grand_total') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getThisMonthOrders()
    {
        try {
            return Order::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->where('status', '!=', 'cancelled')
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getThisMonthRevenue()
    {
        try {
            return Order::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->where('status', '!=', 'cancelled')
                ->sum('grand_total') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getLastMonthOrders()
    {
        try {
            $lastMonth = Carbon::now()->subMonth();
            return Order::whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->where('status', '!=', 'cancelled')
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getLastMonthRevenue()
    {
        try {
            $lastMonth = Carbon::now()->subMonth();
            return Order::whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->where('status', '!=', 'cancelled')
                ->sum('grand_total') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getAverageOrderValue()
    {
        try {
            $totalOrders = Order::where('status', '!=', 'cancelled')->count();
            $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('grand_total');

            if ($totalOrders > 0) {
                return $totalRevenue / $totalOrders;
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    // ==================== PAYMENT METHOD QUERIES ====================

    public function getPayPalOrders()
    {
        try {
            return Order::where('payment_method', 'paypal')
                ->where('status', '!=', 'cancelled')
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getPayPalRevenue()
    {
        try {
            return Order::where('payment_method', 'paypal')
                ->where('status', '!=', 'cancelled')
                ->sum('grand_total') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getStripeOrders()
    {
        try {
            return Order::where('payment_method', 'stripe')
                ->where('status', '!=', 'cancelled')
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getStripeRevenue()
    {
        try {
            return Order::where('payment_method', 'stripe')
                ->where('status', '!=', 'cancelled')
                ->sum('grand_total') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getBankTransferOrders()
    {
        try {
            return Order::where('payment_method', 'bank_transfer')
                ->where('status', '!=', 'cancelled')
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getBankTransferRevenue()
    {
        try {
            return Order::where('payment_method', 'bank_transfer')
                ->where('status', '!=', 'cancelled')
                ->sum('grand_total') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    // ==================== ORDER STATUS ====================

    public function getOrdersByStatus()
    {
        try {
            $statuses = Order::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
                ->map(function ($item) {
                    return [
                        'name' => ucfirst(str_replace('_', ' ', $item->status ?? 'Unknown')),
                        'value' => (int) ($item->count ?? 0)
                    ];
                });

            return $statuses;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getOrderStatusDistribution()
    {
        try {
            $data = Order::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
                ->map(function ($item) {
                    return [
                        'name' => ucfirst(str_replace('_', ' ', $item->status ?? 'Unknown')),
                        'value' => (int) ($item->count ?? 0)
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch order status distribution',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ==================== RECENT ORDERS ====================

    public function getRecentOrders()
    {
        try {
            return Order::with(['user'])
                ->latest()
                ->limit(10)
                ->get()
                ->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'order_number' => $order->order_number ?? '#'.$order->id,
                        'customer_name' => $order->user ? $order->user->name : ($order->first_name . ' ' . $order->last_name),
                        'email' => $order->email ?? 'N/A',
                        'payment_method' => $order->payment_method ?? 'N/A',
                        'grand_total' => (float) ($order->grand_total ?? 0),
                        'status' => $order->status ?? 'pending',
                        'created_at' => $order->created_at
                    ];
                });
        } catch (\Exception $e) {
            return [];
        }
    }

    // ==================== CHART DATA ====================

    public function getOrdersChartData()
    {
        try {
            $data = [];
            $startDate = Carbon::now()->subDays(30);
            $endDate = Carbon::now();

            $orders = Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('count(*) as count')
                )
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->keyBy('date');

            $period = Carbon::parse($startDate);
            while ($period <= $endDate) {
                $date = $period->format('Y-m-d');
                $data[] = [
                    'date' => $date,
                    'count' => $orders->has($date) ? (int) $orders[$date]->count : 0,
                    'formatted_date' => $period->format('M d')
                ];
                $period->addDay();
            }

            return $data;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getRevenueChartData()
    {
        try {
            $data = [];
            $startDate = Carbon::now()->subDays(30);
            $endDate = Carbon::now();

            $revenue = Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(grand_total) as total')
                )
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->keyBy('date');

            $period = Carbon::parse($startDate);
            while ($period <= $endDate) {
                $date = $period->format('Y-m-d');
                $data[] = [
                    'date' => $date,
                    'total' => $revenue->has($date) ? (float) $revenue[$date]->total : 0,
                    'formatted_date' => $period->format('M d')
                ];
                $period->addDay();
            }

            return $data;
        } catch (\Exception $e) {
            return [];
        }
    }

    // ==================== CUSTOMERS ====================

    public function getTotalCustomers()
    {
        try {
            return User::where('role', 'customer')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    // ==================== GROWTH CALCULATIONS ====================

    protected function calculateGrowth($type)
    {
        try {
            $current = 0;
            $previous = 0;

            switch ($type) {
                case 'orders':
                    $current = Order::whereDate('created_at', '>=', Carbon::now()->subDays(30))
                        ->where('status', '!=', 'cancelled')
                        ->count();
                    $previous = Order::whereDate('created_at', '>=', Carbon::now()->subDays(60))
                        ->whereDate('created_at', '<', Carbon::now()->subDays(30))
                        ->where('status', '!=', 'cancelled')
                        ->count();
                    break;
                case 'revenue':
                    $current = Order::where('status', '!=', 'cancelled')
                        ->whereDate('created_at', '>=', Carbon::now()->subDays(30))
                        ->sum('grand_total');
                    $previous = Order::where('status', '!=', 'cancelled')
                        ->whereDate('created_at', '>=', Carbon::now()->subDays(60))
                        ->whereDate('created_at', '<', Carbon::now()->subDays(30))
                        ->sum('grand_total');
                    break;
                case 'customers':
                    $current = User::where('role', 'customer')
                        ->whereDate('created_at', '>=', Carbon::now()->subDays(30))
                        ->count();
                    $previous = User::where('role', 'customer')
                        ->whereDate('created_at', '>=', Carbon::now()->subDays(60))
                        ->whereDate('created_at', '<', Carbon::now()->subDays(30))
                        ->count();
                    break;
            }

            if ($previous > 0) {
                return round((($current - $previous) / $previous) * 100, 2);
            }
            return $current > 0 ? 100 : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function test()
    {
        return response()->json([
            'success' => true,
            'message' => 'Dashboard API is working!',
            'timestamp' => Carbon::now()->toDateTimeString()
        ]);
    }
}
