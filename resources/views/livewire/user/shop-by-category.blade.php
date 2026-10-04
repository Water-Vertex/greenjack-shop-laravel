@section('meta')
@php
    $metas = null;
    try {
        $metas = \App\Models\PageMeta::find(1);
    } catch (\Throwable $e) {
        \Log::warning('PageMeta load failed: ' . $e->getMessage());
    }
@endphp
@if($currentCategory ?? null)
    <title>{{ $currentCategory->name ?? config('app.name') }}</title>
    <meta name="title" content="{{ $currentCategory->meta_title ?? '' }}"/>
    <meta name="description" content="{{ $currentCategory->meta_description ?? '' }}"/>
    <meta name="keywords" content="{{ $currentCategory->meta_keywords ?? '' }}"/>
    <meta name="tags" content="{{ $currentCategory->meta_tags ?? '' }}"/>
    @if($currentCategory->page_schema)
    <script type="application/ld+json">
        {!! is_array($currentCategory->page_schema) ? json_encode($currentCategory->page_schema) : $currentCategory->page_schema !!}
    </script>
    @endif
@else
    <title>{{ config('app.name') }}</title>
@endif
@endsection

<div>
    {{-- ✅ Global error / success banners --}}
    @if(session()->has('error'))
        <div class="container mx-auto px-3 sm:px-4 max-w-7xl mt-3">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-start gap-3">
                <i class="fas fa-exclamation-triangle mt-0.5"></i>
                <div class="flex-1 text-sm">{{ session('error') }}</div>
            </div>
        </div>
    @endif
    @if(session()->has('success'))
        <div class="container mx-auto px-3 sm:px-4 max-w-7xl mt-3">
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-start gap-3">
                <i class="fas fa-check-circle mt-0.5"></i>
                <div class="flex-1 text-sm">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @php
        $currentCategory = $currentCategory ?? null;
        $categories = $categories ?? collect();
        $products = $products ?? null;
        $viewType = $viewType ?? 'grid';
        $sortBy = $sortBy ?? 'latest';
        $perPage = $perPage ?? 12;
    @endphp

    <div class="bg-gray-50 py-6">
        <div class="container mx-auto px-4 max-w-7xl">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <nav class="flex items-center gap-2 text-sm text-gray-500 overflow-x-auto pb-1 whitespace-nowrap">
                    <a href="{{ route('shop') }}" wire:navigate class="hover:text-[#5B9F01] transition">Home</a>
                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    <a href="{{ route('products') }}" wire:navigate class="hover:text-[#5B9F01] transition">Shop</a>
                    @if($currentCategory)
                        <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                        <span class="text-gray-800 font-medium">{{ $currentCategory->name ?? '' }}</span>
                    @endif
                </nav>
            </div>

            <!-- Page Header -->
            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                        @if($currentCategory)
                            {{ $currentCategory->name ?? 'Shop Collection' }}
                        @else
                            Shop Collection
                        @endif
                    </h1>
                    <p class="text-gray-500 text-sm mt-1">Browse our wide range of products</p>
                </div>
                <div class="flex items-center gap-3">
                    <select wire:model.live="sortBy" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#5B9F01]">
                        <option value="latest">Latest Arrivals</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="popular">Most Popular</option>
                    </select>
                    <select wire:model.live="perPage" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#5B9F01]">
                        <option value="12">12 per page</option>
                        <option value="24">24 per page</option>
                        <option value="36">36 per page</option>
                        <option value="48">48 per page</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Sidebar Filters -->
                <div class="lg:w-1/4">
                    <div class="bg-white rounded-xl shadow-sm p-5 sticky top-24">
                        <!-- Search -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Search</h3>
                            <div class="relative">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text"
                                    wire:model.live.debounce.300ms="search"
                                    placeholder="Search products..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#5B9F01] text-sm">
                            </div>
                        </div>

                        <!-- Categories Filter -->
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-lg font-semibold text-gray-800">Categories</h3>
                                <button wire:click="clearFilters" class="text-xs text-[#5B9F01] hover:underline">Clear All</button>
                            </div>
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                @if($categories->isNotEmpty())
                                    @foreach($categories as $category)
                                        <label class="flex items-center gap-2 cursor-pointer hover:text-[#5B9F01] transition">
                                            <a href="{{ route('shop.by.category', $category->slug) }}" 
                                               class="rounded border-gray-300 text-[#5B9F01] focus:ring-[#5B9F01] flex items-center gap-2 w-full">
                                                <span class="text-sm text-gray-600 flex-1">{{ $category->name ?? 'Uncategorized' }}</span>
                                                <span class="text-xs text-gray-400">({{ $category->products_count ?? 0 }})</span>
                                            </a>
                                        </label>
                                    @endforeach
                                @else
                                    <p class="text-xs text-gray-400 italic">No categories available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid/List -->
                <div class="lg:w-3/4">
                    <!-- Results Count -->
                    <div class="mb-4 flex items-center justify-between">
                        <p class="text-sm text-gray-500">
                            @if($products && method_exists($products, 'total'))
                                Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                            @else
                                No products to display.
                            @endif
                        </p>
                    </div>

                    @if($products && $products->count() > 0)
                        @if($viewType === 'grid')
                            <!-- Grid View -->
                            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($products as $product)
                                    <div class="product-card bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300 group h-full flex flex-col">
                                        <div class="relative flex-shrink-0">
                                            <a href="{{ route('shop.details', $product->slug) }}" wire:navigate class="block">
                                                <div class="flex items-center justify-center bg-gray-50 h-40 sm:h-44 md:h-48 lg:h-52 overflow-hidden">
                                                    @if($product->has_variations && $product->variations && $product->variations->whereNotNull('image')->count() > 0)
                                                        @php $firstVarImg = $product->variations->whereNotNull('image')->first(); @endphp
                                                        <img src="{{ asset('storage/' . $firstVarImg->image) }}"
                                                            onerror="this.src='https://placehold.co/400x400/e2e8f0/475569?text=No+Image'"
                                                            alt="{{ $product->name ?? '' }}"
                                                            class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                                                    @else
                                                        <img src="{{ asset('storage/' . $product->image) }}"
                                                            onerror="this.src='https://placehold.co/400x400/e2e8f0/475569?text=No+Image'"
                                                            alt="{{ $product->name ?? '' }}"
                                                            class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                                                    @endif
                                                </div>
                                            </a>

                                            {{-- Category Badge --}}
                                            @if($product->category)
                                                <span class="absolute top-2 left-2 bg-red-500 text-white text-[8px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded">
                                                    {{ $product->category->name }}
                                                </span>
                                            @endif

                                            <button
                                                wire:click="toggleWishlist({{ $product->id }})"
                                                wire:loading.attr="disabled"
                                                class="absolute top-2 right-2 w-7 h-7 sm:w-8 sm:h-8 bg-white rounded-full flex items-center justify-center shadow-md hover:bg-gray-50 transition z-10"
                                                title="Add to wishlist">
                                                <span wire:loading.remove wire:target="toggleWishlist({{ $product->id }})">
                                                    @php
                                                        $inWishlist = false;
                                                        try {
                                                            $inWishlist = \App\Models\Wishlist::where('product_id', $product->id)
                                                                ->when(Auth::id(), fn($q) => $q->where('customer_id', Auth::id()), fn($q) => $q->where('session_id', Session::getId()))
                                                                ->exists();
                                                        } catch (\Throwable $e) {}
                                                    @endphp
                                                    <i class="{{ $inWishlist ? 'fas fa-heart text-red-500' : 'far fa-heart text-gray-600' }} text-xs sm:text-sm"></i>
                                                </span>
                                                <span wire:loading wire:target="toggleWishlist({{ $product->id }})" class="flex items-center justify-center">
                                                    <svg class="animate-spin h-3 w-3 sm:h-4 sm:w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                </span>
                                            </button>

                                            {{-- MOBILE QUICK ACTION --}}
                                            @if($product->has_variations)
                                                <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 lg:hidden">
                                                    <a href="{{ route('shop.details', $product->slug) }}"
                                                       wire:navigate
                                                       class="block w-full bg-[#012D89] text-white py-1.5 rounded-lg text-xs font-medium text-center hover:bg-[#001f5c] transition cursor-pointer">
                                                        <i class="fas fa-list"></i> View Options
                                                    </a>
                                                </div>
                                            @else
                                                <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 lg:hidden">
                                                    <button wire:click="addToCart({{ $product->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="addToCart({{ $product->id }})"
                                                        class="w-full bg-[#FDCC02] text-gray-900 py-1.5 rounded-lg text-xs font-medium hover:bg-[#4a7f01] transition hover:text-white">
                                                        <span wire:loading.remove wire:target="addToCart({{ $product->id }})">
                                                            <i class="fas fa-shopping-cart"></i> Quick Add
                                                        </span>
                                                        <span wire:loading wire:target="addToCart({{ $product->id }})" class="flex items-center justify-center gap-2">
                                                            <svg class="animate-spin h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                            Adding...
                                                        </span>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="p-2 sm:p-3 flex flex-col flex-1">
                                            <a href="{{ route('shop.details', $product->slug) }}" wire:navigate>
                                                <h3 class="text-xs sm:text-sm font-medium text-gray-800 line-clamp-2 hover:text-[#5B9F01] transition min-h-[2rem] sm:min-h-[2.5rem]">
                                                    {{ ucwords(strtolower($product->name ?? '')) }}
                                                </h3>
                                            </a>

                                            <!-- Rating -->
                                            <div class="flex items-center gap-1 mb-1.5 sm:mb-2 flex-wrap">
                                                <div class="flex text-yellow-400 text-[9px] sm:text-xs">
                                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                                                </div>
                                                <span class="text-[9px] sm:text-[10px] text-gray-400 whitespace-nowrap">(24)</span>
                                            </div>

                                            <!-- Price and Button -->
                                            <div class="flex items-center justify-between mt-auto pt-1 sm:pt-2 border-t border-gray-50">
                                                <div class="flex items-center gap-1 sm:gap-2 flex-wrap">
                                                    @if($product->has_variations && $product->variations->isNotEmpty())
                                                        @php
                                                            $minVPrice = $product->variations->min('price');
                                                            $minVDiscount = $product->variations->filter(fn($v) => $v->discounted_price && $v->discounted_price < $v->price)->min('discounted_price');
                                                        @endphp
                                                        @if($minVDiscount && $minVDiscount < $minVPrice)
                                                            <span class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 whitespace-nowrap">${{ number_format($minVDiscount, 2) }}</span>
                                                            <span class="text-[8px] sm:text-[10px] text-gray-400 line-through whitespace-nowrap">${{ number_format($minVPrice, 2) }}</span>
                                                        @else
                                                            <span class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 whitespace-nowrap">${{ number_format($minVPrice, 2) }}</span>
                                                        @endif
                                                        <span class="text-[8px] sm:text-[9px] text-gray-500 whitespace-nowrap">+</span>
                                                    @else
                                                        <span class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 whitespace-nowrap">${{ number_format($product->price ?? 0, 2) }}</span>
                                                        @if($product->discounted_price && $product->discounted_price < $product->price)
                                                            <span class="text-[8px] sm:text-[10px] text-gray-400 line-through whitespace-nowrap">${{ number_format($product->discounted_price, 2) }}</span>
                                                            <span class="text-[7px] sm:text-[8px] bg-red-500 text-white px-1 sm:px-1.5 py-0.5 rounded font-bold whitespace-nowrap">
                                                                -{{ round((($product->price - $product->discounted_price) / $product->price) * 100) }}%
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>

                                                @if($product->has_variations)
                                                    <a href="{{ route('shop.details', $product->slug) }}"
                                                       wire:navigate
                                                       class="flex bg-[#012D89] text-white px-1.5 sm:px-3 py-1 sm:py-1.5 rounded-lg text-[8px] sm:text-[10px] lg:text-xs font-medium hover:bg-[#001f5c] transition items-center gap-0.5 sm:gap-1 whitespace-nowrap flex-shrink-0 cursor-pointer">
                                                        <i class="fas fa-list text-[8px] sm:text-[10px] lg:text-xs"></i>
                                                        <span class="hidden sm:inline">Select</span>
                                                    </a>
                                                @else
                                                    <button wire:click="addToCart({{ $product->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="addToCart({{ $product->id }})"
                                                        class="flex bg-[#FDCC02] text-gray-900 px-1.5 sm:px-3 py-1 sm:py-1.5 rounded-lg text-[8px] sm:text-[10px] lg:text-xs font-medium hover:bg-[#4a7f01] transition hover:text-white items-center gap-0.5 sm:gap-1 whitespace-nowrap flex-shrink-0">
                                                        <span wire:loading.remove wire:target="addToCart({{ $product->id }})">
                                                            <i class="fas fa-shopping-cart text-[8px] sm:text-[10px] lg:text-xs"></i>
                                                            <span class="hidden sm:inline">Add</span>
                                                        </span>
                                                        <span wire:loading wire:target="addToCart({{ $product->id }})" class="flex items-center gap-0.5 sm:gap-1">
                                                            <svg class="animate-spin h-2 w-2 sm:h-3 sm:w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                            <span class="hidden sm:inline">Adding</span>
                                                        </span>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Pagination -->
                        <div class="mt-8">
                            @if($products && method_exists($products, 'links'))
                                {{ $products->links() }}
                            @endif
                        </div>
                    @else
                        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                            <i class="fas fa-search text-5xl text-gray-300 mb-3"></i>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">No products found</h3>
                            <p class="text-gray-500 mb-6">Try adjusting your search or filter criteria</p>
                            <button wire:click="clearFilters" class="bg-[#5B9F01] text-white px-6 py-2 rounded-lg hover:bg-[#4a7f01] transition">
                                Clear All Filters
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        input[type="range"] {
            -webkit-appearance: none;
            height: 4px;
            background: #e5e7eb;
            border-radius: 5px;
            outline: none;
        }
        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            background: #5B9F01;
            border-radius: 50%;
            cursor: pointer;
            border: none;
        }
        input[type="range"]::-webkit-slider-thumb:hover {
            background: #4a7f01;
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .pagination .page-item .page-link {
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            color: #4a5568;
            background-color: white;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }
        .pagination .page-item.active .page-link {
            background-color: #5B9F01;
            border-color: #5B9F01;
            color: white;
        }
        .pagination .page-item .page-link:hover {
            background-color: #5B9F01;
            border-color: #5B9F01;
            color: white;
        }
        .pagination .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }
        @keyframes ping {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(1.5); opacity: 0; }
        }
        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }
    </style>
</div>
