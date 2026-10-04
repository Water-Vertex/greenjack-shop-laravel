<div class="min-h-screen bg-gradient-to-br from-[#EAEDED] to-gray-100 font-sans">
    <div class="container mx-auto px-4 max-w-4xl py-8 md:py-12">
        <!-- Success Card -->
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden transform transition-all duration-500 hover:shadow-3xl">

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order): ?>
            <!-- Success Header -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 md:p-8 border-b border-green-200">
                <div class="flex flex-col items-center">
                    <!-- Animated Checkmark -->
                    <div class="relative">
                        <div class="w-20 h-20 md:w-24 md:h-24 bg-green-500 rounded-full flex items-center justify-center shadow-lg animate-bounce">
                            <svg class="w-10 h-10 md:w-12 md:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <!-- Sparkle effects -->
                        <div class="absolute -top-2 -right-2">
                            <svg class="w-6 h-6 text-yellow-400 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <div class="absolute -bottom-2 -left-2">
                            <svg class="w-5 h-5 text-green-400 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                    </div>

                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-center text-green-600 mt-4">
                        Payment Successful! 🎉
                    </h1>
                    <p class="text-center text-gray-600 mt-2 text-sm md:text-base">
                        <?php echo e($message ?? 'Your order has been confirmed successfully.'); ?>

                    </p>
                </div>
            </div>

            <!-- Order Details Section -->
            <div class="p-6 md:p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900">Order Details</h2>
                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs md:text-sm font-semibold rounded-full">
                        Order #<?php echo e($order->order_number); ?>

                    </span>
                </div>

                <!-- Two Column Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Customer</p>
                                <p class="font-semibold text-gray-900"><?php echo e($order->first_name); ?> <?php echo e($order->last_name); ?></p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Email</p>
                                <p class="font-semibold text-gray-900"><?php echo e($order->email); ?></p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Phone</p>
                                <p class="font-semibold text-gray-900"><?php echo e($order->phone); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Payment Method</p>
                                <p class="font-semibold text-gray-900 capitalize">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->payment_method == 'stripe'): ?>
                                        💳 Stripe
                                    <?php elseif($order->payment_method == 'paypal'): ?>
                                        💰 PayPal
                                    <?php else: ?>
                                        <?php echo e($order->payment_method); ?>

                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Order Date</p>
                                <p class="font-semibold text-gray-900"><?php echo e($order->created_at->format('F d, Y')); ?></p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Status</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs md:text-sm font-medium
                                    <?php echo e($order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                    <span class="w-2 h-2 <?php echo e($order->payment_status === 'paid' ? 'bg-green-500' : 'bg-yellow-500'); ?> rounded-full mr-2 inline-block"></span>
                                    <?php echo e(ucfirst($order->payment_status)); ?>

                                </span>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Total Amount</p>
                                <p class="font-semibold text-green-600 text-lg">$<?php echo e(number_format($order->grand_total, 2)); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->shippingDetail): ?>
                <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-gray-900">Shipping Address</h3>
                            <div class="mt-1 text-gray-700 text-sm">
                                <p><?php echo e($order->shippingDetail->address); ?></p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->shippingDetail->apartment): ?>
                                    <p><?php echo e($order->shippingDetail->apartment); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <p><?php echo e($order->shippingDetail->city); ?>, <?php echo e($order->shippingDetail->state); ?> <?php echo e($order->shippingDetail->zip_code); ?></p>
                                <p><?php echo e($order->shippingDetail->country); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Order Items -->
                <div class="mt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-900">Order Items</h3>
                        <span class="text-sm text-gray-500"><?php echo e($order->items->count()); ?> items</span>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->items && $order->items->count() > 0): ?>
                            <div class="space-y-3 divide-y divide-gray-200">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="py-3 first:pt-0 last:pb-0">
                                    <div class="flex justify-between items-center">
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900"><?php echo e($item->product_name); ?></p>
                                            <div class="flex items-center space-x-4 mt-1">
                                                <span class="text-sm text-gray-500">Qty: <?php echo e($item->quantity); ?></span>
                                                <span class="text-sm text-gray-500">× $<?php echo e(number_format($item->price, 2)); ?></span>
                                            </div>
                                        </div>
                                        <span class="font-semibold text-gray-900">$<?php echo e(number_format($item->subtotal, 2)); ?></span>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <!-- Order Summary -->
                            <div class="mt-4 pt-4 border-t-2 border-gray-200 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium text-gray-900">$<?php echo e(number_format($order->subtotal, 2)); ?></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="font-medium text-gray-900">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->shipping_cost > 0): ?>
                                            $<?php echo e(number_format($order->shipping_cost, 2)); ?>

                                        <?php else: ?>
                                            <span class="text-green-600">Free</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Tax</span>
                                    <span class="font-medium text-gray-900">$<?php echo e(number_format($order->tax_amount, 2)); ?></span>
                                </div>
                                <div class="flex justify-between pt-2 border-t-2 border-gray-300">
                                    <span class="font-bold text-gray-900">Total</span>
                                    <span class="font-bold text-green-600 text-lg">$<?php echo e(number_format($order->grand_total, 2)); ?></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-500 text-center py-4">No items found</p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                    <h3 class="font-semibold text-blue-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        What's Next?
                    </h3>
                    <ul class="mt-3 space-y-3 text-sm text-blue-800">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>A confirmation email has been sent to <strong><?php echo e($order->email); ?></strong></span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Your order will be processed within <strong>24-48 hours</strong></span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>You will receive a shipping confirmation once your order ships</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span>Keep your order number <strong><?php echo e($order->order_number); ?></strong> for reference</span>
                        </li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="<?php echo e(route('shop')); ?>"
                       wire:navigate
                       class="inline-flex items-center justify-center px-6 py-3 bg-[#5C9F01] hover:bg-[#4a7e01] text-white rounded-lg transition-all duration-200 font-medium shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Continue Shopping
                    </a>
                    <a href="<?php echo e(route('home')); ?>"
                       wire:navigate
                       class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-all duration-200 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Return Home
                    </a>
                </div>
            </div>

            <?php elseif($errorMessage): ?>
            <!-- Error State -->
            <div class="bg-red-50 p-8 border-b border-red-200">
                <div class="flex items-center justify-center">
                    <div class="w-20 h-20 bg-red-500 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-center text-red-600 mt-4">Payment Verification Failed</h1>
                <p class="text-center text-gray-600 mt-2"><?php echo e($errorMessage); ?></p>
            </div>
            <div class="p-8 text-center">
                <p class="text-gray-600 mb-6"><?php echo e($message ?? 'We could not verify your payment.'); ?></p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo e(route('shop')); ?>"
                       wire:navigate
                       class="inline-flex items-center justify-center px-6 py-3 bg-[#5C9F01] text-white rounded-lg hover:bg-[#4a7e01] transition-colors font-medium">
                        Continue Shopping
                    </a>
                    <a href="<?php echo e(route('checkout.page')); ?>"
                       wire:navigate
                       class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        Try Again
                    </a>
                </div>
            </div>

            <?php else: ?>
            <!-- Loading State -->
            <div class="p-12 text-center">
                <div class="flex justify-center">
                    <svg class="animate-spin h-12 w-12 text-[#5C9F01]" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <p class="text-gray-600 mt-4">Verifying your payment...</p>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Footer Text -->
        <div class="text-center mt-8 text-xs text-gray-500">
            <p>Thank you for your order! We appreciate your business. ❤️</p>
        </div>
    </div>
</div>
<?php /**PATH F:\Laravel\greenjackshop\resources\views/livewire/user/shop-payment-success.blade.php ENDPATH**/ ?>