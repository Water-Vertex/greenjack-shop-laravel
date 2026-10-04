<div>
    <!-- Header Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Orders</h1>
                <p class="text-sm text-gray-600 mt-1">Manage and track all customer orders</p>
            </div>
            <div class="text-sm text-gray-500 bg-gray-100 px-3 py-1.5 rounded-lg">
                Total Orders: <span class="font-semibold text-gray-900">{{ $totalOrders }}</span>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
            <div class="sm:col-span-1 lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Search by name, email, order number..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Per Page</label>
                <select wire:model.live="perPage"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" wire:click="sortBy('id')">
                            ID @if($sortField == 'id') <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ml-1"></i> @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" wire:click="sortBy('order_number')">
                            Order # @if($sortField == 'order_number') <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ml-1"></i> @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" wire:click="sortBy('first_name')">
                            Customer @if($sortField == 'first_name') <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ml-1"></i> @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" wire:click="sortBy('grand_total')">
                            Total @if($sortField == 'grand_total') <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ml-1"></i> @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" wire:click="sortBy('created_at')">
                            Date @if($sortField == 'created_at') <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ml-1"></i> @endif
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#5C9F01]">{{ $order->order_number }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $order->first_name }} {{ $order->last_name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->email }}</div>
                            <div class="text-xs text-gray-400">{{ $order->phone }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @foreach($order->items->take(2) as $item)
                                <div class="text-xs text-gray-700">• {{ $item->product_name }}</div>
                            @endforeach
                            @if($order->items->count() > 2)
                                <div class="text-xs text-gray-400">+{{ $order->items->count() - 2 }} more</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            ${{ number_format($order->grand_total, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('M d, Y') }}
                            <div class="text-xs text-gray-400">{{ $order->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button wire:click="viewOrder({{ $order->id }})"
                                class="text-blue-600 hover:text-blue-800 transition p-1" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">No orders found</p>
                            @if($search || $statusFilter)
                            <button wire:click="clearFilters" class="mt-3 text-sm text-[#5C9F01] hover:underline">
                                <i class="fas fa-undo-alt mr-1"></i> Clear filters
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden divide-y divide-gray-200">
            @forelse($orders as $order)
            <div class="p-4 hover:bg-gray-50 transition">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium text-gray-500">#{{ $order->id }}</span>
                            <span class="text-xs font-medium text-[#5C9F01]">{{ $order->order_number }}</span>
                        </div>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $order->first_name }} {{ $order->last_name }}</p>
                        <div class="mt-1 space-y-1">
                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                <i class="fas fa-envelope w-4 text-gray-400"></i>
                                {{ $order->email }}
                            </p>
                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                <i class="fas fa-phone w-4 text-gray-400"></i>
                                {{ $order->phone }}
                            </p>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500">Payment:</span>
                                <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-gray-900">${{ number_format($order->grand_total, 2) }}</span>
                                <span class="text-xs text-gray-400">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <div class="mt-1">
                                @foreach($order->items->take(2) as $item)
                                    <div class="text-xs text-gray-600">• {{ $item->product_name }}</div>
                                @endforeach
                                @if($order->items->count() > 2)
                                    <div class="text-xs text-gray-400">+{{ $order->items->count() - 2 }} more</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center ml-2 flex-shrink-0">
                        <button wire:click="viewOrder({{ $order->id }})"
                            class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-4 py-12 text-center">
                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">No orders found</p>
                @if($search || $statusFilter)
                <button wire:click="clearFilters" class="mt-3 text-sm text-[#5C9F01] hover:underline">
                    <i class="fas fa-undo-alt mr-1"></i> Clear filters
                </button>
                @endif
            </div>
            @endforelse
        </div>

        @if($orders->hasPages())
        <div class="border-t border-gray-200 px-4 sm:px-6 py-4 bg-gray-50">
            {{ $orders->links() }}
        </div>
        @endif
    </div>

    {{-- ==================== VIEW ORDER MODAL ==================== --}}
    @if($showViewModal && $viewingOrder)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="$set('showViewModal', false)"></div>

        {{-- Modal Box --}}
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-3xl max-h-[85vh] flex flex-col">

            {{-- Header --}}
            <div class="flex items-center justify-between px-4 sm:px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl sticky top-0 bg-white z-10">
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Order Details</h3>
                    <p class="text-sm text-[#5C9F01] font-medium">{{ $viewingOrder['order_number'] }}</p>
                </div>
                <button wire:click="$set('showViewModal', false)" class="text-gray-400 hover:text-gray-600 transition p-1">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            {{-- Scrollable Body --}}
            <div class="overflow-y-auto flex-1 px-4 sm:px-6 py-4 sm:py-5 space-y-5 sm:space-y-6">

                {{-- Customer Information --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 mb-3 pb-2 border-b border-gray-100 flex items-center gap-2">
                        <i class="fas fa-user text-[#5C9F01]"></i> Customer Information
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <p class="text-xs text-gray-500">Full Name</p>
                            <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $viewingOrder['first_name'] }} {{ $viewingOrder['last_name'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Email</p>
                            <a href="mailto:{{ $viewingOrder['email'] }}" class="text-sm text-blue-600 hover:underline mt-0.5 block break-all">{{ $viewingOrder['email'] }}</a>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Phone</p>
                            <a href="tel:{{ $viewingOrder['phone'] }}" class="text-sm text-blue-600 hover:underline mt-0.5 block">{{ $viewingOrder['phone'] }}</a>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Order Date</p>
                            <p class="text-sm text-gray-900 mt-0.5">{{ $viewingOrder['created_at'] }}</p>
                        </div>
                    </div>
                </div>

                {{-- Shipping Details --}}
                @if($viewingOrder['shippingDetail'])
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 mb-3 pb-2 border-b border-gray-100 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-[#5C9F01]"></i> Shipping Details
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <p class="text-xs text-gray-500">Billing Address</p>
                            <p class="text-sm text-gray-900 mt-0.5">
                                {{ $viewingOrder['shippingDetail']['address'] }}
                                @if($viewingOrder['shippingDetail']['apartment'])
                                    , {{ $viewingOrder['shippingDetail']['apartment'] }}
                                @endif
                            </p>
                            <p class="text-sm text-gray-900">
                                {{ $viewingOrder['shippingDetail']['city'] }},
                                {{ $viewingOrder['shippingDetail']['state'] }}
                                {{ $viewingOrder['shippingDetail']['zip_code'] }}
                            </p>
                            <p class="text-sm text-gray-900">{{ $viewingOrder['shippingDetail']['country'] }}</p>
                        </div>
                        @if($viewingOrder['shippingDetail']['different_shipping'])
                        <div>
                            <p class="text-xs text-gray-500">Shipping Address</p>
                            <p class="text-sm text-gray-900 mt-0.5">
                                {{ $viewingOrder['shippingDetail']['shipping_address'] }}
                                @if($viewingOrder['shippingDetail']['shipping_apartment'])
                                    , {{ $viewingOrder['shippingDetail']['shipping_apartment'] }}
                                @endif
                            </p>
                            <p class="text-sm text-gray-900">
                                {{ $viewingOrder['shippingDetail']['shipping_city'] }},
                                {{ $viewingOrder['shippingDetail']['shipping_state'] }}
                                {{ $viewingOrder['shippingDetail']['shipping_zip_code'] }}
                            </p>
                            <p class="text-sm text-gray-900">{{ $viewingOrder['shippingDetail']['shipping_country'] }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Payment Information --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 mb-3 pb-2 border-b border-gray-100 flex items-center gap-2">
                        <i class="fas fa-credit-card text-[#5C9F01]"></i> Payment Information
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <p class="text-xs text-gray-500">Payment Method</p>
                            <span class="inline-flex mt-1 px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst(str_replace('_', ' ', $viewingOrder['payment_method'])) }}
                            </span>
                        </div>
                        @if($viewingOrder['selected_bank'])
                        <div>
                            <p class="text-xs text-gray-500">Bank</p>
                            <p class="text-sm text-gray-900 mt-0.5">{{ $viewingOrder['selected_bank'] }}</p>
                        </div>
                        @endif
                        @if($viewingOrder['mobile_number'])
                        <div>
                            <p class="text-xs text-gray-500">Mobile Number</p>
                            <p class="text-sm text-gray-900 mt-0.5">{{ $viewingOrder['mobile_number'] }}</p>
                        </div>
                        @endif
                        @if($viewingOrder['transaction_id'])
                        <div>
                            <p class="text-xs text-gray-500">Transaction ID</p>
                            <p class="text-sm text-gray-900 mt-0.5">{{ $viewingOrder['transaction_id'] }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Order Items --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 mb-3 pb-2 border-b border-gray-100 flex items-center gap-2">
                        <i class="fas fa-shopping-cart text-[#5C9F01]"></i> Order Items
                    </h4>
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 sm:px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                        <th class="px-3 sm:px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Qty</th>
                                        <th class="px-3 sm:px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                                        <th class="px-3 sm:px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($viewingOrder['items'] as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 sm:px-4 py-3 text-gray-900 text-xs sm:text-sm">{{ $item['product_name'] }}</td>
                                        <td class="px-3 sm:px-4 py-3 text-center text-gray-600 text-xs sm:text-sm">{{ $item['quantity'] }}</td>
                                        <td class="px-3 sm:px-4 py-3 text-right text-gray-600 text-xs sm:text-sm">${{ number_format($item['price'], 2) }}</td>
                                        <td class="px-3 sm:px-4 py-3 text-right font-medium text-gray-900 text-xs sm:text-sm">${{ number_format($item['subtotal'], 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Order Summary --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 mb-3 pb-2 border-b border-gray-100 flex items-center gap-2">
                        <i class="fas fa-receipt text-[#5C9F01]"></i> Order Summary
                    </h4>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-2 max-w-xs ml-auto w-full sm:w-auto">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Subtotal</span>
                            <span>${{ number_format($viewingOrder['subtotal'], 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Shipping</span>
                            <span>{{ $viewingOrder['shipping_cost'] > 0 ? '$' . number_format($viewingOrder['shipping_cost'], 2) : 'Free' }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Tax</span>
                            <span>${{ number_format($viewingOrder['tax_amount'], 2) }}</span>
                        </div>
                        <div class="border-t border-gray-200 pt-2 flex justify-between font-bold text-gray-900">
                            <span>Grand Total</span>
                            <span class="text-[#5C9F01]">${{ number_format($viewingOrder['grand_total'], 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Order Notes --}}
                @if($viewingOrder['order_notes'])
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 mb-3 pb-2 border-b border-gray-100 flex items-center gap-2">
                        <i class="fas fa-sticky-note text-[#5C9F01]"></i> Order Notes
                    </h4>
                    <div class="bg-yellow-50 border border-yellow-100 p-4 rounded-lg">
                        <p class="text-sm text-gray-700 break-words">{{ $viewingOrder['order_notes'] }}</p>
                    </div>
                </div>
                @endif

            </div>

            {{-- Footer --}}
            <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                <button wire:click="$set('showViewModal', false)"
                    class="w-full sm:w-auto px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Close
                </button>
            </div>

        </div>
    </div>
    @endif

    {{-- Flash Messages --}}
    @if(session()->has('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
        class="fixed bottom-4 right-4 left-4 sm:left-auto z-50">
        <div class="bg-green-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
            <i class="fas fa-check-circle"></i>
            <span class="text-sm sm:text-base">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session()->has('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
        class="fixed bottom-4 right-4 left-4 sm:left-auto z-50">
        <div class="bg-red-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
            <i class="fas fa-exclamation-circle"></i>
            <span class="text-sm sm:text-base">{{ session('error') }}</span>
        </div>
    </div>
    @endif
</div>
