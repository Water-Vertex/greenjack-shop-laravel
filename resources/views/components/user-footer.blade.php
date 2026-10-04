@php
    $settings = \App\Models\Setting::find(1);
@endphp

<!-- Footer Start -->
<footer class="bg-[#1a1f2b] text-gray-300">

    <!-- Main Footer -->
    <div class="container mx-auto px-4 max-w-7xl py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">

            <!-- Column 1: Brand & Contact -->
            <div>
                <img src="@if($settings){{asset('storage/'.$settings->footer_logo)}}@endif" alt="Green Jack Logo" class="mb-4 max-h-16 w-auto">

                <!-- Yelp Review Badge -->
                <div class="mt-4">
                  <a href="https://www.yelp.com/biz/the-green-jack-columbus" target="_blank">
                      <img src="{{asset('assets/images/footer/yelp.png')}}" alt="Yelp Badge" class="mb-4">
                  </a>
                </div>

               
            </div>

            <!-- Column 2: Find it Fast -->
            <div>
                <h3 class="text-white font-semibold text-lg mb-4">Find it Fast</h3>
                <ul class="space-y-2 text-sm">
                    @php
                        $pages = \App\Models\Category::where('is_repairing_category', 1)->get();
                    @endphp
                    @foreach($pages as $page)
                        <li><a href="{{ route('device-repair', ['slug' => $page->slug]) }}" wire:navigate class="text-gray-400 hover:text-[#5B9F01] transition">{{$page->name}}</a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Column 3: Printing & Marketing -->
            <div>
                <h3 class="text-white font-semibold text-lg mb-4">Printing & Marketing</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{route('printing')}}" wire:navigate class="text-gray-400 hover:text-[#5B9F01] transition">Document Printing</a></li>
                    <li><a href="{{route('printing')}}" wire:navigate class="text-gray-400 hover:text-[#5B9F01] transition">Business Cards</a></li>
                    <li><a href="{{route('printing')}}" wire:navigate class="text-gray-400 hover:text-[#5B9F01] transition">Signs, Banners, & Posters</a></li>
                    <li><a href="{{route('printing')}}" wire:navigate class="text-gray-400 hover:text-[#5B9F01] transition">Marketing Materials</a></li>
                    <li><a href="{{route('printing')}}" wire:navigate class="text-gray-400 hover:text-[#5B9F01] transition">Cards & Invitations</a></li>
                    <li><a href="{{route('printing')}}" wire:navigate class="text-gray-400 hover:text-[#5B9F01] transition">Label & Stickers</a></li>
                    <li><a href="{{route('printing')}}" wire:navigate class="text-gray-400 hover:text-[#5B9F01] transition">Envelope & Stationery</a></li>
                    <li><a href="{{route('printing')}}" wire:navigate class="text-gray-400 hover:text-[#5B9F01] transition">Photo Gifts</a></li>
                    <li><a href="{{route('printing')}}" wire:navigate class="text-gray-400 hover:text-[#5B9F01] transition">Business Solutions</a></li>
                </ul>
            </div>

            <!-- Column 4: Quick Links -->
            <div>
                <h3 class="text-white font-semibold text-lg mb-4">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    @php
                        $policies = \App\Models\Policy::where('is_repairing_policy', 1)->get();
                    @endphp
                    @foreach($policies as $policy)
                        <li><a href="{{ url('policy/' . $policy->slug) }}" class="text-gray-400 hover:text-[#5B9F01] transition">{{$policy->title}}</a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Column 5: Follow Us / Payment Methods -->
            <div>
                <h3 class="text-white font-semibold text-lg mb-4">Follow Us</h3>
                <div class="flex gap-4 mb-6">
                    <a href="@if($settings){{$settings->facebook}}@endif" class="text-gray-400 hover:text-[#5B9F01] transition text-xl" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="@if($settings){{$settings->twitter}}@endif" class="text-gray-400 hover:text-[#5B9F01] transition text-xl" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="@if($settings){{$settings->instagram}}@endif" class="text-gray-400 hover:text-[#5B9F01] transition text-xl" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="@if($settings){{$settings->youtube}}@endif" class="text-gray-400 hover:text-[#5B9F01] transition text-xl" target="_blank"><i class="fab fa-youtube"></i></a>
                    <a href="@if($settings){{$settings->tiktok}}@endif" class="text-gray-400 hover:text-[#5B9F01] transition text-xl" target="_blank"><i class="fab fa-tiktok"></i></a>
                    <a href="@if($settings){{$settings->pinterest}}@endif" class="text-gray-400 hover:text-[#5B9F01] transition text-xl" target="_blank"><i class="fab fa-pinterest-p"></i></a>
                    <a href="@if($settings){{$settings->linkedin}}@endif" class="text-gray-400 hover:text-[#5B9F01] transition text-xl" target="_blank"><i class="fab fa-linkedin"></i></a>
                </div>

                <h3 class="text-white font-semibold text-lg mb-3">Payment Methods</h3>
                <div class="flex gap-3 flex-wrap items-center">
                    <img src="{{asset('assets/images/footer/payment.png')}}" alt="Payment Methods" class="object-contain max-h-8">
                </div>

               
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-gray-700 py-5">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="flex flex-col md:flex-row justify-between items-center text-center text-sm text-gray-400">
                <p>&copy; 2026 The Green Jack. All rights Reserved.</p>
                <div class="flex items-center space-x-4 mt-2 md:mt-0">
                    <span class="text-xs text-gray-600">Powered by</span>
                    <span class="text-xs font-semibold text-[#d32323]">Yelp</span>
                    <span class="text-xs text-gray-600">|</span>
                    <span class="text-xs text-gray-600">4.5 ★</span>
                    <span class="text-xs text-gray-600">|</span>
                    <a href="https://www.yelp.com/biz/the-green-jack-columbus" target="_blank" class="text-xs hover:text-[#d32323] transition-colors">
                        Leave a Review
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Yelp Badge CSS -->
<style>
    .yelp-badge {
        position: relative;
        overflow: hidden;
    }
    
    .yelp-badge::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        transform: rotate(25deg);
        transition: all 0.5s ease;
        opacity: 0;
    }
    
    .yelp-badge:hover::before {
        opacity: 1;
        transform: rotate(0deg);
    }
    
    .yelp-badge .fas.fa-star,
    .yelp-badge .fas.fa-star-half-alt {
        text-shadow: 0 0 8px rgba(255, 215, 0, 0.3);
    }
</style>