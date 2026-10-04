@section('meta')
@php $metas = \App\Models\PageMeta::find(1); @endphp
@if($metas)
    <title>{{ $metas->home_title }}</title>
    <meta name="title" content="{{ $metas->home_meta_title }}"/>
    <meta name="description" content="{{ $metas->home_meta_description }}"/>
    <meta name="keywords" content="{{ $metas->home_meta_keywords }}"/>
    <meta name="tags" content="{{ $metas->home_meta_tags }}"/>
    @if($metas->home_page_schema)
    <script type="application/ld+json">
        {!! is_array($metas->home_page_schema) ? json_encode($metas->home_page_schema) : $metas->home_page_schema !!}
    </script>
    @endif
@endif
@endsection

<div>
    <!-- Hero Banner - Stronger Blur Effect -->
    <section class="relative h-[400px] md:h-[500px] flex items-center overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0">
            <img src="{{asset('assets/images/logo/background.jpg')}}" alt="Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>
            <div class="absolute inset-0 bg-white/5"></div>
        </div>

        <!-- Content -->
        <div class="container mx-auto px-4 max-w-7xl relative z-10 w-full">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-12">
                <div class="flex-1 text-center lg:text-left">
                    <div class="inline-block lg:block mb-5">
                        <span class="backdrop-blur-md bg-[#5B9F01]/20 text-[#5B9F01] font-bold text-sm uppercase tracking-wider px-4 py-2 rounded-full inline-flex items-center gap-2 border border-[#5B9F01]/30">
                            <span class="w-2 h-2 bg-[#5B9F01] rounded-full animate-pulse"></span>
                            Book your service today!
                        </span>
                    </div>

                    <h1 class="text-white text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-extrabold leading-tight mb-5 drop-shadow-2xl">
                        We repair
                        <span class="block text-2xl md:text-3xl lg:text-4xl mt-3 text-transparent bg-clip-text bg-gradient-to-r from-[#5B9F01] to-[#8BC34A]">
                            iPhones, iPads, Tablets, Computers,<br>
                            iMacs, Android Phones, and much more.
                        </span>
                    </h1>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{route('device-repair-page')}}" class="bg-[#5B9F01] hover:bg-[#4a7f01] text-white font-semibold px-8 py-3.5 rounded-full transition duration-300 inline-flex items-center justify-center gap-2 shadow-lg shadow-black/40">
                            Discover More <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="{{route('contact-us')}}" class="backdrop-blur-md bg-white/10 hover:bg-white/20 border border-white/30 text-white font-semibold px-8 py-3.5 rounded-full transition duration-300 inline-flex items-center justify-center gap-2">
                            <i class="fas fa-phone"></i> Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Retail Categories Section -->
    <section class="py-16 bg-white overflow-hidden">
        <div class="container mx-auto px-4 max-w-7xl">
            <!-- Section Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 bg-[#5B9F01]/10 px-4 py-1.5 rounded-full mb-4">
                    <span class="w-2 h-2 bg-[#5B9F01] rounded-full animate-pulse"></span>
                    <span class="text-[#5B9F01] font-semibold text-sm uppercase tracking-wider">Shop by Category</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800">
                    Browse Our Repair Device Categories
                </h2>
                <div class="w-20 h-1 bg-[#5B9F01] mx-auto mt-4 rounded-full"></div>
                <p class="text-gray-500 mt-4 max-w-2xl mx-auto">
                    Explore our wide range of repair services across different devices
                </p>
            </div>

            <!-- Auto Scrolling Container -->
            <div class="relative">
                <div class="absolute left-0 top-0 bottom-0 w-32 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>
                <div class="absolute right-0 top-0 bottom-0 w-32 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>

                <div class="overflow-hidden py-4">
                    <div class="category-track flex items-center gap-6 animate-scroll-slow hover:animate-pause">
                        @php
                            $retailCategories = \App\Models\Category::where('is_repairing_category', 1)->get();
                        @endphp

                        @foreach($retailCategories as $category)
                            <a href="{{ route('device-repair', ['slug' => $category->slug]) }}"
                               wire:navigate
                               class="category-card bg-white shadow-md rounded-xl hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden group min-w-[160px]">
                                <div class="p-4 flex flex-col items-center justify-center">
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}"
                                             alt="{{ $category->name }}"
                                             class="h-24 w-24 object-contain transition duration-300 group-hover:scale-105">
                                    @else
                                        <div class="h-24 w-24 bg-gradient-to-br from-[#5B9F01]/20 to-[#5B9F01]/5 rounded-full flex items-center justify-center">
                                            <i class="fas fa-microchip text-3xl text-[#5B9F01]/40"></i>
                                        </div>
                                    @endif
                                    <h3 class="font-semibold text-gray-800 text-sm mt-3 text-center group-hover:text-[#5B9F01] transition-colors">
                                        {{ $category->name }}
                                    </h3>
                                    <p class="text-xs text-[#5B9F01] mt-1">Get Free Quote</p>
                                </div>
                            </a>
                        @endforeach

                        @foreach($retailCategories as $category)
                            <a href="{{ route('device-repair', ['slug' => $category->slug]) }}"
                               wire:navigate
                               class="category-card bg-white shadow-md rounded-xl hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden group min-w-[160px]">
                                <div class="p-4 flex flex-col items-center justify-center">
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}"
                                             alt="{{ $category->name }}"
                                             class="h-24 w-24 object-contain transition duration-300 group-hover:scale-105">
                                    @else
                                        <div class="h-24 w-24 bg-gradient-to-br from-[#5B9F01]/20 to-[#5B9F01]/5 rounded-full flex items-center justify-center">
                                            <i class="fas fa-microchip text-3xl text-[#5B9F01]/40"></i>
                                        </div>
                                    @endif
                                    <h3 class="font-semibold text-gray-800 text-sm mt-3 text-center group-hover:text-[#5B9F01] transition-colors">
                                        {{ $category->name }}
                                    </h3>
                                    <p class="text-xs text-[#5B9F01] mt-1">Get Free Quote</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!--<div class="text-center mt-12">-->
            <!--    <a href="{{ route('shop') }}"-->
            <!--       wire:navigate-->
            <!--       class="inline-flex items-center gap-2 bg-[#5B9F01] hover:bg-[#4a7f01] text-white font-semibold px-8 py-3 rounded-full transition duration-300 shadow-md hover:shadow-lg">-->
            <!--        View All Categories <i class="fas fa-arrow-right text-sm"></i>-->
            <!--    </a>-->
            <!--</div>-->
        </div>
    </section>

    <!-- Brands Section -->
    <section class="py-16 bg-white overflow-hidden">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="text-center mb-12">
                <span class="text-[#5B9F01] font-bold text-sm uppercase tracking-wider bg-[#5B9F01]/10 px-4 py-1.5 rounded-full inline-block mb-4">OUR TRUSTED PARTNER</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800">The Brands we work with <br> and believe in...</h2>
                <div class="w-20 h-1 bg-[#5B9F01] mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="relative">
                <div class="absolute left-0 top-0 bottom-0 w-32 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>
                <div class="absolute right-0 top-0 bottom-0 w-32 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>

                <div class="overflow-hidden py-4">
                    <div class="brand-track flex items-center gap-8 animate-scroll-slow hover:animate-pause">
                        <div class="brand-card bg-white shadow-md rounded-xl px-6 py-4 flex items-center justify-center min-w-[140px] hover:shadow-lg transition-shadow duration-300 border border-gray-100">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b8/Lenovo_logo_2015.svg" alt="Lenovo" class="h-10 w-auto object-contain transition duration-300">
                        </div>
                        <div class="brand-card bg-white shadow-md rounded-xl px-6 py-4 flex items-center justify-center min-w-[140px] hover:shadow-lg transition-shadow duration-300 border border-gray-100">
                            <img src="{{asset('assets/images/brands/sony.png')}}" alt="Sony" class="h-10 w-auto object-contain transition duration-300">
                        </div>
                        <div class="brand-card bg-white shadow-md rounded-xl px-6 py-4 flex items-center justify-center min-w-[140px] hover:shadow-lg transition-shadow duration-300 border border-gray-100">
                            <img src="{{asset('assets/images/brands/samsung.png')}}" alt="Samsung" class="h-10 w-auto transition duration-300">
                        </div>
                        <div class="brand-card bg-white shadow-md rounded-xl px-6 py-4 flex items-center justify-center min-w-[140px] hover:shadow-lg transition-shadow duration-300 border border-gray-100">
                            <img src="{{asset('assets/images/brands/apple.png')}}" alt="Apple" class="h-10 w-auto object-contain transition duration-300">
                        </div>
                        <div class="brand-card bg-white shadow-md rounded-xl px-6 py-4 flex items-center justify-center min-w-[140px] hover:shadow-lg transition-shadow duration-300 border border-gray-100">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/1/18/Dell_logo_2016.svg" alt="Dell" class="h-10 w-auto object-contain transition duration-300">
                        </div>
                        <div class="brand-card bg-white shadow-md rounded-xl px-6 py-4 flex items-center justify-center min-w-[140px] hover:shadow-lg transition-shadow duration-300 border border-gray-100">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/HP_logo_2012.svg" alt="HP" class="h-10 w-auto object-contain transition duration-300">
                        </div>
                        <div class="brand-card bg-white shadow-md rounded-xl px-6 py-4 flex items-center justify-center min-w-[140px] hover:shadow-lg transition-shadow duration-300 border border-gray-100">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b8/Lenovo_logo_2015.svg" alt="Lenovo" class="h-10 w-auto object-contain transition duration-300">
                        </div>
                        <div class="brand-card bg-white shadow-md rounded-xl px-6 py-4 flex items-center justify-center min-w-[140px] hover:shadow-lg transition-shadow duration-300 border border-gray-100">
                            <img src="{{asset('assets/images/brands/sony.png')}}" alt="Sony" class="h-10 w-auto object-contain transition duration-300">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Services Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 bg-[#5B9F01]/10 px-4 py-1.5 rounded-full mb-4">
                    <span class="w-2 h-2 bg-[#5B9F01] rounded-full animate-pulse"></span>
                    <span class="text-[#5B9F01] font-semibold text-sm uppercase tracking-wider">Our Services</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800">See Our Services</h2>
                <div class="w-20 h-1 bg-[#5B9F01] mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="service-card group bg-white rounded-2xl p-6 text-center shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                    <div class="service-icon mb-5">
                        <div class="w-20 h-20 bg-[#5B9F01]/10 rounded-2xl flex items-center justify-center mx-auto group-hover:bg-[#5B9F01] transition-colors duration-300">
                            <img src="http://technician.watervertex.com/wp-content/uploads/2026/03/Untitled-design-1.png" alt="Repair Icon" class="w-12 h-12 object-contain group-hover:brightness-0 group-hover:invert transition duration-300">
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">
                        <a href="#" class="hover:text-[#5B9F01] transition">Repair</a>
                    </h3>
                    <p class="text-gray-500 text-sm leading-relaxed">When you need fast repairs for your iPhone, iPad, tablet, computer, game console, or any other device, trust the experts at The Green Jack.</p>
                </div>

                <div class="service-card group bg-white rounded-2xl p-6 text-center shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                    <div class="service-icon mb-5">
                        <div class="w-20 h-20 bg-[#5B9F01]/10 rounded-2xl flex items-center justify-center mx-auto group-hover:bg-[#5B9F01] transition-colors duration-300">
                            <img src="http://technician.watervertex.com/wp-content/uploads/2026/03/Untitled-design-3.png" alt="Buy Devices Icon" class="w-12 h-12 object-contain group-hover:brightness-0 group-hover:invert transition duration-300">
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">
                        <a href="#" class="hover:text-[#5B9F01] transition">Buy a Devices</a>
                    </h3>
                    <p class="text-gray-500 text-sm leading-relaxed">At The Green Jack, get the latest pre-owned devices without paying premium prices—iPhones, iPads, tablets, Samsung Galaxy devices.</p>
                </div>

                <div class="service-card group bg-white rounded-2xl p-6 text-center shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                    <div class="service-icon mb-5">
                        <div class="w-20 h-20 bg-[#5B9F01]/10 rounded-2xl flex items-center justify-center mx-auto group-hover:bg-[#5B9F01] transition-colors duration-300">
                            <img src="http://technician.watervertex.com/wp-content/uploads/2026/03/Untitled-design-4.png" alt="Trade & Sell Icon" class="w-12 h-12 object-contain group-hover:brightness-0 group-hover:invert transition duration-300">
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">
                        <a href="#" class="hover:text-[#5B9F01] transition">Trade & Sell</a>
                    </h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Let The Green Jack turn your old or damaged phone into cash and help you find a device you'll love. You've got options—sell or trade!</p>
                </div>

                <div class="service-card group bg-white rounded-2xl p-6 text-center shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                    <div class="service-icon mb-5">
                        <div class="w-20 h-20 bg-[#5B9F01]/10 rounded-2xl flex items-center justify-center mx-auto group-hover:bg-[#5B9F01] transition-colors duration-300">
                            <img src="http://technician.watervertex.com/wp-content/uploads/2026/03/Untitled-design.png" alt="Finance Icon" class="w-12 h-12 object-contain group-hover:brightness-0 group-hover:invert transition duration-300">
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">
                        <a href="#" class="hover:text-[#5B9F01] transition">Finance</a>
                    </h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Get the device you've always wanted today—for as low as $80 USD! Including iPhones, Samsung Galaxy devices, iPads, tablets, game systems.</p>
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="{{route('printing')}}" class="inline-flex items-center gap-2 bg-[#5B9F01] hover:bg-[#4a7f01] text-white font-semibold px-8 py-3 rounded-full transition duration-300 shadow-md hover:shadow-lg">
                    Explore All Services <i class="fas fa-arrow-right text-sm"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- iPhone Repair Services Section -->
    <section class="py-10 bg-white">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
                <div class="flex-1 relative group">
                    <div class="rounded-2xl overflow-hidden shadow-xl relative">
                        <img src="http://technician.watervertex.com/wp-content/uploads/2026/03/Mobile-Repair-Optimized.webp" alt="iPhone Repair Services" class="w-full h-auto object-cover rounded-2xl">
                    </div>
                </div>
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 bg-[#5B9F01]/10 px-4 py-1.5 rounded-full mb-4">
                        <span class="w-2 h-2 bg-[#5B9F01] rounded-full animate-pulse"></span>
                        <span class="text-[#5B9F01] font-semibold text-sm uppercase tracking-wider">Expert Service</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">iPhone Repair Services</h2>
                    <p class="text-gray-500 leading-relaxed mb-6">When you need fast, professional iPhone repair that won't break the bank, trust The Green Jack. From cracked screens to liquid damage, our skilled technicians have years of experience handling all common iPhone issues.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-[#5B9F01] text-sm"></i><span class="text-gray-600 text-sm">Cracked Screen Repair</span></div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-[#5B9F01] text-sm"></i><span class="text-gray-600 text-sm">Battery Replacement</span></div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-[#5B9F01] text-sm"></i><span class="text-gray-600 text-sm">Water Damage Repair</span></div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-[#5B9F01] text-sm"></i><span class="text-gray-600 text-sm">Charging Port Fix</span></div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-[#5B9F01] text-sm"></i><span class="text-gray-600 text-sm">Camera Repair</span></div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-[#5B9F01] text-sm"></i><span class="text-gray-600 text-sm">Speaker & Mic Repair</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Computers & Workstation Services Section -->
    <section class="py-10 bg-gray-50">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 bg-[#5B9F01]/10 px-4 py-1.5 rounded-full mb-4">
                        <span class="w-2 h-2 bg-[#5B9F01] rounded-full animate-pulse"></span>
                        <span class="text-[#5B9F01] font-semibold text-sm uppercase tracking-wider">Expert Service</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Computers & Workstation Services</h2>
                    <div class="w-16 h-1 bg-[#5B9F01] rounded-full mb-6"></div>

                    <p class="text-gray-500 leading-relaxed mb-6">
                        At The Green Jack, we provide professional computer and workstation repair services for desktops, custom-built PCs, gaming systems, and business workstations. Our certified technicians diagnose and resolve hardware and software issues quickly, helping you get back to work with minimal downtime.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-[#5B9F01] text-sm"></i>
                            <span class="text-gray-600 text-sm">Hardware Diagnostics & Repair</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-[#5B9F01] text-sm"></i>
                            <span class="text-gray-600 text-sm">SSD & RAM Upgrades</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-[#5B9F01] text-sm"></i>
                            <span class="text-gray-600 text-sm">Motherboard Repair</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-[#5B9F01] text-sm"></i>
                            <span class="text-gray-600 text-sm">Virus & Malware Removal</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-[#5B9F01] text-sm"></i>
                            <span class="text-gray-600 text-sm">Data Recovery & Backup</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-[#5B9F01] text-sm"></i>
                            <span class="text-gray-600 text-sm">Operating System Installation</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-[#5B9F01] text-sm"></i>
                            <span class="text-gray-600 text-sm">Workstation Performance Optimization</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-[#5B9F01] text-sm"></i>
                            <span class="text-gray-600 text-sm">1-Year Service Warranty</span>
                        </div>
                    </div>
                </div>
                <div class="flex-1 relative group">
                    <div class="rounded-2xl overflow-hidden shadow-xl relative">
                        <img src="{{asset('assets/images/home/computer.png')}}" alt="Computers Repair Services" class="w-full h-auto object-cover rounded-2xl">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Green Jack Section -->
    <section class="py-10 bg-white">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
                <div class="flex-1 relative group">
                    <div class="rounded-2xl overflow-hidden shadow-xl relative">
                        <img src="http://technician.watervertex.com/wp-content/uploads/2026/03/pngtree-phone-repair-service-pro-png-image_15060154.png" alt="Cell Phone Repair Services" class="w-full h-auto object-cover rounded-2xl">
                    </div>
                </div>
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 bg-[#5B9F01]/10 px-4 py-1.5 rounded-full mb-4">
                        <span class="w-2 h-2 bg-[#5B9F01] rounded-full animate-pulse"></span>
                        <span class="text-[#5B9F01] font-semibold text-sm uppercase tracking-wider"></span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Why Choose Green Jack?<br></h2>
                    <div class="w-16 h-1 bg-[#5B9F01] rounded-full mb-6"></div>
                    <p class="text-gray-500 leading-relaxed mb-6">Small businesses can enroll in our Yearly IT Service Subscription, which includes ongoing technical support, system maintenance, security monitoring, troubleshooting, and priority service to help minimize downtime and keep operations running efficiently.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-[#5B9F01] text-sm"></i><span class="text-gray-600 text-sm">Fast Response Times</span></div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-[#5B9F01] text-sm"></i><span class="text-gray-600 text-sm">Affordable Pricing</span></div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-[#5B9F01] text-sm"></i><span class="text-gray-600 text-sm">Professional Technicians</span></div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-[#5B9F01] text-sm"></i><span class="text-gray-600 text-sm">Residential & Business Support</span></div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-[#5B9F01] text-sm"></i><span class="text-gray-600 text-sm">Annual IT Maintenance Plans</span></div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-[#5B9F01] text-sm"></i><span class="text-gray-600 text-sm">Remote & On-Site Service</span></div>
                    </div>
                    <p class="text-gray-500 leading-relaxed mb-6">Technology Made Simple. Support You Can Trust. Contact Green Jack IT Services today and ask about our Annual Business IT Support Plans designed to save your company time and money</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Reliable IT Support Section -->
    <section class="py-10 bg-gray-50">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 bg-[#5B9F01]/10 px-4 py-1.5 rounded-full mb-4">
                        <span class="w-2 h-2 bg-[#5B9F01] rounded-full animate-pulse"></span>
                        <span class="text-[#5B9F01] font-semibold text-sm uppercase tracking-wider">Why Choosing Our Services</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Reliable IT Support <br><span class="text-[#5B9F01]">Homes & Small Businesses</span></h2>
                    <div class="w-16 h-1 bg-[#5B9F01] rounded-full mb-6"></div>
                    <div class="space-y-4 text-gray-500 leading-relaxed">
                        <p>Green Jack IT Services provides professional, affordable, and dependable technology solutions for residential customers and small businesses. From computer repairs and network setup to cybersecurity and ongoing IT support, we keep your technology running smoothly.</p>
                        <p><strong class="text-gray-700">Our Services:</strong></p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-6">
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-[#5B9F01] text-sm"></i><span class="text-gray-600 text-sm">Computer & Laptop Repair</span></div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-[#5B9F01] text-sm"></i><span class="text-gray-600 text-sm">Small Business IT Support</span></div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-[#5B9F01] text-sm"></i><span class="text-gray-600 text-sm">Network & Wi-Fi Setup</span></div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-[#5B9F01] text-sm"></i><span class="text-gray-600 text-sm">Virus & Malware Removal</span></div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-[#5B9F01] text-sm"></i><span class="text-gray-600 text-sm">Data Backup & Recovery</span></div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-[#5B9F01] text-sm"></i><span class="text-gray-600 text-sm">Microsoft 365 Support</span></div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-[#5B9F01] text-sm"></i><span class="text-gray-600 text-sm">Printer & Device Setup</span></div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-[#5B9F01] text-sm"></i><span class="text-gray-600 text-sm">Remote & On-Site Assistance</span></div>
                    </div>
                </div>
                <div class="flex-1">
                    <a href="{{route('contact-us')}}" wire:navigate>
                        <div class="bg-gradient-to-br from-[#5B9F01]/10 to-[#5B9F01]/5 rounded-2xl p-8 text-center">
                            <i class="fas fa-tools text-6xl text-[#5B9F01] mb-4"></i>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Need Urgent Repair?</h3>
                            <p class="text-gray-600">Contact us today for fast, reliable service!</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Counter/Stats Section -->
    <section class="py-16 bg-[#5B9F01]">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center border-r border-white/20 last:border-r-0 lg:border-r">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white/15 rounded-full mb-4">
                        <i class="fas fa-laptop-code text-2xl text-white"></i>
                    </div>
                    <div class="text-4xl md:text-5xl font-bold text-white mb-2">1.25<span class="text-xl">k</span></div>
                    <p class="text-white/80 text-sm uppercase tracking-wider">Successful Devices</p>
                </div>
                <div class="text-center border-r border-white/20 last:border-r-0 lg:border-r">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white/15 rounded-full mb-4">
                        <i class="fas fa-smile text-2xl text-white"></i>
                    </div>
                    <div class="text-4xl md:text-5xl font-bold text-white mb-2">1.24<span class="text-xl">k</span></div>
                    <p class="text-white/80 text-sm uppercase tracking-wider">Satisfied Customers</p>
                </div>
                <div class="text-center border-r border-white/20 last:border-r-0 lg:border-r">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white/15 rounded-full mb-4">
                        <i class="fas fa-user-tie text-2xl text-white"></i>
                    </div>
                    <div class="text-4xl md:text-5xl font-bold text-white mb-2">20<span class="text-xl">+</span></div>
                    <p class="text-white/80 text-sm uppercase tracking-wider">Expert Technicians</p>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white/15 rounded-full mb-4">
                        <i class="fas fa-medal text-2xl text-white"></i>
                    </div>
                    <div class="text-4xl md:text-5xl font-bold text-white mb-2">100<span class="text-xl">%</span></div>
                    <p class="text-white/80 text-sm uppercase tracking-wider">Quality Products</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 bg-[#5B9F01]/10 px-4 py-1.5 rounded-full mb-4">
                    <span class="w-2 h-2 bg-[#5B9F01] rounded-full animate-pulse"></span>
                    <span class="text-[#5B9F01] font-semibold text-sm uppercase tracking-wider">Testimonials</span>
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800">What Our Customer Says?</h2>
                <div class="w-20 h-1 bg-[#5B9F01] mx-auto mt-5 rounded-full"></div>
            </div>

            <div class="testimonial-slider grid grid-cols-1 md:grid-cols-2 gap-6" id="testimonialSlider">
                <div class="testimonial-item">
                    <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300 h-full">
                        <div class="flex items-center gap-1 mb-2">
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Daniel Carter</h3>
                        <span class="text-sm text-[#5B9F01] block mb-3">PC Gamer & Streamer</span>
                        <p class="text-gray-500 text-sm leading-relaxed">My gaming PC wouldn't boot and I thought I lost everything. The GreenJack diagnosed a faulty PSU and replaced it within hours. My rig runs better than before. Highly recommend!</p>
                    </div>
                </div>
                <div class="testimonial-item">
                    <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300 h-full">
                        <div class="flex items-center gap-1 mb-2">
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Jessica Wu</h3>
                        <span class="text-sm text-[#5B9F01] block mb-3">Small Business Owner</span>
                        <p class="text-gray-500 text-sm leading-relaxed">My iPhone screen was completely shattered and my iPad had charging issues. The GreenJack fixed both in one day at a fair price. Professional, fast, and honest service.</p>
                    </div>
                </div>
                <div class="testimonial-item">
                    <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300 h-full">
                        <div class="flex items-center gap-1 mb-2">
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Marcus Rodriguez</h3>
                        <span class="text-sm text-[#5B9F01] block mb-3">College Student</span>
                        <p class="text-gray-500 text-sm leading-relaxed">My laptop crashed right before finals week. They recovered all my data and fixed the hard drive in under 48 hours. Also repaired my PS5 controller drift. Lifesavers!</p>
                    </div>
                </div>
                <div class="testimonial-item">
                    <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300 h-full">
                        <div class="flex items-center gap-1 mb-2">
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Taylor Evans</h3>
                        <span class="text-sm text-[#5B9F01] block mb-3">Content Creator</span>
                        <p class="text-gray-500 text-sm leading-relaxed">My Android phone wouldn't charge and my laptop hinge broke. The GreenJack fixed both for half what other shops quoted. They actually care about their customers.</p>
                    </div>
                </div>
                <div class="testimonial-item">
                    <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all duration-300 h-full">
                        <div class="flex items-center gap-1 mb-2">
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Ryan Mitchell</h3>
                        <span class="text-sm text-[#5B9F01] block mb-3">Xbox & PC Gamer</span>
                        <p class="text-gray-500 text-sm leading-relaxed">They revived my water-damaged gaming laptop and fixed the HDMI port on my Xbox Series X. Quick turnaround and transparent pricing. My go-to repair shop now.</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-center gap-2 mt-8">
                <button class="slider-dot w-2 h-2 rounded-full bg-gray-300 transition-all duration-300"></button>
                <button class="slider-dot w-2 h-2 rounded-full bg-gray-300 transition-all duration-300"></button>
                <button class="slider-dot w-2 h-2 rounded-full bg-gray-300 transition-all duration-300"></button>
            </div>
        </div>
    </section>

    <style>
        @keyframes scrollSlow {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .animate-scroll-slow {
            animation: scrollSlow 25s linear infinite;
            width: max-content;
        }

        .hover\:animate-pause:hover {
            animation-play-state: paused;
        }

        .brand-card {
            transition: all 0.3s ease;
        }

        .brand-card:hover {
            transform: translateY(-2px);
        }
    </style>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.testimonial-item');
        const dots = document.querySelectorAll('.slider-dot');
        let slidesToShow = window.innerWidth >= 768 ? 2 : 1;

        function updateSlider() {
            slides.forEach((slide, index) => {
                if (index >= currentSlide && index < currentSlide + slidesToShow) {
                    slide.style.display = 'block';
                } else {
                    slide.style.display = 'none';
                }
            });

            dots.forEach((dot, index) => {
                if (index === Math.floor(currentSlide / slidesToShow)) {
                    dot.classList.add('bg-[#5B9F01]', 'w-6');
                    dot.classList.remove('bg-gray-300', 'w-2');
                } else {
                    dot.classList.remove('bg-[#5B9F01]', 'w-6');
                    dot.classList.add('bg-gray-300', 'w-2');
                }
            });
        }

        function nextSlide() {
            if (currentSlide + slidesToShow < slides.length) {
                currentSlide += slidesToShow;
            } else {
                currentSlide = 0;
            }
            updateSlider();
        }

        updateSlider();
        setInterval(nextSlide, 5000);

        window.addEventListener('resize', () => {
            slidesToShow = window.innerWidth >= 768 ? 2 : 1;
            updateSlider();
        });
    </script>
</div>
