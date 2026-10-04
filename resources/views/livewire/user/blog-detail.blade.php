<div>
    {{-- Breadcrumb --}}
    <div class="breadcumb-wrapper relative bg-gray-100 py-16 md:py-20 overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%235B9F01\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"%2F%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E')] opacity-30"></div>
        <div class="container mx-auto px-4 max-w-7xl relative z-10">
            <div class="text-center">
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-800 mb-3 max-w-3xl mx-auto leading-tight">
                    {{ $blog->name }}
                </h1>
                <ul class="flex items-center justify-center gap-2 text-sm text-gray-500 flex-wrap">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-[#5B9F01] transition duration-300">Home</a>
                    </li>
                    <li><i class="fas fa-chevron-right text-xs text-gray-400"></i></li>
                    <li>
                        <a href="{{ route('blogs') }}" class="hover:text-[#5B9F01] transition duration-300">Blog</a>
                    </li>
                    <li><i class="fas fa-chevron-right text-xs text-gray-400"></i></li>
                    <li class="text-[#5B9F01] font-medium truncate max-w-[200px]">{{ Str::limit($blog->name, 30) }}</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Blog Detail Content --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="lg:flex lg:gap-10">

                {{-- Main Content --}}
                <div class="lg:w-2/3">
                    <article class="bg-white rounded-2xl shadow-md overflow-hidden">

                        {{-- Featured Image --}}
                        @if($blog->image_url)
                            <img
                                src="{{ $blog->image_url }}"
                                alt="{{ $blog->name }}"
                                class="w-full h-72 md:h-96 object-cover"
                            >
                        @else
                            <div class="w-full h-72 md:h-96 bg-gradient-to-br from-[#5B9F01]/20 to-[#5B9F01]/5 flex items-center justify-center">
                                <i class="fas fa-newspaper text-7xl text-[#5B9F01]/30"></i>
                            </div>
                        @endif

                        {{-- Article Body --}}
                        <div class="p-6 md:p-10">

                            {{-- Meta Info --}}
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-400 mb-6 pb-6 border-b border-gray-100">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-calendar-alt text-[#5B9F01]"></i>
                                    {{ $blog->created_at->format('d M, Y') }}
                                </span>
                                @if($blog->meta_keywords)
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-tag text-[#5B9F01]"></i>
                                        {{ $blog->meta_keywords }}
                                    </span>
                                @endif
                            </div>

                            {{-- Title --}}
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 leading-tight">
                                {{ $blog->name }}
                            </h1>

                            {{-- Description / Content --}}
                            <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed blog-content">
                                {!! $blog->description !!}
                            </div>

                            {{-- Tags --}}
                            @if($blog->meta_tags)
                                <div class="mt-8 pt-6 border-t border-gray-100">
                                    <span class="text-sm font-semibold text-gray-700 mr-3">Tags:</span>
                                    @foreach(explode(',', $blog->meta_tags) as $tag)
                                        <span class="inline-block bg-[#5B9F01]/10 text-[#5B9F01] text-xs font-medium px-3 py-1 rounded-full mr-2 mb-2">
                                            {{ trim($tag) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Share / Back Button --}}
                            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between flex-wrap gap-4">
                                <a
                                    href="{{ route('blogs') }}"
                                    class="inline-flex items-center gap-2 text-sm font-semibold text-[#5B9F01] hover:underline"
                                >
                                    <i class="fas fa-arrow-left text-xs"></i>
                                    Back to Blogs
                                </a>

                                {{-- Social Share --}}
                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-gray-500 font-medium">Share:</span>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                       target="_blank"
                                       class="w-9 h-9 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition text-sm">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->name) }}"
                                       target="_blank"
                                       class="w-9 h-9 bg-sky-500 text-white rounded-full flex items-center justify-center hover:bg-sky-600 transition text-sm">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a href="https://wa.me/?text={{ urlencode($blog->name . ' ' . request()->url()) }}"
                                       target="_blank"
                                       class="w-9 h-9 bg-green-500 text-white rounded-full flex items-center justify-center hover:bg-green-600 transition text-sm">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>

                {{-- Sidebar --}}
                <aside class="lg:w-1/3 mt-10 lg:mt-0 space-y-8">

                    {{-- Related Blogs --}}
                    @if($relatedBlogs->count() > 0)
                        <div class="bg-white rounded-2xl shadow-md p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-5 pb-3 border-b border-gray-100">
                                Related <span class="text-[#5B9F01]">Articles</span>
                            </h3>
                            <div class="space-y-5">
                                @foreach($relatedBlogs as $related)
                                    <a href="{{ route('blog.detail', $related->slug) }}" class="flex gap-4 group">
                                        {{-- Thumbnail --}}
                                        @if($related->image_url)
                                            <img
                                                src="{{ $related->image_url }}"
                                                alt="{{ $related->name }}"
                                                class="w-20 h-16 object-cover rounded-xl flex-shrink-0 group-hover:scale-105 transition-transform duration-300"
                                            >
                                        @else
                                            <div class="w-20 h-16 bg-[#5B9F01]/10 rounded-xl flex-shrink-0 flex items-center justify-center">
                                                <i class="fas fa-newspaper text-xl text-[#5B9F01]/40"></i>
                                            </div>
                                        @endif

                                        {{-- Info --}}
                                        <div class="flex flex-col justify-center">
                                            <span class="text-xs text-gray-400 mb-1">
                                                <i class="fas fa-calendar-alt text-[#5B9F01] mr-1"></i>
                                                {{ $related->created_at->format('d M, Y') }}
                                            </span>
                                            <h4 class="text-sm font-semibold text-gray-700 group-hover:text-[#5B9F01] transition-colors duration-300 leading-snug line-clamp-2">
                                                {{ $related->name }}
                                            </h4>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- CTA Card --}}
                    <div class="bg-[#5B9F01] rounded-2xl p-6 text-white text-center shadow-md">
                        <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-tools text-2xl text-white"></i>
                        </div>
                        <h4 class="text-xl font-bold mb-2">Need a Repair?</h4>
                        <p class="text-white/80 text-sm mb-5">Get your device fixed by our expert technicians today!</p>
                        <a href="{{ route('contact-us') }}"
                           class="inline-block bg-white text-[#5B9F01] font-bold px-6 py-2.5 rounded-full text-sm hover:bg-gray-100 transition duration-300 shadow">
                            Contact Us
                        </a>
                    </div>
                </aside>

            </div>
        </div>
    </section>

    {{-- Blog Content Styling --}}
    <style>
        .blog-content p { margin-bottom: 1rem; }
        .blog-content h2 { font-size: 1.5rem; font-weight: 700; color: #1f2937; margin: 1.5rem 0 0.75rem; }
        .blog-content h3 { font-size: 1.25rem; font-weight: 600; color: #374151; margin: 1.25rem 0 0.5rem; }
        .blog-content ul { list-style: disc; padding-left: 1.5rem; margin-bottom: 1rem; }
        .blog-content ol { list-style: decimal; padding-left: 1.5rem; margin-bottom: 1rem; }
        .blog-content li { margin-bottom: 0.4rem; color: #4b5563; }
        .blog-content a { color: #5B9F01; text-decoration: underline; }
        .blog-content img { border-radius: 0.75rem; margin: 1rem 0; max-width: 100%; }
        .blog-content blockquote {
            border-left: 4px solid #5B9F01;
            padding: 0.75rem 1rem;
            background: #f0f7e6;
            border-radius: 0 0.5rem 0.5rem 0;
            margin: 1rem 0;
            font-style: italic;
            color: #374151;
        }
    </style>
</div>
