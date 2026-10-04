<div class="p-6">

    {{-- HEADER --}}
    <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Sitemap Generator</h1>
            <p class="text-sm text-gray-500 mt-1">
                Auto-generated sitemap with <strong>{{ $totalUrls }}</strong> URLs
                &nbsp;·&nbsp; Last updated: {{ $lastUpdated }}
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            {{-- Download XML --}}
            <a href="{{ route('admin.sitemap.download') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12v6m0 0l-3-3m3 3l3-3M12 3v9"/>
                </svg>
                Download XML
            </a>

            {{-- sitemap.xml link --}}
            <a href="{{ url('/sitemap.xml') }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-gray-50 text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                sitemap.xml
            </a>

            {{-- HTML Toggle --}}
            <button wire:click="$set('type','html')"
                    class="px-4 py-2 text-sm font-medium rounded border
                           {{ $type === 'html' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
                HTML
            </button>

            {{-- XML Toggle --}}
            <button wire:click="$set('type','xml')"
                    class="px-4 py-2 text-sm font-medium rounded border
                           {{ $type === 'xml' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
                XML
            </button>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-blue-50 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-blue-600">{{ count($staticPages) }}</p>
            <p class="text-sm text-gray-500 mt-1">Static Pages</p>
        </div>
        <div class="bg-green-50 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-green-600">{{ $totalBlogs }}</p>
            <p class="text-sm text-gray-500 mt-1">Blogs</p>
        </div>
        <div class="bg-purple-50 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-purple-600">{{ $totalCategories }}</p>
            <p class="text-sm text-gray-500 mt-1">Categories</p>
        </div>
        <div class="bg-gray-100 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-gray-700">{{ $totalUrls }}</p>
            <p class="text-sm text-gray-500 mt-1">Total URLs</p>
        </div>
    </div>

    {{-- HTML VIEW --}}
    @if($type === 'html')

        {{-- Static Pages --}}
        <div class="mb-8">
            <h2 class="text-lg font-semibold mb-3 flex items-center gap-2">
                <span>📄</span> Static Pages ({{ count($staticPages) }})
            </h2>
            <hr class="mb-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($staticPages as $page)
                    <div class="border rounded-lg p-4 bg-white hover:shadow-sm transition">
                        <p class="font-semibold text-gray-800">{{ $page['title'] }}</p>
                        <p class="text-sm text-gray-400 mt-1 truncate">{{ $page['url'] }}</p>
                        <div class="flex items-center justify-between mt-3">
                            <div class="flex gap-2">
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                    {{ $page['priority'] >= 0.9 ? 'bg-green-100 text-green-700' : ($page['priority'] >= 0.7 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                                    {{ $page['priority'] }}
                                </span>
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                    {{ $page['changefreq'] === 'daily' ? 'bg-orange-100 text-orange-700' : ($page['changefreq'] === 'weekly' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600') }}">
                                    {{ $page['changefreq'] }}
                                </span>
                            </div>
                            <a href="{{ $page['url'] }}" target="_blank"
                               class="text-xs text-blue-500 hover:underline flex items-center gap-1">
                                Visit ↗
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Blogs --}}
        <div class="mb-8">
            <h2 class="text-lg font-semibold mb-3 flex items-center gap-2">
                <span>📝</span> Blogs ({{ $totalBlogs }})
            </h2>
            <hr class="mb-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @forelse($dynamicPages['blogs'] as $item)
                    <div class="border rounded-lg p-4 bg-white hover:shadow-sm transition">
                        <p class="font-semibold text-gray-800">{{ $item['title'] }}</p>
                        <p class="text-sm text-gray-400 mt-1 truncate">{{ $item['url'] }}</p>
                        <div class="flex items-center justify-between mt-3">
                            <div class="flex gap-2">
                                <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full font-medium">{{ $item['priority'] }}</span>
                                <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full font-medium">{{ $item['changefreq'] }}</span>
                            </div>
                            <a href="{{ $item['url'] }}" target="_blank" class="text-xs text-blue-500 hover:underline">Visit ↗</a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm">Koi blog nahi mila</p>
                @endforelse
            </div>
        </div>

        {{-- Categories --}}
        <div class="mb-8">
            <h2 class="text-lg font-semibold mb-3 flex items-center gap-2">
                <span>📁</span> Categories ({{ $totalCategories }})
            </h2>
            <hr class="mb-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @forelse($dynamicPages['categories'] as $item)
                    <div class="border rounded-lg p-4 bg-white hover:shadow-sm transition">
                        <p class="font-semibold text-gray-800">{{ $item['title'] }}</p>
                        <p class="text-sm text-gray-400 mt-1 truncate">{{ $item['url'] }}</p>
                        <div class="flex items-center justify-between mt-3">
                            <div class="flex gap-2">
                                <span class="text-xs px-2 py-0.5 bg-purple-100 text-purple-700 rounded-full font-medium">{{ $item['priority'] }}</span>
                                <span class="text-xs px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full font-medium">{{ $item['changefreq'] }}</span>
                            </div>
                            <a href="{{ $item['url'] }}" target="_blank" class="text-xs text-blue-500 hover:underline">Visit ↗</a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm">Koi category nahi mili</p>
                @endforelse
            </div>
        </div>

        {{-- Products --}}
        <div class="mb-8">
            <h2 class="text-lg font-semibold mb-3 flex items-center gap-2">
                <span>🛍️</span> Products ({{ $totalProducts }})
            </h2>
            <hr class="mb-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @forelse($dynamicPages['products'] as $item)
                    <div class="border rounded-lg p-4 bg-white hover:shadow-sm transition">
                        <p class="font-semibold text-gray-800">{{ $item['title'] }}</p>
                        <p class="text-sm text-gray-400 mt-1 truncate">{{ $item['url'] }}</p>
                        <div class="flex items-center justify-between mt-3">
                            <div class="flex gap-2">
                                <span class="text-xs px-2 py-0.5 bg-green-100 text-green-700 rounded-full font-medium">{{ $item['priority'] }}</span>
                                <span class="text-xs px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full font-medium">{{ $item['changefreq'] }}</span>
                            </div>
                            <a href="{{ $item['url'] }}" target="_blank" class="text-xs text-blue-500 hover:underline">Visit ↗</a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm">Koi product nahi mila</p>
                @endforelse
            </div>
        </div>

    {{-- XML PREVIEW --}}
    @elseif($type === 'xml')
        <div class="mb-4">
            <h2 class="text-lg font-semibold mb-3">XML Preview</h2>
            <pre class="bg-gray-900 text-green-400 text-xs p-4 rounded-lg overflow-auto max-h-[600px] leading-relaxed">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"&gt;
@foreach($urls as $url)  &lt;url&gt;
    &lt;loc&gt;{{ e($url['url']) }}&lt;/loc&gt;
    &lt;lastmod&gt;{{ $url['lastmod'] }}&lt;/lastmod&gt;
    &lt;changefreq&gt;{{ $url['changefreq'] }}&lt;/changefreq&gt;
    &lt;priority&gt;{{ $url['priority'] }}&lt;/priority&gt;
  &lt;/url&gt;
@endforeach&lt;/urlset&gt;</pre>
        </div>
    @endif

</div>
