<!-- resources/views/livewire/user/search.blade.php -->
<div class="relative w-full" x-data="{ open: false }" @click.away="open = false">
    @php
        $searchTerm = $searchTerm ?? '';
        $searchResults = $searchResults ?? [];
        $showResults = $showResults ?? false;
    @endphp

    <div class="flex w-full">
        <!-- Category Dropdown -->
        {{-- <div class="relative">
            <button id="categoryDropdownBtn" class="flex items-center gap-2 px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-l-lg border-r-0 text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                All Categories <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div id="categoryDropdownMenu" class="hidden absolute top-full left-0 mt-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                <ul class="py-1 text-sm">
                    <li>
                        <a href="{{ route('shop') }}" class="block px-4 py-2 hover:bg-gray-50 transition">
                            All Categories
                        </a>
                    </li>
                    @php
                        $categories = collect();
                        try {
                            $categories = \App\Models\Category::all();
                        } catch (\Throwable $e) {
                            \Log::warning('Search categories load failed: ' . $e->getMessage());
                        }
                    @endphp
                    @if($categories->isNotEmpty())
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ $category->slug ? route('shop.by.category', $category->slug) : '#' }}"

                                   class="block px-4 py-2 hover:bg-gray-50 transition">
                                    {{ $category->name ?? 'Uncategorized' }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div> --}}

        <!-- Search Input -->
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input
                type="text"
                wire:model.live.debounce.300ms="searchTerm"
                @focus="open = true"
                placeholder="Search products..."
                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 focus:outline-none focus:border-[#5B9F01] text-sm"
            >

            <!-- Loading Indicator -->
            <div wire:loading wire:target="searchTerm" class="absolute right-3 top-1/2 -translate-y-1/2">
                <svg class="animate-spin h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <!-- Clear Button -->
            @if(strlen($searchTerm) > 0)
                <button wire:click="clearSearch" type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times-circle text-sm"></i>
                </button>
            @endif
        </div>

        <!-- Search Button -->
        <button wire:click="performSearch" class="bg-[#00349A] text-white px-6 rounded-r-lg hover:bg-[#4a7f01] transition text-sm font-medium">
            Search
        </button>
    </div>

    <!-- Search Results Dropdown -->
    @if($showResults && strlen($searchTerm) >= 2)
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-xl z-50 max-h-96 overflow-y-auto">
            @if(count($searchResults) > 0)
                <div class="py-2">
                    @foreach($searchResults as $result)
                        @php
                            $resultSlug = $result['slug'] ?? null;
                            $resultName = $result['name'] ?? 'Unknown Product';
                            $resultImage = $result['image'] ?? null;
                            $resultPrice = (float) ($result['price'] ?? 0);
                            $resultCompare = (float) ($result['compare_price'] ?? 0);

                            // ✅ Variation image logic
                            $displayImage = $resultImage;
                            if (!empty($result['has_variations']) && !empty($result['variations']) && is_array($result['variations'])) {
                                foreach ($result['variations'] as $v) {
                                    if (!empty($v['image'])) {
                                        $displayImage = $v['image'];
                                        break;
                                    }
                                }
                            }
                        @endphp

                        <a href="{{ $resultSlug ? route('shop.details', $resultSlug) : '#' }}"

                           @click="open = false"
                           class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition group">
                            <!-- Product Image -->
                            <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                <img src="{{ $displayImage ? asset('storage/' . $displayImage) : 'https://placehold.co/60x60/e2e8f0/475569?text=No+Image' }}"
                                     onerror="this.src='https://placehold.co/60x60/e2e8f0/475569?text=No+Image'"
                                     alt="{{ $resultName }}"
                                     class="w-full h-full object-cover">
                            </div>

                            <!-- Product Info -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-800 group-hover:text-[#5B9F01] transition line-clamp-1">
                                    {{ $resultName }}
                                </h4>
                                <div class="flex items-center gap-2 mt-1 flex-wrap">
                                    <span class="text-[#5B9F01] font-bold text-sm">
                                        ${{ number_format($resultPrice, 2) }}
                                    </span>
                                    @if($resultCompare > 0 && $resultCompare > $resultPrice)
                                        <span class="text-gray-400 line-through text-xs">
                                            ${{ number_format($resultCompare, 2) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- View Button -->
                            <div class="text-gray-400 group-hover:text-[#5B9F01] transition flex-shrink-0">
                                <i class="fas fa-arrow-right text-sm"></i>
                            </div>
                        </a>
                    @endforeach

                    <!-- View All Results Link -->
                    <a href="{{ route('products', ['search' => $searchTerm]) }}"

                       @click="open = false"
                       class="block px-4 py-3 text-center text-sm text-[#5B9F01] font-medium hover:bg-gray-50 border-t border-gray-100">
                        View all results for "{{ $searchTerm }}" →
                    </a>
                </div>
            @else
                <div class="p-6 text-center">
                    <i class="fas fa-search text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500 text-sm">No products found for "{{ $searchTerm }}"</p>
                    <p class="text-xs text-gray-400 mt-1">Try searching with different keywords</p>
                </div>
            @endif
        </div>
    @endif

    <style>
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</div>
