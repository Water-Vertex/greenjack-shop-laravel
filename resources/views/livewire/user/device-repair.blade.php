<!-- Dynamic Category View with Complete Repair Calculator - Amazon Inspired Professional UI -->
<div class="bg-[#EAEDED] min-h-screen font-['Amazon Ember', 'Helvetica Neue', Arial, sans-serif]">

    <!-- Amazon Top Bar Breadcrumb -->
    <div class="bg-white border-b border-gray-200 sticky top-0 z-20">
        <div class="container mx-auto px-4 max-w-7xl py-3">
            <div class="flex items-center gap-1 text-xs text-[#007185]">
                <a href="/" class="hover:text-[#C7511F] hover:underline">Home</a>
                <span class="text-gray-400 mx-1">›</span>
                <a href="#" class="hover:text-[#C7511F] hover:underline">Services</a>
                <span class="text-gray-400 mx-1">›</span>
                <span class="text-gray-700 font-medium">{{ $category->name }} Repair</span>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 max-w-7xl py-8">

        <!-- Category Header Section with Small Image on Left -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="flex flex-row">
                <!-- Category Image - Small on Left -->
                @if($category->image)
                <div class="w-32 md:w-48 bg-gray-50 p-4 flex items-center justify-center border-r border-gray-200">
                    <img src="{{ asset('storage/'. $category->image) }}"
                         alt="{{ $category->name }}"
                         class="w-full h-auto object-contain max-h-24 md:max-h-32">
                </div>
                @endif

                <!-- Category Details -->
                <div class="flex-1 p-4 md:p-6">
                    <h1 class="text-xl md:text-2xl font-bold text-[#0F1111] mb-2">
                        {{ $category->name }} Repair Services
                    </h1>
                    @if($category->description)
                    <div class="text-sm text-gray-600 leading-relaxed">
                        {!! Str::limit($category->description, 200) !!}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content - Complete Repair Calculator -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 md:p-8">

                <!-- Progress Steps -->
                <div class="mb-8 overflow-x-auto">
                    <div class="flex items-center justify-between min-w-max">
                        @php
                            $steps = [
                                1 => 'Category',
                                2 => 'Brand',
                                3 => 'Model',
                                4 => 'Problem',
                                5 => 'Service Type',
                                6 => 'Date & Time',
                                7 => 'Your Details'
                            ];
                        @endphp

                        @foreach($steps as $num => $label)
                            <div class="flex items-center">
                                <button
                                    wire:click="goToStep({{ $num }})"
                                    class="flex flex-col items-center group"
                                    {{ $num > $currentStep ? 'disabled' : '' }}
                                >
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold transition-all
                                        {{ $currentStep >= $num ? 'bg-[#5C9F01] text-white shadow-lg' : 'bg-gray-200 text-gray-500' }}
                                        {{ $num < $currentStep ? 'bg-green-500' : '' }}
                                        {{ $num > $currentStep ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:scale-105' }}
                                    ">
                                        @if($num < $currentStep)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @else
                                            {{ $num }}
                                        @endif
                                    </div>
                                    <span class="text-xs mt-2 {{ $currentStep >= $num ? 'text-[#5C9F01] font-medium' : 'text-gray-400' }}">
                                        {{ $label }}
                                    </span>
                                </button>
                                @if(!$loop->last)
                                    <div class="w-12 h-0.5 mx-2 {{ $currentStep > $num ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Step Indicators -->
                <div class="mb-6">
                    <div class="flex items-center space-x-2 text-sm">
                        <span class="text-gray-500">Step {{ $currentStep }} of 7:</span>
                        <span class="font-semibold text-[#5C9F01]">{{ $steps[$currentStep] }}</span>
                    </div>
                </div>

                <!-- Step 1: Select Category -->
                @if($currentStep == 1)
                <div>
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Please Choose Device Category</h2>
                        <p class="text-gray-500">Select the type of device you need repaired</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($categories as $cat)
                        <button
                            wire:click="selectCategory({{ $cat->id }})"
                            class="group relative p-6 bg-white border-2 rounded-xl text-left hover:shadow-lg transition-all duration-300
                                {{ $selectedCategory == $cat->id ? 'border-[#5C9F01] bg-green-50' : 'border-gray-200 hover:border-[#5C9F01]' }}"
                        >
                            <div class="flex flex-col items-center text-center">
                                <div class="w-16 h-16 mb-3 flex items-center justify-center">
                                    @if($cat->image)
                                        <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}" class="mx-auto mb-2 object-contain w-full h-full">
                                    @else
                                        <svg class="w-12 h-12 {{ $selectedCategory == $cat->id ? 'text-[#5C9F01]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <h3 class="font-semibold text-gray-900">{{ $cat->name }}</h3>
                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($cat->description ?? '', 50) }}</p>
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Step 2: Brand Selection with Images -->
                @if($currentStep == 2)
                <div>
                    <div class="mb-6">
                        <div class="text-sm text-[#5C9F01] mb-2">YOUR DEVICE: {{ $this->category_name }}</div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Please Choose Device Brand</h2>
                        <p class="text-gray-500">Select the brand of your device</p>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                        @forelse($brands as $brand)
                        <button
                            wire:click="selectBrand({{ $brand->id }})"
                            class="p-4 bg-white border-2 rounded-xl text-center hover:shadow-lg transition-all duration-300
                                {{ $selectedBrand == $brand->id ? 'border-[#5C9F01] bg-green-50' : 'border-gray-200 hover:border-[#5C9F01]' }}"
                        >
                            @if($brand->featured_image)
                                 <img src="{{ asset('storage/' . $brand->featured_image) }}" alt="{{ $brand->name }}" class="w-20 h-20 mx-auto mb-2 object-contain">
                            @else
                                <div class="w-20 h-20 mx-auto mb-2 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7h-8.5a.5.5 0 01-.5-.5V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h13.5a.5.5 0 00.5-.5V7z"></path>
                                    </svg>
                                </div>
                            @endif
                            <span class="text-sm font-medium text-gray-900">{{ $brand->name }}</span>
                            @if($selectedBrand == $brand->id)
                                <div class="mt-2">
                                    <svg class="w-4 h-4 text-[#5C9F01] mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            @endif
                        </button>
                        @empty
                        <div class="col-span-full text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-500">No brands found for this category</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                @endif

                <!-- Step 3: Model Selection with Series Support and Images -->
                @if($currentStep == 3)
                <div>
                    <div class="mb-6">
                        <div class="text-sm text-[#5C9F01] mb-2">YOUR DEVICE: {{ $this->category_name }} {{ $this->brand_name }}</div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Please Choose Device Model</h2>
                        <p class="text-gray-500">Search and select your device model</p>
                    </div>

                    <!-- Search Box -->
                    <div class="mb-6">
                        <div class="relative">
                            <input
                                type="text"
                                wire:model.live.debounce="modelSearch"
                                placeholder="Search device by name or model number..."
                                class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                            >
                            <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            @if($modelSearch)
                                <button wire:click="$set('modelSearch', '')" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Models Display Area -->
                    <div class="space-y-6 max-h-[500px] overflow-y-auto p-1">

                        <!-- Display Series Accordions -->
                        @foreach($seriesList as $series)
                        <div class="border border-gray-200 rounded-xl overflow-hidden bg-white">
                            <button
                                wire:click="toggleSeries({{ $series->id }})"
                                class="w-full p-4 bg-gradient-to-r from-gray-50 to-white hover:from-gray-100 transition-all duration-200 flex justify-between items-center"
                            >
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-[#5C9F01] bg-opacity-10 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-[#5C9F01]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <h3 class="font-bold text-gray-900">{{ $series->name }}</h3>
                                        <p class="text-xs text-gray-500">{{ $series->models->count() }} models available</p>
                                    </div>
                                </div>
                                <div class="transform transition-transform {{ $expandedSeries == $series->id ? 'rotate-180' : '' }}">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </button>

                            @if($expandedSeries == $series->id)
                            <div class="border-t border-gray-100 p-4 bg-gray-50">
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                    @foreach($series->models as $model)
                                    <button
                                        wire:click="selectModel({{ $model->id }})"
                                        class="group bg-white border-2 rounded-lg hover:shadow-md transition-all duration-300 overflow-hidden
                                            {{ $selectedModel == $model->id ? 'border-[#5C9F01] bg-green-50' : 'border-gray-200 hover:border-[#5C9F01]' }}"
                                    >
                                        <div class="w-full bg-gray-50 flex items-center justify-center p-4">
                                            @if($model->featured_image)
                                                <img src="{{ asset('storage/' . $model->featured_image) }}"
                                                     alt="{{ $model->name }}"
                                                     class="w-24 h-24 object-contain transition-transform duration-300 group-hover:scale-105">
                                            @else
                                                <div class="w-24 h-24 flex items-center justify-center">
                                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-3 text-center border-t border-gray-100">
                                            <h4 class="font-semibold text-gray-900 text-sm">{{ $model->name }}</h4>
                                            @if($model->model_number)
                                                <p class="text-xs text-gray-500 mt-1">{{ $model->model_number }}</p>
                                            @endif
                                        </div>
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach

                        <!-- Standalone Models -->
                        @if($this->filtered_models->count() > 0)
                        <div class="border border-gray-200 rounded-xl overflow-hidden bg-white">
                            <div class="p-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900">Other Models</h3>
                                        <p class="text-xs text-gray-500">Standalone models not in any series</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50">
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                    @foreach($this->filtered_models as $model)
                                    <button
                                        wire:click="selectModel({{ $model->id }})"
                                        class="group bg-white border-2 rounded-lg hover:shadow-md transition-all duration-300 overflow-hidden
                                            {{ $selectedModel == $model->id ? 'border-[#5C9F01] bg-green-50' : 'border-gray-200 hover:border-[#5C9F01]' }}"
                                    >
                                        <div class="w-full bg-gray-50 flex items-center justify-center p-4">
                                            @if($model->featured_image)
                                                <img src="{{ asset('storage/' . $model->featured_image) }}"
                                                     alt="{{ $model->name }}"
                                                     class="w-24 h-24 object-contain transition-transform duration-300 group-hover:scale-105">
                                            @else
                                                <div class="w-24 h-24 flex items-center justify-center">
                                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-3 text-center border-t border-gray-100">
                                            <h4 class="font-semibold text-gray-900 text-sm">{{ $model->name }}</h4>
                                            @if($model->model_number)
                                                <p class="text-xs text-gray-500 mt-1">{{ $model->model_number }}</p>
                                            @endif
                                        </div>
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($seriesList->isEmpty() && $this->filtered_models->isEmpty())
                        <div class="text-center py-12 bg-gray-50 rounded-xl">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-500">No models found</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Step 4: Problem Selection -->
                @if($currentStep == 4)
                <div class="px-4 sm:px-0">
    <!-- Header Section -->
    <div class="mb-4 sm:mb-6">
        <div class="text-xs sm:text-sm text-[#5C9F01] mb-1 sm:mb-2 break-words">
            YOUR DEVICE: {{ $this->category_name }} {{ $this->brand_name }} {{ $this->model_name }}
        </div>
        <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-1 sm:mb-2">Please Choose Device Problems</h2>
        <p class="text-sm sm:text-base text-gray-500">Select all issues you're experiencing with your device</p>
    </div>

    <!-- Search Section -->
    <div class="mb-4 sm:mb-6">
        <div class="relative">
            <input
                type="text"
                wire:model.live.debounce="problemSearch"
                placeholder="Search problems..."
                class="w-full px-4 py-2.5 sm:py-3 pl-9 sm:pl-11 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm sm:text-base"
            >
            <svg class="absolute left-2.5 sm:left-3 top-2.5 sm:top-3.5 w-4 sm:w-5 h-4 sm:h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            @if($problemSearch)
                <button wire:click="$set('problemSearch', '')" class="absolute right-2.5 sm:right-3 top-2.5 sm:top-3 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 sm:w-5 h-4 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            @endif
        </div>
    </div>

    <!-- Main Grid - Mobile First -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">

        <!-- Available Problems Section -->
        <div class="space-y-2">
            <h3 class="text-sm sm:text-md font-semibold text-gray-900 bg-white py-1.5 sm:py-2 sticky top-0 z-10">Available Problems</h3>

            <!-- Problems Grid - Responsive columns -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 max-h-[300px] sm:max-h-[500px] overflow-y-auto pr-1 sm:pr-2">
                @forelse($this->filtered_problems->sortByDesc('is_featured') as $problem)
                    <button
                        type="button"
                        wire:click="toggleProblem({{ $problem->id }})"
                        class="w-full p-2.5 sm:p-3 bg-white border rounded-xl text-left hover:bg-green-50 transition-all duration-200
                            {{ in_array($problem->id, $selectedProblems) ? 'border-[#5C9F01] bg-green-50' : 'border-gray-200 hover:border-[#5C9F01]' }}"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start sm:items-center">
                                    <div class="mr-1.5 sm:mr-2 flex-shrink-0 mt-0.5 sm:mt-0">
                                        @if(in_array($problem->id, $selectedProblems))
                                            <div class="w-3.5 h-3.5 sm:w-4 sm:h-4 bg-[#5C9F01] rounded-full flex items-center justify-center">
                                                <svg class="w-2 h-2 sm:w-2.5 sm:h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-3.5 h-3.5 sm:w-4 sm:h-4 border-2 border-gray-300 rounded-full"></div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        @if($problem->is_featured)
                                            <span class="font-medium text-gray-900 text-sm sm:text-sm"><b>{{ $problem->name }}</b></span>
                                            @if($problem->is_recommended)
                                                <span class="text-red-600 text-[10px] sm:text-sm block sm:inline">(Recommended)</span>
                                            @endif
                                        @else
                                            <span class="font-medium text-gray-900 text-xs sm:text-sm">{{ $problem->name }}</span>
                                            @if($problem->is_recommended)
                                                <span class="text-red-600 text-[10px] sm:text-sm block sm:inline">(Recommended)</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-right ml-1 sm:ml-2 flex-shrink-0">
                                @if($problem->discounted_price)
                                    <p class="text-[12px] sm:text-xs text-gray-500 line-through">${{ number_format($problem->price, 2) }}</p>
                                    <p class="text-[12px] sm:text-xs font-semibold text-green-600">${{ number_format($problem->discounted_price, 2) }}</p>
                                @elseif($problem->price)
                                    <p class="text-[12px] sm:text-xs font-semibold text-gray-900">${{ number_format($problem->price, 2) }}</p>
                                @else
                                    <p class="text-[12px] sm:text-xs text-gray-500">Call for price</p>
                                @endif
                            </div>
                        </div>
                    </button>
                @empty
                    <div class="col-span-1 sm:col-span-2 text-center py-8 sm:py-12">
                        <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-300 mx-auto mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm sm:text-base text-gray-500">No problems found for this device</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Selected Problems Section - Mobile Friendly -->
        <div class="bg-gray-50 rounded-xl p-3 sm:p-4 sticky top-0 lg:top-4 max-h-[300px] sm:max-h-[500px] overflow-y-auto mt-4 lg:mt-0">
            <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-2 sm:mb-3 sticky top-0 bg-gray-50 py-1 z-10">Selected Problems</h3>

            @if(count($selectedProblems) > 0)
                <div class="space-y-1.5 sm:space-y-2">
                    @foreach($formData['problems'] as $problemId => $problem)
                        <div class="bg-white p-2 sm:p-3 rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 text-xs sm:text-sm truncate">{{ $problem['name'] }}</p>
                                    @if($problem['description'])
                                        <p class="text-[10px] sm:text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $problem['description'] }}</p>
                                    @endif
                                </div>
                                <div class="text-right ml-1 sm:ml-2 flex-shrink-0">
                                    <p class="font-semibold text-green-600 text-xs sm:text-sm">${{ number_format($problem['price'], 2) }}</p>
                                    <button type="button" wire:click="removeProblem({{ $problemId }})" class="text-red-500 hover:text-red-700 text-[10px] sm:text-xs">
                                        <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pricing Summary - Mobile Optimized -->
                <div class="mt-3 sm:mt-4 pt-2 sm:pt-3 border-t-2 border-gray-300">
                    <div class="flex justify-between items-center mb-0.5 sm:mb-1">
                        <span class="text-[10px] sm:text-xs text-gray-600">Subtotal:</span>
                        <span class="text-xs sm:text-sm font-semibold text-gray-900">${{ number_format($this->total_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-0.5 sm:mb-1">
                        <span class="text-[10px] sm:text-xs text-gray-600">Tax (estimated):</span>
                        <span class="text-xs sm:text-sm font-semibold text-gray-900">${{ number_format($this->total_price * 0.08, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-1.5 sm:pt-2 border-t border-gray-200">
                        <span class="text-xs sm:text-sm font-bold text-gray-900">Total Estimate:</span>
                        <span class="text-sm sm:text-base font-bold text-green-600">${{ number_format($this->total_price * 1.08, 2) }}</span>
                    </div>
                </div>
            @else
                <div class="text-center py-6 sm:py-8">
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-300 mx-auto mb-2 sm:mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-xs sm:text-sm text-gray-500">No problems selected yet</p>
                    <p class="text-[10px] sm:text-xs text-gray-400 mt-1">Click on problems from the left panel to add them here</p>
                </div>
            @endif
        </div>
    </div>
</div>
                @endif

                <!-- Step 5: Service Type -->
                @if($currentStep == 5)
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-6">How did you hear about us?</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer hover:bg-green-50 transition-all
                            {{ $formData['type'] == 'walk_in' ? 'border-[#5C9F01] bg-green-50' : 'border-gray-200' }}">
                            <input type="radio" wire:model="formData.type" value="walk_in" class="mr-3 text-[#5C9F01] focus:ring-[#5C9F01]">
                            <div>
                                <p class="font-medium text-gray-900">Walk In</p>
                                <p class="text-sm text-gray-500">Walked into our store</p>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer hover:bg-green-50 transition-all
                            {{ $formData['type'] == 'referral' ? 'border-[#5C9F01] bg-green-50' : 'border-gray-200' }}">
                            <input type="radio" wire:model="formData.type" value="referral" class="mr-3 text-[#5C9F01] focus:ring-[#5C9F01]">
                            <div>
                                <p class="font-medium text-gray-900">Referral</p>
                                <p class="text-sm text-gray-500">Referred by a friend or family</p>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer hover:bg-green-50 transition-all
                            {{ $formData['type'] == 'friend' ? 'border-[#5C9F01] bg-green-50' : 'border-gray-200' }}">
                            <input type="radio" wire:model="formData.type" value="friend" class="mr-3 text-[#5C9F01] focus:ring-[#5C9F01]">
                            <div>
                                <p class="font-medium text-gray-900">Friend</p>
                                <p class="text-sm text-gray-500">Recommended by a friend</p>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer hover:bg-green-50 transition-all
                            {{ $formData['type'] == 'google_search' ? 'border-[#5C9F01] bg-green-50' : 'border-gray-200' }}">
                            <input type="radio" wire:model="formData.type" value="google_search" class="mr-3 text-[#5C9F01] focus:ring-[#5C9F01]">
                            <div>
                                <p class="font-medium text-gray-900">Google Search</p>
                                <p class="text-sm text-gray-500">Found us on Google</p>
                            </div>
                        </label>
                    </div>
                </div>
                @endif

                <!-- Step 6: Date & Time -->
                @if($currentStep == 6)
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Select Date & Time</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Date *</label>
                            <input type="date" wire:model="formData.date" min="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]">
                            @error('formData.date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Time *</label>
                            <select wire:model="formData.time"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]">
                                <option value="">Select time</option>
                                <option value="Morning 9:00 AM - 12:00 PM">Morning 9:00 AM - 12:00 PM</option>
                                <option value="Afternoon 12:00 PM - 3:00 PM">Afternoon 12:00 PM - 3:00 PM</option>
                                <option value="Evening 3:00 PM - 7:00 PM">Evening 3:00 PM - 7:00 PM</option>
                            </select>
                            @error('formData.time') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                @endif

                <!-- Step 7: Your Details -->
                @if($currentStep == 7)
<div>
    <h2 class="text-xl font-bold text-gray-900 mb-6">Please Enter Your Details</h2>

    <!-- Validation Summary -->
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-sm font-medium text-red-800">Please fix the following errors:</p>
                    <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
            <input type="text" wire:model="formData.first_name"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                placeholder="Enter your first name">
            @error('formData.first_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
            <input type="text" wire:model="formData.last_name"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                placeholder="Enter your last name">
            @error('formData.last_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
            <input type="email" wire:model="formData.email"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                placeholder="Enter your email address">
            @error('formData.email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Number *</label>
            <input type="tel" wire:model="formData.mobile"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                placeholder="Enter your mobile number">
            @error('formData.mobile') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">IMEI Number</label>
            <input type="text" wire:model="formData.imei"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                placeholder="Enter your device IMEI (Optional)">
            @error('formData.imei') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="mt-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
        <input type="text" wire:model="formData.address"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
            placeholder="Enter your address">
        @error('formData.address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Zip Code</label>
            <input type="text" wire:model="formData.zip_code"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                placeholder="Enter your zip code">
            @error('formData.zip_code') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
            <input type="text" wire:model="formData.city"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                placeholder="Enter your city">
            @error('formData.city') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
            <input type="text" wire:model="formData.state"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                placeholder="Enter your state">
            @error('formData.state') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
            <input type="text" wire:model="formData.country"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                placeholder="Enter your country">
            @error('formData.country') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="mt-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
        <textarea wire:model="formData.message" rows="4"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
            placeholder="Please provide us with a detailed description of the issue with your device"></textarea>
        @error('formData.message') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <!-- Order Summary -->
    <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-3">Order Summary</h3>
        <div class="space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Device:</span>
                <span class="font-medium">{{ $this->category_name }} {{ $this->brand_name }} {{ $this->model_name }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Problems:</span>
                <span class="font-medium">{{ count($selectedProblems) }} selected</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Subtotal:</span>
                <span class="font-medium">${{ number_format($this->total_price, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Tax (8%):</span>
                <span class="font-medium">${{ number_format($this->total_price * 0.08, 2) }}</span>
            </div>
            <div class="border-t border-gray-300 pt-2 flex justify-between font-bold">
                <span>Total:</span>
                <span class="text-[#5C9F01]">${{ number_format($this->total_price * 1.08, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Payment Method Selection -->
    <div class="mt-6">
        <label class="block text-sm font-medium text-gray-700 mb-3">Select Payment Method *</label>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer hover:bg-green-50 transition-all
                {{ $paymentMethod == 'stripe' ? 'border-[#5C9F01] bg-green-50' : 'border-gray-200' }}">
                <input type="radio" wire:model="paymentMethod" value="stripe" class="mr-3 text-[#5C9F01] focus:ring-[#5C9F01]">
                <div class="flex items-center">
                    <svg class="w-8 h-8 mr-3" viewBox="0 0 128 128" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M64 0C28.7 0 0 28.7 0 64s28.7 64 64 64 64-28.7 64-64S99.3 0 64 0z" fill="#6772E5"/>
                        <path d="M63.9 22.4C40.8 22.4 22.4 40.8 22.4 63.9s18.4 41.5 41.5 41.5 41.5-18.4 41.5-41.5S87 22.4 63.9 22.4z" fill="white"/>
                        <path d="M63.9 31.1c-18.1 0-32.8 14.7-32.8 32.8s14.7 32.8 32.8 32.8 32.8-14.7 32.8-32.8S82 31.1 63.9 31.1z" fill="#6772E5"/>
                        <path d="M63.9 41.8c-12.2 0-22.1 9.9-22.1 22.1s9.9 22.1 22.1 22.1 22.1-9.9 22.1-22.1-9.9-22.1-22.1-22.1z" fill="white"/>
                        <path d="M63.9 50.5c-7.4 0-13.4 6-13.4 13.4s6 13.4 13.4 13.4 13.4-6 13.4-13.4-6-13.4-13.4-13.4z" fill="#6772E5"/>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Credit Card</p>
                        <p class="text-sm text-gray-500">Pay with Stripe</p>
                    </div>
                </div>
            </label>

            <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer hover:bg-green-50 transition-all
                {{ $paymentMethod == 'paypal' ? 'border-[#5C9F01] bg-green-50' : 'border-gray-200' }}">
                <input type="radio" wire:model="paymentMethod" value="paypal" class="mr-3 text-[#5C9F01] focus:ring-[#5C9F01]">
                <div class="flex items-center">
                    <svg class="w-8 h-8 mr-3" viewBox="0 0 128 128" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M64 0C28.7 0 0 28.7 0 64s28.7 64 64 64 64-28.7 64-64S99.3 0 64 0z" fill="#003087"/>
                        <path d="M70.4 44.2c-2.8 0-5.1 2.3-5.1 5.1s2.3 5.1 5.1 5.1 5.1-2.3 5.1-5.1-2.3-5.1-5.1-5.1z" fill="white"/>
                        <path d="M87.5 44.2c-2.8 0-5.1 2.3-5.1 5.1s2.3 5.1 5.1 5.1 5.1-2.3 5.1-5.1-2.3-5.1-5.1-5.1z" fill="white"/>
                        <path d="M53.3 44.2c-2.8 0-5.1 2.3-5.1 5.1s2.3 5.1 5.1 5.1 5.1-2.3 5.1-5.1-2.3-5.1-5.1-5.1z" fill="white"/>
                        <path d="M36.2 44.2c-2.8 0-5.1 2.3-5.1 5.1s2.3 5.1 5.1 5.1 5.1-2.3 5.1-5.1-2.3-5.1-5.1-5.1z" fill="white"/>
                        <circle cx="50" cy="64" r="24" fill="white"/>
                        <path d="M50 52c-6.6 0-12 5.4-12 12s5.4 12 12 12 12-5.4 12-12-5.4-12-12-12z" fill="#003087"/>
                        <circle cx="78" cy="64" r="24" fill="white"/>
                        <path d="M78 52c-6.6 0-12 5.4-12 12s5.4 12 12 12 12-5.4 12-12-5.4-12-12-12z" fill="#003087"/>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">PayPal</p>
                        <p class="text-sm text-gray-500">Pay with PayPal</p>
                    </div>
                </div>
            </label>
        </div>
        @error('paymentMethod') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>

    <!-- Terms and Conditions -->
    <div class="mt-6">
        <label class="flex items-center">
            <input type="checkbox" wire:model="formData.terms" class="mr-3 text-[#5C9F01] focus:ring-[#5C9F01] rounded">
           @php
    $policy = \App\Models\Policy::where('slug','terms-and-condition')->first();
@endphp
<span class="text-sm text-gray-700">I accept the <a href="@if($policy){{ route('policy', $policy->slug) }}@endif" target="_blank" class="text-[#5C9F01] hover:underline">terms & conditions</a> *</span>
        </label>
        @error('formData.terms') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>
</div>
@endif

                <!-- Navigation Buttons -->
               <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
    @if($currentStep > 1)
    <button
        wire:click="previousStep"
        wire:loading.attr="disabled"
        class="px-6 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors font-medium"
    >
        ← Previous
    </button>
    @else
    <div></div>
    @endif

    @if($currentStep < 7)
    <button
        wire:click="nextStep"
        wire:loading.attr="disabled"
        class="px-6 py-2 text-white bg-[#5C9F01] rounded-lg hover:bg-[#4a7e01] transition-colors font-medium flex items-center space-x-2"
    >
        <span>Next</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>
    @else
    <button
    wire:click="submitForm"
    wire:loading.attr="disabled"
    wire:loading.class="opacity-50 cursor-not-allowed"
    class="w-full sm:w-auto px-4 sm:px-8 py-2.5 sm:py-3 text-white bg-green-500 rounded-lg hover:bg-green-600 transition-colors font-medium flex items-center justify-center sm:justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed text-sm sm:text-base"
>
    <span wire:loading.remove class="flex items-center justify-center">
        <svg class="w-4 h-4 sm:w-5 sm:h-5 inline mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
        Pay Now
    </span>
    <span wire:loading class="flex items-center justify-center">
        <svg class="animate-spin h-4 w-4 sm:h-5 sm:w-5 inline mr-1.5 sm:mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="hidden xs:inline">Processing Payment...</span>
        <span class="xs:hidden">Processing...</span>
    </span>
</button>
    @endif
</div>
            </div>
        </div>
    </div>

    <button onclick="window.scrollTo({top:0,behavior:'smooth'})"
        class="fixed bottom-8 right-8 bg-[#37475A] hover:bg-[#485769] text-white px-4 py-2 rounded-md text-sm transition shadow-lg opacity-0"
        id="backToTopBtn">
        Back to top ↑
    </button>

    <style>
        @import url('https://fonts.cdnfonts.com/css/amazon-ember');

        body {
            font-family: 'Amazon Ember', 'Helvetica Neue', Arial, sans-serif;
        }

        #backToTopBtn {
            transition: opacity 0.3s ease;
        }
    </style>

    <script>
        window.addEventListener('scroll', function() {
            const btn = document.getElementById('backToTopBtn');
            if (window.scrollY > 300) {
                btn.style.opacity = '1';
            } else {
                btn.style.opacity = '0';
            }
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('scrollToTop', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    </script>

</div>
