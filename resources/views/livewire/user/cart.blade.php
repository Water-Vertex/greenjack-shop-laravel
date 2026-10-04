<div>
    {{-- ✅ Session flash → Toaster (page load pe) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session()->has('success'))
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: { message: @json(session('success')), type: 'success' }
                }));
            @endif
            @if(session()->has('error'))
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: { message: @json(session('error')), type: 'error' }
                }));
            @endif
            @if(session()->has('info'))
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: { message: @json(session('info')), type: 'info' }
                }));
            @endif
            @if(session()->has('warning'))
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: { message: @json(session('warning')), type: 'warning' }
                }));
            @endif
        });
    </script>

    @php
        $cartItems = $cartItems instanceof \Illuminate\Support\Collection
            ? $cartItems
            : collect($cartItems ?? []);
        $cartCount = $cartCount ?? 0;
        $cartTotal = $cartTotal ?? 0;
    @endphp

    <div class="container mx-auto px-3 sm:px-4 max-w-7xl py-4 sm:py-6 md:py-8">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6">Shopping Cart</h1>

        @if($cartItems->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                <!-- Cart Items - Left Column -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="divide-y divide-gray-200">
                            @foreach($cartItems as $item)
                                @php
                                    $itemId = $item['id'] ?? null;
                                    $itemName = $item['name'] ?? 'Unknown Item';
                                    $itemImage = $item['image'] ?? null;
                                    $itemPrice = (float) ($item['price'] ?? 0);
                                    $itemQty = (int) ($item['quantity'] ?? 0);
                                    $itemTotal = $itemPrice * $itemQty;
                                @endphp
                                <div class="p-3 sm:p-4 flex flex-col sm:flex-row gap-3 sm:gap-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0 flex justify-center sm:block">
                                        <img src="{{ $itemImage ? asset('storage/' . $itemImage) : 'https://placehold.co/100x100/e2e8f0/475569?text=No+Image' }}"
                                             onerror="this.src='https://placehold.co/100x100/e2e8f0/475569?text=No+Image'"
                                             alt="{{ $itemName }}"
                                             class="w-20 h-20 sm:w-24 sm:h-24 rounded object-cover">
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-gray-800 text-sm sm:text-base line-clamp-2">{{ $itemName }}</h3>
                                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5 sm:mt-1">Price: ${{ number_format($itemPrice, 2) }}</p>

                                        <!-- Mobile: Price and Quantity Row -->
                                        <div class="flex flex-wrap items-center justify-between sm:justify-start gap-2 sm:gap-3 mt-2 sm:mt-3">
                                            <div class="flex items-center border border-gray-300 rounded">
                                                <button wire:click="updateQuantity({{ $itemId }}, {{ $itemQty - 1 }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="updateQuantity({{ $itemId }}, {{ $itemQty - 1 }})"
                                                        @if($itemQty <= 1) disabled @endif
                                                        class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center hover:bg-gray-100 text-sm sm:text-base disabled:opacity-40 disabled:cursor-not-allowed">
                                                    -
                                                </button>
                                                <span class="w-8 sm:w-12 text-center text-sm sm:text-base">{{ $itemQty }}</span>
                                                <button wire:click="updateQuantity({{ $itemId }}, {{ $itemQty + 1 }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="updateQuantity({{ $itemId }}, {{ $itemQty + 1 }})"
                                                        class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center hover:bg-gray-100 text-sm sm:text-base">
                                                    +
                                                </button>
                                            </div>
                                            <button wire:click="removeItem({{ $itemId }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="removeItem({{ $itemId }})"
                                                    class="text-red-500 text-xs sm:text-sm hover:underline disabled:opacity-50">
                                                <span wire:loading.remove wire:target="removeItem({{ $itemId }})">Remove</span>
                                                <span wire:loading wire:target="removeItem({{ $itemId }})">Removing...</span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Item Total - Desktop -->
                                    <div class="hidden sm:block text-right flex-shrink-0">
                                        <p class="font-bold text-gray-800 text-sm sm:text-base">${{ number_format($itemTotal, 2) }}</p>
                                    </div>

                                    <!-- Item Total - Mobile -->
                                    <div class="sm:hidden flex justify-between items-center pt-2 border-t border-gray-100 mt-2">
                                        <span class="text-sm text-gray-600">Total:</span>
                                        <span class="font-bold text-gray-800 text-base">${{ number_format($itemTotal, 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Continue Shopping - Mobile -->
                    <div class="mt-4 sm:mt-6">
                        <a href="{{ route('shop') }}" wire:navigate class="inline-flex items-center text-[#5B9F01] hover:text-[#4a7f01] transition text-sm sm:text-base">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Continue Shopping
                        </a>
                    </div>
                </div>

                <!-- Order Summary - Right Column -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 sticky top-4">
                        <h2 class="text-base sm:text-lg font-bold text-gray-800 mb-3 sm:mb-4">Order Summary</h2>

                        <div class="space-y-2 border-b border-gray-200 pb-3 sm:pb-4">
                            <div class="flex justify-between text-sm sm:text-base">
                                <span class="text-gray-600">Subtotal ({{ $cartCount }} items)</span>
                                <span class="font-medium text-gray-800">${{ number_format($cartTotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm sm:text-base">
                                <span class="text-gray-600">Shipping</span>
                                <span class="font-medium text-green-600">Free</span>
                            </div>
                            @if($cartTotal > 0)
                                <div class="flex justify-between text-sm sm:text-base text-gray-600">
                                    <span>Estimated Tax</span>
                                    <span>$0.00</span>
                                </div>
                            @endif
                        </div>

                        <div class="flex justify-between pt-3 sm:pt-4 mb-4 sm:mb-6">
                            <span class="font-bold text-base sm:text-lg text-gray-800">Total</span>
                            <span class="font-bold text-lg sm:text-xl text-[#5B9F01]">${{ number_format($cartTotal, 2) }}</span>
                        </div>

                        <a href="{{ route('checkout.page') }}"
                           wire:navigate
                           class="block w-full bg-[#5B9F01] text-white py-2.5 sm:py-3 rounded-lg font-semibold text-center hover:bg-[#4a7f01] transition text-sm sm:text-base">
                            Proceed to Checkout
                        </a>

                        <!-- Secure Checkout Badge -->
                        <div class="flex items-center justify-center gap-2 mt-3 text-xs text-gray-500">
                            <i class="fas fa-lock text-[#5B9F01]"></i>
                            <span>Secure Checkout</span>
                            <span class="w-px h-3 bg-gray-300"></span>
                            <i class="fas fa-credit-card text-[#5B9F01]"></i>
                            <span>SSL Encrypted</span>
                        </div>

                        <!-- Payment Icons -->
                        <div class="flex items-center justify-center gap-3 mt-3 text-gray-400 text-xl">
                            <i class="fab fa-cc-visa"></i>
                            <i class="fab fa-cc-mastercard"></i>
                            <i class="fab fa-cc-amex"></i>
                            <i class="fab fa-cc-paypal"></i>
                            <i class="fab fa-cc-apple-pay"></i>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="bg-white rounded-lg shadow-sm p-8 sm:p-12 text-center">
                <i class="fas fa-shopping-cart text-5xl sm:text-6xl text-gray-300 mb-3 sm:mb-4"></i>
                <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2">Your cart is empty</h2>
                <p class="text-sm sm:text-base text-gray-500 mb-4 sm:mb-6">Looks like you haven't added any items to your cart yet.</p>
                <a href="{{ route('shop') }}" wire:navigate class="inline-block bg-[#5B9F01] text-white px-5 sm:px-6 py-2.5 sm:py-3 rounded-lg hover:bg-[#4a7f01] transition text-sm sm:text-base">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Start Shopping
                </a>
            </div>
        @endif
    </div>

    <!-- Additional Responsive Styles -->
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @media (max-width: 640px) {
            button {
                -webkit-tap-highlight-color: transparent;
            }
            .w-7, .w-8, .h-7, .h-8 {
                touch-action: manipulation;
            }
        }

        .transition {
            transition: all 0.2s ease-in-out;
        }

        .border-gray-300 button:hover {
            background-color: #f3f4f6;
        }

        .text-gray-400 .fab:hover {
            color: #5B9F01;
            transform: scale(1.1);
            transition: all 0.2s ease;
        }
    </style>

    <!-- Mobile Bottom Navigation for Cart -->
    @if($cartItems->count() > 0)
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-3 shadow-lg lg:hidden z-40">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">Total</p>
                    <p class="font-bold text-lg text-[#5B9F01]">${{ number_format($cartTotal, 2) }}</p>
                </div>
                <a href="{{ route('checkout.page') }}"
                   wire:navigate
                   class="flex-1 ml-4 bg-[#5B9F01] text-white py-2.5 rounded-lg font-semibold text-center hover:bg-[#4a7f01] transition text-sm">
                    Checkout
                </a>
            </div>
        </div>
        {{-- Bottom nav ke liye extra padding --}}
        <div class="h-20 lg:hidden"></div>
    @endif

   
</div>

