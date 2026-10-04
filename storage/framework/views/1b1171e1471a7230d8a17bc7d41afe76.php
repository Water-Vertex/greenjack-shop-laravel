<!-- Header Start -->
<?php
    $settings = \App\Models\Setting::find(1);
?>

<header class="bg-white shadow-sm sticky top-0 z-50">
    <!-- Top Bar - Minimal with only essentials -->
    <div class="border-b border-gray-100 bg-[#00349a] hidden lg:block">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="flex items-center justify-between h-8 text-xs">
                <div class="text-white flex items-center gap-4">
                    <span><i class="fas fa-check-circle text-[#5B9F01] mr-1 text-white"></i> Free Shipping on Orders $50+ </span>
                    <span><i class="far fa-clock text-[#5B9F01] mr-1 text-white"></i> 30-Day Returns</span>
                    <span><i class="fas fa-shield-alt text-[#5B9F01] mr-1 text-white"></i> Secure Checkout</span>
                </div>
                <div class="flex items-center gap-4">
                   <a href="<?php if($settings): ?><?php echo e($settings->facebook); ?><?php endif; ?>" class="text-white transition text-md"><i class="fab fa-facebook-f"></i></a>
                    <a href="<?php if($settings): ?><?php echo e($settings->twitter); ?><?php endif; ?>" class="text-white transition text-md"><i class="fab fa-twitter"></i></a>
                    <a href="<?php if($settings): ?><?php echo e($settings->instagram); ?><?php endif; ?>" class="text-white transition text-md"><i class="fab fa-instagram"></i></a>
                    <a href="<?php if($settings): ?><?php echo e($settings->youtube); ?><?php endif; ?>" class="text-white transition text-md"><i class="fab fa-tiktok"></i></a>
                    <a href="<?php if($settings): ?><?php echo e($settings->pintrest); ?><?php endif; ?>" class="text-white transition text-md"><i class="fab fa-pinterest-p"></i></a>
                    <a href="<?php if($settings): ?><?php echo e($settings->linkedin); ?><?php endif; ?>" class="text-white transition text-md"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header - Desktop -->
    <div class="container mx-auto px-4 max-w-7xl py-3">

        <!-- Desktop Layout -->
        <div class="hidden lg:flex lg:flex-row lg:items-center lg:gap-3">
            <!-- Logo -->
            <div class="flex items-center justify-between w-full md:w-auto">
                <a href="<?php echo e(route('shop')); ?>"  class="flex items-center gap-2">
                    <img src="<?php if($settings): ?><?php echo e(asset('storage/'.$settings->main_logo)); ?><?php endif; ?>" alt="Logo" class="h-20 md:h-20">
                </a>
            </div>

            <!-- Search Bar -->
            <div class="flex-1 w-full">
                 <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user.search');

$__key = null;

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-270159500-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key);

echo $__html;

unset($__html);
unset($__key);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </div>

            <!-- Header Icons -->
            <div class="flex items-center gap-4">
    <!-- Wishlist Icon -->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user.wishlist-icon');

$__key = null;

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-270159500-1', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key);

echo $__html;

unset($__html);
unset($__key);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

    <?php
        $isLoggedIn = Session::has('customer_id');
    ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isLoggedIn): ?>
        <!-- Logged In - Show Account Link -->
        <a href="<?php echo e(route('customer.account')); ?>"  class="hidden md:flex flex-col items-center text-gray-600 hover:text-[#5B9F01] transition relative group">
            <div class="relative">
                <i class="far fa-user text-xl md:text-2xl"></i> <!-- Added md:text-2xl -->
                <span class="absolute -top-1 -right-2 w-2 h-2 bg-green-500 rounded-full border-2 border-white"></span>
            </div>
            <span class="text-[10px] mt-0.5">Account</span>
        </a>
    <?php else: ?>
        <!-- Not Logged In - Show Login Link -->
        <a href="<?php echo e(route('customer.login')); ?>"  class="hidden md:flex flex-col items-center text-gray-600 hover:text-[#5B9F01] transition">
            <i class="far fa-user text-xl md:text-2xl"></i> <!-- Added md:text-2xl -->
            <span class="text-[10px] mt-1.5">Login</span>
        </a>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user.cart-icon');

$__key = null;

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-270159500-2', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key);

echo $__html;

unset($__html);
unset($__key);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</div>
        </div>

        <!-- Main row that contains both menu AND hero section -->
        <div class="hidden lg:flex flex-wrap relative">

            <!-- LEFT SIDE: All Departments Vertical Menu -->
            <div class="w-full sm:w-[270px] min-w-[270px] max-w-[270px] mt-2 hidden xl:block relative z-40">
                <div class="card border-0 shadow-sm rounded-lg relative overflow-visible">
                    <div class="card-header border-0 p-0">
                        <button type="button" id="categoriesToggleBtn" class="btn-link btn-remove-focus btn-block d-flex card-btn py-3 px-4 shadow-none bg-[#00349A] rounded border-0 font-weight-bold text-white items-center justify-between w-full hover:bg-[#00287a] transition">
                            <div class="flex items-center">
                                <span class="mr-2"><i class="fas fa-list-ul"></i></span>
                                <span class="text-center">Show All Categories</span>
                                <i class="fas fa-chevron-down text-sm ml-2 transition-transform duration-200" id="chevronIcon"></i>
                            </div>
                        </button>
                    </div>

                    <div id="categoriesCollapse" class="hidden absolute top-full left-0 w-[270px] z-[60]">
                        <div class="p-0 bg-white shadow-2xl rounded-b-lg border border-gray-200 overflow-hidden">
                            <nav class="w-full">
                                <ul class="flex flex-col max-h-[400px] overflow-y-auto">
                                    <?php
                                        $categories = \App\Models\Category::orderBy('position', 'asc')->get();
                                    ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="relative group border-b border-gray-100">
                                        <a href="<?php echo e(route('shop.by.category', $category->slug)); ?>"  class="flex items-center justify-between py-3 px-4 text-gray-700 hover:text-[#5B9F01] hover:bg-gray-50 transition">
                                            <span><?php echo e($category->name); ?></span>
                                        </a>
                                    </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE: Secondary Navigation -->
            <div class="flex-1 w-full relative mt-2 z-0">
                <nav class="w-full overflow-x-auto">
                    <ul class="flex items-center bg-white py-3 border-b border-gray-100 min-w-max">
                        <li class="relative group"><a href="<?php echo e(route('shop')); ?>"  class="text-red-600 font-semibold hover:text-[#5B9F01] transition px-3 whitespace-nowrap">Home</a></li>
                        <li class="text-gray-300 whitespace-nowrap">|</li>
                        <li class="relative group"><a href="<?php echo e(route('products')); ?>"  class="text-gray-700 hover:text-[#5B9F01] transition px-3 whitespace-nowrap">Shop</a></li>
                        <li class="text-gray-300 whitespace-nowrap">|</li>
                        <li class="relative group"><a href="<?php echo e(route('categories')); ?>"  class="text-gray-700 hover:text-[#5B9F01] transition px-3 whitespace-nowrap">Categories</a></li>
                        <li class="text-gray-300 whitespace-nowrap">|</li>


                        <li class="ml-auto hidden sm:block"><span class="text-gray-600 text-sm whitespace-nowrap"><i class="fas fa-truck text-[#5B9F01] mr-1"></i> Free Shipping on Orders $50+</span></li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Mobile Layout -->
        <div class="lg:hidden">
            <div class="flex items-center justify-between w-full">
                <button id="mobileMenuBtn" class="text-gray-700 text-2xl hover:text-[#5B9F01] transition">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="<?php echo e(route('shop')); ?>" class="flex items-center gap-2">
                    <img src="<?php if($settings): ?><?php echo e(asset('storage/'.$settings->main_logo)); ?><?php endif; ?>" alt="Logo" class="h-12">
                </a>
                <div class="flex items-center gap-3">
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user.wishlist-icon');

$__key = null;

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-270159500-3', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key);

echo $__html;

unset($__html);
unset($__key);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                    <?php
                        $isLoggedIn = Session::has('customer_id');
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isLoggedIn): ?>
                        <a href="<?php echo e(route('customer.account')); ?>"  class="text-gray-600 hover:text-[#5B9F01] transition">
                            <i class="far fa-user text-xl"></i>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('customer.login')); ?>"  class="text-gray-600 hover:text-[#5B9F01] transition">
                            <i class="far fa-user text-xl"></i>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user.cart-icon');

$__key = null;

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-270159500-4', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key);

echo $__html;

unset($__html);
unset($__key);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                </div>
            </div>

            <!-- Mobile Search -->
            <div class="mt-3">
               <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user.mobile-search');

$__key = null;

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-270159500-5', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key);

echo $__html;

unset($__html);
unset($__key);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </div>
        </div>
    </div>

    <!-- Simple Desktop Navigation -->
    <div class="border-y border-gray-100 bg-white w-full lg:block xl:hidden">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="flex flex-wrap relative">

                <div class="w-full sm:w-[270px] min-w-[270px] max-w-[270px] mt-2 hidden xl:block relative z-40">
                    <div class="card border-0 shadow-sm rounded-lg relative overflow-visible">
                        <div class="card-header border-0 p-0">
                            <button type="button" id="categoriesToggleBtnDesktop" class="btn-link btn-remove-focus btn-block d-flex card-btn py-3 px-4 shadow-none bg-[#00349A] rounded border-0 font-weight-bold text-white items-center justify-between w-full hover:bg-[#00287a] transition">
                                <div class="flex items-center">
                                    <span class="mr-2"><i class="fas fa-list-ul"></i></span>
                                    <span class="text-center">Show All Categories</span>
                                    <i class="fas fa-chevron-down text-sm ml-2 transition-transform duration-200" id="chevronIconDesktop"></i>
                                </div>
                            </button>
                        </div>

                        <div id="categoriesCollapseDesktop" class="hidden absolute top-full left-0 w-[270px] z-[60]">
                            <div class="p-0 bg-white shadow-2xl rounded-b-lg border border-gray-200 overflow-hidden">
                                <nav class="w-full">
                                    <ul class="flex flex-col max-h-[400px] overflow-y-auto">
                                        <?php
                                            $categories = \App\Models\Category::all();
                                        ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="relative group border-b border-gray-100">
                                            <a href="<?php echo e(route('shop.by.category', $category->slug)); ?>"  class="flex items-center justify-between py-3 px-4 text-gray-700 hover:text-[#5B9F01] hover:bg-gray-50 transition">
                                                <span><?php echo e($category->name); ?></span>
                                            </a>
                                        </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-1 w-full relative mt-2 z-0">
                    <nav class="w-full overflow-x-auto">
                        <ul class="flex items-center bg-white py-3 border-b border-gray-100 min-w-max">
                            <li class="relative group"><a href="<?php echo e(route('shop')); ?>"  class="text-red-600 font-semibold hover:text-[#5B9F01] transition px-3 whitespace-nowrap">Home</a></li>
                            <li class="text-gray-300 whitespace-nowrap">|</li>
                            <li class="relative group"><a href="<?php echo e(route('products')); ?>"  class="text-gray-700 hover:text-[#5B9F01] transition px-3 whitespace-nowrap">Shop</a></li>
                            <li class="text-gray-300 whitespace-nowrap">|</li>
                            <li class="relative group"><a href="<?php echo e(route('categories')); ?>"  class="text-gray-700 hover:text-[#5B9F01] transition px-3 whitespace-nowrap">Categories</a></li>
                            <li class="text-gray-300 whitespace-nowrap">|</li>



                            <li class="ml-auto hidden sm:block"><span class="text-gray-600 text-sm whitespace-nowrap"><i class="fas fa-truck text-[#5B9F01] mr-1"></i> Free Shipping on Orders $50+</span></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Sidebar Menu -->
<div id="mobileSidebar" class="fixed top-0 left-0 h-full w-80 max-w-[85%] bg-white z-[9999] shadow-2xl transform -translate-x-full transition-transform duration-300 overflow-y-auto">
    <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
        <div>
            <img src="<?php if($settings): ?><?php echo e(asset('storage/'.$settings->main_logo)); ?><?php endif; ?>" alt="Logo" class="h-10 md:h-10">
        </div>
        <button id="closeSidebar" class="text-gray-500 text-2xl hover:text-gray-700">&times;</button>
    </div>

    <!-- User Info -->
    <div class="p-4 border-b border-gray-200">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-gray-500"></i>
            </div>
            <div>
                <?php
                    $isLoggedIn = Session::has('customer_id');
                ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isLoggedIn): ?>
                    <p class="text-sm font-medium">Welcome Back!</p>
                    <a href="<?php echo e(route('customer.account')); ?>"  class="text-xs text-[#5B9F01]">My Account</a>
                <?php else: ?>
                    <p class="text-sm font-medium">Welcome Guest</p>
                    <a href="<?php echo e(route('customer.login')); ?>"  class="text-xs text-[#5B9F01]">Sign in / Register</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <div class="p-4">
        <a href="<?php echo e(route('shop')); ?>"  class="flex items-center gap-3 py-3 text-gray-700 border-b border-gray-200 hover:text-[#5B9F01] transition">
            <i class="fas fa-home w-5 text-gray-400"></i>
            <span>Home</span>
        </a>
        <a href="<?php echo e(route('products')); ?>"  class="flex items-center gap-3 py-3 text-gray-700 border-b border-gray-200 hover:text-[#5B9F01] transition">
            <i class="fas fa-store w-5 text-gray-400"></i>
            <span>Shop All</span>
        </a>
        
        <a href="<?php echo e(route('wishlist')); ?>"  class="flex items-center gap-3 py-3 text-gray-700 border-b border-gray-200 hover:text-[#5B9F01] transition">
            <i class="fas fa-heart w-5 text-gray-400"></i>
            <span>Wishlist</span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($wishlistCount) && $wishlistCount > 0): ?>
                <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full"><?php echo e($wishlistCount); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </a>

        <!-- Categories Accordion in Mobile -->
        <div class="border-b border-gray-200">
            <button id="mobileCategoriesToggle" class="flex items-center justify-between w-full py-3 text-gray-700 hover:text-[#5B9F01] transition">
                <div class="flex items-center gap-3">
                    <i class="fas fa-list-ul w-5 text-gray-400"></i>
                    <span>All Categories</span>
                </div>
                <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="mobileChevronIcon"></i>
            </button>
            <div id="mobileCategoriesMenu" class="hidden pl-8 pb-2">
                <?php
                    $categories = \App\Models\Category::all();
                ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('shop.by.category', $category->slug)); ?>"  class="flex items-center py-2 text-sm text-gray-600 hover:text-[#5B9F01] transition">
                        <span><?php echo e($category->name); ?></span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isLoggedIn): ?>
            <a href="<?php echo e(route('customer.account')); ?>"  class="flex items-center gap-3 py-3 text-gray-700 border-b border-gray-200 hover:text-[#5B9F01] transition">
                <i class="fas fa-user w-5 text-gray-400"></i>
                <span>My Account</span>
            </a>
            <a href="<?php echo e(route('customer.logout')); ?>"  class="flex items-center gap-3 py-3 text-gray-700 border-b border-gray-200 hover:text-red-600 transition">
                <i class="fas fa-sign-out-alt w-5 text-gray-400"></i>
                <span>Logout</span>
            </a>
        <?php else: ?>
            <a href="<?php echo e(route('customer.login')); ?>"  class="flex items-center gap-3 py-3 text-gray-700 border-b border-gray-200 hover:text-[#5B9F01] transition">
                <i class="fas fa-sign-in-alt w-5 text-gray-400"></i>
                <span>Login</span>
            </a>
            <a href="<?php echo e(route('customer.register')); ?>"  class="flex items-center gap-3 py-3 text-gray-700 border-b border-gray-200 hover:text-[#5B9F01] transition">
                <i class="fas fa-user-plus w-5 text-gray-400"></i>
                <span>Register</span>
            </a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>

    <!-- Contact Info -->
    <div class="p-4 bg-gray-50 mt-4">
        <p class="text-xs font-semibold text-gray-500 mb-2">CONTACT INFO</p>
        <div class="space-y-2">
            <a href="tel:<?php if($settings): ?><?php echo e($settings->phone); ?><?php endif; ?>" class="flex items-center gap-2 text-sm text-gray-600 hover:text-[#5B9F01] transition">
                <i class="fas fa-phone-alt text-[#5B9F01] text-xs"></i>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($settings): ?><?php echo e($settings->phone); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </a>
            <a href="mailto:<?php if($settings): ?><?php echo e($settings->email); ?><?php endif; ?>" class="flex items-center gap-2 text-sm text-gray-600 hover:text-[#5B9F01] transition">
                <i class="fas fa-envelope text-[#5B9F01] text-xs"></i>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($settings): ?><?php echo e($settings->email); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </a>
        </div>
    </div>
</div>

<div id="overlay" class="fixed inset-0 bg-black/50 z-[9998] hidden"></div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
(function () {

    function initHeaderScripts() {

        // Category dropdown
        const dropdownBtn = document.getElementById('categoryDropdownBtn');
        const dropdownMenu = document.getElementById('categoryDropdownMenu');

        if (dropdownBtn && dropdownMenu) {
            dropdownBtn.onclick = function (e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('hidden');
            };

            dropdownMenu.querySelectorAll('a').forEach(function (link) {
                link.onclick = function (e) {
                    e.preventDefault();
                    dropdownBtn.innerHTML = `${link.textContent} <i class="fas fa-chevron-down text-xs"></i>`;
                    dropdownMenu.classList.add('hidden');
                };
            });
        }

        // All Departments toggle (Main)
        const toggleBtn = document.getElementById('categoriesToggleBtn');
        const menu = document.getElementById('categoriesCollapse');
        const chevronIcon = document.getElementById('chevronIcon');

        if (toggleBtn && menu) {
            toggleBtn.onclick = function (e) {
                e.preventDefault();
                e.stopPropagation();
                const isHidden = menu.classList.contains('hidden');
                if (isHidden) {
                    menu.classList.remove('hidden');
                    if (chevronIcon) {
                        chevronIcon.style.transform = 'rotate(180deg)';
                    }
                } else {
                    menu.classList.add('hidden');
                    if (chevronIcon) {
                        chevronIcon.style.transform = 'rotate(0deg)';
                    }
                }
            };
        }

        // All Departments toggle (Desktop)
        const toggleBtnDesktop = document.getElementById('categoriesToggleBtnDesktop');
        const menuDesktop = document.getElementById('categoriesCollapseDesktop');
        const chevronIconDesktop = document.getElementById('chevronIconDesktop');

        if (toggleBtnDesktop && menuDesktop) {
            toggleBtnDesktop.onclick = function (e) {
                e.preventDefault();
                e.stopPropagation();
                const isHidden = menuDesktop.classList.contains('hidden');
                if (isHidden) {
                    menuDesktop.classList.remove('hidden');
                    if (chevronIconDesktop) {
                        chevronIconDesktop.style.transform = 'rotate(180deg)';
                    }
                } else {
                    menuDesktop.classList.add('hidden');
                    if (chevronIconDesktop) {
                        chevronIconDesktop.style.transform = 'rotate(0deg)';
                    }
                }
            };
        }

        // Mobile Categories Toggle
        const mobileCategoriesToggle = document.getElementById('mobileCategoriesToggle');
        const mobileCategoriesMenu = document.getElementById('mobileCategoriesMenu');
        const mobileChevronIcon = document.getElementById('mobileChevronIcon');

        if (mobileCategoriesToggle && mobileCategoriesMenu) {
            mobileCategoriesToggle.onclick = function (e) {
                e.preventDefault();
                e.stopPropagation();
                const isHidden = mobileCategoriesMenu.classList.contains('hidden');
                if (isHidden) {
                    mobileCategoriesMenu.classList.remove('hidden');
                    if (mobileChevronIcon) {
                        mobileChevronIcon.style.transform = 'rotate(180deg)';
                    }
                } else {
                    mobileCategoriesMenu.classList.add('hidden');
                    if (mobileChevronIcon) {
                        mobileChevronIcon.style.transform = 'rotate(0deg)';
                    }
                }
            };
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function (e) {
            // Main dropdown
            const toggleBtn = document.getElementById('categoriesToggleBtn');
            const menu = document.getElementById('categoriesCollapse');
            const chevronIcon = document.getElementById('chevronIcon');

            if (toggleBtn && menu) {
                if (!toggleBtn.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.add('hidden');
                    if (chevronIcon) {
                        chevronIcon.style.transform = 'rotate(0deg)';
                    }
                }
            }

            // Desktop dropdown
            const toggleBtnDesktop = document.getElementById('categoriesToggleBtnDesktop');
            const menuDesktop = document.getElementById('categoriesCollapseDesktop');
            const chevronIconDesktop = document.getElementById('chevronIconDesktop');

            if (toggleBtnDesktop && menuDesktop) {
                if (!toggleBtnDesktop.contains(e.target) && !menuDesktop.contains(e.target)) {
                    menuDesktop.classList.add('hidden');
                    if (chevronIconDesktop) {
                        chevronIconDesktop.style.transform = 'rotate(0deg)';
                    }
                }
            }

            // Mobile Categories dropdown
            const mobileToggle = document.getElementById('mobileCategoriesToggle');
            const mobileMenu = document.getElementById('mobileCategoriesMenu');
            if (mobileToggle && mobileMenu) {
                if (!mobileToggle.contains(e.target) && !mobileMenu.contains(e.target)) {
                    mobileMenu.classList.add('hidden');
                    const mobileChevron = document.getElementById('mobileChevronIcon');
                    if (mobileChevron) {
                        mobileChevron.style.transform = 'rotate(0deg)';
                    }
                }
            }

            // Currency dropdown
            $('.currency-dropdown').hide();
        });

        // Currency dropdown
        $('.currency-btn').off('click').on('click', function (e) {
            e.stopPropagation();
            $('.currency-dropdown').toggle();
        });

        $('.currency-dropdown').off('click').on('click', function (e) {
            e.stopPropagation();
        });

        // Mobile Sidebar
        $('#mobileMenuBtn').off('click').on('click', function () {
            $('#mobileSidebar').addClass('translate-x-0').removeClass('-translate-x-full');
            $('#overlay').removeClass('hidden');
            $('body').css('overflow', 'hidden');
        });

        $('#closeSidebar, #overlay').off('click').on('click', function () {
            $('#mobileSidebar').addClass('-translate-x-full').removeClass('translate-x-0');
            $('#overlay').addClass('hidden');
            $('body').css('overflow', '');
        });

        // Mobile Search
        $('#mobileSearchBtn').off('click').on('click', function() {
            const query = $('#mobileSearchInput').val().trim();
            if (query) {
                window.location.href = '<?php echo e(route("shop")); ?>?search=' + encodeURIComponent(query);
            }
        });

        $('#mobileSearchInput').off('keypress').on('keypress', function(e) {
            if (e.which === 13) {
                const query = $(this).val().trim();
                if (query) {
                    window.location.href = '<?php echo e(route("shop")); ?>?search=' + encodeURIComponent(query);
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initHeaderScripts);
    document.addEventListener('lived', initHeaderScripts);

})();
</script>

<style>
    .rotate-180 { transform: rotate(180deg); }
    #mobileSidebar { width: 85%; max-width: 340px; }
    @media (max-width: 768px) {
        button, a { cursor: pointer; -webkit-tap-highlight-color: transparent; }
    }
</style>
<?php /**PATH F:\Laravel\greenjackshop\resources\views/components/shop-header.blade.php ENDPATH**/ ?>