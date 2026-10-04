<div>
    <!-- Header Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Problems Management</h1>
                <p class="text-sm text-gray-600 mt-1">Manage device problems, repairs, and pricing</p>
            </div>
            <button
                wire:click="create"
                wire:loading.attr="disabled"
                class="w-full sm:w-auto px-4 py-2 bg-[#5C9F01] text-white rounded-md hover:bg-[#4a8501] transition-colors flex items-center justify-center sm:justify-start space-x-2 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span wire:loading.remove wire:target="create">
                    <i class="fas fa-plus"></i>
                    <span>Add Problem</span>
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
            <div class="flex-1">
                <div class="flex items-center bg-gray-100 rounded-lg px-3 py-2 w-full">
                    <i class="fas fa-search text-gray-400 mr-2"></i>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search problems..."
                        class="bg-transparent border-none focus:outline-none focus:ring-0 w-full text-sm"
                    >
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <select wire:model.live="sortField" class="bg-gray-100 border-none rounded-lg px-3 py-2 text-sm focus:ring-0 w-full sm:w-auto">
                    <option value="name">Sort by Name</option>
                    <option value="price">Sort by Price</option>
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

    <!-- Problems Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <!-- Desktop Table Header -->
        <div class="hidden md:block border-b border-gray-200">
            <div class="grid grid-cols-12 gap-4 px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">
                <div class="col-span-3">Problem</div>
                <div class="col-span-2">Device Model</div>
                <div class="col-span-3">Description</div>
                <div class="col-span-2">Pricing</div>
                <div class="col-span-1">Status</div>
                <div class="col-span-1 text-center">Actions</div>
            </div>
        </div>

        <!-- Table Body -->
        <div class="divide-y divide-gray-200">
            @if($problems->count() > 0)
                @foreach($problems as $problem)
                <!-- Desktop View -->
                <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 items-center hover:bg-gray-50 transition-colors">
                    <!-- Problem Info -->
                    <div class="col-span-3">
                        <div>
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $problem->name }}</p>
                            <p class="text-xs text-gray-500 truncate">/{{ $problem->slug }}</p>
                        </div>
                    </div>

                    <!-- Device Model -->
                    <div class="col-span-2">
                        @if($problem->brand_model_id)
                            <p class="text-sm text-gray-900 truncate">{{ $problem->model->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $problem->model->brand->name ?? '' }}</p>
                        @else
                            <span class="text-sm text-gray-400">-</span>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="col-span-3">
                        @if($problem->description)
                            <p class="text-sm text-gray-600 truncate" title="{{ $problem->description }}">
                                {{ Str::limit($problem->description, 80) }}
                            </p>
                        @else
                            <span class="text-sm text-gray-400">No description</span>
                        @endif
                    </div>

                    <!-- Pricing -->
                    <div class="col-span-2">
                        @if($problem->discounted_price)
                            <p class="text-sm text-gray-500 line-through">${{ number_format($problem->price, 2) }}</p>
                            <p class="text-sm font-semibold text-green-600">${{ number_format($problem->discounted_price, 2) }}</p>
                        @elseif($problem->price)
                            <p class="text-sm font-semibold text-gray-900">${{ number_format($problem->price, 2) }}</p>
                        @else
                            <span class="text-sm text-gray-400">-</span>
                        @endif
                    </div>

                    <!-- Status -->
                    <div class="col-span-1">
                        <button
                            wire:click="toggleStatus({{ $problem->id }})"
                            class="px-2 py-1 rounded-full text-xs font-medium transition-colors
                                {{ $problem->status == 'active' ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                            {{ ucfirst($problem->status) }}
                        </button>
                    </div>

                    <!-- Actions -->
                    <div class="col-span-1">
                        <div class="flex items-center justify-center space-x-2">
                            <button
                                wire:click="edit({{ $problem->id }})"
                                wire:loading.attr="disabled"
                                class="px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors text-xs font-medium flex items-center space-x-1 disabled:opacity-50"
                                title="Edit Problem"
                            >
                                <span wire:loading.remove wire:target="edit({{ $problem->id }})">
                                    <i class="fas fa-edit text-xs"></i>
                                    <span class="hidden sm:inline">Edit</span>
                                </span>
                                <span wire:loading wire:target="edit({{ $problem->id }})" class="flex items-center space-x-1">
                                    <div class="animate-spin rounded-full h-3 w-3 border-b-2 border-blue-700"></div>
                                    <span class="hidden sm:inline">Loading...</span>
                                </span>
                            </button>

                            <button
                                wire:click="delete({{ $problem->id }})"
                                wire:confirm="Are you sure you want to delete this problem?"
                                wire:loading.attr="disabled"
                                class="px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors text-xs font-medium flex items-center space-x-1 disabled:opacity-50"
                                title="Delete Problem"
                            >
                                <span wire:loading.remove wire:target="delete({{ $problem->id }})">
                                    <i class="fas fa-trash text-xs"></i>
                                    <span class="hidden sm:inline">Delete</span>
                                </span>
                                <span wire:loading wire:target="delete({{ $problem->id }})" class="flex items-center space-x-1">
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
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $problem->name }}</p>
                                <button
                                    wire:click="toggleStatus({{ $problem->id }})"
                                    class="px-2 py-0.5 rounded-full text-[10px] font-medium transition-colors flex-shrink-0
                                        {{ $problem->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($problem->status) }}
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 truncate">/{{ $problem->slug }}</p>

                            @if($problem->brand_model_id)
                                <p class="text-xs text-gray-600 mt-1 truncate">
                                    <span class="font-medium">Model:</span> {{ $problem->model->name ?? 'N/A' }}
                                </p>
                            @endif

                            @if($problem->description)
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                    {{ Str::limit($problem->description, 60) }}
                                </p>
                            @endif

                            <div class="mt-2">
                                @if($problem->discounted_price)
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-400 line-through">${{ number_format($problem->price, 2) }}</span>
                                        <span class="text-sm font-semibold text-green-600">${{ number_format($problem->discounted_price, 2) }}</span>
                                    </div>
                                @elseif($problem->price)
                                    <span class="text-sm font-semibold text-gray-900">${{ number_format($problem->price, 2) }}</span>
                                @else
                                    <span class="text-xs text-gray-400">No price set</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Actions -->
                    <div class="flex items-center space-x-2 mt-3 pt-2 border-t border-gray-100">
                        <button
                            wire:click="edit({{ $problem->id }})"
                            class="flex-1 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 text-xs font-medium text-center transition-colors"
                        >
                            <i class="fas fa-edit"></i> Edit
                        </button>

                        <button
                            wire:click="delete({{ $problem->id }})"
                            wire:confirm="Are you sure you want to delete this problem?"
                            class="flex-1 px-3 py-1.5 bg-red-100 text-red-700 rounded-md hover:bg-red-200 text-xs font-medium text-center transition-colors"
                        >
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
                @endforeach
            @else
                <div class="px-4 sm:px-6 py-12 text-center">
                    <i class="fas fa-wrench text-4xl sm:text-5xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">No problems found.</p>
                    @if($search)
                        <p class="text-sm text-gray-400 mt-1">Try adjusting your search criteria</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($problems->hasPages())
            <div class="border-t border-gray-200 px-4 sm:px-6 py-4 bg-gray-50">
                {{ $problems->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if($showForm)
    <div class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm transition-all duration-300" wire:click="resetForm"></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl max-h-[90vh] overflow-hidden">
            <!-- Modal Header -->
            <div class="flex justify-between items-center px-4 sm:px-6 py-4 border-b border-gray-200 sticky top-0 bg-white z-10">
                <div class="flex items-center min-w-0">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">
                        {{ $formType === 'create' ? 'Create New Problem' : 'Edit Problem' }}
                    </h3>
                    <div wire:loading wire:target="create,edit" class="ml-3 flex-shrink-0">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-[#5C9F01]"></div>
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
                    <!-- Category Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="selectedCategory"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedCategory')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Brand Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Brand <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="selectedBrand"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm {{ !$selectedCategory ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                            {{ !$selectedCategory ? 'disabled' : '' }}>
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedBrand')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                        @if(!$selectedCategory)
                            <p class="text-xs text-gray-400 mt-1">Please select a category first</p>
                        @endif
                    </div>

                    <!-- Model Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Model <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="selectedModel"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm {{ !$selectedBrand ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                            {{ !$selectedBrand ? 'disabled' : '' }}>
                            <option value="">Select Model</option>
                            @foreach($models as $model)
                                <option value="{{ $model->id }}">{{ $model->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedModel')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                        @if(!$selectedBrand)
                            <p class="text-xs text-gray-400 mt-1">Please select a brand first</p>
                        @endif
                    </div>

                    <!-- Problem Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Problem Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm"
                            placeholder="e.g., Screen Replacement, Battery Issue">
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Slug <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center">
                            <span class="px-3 py-2 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md text-gray-500 text-sm">/</span>
                            <input type="text" wire:model="slug"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm"
                                placeholder="problem-slug">
                        </div>
                        @error('slug') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">URL-friendly version of the problem name</p>
                    </div>

                    <!-- Pricing -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price ($)</label>
                            <input type="number" step="0.01" wire:model="price"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm"
                                placeholder="0.00">
                            @error('price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Discounted Price ($)</label>
                            <input type="number" step="0.01" wire:model="discounted_price"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm"
                                placeholder="0.00">
                            @error('discounted_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select wire:model="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea wire:model="description" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm resize-y"
                            placeholder="Detailed description of the problem and repair process..."></textarea>
                        @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Is Featured -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Is Featured</label>
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="is_featured"
                                class="h-4 w-4 text-[#5C9F01] focus:ring-[#5C9F01] border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Mark as featured problem</span>
                        </div>
                        @error('is_featured') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Is Recommended -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Is Recommended</label>
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="is_recommended"
                                class="h-4 w-4 text-[#5C9F01] focus:ring-[#5C9F01] border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Mark as recommended problem</span>
                        </div>
                        @error('is_recommended') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 pt-4 sm:pt-6 border-t border-gray-200">
                        <button type="button" wire:click="resetForm"
                            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors order-2 sm:order-1">
                            Cancel
                        </button>
                        <button type="submit"
                            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-[#5C9F01] rounded-md hover:bg-[#4a8501] transition-colors flex items-center justify-center space-x-2 order-1 sm:order-2">
                            <span wire:loading.remove wire:target="save">
                                {{ $formType === 'create' ? 'Create Problem' : 'Update Problem' }}
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
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
            class="fixed bottom-4 right-4 left-4 sm:left-auto bg-green-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg transition-all duration-300 z-50">
            <div class="flex items-center space-x-2">
                <i class="fas fa-check-circle"></i>
                <span class="text-sm sm:text-base">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
            class="fixed bottom-4 right-4 left-4 sm:left-auto bg-red-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg transition-all duration-300 z-50">
            <div class="flex items-center space-x-2">
                <i class="fas fa-exclamation-circle"></i>
                <span class="text-sm sm:text-base">{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>
