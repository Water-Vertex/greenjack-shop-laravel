<div>
    {{-- Breadcrumb Section --}}
    <div class="breadcumb-wrapper relative bg-gray-100 py-16 md:py-20 overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%235B9F01\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"%2F%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E')] opacity-30"></div>
        <div class="container mx-auto px-4 max-w-7xl relative z-10">
            <div class="text-center">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800 mb-3">
                    Our Blog
                </h1>
                <ul class="flex items-center justify-center gap-2 text-sm text-gray-500">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-[#5B9F01] transition duration-300">Home</a>
                    </li>
                    <li><i class="fas fa-chevron-right text-xs text-gray-400"></i></li>
                    <li class="text-[#5B9F01] font-medium">Blog</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Blog Section --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 max-w-7xl">

            {{-- Search Bar (Moved directly here) --}}
            <div class="max-w-md mx-auto mb-10">
                <div class="relative">
                    <input
                        type="text"
                        wire:model.live.debounce.400ms="search"
                        placeholder="Search blogs..."
                        class="w-full px-5 py-3 pl-12 border border-gray-300 rounded-full focus:outline-none focus:border-[#5B9F01] focus:ring-2 focus:ring-[#5B9F01]/20 transition-all duration-300 bg-white shadow-sm"
                    >
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            {{-- Blog Grid --}}
            @if($blogs->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($blogs as $blog)
                        <article class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group">

                            {{-- Blog Image --}}
                            <a href="{{ route('blog.detail', $blog->slug) }}" class="block overflow-hidden">
                                @if($blog->image_url)
                                    <img
                                        src="{{ $blog->image_url }}"
                                        alt="{{ $blog->name }}"
                                        class="w-full h-52 object-cover group-hover:scale-105 transition-transform duration-500"
                                    >
                                @else
                                    <div class="w-full h-52 bg-gradient-to-br from-[#5B9F01]/20 to-[#5B9F01]/5 flex items-center justify-center">
                                        <i class="fas fa-newspaper text-5xl text-[#5B9F01]/40"></i>
                                    </div>
                                @endif
                            </a>

                            {{-- Blog Content --}}
                            <div class="p-6 flex flex-col flex-1">

                                {{-- Date --}}
                                <div class="flex items-center gap-2 text-xs text-gray-400 mb-3">
                                    <i class="fas fa-calendar-alt text-[#5B9F01]"></i>
                                    <span>{{ $blog->created_at->format('d M, Y') }}</span>
                                </div>

                                {{-- Title --}}
                                <h3 class="text-lg font-bold text-gray-800 mb-3 leading-snug group-hover:text-[#5B9F01] transition-colors duration-300 line-clamp-2">
                                    <a href="{{ route('blog.detail', $blog->slug) }}" wire:navigate>
                                        {{ $blog->name }}
                                    </a>
                                </h3>

                                {{-- Excerpt --}}
                                <p class="text-gray-500 text-sm leading-relaxed mb-5 flex-1 line-clamp-3">
                                    {{ $blog->excerpt }}
                                </p>

                                {{-- Read More --}}
                                <a
                                    href="{{ route('blog.detail', $blog->slug) }}" wire:navigate
                                    class="inline-flex items-center gap-2 text-[#5B9F01] font-semibold text-sm hover:gap-3 transition-all duration-300 mt-auto"
                                >
                                    Read More
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12 flex justify-center">
                    {{ $blogs->links() }}
                </div>

            @else
                {{-- Empty State --}}
                <div class="text-center py-20">
                    <div class="w-24 h-24 bg-[#5B9F01]/10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-newspaper text-4xl text-[#5B9F01]/50"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">No Blogs Found</h3>
                    <p class="text-gray-400 text-sm">
                        @if($search)
                            No results for "{{ $search }}". Try a different search term.
                        @else
                            No blogs have been published yet. Check back soon!
                        @endif
                    </p>
                    @if($search)
                        <button
                            wire:click="$set('search', '')"
                            class="mt-5 px-6 py-2 bg-[#5B9F01] text-white rounded-full text-sm font-medium hover:bg-[#4a8001] transition"
                        >
                            Clear Search
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </section>
</div>
