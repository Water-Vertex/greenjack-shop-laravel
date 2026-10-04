@section('meta')
@php
    $metas = null;
    try {
        $metas = \App\Models\Setting::find(1);
    } catch (\Throwable $e) {
        \Log::warning('Setting load failed: ' . $e->getMessage());
    }
@endphp
@if($metas)
    <title>{{ $metas->site_title ?? config('app.name') }}</title>
    <meta name="title" content="{{ $metas->meta_title ?? '' }}"/>
    <meta name="description" content="{{ $metas->meta_description ?? '' }}"/>
    <meta name="keywords" content="{{ $metas->meta_keywords ?? '' }}"/>
    <meta name="tags" content="{{ $metas->meta_tags ?? '' }}"/>
    @if($metas->page_schema)
    <script type="application/ld+json">
        {!! is_array($metas->page_schema) ? json_encode($metas->page_schema) : $metas->page_schema !!}
    </script>
    @endif
@else
    <title>{{ config('app.name') }}</title>
@endif
@endsection

<div>
    @php
        $wishlistItems = $wishlistItems ?? null;
        $wishlistCount = $wishlistCount ?? 0;
    @endphp

    <!-- Page Header -->
    <div class="bg-gray-50 border-b border-gray-200 py-6">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">My Wishlist</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        <span wire:loading.remove wire:target="wishlistCount">
                            {{ $wishlistCount }} items
                        </span>
                        <span wire:loading wire:target="wishlistCount" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Loading...
                        </span>
                    </p>
                </div>
                <a href="{{ route('shop') }}" wire:navigate class="text-[#5B9F01] hover:text-[#4a7f01] text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>

    <!-- Wishlist Grid -->
    <div class="container mx-auto px-4 max-w-7xl py-8">
        @if($wishlistItems && method_exists($wishlistItems, 'count') && $wishlistItems->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($wishlistItems as $item)
                    @php
                        $product = $item->product ?? null;
                        if (!$product) continue;

                        $productId = $product->id ?? null;
                        $productName = $product->name ?? 'Unknown Product';
                        $productSlug = $product->slug ?? null;
                        $productPrice = (float) ($product->price ?? 0);
                        $productDiscount = (float) ($product->discounted_price ?? 0);
                        $hasDiscount = $productDiscount > 0 && $productDiscount < $productPrice;
                        $displayPrice = $hasDiscount ? $productDiscount : $productPrice;
                        $productStock = (int) ($product->stock ?? 0);
                        $productCategory = $product->category ?? null;

                        // ✅ Variation image logic — same as Shop/Category
                        $productImage = $product->image ?? null;
                        if ($product->has_variations && $product->variations && $product->variations->whereNotNull('image')->count() > 0) {
                            $firstVarImg = $product->variations->whereNotNull('image')->first();
                            if ($firstVarImg && $firstVarImg->image) {
                                $productImage = $firstVarImg->image;
                            }
                        }
                    @endphp

                    @if($productId)
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300 group relative">
                        <!-- Remove Button -->
                        <button
                            wire:click="removeFromWishlist({{ $productId }})"
                            wire:loading.attr="disabled"
                            wire:target="removeFromWishlist({{ $productId }})"
                            class="absolute top-2 right-2 z-10 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-md hover:bg-red-50 transition group-hover:opacity-100"
                            title="Remove from wishlist">
                            <span wire:loading.remove wire:target="removeFromWishlist({{ $productId }})">
                                <i class="fas fa-times text-gray-400 hover:text-red-500 text-sm"></i>
                            </span>
                            <span wire:loading wire:target="removeFromWishlist({{ $productId }})" class="flex items-center justify-center">
                                <svg class="animate-spin h-4 w-4 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>

                        <!-- Product Image -->
                        <a href="{{ $productSlug ? route('shop.details', $productSlug) : '#' }}" wire:navigate>
                            <div class="relative overflow-hidden bg-gray-100">
                                <img src="{{ $productImage ? asset('storage/' . $productImage) : 'https://placehold.co/300x300/e2e8f0/475569?text=No+Image' }}"
                                    onerror="this.src='https://placehold.co/300x300/e2e8f0/475569?text=No+Image'"
                                    alt="{{ $productName }}"
                                    class="w-full h-48 sm:h-56 object-cover group-hover:scale-105 transition-transform duration-300">

                                {{-- ✅ Variation Badge --}}
                                @if($product->has_variations)
                                    <span class="absolute bottom-2 left-2 bg-[#012D89] text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-md">
                                        <i class="fas fa-list"></i> {{ $product->variations->count() }} Options
                                    </span>
                                @endif
                            </div>
                        </a>

                        <!-- Product Info -->
                        <div class="p-4">
                            <!-- Category Badge -->
                            @if($productCategory)
                                <span class="text-xs text-[#5B9F01] font-medium">{{ $productCategory->name ?? '' }}</span>
                            @endif

                            <!-- Product Name -->
                            <a href="{{ $productSlug ? route('shop.details', $productSlug) : '#' }}" wire:navigate>
                                <h3 class="text-sm font-semibold text-gray-800 mt-1 hover:text-[#5B9F01] transition line-clamp-2 min-h-[40px]">
                                    {{ $productName }}
                                </h3>
                            </a>

                            <!-- Rating -->
                            <div class="flex items-center gap-1 mt-2">
                                <div class="flex text-yellow-400 text-xs">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="text-xs text-gray-400">(24)</span>
                            </div>

                            <!-- Price -->
                            <div class="mt-3 flex items-center gap-2 flex-wrap">
                                @if($hasDiscount)
                                    <span class="text-lg font-bold text-red-600">${{ number_format($productDiscount, 2) }}</span>
                                    <span class="text-sm text-gray-400 line-through">${{ number_format($productPrice, 2) }}</span>
                                    <span class="text-xs bg-red-500 text-white px-2 py-0.5 rounded font-bold">
                                        -{{ round((($productPrice - $productDiscount) / $productPrice) * 100) }}%
                                    </span>
                                @else
                                    <span class="text-lg font-bold text-gray-900">${{ number_format($productPrice, 2) }}</span>
                                @endif
                            </div>

                            <!-- Stock Status -->
                            <div class="mt-2">
                                @if($productStock > 0)
                                    <span class="text-xs text-green-600 flex items-center gap-1">
                                        <i class="fas fa-check-circle"></i> In Stock
                                    </span>
                                @else
                                    <span class="text-xs text-red-600 flex items-center gap-1">
                                        <i class="fas fa-times-circle"></i> Out of Stock
                                    </span>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-4 flex gap-2">
                                @if($productStock > 0)
                                    @if($product->has_variations)
                                        <a href="{{ $productSlug ? route('shop.details', $productSlug) : '#' }}"
                                           wire:navigate
                                           class="flex-1 bg-[#012D89] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#001f5c] transition flex items-center justify-center gap-2">
                                            <i class="fas fa-list"></i>
                                            Select Options
                                        </a>
                                    @else
                                        <button
                                            wire:click="addToCart({{ $productId }})"
                                            wire:loading.attr="disabled"
                                            wire:target="addToCart({{ $productId }})"
                                            class="flex-1 bg-[#FDCC02] text-gray-900 px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#f5c400] transition flex items-center justify-center gap-2">
                                            <span wire:loading.remove wire:target="addToCart({{ $productId }})">
                                                <i class="fas fa-shopping-cart"></i>
                                                Add to Cart
                                            </span>
                                            <span wire:loading wire:target="addToCart({{ $productId }})" class="flex items-center gap-2">
                                                <svg class="animate-spin h-4 w-4 text-gray-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Adding...
                                            </span>
                                        </button>
                                    @endif
                                @else
                                    <button disabled class="flex-1 bg-gray-200 text-gray-500 px-4 py-2 rounded-lg text-sm font-medium cursor-not-allowed">
                                        Out of Stock
                                    </button>
                                @endif

                                <a href="{{ $productSlug ? route('shop.details', $productSlug) : '#' }}"
                                   wire:navigate
                                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm flex items-center justify-center">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                @if(method_exists($wishlistItems, 'links'))
                    {{ $wishlistItems->links() }}
                @endif
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16 bg-white rounded-lg border border-gray-200">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="far fa-heart text-4xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Your wishlist is empty</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">
                    Start adding your favorite items to your wishlist by clicking the heart icon on products.
                </p>
                <a href="{{ route('shop') }}" wire:navigate class="inline-block bg-[#5B9F01] text-white px-6 py-3 rounded-lg hover:bg-[#4a7f01] transition font-medium">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Start Shopping
                </a>
            </div>
        @endif
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div x-data="{ show: true }"
             x-init="setTimeout(() => show = false, 3000)"
             x-show="show"
             class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transition-all duration-300 z-50">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }"
             x-init="setTimeout(() => show = false, 5000)"
             x-show="show"
             class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg transition-all duration-300 z-50">
            <div class="flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>
