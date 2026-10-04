<div>
    <div class="container mx-auto px-4 max-w-7xl py-8">
        <!-- Success Message -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('success')): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-check-circle mr-2"></i>
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Error Message -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('error')): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden sticky top-8">
                    <!-- Profile Header -->
                    <div class="bg-[#5B9F01] p-6 text-center">
                        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 border-2 border-white/30">
                            <i class="fas fa-user text-white text-3xl"></i>
                        </div>
                        <h3 class="text-white font-semibold text-lg"><?php echo e($customer->firstname ?? 'Customer'); ?> <?php echo e($customer->lastname ?? ''); ?></h3>
                        <p class="text-white/80 text-sm"><?php echo e($customer->email ?? ''); ?></p>
                    </div>

                    <!-- Navigation -->
                    <nav class="p-4">
                        <button wire:click="$set('activeTab', 'dashboard')"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left
                                <?php echo e($activeTab == 'dashboard' ? 'bg-[#5B9F01]/10 text-[#5B9F01]' : 'hover:bg-gray-50 text-gray-700'); ?>">
                            <i class="fas fa-chart-pie"></i>
                            <span>Dashboard</span>
                        </button>

                        <button wire:click="$set('activeTab', 'profile')"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left mt-1
                                <?php echo e($activeTab == 'profile' ? 'bg-[#5B9F01]/10 text-[#5B9F01]' : 'hover:bg-gray-50 text-gray-700'); ?>">
                            <i class="fas fa-user-edit"></i>
                            <span>Profile Settings</span>
                        </button>

                        <button wire:click="$set('activeTab', 'orders')"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left mt-1
                                <?php echo e($activeTab == 'orders' ? 'bg-[#5B9F01]/10 text-[#5B9F01]' : 'hover:bg-gray-50 text-gray-700'); ?>">
                            <i class="fas fa-shopping-bag"></i>
                            <span>Order History</span>
                            <span class="ml-auto bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full"><?php echo e($orderCount); ?></span>
                        </button>

                        <button wire:click="$set('activeTab', 'wishlist')"
        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left mt-1
        <?php echo e($activeTab == 'wishlist' ? 'bg-[#5B9F01]/10 text-[#5B9F01]' : 'hover:bg-gray-50 text-gray-700'); ?>">
    <i class="fas fa-heart"></i>
    <span>Wishlist</span>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($wishlistCount > 0): ?>
        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full"><?php echo e($wishlistCount); ?></span>
    <?php else: ?>
        <span class="ml-auto bg-gray-200 text-gray-600 text-xs px-2 py-0.5 rounded-full">0</span>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</button>

                        <button wire:click="$set('activeTab', 'settings')"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left mt-1
                                <?php echo e($activeTab == 'settings' ? 'bg-[#5B9F01]/10 text-[#5B9F01]' : 'hover:bg-gray-50 text-gray-700'); ?>">
                            <i class="fas fa-cog"></i>
                            <span>Account Settings</span>
                        </button>

                        <div class="border-t border-gray-200 mt-4 pt-4">
                            <button wire:click="logout"
                                    class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition text-left">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-9">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8">

                    <!-- Dashboard Tab -->
                    <!-- Dashboard Tab -->
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab == 'dashboard'): ?>
    <div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Dashboard</h2>
        <p class="text-gray-500 mb-6">Welcome back, <?php echo e($customer->firstname ?? 'Customer'); ?>!</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-shopping-bag text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Orders</p>
                        <p class="text-2xl font-bold text-gray-800"><?php echo e($orderCount); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Spent</p>
                        <p class="text-2xl font-bold text-gray-800">$<?php echo e(number_format($totalSpent, 2)); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Member Since</p>
                        <p class="text-sm font-semibold text-gray-800"><?php echo e($customer->created_at ? $customer->created_at->format('M d, Y') : 'N/A'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentOrders && $recentOrders->count() > 0): ?>
        <div class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Recent Orders</h3>
                <button wire:click="$set('activeTab', 'orders')" class="text-sm text-[#5B9F01] hover:underline">
                    View All →
                </button>
            </div>
            <div class="space-y-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-100">
                    <div>
                        <p class="text-sm font-medium text-gray-800">#<?php echo e($order->order_number); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($order->created_at->format('M d, Y')); ?></p>
                    </div>
                    <div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo e($order->status_badge_color); ?>">
                            <?php echo e(ucfirst($order->status)); ?>

                        </span>
                        <span class="ml-3 text-sm font-semibold text-gray-800">$<?php echo e(number_format($order->grand_total, 2)); ?></span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Quick Actions -->
        <div class="mt-8 p-6 bg-gray-50 rounded-xl border border-gray-200">
            <h3 class="font-semibold text-gray-800 mb-2">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <button wire:click="$set('activeTab', 'profile')"
                        class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition text-center border border-gray-200">
                    <i class="fas fa-user-edit text-[#5B9F01] text-xl"></i>
                    <p class="text-xs text-gray-600 mt-1">Edit Profile</p>
                </button>
                <button wire:click="$set('activeTab', 'orders')"
                        class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition text-center border border-gray-200">
                    <i class="fas fa-shopping-bag text-[#5B9F01] text-xl"></i>
                    <p class="text-xs text-gray-600 mt-1">View Orders</p>
                </button>
                <button class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition text-center border border-gray-200">
                    <i class="fas fa-heart text-[#5B9F01] text-xl"></i>
                    <p class="text-xs text-gray-600 mt-1">Wishlist</p>
                </button>
                <button wire:click="$set('activeTab', 'settings')"
                        class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition text-center border border-gray-200">
                    <i class="fas fa-cog text-[#5B9F01] text-xl"></i>
                    <p class="text-xs text-gray-600 mt-1">Settings</p>
                </button>
            </div>
        </div>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Profile Tab -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab == 'profile'): ?>
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-800">Profile Settings</h2>
                                    <p class="text-gray-500 text-sm">Update your personal information</p>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$editMode): ?>
                                    <button wire:click="toggleEdit"
                                            class="bg-[#5B9F01] text-white px-4 py-2 rounded-lg hover:bg-[#4a7f01] transition">
                                        <i class="fas fa-edit mr-2"></i> Edit Profile
                                    </button>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <form wire:submit.prevent="updateProfile">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            First Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               wire:model="firstname"
                                               <?php echo e(!$editMode ? 'disabled' : ''); ?>

                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent <?php $__errorArgs = ['firstname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                               <?php echo e(!$editMode ? 'bg-gray-100 cursor-not-allowed' : ''); ?>">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['firstname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Last Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               wire:model="lastname"
                                               <?php echo e(!$editMode ? 'disabled' : ''); ?>

                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent <?php $__errorArgs = ['lastname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                               <?php echo e(!$editMode ? 'bg-gray-100 cursor-not-allowed' : ''); ?>">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['lastname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email"
                                           wire:model="email"
                                           <?php echo e(!$editMode ? 'disabled' : ''); ?>

                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                           <?php echo e(!$editMode ? 'bg-gray-100 cursor-not-allowed' : ''); ?>">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Phone Number <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel"
                                           wire:model="phone"
                                           <?php echo e(!$editMode ? 'disabled' : ''); ?>

                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                           <?php echo e(!$editMode ? 'bg-gray-100 cursor-not-allowed' : ''); ?>">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           wire:model="address"
                                           <?php echo e(!$editMode ? 'disabled' : ''); ?>

                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                           <?php echo e(!$editMode ? 'bg-gray-100 cursor-not-allowed' : ''); ?>">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Country <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               wire:model="country"
                                               <?php echo e(!$editMode ? 'disabled' : ''); ?>

                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent <?php $__errorArgs = ['country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                               <?php echo e(!$editMode ? 'bg-gray-100 cursor-not-allowed' : ''); ?>">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            State <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               wire:model="state"
                                               <?php echo e(!$editMode ? 'disabled' : ''); ?>

                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                               <?php echo e(!$editMode ? 'bg-gray-100 cursor-not-allowed' : ''); ?>">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            City <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               wire:model="city"
                                               <?php echo e(!$editMode ? 'disabled' : ''); ?>

                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                               <?php echo e(!$editMode ? 'bg-gray-100 cursor-not-allowed' : ''); ?>">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Postal Code <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               wire:model="postalcode"
                                               <?php echo e(!$editMode ? 'disabled' : ''); ?>

                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent <?php $__errorArgs = ['postalcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                               <?php echo e(!$editMode ? 'bg-gray-100 cursor-not-allowed' : ''); ?>">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['postalcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editMode): ?>
                                    <div class="flex gap-3 mt-6">
                                        <button type="submit"
                                                wire:loading.attr="disabled"
                                                class="bg-[#5B9F01] text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-[#4a7f01] transition">
                                            <span wire:loading.remove>
                                                <i class="fas fa-save mr-2"></i> Save Changes
                                            </span>
                                            <span wire:loading>
                                                <svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Saving...
                                            </span>
                                        </button>
                                        <button type="button"
                                                wire:click="toggleEdit"
                                                class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-semibold hover:bg-gray-300 transition">
                                            Cancel
                                        </button>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </form>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Orders Tab -->
                    <!-- Orders Tab -->
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab == 'orders'): ?>
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Order History</h2>
                <p class="text-gray-500 text-sm">View all your past orders</p>
            </div>
            <span class="bg-[#5B9F01]/10 text-[#5B9F01] px-3 py-1 rounded-full text-sm font-medium">
                <?php echo e($orderCount); ?> Orders
            </span>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($orders && $orders->count() > 0): ?>
            <div class="space-y-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition">
                    <!-- Order Header -->
                    <div class="bg-gray-50 px-6 py-3 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-semibold text-gray-800">Order #<?php echo e($order->order_number); ?></span>
                            <span class="text-xs text-gray-500"><?php echo e($order->created_at->format('M d, Y')); ?></span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo e($order->status_badge_color); ?>">
                                <?php echo e(ucfirst($order->status)); ?>

                            </span>
                            <span class="text-sm font-semibold text-gray-800">$<?php echo e(number_format($order->grand_total, 2)); ?></span>
                        </div>
                    </div>

                    <!-- Order Body -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Payment Method</p>
                                <p class="text-sm font-medium text-gray-700 capitalize"><?php echo e($order->payment_method); ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Payment Status</p>
                                <p class="text-sm font-medium text-gray-700 capitalize"><?php echo e($order->payment_status); ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Items</p>
                                <p class="text-sm font-medium text-gray-700"><?php echo e($order->items->count()); ?> items</p>
                            </div>
                        </div>

                        <!-- Order Items Preview -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-xs text-gray-500 mb-2">Order Items</p>
                            <div class="flex flex-wrap gap-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $order->items->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="text-xs bg-gray-100 px-2 py-1 rounded"><?php echo e($item->product_name); ?> x<?php echo e($item->quantity); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->items->count() > 3): ?>
                                    <span class="text-xs text-gray-500">+<?php echo e($order->items->count() - 3); ?> more</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <!-- Order Actions -->
                        <div class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap gap-3">
    <a href="<?php echo e(route('order.details', $order->id)); ?>"
       wire:navigate
       class="text-sm text-[#5B9F01] hover:underline font-medium">
        <i class="fas fa-eye mr-1"></i> View Details
    </a>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->status == 'delivered'): ?>
    <button class="text-sm text-[#5B9F01] hover:underline font-medium">
        <i class="fas fa-redo mr-1"></i> Reorder
    </button>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <i class="fas fa-shopping-bag text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700">No Orders Yet</h3>
                <p class="text-gray-500 mt-2">Start shopping to see your orders here</p>
                <a href="<?php echo e(route('shop')); ?>" wire:navigate class="inline-block mt-4 bg-[#5B9F01] text-white px-6 py-2 rounded-lg hover:bg-[#4a7f01] transition">
                    Start Shopping
                </a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Wishlist Tab -->
                   <!-- Wishlist Tab -->
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab == 'wishlist'): ?>
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">My Wishlist</h2>
                <p class="text-gray-500 text-sm">Your favorite products</p>
            </div>
            <span class="bg-[#5B9F01]/10 text-[#5B9F01] px-3 py-1 rounded-full text-sm font-medium">
                <?php echo e($wishlistCount); ?> Items
            </span>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($wishlistCount > 0): ?>
            <?php
                $wishlistItems = \App\Models\Wishlist::with('product')
                    ->where('user_id', $customer->id)
                    ->whereHas('product', function($q) {
                        $q->where('is_active', true);
                    })
                    ->get();
            ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $wishlistItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $product = $item->product;
                    ?>
                    <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition group bg-white">
                        <a href="<?php echo e(route('shop.details', $product->slug)); ?>" wire:navigate>
                            <div class="relative overflow-hidden bg-gray-100 h-48">
                                <img src="<?php echo e(asset('storage/' . $product->image)); ?>"
                                     onerror="this.src='https://placehold.co/300x300/e2e8f0/475569?text=No+Image'"
                                     alt="<?php echo e($product->name); ?>"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->discounted_price && $product->discounted_price < $product->price): ?>
                                    <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                        -<?php echo e(round((($product->price - $product->discounted_price) / $product->price) * 100)); ?>%
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <button wire:click="$emit('removeFromWishlist', <?php echo e($product->id); ?>)"
                                        wire:loading.attr="disabled"
                                        class="absolute top-2 right-2 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-md hover:bg-red-50 transition">
                                    <i class="fas fa-times text-gray-400 hover:text-red-500 text-sm"></i>
                                </button>
                            </div>
                        </a>
                        <div class="p-4">
                            <a href="<?php echo e(route('shop.details', $product->slug)); ?>" wire:navigate>
                                <h3 class="text-sm font-semibold text-gray-800 hover:text-[#5B9F01] transition line-clamp-2 min-h-[40px]">
                                    <?php echo e($product->name); ?>

                                </h3>
                            </a>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-lg font-bold text-[#5B9F01]">
                                    $<?php echo e(number_format($product->discounted_price > 0 ? $product->discounted_price : $product->price, 2)); ?>

                                </span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->discounted_price && $product->discounted_price < $product->price): ?>
                                    <span class="text-sm text-gray-400 line-through">$<?php echo e(number_format($product->price, 2)); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->stock > 0): ?>
                                    <span class="text-xs text-green-600 flex items-center gap-1">
                                        <i class="fas fa-check-circle"></i> In Stock
                                    </span>
                                    <button wire:click="$emit('addToCart', <?php echo e($product->id); ?>)"
                                            wire:loading.attr="disabled"
                                            class="text-sm bg-[#FDCC02] text-gray-900 px-4 py-1.5 rounded-lg font-medium hover:bg-[#f5c400] transition">
                                        <i class="fas fa-shopping-cart mr-1"></i> Add to Cart
                                    </button>
                                <?php else: ?>
                                    <span class="text-xs text-red-500 flex items-center gap-1">
                                        <i class="fas fa-times-circle"></i> Out of Stock
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <i class="fas fa-heart text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700">Wishlist is Empty</h3>
                <p class="text-gray-500 mt-2">Save your favorite items here</p>
                <a href="<?php echo e(route('shop')); ?>" wire:navigate class="inline-block mt-4 bg-[#5B9F01] text-white px-6 py-2.5 rounded-lg hover:bg-[#4a7f01] transition">
                    <i class="fas fa-shopping-bag mr-2"></i> Start Shopping
                </a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Settings Tab -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab == 'settings'): ?>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Account Settings</h2>
                            <p class="text-gray-500 mb-6">Manage your account security</p>

                            <!-- Change Password -->
                            <div class="border border-gray-200 rounded-xl p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="font-semibold text-gray-800">Change Password</h3>
                                        <p class="text-sm text-gray-500">Update your password to keep your account secure</p>
                                    </div>
                                    <button wire:click="togglePasswordForm"
                                            class="text-[#5B9F01] hover:underline text-sm font-medium">
                                        <?php echo e($showPasswordForm ? 'Cancel' : 'Change Password'); ?>

                                    </button>
                                </div>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showPasswordForm): ?>
                                    <form wire:submit.prevent="updatePassword" class="space-y-4 mt-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Current Password <span class="text-red-500">*</span>
                                            </label>
                                            <input type="password"
                                                   wire:model="current_password"
                                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                   placeholder="Enter current password">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                New Password <span class="text-red-500">*</span>
                                            </label>
                                            <input type="password"
                                                   wire:model="new_password"
                                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                   placeholder="Enter new password (min 8 characters)">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Confirm New Password <span class="text-red-500">*</span>
                                            </label>
                                            <input type="password"
                                                   wire:model="new_password_confirmation"
                                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent <?php $__errorArgs = ['new_password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                   placeholder="Confirm new password">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['new_password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>

                                        <button type="submit"
                                                wire:loading.attr="disabled"
                                                class="bg-[#5B9F01] text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-[#4a7f01] transition">
                                            <span wire:loading.remove>
                                                <i class="fas fa-key mr-2"></i> Update Password
                                            </span>
                                            <span wire:loading>
                                                <svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Updating...
                                            </span>
                                        </button>
                                    </form>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <!-- Account Actions -->
                            <div class="mt-6 border border-red-200 rounded-xl p-6 bg-red-50">
                                <h3 class="font-semibold text-red-800 mb-2">Danger Zone</h3>
                                <p class="text-sm text-red-600 mb-4">Permanently delete your account and all associated data</p>
                                <button class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition">
                                    <i class="fas fa-trash mr-2"></i> Delete Account
                                </button>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>


</div>
<?php /**PATH F:\Laravel\greenjackshop\resources\views/livewire/user/my-account.blade.php ENDPATH**/ ?>