@section('meta')
@php
    $metas = null;
    try {
        $metas = \App\Models\PageMeta::find(1);
    } catch (\Throwable $e) {
        \Log::warning('PageMeta load failed: ' . $e->getMessage());
    }
@endphp
@if($product)
    <title>{{ $product->name ?? config('app.name') }}</title>
    <meta name="title" content="{{ $product->meta_title ?? '' }}"/>
    <meta name="description" content="{{ $product->meta_description ?? '' }}"/>
    <meta name="keywords" content="{{ $product->meta_keywords ?? '' }}"/>
    <meta name="tags" content="{{ $product->meta_tags ?? '' }}"/>
    @if($product->page_schema)
    <script type="application/ld+json">
        {!! is_array($product->page_schema) ? json_encode($product->page_schema) : $product->page_schema !!}
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
        $products = $products ?? collect();
        $availableAttributes = $availableAttributes ?? [];
        $selectedAttributes = $selectedAttributes ?? [];
        $lockedAttributeGroups = $lockedAttributeGroups ?? [];
        $cartItemQty = $cartItemQty ?? 0;
        $remainingQty = $remainingQty ?? 0;
        $isMaxQtyInCart = $isMaxQtyInCart ?? false;
        $addToCartButtonText = $addToCartButtonText ?? 'Add to Cart';
    @endphp

    <main class="bg-gray-50 py-4 sm:py-6">
    <div class="container mx-auto px-3 sm:px-4 max-w-7xl">
        @if($product)
        <!-- Breadcrumb -->
        <div class="mb-4 sm:mb-6">
            <nav class="flex items-center gap-1 sm:gap-2 text-xs sm:text-sm text-gray-500 overflow-x-auto pb-2 whitespace-nowrap">
                <a href="{{ route('shop') }}" wire:navigate class="hover:text-[#5B9F01] transition">Home</a>
                <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
                <a href="{{ $product->category ? route('shop.by.category', $product->category->slug) : route('shop') }}"
                   wire:navigate class="hover:text-[#5B9F01] transition">{{ $product->category->name ?? 'Shop' }}</a>
                <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
                <span class="text-gray-800 font-medium truncate">{{ $product->name ?? '' }}</span>
            </nav>
        </div>

        <!-- Product Main Section -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6 sm:mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 md:gap-8 p-3 sm:p-4 md:p-6">
                <!-- Product Images Gallery -->
                <div>
                    <!-- Main Image -->
                    <div class="relative mb-3 sm:mb-4 rounded-lg overflow-hidden bg-gray-50">
                        <img id="mainImage"
                             src="{{ $this->getVariationImage() }}"
                             onerror="this.src='https://placehold.co/60x60/e2e8f0/475569?text=No+Image'"
                             alt="{{ $product->name ?? '' }}"
                             class="w-full h-auto max-h-[300px] sm:max-h-[400px] md:max-h-[500px] object-contain transition-opacity duration-300"
                             style="transition: opacity 0.3s ease-in-out;">
                        <button
                            wire:click="toggleWishlist({{ $product->id }})"
                            wire:loading.attr="disabled"
                            class="absolute top-2 right-2 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-md hover:bg-gray-50 transition z-10"
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
                                <i class="{{ $inWishlist ? 'fas fa-heart text-red-500' : 'far fa-heart text-gray-600' }} text-sm"></i>
                            </span>
                            <span wire:loading wire:target="toggleWishlist({{ $product->id }})" class="flex items-center justify-center">
                                <svg class="animate-spin h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>

                    <!-- Thumbnail Images -->
                    <div class="grid grid-cols-4 sm:grid-cols-5 gap-2 sm:gap-3">
                        @if($product->has_variations && $product->variations && $product->variations->isNotEmpty())
                            @foreach($product->variations as $variation)
                                @if($variation->image)
                                    <div class="thumbnail-item border-2 rounded-lg overflow-hidden cursor-pointer hover:border-[#5B9F01] transition aspect-square
                                        {{ $selectedVariationId == $variation->id ? 'border-[#5B9F01]' : 'border-transparent' }}"
                                        data-image="{{ asset('storage/' . $variation->image) }}"
                                        data-variation-id="{{ $variation->id }}"
                                        wire:click="selectVariationFromGallery({{ $variation->id }})">
                                        <img src="{{ asset('storage/' . $variation->image) }}"
                                             onerror="this.src='https://placehold.co/60x60/e2e8f0/475569?text=No+Image'"
                                             alt="Variation Image"
                                             class="w-full h-full object-cover">
                                    </div>
                                @endif
                            @endforeach
                        @endif

                        @if($product->productImages && $product->productImages->isNotEmpty())
                            @foreach($product->productImages as $productImage)
                                @php
                                    $images = is_string($productImage->images) ? json_decode($productImage->images, true) : $productImage->images;
                                @endphp
                                @if(!empty($images) && is_array($images))
                                    @foreach($images as $index => $imagePath)
                                        <div class="thumbnail-item border-2 border-transparent rounded-lg overflow-hidden cursor-pointer hover:border-[#5B9F01] transition aspect-square"
                                            data-image="{{ asset('storage/' . $imagePath) }}">
                                            <img src="{{ asset('storage/' . $imagePath) }}"
                                                 onerror="this.src='https://placehold.co/60x60/e2e8f0/475569?text=No+Image'"
                                                 alt="Product Image {{ $index + 1 }}"
                                                 class="w-full h-full object-cover">
                                        </div>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Product Info -->
                <div>
                    <a href="{{ $product->category ? route('shop.by.category', $product->category->slug) : '#' }}"
                       class="text-[#5B9F01] text-xs sm:text-sm font-medium mb-1 sm:mb-2 inline-block">{{ $product->category->name ?? 'Uncategorized' }}</a>

                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 mb-2 sm:mb-3">{{ ucwords(strtolower($product->name ?? '')) }}</h1>

                    <div class="text-xs sm:text-sm text-gray-500 mb-3 sm:mb-4">
                        <span class="font-medium">SKU:</span> {{ $variationSku ?? $product->sku ?? 'N/A' }}
                    </div>

                    {{-- PRICE SECTION --}}
                    <div class="mb-3 sm:mb-4">
                        @if($product->has_variations)
                            @if($selectedVariationId)
                                @php
                                    $selectedVariation = $product->variations->firstWhere('id', $selectedVariationId);
                                    $displayPrice = $selectedVariation->discounted_price ?? $selectedVariation->price ?? 0;
                                    $originalPrice = $selectedVariation->price ?? 0;
                                    $hasDiscount = $selectedVariation->discounted_price && $selectedVariation->discounted_price < $selectedVariation->price;
                                @endphp
                                <div class="flex items-baseline gap-2 sm:gap-3 flex-wrap">
                                    <span class="text-2xl sm:text-3xl font-bold text-[#5B9F01]">${{ number_format($displayPrice, 2) }}</span>
                                    @if($hasDiscount)
                                        <span class="text-sm sm:text-base text-gray-400 line-through">${{ number_format($originalPrice, 2) }}</span>
                                        <span class="bg-red-500 text-white text-[10px] sm:text-xs font-bold px-2 py-0.5 rounded">
                                            -{{ round((($originalPrice - $selectedVariation->discounted_price) / $originalPrice) * 100) }}%
                                        </span>
                                    @endif
                                </div>
                            @else
                                @php
                                    $basePrice = $product->discounted_price ?? $product->price ?? 0;
                                    $hasBaseDiscount = $product->discounted_price && $product->discounted_price < $product->price;
                                @endphp
                                <div class="flex items-baseline gap-2 sm:gap-3 flex-wrap">
                                    <span class="text-2xl sm:text-3xl font-bold text-[#5B9F01]">${{ number_format($basePrice, 2) }}</span>
                                    @if($hasBaseDiscount)
                                        <span class="text-sm sm:text-base text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                                        <span class="bg-red-500 text-white text-[10px] sm:text-xs font-bold px-2 py-0.5 rounded">
                                            -{{ round((($product->price - $product->discounted_price) / $product->price) * 100) }}%
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>Select options to see exact price
                                </div>
                            @endif
                        @else
                            @php
                                $displayPrice = $product->discounted_price ?? $product->price ?? 0;
                                $hasDiscount = $product->discounted_price && $product->discounted_price < $product->price;
                            @endphp
                            <div class="flex items-baseline gap-2 sm:gap-3 flex-wrap">
                                <span class="text-2xl sm:text-3xl font-bold text-[#5B9F01]">${{ number_format($displayPrice, 2) }}</span>
                                @if($hasDiscount)
                                    <span class="text-sm sm:text-base text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                                    <span class="bg-red-500 text-white text-[10px] sm:text-xs font-bold px-2 py-0.5 rounded">
                                        -{{ round((($product->price - $product->discounted_price) / $product->price) * 100) }}%
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- VARIATIONS SECTION --}}
                    @if($product->has_variations && $product->variations->isNotEmpty())
                        <div class="mb-4 sm:mb-5">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Select Options:</h4>

                            @foreach($availableAttributes as $attributeGroup)
                                <div class="mb-4">
                                    <label class="block text-xs font-medium text-gray-600 mb-1.5">{{ $attributeGroup['attribute_name'] ?? '' }}:</label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(($attributeGroup['values'] ?? []) as $attributeValue)
                                            @php
                                                $isSelected = in_array($attributeValue['id'], $selectedAttributes ?? []);
                                                $isAvailable = $attributeValue['available'] ?? true;
                                            @endphp
                                            <button
                                                wire:click="selectAttributeValue({{ $attributeValue['id'] }})"
                                                wire:loading.attr="disabled"
                                                class="px-3 py-1.5 text-xs sm:text-sm rounded-lg border-2 transition-all duration-200
                                                    {{ $isSelected
                                                        ? 'border-[#5B9F01] bg-[#5B9F01]/10 text-[#5B9F01] font-medium'
                                                        : ($isAvailable
                                                            ? 'border-gray-200 hover:border-gray-300 text-gray-700 cursor-pointer hover:bg-gray-50'
                                                            : 'border-gray-100 bg-gray-50 text-gray-400 cursor-not-allowed opacity-50') }}"
                                                {{ !$isAvailable ? 'disabled' : '' }}
                                                title="{{ $isAvailable ? 'Click to select' : 'Not available with current selection' }}">
                                                @if($attributeValue['color_code'] ?? false)
                                                    <span class="inline-block w-3 h-3 rounded-full mr-1.5 align-middle"
                                                        style="background-color: {{ $attributeValue['color_code'] }}"></span>
                                                @endif
                                                {{ $attributeValue['value'] ?? '' }}
                                                @if(!$isAvailable)
                                                    <span class="text-[8px] ml-1 text-gray-400">(unavailable)</span>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            {{-- Selected Variation Summary --}}
                            <div class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div>
                                        <span class="text-xs text-gray-500">Selected:</span>
                                        <span class="text-xs font-medium text-gray-800 ml-1">
                                            @php
                                                $attrString = '';
                                                if (!empty($selectedAttributes)) {
                                                    $attrNames = [];
                                                    foreach ($selectedAttributes as $attrValueId) {
                                                        try {
                                                            $attrValue = \App\Models\AttributeValue::with('attribute')->find($attrValueId);
                                                            if ($attrValue && $attrValue->attribute) {
                                                                $attrNames[] = $attrValue->attribute->name . ': ' . $attrValue->value;
                                                            }
                                                        } catch (\Throwable $e) {}
                                                    }
                                                    $attrString = implode(', ', $attrNames);
                                                }
                                            @endphp
                                            {{ $selectedVariationId ? $attrString : 'Please select options above' }}
                                        </span>
                                        @if(!$selectedVariationId)
                                            <span class="text-xs text-orange-500 ml-2">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>Required
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        @if($selectedVariationId)
                                            <span class="text-green-600 font-medium">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                {{ ($variationStock ?? 0) > 0 ? 'In Stock' : 'Out of Stock' }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">Select options to see availability</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($selectedVariationId)
                                <div class="flex flex-wrap items-center gap-2 sm:gap-4 mt-3 pb-3 border-b border-gray-200">
                                    <span class="text-xs sm:text-sm text-gray-500">
                                        <span class="font-medium">Stock:</span>
                                        <span class="{{ ($variationStock ?? 0) > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ ($variationStock ?? 0) > 0 ? $variationStock . ' available' : 'Out of Stock' }}
                                        </span>
                                    </span>
                                    @if($variationSku)
                                        <span class="text-xs sm:text-sm text-gray-500">
                                            <span class="font-medium">SKU:</span> {{ $variationSku }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Description --}}
                    <div class="text-gray-600 text-xs sm:text-sm mb-3 sm:mb-4">
                        @php
                            $description = $product->description ?? '';
                            $maxLength = 500;
                            $isLong = strlen(strip_tags($description)) > $maxLength;
                        @endphp
                        <div class="description-wrapper">
                            <div class="description-content {{ $isLong ? 'truncated' : '' }}"
                                 style="{{ $isLong ? 'max-height: 10em; overflow: hidden; position: relative;' : '' }}">
                                {!! $description !!}
                            </div>
                            @if($isLong)
                                <button class="see-more-btn text-indigo-600 hover:text-indigo-800 font-medium text-xs sm:text-sm mt-1 focus:outline-none"
                                        onclick="toggleDescription(this)">
                                    See More
                                </button>
                            @endif
                        </div>
                    </div>

                    @php
                        $maxStock = (int) ($variationStock ?? $product->stock ?? 0);
                        $cartItemQty = (int) ($cartItemQty ?? 0);
                        $remainingQty = (int) ($remainingQty ?? max(0, $maxStock - $cartItemQty));
                        $canIncrease = !$isMaxQtyInCart && $quantity < $remainingQty;
                        $canDecrease = $quantity > 1;
                    @endphp

                    {{-- ✅ Already in cart info --}}
                    @if($cartItemQty > 0)
                        <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-start gap-2 text-xs sm:text-sm text-blue-800">
                                <i class="fas fa-shopping-cart mt-0.5"></i>
                                <div class="flex-1">
                                    <span class="font-medium">{{ $cartItemQty }} item(s) already in your cart.</span>
                                    @if($remainingQty > 0)
                                        <span class="block mt-0.5 text-blue-700">You can add up to <strong>{{ $remainingQty }}</strong> more.</span>
                                    @else
                                        <span class="block mt-0.5 text-blue-700">Maximum available quantity already added.</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Quantity + Add to Cart --}}
                    <div class="flex flex-wrap items-center gap-3 sm:gap-4 mb-3 sm:mb-4">
                        <div class="flex items-center border border-gray-300 rounded-lg">
                            <button
                                wire:click="decreaseQuantity"
                                wire:loading.attr="disabled"
                                @if(!$canDecrease) disabled @endif
                                class="qty-btn w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition disabled:opacity-40 disabled:cursor-not-allowed text-sm sm:text-base">
                                -
                            </button>
                            <input
                                type="number"
                                wire:model.live="quantity"
                                min="1"
                                max="{{ max(1, $remainingQty) }}"
                                readonly
                                class="qty-input w-10 h-8 sm:w-14 sm:h-10 text-center border-x border-gray-300 focus:outline-none text-sm sm:text-base">
                            <button
                                wire:click="increaseQuantity"
                                wire:loading.attr="disabled"
                                @if(!$canIncrease) disabled @endif
                                class="qty-btn w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition disabled:opacity-40 disabled:cursor-not-allowed text-sm sm:text-base">
                                +
                            </button>
                        </div>

                        @if($maxStock > 0 && $maxStock <= 10)
                            <p class="text-xs text-orange-600 mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>Only {{ $maxStock }} left in stock
                            </p>
                        @endif

                        <button wire:click="addToCart" wire:loading.attr="disabled"
                                @if($isAddToCartDisabled) disabled @endif
                                class="flex-1 sm:flex-none {{ $isAddToCartDisabled ? 'bg-gray-300 cursor-not-allowed text-gray-500' : 'bg-[#FFD814] hover:bg-[#F7CA00] text-gray-800' }} font-semibold py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg transition flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed text-sm sm:text-base min-h-[44px]">
                            <span wire:loading.remove wire:target="addToCart">
                                <i class="fas fa-shopping-cart text-sm sm:text-base"></i>
                                <span class="hidden xs:inline">{{ $addToCartButtonText }}</span>
                                <span class="xs:hidden">{{ $addToCartButtonText }}</span>
                            </span>
                            <span wire:loading wire:target="addToCart" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-gray-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Adding...
                            </span>
                        </button>

                        <button
                            wire:click="toggleWishlist({{ $product->id }})"
                            wire:loading.attr="disabled"
                            class="w-8 h-8 sm:w-10 sm:h-10 bg-white rounded-full flex items-center justify-center shadow-md hover:bg-gray-50 transition border border-gray-200 flex-shrink-0"
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
                                <i class="{{ $inWishlist ? 'fas fa-heart text-red-500' : 'far fa-heart text-gray-600' }} text-sm"></i>
                            </span>
                            <span wire:loading wire:target="toggleWishlist({{ $product->id }})" class="flex items-center justify-center">
                                <svg class="animate-spin h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>

                    <!-- Delivery Info -->
                    <div class="bg-gray-50 rounded-lg p-3 sm:p-4 space-y-1.5 sm:space-y-2">
                        <div class="flex items-center gap-2 text-xs sm:text-sm">
                            <i class="fas fa-truck text-[#5B9F01] text-sm sm:text-base"></i>
                            <span class="text-gray-600">Free Shipping on orders over $50</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs sm:text-sm">
                            <i class="fas fa-undo-alt text-[#5B9F01] text-sm sm:text-base"></i>
                            <span class="text-gray-600">30-day easy returns</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs sm:text-sm">
                            <i class="fas fa-shield-alt text-[#5B9F01] text-sm sm:text-base"></i>
                            <span class="text-gray-600">2-year warranty included</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <i class="fas fa-box-open text-5xl text-gray-300 mb-3"></i>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Product not available</h3>
                <a href="{{ route('shop') }}" wire:navigate class="inline-block bg-[#5B9F01] text-white px-6 py-2 rounded-lg hover:bg-[#4a7f01] transition mt-3">Back to Shop</a>
            </div>
        @endif

        <!-- Related Products -->
        @if($product && $products->isNotEmpty())
        <div class="mb-6 sm:mb-8">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
                <h3 class="text-base sm:text-xl font-bold text-gray-800">Related Products</h3>
                <a href="{{ $product->category ? route('shop.by.category', $product->category->slug) : route('shop') }}"
                   wire:navigate class="text-[#5B9F01] text-xs sm:text-sm font-medium hover:underline">View All →</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 sm:gap-3 md:gap-4">
                @foreach($products as $relatedProduct)
                    <div class="product-card bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group border border-gray-100 hover:border-[#FDCC02]/30">
                        <div class="relative">
                            <a href="{{ route('shop.details', $relatedProduct->slug) }}" wire:navigate>
                                <div class="flex items-center justify-center bg-gray-50 h-40 sm:h-44 md:h-48 lg:h-52 overflow-hidden">
                                    @if($relatedProduct->has_variations && $relatedProduct->variations && $relatedProduct->variations->whereNotNull('image')->count() > 0)
                                        @php $firstVarImg = $relatedProduct->variations->whereNotNull('image')->first(); @endphp
                                        <img src="{{ asset('storage/' . $firstVarImg->image) }}"
                                            onerror="this.src='https://placehold.co/60x60/e2e8f0/475569?text=No+Image'"
                                            alt="{{ $relatedProduct->name }}"
                                            class="w-full h-32 sm:h-40 md:h-44 lg:h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <img src="{{ asset('storage/' . $relatedProduct->image) }}"
                                            onerror="this.src='https://placehold.co/60x60/e2e8f0/475569?text=No+Image'"
                                            alt="{{ $relatedProduct->name }}"
                                            class="w-full h-32 sm:h-40 md:h-44 lg:h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                    @endif
                                </div>
                            </a>

                            @if($relatedProduct->category)
                                <span class="absolute top-2 sm:top-3 left-2 sm:left-3 bg-[#FDCC02] text-gray-900 text-[8px] sm:text-[10px] font-bold px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full shadow-md">
                                    {{ $relatedProduct->category->name }}
                                </span>
                            @endif

                            @php
                                $displayPrice = $relatedProduct->price;
                                $displayDiscount = $relatedProduct->discounted_price;
                                if ($relatedProduct->has_variations && $relatedProduct->variations->isNotEmpty()) {
                                    $displayPrice = $relatedProduct->variations->min('price');
                                    $displayDiscount = $relatedProduct->variations->filter(fn($v) => $v->discounted_price && $v->discounted_price < $v->price)->min('discounted_price');
                                }
                            @endphp
                            @if($displayDiscount && $displayDiscount < $displayPrice)
                                <span class="absolute top-2 sm:top-3 left-16 sm:left-20 bg-red-500 text-white text-[8px] sm:text-[10px] font-bold px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full shadow-md">
                                    -{{ round((($displayPrice - $displayDiscount) / $displayPrice) * 100) }}%
                                </span>
                            @endif

                            <button
                                wire:click="toggleWishlist({{ $relatedProduct->id }})"
                                wire:loading.attr="disabled"
                                class="absolute top-2 sm:top-3 right-2 sm:right-3 w-7 h-7 sm:w-8 sm:h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-md hover:bg-white hover:scale-110 transition-all duration-300 z-10"
                                title="Add to wishlist">
                                <span wire:loading.remove wire:target="toggleWishlist({{ $relatedProduct->id }})">
                                    @php
                                        $inWishlist = false;
                                        try {
                                            $inWishlist = \App\Models\Wishlist::where('product_id', $relatedProduct->id)
                                                ->when(Auth::id(), fn($q) => $q->where('customer_id', Auth::id()), fn($q) => $q->where('session_id', Session::getId()))
                                                ->exists();
                                        } catch (\Throwable $e) {}
                                    @endphp
                                    <i class="{{ $inWishlist ? 'fas fa-heart text-red-500' : 'far fa-heart text-gray-600' }} text-xs sm:text-sm"></i>
                                </span>
                                <span wire:loading wire:target="toggleWishlist({{ $relatedProduct->id }})" class="flex items-center justify-center">
                                    <svg class="animate-spin h-3 w-3 sm:h-4 sm:w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </button>

                            <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 sm:hidden">
                                @if($relatedProduct->has_variations)
                                    <a href="{{ route('shop.details', $relatedProduct->slug) }}"
                                       wire:navigate
                                       class="block w-full bg-[#012D89] text-white py-1.5 rounded-lg text-[10px] font-medium text-center hover:bg-[#001f5c] transition">
                                        <i class="fas fa-list text-[10px]"></i> View Options
                                    </a>
                                @else
                                    <button wire:click="addRelatedToCart({{ $relatedProduct->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="addRelatedToCart({{ $relatedProduct->id }})"
                                            class="w-full bg-[#FDCC02] text-gray-900 py-1.5 rounded-lg text-[10px] font-medium hover:bg-[#4a7f01] transition hover:text-white">
                                        <span wire:loading.remove wire:target="addRelatedToCart({{ $relatedProduct->id }})">
                                            <i class="fas fa-shopping-cart text-[10px]"></i> Quick Add
                                        </span>
                                        <span wire:loading wire:target="addRelatedToCart({{ $relatedProduct->id }})" class="flex items-center justify-center gap-2">
                                            <svg class="animate-spin h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Adding...
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="p-2 sm:p-3 md:p-4">
                            <a href="{{ route('shop.details', $relatedProduct->slug) }}" wire:navigate>
                                <h3 class="text-xs sm:text-sm font-medium text-gray-800 line-clamp-2 mb-1 hover:text-[#5B9F01] transition min-h-[2rem] sm:min-h-[2.5rem]">
                                    {{ $relatedProduct->name }}
                                </h3>
                            </a>

                            <div class="flex items-center gap-1 mb-1.5 sm:mb-2">
                                <div class="flex text-yellow-400 text-[9px] sm:text-xs">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="text-[9px] sm:text-[10px] text-gray-400">(24)</span>
                            </div>

                            <div class="flex items-center justify-between mt-1 pt-1 sm:pt-2 border-t border-gray-50">
                                <div class="flex items-center gap-1 sm:gap-2 flex-wrap">
                                    @if($relatedProduct->has_variations && $relatedProduct->variations->isNotEmpty())
                                        @php
                                            $minVPrice = $relatedProduct->variations->min('price');
                                            $minVDiscount = $relatedProduct->variations->filter(fn($v) => $v->discounted_price && $v->discounted_price < $v->price)->min('discounted_price');
                                        @endphp
                                        @if($minVDiscount && $minVDiscount < $minVPrice)
                                            <span class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 whitespace-nowrap">${{ number_format($minVDiscount, 2) }}</span>
                                            <span class="text-[8px] sm:text-[10px] text-gray-400 line-through whitespace-nowrap">${{ number_format($minVPrice, 2) }}</span>
                                        @else
                                            <span class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 whitespace-nowrap">${{ number_format($minVPrice, 2) }}</span>
                                        @endif
                                        <span class="text-[8px] sm:text-[9px] text-gray-500 whitespace-nowrap">+</span>
                                    @else
                                        <span class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 whitespace-nowrap">${{ number_format($relatedProduct->price, 2) }}</span>
                                        @if($relatedProduct->discounted_price && $relatedProduct->discounted_price < $relatedProduct->price)
                                            <span class="text-[8px] sm:text-[10px] text-gray-400 line-through whitespace-nowrap">${{ number_format($relatedProduct->discounted_price, 2) }}</span>
                                        @endif
                                    @endif
                                </div>

                                @if($relatedProduct->has_variations)
                                    <a href="{{ route('shop.details', $relatedProduct->slug) }}"
                                       wire:navigate
                                       class="hidden sm:flex bg-[#012D89] text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg text-[10px] sm:text-xs font-medium hover:bg-[#001f5c] transition items-center gap-1">
                                        <i class="fas fa-list text-[10px] sm:text-xs"></i>
                                        <span class="hidden md:inline">Select</span>
                                    </a>
                                @else
                                    <button wire:click="addRelatedToCart({{ $relatedProduct->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="addRelatedToCart({{ $relatedProduct->id }})"
                                            class="hidden sm:flex bg-[#FDCC02] text-gray-900 px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg text-[10px] sm:text-xs font-medium hover:bg-[#4a7f01] transition hover:text-white items-center gap-1">
                                        <span wire:loading.remove wire:target="addRelatedToCart({{ $relatedProduct->id }})">
                                            <i class="fas fa-shopping-cart text-[10px] sm:text-xs"></i>
                                            <span class="hidden md:inline">Add to Cart</span>
                                        </span>
                                        <span wire:loading wire:target="addRelatedToCart({{ $relatedProduct->id }})" class="flex items-center gap-1">
                                            <svg class="animate-spin h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="hidden md:inline">Adding...</span>
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    </main>

    <style>
        .description-content.truncated::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 2.5em;
            background: linear-gradient(transparent, white);
            pointer-events: none;
        }
        .description-content.expanded {
            max-height: none !important;
            overflow: visible !important;
        }
        .description-content.expanded::after { display: none; }

        @media (min-width: 475px) { .xs\:inline { display: inline !important; } .xs\:hidden { display: none !important; } }
        @media (max-width: 474px) { .xs\:inline { display: none !important; } .xs\:hidden { display: inline !important; } }

        @media (max-width: 480px) { .product-card .relative img { height: 120px !important; } .product-card .p-1\.5 { padding: 6px !important; } }
        @media (min-width: 481px) and (max-width: 640px) { .product-card .relative img { height: 140px !important; } }

        .qty-input::-webkit-inner-spin-button,
        .qty-input::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        .qty-input { -moz-appearance: textfield; }

        #mainImage { transition: opacity 0.3s ease-in-out; }
    </style>

    <script>
        function toggleDescription(button) {
            const content = button.previousElementSibling;
            const isExpanded = content.classList.contains('expanded');
            if (isExpanded) {
                content.classList.remove('expanded');
                content.style.maxHeight = '10em';
                content.style.overflow = 'hidden';
                button.textContent = 'See More';
            } else {
                content.classList.add('expanded');
                content.style.maxHeight = 'none';
                content.style.overflow = 'visible';
                button.textContent = 'See Less';
            }
        }

        document.addEventListener('DOMContentLoaded', initThumbnails);
        document.addEventListener('livewire:navigated', initThumbnails);

        function initThumbnails() {
            document.querySelectorAll('.thumbnail-item').forEach(function (thumb) {
                if (thumb.dataset.bound === '1') return;
                thumb.dataset.bound = '1';
                thumb.addEventListener('click', function (e) {
                    if (this.dataset.variationId) return; // Livewire handles variation thumbs
                    const imageUrl = this.dataset.image;
                    const mainImage = document.getElementById('mainImage');
                    if (mainImage && imageUrl) {
                        mainImage.style.opacity = '0';
                        setTimeout(() => {
                            mainImage.src = imageUrl;
                            mainImage.style.opacity = '1';
                        }, 150);
                    }
                    document.querySelectorAll('.thumbnail-item').forEach(t => {
                        t.classList.remove('border-[#5B9F01]');
                        t.classList.add('border-transparent');
                    });
                    this.classList.add('border-[#5B9F01]');
                    this.classList.remove('border-transparent');
                });
            });
        }

        document.addEventListener('livewire:init', () => {
            Livewire.on('update-main-image', (event) => {
                const mainImage = document.getElementById('mainImage');
                if (mainImage && event.imageUrl) {
                    mainImage.style.opacity = '0';
                    setTimeout(() => {
                        mainImage.src = event.imageUrl;
                        mainImage.style.opacity = '1';
                    }, 150);
                }
                document.querySelectorAll('.thumbnail-item').forEach(thumb => {
                    const thumbImg = thumb.querySelector('img');
                    if (thumbImg && thumbImg.src === event.imageUrl) {
                        thumb.classList.add('border-[#5B9F01]');
                        thumb.classList.remove('border-transparent');
                    } else {
                        thumb.classList.remove('border-[#5B9F01]');
                        thumb.classList.add('border-transparent');
                    }
                });
            });
        });
    </script>
</div>
