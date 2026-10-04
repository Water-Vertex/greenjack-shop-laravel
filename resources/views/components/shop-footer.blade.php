@php
    $settings = \App\Models\Setting::find(1);
    $categories = \App\Models\Category::get();
    $policies = \App\Models\Policy::where('is_repairing_policy', null)->get();
@endphp

<!-- Footer Start -->
<footer class="bg-[#1a1f2b] text-gray-300 border-t border-gray-700">
    <!-- Main Footer -->
    <div class="container mx-auto px-4 max-w-7xl py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-8">

            <!-- Column 1: Brand & Contact -->
            <div class="space-y-4">
                <a href="{{ route('shop') }}" >
                    <img src="@if($settings){{ asset('storage/'.$settings->footer_logo) }}@endif"
                         alt="Logo"
                         class="h-16 object-contain">
                </a>
                <p class="text-sm text-gray-400 leading-relaxed">
                    @if($settings){{ $settings->about }}@endif
                </p>
                <div class="space-y-2 text-sm">
                    <p class="flex items-center gap-2 text-gray-400">
                        <i class="fas fa-map-marker-alt text-[#5B9F01] w-5"></i>
                        <span>@if($settings){{ $settings->address }}@endif</span>
                    </p>
                    <p class="flex items-center gap-2 text-gray-400">
                        <i class="fas fa-phone-alt text-[#5B9F01] w-5"></i>
                        <span>@if($settings){{ $settings->phone }}@endif</span>
                    </p>
                    <p class="flex items-center gap-2 text-gray-400">
                        <i class="fas fa-envelope text-[#5B9F01] w-5"></i>
                        <span>@if($settings){{ $settings->email }}@endif</span>
                    </p>
                </div>
            </div>

            <!-- Column 2: Find it Fast -->
            <div>
                <h3 class="text-white font-semibold text-lg mb-4 flex items-center gap-2">
                    <span class="w-1 h-6 bg-[#5B9F01] rounded-full"></span>
                    Categories
                </h3>
                <ul class="space-y-2 text-sm">
                    @foreach($categories->take(4) as $category)
                        <li>
                            <a href="{{ route('shop.by.category', $category->slug) }}"
                            class="text-gray-400 hover:text-[#5B9F01] transition flex items-center gap-2">
                                <i class="fas fa-chevron-right text-[8px] text-[#5B9F01]"></i>
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                    @if($categories->count() > 4)
                        <a href="{{ route('categories') }}"
                        class="inline-flex items-center gap-2 text-sm text-[#5B9F01] hover:text-[#4a7f01] font-medium transition">
                            Show More
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    @endif
                </ul>
            </div>



            <!-- Column 4: Quick Links -->
            <div>
                <h3 class="text-white font-semibold text-lg mb-4 flex items-center gap-2">
                    <span class="w-1 h-6 bg-[#5B9F01] rounded-full"></span>
                    Quick Links
                </h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="{{ route('shop') }}"  class="text-gray-400 hover:text-[#5B9F01] transition flex items-center gap-2">
                            <i class="fas fa-chevron-right text-[8px] text-[#5B9F01]"></i>
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products') }}"  class="text-gray-400 hover:text-[#5B9F01] transition flex items-center gap-2">
                            <i class="fas fa-chevron-right text-[8px] text-[#5B9F01]"></i>
                            Shop
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('categories') }}"  class="text-gray-400 hover:text-[#5B9F01] transition flex items-center gap-2">
                            <i class="fas fa-chevron-right text-[8px] text-[#5B9F01]"></i>
                            Categories
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact-us') }}"  class="text-gray-400 hover:text-[#5B9F01] transition flex items-center gap-2">
                            <i class="fas fa-chevron-right text-[8px] text-[#5B9F01]"></i>
                            Contact Us
                        </a>
                    </li>

                    <!--<li>-->
                    <!--    <a href="sitemap.html"  target="_blank" class="text-gray-400 hover:text-[#5B9F01] transition flex items-center gap-2">-->
                    <!--        <i class="fas fa-chevron-right text-[8px] text-[#5B9F01]"></i>-->
                    <!--        HTML Sitemap-->
                    <!--    </a>-->
                    <!--</li>-->
                    <!--<li>-->
                    <!--    <a href="sitemap.xml" target="_blank" class="text-gray-400 hover:text-[#5B9F01] transition flex items-center gap-2">-->
                    <!--        <i class="fas fa-chevron-right text-[8px] text-[#5B9F01]"></i>-->
                    <!--        XML Sitemap-->
                    <!--    </a>-->
                    <!--</li>-->
                </ul>
            </div>

            <!-- Column 5: Follow Us / Payment Methods -->
            <div>
                <h3 class="text-white font-semibold text-lg mb-4 flex items-center gap-2">
                    <span class="w-1 h-6 bg-[#5B9F01] rounded-full"></span>
                    Follow Us
                </h3>
                <div class="flex flex-wrap gap-3 mb-6">
                    <a href="@if($settings){{ $settings->facebook }}@endif"
                       target="_blank"
                       class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center text-gray-300 hover:bg-[#5B9F01] hover:text-white transition-all duration-300">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="@if($settings){{ $settings->twitter }}@endif"
                       target="_blank"
                       class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center text-gray-300 hover:bg-[#5B9F01] hover:text-white transition-all duration-300">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="@if($settings){{ $settings->instagram }}@endif"
                       target="_blank"
                       class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center text-gray-300 hover:bg-[#5B9F01] hover:text-white transition-all duration-300">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="@if($settings){{ $settings->youtube }}@endif"
                       target="_blank"
                       class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center text-gray-300 hover:bg-[#5B9F01] hover:text-white transition-all duration-300">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="@if($settings){{ $settings->pintrest }}@endif"
                       target="_blank"
                       class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center text-gray-300 hover:bg-[#5B9F01] hover:text-white transition-all duration-300">
                        <i class="fab fa-pinterest-p"></i>
                    </a>
                    <a href="@if($settings){{ $settings->linkedin }}@endif"
                       target="_blank"
                       class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center text-gray-300 hover:bg-[#5B9F01] hover:text-white transition-all duration-300">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>

                <h3 class="text-white font-semibold text-lg mb-3 flex items-center gap-2">
                    <span class="w-1 h-6 bg-[#5B9F01] rounded-full"></span>
                    Payment Methods
                </h3>
                <div class="flex flex-wrap gap-2">
                    <img src="{{ asset('assets/images/footer/payment.png') }}"
                         alt="Payment Methods"
                         class="h-10 object-contain">
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Bar - Matches Header Top Bar -->
    <div class="border-t border-gray-700 bg-[#151a24]">
        <div class="container mx-auto px-4 max-w-7xl py-4">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-400">
                    <p>&copy; {{ date('Y') }} Green Jack Shop. All rights reserved.</p>
                </div>
                <div class="flex items-center gap-6 text-sm">
                    @foreach($policies as $policy)
                    <a href="{{route('policy',$policy->slug)}}" class="text-gray-400 hover:text-[#5B9F01] transition">{{$policy->title}}</a>
                    <span class="text-gray-600">|</span>
                    @endforeach
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <i class="fas fa-shield-alt text-[#5B9F01]"></i>
                    <span>Secure Checkout</span>
                    <span class="w-1 h-1 bg-gray-600 rounded-full"></span>
                    <i class="fas fa-lock text-[#5B9F01]"></i>
                    <span>SSL Encrypted</span>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Font Awesome (if not already included) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
