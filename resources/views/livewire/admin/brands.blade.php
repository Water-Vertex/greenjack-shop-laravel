{{-- resources/views/livewire/admin/brands.blade.php --}}
<div>
    <!-- Header Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Brands Management</h1>
                <p class="text-sm text-gray-600 mt-1">Manage your product brands with SEO optimization</p>
            </div>
            <button
                wire:click="create"
                wire:loading.attr="disabled"
                class="w-full sm:w-auto px-4 py-2 bg-[#5C9F01] text-white rounded-md hover:bg-[#4a8501] transition-colors flex items-center justify-center sm:justify-start space-x-2 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span wire:loading.remove wire:target="create">
                    <i class="fas fa-plus"></i>
                    <span>Add Brand</span>
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
                        placeholder="Search brands..."
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

    <!-- Brands Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <!-- Desktop Table Header -->
        <div class="hidden md:block border-b border-gray-200">
            <div class="grid grid-cols-12 gap-4 px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">
                <div class="col-span-3">Brand</div>
                <div class="col-span-3">Description</div>
                <div class="col-span-2">Meta Title</div>
                <div class="col-span-2">Last Updated</div>
                <div class="col-span-2 text-center">Actions</div>
            </div>
        </div>

        <!-- Table Body -->
        <div class="divide-y divide-gray-200">
            @if($brands->count() > 0)
                @foreach($brands as $brand)
                <!-- Desktop View -->
                <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 items-center hover:bg-gray-50 transition-colors">
                    <!-- Brand Info -->
                    <div class="col-span-3">
                        <div class="flex items-center space-x-3">
                            @if($brand->featured_image)
                                <img src="{{ asset('storage/' . $brand->featured_image) }}"
                                     alt="{{ $brand->name }}"
                                     class="w-12 h-12 rounded-lg object-cover border border-gray-200 flex-shrink-0">
                            @else
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200 flex-shrink-0">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $brand->name }}</p>
                                <p class="text-xs text-gray-500 truncate">/{{ $brand->slug }}</p>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach($brand->categories as $category)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-[#5C9F01] bg-opacity-10 text-white">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-span-3">
                        @if($brand->description)
                            <p class="text-sm text-gray-600 truncate" title="{{ $brand->description }}">
                                {{ $brand->excerpt ?? Str::limit($brand->description, 50) }}
                            </p>
                        @else
                            <span class="text-sm text-gray-400">No description</span>
                        @endif
                    </div>

                    <!-- Meta Title -->
                    <div class="col-span-2">
                        @if($brand->meta_title)
                            <p class="text-sm text-gray-900 truncate" title="{{ $brand->meta_title }}">
                                {{ Str::limit($brand->meta_title, 30) }}
                            </p>
                        @else
                            <span class="text-sm text-gray-400">-</span>
                        @endif
                    </div>

                    <!-- Last Updated -->
                    <div class="col-span-2">
                        <span class="text-sm text-gray-600">{{ $brand->updated_at->format('M d, Y') }}</span>
                    </div>

                    <!-- Actions -->
                    <div class="col-span-2">
                        <div class="flex items-center justify-center space-x-2">
                            <button
                                wire:click="edit({{ $brand->id }})"
                                wire:loading.attr="disabled"
                                class="px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors text-xs font-medium flex items-center space-x-1 disabled:opacity-50"
                                title="Edit Brand"
                            >
                                <span wire:loading.remove wire:target="edit({{ $brand->id }})">
                                    <i class="fas fa-edit text-xs"></i>
                                    <span class="hidden sm:inline">Edit</span>
                                </span>
                                <span wire:loading wire:target="edit({{ $brand->id }})" class="flex items-center space-x-1">
                                    <div class="animate-spin rounded-full h-3 w-3 border-b-2 border-blue-700"></div>
                                    <span class="hidden sm:inline">Loading...</span>
                                </span>
                            </button>

                            <button
                                wire:click="delete({{ $brand->id }})"
                                wire:confirm="Are you sure you want to delete this brand?"
                                wire:loading.attr="disabled"
                                class="px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors text-xs font-medium flex items-center space-x-1 disabled:opacity-50"
                                title="Delete Brand"
                            >
                                <span wire:loading.remove wire:target="delete({{ $brand->id }})">
                                    <i class="fas fa-trash text-xs"></i>
                                    <span class="hidden sm:inline">Delete</span>
                                </span>
                                <span wire:loading wire:target="delete({{ $brand->id }})" class="flex items-center space-x-1">
                                    <div class="animate-spin rounded-full h-3 w-3 border-b-2 border-red-700"></div>
                                    <span class="hidden sm:inline">Deleting...</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile View -->
                <div class="md:hidden p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start space-x-3">
                        <!-- Image -->
                        <div class="flex-shrink-0">
                            @if($brand->featured_image)
                                <img src="{{ asset('storage/' . $brand->featured_image) }}"
                                     alt="{{ $brand->name }}"
                                     class="w-14 h-14 rounded-lg object-cover border border-gray-200">
                            @else
                                <div class="w-14 h-14 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                    <i class="fas fa-tag text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $brand->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">/{{ $brand->slug }}</p>
                                </div>
                                <span class="text-xs text-gray-400 ml-2 flex-shrink-0">{{ $brand->updated_at->format('M d, Y') }}</span>
                            </div>

                            <!-- Categories -->
                            @if($brand->categories->count() > 0)
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach($brand->categories as $category)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-[#5C9F01] bg-opacity-10 text-white">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            @if($brand->description)
                                <p class="text-xs text-gray-600 mt-1 line-clamp-2">
                                    {{ Str::limit($brand->description, 60) }}
                                </p>
                            @endif

                            <!-- Mobile Actions -->
                            <div class="flex items-center space-x-2 mt-2 pt-2 border-t border-gray-100">
                                <button
                                    wire:click="edit({{ $brand->id }})"
                                    class="flex-1 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 text-xs font-medium text-center transition-colors"
                                >
                                    <i class="fas fa-edit"></i> Edit
                                </button>

                                <button
                                    wire:click="delete({{ $brand->id }})"
                                    wire:confirm="Are you sure you want to delete this brand?"
                                    class="flex-1 px-3 py-1.5 bg-red-100 text-red-700 rounded-md hover:bg-red-200 text-xs font-medium text-center transition-colors"
                                >
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="px-4 sm:px-6 py-12 text-center">
                    <i class="fas fa-tags text-4xl sm:text-5xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">No brands found.</p>
                    @if($search)
                        <p class="text-sm text-gray-400 mt-1">Try adjusting your search criteria</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($brands->hasPages())
            <div class="border-t border-gray-200 px-4 sm:px-6 py-4 bg-gray-50">
                {{ $brands->links() }}
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
                        {{ $formType === 'create' ? 'Create New Brand' : 'Edit Brand' }}
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
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 gap-4 sm:gap-6">
                        <!-- Categories Multi-Select -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Categories <span class="text-red-500">*</span>
                            </label>
                            <div class="relative" x-data="{ open: false }">
                                <button type="button" @click="open = !open"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-left flex justify-between items-center text-sm">
                                    <span class="truncate">
                                        @if(count($selectedCategories) > 0)
                                            {{ count($selectedCategories) }} category(s) selected
                                        @else
                                            Select categories
                                        @endif
                                    </span>
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="open" @click.away="open = false"
                                    class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
                                    <div class="p-2">
                                        @foreach($categories as $category)
                                        <label class="flex items-center space-x-2 p-2 hover:bg-gray-50 rounded cursor-pointer">
                                            <input type="checkbox"
                                                wire:model="selectedCategories"
                                                value="{{ $category->id }}"
                                                class="rounded border-gray-300 text-[#5C9F01] focus:ring-[#5C9F01]">
                                            <span class="text-sm text-gray-700">{{ $category->name }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @error('selectedCategories')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror

                            <!-- Selected Categories Badges -->
                            @if(count($selectedCategories) > 0)
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach($selectedCategories as $catId)
                                    @php
                                        $category = $categories->firstWhere('id', $catId);
                                    @endphp
                                    @if($category)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-[#5C9F01] bg-opacity-10 text-[#5C9F01]">
                                        {{ $category->name }}
                                        <button type="button" wire:click="$set('selectedCategories', {{ json_encode(array_diff($selectedCategories, [$catId])) }})" class="ml-1 hover:text-red-600">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </span>
                                    @endif
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Brand Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] disabled:opacity-50 text-sm"
                                placeholder="Enter brand name"
                            >
                            @error('name')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Slug <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center">
                                <span class="px-3 py-2 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md text-gray-500 text-sm">/</span>
                                <input
                                    type="text"
                                    wire:model="slug"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] disabled:opacity-50 text-sm"
                                    placeholder="brand-slug"
                                >
                            </div>
                            @error('slug')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">URL-friendly version of the brand name</p>
                        </div>

                        <!-- Featured Image -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                                <!-- Image Preview -->
                                @if($featured_image)
                                    <div class="relative flex-shrink-0">
                                        <img src="{{ $featured_image->temporaryUrl() }}"
                                             alt="Preview"
                                             class="h-20 w-20 object-cover rounded-lg border border-gray-200">
                                        <button type="button"
                                                wire:click="$set('featured_image', null)"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                @elseif($formType === 'edit' && $brandId)
                                    @php
                                        $brand = \App\Models\Brand::find($brandId);
                                    @endphp
                                    @if($brand && $brand->featured_image)
                                        <div class="relative flex-shrink-0">
                                            <img src="{{ asset('storage/' . $brand->featured_image) }}"
                                                 alt="{{ $brand->name }}"
                                                 class="h-20 w-20 object-cover rounded-lg border border-gray-200">
                                            <button type="button"
                                                    wire:click="removeImage"
                                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors">
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        </div>
                                    @endif
                                @endif

                                <!-- File Upload -->
                                <div class="relative w-full sm:flex-1">
                                    <input type="file"
                                           wire:model="featured_image"
                                           class="block w-full text-xs sm:text-sm text-gray-500 file:mr-4 file:py-1.5 sm:file:py-2 file:px-3 sm:file:px-4 file:rounded-md file:border-0 file:text-xs sm:file:text-sm file:font-semibold file:bg-[#5C9F01] file:text-white hover:file:bg-[#4a7f01] transition-colors cursor-pointer disabled:opacity-50"
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                           wire:loading.attr="disabled">

                                    <!-- Upload Progress -->
                                    <div wire:loading wire:target="featured_image" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-md">
                                        <div class="flex items-center space-x-2">
                                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-[#5C9F01]"></div>
                                            <span class="text-xs text-gray-500">Uploading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @error('featured_image')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                            <p class="text-xs text-gray-500 mt-2">Recommended: 400x400px. Max size: 2MB. Supported: JPG, PNG, GIF</p>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea
                                wire:model="description"
                                rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] disabled:opacity-50 text-sm resize-y"
                                placeholder="Brief description about the brand"
                            ></textarea>
                            @error('description')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Blog Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Blog Description</label>
                            <textarea
                                wire:model="blog_description"
                                rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] disabled:opacity-50 text-sm resize-y"
                                placeholder="Detailed description for blog posts or brand pages"
                            ></textarea>
                            @error('blog_description')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- SEO Section -->
                    <div class="border-t border-gray-200 pt-4 sm:pt-6">
                        <h4 class="text-sm sm:text-md font-semibold text-gray-900 mb-3 sm:mb-4">SEO Settings</h4>

                        <div class="space-y-3 sm:space-y-4">
                            <!-- Meta Title -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                                <input
                                    type="text"
                                    wire:model="meta_title"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] disabled:opacity-50 text-sm"
                                    placeholder="Meta title for SEO (defaults to brand name)"
                                >
                                @error('meta_title')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Meta Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                                <textarea
                                    wire:model="meta_description"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] disabled:opacity-50 text-sm resize-y"
                                    placeholder="Meta description for SEO"
                                ></textarea>
                                @error('meta_description')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 pt-4 sm:pt-6 border-t border-gray-200">
                        <button
                            type="button"
                            wire:click="resetForm"
                            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors disabled:opacity-50 order-2 sm:order-1"
                            wire:loading.attr="disabled"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-[#5C9F01] rounded-md hover:bg-[#4a8501] transition-colors flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed order-1 sm:order-2"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove wire:target="save">
                                {{ $formType === 'create' ? 'Create Brand' : 'Update Brand' }}
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
