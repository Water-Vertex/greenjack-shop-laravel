@section('meta')
@php $metas = \App\Models\Setting::find(1); @endphp
@if($metas)
    <title>{{ $metas->site_title }}</title>
    <meta name="title" content="{{ $metas->meta_title }}"/>
    <meta name="description" content="{{ $metas->meta_description }}"/>
    <meta name="keywords" content="{{ $metas->meta_keywords }}"/>
    <meta name="tags" content="{{ $metas->meta_tags }}"/>
    @if($metas->page_schema)
    <script type="application/ld+json">
        {!! is_array($metas->page_schema) ? json_encode($metas->page_schema) : $metas->page_schema !!}
    </script>
    @endif
@endif
@endsection
<div>
   
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-[#5B9F01] to-[#4a7f01] py-16 overflow-hidden ">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-64 h-64 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full translate-x-1/3 translate-y-1/3"></div>
        </div>
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Browse Categories
            </h1>
            <p class="text-white/90 text-lg max-w-2xl mx-auto">
                Discover our wide range of products organized by category
            </p>
            <div class="flex items-center justify-center gap-2 mt-4 text-white/80 text-sm flex-wrap">
                <span>{{ $categories->count() }} Categories</span>
                <span class="w-1 h-1 bg-white/50 rounded-full"></span>
                <span>{{ $categories->sum('products_count') }} Products</span>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="container mx-auto px-4 py-12 overflow-x-hidden">
        <!-- Categories Grid -->
        @if($categories->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 md:gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('shop.by.category', $category->slug) }}"
                       wire:navigate
                       class="group bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden hover:-translate-y-1 category-card-item">
                        <div class="p-3 md:p-4 flex flex-col items-center text-center">
                            <!-- Category Image -->
                            <div class="category-image-container w-24 h-24 md:w-32 md:h-32 mb-3 md:mb-4">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}"
                                         alt="{{ $category->name }}"
                                         loading="lazy"
                                         class="w-full h-full object-contain transition duration-300 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-[#5B9F01]/10 to-[#5B9F01]/5 rounded-2xl flex items-center justify-center">
                                        <i class="fas fa-microchip text-3xl md:text-5xl text-[#5B9F01]/30"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Category Name -->
                            <h3 class="font-semibold text-gray-800 text-sm md:text-base group-hover:text-[#5B9F01] transition-colors line-clamp-2 w-full">
                                {{ $category->name }}
                            </h3>

                            <!-- Product Count -->
                            <p class="text-xs md:text-sm text-gray-500 mt-1">
                                {{ $category->products_count ?? 0 }} Products
                            </p>

                            <!-- View Button -->
                            <div class="mt-2 md:mt-3 transition-all duration-300">
                                <span class="text-[#5B9F01] text-xs md:text-sm font-medium inline-flex items-center gap-1">
                                    View Products
                                    <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-folder-open text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Categories Found</h3>
                <p class="text-gray-500">No categories are available at the moment.</p>
            </div>
        @endif
    </div>
</div>
