<div class="bg-[#EAEDED] min-h-screen font-['Amazon Ember', 'Helvetica Neue', Arial, sans-serif]">
    <div class="container mx-auto px-4 max-w-4xl py-12">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <!-- Cancelled Header -->
            <div class="bg-red-50 p-8 border-b border-red-200">
                <div class="flex items-center justify-center">
                    <div class="w-20 h-20 bg-red-500 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-center text-red-600 mt-4">Payment Cancelled</h1>
                <p class="text-center text-gray-600 mt-2">{{ $message }}</p>
            </div>

            <!-- Order Details -->
            <div class="p-8">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-red-800">
                                <strong>No charges were made.</strong> Your order is saved but not confirmed until payment is completed.
                            </p>
                        </div>
                    </div>
                </div>

                <h2 class="text-xl font-bold text-gray-900 mb-6">Order Details</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Order Number</p>
                            <p class="font-semibold text-gray-900">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Customer</p>
                            <p class="font-semibold text-gray-900">{{ $order->first_name }} {{ $order->last_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-semibold text-gray-900">{{ $order->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="font-semibold text-gray-900">{{ $order->phone }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Payment Method</p>
                            <p class="font-semibold text-gray-900 capitalize">{{ $order->payment_method }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Amount</p>
                            <p class="font-semibold text-gray-900 text-xl">${{ number_format($order->grand_total, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                Payment Pending
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Order Date</p>
                            <p class="font-semibold text-gray-900">{{ $order->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                @if($order->shippingDetail)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="font-semibold text-gray-900 mb-3">Shipping Address</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700">{{ $order->shippingDetail->address }}</p>
                        @if($order->shippingDetail->apartment)
                            <p class="text-gray-700">{{ $order->shippingDetail->apartment }}</p>
                        @endif
                        <p class="text-gray-700">{{ $order->shippingDetail->city }}, {{ $order->shippingDetail->state }} {{ $order->shippingDetail->zip_code }}</p>
                        <p class="text-gray-700">{{ $order->shippingDetail->country }}</p>
                    </div>
                </div>
                @endif

                <!-- Order Items -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="font-semibold text-gray-900 mb-3">Order Items</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        @if($order->items && $order->items->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach($order->items as $item)
                                <li class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                                        <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                    </div>
                                    <span class="font-medium text-gray-900">${{ number_format($item->subtotal, 2) }}</span>
                                </li>
                                @endforeach
                            </ul>

                            <!-- Summary -->
                            <div class="mt-3 pt-3 border-t border-gray-200 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium text-gray-900">${{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="font-medium text-gray-900">
                                        @if($order->shipping_cost > 0)
                                            ${{ number_format($order->shipping_cost, 2) }}
                                        @else
                                            Free
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Tax</span>
                                    <span class="font-medium text-gray-900">${{ number_format($order->tax_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t-2 border-gray-300">
                                    <span class="font-bold text-gray-900">Total</span>
                                    <span class="font-bold text-gray-900 text-lg">${{ number_format($order->grand_total, 2) }}</span>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No items found</p>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <button
                        wire:click="retryPayment"
                        class="inline-flex items-center justify-center px-6 py-3 bg-[#5C9F01] text-white rounded-lg hover:bg-[#4a7e01] transition-colors font-medium"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Retry Payment
                    </button>
                    <a href="{{ route('shop') }}"
                       wire:navigate
                       class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Continue Shopping
                    </a>
                    <a href="{{ route('home') }}"
                       wire:navigate
                       class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Return Home
                    </a>
                </div>

                <!-- Help Section -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200 text-center">
                    <p class="text-sm text-gray-600">
                        Need help?
                        <a href="{{ route('contact-us') }}" wire:navigate class="text-[#5C9F01] hover:underline font-medium">
                            Contact our support team
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
