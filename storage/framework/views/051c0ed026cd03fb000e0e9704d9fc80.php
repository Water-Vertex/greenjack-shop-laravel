<div>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('error')): ?>
        <div class="container mx-auto px-4 max-w-7xl mt-3">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-start gap-3">
                <i class="fas fa-exclamation-triangle mt-0.5"></i>
                <div class="flex-1 text-sm"><?php echo e(session('error')); ?></div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('success')): ?>
        <div class="container mx-auto px-4 max-w-7xl mt-3">
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-start gap-3">
                <i class="fas fa-check-circle mt-0.5"></i>
                <div class="flex-1 text-sm"><?php echo e(session('success')); ?></div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php
        $cart = $cart ?? [];
        $cartCount = $cartCount ?? 0;
        $cartTotal = $cartTotal ?? 0;
        $shippingCost = $shippingCost ?? 0;
        $taxAmount = $taxAmount ?? 0;
        $grandTotal = $grandTotal ?? 0;
        $countries = $countries ?? ['US' => 'United States'];
        $currentStep = $currentStep ?? 1;
        $totalSteps = $totalSteps ?? 3;

        // ✅ Country codes for phone
        $phoneCountryCodes = [
            'US' => '+1 (USA)',
        ];
    ?>

    <div class="bg-gray-50 py-8 min-h-screen">
        <div class="container mx-auto px-4 max-w-7xl">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <nav class="flex items-center gap-2 text-sm text-gray-500">
                    <a href="<?php echo e(route('home')); ?>" wire:navigate class="hover:text-[#5B9F01] transition">Home</a>
                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    <a href="<?php echo e(route('shop')); ?>" wire:navigate class="hover:text-[#5B9F01] transition">Shop</a>
                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    <a href="<?php echo e(route('cart.page')); ?>" wire:navigate class="hover:text-[#5B9F01] transition">Cart</a>
                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    <span class="text-gray-800 font-medium">Checkout</span>
                </nav>
            </div>

            <!-- Login Section -->
            <div class="mb-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$isLoggedIn): ?>
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-user-circle text-2xl text-[#5B9F01]"></i>
                                    <h3 class="text-lg font-semibold text-gray-800">Already have an account?</h3>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Sign in to auto-fill your details and track your order</p>
                            </div>
                            <div class="flex gap-3 w-full md:w-auto">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$showLoginForm): ?>
                                    <button wire:click="toggleLoginForm" class="bg-[#5B9F01] text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-[#4a7f01] transition w-full md:w-auto">
                                        <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                                    </button>
                                    <a href="<?php echo e(route('customer.register')); ?>" wire:navigate class="border border-gray-300 text-gray-700 px-6 py-2.5 rounded-lg font-semibold hover:bg-gray-50 transition w-full md:w-auto text-center">
                                        <i class="fas fa-user-plus mr-2"></i> Register
                                    </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showLoginForm): ?>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <form wire:submit.prevent="login">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                                            <input type="email"
                                                   wire:model="loginEmail"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#5B9F01] <?php $__errorArgs = ['loginEmail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                   placeholder="Enter your email">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['loginEmail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                                            <input type="password"
                                                   wire:model="loginPassword"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#5B9F01] <?php $__errorArgs = ['loginPassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                   placeholder="Enter your password">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['loginPassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-3 mt-4">
                                        <button type="submit"
                                                wire:loading.attr="disabled"
                                                class="bg-[#5B9F01] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#4a7f01] transition flex items-center gap-2">
                                            <span wire:loading.remove>
                                                <i class="fas fa-sign-in-alt mr-1"></i> Sign In
                                            </span>
                                            <span wire:loading>
                                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Signing In...
                                            </span>
                                        </button>
                                        <button type="button"
                                                wire:click="toggleLoginForm"
                                                class="text-gray-500 hover:text-gray-700 text-sm font-medium transition">
                                            Cancel
                                        </button>
                                        <a href="<?php echo e(route('customer.register')); ?>" wire:navigate class="text-sm text-[#5B9F01] hover:underline ml-auto">
                                            Don't have an account? Register
                                        </a>
                                    </div>
                                </form>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-lg"></i>
                            <span>You are signed in as <strong><?php echo e($customer ? ($customer->firstname . ' ' . $customer->lastname) : ''); ?></strong> (<?php echo e($customer ? $customer->email : ''); ?>)</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-green-600">✓ Details auto-filled</span>
                            <a href="<?php echo e(route('customer.logout')); ?>" class="text-sm text-green-700 hover:text-green-900 transition">
                                <i class="fas fa-sign-out-alt mr-1"></i> Logout
                            </a>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Order Confirmation Screen -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($orderPlaced): ?>
                <div class="bg-white rounded-xl shadow-sm p-12 text-center max-w-2xl mx-auto">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-check-circle text-4xl text-green-600"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-3">Order Confirmed!</h1>
                    <p class="text-gray-600 mb-2">Thank you for your purchase, <?php echo e($firstName); ?>!</p>
                    <p class="text-gray-500 text-sm mb-6">Your order number is: <strong class="text-[#5B9F01]"><?php echo e($orderNumber); ?></strong></p>

                    <div class="bg-gray-50 rounded-lg p-6 mb-6 text-left">
                        <h3 class="font-semibold text-gray-800 mb-3">Order Details</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span>$<?php echo e(number_format($cartTotal, 2)); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Shipping:</span>
                                <span><?php echo e($shippingCost > 0 ? '$' . number_format($shippingCost, 2) : 'Free'); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">OH Tax:</span>
                                <span>$<?php echo e(number_format($taxAmount, 2)); ?></span>
                            </div>
                            <div class="border-t pt-2 mt-2">
                                <div class="flex justify-between font-bold text-gray-800">
                                    <span>Total:</span>
                                    <span class="text-[#5B9F01]">$<?php echo e(number_format($grandTotal, 2)); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 justify-center">
                        <button wire:click="continueShopping" class="bg-[#5B9F01] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#4a7f01] transition">
                            Continue Shopping
                        </button>
                    </div>

                    <div class="mt-6 text-center text-xs text-gray-400">
                        <p>A confirmation email has been sent to <strong><?php echo e($email); ?></strong></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Checkout Form -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                            <!-- Step Progress Bar -->
                            <div class="border-b border-gray-200 p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center <?php echo e($currentStep >= 1 ? 'bg-[#5B9F01] text-white' : 'bg-gray-200 text-gray-600'); ?>">1</div>
                                                <div class="ml-2 hidden md:block">
                                                    <p class="text-sm font-medium">Shipping</p>
                                                    <p class="text-xs text-gray-500">Address info</p>
                                                </div>
                                            </div>
                                            <div class="flex-1 h-0.5 mx-4 <?php echo e($currentStep >= 2 ? 'bg-[#5B9F01]' : 'bg-gray-200'); ?>"></div>
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center <?php echo e($currentStep >= 2 ? 'bg-[#5B9F01] text-white' : 'bg-gray-200 text-gray-600'); ?>">2</div>
                                                <div class="ml-2 hidden md:block">
                                                    <p class="text-sm font-medium">Payment</p>
                                                    <p class="text-xs text-gray-500">Select method</p>
                                                </div>
                                            </div>
                                            <div class="flex-1 h-0.5 mx-4 <?php echo e($currentStep >= 3 ? 'bg-[#5B9F01]' : 'bg-gray-200'); ?>"></div>
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center <?php echo e($currentStep >= 3 ? 'bg-[#5B9F01] text-white' : 'bg-gray-200 text-gray-600'); ?>">3</div>
                                                <div class="ml-2 hidden md:block">
                                                    <p class="text-sm font-medium">Confirm</p>
                                                    <p class="text-xs text-gray-500">Review order</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                <!-- Step 1: Shipping Information -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep == 1): ?>
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-800 mb-4">Shipping Information</h2>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                                                <input type="text" wire:model="firstName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#5B9F01] <?php $__errorArgs = ['firstName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['firstName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                                                <input type="text" wire:model="lastName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#5B9F01] <?php $__errorArgs = ['lastName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['lastName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                                                <input type="email" wire:model="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#5B9F01] <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>

                                                
                                                <div class="flex gap-2">
                                                    <select wire:model="phoneCountryCode"
                                                            class="w-32 px-2 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#5B9F01] text-sm bg-white">
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $phoneCountryCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($code); ?>"><?php echo e($label); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </select>
                                                    <input type="tel"
                                                           wire:model="phone"
                                                           placeholder="5551234567"
                                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#5B9F01] <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <i class="fas fa-info-circle mr-1"></i>
                                                    Enter digits only (e.g., <strong><?php echo e($phoneCountryCode === 'PK' ? '3001234567' : ($phoneCountryCode === 'US' || $phoneCountryCode === 'CA' ? '5551234567' : '123456789')); ?></strong>). Country code auto-added.
                                                </p>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Street Address *</label>
                                            <input type="text" wire:model="address" placeholder="House number and street name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#5B9F01] <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Apartment, suite, etc. (optional)</label>
                                            <input type="text" wire:model="apartment" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#5B9F01]">
                                        </div>

                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                                                <input type="text" wire:model="city" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#5B9F01] <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">State *</label>
                                                <input type="text" wire:model="state" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#5B9F01] <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">ZIP Code *</label>
                                                <input type="text" wire:model="zipCode" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#5B9F01] <?php $__errorArgs = ['zipCode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['zipCode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Country *</label>
                                                <select wire:model="country" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#5B9F01]">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($code); ?>"><?php echo e($name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </select>
                                            </div>
                                        </div>

                                        

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($differentShippingAddress): ?>
                                            <div class="border border-gray-200 rounded-lg p-4 mb-4 bg-gray-50">
                                                <h3 class="font-semibold text-gray-800 mb-3">Shipping Address</h3>
                                                <div class="grid grid-cols-1 gap-3">
                                                    <input type="text" wire:model="shippingAddress" placeholder="Street Address" class="px-3 py-2 border border-gray-300 rounded-lg">
                                                    <input type="text" wire:model="shippingApartment" placeholder="Apartment (optional)" class="px-3 py-2 border border-gray-300 rounded-lg">
                                                    <div class="grid grid-cols-2 gap-3">
                                                        <input type="text" wire:model="shippingCity" placeholder="City" class="px-3 py-2 border border-gray-300 rounded-lg">
                                                        <input type="text" wire:model="shippingState" placeholder="State" class="px-3 py-2 border border-gray-300 rounded-lg">
                                                        <input type="text" wire:model="shippingZipCode" placeholder="ZIP Code" class="px-3 py-2 border border-gray-300 rounded-lg">
                                                        <select wire:model="shippingCountry" class="px-3 py-2 border border-gray-300 rounded-lg">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($code); ?>"><?php echo e($name); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- Step 2: Payment Method -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep == 2): ?>
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-800 mb-6">Payment Method</h2>

                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">

                                            
                                            

                                            
                                            <div class="relative cursor-pointer flex" wire:click="$set('paymentMethod', 'paypal')">
                                                <div class="flex-1 border-2 rounded-xl p-4 transition-all duration-200 flex flex-col
                                                            <?php echo e($paymentMethod == 'paypal' ? 'border-[#5B9F01] bg-[#5B9F01]/5 shadow-md' : 'border-gray-200 hover:border-gray-400'); ?>">
                                                    <div class="absolute top-3 right-3">
                                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                                                    <?php echo e($paymentMethod == 'paypal' ? 'border-[#5B9F01] bg-[#5B9F01]' : 'border-gray-300'); ?>">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paymentMethod == 'paypal'): ?>
                                                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                </svg>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <input type="radio" wire:model="paymentMethod" value="paypal" class="hidden">
                                                    <div class="flex justify-center mb-3">
                                                        <div class="w-16 h-16 rounded-full bg-[#003087]/10 flex items-center justify-center">
                                                            <i class="fab fa-paypal text-4xl text-[#003087]"></i>
                                                        </div>
                                                    </div>
                                                    <h3 class="text-center font-semibold text-gray-800 mb-1">PayPal</h3>
                                                    <p class="text-center text-xs text-gray-500 flex-1">Pay with your PayPal account</p>
                                                    <div class="mt-3 text-center">
                                                        <span class="text-xs text-gray-400">Powered by</span>
                                                        <span class="text-sm font-bold text-[#003087] ml-1">PayPal</span>
                                                    </div>
                                                </div>
                                            </div>

                                            
                                            <div class="relative cursor-pointer flex" wire:click="$set('paymentMethod', 'square')">
                                                <div class="flex-1 border-2 rounded-xl p-4 transition-all duration-200 flex flex-col
                                                            <?php echo e($paymentMethod == 'square' ? 'border-[#5B9F01] bg-[#5B9F01]/5 shadow-md' : 'border-gray-200 hover:border-gray-400'); ?>">
                                                    <div class="absolute top-3 right-3">
                                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                                                    <?php echo e($paymentMethod == 'square' ? 'border-[#5B9F01] bg-[#5B9F01]' : 'border-gray-300'); ?>">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paymentMethod == 'square'): ?>
                                                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                </svg>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <input type="radio" wire:model="paymentMethod" value="square" class="hidden">
                                                    <div class="flex justify-center mb-3">
                                                        <div class="flex gap-2">
                                                            <i class="fab fa-cc-visa text-3xl text-blue-600"></i>
                                                            <i class="fab fa-cc-mastercard text-3xl text-orange-500"></i>
                                                            <i class="fab fa-cc-amex text-3xl text-blue-400"></i>
                                                            <i class="fab fa-cc-discover text-3xl text-gray-600"></i>
                                                        </div>
                                                    </div>
                                                    <h3 class="text-center font-semibold text-gray-800 mb-1">Credit / Debit Card</h3>
                                                    <p class="text-center text-xs text-gray-500 flex-1">Secure payment powered by Square</p>
                                                    <div class="mt-3 text-center">
                                                        <span class="text-xs text-gray-400">Powered by</span>
                                                        <span class="text-sm font-bold text-black ml-1">Square</span>
                                                    </div>
                                                </div>
                                            </div>

                                            
                                            <div class="relative cursor-pointer flex" wire:click="$set('paymentMethod', 'cod')">
                                                <div class="flex-1 border-2 rounded-xl p-4 transition-all duration-200 flex flex-col
                                                            <?php echo e($paymentMethod == 'cod' ? 'border-[#5B9F01] bg-[#5B9F01]/5 shadow-md' : 'border-gray-200 hover:border-gray-400'); ?>">
                                                    <div class="absolute top-3 right-3">
                                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                                                    <?php echo e($paymentMethod == 'cod' ? 'border-[#5B9F01] bg-[#5B9F01]' : 'border-gray-300'); ?>">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paymentMethod == 'cod'): ?>
                                                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                </svg>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <input type="radio" wire:model="paymentMethod" value="cod" class="hidden">
                                                    <div class="flex justify-center mb-3">
                                                        <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
                                                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <h3 class="text-center font-semibold text-gray-800 mb-1">Pay with Finance</h3>
                                                    <p class="text-center text-xs text-gray-500 flex-1">Pay at your convenience, after delivery</p>
                                                    <div class="mt-3 text-center">
                                                        <span class="text-xs text-gray-400">Settle after delivery</span>
                                                        <span class="text-sm font-bold text-green-600 ml-1">after delivery</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['paymentMethod'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                                                <i class="fas fa-exclamation-circle mr-2"></i>
                                                <?php echo e($message); ?>

                                            </div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paymentMethod == 'cod'): ?>
                                            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                                                <div class="flex items-start gap-3">
                                                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <div class="flex-1">
                                                        <h4 class="font-medium text-yellow-800">Cash on Delivery Information</h4>
                                                        <p class="text-sm text-yellow-700 mt-1">You will pay the full amount in cash when your order is delivered to your doorstep.</p>
                                                        <div class="mt-3">
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Instructions (optional)</label>
                                                            <textarea wire:model="codNotes"
                                                                      rows="2"
                                                                      placeholder="Any special instructions for delivery?"
                                                                      class="w-full px-3 py-2 border border-yellow-300 rounded-lg focus:outline-none focus:border-[#5B9F01] bg-white"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        <div class="mt-6">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Order Notes (optional)</label>
                                            <textarea wire:model="orderNotes"
                                                      rows="3"
                                                      placeholder="Special instructions for your order..."
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#5B9F01] transition"></textarea>
                                        </div>

                                        <div class="mt-4 flex items-center gap-2 text-sm text-gray-500">
                                            <svg class="w-4 h-4 text-[#5B9F01]" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Selected: <strong class="text-gray-700">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paymentMethod == 'stripe'): ?> Credit/Debit Card (Stripe)
                                                <?php elseif($paymentMethod == 'paypal'): ?> PayPal
                                                <?php elseif($paymentMethod == 'square'): ?> Credit/Debit Card (Square)
                                                <?php else: ?> Cash on Delivery
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </strong></span>
                                        </div>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- Step 3: Confirm Order -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep == 3): ?>
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-800 mb-4">Review Your Order</h2>

                                        <div class="space-y-6">
                                            <div class="border-b pb-4">
                                                <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                                    <i class="fas fa-user text-[#5B9F01] text-sm"></i>
                                                    Customer Information
                                                </h3>
                                                <p class="text-sm text-gray-600"><?php echo e($firstName); ?> <?php echo e($lastName); ?></p>
                                                <p class="text-sm text-gray-600"><?php echo e($email); ?> | <?php echo e($phoneCountryCode ?? ''); ?><?php echo e($phone); ?></p>
                                            </div>

                                            <div class="border-b pb-4">
                                                <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                                    <i class="fas fa-map-marker-alt text-[#5B9F01] text-sm"></i>
                                                    Shipping Address
                                                </h3>
                                                <p class="text-sm text-gray-600"><?php echo e($address); ?>, <?php echo e($apartment ? $apartment . ', ' : ''); ?><?php echo e($city); ?>, <?php echo e($state); ?> <?php echo e($zipCode); ?>, <?php echo e($countries[$country] ?? ''); ?></p>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($differentShippingAddress): ?>
                                                    <div class="mt-2 pt-2 border-t">
                                                        <p class="text-xs text-gray-500 font-medium mb-1">Different Shipping Address:</p>
                                                        <p class="text-sm text-gray-600"><?php echo e($shippingAddress); ?>, <?php echo e($shippingCity); ?>, <?php echo e($shippingState); ?> <?php echo e($shippingZipCode); ?></p>
                                                    </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>

                                            <div class="border-b pb-4">
                                                <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                                    <i class="fas fa-credit-card text-[#5B9F01] text-sm"></i>
                                                    Payment Method
                                                </h3>
                                                <p class="text-sm text-gray-600">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paymentMethod == 'stripe'): ?>
                                                        <i class="fab fa-cc-stripe"></i> Credit/Debit Card (Stripe)
                                                    <?php elseif($paymentMethod == 'paypal'): ?>
                                                        <i class="fab fa-paypal"></i> PayPal
                                                    <?php elseif($paymentMethod == 'square'): ?>
                                                        <i class="fas fa-credit-card"></i> Credit/Debit Card (Square)
                                                    <?php else: ?>
                                                        <i class="fas fa-money-bill-wave"></i> Cash on Delivery
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </p>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($orderNotes): ?>
                                                    <p class="text-sm text-gray-600 mt-2"><strong>Order Notes:</strong> <?php echo e($orderNotes); ?></p>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Order Summary</h3>

                            <div class="space-y-3 max-h-80 overflow-y-auto mb-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($cart)): ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $itemName = $item['name'] ?? 'Unknown';
                                            $itemImage = $item['image'] ?? null;
                                            $itemPrice = (float) ($item['price'] ?? 0);
                                            $itemQty = (int) ($item['quantity'] ?? 0);
                                            $itemTotal = $itemPrice * $itemQty;
                                        ?>
                                        <div class="flex gap-3">
                                            <img src="<?php echo e($itemImage ? asset('storage/' . $itemImage) : 'https://placehold.co/60x60/e2e8f0/475569?text=No+Image'); ?>"
                                                 onerror="this.src='https://placehold.co/60x60/e2e8f0/475569?text=No+Image'"
                                                 alt="<?php echo e($itemName); ?>"
                                                 class="w-16 h-16 rounded object-cover">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium line-clamp-2"><?php echo e($itemName); ?></p>
                                                <p class="text-xs text-gray-500">Qty: <?php echo e($itemQty); ?></p>
                                                <p class="text-sm font-semibold text-[#5B9F01]">$<?php echo e(number_format($itemTotal, 2)); ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div class="border-t pt-4 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal (<?php echo e($cartCount); ?> items)</span>
                                    <span class="font-medium">$<?php echo e(number_format($cartTotal, 2)); ?></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="font-medium"><?php echo e($shippingCost > 0 ? '$' . number_format($shippingCost, 2) : 'Free'); ?></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">OH Tax</span>
                                    <span class="font-medium">$<?php echo e(number_format($taxAmount, 2)); ?></span>
                                </div>
                                <div class="border-t pt-2 mt-2">
                                    <div class="flex justify-between text-lg font-bold">
                                        <span>Total</span>
                                        <span class="text-[#5B9F01]">$<?php echo e(number_format($grandTotal, 2)); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex flex-col gap-3">
                                <div class="w-full">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep < 3): ?>
                                        <button wire:click="nextStep" class="w-full bg-[#5B9F01] text-white py-3 rounded-lg font-semibold hover:bg-[#4a7f01] transition">
                                            Continue
                                        </button>
                                    <?php else: ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paymentMethod == 'stripe'): ?>
                                            <button wire:click="processStripePayment" wire:loading.attr="disabled" class="w-full bg-[#5B9F01] text-white py-3 rounded-lg font-semibold hover:bg-[#4a7f01] transition flex items-center justify-center gap-2">
                                                <span wire:loading.remove>Pay with Stripe - $<?php echo e(number_format($grandTotal, 2)); ?></span>
                                                <span wire:loading class="flex items-center gap-2">
                                                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Redirecting to Stripe...
                                                </span>
                                            </button>
                                        <?php elseif($paymentMethod == 'paypal'): ?>
                                            <button wire:click="processPayPalPayment" wire:loading.attr="disabled" class="w-full bg-[#0070BA] text-white py-3 rounded-lg font-semibold hover:bg-[#003087] transition flex items-center justify-center gap-2">
                                                <span wire:loading.remove><i class="fab fa-paypal mr-2"></i>Pay with PayPal - $<?php echo e(number_format($grandTotal, 2)); ?></span>
                                                <span wire:loading class="flex items-center gap-2">
                                                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Redirecting to PayPal...
                                                </span>
                                            </button>
                                        <?php elseif($paymentMethod == 'square'): ?>
                                            <button wire:click="processSquarePayment" wire:loading.attr="disabled" class="w-full bg-[#5B9F01] text-white py-3 rounded-lg font-semibold hover:bg-[#4a7f01] transition flex items-center justify-center gap-2">
                                                <span wire:loading.remove>Pay with Square - $<?php echo e(number_format($grandTotal, 2)); ?></span>
                                                <span wire:loading class="flex items-center gap-2">
                                                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Redirecting to Square...
                                                </span>
                                            </button>
                                        <?php else: ?>
                                            <button wire:click="placeOrder" wire:loading.attr="disabled" class="w-full bg-[#5B9F01] text-white py-3 rounded-lg font-semibold hover:bg-[#4a7f01] transition flex items-center justify-center gap-2">
                                                <span wire:loading.remove>Place Order (COD)</span>
                                                <span wire:loading class="flex items-center gap-2">
                                                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Processing...
                                                </span>
                                            </button>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep > 1): ?>
                                    <button wire:click="previousStep" class="w-full border border-gray-300 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-50 transition">
                                        ← Back
                                    </button>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div class="mt-4 text-center">
                                <div class="flex items-center justify-center gap-2 text-xs text-gray-500">
                                    <i class="fas fa-lock text-[#5B9F01]"></i>
                                    <span>Secure checkout powered by Stripe, PayPal & Square</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</div>
<?php /**PATH F:\Laravel\greenjackshop\resources\views/livewire/user/checkout.blade.php ENDPATH**/ ?>