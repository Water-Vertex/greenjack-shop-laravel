<!-- Wishlist Component -->
<div class="relative wishlist-wrapper" x-data="{ open: false }">
    <button @click="open = !open" class="flex flex-col items-center text-gray-600 hover:text-[#5B9F01] transition relative">
        <div class="relative">
            <i class="far fa-heart text-xl md:text-2xl"></i>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($wishlistCount > 0): ?>
                <span class="absolute -top-2 -right-2.5 bg-red-500 text-white text-[10px] font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 shadow-md">
                    <?php echo e($wishlistCount); ?>

                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <span class="text-[10px] mt-0.5 hidden md:block">Wishlist</span>
    </button>

    <!-- Backdrop -->
    <div x-show="open"
         x-cloak
         class="fixed inset-0 bg-black/50 z-[999]"
         @click="open = false"
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
    </div>

    <!-- Wishlist Dropdown -->
    <div x-show="open"
         @click.away="open = false"
         x-cloak
         class="fixed sm:absolute inset-x-0 bottom-0 sm:inset-auto sm:top-full sm:right-0 sm:left-auto sm:bottom-auto mt-0 sm:mt-3 mx-0 w-full sm:w-96 bg-white rounded-t-2xl sm:rounded-xl shadow-2xl border border-gray-100 z-[1000]
                h-[85vh] sm:h-auto
                max-h-[85vh] sm:max-h-[calc(100vh-120px)]
                flex flex-col overflow-hidden"
         style="display: none;"
         @keydown.escape.window="open = false">

        <!-- Header (fixed) -->
        <div class="p-4 sm:p-5 pb-3 border-b border-gray-200 flex-shrink-0">
            <div class="w-12 h-1 bg-gray-300 rounded-full mx-auto mb-3 sm:hidden"></div>

            <div class="flex items-center justify-between">
                <h4 class="font-semibold text-gray-800 text-base">My Wishlist</h4>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500">(<?php echo e($wishlistCount); ?>)</span>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($wishlistCount > 0): ?>
            <!-- Scrollable Items (middle) -->
            <div class="flex-1 overflow-y-auto px-4 sm:px-5 py-3 space-y-3 min-h-0">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $wishlistItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $product = $item['product'] ?? null;
                        if (!$product) continue;

                        $productId = $product['id'] ?? null;
                        $productName = $product['name'] ?? 'Unknown Item';
                        $productSlug = $product['slug'] ?? null;
                        $productPrice = (float) ($product['price'] ?? 0);
                        $productDiscount = (float) ($product['discounted_price'] ?? 0);
                        $displayPrice = $productDiscount > 0 ? $productDiscount : $productPrice;
                        $productStock = (int) ($product['stock'] ?? 0);

                        // ✅ Variation image logic — same as Shop/Cart
                        $productImage = $product['image'] ?? null;
                        if (!empty($product['has_variations']) && !empty($product['variations']) && is_array($product['variations'])) {
                            $firstVarImg = null;
                            foreach ($product['variations'] as $v) {
                                if (!empty($v['image'])) {
                                    $firstVarImg = $v['image'];
                                    break;
                                }
                            }
                            if ($firstVarImg) {
                                $productImage = $firstVarImg;
                            }
                        }
                    ?>
                    <div class="flex gap-3 hover:bg-gray-50 rounded-lg p-2 transition">
                        <a href="<?php echo e($productSlug ? route('shop.details', $productSlug) : '#'); ?>" wire:navigate class="flex-shrink-0">
                            <img src="<?php echo e($productImage ? asset('storage/' . $productImage) : 'https://placehold.co/60x60/e2e8f0/475569?text=No+Image'); ?>"
                                 onerror="this.src='https://placehold.co/60x60/e2e8f0/475569?text=No+Image'"
                                 alt="<?php echo e($productName); ?>"
                                 class="w-14 h-14 rounded object-cover">
                        </a>
                        <div class="flex-1 min-w-0">
                            <a href="<?php echo e($productSlug ? route('shop.details', $productSlug) : '#'); ?>" wire:navigate>
                                <p class="text-sm font-medium line-clamp-2 hover:text-[#5B9F01] transition"><?php echo e($productName); ?></p>
                            </a>
                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                <span class="text-sm font-semibold text-[#5B9F01]">
                                    $<?php echo e(number_format($displayPrice, 2)); ?>

                                </span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($productStock > 0): ?>
                                    <span class="text-xs text-green-600">In Stock</span>
                                <?php else: ?>
                                    <span class="text-xs text-red-500">Out of Stock</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1 flex-shrink-0">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($productStock > 0 && $productId): ?>
                                <button wire:click="addToCart(<?php echo e($productId); ?>)"
                                        wire:loading.attr="disabled"
                                        wire:target="addToCart(<?php echo e($productId); ?>)"
                                        class="text-[#5B9F01] hover:text-[#4a7f01] text-xs p-1"
                                        title="Add to Cart">
                                    <span wire:loading.remove wire:target="addToCart(<?php echo e($productId); ?>)">
                                        <i class="fas fa-shopping-cart text-sm"></i>
                                    </span>
                                    <span wire:loading wire:target="addToCart(<?php echo e($productId); ?>)">
                                        <svg class="animate-spin h-3 w-3 text-[#5B9F01] inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($productId): ?>
                                <button wire:click="removeFromWishlist(<?php echo e($productId); ?>)"
                                        wire:loading.attr="disabled"
                                        wire:target="removeFromWishlist(<?php echo e($productId); ?>)"
                                        class="text-red-400 hover:text-red-600 text-xs p-1"
                                        title="Remove from wishlist">
                                    <span wire:loading.remove wire:target="removeFromWishlist(<?php echo e($productId); ?>)">
                                        <i class="fas fa-times text-sm"></i>
                                    </span>
                                    <span wire:loading wire:target="removeFromWishlist(<?php echo e($productId); ?>)">
                                        <svg class="animate-spin h-3 w-3 text-red-500 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Footer (fixed) -->
            <div class="px-4 sm:px-5 pt-3 pb-4 sm:pb-5 border-t border-gray-200 flex-shrink-0 bg-white">
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <a href="<?php echo e(route('wishlist')); ?>" wire:navigate class="w-full sm:flex-1 text-center border border-gray-300 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                        View All
                    </a>
                    <a href="<?php echo e(route('shop')); ?>" wire:navigate class="w-full sm:flex-1 text-center bg-[#5B9F01] text-white py-2.5 rounded-lg text-sm font-medium hover:bg-[#4a7f01] transition">
                        Continue Shopping
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="flex-1 flex flex-col items-center justify-center p-8 text-center">
                <i class="far fa-heart text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 text-base">Your wishlist is empty</p>
                <p class="text-xs text-gray-400 mt-1">Start adding your favorite items!</p>
                <a href="<?php echo e(route('shop')); ?>" wire:navigate class="inline-block mt-3 text-[#5B9F01] text-sm font-medium hover:underline">Browse Products</a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH F:\Laravel\greenjackshop\resources\views/livewire/user/wishlist-icon.blade.php ENDPATH**/ ?>