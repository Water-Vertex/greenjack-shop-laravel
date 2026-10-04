<?php $__env->startSection('meta'); ?>
<?php
    $metas = null;
    try {
        $metas = \App\Models\PageMeta::find(1);
    } catch (\Throwable $e) {
        \Log::warning('PageMeta load failed: ' . $e->getMessage());
    }
?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($metas): ?>
    <title><?php echo e($metas->home_title ?? config('app.name')); ?></title>
    <meta name="title" content="<?php echo e($metas->home_meta_title ?? ''); ?>"/>
    <meta name="description" content="<?php echo e($metas->home_meta_description ?? ''); ?>"/>
    <meta name="keywords" content="<?php echo e($metas->home_meta_keywords ?? ''); ?>"/>
    <meta name="tags" content="<?php echo e($metas->home_meta_tags ?? ''); ?>"/>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($metas->home_page_schema): ?>
    <script type="application/ld+json">
        <?php echo is_array($metas->home_page_schema) ? json_encode($metas->home_page_schema) : $metas->home_page_schema; ?>

    </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php else: ?>
    <title><?php echo e(config('app.name')); ?></title>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<div>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('error')): ?>
        <div class="container mx-auto px-4 max-w-7xl mt-3">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-start gap-3">
                <i class="fas fa-exclamation-triangle mt-0.5"></i>
                <div class="flex-1 text-sm"><?php echo e(session('error')); ?></div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php
        $banners = $banners ?? collect();
        $side_banners = $side_banners ?? collect();
        $brands = $brands ?? collect();
        $bestSellerProducts = $bestSellerProducts ?? collect();
        $featuredProducts = $featuredProducts ?? collect();
        $specialProducts = $specialProducts ?? collect();
        $categories = $categories ?? collect();
    ?>

   <!-- Navigation Menu + All Departments Mega Menu (Desktop) WITH HERO SECTION SIDE BY SIDE -->
   <div class="border-y border-gray-100 bg-white w-full">
      <div class="container mx-auto px-4 max-w-7xl">
         <!-- Hero Section Content -->
         <div class="mt-2 relative z-[1]" id="heroSectionContainer">
            <!-- Main Hero Row -->
            <div class="flex flex-col lg:flex-row gap-5">
                <!-- LEFT COLUMN - FIXED 4 COLUMNS (STATIC BANNER) - HIDDEN ON MOBILE -->
                <div class="hidden lg:block lg:w-1/3 xl:w-1/3 flex-shrink-0">
                    <a href="<?php echo e($main_side_banner->url ?? route('shop')); ?>">
                        <div class="rounded-xl sm:rounded-2xl relative z-0 overflow-hidden min-h-[200px] sm:min-h-[250px] md:min-h-[300px] lg:min-h-[400px]"
                            style="background-image: url('<?php echo e($main_side_banner ? asset('storage/' . $main_side_banner->image) : 'https://placehold.co/60x60/e2e8f0/475569?text=No+Image'); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                        </div>
                    </a>
                </div>

                <!-- RIGHT COLUMN - 8 COLUMNS (BANNER SLIDER) - FULL WIDTH ON MOBILE -->
                <div class="lg:flex-1 w-full">
                    <div class="swiper banner-slider rounded-xl sm:rounded-2xl overflow-hidden relative w-full"
                        style="height: 200px; min-height: 200px; width: 100%;"
                        x-data="bannerSwiper()"
                        x-init="init()">
                        <div class="swiper-wrapper" style="height: 100%; width: 100%;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banners->count() > 0): ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="swiper-slide" style="height: 100%; width: 100%;">
                                        <a href="<?php echo e($banner->url ?? route('shop')); ?>">
                                            <div class="w-full h-full relative z-0"
                                                style="background-image: url('<?php echo e(asset('storage/' . $banner->image)); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php else: ?>
                                <div class="swiper-slide" style="height: 100%; width: 100%;">
                                    <div class="w-full h-full relative z-0 bg-gray-200 flex items-center justify-center">
                                        <p class="text-gray-500">No banners available</p>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="swiper-pagination banner-pagination"></div>
                        <div class="swiper-button-next banner-next"></div>
                        <div class="swiper-button-prev banner-prev"></div>
                    </div>
                </div>
            </div>

            <style>
                .banner-slider { position: relative; overflow: hidden; width: 100%; height: 200px !important; min-height: 200px !important; }
                .banner-slider .swiper-wrapper { height: 100% !important; width: 100% !important; display: flex; flex-wrap: nowrap !important; }
                .banner-slider .swiper-slide { height: 100% !important; width: 100% !important; flex-shrink: 0; }
                .banner-slider .swiper-slide > div { width: 100%; height: 100%; background-size: cover !important; background-position: center !important; background-repeat: no-repeat !important; }
                .banner-slider .swiper-button-next,
                .banner-slider .swiper-button-prev { background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(4px); width: 40px; height: 40px; border-radius: 50%; color: white; transition: all 0.3s ease; z-index: 10; }
                .banner-slider .swiper-button-next:hover,
                .banner-slider .swiper-button-prev:hover { background: rgba(255, 215, 0, 0.3); }
                .banner-slider .swiper-button-next:after,
                .banner-slider .swiper-button-prev:after { font-size: 18px; font-weight: bold; }
                .banner-slider .swiper-pagination-bullet { background: white; opacity: 0.5; width: 10px; height: 10px; }
                .banner-slider .swiper-pagination-bullet-active { background: #FFD700; opacity: 1; width: 30px; border-radius: 5px; }
                .banner-slider .swiper-pagination { bottom: 20px; z-index: 10; }
                @media (max-width: 640px) {
                    .banner-slider { height: 180px !important; min-height: 180px !important; max-height: 220px !important; }
                    .banner-slider .swiper-button-next, .banner-slider .swiper-button-prev { display: none !important; }
                    .banner-slider .swiper-pagination { bottom: 10px !important; }
                    .banner-slider .swiper-pagination-bullet { width: 6px !important; height: 6px !important; }
                    .banner-slider .swiper-pagination-bullet-active { width: 16px !important; }
                }
                @media (min-width: 641px) and (max-width: 1023px) {
                    .banner-slider { height: 250px !important; min-height: 250px !important; max-height: 350px !important; }
                }
                @media (min-width: 1024px) {
                    .banner-slider { height: 100% !important; min-height: 300px !important; max-height: 500px !important; }
                }
            </style>

            <!-- Trust Badges Section -->
            <div class="hidden lg:block bg-white py-6 md:py-8 border-b border-gray-100">
               <div class="container mx-auto px-4 max-w-7xl">
                  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                     <div class="flex items-center gap-3 p-3 md:p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-[#5B9F01]/10 rounded-full flex items-center justify-center"><i class="fas fa-truck text-[#5B9F01] text-lg md:text-xl"></i></div>
                        <div><h3 class="text-sm font-bold text-gray-800">FREE SHIPPING</h3><p class="text-xs text-gray-500">On orders over $25</p></div>
                     </div>
                     <div class="flex items-center gap-3 p-3 md:p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-[#5B9F01]/10 rounded-full flex items-center justify-center"><i class="fas fa-undo-alt text-[#5B9F01] text-lg md:text-xl"></i></div>
                        <div><h3 class="text-sm font-bold text-gray-800">30-DAY RETURNS</h3><p class="text-xs text-gray-500">Easy returns policy</p></div>
                     </div>
                     <div class="flex items-center gap-3 p-3 md:p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-[#5B9F01]/10 rounded-full flex items-center justify-center"><i class="fas fa-lock text-[#5B9F01] text-lg md:text-xl"></i></div>
                        <div><h3 class="text-sm font-bold text-gray-800">SECURE PAYMENTS</h3><p class="text-xs text-gray-500">100% safe & secure</p></div>
                     </div>
                     <div class="flex items-center gap-3 p-3 md:p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-[#5B9F01]/10 rounded-full flex items-center justify-center"><i class="fas fa-headset text-[#5B9F01] text-lg md:text-xl"></i></div>
                        <div><h3 class="text-sm font-bold text-gray-800">EXPERT SUPPORT</h3><p class="text-xs text-gray-500">24/7 customer support</p></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <!-- Full Width Banner with Products Section -->
        <div class="hidden lg:block relative w-full overflow-hidden rounded-xl" style="background-image: url('<?php echo e(asset('assets/images/home/banner2.png')); ?>'); background-size:cover; background-position:center; background-repeat: no-repeat;">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative z-10 container mx-auto px-3 sm:px-4 max-w-7xl py-6 sm:py-8 md:py-12">
                <div class="grid grid-cols-1 gap-4 sm:gap-6">
                    <div class="w-full">
                        <div class="rounded-xl sm:rounded-2xl relative overflow-hidden">
                            <div class="relative z-10">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-3 sm:mb-4 md:mb-6 gap-2 sm:gap-0">
                                    <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                                        <h3 class="text-white text-base sm:text-lg md:text-xl font-bold">Best Sellers</h3>
                                    </div>
                                </div>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bestSellerProducts->isNotEmpty()): ?>
                                <div wire:key="best-seller-slider-wrapper" x-data="swiperManager('bestSellerSlider')" x-init="init()" class="relative">
                                    <div class="swiper bestSellerSlider overflow-hidden px-0 sm:px-1" x-ref="slider">
                                        <div class="swiper-wrapper">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $bestSellerProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="swiper-slide h-auto" wire:key="best-product-<?php echo e($product->id); ?>">
                                                    <div class="product-card bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300 group h-full flex flex-col">
                                                        <div class="relative flex-shrink-0">
                                                            <a href="<?php echo e(route('shop.details', $product->slug)); ?>">
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_variations && $product->variations && $product->variations->whereNotNull('image')->count() > 0): ?>
                                                                    <?php $firstVarImg = $product->variations->whereNotNull('image')->first(); ?>
                                                                    <img src="<?php echo e(asset('storage/' . $firstVarImg->image)); ?>" onerror="this.src='https://placehold.co/60x60/e2e8f0/475569?text=No+Image'" alt="<?php echo e($product->name); ?>" class="w-full h-32 sm:h-40 md:h-44 lg:h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                                                <?php else: ?>
                                                                    <img src="<?php echo e(asset('storage/' . $product->image)); ?>" onerror="this.src='https://placehold.co/60x60/e2e8f0/475569?text=No+Image'" alt="<?php echo e($product->name); ?>" class="w-full h-32 sm:h-40 md:h-44 lg:h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            </a>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->category): ?>
                                                                <span class="absolute top-2 left-2 bg-red-500 text-white text-[8px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded"><?php echo e($product->category->name); ?></span>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_variations): ?>
                                                                <span class="absolute bottom-2 left-2 bg-[#012D89] text-white text-[8px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded"><i class="fas fa-list"></i> <?php echo e($product->variations->count()); ?> Options</span>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            <button wire:click="toggleWishlist(<?php echo e($product->id); ?>)" wire:loading.attr="disabled" class="absolute top-2 right-2 w-7 h-7 sm:w-8 sm:h-8 bg-white rounded-full flex items-center justify-center shadow-md hover:bg-gray-50 transition z-10" title="Add to wishlist">
                                                                <span wire:loading.remove wire:target="toggleWishlist(<?php echo e($product->id); ?>)">
                                                                    <?php
                                                                        $inWishlist = \App\Models\Wishlist::where('product_id', $product->id)
                                                                            ->when(Auth::id(), fn($q) => $q->where('customer_id', Auth::id()), fn($q) => $q->where('session_id', Session::getId()))
                                                                            ->exists();
                                                                    ?>
                                                                    <i class="<?php echo e($inWishlist ? 'fas fa-heart text-red-500' : 'far fa-heart text-gray-600'); ?> text-xs sm:text-sm"></i>
                                                                </span>
                                                                <span wire:loading wire:target="toggleWishlist(<?php echo e($product->id); ?>)" class="flex items-center justify-center"><svg class="animate-spin h-3 w-3 sm:h-4 sm:w-4 text-gray-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></span>
                                                            </button>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_variations): ?>
                                                                <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 lg:hidden">
                                                                    <a href="<?php echo e(route('shop.details', $product->slug)); ?>" class="block w-full bg-[#012D89] text-white py-1.5 rounded-lg text-xs font-medium text-center hover:bg-[#001f5c] transition"><i class="fas fa-list"></i> View Options</a>
                                                                </div>
                                                            <?php else: ?>
                                                                <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 lg:hidden">
                                                                    <button wire:click="addToCart(<?php echo e($product->id); ?>)" wire:loading.attr="disabled" wire:target="addToCart(<?php echo e($product->id); ?>)" class="w-full bg-[#FDCC02] text-gray-900 py-1.5 rounded-lg text-xs font-medium hover:bg-[#4a7f01] transition hover:text-white">
                                                                        <span wire:loading.remove wire:target="addToCart(<?php echo e($product->id); ?>)"><i class="fas fa-shopping-cart"></i> Quick Add</span>
                                                                        <span wire:loading wire:target="addToCart(<?php echo e($product->id); ?>)" class="flex items-center justify-center gap-2"><svg class="animate-spin h-3 w-3 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Adding...</span>
                                                                    </button>
                                                                </div>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                        <div class="p-2 sm:p-3 flex flex-col flex-1">
                                                            <a href="<?php echo e(route('shop.details', $product->slug)); ?>"><h3 class="text-xs sm:text-sm font-medium text-gray-800 line-clamp-2 hover:text-[#5B9F01] transition min-h-[2rem] sm:min-h-[2.5rem]"><?php echo e($product->name); ?></h3></a>
                                                            <div class="flex items-center gap-1 mb-1.5 sm:mb-2 flex-wrap">
                                                                <div class="flex text-yellow-400 text-[9px] sm:text-xs"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i></div>
                                                                <span class="text-[9px] sm:text-[10px] text-gray-400 whitespace-nowrap">(24)</span>
                                                            </div>
                                                            <div class="flex items-center justify-between mt-auto pt-1 sm:pt-2 border-t border-gray-50">
                                                                <div class="flex items-center gap-1 sm:gap-2 flex-wrap">
                                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_variations && $product->variations->isNotEmpty()): ?>
                                                                        <?php
                                                                            $minVariationPrice = $product->variations->min('price');
                                                                            $minVariationDiscount = $product->variations->filter(fn($v) => $v->discounted_price && $v->discounted_price < $v->price)->min('discounted_price');
                                                                        ?>
                                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($minVariationDiscount && $minVariationDiscount < $minVariationPrice): ?>
                                                                            <span class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 whitespace-nowrap">$<?php echo e(number_format($minVariationDiscount, 2)); ?></span>
                                                                            <span class="text-[8px] sm:text-[10px] text-gray-400 line-through whitespace-nowrap">$<?php echo e(number_format($minVariationPrice, 2)); ?></span>
                                                                        <?php else: ?>
                                                                            <span class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 whitespace-nowrap">$<?php echo e(number_format($minVariationPrice, 2)); ?></span>
                                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                        <span class="text-[8px] sm:text-[9px] text-gray-500 whitespace-nowrap">+</span>
                                                                    <?php else: ?>
                                                                        <span class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 whitespace-nowrap">$<?php echo e(number_format($product->price, 2)); ?></span>
                                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->discounted_price && $product->discounted_price < $product->price): ?>
                                                                            <span class="text-[8px] sm:text-[10px] text-gray-400 line-through whitespace-nowrap">$<?php echo e(number_format($product->discounted_price, 2)); ?></span>
                                                                            <span class="text-[7px] sm:text-[8px] bg-red-500 text-white px-1 sm:px-1.5 py-0.5 rounded font-bold whitespace-nowrap">-<?php echo e(round((($product->price - $product->discounted_price) / $product->price) * 100)); ?>%</span>
                                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                </div>
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_variations): ?>
                                                                    <a href="<?php echo e(route('shop.details', $product->slug)); ?>" class="flex bg-[#012D89] text-white px-1.5 sm:px-3 py-1 sm:py-1.5 rounded-lg text-[8px] sm:text-[10px] lg:text-xs font-medium hover:bg-[#001f5c] transition items-center gap-0.5 sm:gap-1 whitespace-nowrap flex-shrink-0"><i class="fas fa-list text-[8px] sm:text-[10px] lg:text-xs"></i> <span class="hidden sm:inline">Select</span></a>
                                                                <?php else: ?>
                                                                    <button wire:click="addToCart(<?php echo e($product->id); ?>)" wire:loading.attr="disabled" wire:target="addToCart(<?php echo e($product->id); ?>)" class="flex bg-[#FDCC02] text-gray-900 px-1.5 sm:px-3 py-1 sm:py-1.5 rounded-lg text-[8px] sm:text-[10px] lg:text-xs font-medium hover:bg-[#4a7f01] transition hover:text-white items-center gap-0.5 sm:gap-1 whitespace-nowrap flex-shrink-0">
                                                                        <span wire:loading.remove wire:target="addToCart(<?php echo e($product->id); ?>)"><i class="fas fa-shopping-cart text-[8px] sm:text-[10px] lg:text-xs"></i><span class="hidden sm:inline">Add</span></span>
                                                                        <span wire:loading wire:target="addToCart(<?php echo e($product->id); ?>)" class="flex items-center gap-0.5 sm:gap-1"><svg class="animate-spin h-2 w-2 sm:h-3 sm:w-3 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="hidden sm:inline">Adding</span></span>
                                                                    </button>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php else: ?>
                                    <div class="text-white/70 text-sm py-6 text-center">No best sellers yet.</div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- LEFT SIDE - COL 3 -->
            <div class="lg:col-span-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($deal_of_the_day): ?>
                <div class="bg-[#012D89] rounded-xl border border-gray-200 p-5 mt-4">
                    <div class="flex items-center justify-between mb-4"><h2 class="text-lg font-bold text-[#FDCC02]">Deal of the Day</h2></div>
                    <div class="flex items-center justify-center gap-2 mb-1"><span class="text-sm text-white font-bold"><?php echo e($deal_of_the_day->name); ?></span></div>
                    <div class="flex items-center justify-center gap-3 mb-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($deal_of_the_day->has_variations && $deal_of_the_day->variations->isNotEmpty()): ?>
                            <?php
                                $dealMinPrice = $deal_of_the_day->variations->min('price');
                                $dealMinDiscount = $deal_of_the_day->variations->filter(fn($v) => $v->discounted_price && $v->discounted_price < $v->price)->min('discounted_price');
                            ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($dealMinDiscount && $dealMinDiscount < $dealMinPrice): ?>
                                <span class="text-sm text-white line-through">$<?php echo e(number_format($dealMinPrice, 2)); ?></span>
                                <span class="text-lg font-bold text-white">$<?php echo e(number_format($dealMinDiscount, 2)); ?></span>
                                <span class="bg-[#5B9F01] text-xs font-bold text-white p-2 rounded">-<?php echo e(round((($dealMinPrice - $dealMinDiscount) / $dealMinPrice) * 100)); ?>% OFF</span>
                            <?php else: ?>
                                <span class="text-base font-bold text-white">$<?php echo e(number_format($dealMinPrice, 2)); ?> +</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php else: ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($deal_of_the_day->discounted_price && $deal_of_the_day->discounted_price < $deal_of_the_day->price): ?>
                                <span class="text-sm text-white line-through">$<?php echo e(number_format($deal_of_the_day->price, 2)); ?></span>
                                <span class="text-lg font-bold text-white">$<?php echo e(number_format($deal_of_the_day->discounted_price, 2)); ?></span>
                                <span class="bg-[#5B9F01] text-xs font-bold text-white p-2 rounded">-<?php echo e(round((($deal_of_the_day->price - $deal_of_the_day->discounted_price) / $deal_of_the_day->price) * 100)); ?>% OFF</span>
                            <?php else: ?>
                                <span class="text-base font-bold text-white">$<?php echo e(number_format($deal_of_the_day->price, 2)); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($deal_of_the_day->has_variations): ?>
                        <a href="<?php echo e(route('shop.details', $deal_of_the_day->slug)); ?>" class="block w-full bg-[#012D89] border-2 border-[#fdcc02] text-[#fdcc02] py-2 rounded-lg text-sm font-medium text-center hover:bg-[#fdcc02] hover:text-gray-900 transition"><i class="fas fa-list"></i> Select Options</a>
                    <?php else: ?>
                        <button wire:click="addToCart(<?php echo e($deal_of_the_day->id); ?>)" wire:loading.attr="disabled" wire:target="addToCart(<?php echo e($deal_of_the_day->id); ?>)" class="w-full bg-[#fdcc02] text-gray-900 py-2 rounded-lg text-sm font-medium hover:bg-[#fdcc02] transition">
                            <span wire:loading.remove wire:target="addToCart(<?php echo e($deal_of_the_day->id); ?>)"><i class="fas fa-shopping-cart"></i> <span class="hidden sm:inline">Add to Cart</span></span>
                            <span wire:loading wire:target="addToCart(<?php echo e($deal_of_the_day->id); ?>)" class="flex items-center justify-center gap-1"><svg class="animate-spin h-3 w-3 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="hidden sm:inline">Adding...</span></span>
                        </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="hidden lg:block bg-[#5B9F01] rounded-xl p-5 mb-6 mt-2">
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="flex-shrink-0"><div class="rounded-full flex items-center justify-center"><i class="fas fa-wallet text-white text-2xl"></i></div></div>
                        <div class="flex-1 text-center sm:text-left">
                            <h3 class="text-white text-md sm:text-sm font-bold mb-1">Get 5% Back in Rewards</h3>
                            <p class="text-white/80 text-xs">Join My Green Jack Rewards</p>
                            <p class="text-white/60 text-xs mt-0.5">It's free and easy.</p>
                        </div>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($side_banners->isNotEmpty()): ?>
                <div class="hidden lg:block">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $side_banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-xl overflow-hidden mt-4 relative group cursor-pointer shadow-lg">
                            <a href="<?php echo e($banner->url ?? route('shop')); ?>"><img src="<?php echo e(asset('storage/' . $banner->image)); ?>" alt="<?php echo e($banner->title ?? ''); ?>" class="w-full h-auto object-cover"></a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- RIGHT SIDE - COL 9 -->
            <div class="lg:col-span-9">
                <?php
                    $categoryGroups = [
                        'Computers & Laptops' => ['computer-laptop', 'laptop', 'desktop', 'workstation', 'all-in-one', 'gaming-laptop'],
                        'Apple' => ['iphones', 'ipads', 'macbook', 'imac', 'apple-watch', 'airpods', 'macbook-pro', 'macbook-air'],
                        'Android' => ['android-phones', 'android-tablets', 'samsung', 'google-pixel', 'oneplus', 'xiaomi', 'oppo', 'vivo'],
                        'Gaming' => ['gaming-computer', 'gaming-console', 'ps5', 'xbox', 'nintendo-switch', 'playstation', 'gaming-accessories'],
                        'Accessories' => ['accessories', 'headphones', 'earbuds', 'speakers', 'cases', 'chargers', 'cables', 'screen-protectors', 'power-banks'],
                    ];

                    $allCategories = collect();
                    try {
                        $allCategories = \App\Models\Category::with(['products' => function($query) {
                            $query->limit(10)->orderBy('created_at', 'desc');
                        }])->whereIn('slug', collect($categoryGroups)->flatten()->toArray())->get();
                    } catch (\Throwable $e) {
                        \Log::warning('Shop group categories load failed: ' . $e->getMessage());
                    }

                    $groupedProducts = [];
                    $index = 0;
                    foreach ($categoryGroups as $groupName => $categorySlugs) {
                        $categories = $allCategories->filter(fn($cat) => in_array($cat->slug, $categorySlugs));
                        $products = collect();
                        foreach ($categories as $category) {
                            $products = $products->concat($category->products ?? collect());
                        }
                        if ($products->count() > 0) {
                            $groupedProducts[] = [
                                'id' => $index,
                                'name' => $groupName,
                                'categories' => $categories,
                                'products' => $products->take(10),
                                'slug' => strtolower(str_replace(' & ', '-', str_replace(' ', '-', $groupName))),
                            ];
                            $index++;
                        }
                    }
                ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $groupedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mb-8" x-data="swiperManager('productCategorySlider-<?php echo e($group['id']); ?>')" x-init="init()">
                        <div class="flex items-center justify-between mb-6 mt-4">
                            <div class="relative">
                                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 inline-block">
                                    <?php echo e($group['name']); ?>

                                    <span class="absolute -bottom-1 left-0 w-12 h-1 bg-[#5B9F01] rounded-full"></span>
                                </h2>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($group['categories']->count() > 0): ?>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $group['categories']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="text-[8px] sm:text-[10px] bg-gray-100 text-gray-600 px-1.5 sm:px-2 py-0.5 rounded-full"><?php echo e($category->name); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <a href="<?php echo e(route('shop.by.category', $group['slug'])); ?>" class="text-sm text-[#5B9F01] hover:text-[#4a7f01] font-medium flex items-center gap-1 group whitespace-nowrap">View All <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i></a>
                        </div>
                        <div class="relative">
                            <div class="swiper productCategorySlider-<?php echo e($group['id']); ?> overflow-hidden px-1" x-ref="slider">
                                <div class="swiper-wrapper">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $group['products']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="swiper-slide h-auto" wire:key="group-<?php echo e($group['id']); ?>-product-<?php echo e($product->id); ?>">
                                            <div class="product-card bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300 group h-full flex flex-col">
                                                <div class="relative flex-shrink-0">
                                                    <a href="<?php echo e(route('shop.details', $product->slug)); ?>">
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_variations && $product->variations && $product->variations->whereNotNull('image')->count() > 0): ?>
                                                            <?php $firstVarImg = $product->variations->whereNotNull('image')->first(); ?>
                                                            <img src="<?php echo e(asset('storage/' . $firstVarImg->image)); ?>" onerror="this.src='https://placehold.co/60x60/e2e8f0/475569?text=No+Image'" alt="Product" class="w-full h-32 sm:h-40 md:h-44 lg:h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                                        <?php else: ?>
                                                            <img src="<?php echo e(asset('storage/' . $product->image)); ?>" onerror="this.src='https://placehold.co/60x60/e2e8f0/475569?text=No+Image'" alt="Product" class="w-full h-32 sm:h-40 md:h-44 lg:h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </a>
                                                    <?php
                                                        $colors = ['#ef4444','#f97316','#f59e0b','#eab308','#84cc16','#22c55e','#10b981','#14b8a6','#06b6d4','#0ea5e9','#3b82f6','#6366f1','#8b5cf6','#a855f7','#d946ef','#ec4899','#f43f5e'];
                                                        $color = $colors[($product->category->id ?? 0) % count($colors)];
                                                    ?>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->category): ?>
                                                        <span class="absolute top-2 left-2 text-white text-[8px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded" style="background-color: <?php echo e($color); ?>;"><?php echo e($product->category->name); ?></span>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <button wire:click="toggleWishlist(<?php echo e($product->id); ?>)" wire:loading.attr="disabled" class="absolute top-2 right-2 w-7 h-7 sm:w-8 sm:h-8 bg-white rounded-full flex items-center justify-center shadow-md hover:bg-gray-50 transition z-10" title="Add to wishlist">
                                                        <span wire:loading.remove wire:target="toggleWishlist(<?php echo e($product->id); ?>)">
                                                            <?php
                                                                $inWishlist = \App\Models\Wishlist::where('product_id', $product->id)
                                                                    ->when(Auth::id(), fn($q) => $q->where('customer_id', Auth::id()), fn($q) => $q->where('session_id', Session::getId()))
                                                                    ->exists();
                                                            ?>
                                                            <i class="<?php echo e($inWishlist ? 'fas fa-heart text-red-500' : 'far fa-heart text-gray-600'); ?> text-xs sm:text-sm"></i>
                                                        </span>
                                                        <span wire:loading wire:target="toggleWishlist(<?php echo e($product->id); ?>)" class="flex items-center justify-center"><svg class="animate-spin h-3 w-3 sm:h-4 sm:w-4 text-gray-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></span>
                                                    </button>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_variations): ?>
                                                        <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 lg:hidden">
                                                            <a href="<?php echo e(route('shop.details', $product->slug)); ?>" class="block w-full bg-[#012D89] text-white py-1.5 rounded-lg text-xs font-medium text-center hover:bg-[#001f5c] transition"><i class="fas fa-list"></i> View Options</a>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 lg:hidden">
                                                            <button wire:click="addToCart(<?php echo e($product->id); ?>)" wire:loading.attr="disabled" wire:target="addToCart(<?php echo e($product->id); ?>)" class="w-full bg-[#FDCC02] text-gray-900 py-1.5 rounded-lg text-xs font-medium hover:bg-[#4a7f01] transition hover:text-white">
                                                                <span wire:loading.remove wire:target="addToCart(<?php echo e($product->id); ?>)"><i class="fas fa-shopping-cart"></i> Quick Add</span>
                                                                <span wire:loading wire:target="addToCart(<?php echo e($product->id); ?>)" class="flex items-center justify-center gap-2"><svg class="animate-spin h-3 w-3 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Adding...</span>
                                                            </button>
                                                        </div>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                                <div class="p-2 sm:p-3 flex flex-col flex-1">
                                                    <a href="<?php echo e(route('shop.details', $product->slug)); ?>"><h3 class="text-xs sm:text-sm font-medium text-gray-800 line-clamp-2 hover:text-[#5B9F01] transition min-h-[2rem] sm:min-h-[2.5rem]"><?php echo e(ucwords(strtolower($product->name))); ?></h3></a>
                                                    <div class="flex items-center gap-1 mb-1.5 sm:mb-2 flex-wrap">
                                                        <div class="flex text-yellow-400 text-[9px] sm:text-xs"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i></div>
                                                        <span class="text-[9px] sm:text-[10px] text-gray-400 whitespace-nowrap">(24)</span>
                                                    </div>
                                                    <div class="flex items-center justify-between mt-auto pt-1 sm:pt-2 border-t border-gray-50">
                                                        <div class="flex items-center gap-1 sm:gap-2 flex-wrap">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_variations && $product->variations->isNotEmpty()): ?>
                                                                <?php
                                                                    $minVPrice = $product->variations->min('price');
                                                                    $minVDiscount = $product->variations->filter(fn($v) => $v->discounted_price && $v->discounted_price < $v->price)->min('discounted_price');
                                                                ?>
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($minVDiscount && $minVDiscount < $minVPrice): ?>
                                                                    <span class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 whitespace-nowrap">$<?php echo e(number_format($minVDiscount, 2)); ?></span>
                                                                    <span class="text-[8px] sm:text-[10px] text-gray-400 line-through whitespace-nowrap">$<?php echo e(number_format($minVPrice, 2)); ?></span>
                                                                <?php else: ?>
                                                                    <span class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 whitespace-nowrap">$<?php echo e(number_format($minVPrice, 2)); ?></span>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                <span class="text-[8px] sm:text-[9px] text-gray-500 whitespace-nowrap">+</span>
                                                            <?php else: ?>
                                                                <span class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 whitespace-nowrap">$<?php echo e(number_format($product->price, 2)); ?></span>
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->discounted_price && $product->discounted_price < $product->price): ?>
                                                                    <span class="text-[8px] sm:text-[10px] text-gray-400 line-through whitespace-nowrap">$<?php echo e(number_format($product->discounted_price, 2)); ?></span>
                                                                    <span class="text-[7px] sm:text-[8px] bg-red-500 text-white px-1 sm:px-1.5 py-0.5 rounded font-bold whitespace-nowrap">-<?php echo e(round((($product->price - $product->discounted_price) / $product->price) * 100)); ?>%</span>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_variations): ?>
                                                            <a href="<?php echo e(route('shop.details', $product->slug)); ?>" class="flex bg-[#012D89] text-white px-1.5 sm:px-3 py-1 sm:py-1.5 rounded-lg text-[8px] sm:text-[10px] lg:text-xs font-medium hover:bg-[#001f5c] transition items-center gap-0.5 sm:gap-1 whitespace-nowrap flex-shrink-0"><i class="fas fa-list text-[8px] sm:text-[10px] lg:text-xs"></i><span class="hidden sm:inline">Select</span></a>
                                                        <?php else: ?>
                                                            <button wire:click="addToCart(<?php echo e($product->id); ?>)" wire:loading.attr="disabled" wire:target="addToCart(<?php echo e($product->id); ?>)" class="flex bg-[#FDCC02] text-gray-900 px-1.5 sm:px-3 py-1 sm:py-1.5 rounded-lg text-[8px] sm:text-[10px] lg:text-xs font-medium hover:bg-[#4a7f01] transition hover:text-white items-center gap-0.5 sm:gap-1 whitespace-nowrap flex-shrink-0">
                                                                <span wire:loading.remove wire:target="addToCart(<?php echo e($product->id); ?>)"><i class="fas fa-shopping-cart text-[8px] sm:text-[10px] lg:text-xs"></i><span class="hidden sm:inline">Add</span></span>
                                                                <span wire:loading wire:target="addToCart(<?php echo e($product->id); ?>)" class="flex items-center gap-0.5 sm:gap-1"><svg class="animate-spin h-2 w-2 sm:h-3 sm:w-3 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="hidden sm:inline">Adding</span></span>
                                                            </button>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div class="categoryProduct-pagination-<?php echo e($group['id']); ?> flex justify-center mt-4"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Categories Slider -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="relative"><h2 class="text-2xl font-bold text-gray-800 inline-block">Categories<span class="absolute -bottom-1 left-0 w-12 h-1 bg-[#5B9F01] rounded-full"></span></h2></div>
                        <a href="<?php echo e(route('categories')); ?>" class="text-sm text-[#5B9F01] hover:text-[#4a7f01] font-medium flex items-center gap-1 group">View All <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i></a>
                    </div>
                    <div class="relative">
                        <div class="absolute left-0 top-0 bottom-0 w-32 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>
                        <div class="absolute right-0 top-0 bottom-0 w-32 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>
                        <div class="overflow-hidden py-4">
                            <div class="category-track flex items-center gap-6 animate-scroll-slow hover:animate-pause">
                                <?php
                                    $retailCategories = collect();
                                    try { $retailCategories = \App\Models\Category::get(); } catch (\Throwable $e) { \Log::warning('Categories load failed: ' . $e->getMessage()); }
                                ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($retailCategories->isNotEmpty()): ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $retailCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e(route('shop.by.category', $category->slug)); ?>" class="category-card bg-white shadow-md rounded-xl hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden group min-w-[160px]">
                                            <div class="p-4 flex flex-col items-center justify-center">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->image): ?>
                                                    <img src="<?php echo e(asset('storage/' . $category->image)); ?>" alt="<?php echo e($category->name); ?>" class="h-24 w-24 object-contain transition duration-300 group-hover:scale-105">
                                                <?php else: ?>
                                                    <div class="h-24 w-24 bg-gradient-to-br from-[#5B9F01]/20 to-[#5B9F01]/5 rounded-full flex items-center justify-center"><i class="fas fa-microchip text-3xl text-[#5B9F01]/40"></i></div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <h3 class="font-semibold text-gray-800 text-sm mt-3 text-center group-hover:text-[#5B9F01] transition-colors"><?php echo e($category->name); ?></h3>
                                                <?php
                                                    $productCount = 0;
                                                    try { $productCount = \App\Models\Product::where('category_id', $category->id)->count(); } catch (\Throwable $e) {}
                                                ?>
                                                <p class="text-xs text-[#5B9F01] mt-1">Products (<?php echo e($productCount); ?>)</p>
                                            </div>
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $retailCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e(route('shop.by.category', $category->slug)); ?>" class="category-card bg-white shadow-md rounded-xl hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden group min-w-[160px]">
                                            <div class="p-4 flex flex-col items-center justify-center">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->image): ?>
                                                    <img src="<?php echo e(asset('storage/' . $category->image)); ?>" alt="<?php echo e($category->name); ?>" class="h-24 w-24 object-contain transition duration-300 group-hover:scale-105">
                                                <?php else: ?>
                                                    <div class="h-24 w-24 bg-gradient-to-br from-[#5B9F01]/20 to-[#5B9F01]/5 rounded-full flex items-center justify-center"><i class="fas fa-microchip text-3xl text-[#5B9F01]/40"></i></div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <h3 class="font-semibold text-gray-800 text-sm mt-3 text-center group-hover:text-[#5B9F01] transition-colors"><?php echo e($category->name); ?></h3>
                                                <?php
                                                    $productCount = 0;
                                                    try { $productCount = \App\Models\Product::where('category_id', $category->id)->count(); } catch (\Throwable $e) {}
                                                ?>
                                                <p class="text-xs text-[#5B9F01] mt-1">Products (<?php echo e($productCount); ?>)</p>
                                            </div>
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Featured Products Slider -->
                <div class="mb-8" x-data="swiperManager('featuredSlider')" x-init="init()">
                    <div class="flex items-center justify-between mb-6">
                        <div class="relative"><h2 class="text-2xl font-bold text-gray-800 inline-block">Featured<span class="absolute -bottom-1 left-0 w-12 h-1 bg-[#5B9F01] rounded-full"></span></h2></div>
                        <a href="<?php echo e(route('products')); ?>" class="text-sm text-[#5B9F01] hover:text-[#4a7f01] font-medium flex items-center gap-1 group">View All <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i></a>
                    </div>
                    <div class="relative">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featuredProducts->isNotEmpty()): ?>
                            <div class="swiper featuredSlider overflow-hidden px-1" x-ref="slider">
                                <div class="swiper-wrapper">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="swiper-slide h-auto" wire:key="featured-<?php echo e($product->id); ?>">
                                            <div class="product-card bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300 group h-full flex flex-col">
                                                <div class="relative flex-shrink-0">
                                                    <a href="<?php echo e(route('shop.details', $product->slug)); ?>">
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_variations && $product->variations && $product->variations->whereNotNull('image')->count() > 0): ?>
                                                            <?php $firstVarImg = $product->variations->whereNotNull('image')->first(); ?>
                                                            <img src="<?php echo e(asset('storage/' . $firstVarImg->image)); ?>" onerror="this.src='https://placehold.co/60x60/e2e8f0/475569?text=No+Image'" alt="Product" class="w-full h-32 sm:h-40 md:h-44 lg:h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                                        <?php else: ?>
                                                            <img src="<?php echo e(asset('storage/' . $product->image)); ?>" onerror="this.src='https://placehold.co/60x60/e2e8f0/475569?text=No+Image'" alt="Product" class="w-full h-32 sm:h-40 md:h-44 lg:h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </a>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->category): ?>
                                                        <span class="absolute top-2 left-2 bg-red-500 text-white text-[8px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded"><?php echo e($product->category->name); ?></span>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_variations): ?>
                                                        <span class="absolute bottom-2 left-2 bg-[#012D89] text-white text-[8px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded"><i class="fas fa-list"></i> <?php echo e($product->variations->count()); ?> Options</span>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <button wire:click="toggleWishlist(<?php echo e($product->id); ?>)" wire:loading.attr="disabled" class="absolute top-2 right-2 w-7 h-7 sm:w-8 sm:h-8 bg-white rounded-full flex items-center justify-center shadow-md hover:bg-gray-50 transition z-10" title="Add to wishlist">
                                                        <span wire:loading.remove wire:target="toggleWishlist(<?php echo e($product->id); ?>)">
                                                            <?php
                                                                $inWishlist = \App\Models\Wishlist::where('product_id', $product->id)
                                                                    ->when(Auth::id(), fn($q) => $q->where('customer_id', Auth::id()), fn($q) => $q->where('session_id', Session::getId()))
                                                                    ->exists();
                                                            ?>
                                                            <i class="<?php echo e($inWishlist ? 'fas fa-heart text-red-500' : 'far fa-heart text-gray-600'); ?> text-xs sm:text-sm"></i>
                                                        </span>
                                                        <span wire:loading wire:target="toggleWishlist(<?php echo e($product->id); ?>)" class="flex items-center justify-center"><svg class="animate-spin h-3 w-3 sm:h-4 sm:w-4 text-gray-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></span>
                                                    </button>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_variations): ?>
                                                        <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 lg:hidden">
                                                            <a href="<?php echo e(route('shop.details', $product->slug)); ?>" class="block w-full bg-[#012D89] text-white py-1.5 rounded-lg text-xs font-medium text-center hover:bg-[#001f5c] transition"><i class="fas fa-list"></i> View Options</a>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 lg:hidden">
                                                            <button wire:click="addToCart(<?php echo e($product->id); ?>)" wire:loading.attr="disabled" wire:target="addToCart(<?php echo e($product->id); ?>)" class="w-full bg-[#FDCC02] text-gray-900 py-1.5 rounded-lg text-xs font-medium hover:bg-[#4a7f01] transition hover:text-white">
                                                                <span wire:loading.remove wire:target="addToCart(<?php echo e($product->id); ?>)"><i class="fas fa-shopping-cart"></i> Quick Add</span>
                                                                <span wire:loading wire:target="addToCart(<?php echo e($product->id); ?>)" class="flex items-center justify-center gap-2"><svg class="animate-spin h-3 w-3 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Adding...</span>
                                                            </button>
                                                        </div>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                                <div class="p-2 sm:p-3 flex flex-col flex-1">
                                                    <a href="<?php echo e(route('shop.details', $product->slug)); ?>"><h3 class="text-xs sm:text-sm font-medium text-gray-800 line-clamp-2 hover:text-[#5B9F01] transition min-h-[2rem] sm:min-h-[2.5rem]"><?php echo e($product->name); ?></h3></a>
                                                    <div class="flex items-center gap-1 mb-1.5 sm:mb-2 flex-wrap">
                                                        <div class="flex text-yellow-400 text-[9px] sm:text-xs"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i></div>
                                                        <span class="text-[9px] sm:text-[10px] text-gray-400 whitespace-nowrap">(24)</span>
                                                    </div>
                                                    <div class="flex items-center justify-between mt-auto pt-1 sm:pt-2 border-t border-gray-50">
                                                        <div class="flex items-center gap-1 sm:gap-2 flex-wrap">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_variations && $product->variations->isNotEmpty()): ?>
                                                                <?php
                                                                    $minVPrice = $product->variations->min('price');
                                                                    $minVDiscount = $product->variations->filter(fn($v) => $v->discounted_price && $v->discounted_price < $v->price)->min('discounted_price');
                                                                ?>
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($minVDiscount && $minVDiscount < $minVPrice): ?>
                                                                    <span class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 whitespace-nowrap">$<?php echo e(number_format($minVDiscount, 2)); ?></span>
                                                                    <span class="text-[8px] sm:text-[10px] text-gray-400 line-through whitespace-nowrap">$<?php echo e(number_format($minVPrice, 2)); ?></span>
                                                                <?php else: ?>
                                                                    <span class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 whitespace-nowrap">$<?php echo e(number_format($minVPrice, 2)); ?></span>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                <span class="text-[8px] sm:text-[9px] text-gray-500 whitespace-nowrap">+</span>
                                                            <?php else: ?>
                                                                <span class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 whitespace-nowrap">$<?php echo e(number_format($product->price, 2)); ?></span>
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->discounted_price && $product->discounted_price < $product->price): ?>
                                                                    <span class="text-[8px] sm:text-[10px] text-gray-400 line-through whitespace-nowrap">$<?php echo e(number_format($product->discounted_price, 2)); ?></span>
                                                                    <span class="text-[7px] sm:text-[8px] bg-red-500 text-white px-1 sm:px-1.5 py-0.5 rounded font-bold whitespace-nowrap">-<?php echo e(round((($product->price - $product->discounted_price) / $product->price) * 100)); ?>%</span>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_variations): ?>
                                                            <a href="<?php echo e(route('shop.details', $product->slug)); ?>" class="flex bg-[#012D89] text-white px-1.5 sm:px-3 py-1 sm:py-1.5 rounded-lg text-[8px] sm:text-[10px] lg:text-xs font-medium hover:bg-[#001f5c] transition items-center gap-0.5 sm:gap-1 whitespace-nowrap flex-shrink-0"><i class="fas fa-list text-[8px] sm:text-[10px] lg:text-xs"></i><span class="hidden sm:inline">Select</span></a>
                                                        <?php else: ?>
                                                            <button wire:click="addToCart(<?php echo e($product->id); ?>)" wire:loading.attr="disabled" wire:target="addToCart(<?php echo e($product->id); ?>)" class="flex bg-[#FDCC02] text-gray-900 px-1.5 sm:px-3 py-1 sm:py-1.5 rounded-lg text-[8px] sm:text-[10px] lg:text-xs font-medium hover:bg-[#4a7f01] transition hover:text-white items-center gap-0.5 sm:gap-1 whitespace-nowrap flex-shrink-0">
                                                                <span wire:loading.remove wire:target="addToCart(<?php echo e($product->id); ?>)"><i class="fas fa-shopping-cart text-[8px] sm:text-[10px] lg:text-xs"></i><span class="hidden sm:inline">Add</span></span>
                                                                <span wire:loading wire:target="addToCart(<?php echo e($product->id); ?>)" class="flex items-center gap-0.5 sm:gap-1"><svg class="animate-spin h-2 w-2 sm:h-3 sm:w-3 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="hidden sm:inline">Adding</span></span>
                                                            </button>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div class="featured-pagination flex justify-center mt-4"></div>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-gray-500 py-6 text-sm">No featured products yet.</div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Brands Slider -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div class="relative"><h2 class="text-2xl font-bold text-gray-800 inline-block">Brands<span class="absolute -bottom-1 left-0 w-12 h-1 bg-[#5B9F01] rounded-full"></span></h2></div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($brands->isNotEmpty()): ?>
                <div class="relative">
                    <div class="absolute left-0 top-0 bottom-0 w-32 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>
                    <div class="absolute right-0 top-0 bottom-0 w-32 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>
                    <div class="overflow-hidden py-4">
                        <div class="category-track flex items-center gap-6 animate-scroll-slow hover:animate-pause">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="javascript:;" class="category-card bg-white shadow-md rounded-xl hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden group min-w-[160px]">
                                    <div class="p-4 flex flex-col items-center justify-center">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($brand->featured_image): ?>
                                            <img src="<?php echo e(asset('storage/' . $brand->featured_image)); ?>" alt="<?php echo e($brand->name); ?>" class="h-24 w-24 object-contain transition duration-300 group-hover:scale-105">
                                        <?php else: ?>
                                            <div class="h-24 w-24 bg-gradient-to-br from-[#5B9F01]/20 to-[#5B9F01]/5 rounded-full flex items-center justify-center"><i class="fas fa-microchip text-3xl text-[#5B9F01]/40"></i></div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="javascript:;" class="category-card bg-white shadow-md rounded-xl hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden group min-w-[160px]">
                                    <div class="p-4 flex flex-col items-center justify-center">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($brand->featured_image): ?>
                                            <img src="<?php echo e(asset('storage/' . $brand->featured_image)); ?>" alt="<?php echo e($brand->name); ?>" class="h-24 w-24 object-contain transition duration-300 group-hover:scale-105">
                                        <?php else: ?>
                                            <div class="h-24 w-24 bg-gradient-to-br from-[#5B9F01]/20 to-[#5B9F01]/5 rounded-full flex items-center justify-center"><i class="fas fa-microchip text-3xl text-[#5B9F01]/40"></i></div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <style>
            @keyframes scrollSlow { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
            .animate-scroll-slow { animation: scrollSlow 25s linear infinite; width: max-content; }
            .hover\:animate-pause:hover { animation-play-state: paused; }
            .brand-card { transition: all 0.3s ease; }
            .brand-card:hover { transform: translateY(-2px); }
            .swiper-slide { height: auto !important; }
            .product-card { height: 100%; display: flex; flex-direction: column; }
            .product-card .relative { flex-shrink: 0; }
            .product-card .p-2 { flex: 1; display: flex; flex-direction: column; }
            .product-card .p-2 .flex.items-center.justify-between.mt-2 { margin-top: auto; }
        </style>

        <!-- SECTION 5: Customer Reviews -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">What Our Customers Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-2 mb-2"><div class="text-yellow-400"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><span class="text-gray-600 text-sm">5.0</span></div>
                    <p class="text-gray-600 text-sm mb-3">"Amazing product quality! Fast shipping and great customer service. Will definitely buy again!"</p>
                    <div class="flex items-center gap-2"><div class="w-8 h-8 bg-[#5B9F01] rounded-full flex items-center justify-center text-white text-sm font-bold">JD</div><div><h4 class="text-sm font-semibold text-gray-800">John Doe</h4><p class="text-xs text-gray-400">Verified Buyer</p></div></div>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-2 mb-2"><div class="text-yellow-400"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i></div><span class="text-gray-600 text-sm">4.5</span></div>
                    <p class="text-gray-600 text-sm mb-3">"Great selection of products. The prices are competitive and delivery was quick."</p>
                    <div class="flex items-center gap-2"><div class="w-8 h-8 bg-[#00349A] rounded-full flex items-center justify-center text-white text-sm font-bold">JS</div><div><h4 class="text-sm font-semibold text-gray-800">Jane Smith</h4><p class="text-xs text-gray-400">Verified Buyer</p></div></div>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-2 mb-2"><div class="text-yellow-400"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><span class="text-gray-600 text-sm">5.0</span></div>
                    <p class="text-gray-600 text-sm mb-3">"Excellent customer support! They helped me choose the right product for my needs."</p>
                    <div class="flex items-center gap-2"><div class="w-8 h-8 bg-[#FDCC02] rounded-full flex items-center justify-center text-gray-900 text-sm font-bold">MC</div><div><h4 class="text-sm font-semibold text-gray-800">Mike Chen</h4><p class="text-xs text-gray-400">Verified Buyer</p></div></div>
                </div>
            </div>
        </div>

        <!-- SECTION 6: Newsletter Subscription -->
        <div class="mb-8" x-data="{ showMsg: true }">
            <div class="bg-gradient-to-br from-[#01285F] via-[#00349B] to-[#328429] rounded-2xl p-8 text-center">
                <h3 class="text-white text-2xl font-bold mb-2">Stay in the Loop</h3>
                <p class="text-white/80 mb-6">Subscribe to get special offers, free giveaways, and exclusive deals.</p>

                <form wire:submit.prevent="subscribe" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                    <input type="email" wire:model.defer="subscribeEmail" placeholder="Enter your email address" class="bg-white flex-1 px-4 py-2 rounded-lg border-0 focus:outline-none focus:ring-2 focus:ring-[#5B9F01] <?php $__errorArgs = ['subscribeEmail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-2 ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" autocomplete="email">
                    <button type="submit" wire:loading.attr="disabled" wire:target="subscribe" class="bg-[#FDCC02] text-gray-900 px-6 py-2 rounded-lg font-semibold hover:bg-[#FFE44D] transition disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2 min-w-[130px]">
                        <span wire:loading.remove wire:target="subscribe">Subscribe</span>
                        <span wire:loading wire:target="subscribe" class="flex items-center gap-2"><svg class="animate-spin h-4 w-4 text-gray-900" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Sending...</span>
                    </button>
                </form>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['subscribeEmail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-300 text-sm mt-3"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('subscribe_success')): ?><p class="text-green-300 text-sm mt-3 font-medium"><i class="fas fa-check-circle mr-1"></i><?php echo e(session('subscribe_success')); ?></p><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('subscribe_info')): ?><p class="text-yellow-200 text-sm mt-3 font-medium"><i class="fas fa-info-circle mr-1"></i><?php echo e(session('subscribe_info')); ?></p><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('subscribe_error')): ?><p class="text-red-300 text-sm mt-3 font-medium"><i class="fas fa-exclamation-circle mr-1"></i><?php echo e(session('subscribe_error')); ?></p><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <p class="text-white/60 text-xs mt-4">No spam. Unsubscribe anytime.</p>
            </div>
        </div>

        <style>
            .scrollbar-hide::-webkit-scrollbar { display: none; }
            .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        </style>

        <!-- Alpine.js All Swipers Manager -->
        <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('bannerSwiper', () => ({
            swiper: null, reinitTimer: null,
            init() { this.$nextTick(() => { setTimeout(() => this.initSwiper(), 200); }); },
            initSwiper() {
                const el = this.$el;
                if (!el) return;
                if (this.swiper) { try { this.swiper.destroy(true, true); } catch(e) {} this.swiper = null; }
                const width = window.innerWidth;
                if (width <= 640) { el.style.height = '180px'; el.style.minHeight = '180px'; el.style.maxHeight = '220px'; }
                else if (width <= 1024) { el.style.height = '250px'; el.style.minHeight = '250px'; el.style.maxHeight = '350px'; }
                else { el.style.height = '100%'; el.style.minHeight = '300px'; el.style.maxHeight = '500px'; }
                const slides = el.querySelectorAll('.swiper-slide');
                const slideCount = slides.length;
                if (slideCount === 0) return;
                const enableLoop = slideCount > 1;
                this.swiper = new Swiper(el, {
                    slidesPerView: 1, spaceBetween: 0, loop: enableLoop,
                    autoplay: enableLoop ? { delay: 5000, disableOnInteraction: false, pauseOnMouseEnter: true } : false,
                    pagination: { el: '.banner-pagination', clickable: true },
                    navigation: { nextEl: '.banner-next', prevEl: '.banner-prev' },
                    effect: 'fade', fadeEffect: { crossFade: true },
                    observer: true, observeParents: true, watchOverflow: true,
                    on: { init: function() { setTimeout(() => this.update(), 100); } }
                });
            },
            reinit() { clearTimeout(this.reinitTimer); this.reinitTimer = setTimeout(() => this.initSwiper(), 300); }
        }));

        Alpine.data('swiperManager', (sliderClass) => ({
            swiper: null, sliderClass: sliderClass, config: {}, initTimer: null, resizeTimer: null,
            init() {
                this.buildConfig();
                this.$nextTick(() => { setTimeout(() => this.initSwiper(), 200); });
                window.addEventListener('resize', () => {
                    clearTimeout(this.resizeTimer);
                    this.resizeTimer = setTimeout(() => this.reinit(), 300);
                });
                document.addEventListener('wishlist-updated', () => this.reinit());
                document.addEventListener('cart-updated', () => this.reinit());
            },
            buildConfig() {
                if (this.sliderClass === 'bestSellerSlider') {
                    this.config = {
                        slidesPerView: 1,
                        spaceBetween: 15,
                        pagination: { el: '.bestSeller-pagination', clickable: true },
                        navigation: { nextEl: '.bestSeller-next', prevEl: '.bestSeller-prev' },
                        breakpoints: {
                            480:  { slidesPerView: 2, spaceBetween: 15 },
                            640:  { slidesPerView: 3, spaceBetween: 15 },
                            768:  { slidesPerView: 4, spaceBetween: 20 },
                            1024: { slidesPerView: 5, spaceBetween: 20 },
                            1280: { slidesPerView: 5, spaceBetween: 25 }
                        }
                    };
                } else if (this.sliderClass === 'featuredSlider') {
                    this.config = {
                        slidesPerView: 2,
                        spaceBetween: 15,
                        pagination: { el: '.featured-pagination', clickable: true },
                        breakpoints: {
                            480:  { slidesPerView: 2, spaceBetween: 15 },
                            640:  { slidesPerView: 2, spaceBetween: 15 },
                            768:  { slidesPerView: 3, spaceBetween: 20 },
                            1024: { slidesPerView: 4, spaceBetween: 20 }
                        }
                    };
                } else if (this.sliderClass.startsWith('productCategorySlider-')) {
                    let index = this.sliderClass.replace('productCategorySlider-', '');
                    this.config = {
                        slidesPerView: 2,
                        spaceBetween: 15,
                        pagination: { el: '.categoryProduct-pagination-' + index, clickable: true },
                        breakpoints: {
                            480:  { slidesPerView: 2, spaceBetween: 15 },
                            640:  { slidesPerView: 2, spaceBetween: 15 },
                            768:  { slidesPerView: 3, spaceBetween: 20 },
                            1024: { slidesPerView: 4, spaceBetween: 20 }
                        }
                    };
                }
            },
            initSwiper() {
                let slider = this.$refs.slider;
                if (!slider) {
                    console.warn(`[${this.sliderClass}] slider ref not found`);
                    return;
                }

                this.destroySwiper();

                let slidesCount = slider.querySelectorAll('.swiper-slide').length;
                console.log(`[${this.sliderClass}] Initializing with ${slidesCount} slides`);

                if (slidesCount === 0) return;

                const enableLoop = slidesCount > 2;
                const enableAutoplay = slidesCount > 1;

                let finalConfig = {
                    ...this.config,
                    observer: true,
                    observeParents: true,
                    observeSlideChildren: true,
                    watchOverflow: true,
                    speed: 600,
                    loop: enableLoop,
                    autoplay: enableAutoplay ? {
                        delay: 3000,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: true
                    } : false,
                    on: {
                        init: function() { setTimeout(() => this.update(), 100); }
                    }
                };

                if (finalConfig.breakpoints) {
                    Object.keys(finalConfig.breakpoints).forEach(bp => {
                        const s = finalConfig.breakpoints[bp];
                        if (s.slidesPerView && s.slidesPerView > slidesCount) {
                            s.slidesPerView = slidesCount;
                        }
                    });
                }

                if (finalConfig.slidesPerView && finalConfig.slidesPerView > slidesCount) {
                    finalConfig.slidesPerView = slidesCount;
                }

                if (slidesCount === 1) {
                    finalConfig.loop = false;
                    finalConfig.autoplay = false;
                    finalConfig.slidesPerView = 1;
                }

                try {
                    this.swiper = new Swiper(slider, finalConfig);
                    console.log(`[${this.sliderClass}] Swiper created. slidesPerView:`,
                        this.swiper.params.slidesPerView);
                } catch (e) {
                    console.error(`[${this.sliderClass}] Swiper init error:`, e);
                }
            },
            destroySwiper() {
                if (this.swiper) {
                    try { this.swiper.destroy(true, true); } catch(e) {}
                    this.swiper = null;
                }
                let slider = this.$refs.slider;
                if (slider) {
                    slider.removeAttribute('style');
                    let wrapper = slider.querySelector('.swiper-wrapper');
                    if (wrapper) wrapper.removeAttribute('style');
                    slider.querySelectorAll('.swiper-slide').forEach(slide => slide.removeAttribute('style'));
                }
            },
            reinit() {
                clearTimeout(this.initTimer);
                this.initTimer = setTimeout(() => {
                    if (this.$refs.slider && document.contains(this.$refs.slider)) this.initSwiper();
                }, 200);
            }
        }));
    });

    function reinitAllProductSwipers() {
        document.querySelectorAll('[x-data*="swiperManager"]').forEach(el => {
            if (el.__x && el.__x.$data && el.__x.$data.reinit) el.__x.$data.reinit();
        });
    }
    function reinitBannerSwiper() {
        const el = document.querySelector('.banner-slider');
        if (el && el.__x && el.__x.$data && el.__x.$data.reinit) el.__x.$data.reinit();
    }
    function reinitAllSwipers() { reinitAllProductSwipers(); reinitBannerSwiper(); }

    document.addEventListener('livewire:init', () => {
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('morph.updated', () => setTimeout(reinitAllSwipers, 200));
        }
    });
    document.addEventListener('lived', () => setTimeout(reinitAllSwipers, 200));
    document.addEventListener('livewire:update', () => setTimeout(reinitAllSwipers, 200));

    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => reinitAllSwipers(), 500);
    });
</script>
      </div>
   </div>
</div>
<?php /**PATH F:\Laravel\greenjackshop\resources\views/livewire/user/shop.blade.php ENDPATH**/ ?>