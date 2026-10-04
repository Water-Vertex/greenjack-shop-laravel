<div>
    <div>
    {{-- resources/views/livewire/admin/seo-settings.blade.php --}}
    <!-- Header Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">SEO Settings</h1>
                <p class="text-sm text-gray-600 mt-1">Manage SEO meta tags and schema for all pages</p>
            </div>

            <!-- Overall SEO Health -->
            <div class="flex items-center space-x-4 bg-gray-50 px-3 sm:px-4 py-2 rounded-lg w-full sm:w-auto">
                <div class="text-left sm:text-right w-full sm:w-auto">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Overall SEO Health</p>
                    <div class="flex items-center space-x-2">
                        <span class="text-base sm:text-lg font-bold {{ $overallHealth['overall_score'] >= 80 ? 'text-green-600' : ($overallHealth['overall_score'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $overallHealth['overall_score'] }}%
                        </span>
                        <span class="text-xs sm:text-sm text-gray-500">
                            ({{ $overallHealth['total_pages'] }} pages)
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Page Selector -->
    <div class="lg:hidden mb-4 sm:mb-6">
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Page</label>
            <select wire:model.live="activeTab" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                @foreach(['home', 'about', 'contact', 'service', 'products', 'faq'] as $page)
                <option value="{{ $page }}">{{ ucfirst($page) }} Page</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Sidebar Navigation - Desktop -->
        <div class="hidden lg:block lg:col-span-1">
            <div class="bg-white rounded-lg border border-gray-200 p-4 sticky top-20">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pages</h3>
                <nav class="space-y-2">
                    @foreach(['home', 'about', 'contact', 'service', 'products', 'faq'] as $page)
                    <button
                        wire:click="switchTab('{{ $page }}')"
                        class="w-full text-left px-4 py-3 rounded-lg transition-colors {{ $activeTab === $page ? 'bg-orange-50 border border-orange-200 text-orange-700' : 'text-gray-600 hover:bg-gray-50' }}"
                    >
                        <div class="flex items-center justify-between">
                            <span class="font-medium capitalize text-sm">{{ $page }} Page</span>
                            <span class="text-xs px-2 py-1 rounded-full {{ $overallHealth['page_scores'][$page] >= 80 ? 'bg-green-100 text-green-800' : ($overallHealth['page_scores'][$page] >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $overallHealth['page_scores'][$page] }}%
                            </span>
                        </div>
                    </button>
                    @endforeach
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <form wire:submit="save">
                    <!-- SEO Analysis Panel -->
                    <div class="border-b border-gray-200 p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-2">
                            <h2 class="text-lg sm:text-xl font-bold text-gray-900 capitalize">{{ $activeTab }} Page SEO Settings</h2>
                            <div class="flex items-center space-x-4">
                                <span class="text-xs sm:text-sm font-medium text-gray-700">Current Score:</span>
                                <span class="text-xl sm:text-2xl font-bold {{ $seoScore >= 80 ? 'text-green-600' : ($seoScore >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $seoScore }}%
                                </span>
                            </div>
                        </div>

                        <!-- Score Bar -->
                        <div class="score-bar bg-gray-200 rounded-full h-2 sm:h-3 mb-4">
                            <div class="score-fill h-2 sm:h-3 rounded-full {{ $seoScore >= 80 ? 'bg-green-500' : ($seoScore >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                 style="width: {{ $seoScore }}%"></div>
                        </div>

                        <!-- SEO Metrics -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3">
                            @foreach($seoMetrics as $metric)
                            <div class="metric flex justify-between items-center p-2 sm:p-3 rounded-lg text-xs sm:text-sm {{ $metric['status'] ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                                <span class="font-medium text-gray-700">{{ $metric['name'] }}</span>
                                <span class="{{ $metric['status'] ? 'text-green-600' : 'text-red-600' }} font-medium">
                                    {{ $metric['message'] }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                        @if(in_array($activeTab, ['home', 'about', 'contact', 'service']))
                        <!-- Page Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Page Title</label>
                            <input
                                type="text"
                                wire:model="{{ $activeTab }}_title"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm"
                                placeholder="Enter page title"
                            >
                        </div>
                        @endif

                        <!-- Meta Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                            <input
                                type="text"
                                wire:model="{{ $activeTab }}_meta_title"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm"
                                placeholder="Meta title for SEO (50-60 characters recommended)"
                            >
                            <div class="flex flex-col sm:flex-row justify-between mt-1 gap-1">
                                <p class="text-xs text-gray-500">Recommended length: 50-60 characters</p>
                                <span class="text-xs {{ strlen(${$activeTab . '_meta_title'}) <= 60 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ strlen(${$activeTab . '_meta_title'}) }}/60
                                </span>
                            </div>
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                            <textarea
                                wire:model="{{ $activeTab }}_meta_description"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm resize-y"
                                placeholder="Meta description for SEO (120-160 characters recommended)"
                            ></textarea>
                            <div class="flex flex-col sm:flex-row justify-between mt-1 gap-1">
                                <p class="text-xs text-gray-500">Recommended length: 150-160 characters</p>
                                <span class="text-xs {{ strlen(${$activeTab . '_meta_description'}) <= 160 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ strlen(${$activeTab . '_meta_description'}) }}/160
                                </span>
                            </div>
                        </div>

                        <!-- Meta Keywords -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                            <input
                                type="text"
                                wire:model="{{ $activeTab }}_meta_keywords"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm"
                                placeholder="keyword1, keyword2, keyword3"
                            >
                            <p class="text-xs text-gray-500 mt-1">Separate keywords with commas</p>
                        </div>

                        <!-- Focus Keyword -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Focus Keyword
                            </label>
                            <input
                                type="text"
                                wire:model.live="{{ $activeTab }}_focus_keyword"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm"
                                placeholder="best digital marketing services"
                            >
                            <div class="mt-2 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-info-circle"></i>
                                    This is the main keyword you want this page to rank for
                                </p>
                                @if(!empty(${$activeTab . '_focus_keyword'}))
                                    <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full whitespace-nowrap">
                                        <i class="fas fa-check-circle"></i> Keyword set: {{ Str::limit(${$activeTab . '_focus_keyword'}, 30) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Meta Tags -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Additional Meta Tags</label>
                            <textarea
                                wire:model="{{ $activeTab }}_meta_tags"
                                rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm resize-y"
                                placeholder='<meta name="robots" content="index, follow">'
                            ></textarea>
                            <p class="text-xs text-gray-500 mt-1">Add additional meta tags in HTML format</p>
                        </div>

                        <!-- Page Schema -->
                        @if(in_array($activeTab, ['home', 'about', 'contact', 'service', 'products']))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Page Schema (JSON-LD)</label>
                            <textarea
                                wire:model="{{ $activeTab }}_page_schema"
                                rows="6"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-mono text-xs sm:text-sm resize-y"
                                placeholder=''
                            ></textarea>
                            <p class="text-xs text-gray-500 mt-1">Add structured data schema for rich snippets</p>
                        </div>
                        @endif
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 p-4 sm:p-6 border-t border-gray-200 bg-gray-50">
                        <button
                            type="submit"
                            class="w-full sm:w-auto px-6 py-2 text-sm font-medium text-white bg-orange-500 rounded-md hover:bg-orange-600 transition-colors flex items-center justify-center sm:justify-start space-x-2"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove wire:target="save">
                                <i class="fas fa-save"></i>
                                <span>Save {{ ucfirst($activeTab) }} Page Settings</span>
                            </span>
                            <span wire:loading wire:target="save" class="flex items-center space-x-2">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                                <span>Saving...</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 left-4 sm:left-auto bg-green-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg transition-all duration-300 z-50">
            <div class="flex items-center space-x-2">
                <i class="fas fa-check-circle"></i>
                <span class="text-sm sm:text-base">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 5000)"
             class="fixed bottom-4 right-4 left-4 sm:left-auto bg-red-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg transition-all duration-300 z-50">
            <div class="flex items-center space-x-2">
                <i class="fas fa-exclamation-circle"></i>
                <span class="text-sm sm:text-base">{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>

<style>
.score-bar {
    background: #e9ecef;
    border-radius: 10px;
    height: 8px;
    overflow: hidden;
}

.score-fill {
    height: 100%;
    transition: width 0.3s ease;
}

.metric {
    transition: all 0.2s ease;
}

.metric:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

/* Mobile responsive */
@media (max-width: 640px) {
    .score-bar {
        height: 6px;
    }

    .metric {
        font-size: 11px;
        padding: 8px 10px;
    }
}

/* Prevent body scroll when modal is open */
body:has(.fixed.inset-0.z-40) {
    overflow: hidden;
}
</style>

</div>
