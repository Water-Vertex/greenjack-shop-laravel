<div>
    @push('seo_meta')
        @if($record->meta_title)
            <title>{{ $record->meta_title }}</title>
            <meta property="og:title" content="{{ $record->meta_title }}">
        @endif
        @if($record->meta_description)
            <meta name="description" content="{{ $record->meta_description }}">
            <meta property="og:description" content="{{ $record->meta_description }}">
        @endif
        @if($record->meta_keywords)
            <meta name="keywords" content="{{ $record->meta_keywords }}">
        @endif
        
        @if($record->image)
            <meta property="og:image" content="{{ $record->image }}">
        @endif
        @if($record->meta_tags)
            {!! $record->meta_tags !!}
        @endif
        @if($record->page_schema)
            <script type="application/ld+json">{!! $record->page_schema !!}</script>
        @endif
    @endpush

    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 md:py-16">

            <!-- HERO -->
            <div class="text-center mb-12 md:mb-16">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900">
                    {{ $record->focus_keyword }}
                </h1>
                @if($record->h1_heading)
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto mt-4 leading-relaxed">
                        {{ $record->h1_heading }}
                    </p>
                @endif
            </div>

            <!-- MAIN IMAGE -->
            @if($record->image)
                <div class="mb-12">
                    <img src="{{ $record->image }}" alt="{{ $record->image_alt ?? '' }}"
                         class="w-full rounded-xl shadow-lg object-cover">
                    @if($record->image_alt)
                        <p class="text-sm text-gray-400 text-center mt-3">{{ $record->image_alt }}</p>
                    @endif
                </div>
            @endif

            <!-- MAIN CONTENT -->
            @if($record->content)
                <div class="prose prose-lg max-w-none mb-12">
                    {!! $record->content !!}
                </div>
            @endif

            <!-- SECTION 1: Image LEFT | Content RIGHT -->
            @if($record->image_left || $record->section_content_left)
                <div class="border-t border-gray-100 my-12"></div>
                <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-start mb-12">
                    <div>
                        @if($record->image_left)
                            <img src="{{ $record->image_left }}" alt="{{ $record->image_left_alt ?? '' }}"
                                 class="w-full rounded-xl shadow-md object-cover">
                            @if($record->image_left_alt)
                                <p class="text-sm text-gray-400 text-center mt-2">{{ $record->image_left_alt }}</p>
                            @endif
                        @endif
                    </div>
                    <div class="prose prose-gray max-w-none">
                        {!! $record->section_content_left !!}
                    </div>
                </div>
            @endif

            <!-- SECTION 2: Content LEFT | Image RIGHT -->
            @if($record->image_right || $record->section_content_right)
                <div class="border-t border-gray-100 my-12"></div>
                <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-start mb-12">
                    <div class="prose prose-gray max-w-none order-2 md:order-1">
                        {!! $record->section_content_right !!}
                    </div>
                    <div class="order-1 md:order-2">
                        @if($record->image_right)
                            <img src="{{ $record->image_right }}" alt="{{ $record->image_right_alt ?? '' }}"
                                 class="w-full rounded-xl shadow-md object-cover">
                            @if($record->image_right_alt)
                                <p class="text-sm text-gray-400 text-center mt-2">{{ $record->image_right_alt }}</p>
                            @endif
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>

<!-- FAQ SECTION -->
@if(count($faqsArray) > 0)
<div class="bg-white py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mt-2 mb-3 text-gray-900">
                Frequently <span class="text-green-600">Asked</span> Questions
            </h2>
        </div>

        <div class="grid md:grid-cols-1 gap-4 max-w-3xl mx-auto">
            @foreach($faqsArray as $index => $faq)
            <div x-data="{ open: false }" class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                <!-- Question -->
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between p-5 text-left focus:outline-none">
                    <span class="text-lg font-semibold text-gray-800">
                        <span class="text-green-500 mr-2">Q.</span> {{ $faq['question'] }}
                    </span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" 
                         :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Answer -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="px-5 pb-5 text-gray-600 border-t border-gray-200 bg-white"
                     style="display: none;">
                    <div class="pt-4">
                        {!! nl2br(e($faq['answer'])) !!}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

    <style>
        .accordion-button:not(.collapsed) {
            background-color: #f8fafc;
            color: #1e40af;
            box-shadow: none;
        }
        .accordion-button:focus {
            box-shadow: none;
            border-color: transparent;
        }
        .accordion-button::after {
            filter: brightness(0.8);
        }
        .prose h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #1f2937;
        }
        .prose h3 {
            font-size: 1.35rem;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            color: #374151;
        }
        .prose p {
            margin-bottom: 1.25rem;
            line-height: 1.7;
        }
        .prose ul, .prose ol {
            padding-left: 1.5rem;
            margin-bottom: 1.25rem;
        }
        .prose a {
            color: #2563eb;
            text-decoration: underline;
        }
    </style>
</div>