<div>
    <!-- Breadcrumb Section -->
    <div class="breadcumb-wrapper relative bg-gray-100 py-16 md:py-20 overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%235B9F01" fill-opacity="0.05"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>

        <div class="container mx-auto px-4 max-w-7xl relative z-10">
            <div class="text-center">
                <!-- Page Title -->
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800 mb-3">
                    {{ $policy->title ?? 'Policy' }}
                </h1>

                <!-- Breadcrumb Navigation -->
                <ul class="flex items-center justify-center gap-2 text-sm text-gray-500">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-[#5B9F01] transition duration-300">
                            Home
                        </a>
                    </li>
                    <li>
                        <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    </li>
                    <li class="text-[#5B9F01] font-medium">
                        {{ $policy->title ?? 'Policy' }}
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Policy Content -->
    <div class="container mx-auto px-4 max-w-7xl py-12">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-6 md:p-10">
                @if($policy)
                    <!-- Policy Content -->
                    <div class="prose prose-lg max-w-none">
                        {!! $policy->description !!}
                    </div>

                    <!-- Last Updated -->
                    @if($policy->updated_at)
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <p class="text-sm text-gray-500">
                                <i class="far fa-calendar-alt mr-2"></i>
                                Last Updated: {{ $policy->updated_at->format('F d, Y') }}
                            </p>
                        </div>
                    @endif
                @else
                    <!-- No Policy Found -->
                    <div class="text-center py-12">
                        <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Policy Not Found</h3>
                        <p class="text-gray-500">The policy you're looking for could not be found.</p>
                        <a href="{{ route('home') }}" class="inline-block mt-4 px-6 py-3 bg-[#5B9F01] text-white rounded-lg hover:bg-[#4a7e01] transition-colors">
                            Return Home
                        </a>
                    </div>
                @endif
            </div>
        </div>

       
    </div>
</div>