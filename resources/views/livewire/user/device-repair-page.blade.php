<div>

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
                                    <p class="text-xs text-gray-400 mt-1">Repair Service</p>
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
                                    <p class="text-xs text-gray-400 mt-1">Repair Service</p>
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
</div>
