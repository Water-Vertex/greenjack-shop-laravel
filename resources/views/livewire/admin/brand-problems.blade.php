<div>
    <!-- Header Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Brand Problems Management</h1>
        <p class="text-sm text-gray-600 mt-1">Select category, brand, and assign multiple problems</p>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6 space-y-4 sm:space-y-5">
        <!-- Category Selection -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Category <span class="text-red-500">*</span>
            </label>
            <select wire:model.live="selectedCategory"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] transition text-sm">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('selectedCategory') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Brand Selection -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Brand <span class="text-red-500">*</span>
            </label>
            <select wire:model.live="selectedBrand"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] transition text-sm {{ !$selectedCategory ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                {{ !$selectedCategory ? 'disabled' : '' }}>
                <option value="">Select Brand</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>
            @error('selectedBrand') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            @if(!$selectedCategory)
                <p class="text-xs text-gray-400 mt-1">Please select a category first</p>
            @endif
        </div>
    </div>

    <!-- Problems Grid -->
    @if($selectedBrand)
        <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Available Problems</h2>
                <div class="flex items-center space-x-3">
                    <span class="text-xs sm:text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                        {{ count($selectedProblems) }} selected
                    </span>
                    @if(count($selectedProblems) > 0)
                        <button type="button" wire:click="clearSelection"
                            class="text-xs text-red-600 hover:text-red-800 transition-colors">
                            <i class="fas fa-times"></i> Clear All
                        </button>
                    @endif
                </div>
            </div>

            @if($problems->count() > 0)
                <form wire:submit="save">
                    <!-- Problems Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                        @foreach($problems as $problem)
                            <label class="border rounded-lg p-3 sm:p-4 cursor-pointer hover:bg-gray-50 transition-colors flex items-start gap-3 {{ in_array($problem->id, $selectedProblems) ? 'border-[#5C9F01] bg-green-50' : '' }}">
                                <input type="checkbox"
                                    wire:model="selectedProblems"
                                    value="{{ $problem->id }}"
                                    class="mt-1 rounded border-gray-300 text-[#5C9F01] focus:ring-[#5C9F01] flex-shrink-0">

                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">{{ $problem->name }}</p>

                                    @if($problem->description)
                                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                            {{ Str::limit($problem->description, 70) }}
                                        </p>
                                    @endif

                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                        <span class="text-xs font-medium text-gray-600">Price:</span>
                                        @if($problem->discounted_price)
                                            <span class="text-xs text-gray-400 line-through">${{ number_format($problem->price, 2) }}</span>
                                            <span class="text-xs sm:text-sm font-semibold text-green-600">${{ number_format($problem->discounted_price, 2) }}</span>
                                        @elseif($problem->price)
                                            <span class="text-xs sm:text-sm font-semibold text-gray-900">${{ number_format($problem->price, 2) }}</span>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @error('selectedProblems')
                        <span class="text-red-500 text-xs block mt-3">{{ $message }}</span>
                    @enderror

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button" wire:click="resetSelections"
                            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors order-2 sm:order-1">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                        <button type="submit"
                            class="w-full sm:w-auto px-5 py-2 bg-[#5C9F01] text-white rounded-md text-sm font-medium hover:bg-[#4a8501] transition-colors flex items-center justify-center space-x-2 order-1 sm:order-2">
                            <span wire:loading.remove wire:target="save">
                                <i class="fas fa-save"></i>
                                <span>Save Selected Problems</span>
                            </span>
                            <span wire:loading wire:target="save" class="flex items-center space-x-2">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                                <span>Saving...</span>
                            </span>
                        </button>
                    </div>
                </form>
            @else
                <!-- Empty State -->
                <div class="text-center py-10 sm:py-16">
                    <i class="fas fa-wrench text-4xl sm:text-5xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500 text-sm sm:text-base">No problems found for this brand.</p>
                    <p class="text-xs text-gray-400 mt-1">Try selecting a different category or brand.</p>
                </div>
            @endif
        </div>
    @else
        <!-- No Brand Selected -->
        <div class="bg-white rounded-lg border border-gray-200 p-8 sm:p-12 text-center">
            <i class="fas fa-hand-pointer text-4xl sm:text-5xl text-gray-300 mb-3"></i>
            <h3 class="text-base sm:text-lg font-semibold text-gray-700">Select a Brand</h3>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Please select a category and brand to view available problems.</p>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 3000)"
            class="fixed bottom-4 right-4 left-4 sm:left-auto bg-green-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg z-50 flex items-center space-x-2">
            <i class="fas fa-check-circle"></i>
            <span class="text-sm sm:text-base">{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            class="fixed bottom-4 right-4 left-4 sm:left-auto bg-red-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg z-50 flex items-center space-x-2">
            <i class="fas fa-exclamation-circle"></i>
            <span class="text-sm sm:text-base">{{ session('error') }}</span>
        </div>
    @endif
</div>

<!-- Additional Styles -->
<style>
    /* Checkbox custom styling */
    input[type="checkbox"] {
        accent-color: #5C9F01;
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    /* Label hover effect */
    .hover\:bg-gray-50:hover {
        background-color: #f9fafb;
    }

    /* Selected state */
    .border-\[\#5C9F01\] {
        border-color: #5C9F01;
    }

    .bg-green-50 {
        background-color: #f0fdf4;
    }

    /* Line clamp for descriptions */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Responsive improvements */
    @media (max-width: 640px) {
        .grid {
            gap: 0.75rem;
        }

        label.border {
            padding: 0.75rem;
        }
    }

    /* Smooth transitions */
    .transition-colors {
        transition: all 0.2s ease;
    }

    /* Disabled state */
    .cursor-not-allowed {
        cursor: not-allowed;
    }
</style>

<!-- Add this to your Livewire component if not already present -->
<script>
    document.addEventListener('livewire:load', function() {
        // Auto-scroll to problems section when brand is selected
        Livewire.on('brandSelected', () => {
            setTimeout(() => {
                const problemsSection = document.querySelector('.bg-white.rounded-lg.border.border-gray-200.p-4.sm\\:p-6');
                if (problemsSection) {
                    problemsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 300);
        });
    });
</script>
