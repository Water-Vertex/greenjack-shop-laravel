@php
    $settings = \App\Models\Setting::find(1);
    $isLoggedIn = Session::has('customer_id');
@endphp
<!-- Header Start -->
<header class="bg-white shadow-sm z-50">
    <!-- Top Bar - Store Info & Currency/Lang/Account -->
    <div class="border-b border-gray-100 bg-[#5B9F01] hidden lg:block">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="flex items-center justify-between h-10 text-xs">
                <div class="text-white font-medium flex items-center gap-1">
                    <i class="fas fa-store mr-1 text-white"></i>
                    Welcome to Worldwide Electronics Store
                </div>
                <div class="flex items-center gap-5">
                    <a href="@if($settings){{$settings->facebook}}@endif" class="text-white transition text-md"><i class="fab fa-facebook-f"></i></a>
                    <a href="@if($settings){{$settings->twitter}}@endif" class="text-white transition text-md"><i class="fab fa-twitter"></i></a>
                    <a href="@if($settings){{$settings->instagram}}@endif" class="text-white transition text-md"><i class="fab fa-instagram"></i></a>
                    <a href="@if($settings){{$settings->youtube}}@endif" class="text-white transition text-md"><i class="fab fa-tiktok"></i></a>
                    <a href="@if($settings){{$settings->pintrest}}@endif" class="text-white transition text-md"><i class="fab fa-pinterest-p"></i></a>
                    <a href="@if($settings){{$settings->linkedin}}@endif" class="text-white transition text-md"><i class="fab fa-linkedin"></i></a>

                    <span class="text-white/30">|</span>

                    <!-- Login/My Account - Desktop Top Bar -->
                    @if($isLoggedIn)
                        <a href="{{route('repair.customer.account')}}" wire:navigate class="text-white hover:text-white/80 transition flex items-center gap-1.5">
                            <i class="fas fa-user-circle"></i>
                            <span>My Account</span>
                        </a>
                    @else
                        <a href="{{route('repair.customer.login')}}" wire:navigate class="text-white hover:text-white/80 transition flex items-center gap-1.5">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </a>
                        <span class="text-white/30">|</span>
                        <a href="{{route('repair.customer.registration')}}" wire:navigate class="text-white hover:text-white/80 transition flex items-center gap-1.5">
                            <i class="fas fa-user-plus"></i>
                            <span>Register</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header Area - Mobile optimized -->
    <div class="container mx-auto px-4 max-w-7xl py-4">
        <!-- Desktop Layout (lg and above) -->
        <div class="hidden lg:flex lg:flex-row lg:items-center lg:gap-4 lg:gap-6">
            <!-- Logo -->
            <div class="flex items-center justify-between w-full lg:w-auto">
                <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2">
                    <img src="@if($settings){{asset('storage/'.$settings->main_logo)}}@endif" alt="Logo" class="h-10 md:h-auto">
                </a>
            </div>

            <!-- Contact Information Cards - Desktop only -->
            <div class="grid grid-cols-3 gap-4 flex-1">
                <!-- Address Card -->
                <div class="contact-card group relative overflow-hidden bg-gradient-to-br from-gray-50 to-white rounded-2xl p-3 border border-gray-100 hover:border-[#5B9F01]/30 transition-all duration-300 hover:shadow-lg">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-[#5B9F01]/5 rounded-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="flex items-start gap-3 relative z-10">
                        <div class="icon-wrapper bg-gradient-to-br from-[#5B9F01] to-[#4a7f01] w-10 h-10 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-map-marker-alt text-white text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Visit Us</p>
                            <p class="text-sm font-semibold text-gray-800">@if($settings){{$settings->address}}@endif</p>
                        </div>
                    </div>
                </div>

                <!-- Email Card -->
                <div class="contact-card group relative overflow-hidden bg-gradient-to-br from-gray-50 to-white rounded-2xl p-3 border border-gray-100 hover:border-[#5B9F01]/30 transition-all duration-300 hover:shadow-lg">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-[#5B9F01]/5 rounded-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="flex items-start gap-3 relative z-10">
                        <div class="icon-wrapper bg-gradient-to-br from-[#5B9F01] to-[#4a7f01] w-10 h-10 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-envelope text-white text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Email Us</p>
                            <p class="text-sm font-semibold text-gray-800">@if($settings){{$settings->email}}@endif</p>
                        </div>
                    </div>
                </div>

                <!-- Phone Card -->
                <div class="contact-card group relative overflow-hidden bg-gradient-to-br from-gray-50 to-white rounded-2xl p-3 border border-gray-100 hover:border-[#5B9F01]/30 transition-all duration-300 hover:shadow-lg">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-[#5B9F01]/5 rounded-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="flex items-start gap-3 relative z-10">
                        <div class="icon-wrapper bg-gradient-to-br from-[#5B9F01] to-[#4a7f01] w-10 h-10 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-phone-alt text-white text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Call Us</p>
                            <p class="text-sm font-semibold text-gray-800">@if($settings){{$settings->phone}}@endif</p>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <!-- Mobile Layout (below lg) -->
        <div class="lg:hidden">
            <div class="flex items-center justify-between w-full">
                <button id="mobileMenuBtn" class="text-gray-700 text-2xl hover:text-[#5B9F01] transition">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <img src="{{asset('assets/images/logo/green-jack-logo-header.png')}}" alt="Logo" class="h-10">
                </a>
                <div class="flex items-center gap-3">
                    <!-- Login/My Account - Mobile Header -->
                    @if($isLoggedIn)
                        <a href="{{route('repair.customer.account')}}" wire:navigate class="text-gray-600 hover:text-[#5B9F01] transition relative">
                            <div class="relative">
                                <i class="far fa-user text-xl"></i>
                                <span class="absolute -top-1 -right-2 w-2 h-2 bg-green-500 rounded-full border-2 border-white"></span>
                            </div>
                        </a>
                    @else
                        <a href="{{route('repair.customer.login')}}" wire:navigate class="text-gray-600 hover:text-[#5B9F01] transition">
                            <i class="far fa-user text-xl"></i>
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Menu - Sticky on scroll -->
    <div class="menu-container border-t border-gray-200 bg-white w-full">
        <div class="container mx-auto px-4 max-w-7xl">
            <!-- Desktop Menu -->
            <div class="hidden lg:flex lg:flex-wrap lg:items-center lg:justify-between">
                <div class="flex items-center flex-wrap">
                    <a href="{{ route('home') }}" wire:navigate class="py-3 px-5 text-gray-700 font-semibold hover:text-[#5B9F01] border-b-2 border-transparent hover:border-[#5B9F01] transition whitespace-nowrap">Home</a>

                    <div class="relative repair-menu">
                        <a href="{{route('device-repair-page')}}" wire:navigate class="repair-btn py-3 px-5 text-gray-700 font-semibold hover:text-[#5B9F01] border-b-2 border-transparent hover:border-[#5B9F01] transition flex items-center gap-1 whitespace-nowrap">
                            Device Repair <i class="fas fa-chevron-down text-[10px] ml-1"></i>
                        </a>
                        <div class="repair-submenu absolute left-0 top-full mt-0 w-64 bg-white shadow-xl border border-gray-100 rounded-lg hidden z-50">
                            <div class="py-2">
                                @php
                                    $pages = \App\Models\Category::where('is_repairing_category', 1)->get();
                                @endphp
                                @foreach($pages as $page)
                                <a href="{{ route('device-repair', ['slug' => $page->slug]) }}" wire:navigate class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#5B9F01] whitespace-nowrap">{{$page->name}}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <a href="{{route('shop')}}" target="_blank" class="py-3 px-5 text-gray-700 font-semibold hover:text-[#5B9F01] border-b-2 border-transparent hover:border-[#5B9F01] transition whitespace-nowrap">Shop</a>

                    <div class="relative repair-menu">
                        <button class="repair-btn py-3 px-5 text-gray-700 font-semibold hover:text-[#5B9F01] border-b-2 border-transparent hover:border-[#5B9F01] transition flex items-center gap-1 whitespace-nowrap">
                           Printing & Marketing <i class="fas fa-chevron-down text-[10px] ml-1"></i>
                        </button>
                        <div class="repair-submenu absolute left-0 top-full mt-0 w-64 bg-white shadow-xl border border-gray-100 rounded-lg hidden z-50">
                            <div class="py-2">
                                <a href="{{route('printing')}}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#5B9F01] whitespace-nowrap">Document Printing</a>
                                <a href="{{route('printing')}}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#5B9F01] whitespace-nowrap">Business Cards</a>
                                <a href="{{route('printing')}}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#5B9F01] whitespace-nowrap">Signs, Banners, & Posters</a>
                                <a href="{{route('printing')}}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#5B9F01] whitespace-nowrap">Marketing Materials</a>
                                <a href="{{route('printing')}}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#5B9F01] whitespace-nowrap">Cards & Invitations</a>
                                <a href="{{route('printing')}}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#5B9F01] whitespace-nowrap">Label & Stickers</a>
                                <a href="{{route('printing')}}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#5B9F01] whitespace-nowrap">Envelope & Stationery</a>
                                <a href="{{route('printing')}}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#5B9F01] whitespace-nowrap">Photo Gifts</a>
                                <a href="{{route('printing')}}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#5B9F01] whitespace-nowrap">Business Solutions</a>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="py-3 px-5 text-gray-600 hover:text-[#5B9F01] font-semibold transition whitespace-nowrap">Finance</a>
                    <a href="{{route('blogs')}}" wire:navigate class="py-3 px-5 text-gray-700 font-semibold hover:text-[#5B9F01] border-b-2 border-transparent hover:border-[#5B9F01] transition whitespace-nowrap">Blogs</a>
                    <a href="{{ route('contact-us') }}" wire:navigate class="py-3 px-5 text-gray-600 hover:text-[#5B9F01] font-semibold transition whitespace-nowrap">Contact Us</a>
                </div>

                <span class="hidden lg:inline-block text-sm text-gray-500 py-3 whitespace-nowrap"><i class="fas fa-truck"></i> Free Shipping on Orders $50+</span>
            </div>
            <!-- Mobile Menu Toggle - Shows only on mobile -->
            <div class="lg:hidden py-3">
                <button id="mobileMenuToggle" class="flex items-center justify-between w-full text-gray-700 font-semibold">
                    <span>Menu</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Sidebar Menu -->
<div id="mobileSidebar" class="fixed top-0 left-0 h-full w-80 bg-white z-[9999] shadow-2xl transform -translate-x-full transition-transform duration-300 overflow-y-auto">
    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
        <div>
            <img src="{{asset('assets/images/logo/green-jack-logo-header.png')}}" alt="Logo" class="h-10">
        </div>
        <button id="closeSidebar" class="text-gray-500 text-2xl hover:text-gray-700">&times;</button>
    </div>

    <!-- User Info Section -->
    <div class="p-4 border-b border-gray-200 bg-gray-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#5B9F01] rounded-full flex items-center justify-center">
                <i class="fas fa-user text-white"></i>
            </div>
            <div>
                @if($isLoggedIn)
                    <p class="text-sm font-semibold text-gray-800">Welcome Back!</p>
                    <a href="{{route('repair.customer.account')}}" wire:navigate class="text-xs text-[#5B9F01] hover:underline">My Account</a>
                @else
                    <p class="text-sm font-semibold text-gray-800">Welcome Guest</p>
                    <a href="{{route('repair.customer.login')}}" wire:navigate class="text-xs text-[#5B9F01] hover:underline">Sign in / Register</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Navigation Links -->
    <div class="p-4">
        <a href="{{ route('home') }}" wire:navigate class="block py-3 text-gray-700 border-b border-gray-200 font-semibold hover:text-[#5B9F01]">Home</a>

        <div class="border-b border-gray-200">
            <button id="repairMobileBtn" class="flex justify-between items-center w-full py-3 text-gray-700 font-semibold hover:text-[#5B9F01]">
                Device Repair <i class="fas fa-chevron-down text-xs transition-transform"></i>
            </button>
            <div id="repairMobileSubmenu" class="hidden pl-4 pb-3 space-y-2">
                @php
                    $pages = \App\Models\Category::where('is_repairing_category', 1)->get();
                @endphp
                @foreach($pages as $page)
                <a href="{{ route('device-repair', ['slug' => $page->slug]) }}" wire:navigate class="block py-2 text-sm text-gray-600 hover:text-[#5B9F01]">{{$page->name}}</a>
                @endforeach
            </div>
        </div>

        <a href="{{route('shop')}}" target="_blank" class="block py-3 text-gray-700 border-b border-gray-200 font-semibold hover:text-[#5B9F01]">Shop</a>

        <div class="border-b border-gray-200">
            <button id="printingMobileBtn" class="flex justify-between items-center w-full py-3 text-gray-700 font-semibold hover:text-[#5B9F01]">
                Printing & Marketing <i class="fas fa-chevron-down text-xs transition-transform"></i>
            </button>
            <div id="printingMobileSubmenu" class="hidden pl-4 pb-3 space-y-2">
                <a href="{{route('printing')}}" wire:navigate class="block py-2 text-sm text-gray-600 hover:text-[#5B9F01]">Document Printing</a>
                <a href="{{route('printing')}}" class="block py-2 text-sm text-gray-600 hover:text-[#5B9F01]">Business Cards</a>
                <a href="{{route('printing')}}" wire:navigate class="block py-2 text-sm text-gray-600 hover:text-[#5B9F01]">Signs, Banners, & Posters</a>
                <a href="{{route('printing')}}" wire:navigate class="block py-2 text-sm text-gray-600 hover:text-[#5B9F01]">Marketing Materials</a>
                <a href="{{route('printing')}}" wire:navigate class="block py-2 text-sm text-gray-600 hover:text-[#5B9F01]">Cards & Invitations</a>
                <a href="{{route('printing')}}" wire:navigate class="block py-2 text-sm text-gray-600 hover:text-[#5B9F01]">Label & Stickers</a>
                <a href="{{route('printing')}}" wire:navigate class="block py-2 text-sm text-gray-600 hover:text-[#5B9F01]">Envelope & Stationery</a>
                <a href="{{route('printing')}}" wire:navigate class="block py-2 text-sm text-gray-600 hover:text-[#5B9F01]">Photo Gifts</a>
                <a href="{{route('printing')}}" wire:navigate class="block py-2 text-sm text-gray-600 hover:text-[#5B9F01]">Business Solutions</a>
            </div>
        </div>

        <a href="#" class="block py-3 text-gray-600 border-b border-gray-200 hover:text-[#5B9F01]">Finance</a>
        <a href="{{ route('contact-us') }}" wire:navigate class="block py-3 text-gray-600 border-b border-gray-200 hover:text-[#5B9F01]">Contact Us</a>
        <a href="{{ route('blogs') }}" wire:navigate class="block py-3 text-gray-600 border-b border-gray-200 font-semibold hover:text-[#5B9F01]">Blog</a>

      
    </div>

    <!-- Contact Details -->
    <div class="p-4 bg-gradient-to-r from-[#5B9F01]/10 to-transparent border-b border-gray-200">
        <div class="space-y-4">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-[#5B9F01] rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-map-marker-alt text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Visit Us</p>
                    <p class="text-sm font-medium text-gray-800">@if($settings){{$settings->address}}@endif</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-[#5B9F01] rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-envelope text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Email Us</p>
                    <p class="text-sm font-medium text-gray-800">@if($settings){{$settings->email}}@endif</p>
                    <p class="text-xs text-gray-500">24/7 Support</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-[#5B9F01] rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-phone-alt text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Call Us</p>
                    <p class="text-sm font-medium text-gray-800">@if($settings){{$settings->phone}}@endif</p>
                    <p class="text-xs text-gray-500">Mon-Fri 9am-6pm</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Login/Register Button in Contact Section -->
    <div class="p-4">
        @if($isLoggedIn)
            <a href="{{route('repair.customer.account')}}" wire:navigate class="flex items-center gap-3 w-full px-4 py-3 bg-[#5B9F01] text-white rounded-lg hover:bg-[#4a7f01] transition shadow-md">
                <i class="fas fa-user-circle text-lg"></i>
                <span class="font-medium">My Account</span>
                <i class="fas fa-arrow-right ml-auto"></i>
            </a>
        @else
            <a href="{{route('repair.customer.login')}}" wire:navigate class="flex items-center gap-3 w-full px-4 py-3 bg-[#5B9F01] text-white rounded-lg hover:bg-[#4a7f01] transition shadow-md">
                <i class="fas fa-sign-in-alt text-lg"></i>
                <span class="font-medium">Login / Register</span>
                <i class="fas fa-arrow-right ml-auto"></i>
            </a>
        @endif
    </div>
</div>

<div id="overlay" class="fixed inset-0 bg-black/50 z-[9998] hidden"></div>

<!-- jQuery and Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    // Desktop Repair Submenu - Hover
    $('.repair-menu').on('mouseenter', function(e) {
        $(this).find('.repair-submenu').stop(true, true).show();
    });
    $('.repair-menu').on('mouseleave', function(e) {
        $(this).find('.repair-submenu').stop(true, true).hide();
    });

    // Currency Dropdown
    $('.currency-btn').click(function(e) {
        e.stopPropagation();
        $('.currency-dropdown').toggle();
    });
    $(document).click(function() { $('.currency-dropdown').hide(); });
    $('.currency-dropdown').click(function(e) { e.stopPropagation(); });

    // Mobile Menu Sidebar
    $('#mobileMenuBtn, #mobileMenuToggle').click(function() {
        $('#mobileSidebar').addClass('translate-x-0').removeClass('-translate-x-full');
        $('#overlay').removeClass('hidden');
        $('body').css('overflow', 'hidden');
    });

    $('#closeSidebar, #overlay').click(function() {
        $('#mobileSidebar').addClass('-translate-x-full').removeClass('translate-x-0');
        $('#overlay').addClass('hidden');
        $('body').css('overflow', '');
    });

    // Mobile Submenu Toggles
    $('#repairMobileBtn').click(function() {
        $('#repairMobileSubmenu').slideToggle(200);
        $(this).find('i').toggleClass('rotate-180');
    });

    $('#printingMobileBtn').click(function() {
        $('#printingMobileSubmenu').slideToggle(200);
        $(this).find('i').toggleClass('rotate-180');
    });

    // Sticky Menu on Scroll
    var menuContainer = $('.menu-container');
    var header = $('header');
    var menuOffset = menuContainer.offset().top;

    $(window).on('scroll', function() {
        var scrollPos = $(window).scrollTop();

        if (scrollPos >= menuOffset) {
            menuContainer.addClass('fixed-menu');
            $('body').css('padding-top', menuContainer.outerHeight() + 'px');
        } else {
            menuContainer.removeClass('fixed-menu');
            $('body').css('padding-top', '0');
        }
    });
});
</script>

<style>
    .rotate-180 { transform: rotate(180deg); }
    .repair-submenu, .cart-dropdown, .currency-dropdown, #categoryDropdown {
        position: absolute;
        z-index: 9999;
    }
    .repair-menu {
        position: relative;
    }
    .repair-submenu {
        position: absolute;
        left: 0;
        top: 100%;
        min-width: 240px;
    }

    /* Contact Card Animations */
    .contact-card {
        transition: all 0.3s ease;
    }

    .contact-card:hover .icon-wrapper {
        transform: scale(1.1);
    }

    @keyframes subtlePulse {
        0%, 100% { opacity: 0.05; }
        50% { opacity: 0.1; }
    }

    .contact-card .absolute {
        animation: subtlePulse 3s ease-in-out infinite;
    }

    /* Fixed Menu Styles */
    .fixed-menu {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        z-index: 999;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Mobile Responsive */
    @media (max-width: 1023px) {
        .fixed-menu {
            position: fixed;
            top: 0;
        }
    }

    @media (max-width: 768px) {
        button, .repair-btn, #mobileMenuToggle {
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
        }

        #mobileSidebar {
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
