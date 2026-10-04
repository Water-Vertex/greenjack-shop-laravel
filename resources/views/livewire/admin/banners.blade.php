<div>
    <!-- Header Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Banners Management</h1>
                <p class="text-sm text-gray-600 mt-1">Manage website banners and promotional slides</p>
            </div>

            <button wire:click="create"
                class="w-full sm:w-auto px-4 py-2 bg-[#5C9F01] text-white rounded-md hover:bg-[#4b8501] transition-colors flex items-center justify-center sm:justify-start space-x-2 text-sm font-medium">
                <i class="fas fa-plus"></i>
                <span>Add Banner</span>
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
            <!-- Search -->
            <div class="flex-1">
                <div class="flex items-center bg-gray-100 rounded-lg px-3 py-2 w-full">
                    <i class="fas fa-search text-gray-400 mr-2"></i>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Search banners..."
                        class="bg-transparent border-none focus:outline-none focus:ring-0 w-full text-sm">
                </div>
            </div>

            <!-- Sort & Per Page -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <select wire:model.live="sortField" class="bg-gray-100 border-none rounded-lg px-3 py-2 text-sm focus:ring-0 w-full sm:w-auto">
                    <option value="created_at">Sort by Date</option>
                    <option value="title">Sort by Title</option>
                    <option value="sub_title">Sort by Subtitle</option>
                </select>

                <select wire:model.live="perPage" class="bg-gray-100 border-none rounded-lg px-3 py-2 text-sm focus:ring-0 w-full sm:w-auto">
                    <option value="5">5 per page</option>
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Banners Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <!-- Desktop Table Header -->
        <div class="hidden md:block border-b border-gray-200">
            <div class="grid grid-cols-12 gap-4 px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase">
                <div class="col-span-3">Banner</div>
                <div class="col-span-2">Visibility</div>
                <div class="col-span-3">Description</div>
                <div class="col-span-2">URL</div>
                <div class="col-span-2 text-center">Actions</div>
            </div>
        </div>

        <!-- Banners List -->
        <div class="divide-y divide-gray-200">
            @forelse($banners as $banner)
                <!-- Desktop View -->
                <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 items-center hover:bg-gray-50">
                    <div class="col-span-3">
                        <div class="flex items-center space-x-3">
                            @if($banner->image)
                                <img src="{{ asset('storage/' . $banner->image) }}"
                                    class="w-14 h-12 rounded-lg object-cover border"
                                    alt="{{ $banner->title }}">
                            @else
                                <div class="w-14 h-12 bg-gray-100 rounded-lg flex items-center justify-center border">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif

                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $banner->title ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $banner->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-2">
                        <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-700">
                            {{ $banner->visibility }}
                        </span>
                    </div>

                    <div class="col-span-3 text-sm text-gray-600 truncate">
                        {{ Str::limit($banner->description, 55) ?: '-' }}
                    </div>

                    <div class="col-span-2 text-sm text-gray-600 truncate">
                        @if($banner->url)
                            <a href="{{ $banner->url }}" target="_blank" class="text-blue-600 hover:underline">
                                {{ Str::limit($banner->url, 30) }}
                            </a>
                        @else
                            -
                        @endif
                    </div>

                    <div class="col-span-2">
                        <div class="flex justify-center space-x-2">
                            <button wire:click="edit({{ $banner->id }})"
                                class="px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 text-xs font-medium transition-colors">
                                <i class="fas fa-edit"></i> Edit
                            </button>

                            <button wire:click="delete({{ $banner->id }})"
                                wire:confirm="Are you sure you want to delete this banner?"
                                class="px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 text-xs font-medium transition-colors">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile View -->
                <div class="md:hidden p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start space-x-3">
                        <!-- Image -->
                        <div class="flex-shrink-0">
                            @if($banner->image)
                                <img src="{{ asset('storage/' . $banner->image) }}"
                                    class="w-20 h-16 rounded-lg object-cover border"
                                    alt="{{ $banner->title }}">
                            @else
                                <div class="w-20 h-16 bg-gray-100 rounded-lg flex items-center justify-center border">
                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $banner->title ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">{{ $banner->created_at->format('d M Y') }}</p>
                                </div>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 ml-2 flex-shrink-0">
                                    {{ $banner->visibility }}
                                </span>
                            </div>

                            <p class="text-xs text-gray-600 mt-1 line-clamp-2">
                                {{ Str::limit($banner->description, 60) ?: '-' }}
                            </p>

                            @if($banner->url)
                                <a href="{{ $banner->url }}" target="_blank"
                                    class="text-xs text-blue-600 hover:underline truncate block mt-0.5">
                                    {{ Str::limit($banner->url, 35) }}
                                </a>
                            @endif

                            <!-- Mobile Actions -->
                            <div class="flex items-center space-x-2 mt-2 pt-2 border-t border-gray-100">
                                <button wire:click="edit({{ $banner->id }})"
                                    class="flex-1 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 text-xs font-medium text-center transition-colors">
                                    <i class="fas fa-edit"></i> Edit
                                </button>

                                <button wire:click="delete({{ $banner->id }})"
                                    wire:confirm="Are you sure you want to delete this banner?"
                                    class="flex-1 px-3 py-1.5 bg-red-100 text-red-700 rounded-md hover:bg-red-200 text-xs font-medium text-center transition-colors">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 sm:px-6 py-12 text-center">
                    <i class="fas fa-image text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">No banners found.</p>
                    @if($search)
                        <p class="text-sm text-gray-400 mt-1">Try adjusting your search criteria</p>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($banners->hasPages())
            <div class="border-t border-gray-200 px-4 sm:px-6 py-4 bg-gray-50">
                {{ $banners->links() }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    @if($showForm)
        <div class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm" wire:click="resetForm"></div>

        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
                <!-- Modal Header -->
                <div class="flex justify-between items-center px-4 sm:px-6 py-4 border-b border-gray-200 sticky top-0 bg-white z-10">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $formType === 'create' ? 'Create New Banner' : 'Edit Banner' }}
                    </h3>

                    <button wire:click="resetForm" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-4 sm:p-6 overflow-y-auto max-h-[70vh]">
                    <form wire:submit="save" class="space-y-4">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="title"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] transition"
                                placeholder="Enter banner title">
                            @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Sub Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sub Title</label>
                            <input type="text" wire:model="sub_title"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] transition"
                                placeholder="Enter banner subtitle">
                            @error('sub_title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea wire:model="description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] transition resize-y"
                                placeholder="Enter banner description"></textarea>
                            @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- URL -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                            <input type="text" wire:model="url"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] transition"
                                placeholder="https://example.com">
                            @error('url') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Visibility -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Visibility <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="visibility"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] transition">
                                <option value="">Select Visibility</option>
                                <option value="home_main_banner">Home Main Banner</option>
                                <option value="home_main_side_banner">Home Main Side Banner</option>
                                <option value="home_side_banner">Home Side Banner</option>
                            </select>
                            @error('visibility') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Banner Image</label>

                            <!-- Image Preview -->
                            <div class="mb-3">
                                @if($image)
                                    <div class="relative inline-block">
                                        <img src="{{ $image->temporaryUrl() }}"
                                            class="w-32 h-20 object-cover rounded-lg border-2 border-green-500"
                                            alt="Preview">
                                        <button type="button" wire:click="$set('image', null)"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600 transition">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @elseif($existingImage)
                                    <div class="relative inline-block">
                                        <img src="{{ asset('storage/' . $existingImage) }}"
                                            class="w-32 h-20 object-cover rounded-lg border-2 border-gray-200"
                                            alt="Current Image">
                                        <span class="absolute top-0 left-0 bg-green-500 text-white text-[8px] px-1.5 py-0.5 rounded-br-lg">Current</span>
                                    </div>
                                @endif
                            </div>

                            <!-- File Input -->
                            <div class="relative">
                                <input type="file" wire:model="image" accept="image/*"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] transition file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#5C9F01] file:text-white hover:file:bg-[#4b8501]">

                                <!-- Upload Loader -->
                                <div wire:loading wire:target="image"
                                    class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-md">
                                    <div class="flex items-center space-x-2">
                                        <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-[#5C9F01]"></div>
                                        <span class="text-sm text-gray-600">Uploading...</span>
                                    </div>
                                </div>
                            </div>

                            <p class="text-xs text-gray-500 mt-1">Recommended: 1920x600px, Max 2MB (JPG, PNG, WEBP)</p>
                            @error('image') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 pt-4 border-t border-gray-200">
                            <button type="button" wire:click="resetForm"
                                class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors order-2 sm:order-1">
                                Cancel
                            </button>

                            <button type="submit"
                                class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-[#5C9F01] rounded-md hover:bg-[#4b8501] transition-colors flex items-center justify-center space-x-2 order-1 sm:order-2">
                                <span wire:loading.remove wire:target="save">
                                    {{ $formType === 'create' ? 'Create Banner' : 'Update Banner' }}
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
