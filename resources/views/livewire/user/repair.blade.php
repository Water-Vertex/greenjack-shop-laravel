<div>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Device Repair Request</h1>
            <p class="text-gray-600 mt-2">Complete the form below to schedule your device repair</p>
        </div>

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

        <!-- Form Wizard Content -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-6 md:p-8">
                <!-- Step 1: Select Category -->
                @if($currentStep == 1)
                <div>
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Please Choose Device Category</h2>
                        <p class="text-gray-500">Select the type of device you need repaired</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($categories as $category)
                        <button
                            wire:click="selectCategory({{ $category->id }})"
                            class="group relative p-6 bg-white border-2 rounded-xl text-left hover:shadow-lg transition-all duration-300
                                {{ $selectedCategory == $category->id ? 'border-[#5C9F01] bg-green-50' : 'border-gray-200 hover:border-[#5C9F01]' }}"
                        >
                            <div class="flex flex-col items-center text-center">
                                <div class="w-16 h-16 mb-3 flex items-center justify-center">
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="mx-auto mb-2 object-contain">
                                    @else
                                        <svg class="w-12 h-12 {{ $selectedCategory == $category->id ? 'text-[#5C9F01]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <h3 class="font-semibold text-gray-900">{{ $category->name }}</h3>
                                <p class="text-xs text-gray-500 mt-1">{!! Str::limit($category->description ?? '', 50) !!}</p>
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Step 2: Select Brand -->
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
                                 <img src="{{ asset('storage/' . $brand->featured_image) }}" alt="{{ $brand->name }}" class="mx-auto mb-2 object-contain">
                            @else
                                <div class="w-12 h-12 mx-auto mb-2 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7h-8.5a.5.5 0 01-.5-.5V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h13.5a.5.5 0 00.5-.5V7z"></path>
                                    </svg>
                                </div>
                            @endif
                            <span class="text-sm font-medium text-gray-900">{{ $brand->name }}</span>
                            @if($selectedBrand == $brand->id)
                                <div class="mt-1">
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

 <!-- Step 3: Select Model with Series Support -->
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
    <div class="space-y-6 max-h-[600px] overflow-y-auto p-1">

        <!-- Display Series Accordions -->
        @foreach($seriesList as $series)
        <div class="border border-gray-200 rounded-xl overflow-hidden bg-white">
            <!-- Series Header -->
            <button
                wire:click="toggleSeries({{ $series->id }})"
                class="w-full p-3 bg-gradient-to-r from-gray-50 to-white hover:from-gray-100 transition-all duration-200 flex justify-between items-center"
            >
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-[#5C9F01] bg-opacity-10 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-[#5C9F01]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="font-bold text-gray-900 text-sm">{{ $series->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $series->models->count() }} models available</p>
                    </div>
                </div>
                <div class="transform transition-transform {{ $expandedSeries == $series->id ? 'rotate-180' : '' }}">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </button>

            <!-- Series Models (Collapsible) -->
            @if($expandedSeries == $series->id)
            <div class="border-t border-gray-100 p-3 bg-gray-50">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    @foreach($series->models as $model)
                    <button
                        wire:click="selectModel({{ $model->id }})"
                        class="group bg-white border-2 rounded-lg hover:shadow-md transition-all duration-300 overflow-hidden
                            {{ $selectedModel == $model->id ? 'border-[#5C9F01] bg-green-50' : 'border-gray-200 hover:border-[#5C9F01]' }}"
                    >
                        <div class="w-full bg-gray-50 flex items-center justify-center p-3">
                            @if($model->featured_image)
                                <img src="{{ asset('storage/' . $model->featured_image) }}"
                                     alt="{{ $model->name }}"
                                     class="w-20 h-20 sm:w-20 sm:h-20 object-contain transition-transform duration-300 group-hover:scale-105">
                            @else
                                <div class="w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center">
                                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-2 text-center border-t border-gray-100">
                            <h4 class="font-semibold text-gray-900 text-xs sm:text-sm truncate">{{ $model->name }}</h4>
                            @if(property_exists($model, 'model_number') && $model->model_number)
                                <p class="text-xs text-gray-500 truncate">{{ $model->model_number }}</p>
                            @endif
                        </div>
                    </button>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endforeach

        <!-- Display Standalone Models (Not in any series) -->
        @if($this->filtered_models->count() > 0)
        <div class="border border-gray-200 rounded-xl overflow-hidden bg-white">
            <div class="p-3 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Other Models</h3>
                        <p class="text-xs text-gray-500">Standalone models not in any series</p>
                    </div>
                </div>
            </div>
            <div class="p-3 bg-gray-50">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    @foreach($this->filtered_models as $model)
                    <button
                        wire:click="selectModel({{ $model->id }})"
                        class="group bg-white border-2 rounded-lg hover:shadow-md transition-all duration-300 overflow-hidden
                            {{ $selectedModel == $model->id ? 'border-[#5C9F01] bg-green-50' : 'border-gray-200 hover:border-[#5C9F01]' }}"
                    >
                        <div class="w-full bg-gray-50 flex items-center justify-center p-3">
                            @if($model->featured_image)
                                <img src="{{ asset('storage/' . $model->featured_image) }}"
                                     alt="{{ $model->name }}"
                                     class="w-20 h-20 sm:w-20 sm:h-20 object-contain transition-transform duration-300 group-hover:scale-105">
                            @else
                                <div class="w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center">
                                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-2 text-center border-t border-gray-100">
                            <h4 class="font-semibold text-gray-900 text-xs sm:text-sm truncate">{{ $model->name }}</h4>
                            @if(property_exists($model, 'model_number') && $model->model_number)
                                <p class="text-xs text-gray-500 truncate">{{ $model->model_number }}</p>
                            @endif
                        </div>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- No Models Message -->
        @if($seriesList->isEmpty() && $this->filtered_models->isEmpty())
        <div class="text-center py-12 bg-gray-50 rounded-xl">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-gray-500">No models found</p>
            @if($modelSearch)
                <p class="text-sm text-gray-400 mt-1">Try a different search term</p>
            @else
                <p class="text-sm text-gray-400 mt-1">No models available for this brand</p>
            @endif
        </div>
        @endif
    </div>

    <!-- Selected Model Summary -->
    @if($selectedModel)
    <div class="mt-6 p-3 bg-[#5C9F01] bg-opacity-10 rounded-xl border border-[#5C9F01]">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 text-[#5C9F01]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <div>
                    <p class="text-xs text-gray-600">Selected Model:</p>
                    <p class="font-semibold text-gray-900 text-sm">{{ $this->model_name }}</p>
                </div>
            </div>
            <button wire:click="$set('selectedModel', null)" class="text-red-500 hover:text-red-700 text-xs">
                Change
            </button>
        </div>
    </div>
    @endif
</div>
@endif

                <!-- Step 4: Select Problems (Two Column Layout) -->
               @if($currentStep == 4)
    <div>
        <div class="mb-6">
            <div class="text-sm text-[#5C9F01] mb-2">YOUR DEVICE: {{ $this->category_name }} {{ $this->brand_name }} {{ $this->model_name }}</div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Please Choose Device Problems</h2>
            <p class="text-gray-500">Select all issues you're experiencing with your device</p>
        </div>

        <div class="mb-6">
            <div class="relative">
                <input
                    type="text"
                    wire:model.live.debounce="problemSearch"
                    placeholder="Search problems..."
                    class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                >
                <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                @if($problemSearch)
                    <button wire:click="$set('problemSearch', '')" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-2 max-h-[500px] overflow-y-auto pr-2">
                <h3 class="text-md font-semibold text-gray-900 sticky top-0 bg-white py-2 z-10">Available Problems</h3>
                <div class="grid grid-cols-2 gap-3">
                    @forelse($this->filtered_problems->sortByDesc('is_featured') as $problem)
                        <button
                            type="button"
                            wire:click="toggleProblem({{ $problem->id }})"
                            class="w-full p-3 bg-white border rounded-xl text-left hover:bg-green-50 transition-all duration-200
                                {{ in_array($problem->id, $selectedProblems) ? 'border-[#5C9F01] bg-green-50' : 'border-gray-200 hover:border-[#5C9F01]' }}"
                        >
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <div class="mr-2">
                                            @if(in_array($problem->id, $selectedProblems))
                                                <div class="w-4 h-4 bg-[#5C9F01] rounded-full flex items-center justify-center">
                                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full"></div>
                                            @endif
                                        </div>
                                        <div>
                                            @if($problem->is_featured)
                                                <div class="flex items-center gap-1">
                                                    <span class="font-medium text-gray-900 text-sm"><b>{{ $problem->name }}</b></span>
                                                    {{-- <span class="text-xs bg-yellow-100 text-yellow-800 px-1.5 py-0.5 rounded">Featured</span> --}}
                                                </div>
                                            @else
                                                <span class="font-medium text-gray-900 text-sm">{{ $problem->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right ml-2">
                                    @if($problem->discounted_price)
                                        <p class="text-xs text-gray-500 line-through">${{ number_format($problem->price, 2) }}</p>
                                        <p class="text-xs font-semibold text-green-600">${{ number_format($problem->discounted_price, 2) }}</p>
                                    @elseif($problem->price)
                                        <p class="text-xs font-semibold text-gray-900">${{ number_format($problem->price, 2) }}</p>
                                    @else
                                        <p class="text-xs text-gray-500">Call for price</p>
                                    @endif
                                </div>
                            </div>
                        </button>
                    @empty
                        <div class="col-span-2 text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-500">No problems found for this device</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-3 sticky top-4 h-[500px] overflow-y-auto">
                <h3 class="text-sm font-semibold text-gray-900 mb-3 sticky top-0 bg-gray-50 py-1">Selected Problems</h3>

                @if(count($selectedProblems) > 0)
                    <div class="space-y-2">
                        @foreach($formData['problems'] as $problemId => $problem)
                            <div class="bg-white p-2 rounded-lg border border-gray-200 shadow-sm">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900 text-sm">{{ $problem['name'] }}</p>
                                        @if($problem['description'])
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $problem['description'] }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right ml-2">
                                        <p class="font-semibold text-green-600 text-sm">${{ number_format($problem['price'], 2) }}</p>
                                        <button type="button" wire:click="removeProblem({{ $problemId }})" class="text-red-500 hover:text-red-700 text-xs">
                                            <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-3 border-t-2 border-gray-300">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs text-gray-600">Subtotal:</span>
                            <span class="text-sm font-semibold text-gray-900">${{ number_format($this->total_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs text-gray-600">Tax (estimated):</span>
                            <span class="text-sm font-semibold text-gray-900">${{ number_format($this->total_price * 0.1, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                            <span class="text-sm font-bold text-gray-900">Total Estimate:</span>
                            <span class="text-base font-bold text-green-600">${{ number_format($this->total_price * 1.1, 2) }}</span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500 text-sm">No problems selected yet</p>
                        <p class="text-xs text-gray-400 mt-1">Click on problems from the left panel to add them here</p>
                    </div>
                @endif
            </div>
        </div>

        @if(count($selectedProblems) > 0)
            <div class="mt-3 p-2 bg-[#5C9F01] bg-opacity-10 rounded-lg border border-[#5C9F01]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-[#5C9F01]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-xs text-white">{{ count($selectedProblems) }} problem(s) selected</span>
                    </div>
                    <div class="text-xs font-semibold text-[#5C9F01]">
                        Total: ${{ number_format($this->total_price, 2) }}
                    </div>
                </div>
            </div>
        @endif
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                            <input type="text" wire:model="formData.first_name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                                placeholder="Enter your first name">
                            @error('formData.first_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                            <input type="text" wire:model="formData.last_name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                                placeholder="Enter your last name">
                            @error('formData.last_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" wire:model="formData.email"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                                placeholder="Enter your email address">
                            @error('formData.email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Mobile Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Number *</label>
                            <input type="tel" wire:model="formData.mobile"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                                placeholder="Enter your mobile number">
                            @error('formData.mobile') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- IMEI Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">IMEI Number</label>
                            <input type="text" wire:model="formData.imei"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                                placeholder="Enter your device IMEI (Optional)">
                            @error('formData.imei') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Address Section -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <input type="text" wire:model="formData.address"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                            placeholder="Enter your address">
                        @error('formData.address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
                        <!-- Zip Code -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Zip Code</label>
                            <input type="text" wire:model="formData.zip_code"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                                placeholder="Enter your zip code">
                            @error('formData.zip_code') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <input type="text" wire:model="formData.city"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                                placeholder="Enter your city">
                            @error('formData.city') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- State -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                            <input type="text" wire:model="formData.state"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                                placeholder="Enter your state">
                            @error('formData.state') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Country -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                            <input type="text" wire:model="formData.country"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                                placeholder="Enter your country">
                            @error('formData.country') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea wire:model="formData.message" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                            placeholder="Please provide us with a detailed description of the issue with your device"></textarea>
                        @error('formData.message') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="mt-6">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="formData.terms" class="mr-3 text-[#5C9F01] focus:ring-[#5C9F01] rounded">
                            <span class="text-sm text-gray-700">I accept the <a href="#" class="text-[#5C9F01] hover:underline">terms & conditions</a> *</span>
                        </label>
                        @error('formData.terms') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                @endif

                <!-- Navigation Buttons -->
                <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
                    @if($currentStep > 1)
                    <button
                        wire:click="previousStep"
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
                        class="px-6 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600 transition-colors font-medium flex items-center space-x-2"
                    >
                        <span>Submit Request</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session()->has('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" class="fixed bottom-4 right-4 z-50">
            <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" class="fixed bottom-4 right-4 z-50">
            <div class="bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('scrollToTop', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    </script>
</div>
