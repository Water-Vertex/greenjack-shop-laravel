<div>
    <!-- Header Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Series Management</h1>
                <p class="text-sm text-gray-600 mt-1">Manage product series with multi-model selection</p>
            </div>
            <button
                wire:click="create"
                wire:loading.attr="disabled"
                class="w-full sm:w-auto px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors flex items-center justify-center sm:justify-start space-x-2 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span wire:loading.remove wire:target="create">
                    <i class="fas fa-plus"></i>
                    <span>Add Series</span>
                </span>
                <span wire:loading wire:target="create" class="flex items-center space-x-2">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                    <span>Opening...</span>
                </span>
            </button>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
            <!-- Search -->
            <div class="flex-1">
                <div class="flex items-center bg-gray-100 rounded-lg px-3 py-2 w-full">
                    <i class="fas fa-search text-gray-400 mr-2"></i>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search series..."
                        class="bg-transparent border-none focus:outline-none focus:ring-0 w-full text-sm"
                    >
                </div>
            </div>

            <!-- Sort and Per Page -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <select wire:model.live="sortField" class="bg-gray-100 border-none rounded-lg px-3 py-2 text-sm focus:ring-0 w-full sm:w-auto">
                    <option value="name">Sort by Name</option>
                    <option value="created_at">Sort by Date</option>
                    <option value="updated_at">Sort by Update</option>
                </select>

                <select wire:model.live="perPage" class="bg-gray-100 border-none rounded-lg px-3 py-2 text-sm focus:ring-0 w-full sm:w-auto">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Series Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <!-- Desktop Table Header -->
        <div class="hidden md:block border-b border-gray-200">
            <div class="grid grid-cols-12 gap-4 px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">
                <div class="col-span-3">Series Name</div>
                <div class="col-span-2">Category</div>
                <div class="col-span-2">Brand</div>
                <div class="col-span-2">Models Count</div>
                <div class="col-span-2">Last Updated</div>
                <div class="col-span-1 text-center">Actions</div>
            </div>
        </div>

        <!-- Table Body -->
        <div class="divide-y divide-gray-200">
            @forelse($seriesList as $series)
            <!-- Desktop View -->
            <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 items-center hover:bg-gray-50 transition-colors">
                <!-- Series Info -->
                <div class="col-span-3">
                    <div>
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $series->name }}</p>
                        <p class="text-xs text-gray-500 truncate">/{{ $series->slug }}</p>
                        @if($series->brand_model_id)
                            <p class="text-xs text-gray-400 truncate">Primary Model ID: {{ $series->brand_model_id }}</p>
                        @endif
                    </div>
                </div>

                <!-- Category -->
                <div class="col-span-2">
                    @if($series->category)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $series->category->name }}
                        </span>
                    @else
                        <span class="text-sm text-gray-400">-</span>
                    @endif
                </div>

                <!-- Brand -->
                <div class="col-span-2">
                    @if($series->brand)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $series->brand->name }}
                        </span>
                    @else
                        <span class="text-sm text-gray-400">-</span>
                    @endif
                </div>

                <!-- Models Count -->
                <div class="col-span-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        <i class="fas fa-microchip mr-1"></i>
                        {{ $series->seriesModels->count() }} Models
                    </span>
                </div>

                <!-- Last Updated -->
                <div class="col-span-2">
                    <span class="text-sm text-gray-600">{{ $series->updated_at->format('M d, Y') }}</span>
                </div>

                <!-- Actions -->
                <div class="col-span-1">
                    <div class="flex items-center justify-center space-x-2">
                        <button
                            wire:click="edit({{ $series->id }})"
                            wire:loading.attr="disabled"
                            class="px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors text-xs font-medium flex items-center space-x-1 disabled:opacity-50"
                            title="Edit Series"
                        >
                            <span wire:loading.remove wire:target="edit({{ $series->id }})">
                                <i class="fas fa-edit text-xs"></i>
                                <span class="hidden sm:inline">Edit</span>
                            </span>
                            <span wire:loading wire:target="edit({{ $series->id }})" class="flex items-center space-x-1">
                                <div class="animate-spin rounded-full h-3 w-3 border-b-2 border-blue-700"></div>
                                <span class="hidden sm:inline">Loading...</span>
                            </span>
                        </button>

                        <button
                            wire:click="delete({{ $series->id }})"
                            wire:confirm="Are you sure you want to delete this series? This will remove all model associations."
                            wire:loading.attr="disabled"
                            class="px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors text-xs font-medium flex items-center space-x-1 disabled:opacity-50"
                            title="Delete Series"
                        >
                            <span wire:loading.remove wire:target="delete({{ $series->id }})">
                                <i class="fas fa-trash text-xs"></i>
                                <span class="hidden sm:inline">Delete</span>
                            </span>
                            <span wire:loading wire:target="delete({{ $series->id }})" class="flex items-center space-x-1">
                                <div class="animate-spin rounded-full h-3 w-3 border-b-2 border-red-700"></div>
                                <span class="hidden sm:inline">Deleting...</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile View -->
            <div class="md:hidden p-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $series->name }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 flex-shrink-0">
                                <i class="fas fa-microchip mr-1 text-xs"></i>
                                {{ $series->seriesModels->count() }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 truncate">/{{ $series->slug }}</p>

                        <div class="mt-1 flex flex-wrap gap-2">
                            @if($series->category)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $series->category->name }}
                                </span>
                            @endif
                            @if($series->brand)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $series->brand->name }}
                                </span>
                            @endif
                        </div>

                        <p class="text-xs text-gray-400 mt-1">{{ $series->updated_at->format('M d, Y') }}</p>
                    </div>
                    <div class="flex items-center space-x-1 ml-2 flex-shrink-0">
                        <button
                            wire:click="edit({{ $series->id }})"
                            class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition"
                            title="Edit Series"
                        >
                            <i class="fas fa-edit"></i>
                        </button>
                        <button
                            wire:click="delete({{ $series->id }})"
                            wire:confirm="Are you sure you want to delete this series? This will remove all model associations."
                            class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition"
                            title="Delete Series"
                        >
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-4 sm:px-6 py-12 text-center">
                <i class="fas fa-layer-group text-4xl sm:text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">No series found.</p>
                @if($search)
                    <p class="text-sm text-gray-400 mt-1">Try adjusting your search criteria</p>
                @endif
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($seriesList->hasPages())
            <div class="border-t border-gray-200 px-4 sm:px-6 py-4 bg-gray-50">
                {{ $seriesList->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if($showForm)
    <div class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm transition-all duration-300" wire:click="resetForm"></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
            <!-- Modal Header -->
            <div class="flex justify-between items-center px-4 sm:px-6 py-4 border-b border-gray-200 sticky top-0 bg-white z-10">
                <div class="flex items-center min-w-0">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">
                        {{ $formType === 'create' ? 'Create New Series' : 'Edit Series' }}
                    </h3>
                    <div wire:loading wire:target="create,edit" class="ml-3 flex-shrink-0">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-orange-500"></div>
                    </div>
                </div>
                <button wire:click="resetForm"
                        class="text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0 ml-2"
                        wire:loading.attr="disabled">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-4 sm:p-6 overflow-y-auto max-h-[70vh]">
                <form wire:submit="save" class="space-y-4 sm:space-y-6">
                    <!-- Category and Brand Selection -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="selectedCategoryId"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('selectedCategoryId')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Brand -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Brand <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="selectedBrandId"
                                    @if(!$selectedCategoryId) disabled @endif
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm {{ !$selectedCategoryId ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                                <option value="">
                                    {{ $selectedCategoryId ? 'Select a brand' : 'Select category first' }}
                                </option>
                                @foreach($availableBrands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('selectedBrandId')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                            @if($selectedCategoryId && $availableBrands->isEmpty())
                                <p class="text-xs text-yellow-600 mt-1">No brands have models in this category.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Series Name and Slug -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Series Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm"
                                placeholder="Enter series name"
                            >
                            @error('name')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                            <div class="flex items-center">
                                <span class="px-3 py-2 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md text-gray-500 text-sm">/</span>
                                <input
                                    type="text"
                                    wire:model="slug"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm"
                                    placeholder="series-slug"
                                >
                            </div>
                            @error('slug')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">URL-friendly version (auto-generated from name)</p>
                        </div>
                    </div>

                    <!-- Primary Model Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Primary Model (Optional)</label>
                        <select wire:model="brand_model_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm"
                                @if(count($availableModels) === 0) disabled @endif>
                            <option value="">Select Primary Model</option>
                            @foreach($availableModels as $model)
                                <option value="{{ $model->id }}">{{ $model->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Optional: Mark one model as the primary/default model for this series</p>
                    </div>

                    <!-- Multi-Select Models -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Select Models <span class="text-red-500">*</span>
                        </label>

                        @if($loadingModels)
                            <div class="text-center py-6 bg-gray-50 rounded-lg">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-500 mx-auto"></div>
                                <p class="text-sm text-gray-500 mt-2">Loading models...</p>
                            </div>
                        @elseif(count($availableModels) > 0)
                            <div class="border border-gray-300 rounded-md p-3 sm:p-4 max-h-48 sm:max-h-64 overflow-y-auto bg-gray-50">
                                <div class="space-y-2">
                                    @foreach($availableModels as $model)
                                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-white p-2 rounded transition-colors">
                                            <input
                                                type="checkbox"
                                                value="{{ $model->id }}"
                                                wire:model="selectedModels"
                                                class="form-checkbox h-4 w-4 text-orange-500 rounded border-gray-300 focus:ring-orange-500 flex-shrink-0"
                                            >
                                            <span class="text-sm text-gray-700">{{ $model->name }}</span>
                                            @if($brand_model_id == $model->id)
                                                <span class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full flex-shrink-0">Primary</span>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-xs text-gray-500">
                                    <span class="font-medium">{{ count($selectedModels) }}</span> model(s) selected
                                </p>
                                @if(count($selectedModels) > 0)
                                    <button type="button" wire:click="$set('selectedModels', [])" class="text-xs text-red-600 hover:text-red-800">
                                        <i class="fas fa-times"></i> Clear all
                                    </button>
                                @endif
                            </div>
                            @error('selectedModels')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        @elseif($selectedCategoryId && $selectedBrandId)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 text-center">
                                <i class="fas fa-exclamation-triangle text-yellow-500 text-lg mb-1"></i>
                                <p class="text-sm text-yellow-700">No models found for the selected category and brand.</p>
                                <p class="text-xs text-yellow-600 mt-1">Please add models first or select different options.</p>
                            </div>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-md p-4 text-center">
                                <i class="fas fa-info-circle text-gray-400 text-lg mb-1"></i>
                                <p class="text-sm text-gray-600">Please select both category and brand to see available models.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 pt-4 sm:pt-6 border-t border-gray-200">
                        <button
                            type="button"
                            wire:click="resetForm"
                            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors order-2 sm:order-1"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-orange-500 rounded-md hover:bg-orange-600 transition-colors flex items-center justify-center space-x-2 disabled:opacity-50 order-1 sm:order-2"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove wire:target="save">
                                {{ $formType === 'create' ? 'Create Series' : 'Update Series' }}
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
    @endif

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
