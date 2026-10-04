<div>
    <!-- Header Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Products Management</h1>
                <p class="text-sm text-gray-600 mt-1">Manage your product catalog with SEO optimization</p>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                <button
                    wire:click="openImportModal"
                    class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2 text-sm font-medium"
                >
                    <i class="fas fa-file-excel"></i>
                    <span>Import Excel</span>
                </button>
                <button
                    wire:click="create"
                    wire:loading.attr="disabled"
                    class="w-full sm:w-auto px-4 py-2 bg-[#5C9F01] text-white rounded-md hover:bg-[#4a7e01] transition-colors flex items-center justify-center space-x-2 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span wire:loading.remove wire:target="create">
                        <i class="fas fa-plus"></i>
                        <span>Add Product</span>
                    </span>
                    <span wire:loading wire:target="create" class="flex items-center space-x-2">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                        <span>Opening...</span>
                    </span>
                </button>
            </div>
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
                        placeholder="Search products..."
                        class="bg-transparent border-none focus:outline-none focus:ring-0 w-full text-sm"
                    >
                </div>
            </div>

            <!-- Sort and Per Page -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <select wire:model.live="sortField" class="bg-gray-100 border-none rounded-lg px-3 py-2 text-sm focus:ring-0 w-full sm:w-auto">
                    <option value="name">Sort by Name</option>
                    <option value="price">Sort by Price</option>
                    <option value="stock">Sort by Stock</option>
                    <option value="created_at">Sort by Date</option>
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

    <!-- Products Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <!-- Desktop Table Header -->
        <div class="hidden md:block border-b border-gray-200">
            <div class="grid grid-cols-12 gap-4 px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">
                <div class="col-span-3">Product</div>
                <div class="col-span-2">SKU / UPC</div>
                <div class="col-span-2">Price</div>
                <div class="col-span-1 text-center">Stock</div>
                <div class="col-span-2 text-center">Category</div>
                <div class="col-span-2 text-center">Actions</div>
            </div>
        </div>

        <!-- Table Body -->
        <div class="divide-y divide-gray-200">
            @if($products->count() > 0)
                @foreach($products as $product)
                <!-- Desktop View -->
                <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 items-center hover:bg-gray-50 transition-colors">
                    <!-- Product Info -->
                    <div class="col-span-3">
                        <div class="flex items-center space-x-3">
                            @if($product->image && Storage::disk('public')->exists($product->image))
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="w-10 h-10 rounded-lg object-cover border border-gray-200 flex-shrink-0">
                            @else
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200 flex-shrink-0">
                                    <i class="fas fa-box text-gray-400"></i>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ Str::limit($product->name, 40) }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $product->slug }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- SKU / UPC -->
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600">{{ $product->sku ?? '-' }}</p>
                        <p class="text-xs text-gray-400">UPC: {{ $product->upc ?? '-' }}</p>
                    </div>

                    <!-- Price -->
                    <div class="col-span-2">
                        @if($product->discounted_price && $product->discounted_price < $product->price)
                            <p class="text-sm font-semibold text-green-600">${{ number_format($product->discounted_price, 2) }}</p>
                            <p class="text-xs text-gray-400 line-through">${{ number_format($product->price, 2) }}</p>
                        @else
                            <p class="text-sm font-semibold text-gray-900">${{ number_format($product->price, 2) }}</p>
                        @endif
                    </div>

                    <!-- Stock -->
                    <div class="col-span-1 text-center">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $product->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->stock }}
                        </span>
                    </div>

                    <!-- Category -->
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600 truncate">{{ $product->category?->name ?? '-' }}</p>
                        @if($product->subCategory)
                            <p class="text-xs text-gray-400 truncate">{{ $product->subCategory->name }}</p>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="col-span-2">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('shop.details', $product->slug) }}" target="_blank"
                                class="px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors text-xs font-medium flex items-center space-x-1">
                                <i class="fas fa-eye text-xs"></i>
                                <span class="hidden sm:inline">View</span>
                            </a>
                            <button
                                wire:click="edit({{ $product->id }})"
                                wire:loading.attr="disabled"
                                class="px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors text-xs font-medium flex items-center space-x-1 disabled:opacity-50"
                                title="Edit Product"
                            >
                                <span wire:loading.remove wire:target="edit({{ $product->id }})">
                                    <i class="fas fa-edit text-xs"></i>
                                    <span class="hidden sm:inline">Edit</span>
                                </span>
                                <span wire:loading wire:target="edit({{ $product->id }})" class="flex items-center space-x-1">
                                    <div class="animate-spin rounded-full h-3 w-3 border-b-2 border-blue-700"></div>
                                    <span class="hidden sm:inline">Loading...</span>
                                </span>
                            </button>

                            <button
                                wire:click="delete({{ $product->id }})"
                                wire:confirm="Are you sure you want to delete this product?"
                                wire:loading.attr="disabled"
                                class="px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors text-xs font-medium flex items-center space-x-1 disabled:opacity-50"
                                title="Delete Product"
                            >
                                <span wire:loading.remove wire:target="delete({{ $product->id }})">
                                    <i class="fas fa-trash text-xs"></i>
                                    <span class="hidden sm:inline">Delete</span>
                                </span>
                                <span wire:loading wire:target="delete({{ $product->id }})" class="flex items-center space-x-1">
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
                            @if($product->image && Storage::disk('public')->exists($product->image))
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="w-14 h-14 rounded-lg object-cover border border-gray-200">
                            @else
                                <div class="w-14 h-14 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                    <i class="fas fa-box text-gray-400 text-xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ Str::limit($product->name, 30) }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $product->slug }}</p>
                                </div>
                                <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full ml-2 flex-shrink-0 {{ $product->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    Stock: {{ $product->stock }}
                                </span>
                            </div>

                            <div class="mt-1">
                                @if($product->discounted_price && $product->discounted_price < $product->price)
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-semibold text-green-600">${{ number_format($product->discounted_price, 2) }}</span>
                                        <span class="text-xs text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                                    </div>
                                @else
                                    <span class="text-sm font-semibold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>

                            <div class="mt-1">
                                <p class="text-xs text-gray-600">SKU: {{ $product->sku ?? '-' }}</p>
                                <p class="text-xs text-gray-500">Category: {{ $product->category?->name ?? '-' }}</p>
                            </div>

                            <!-- Mobile Actions -->
                            <div class="flex items-center space-x-2 mt-2 pt-2 border-t border-gray-100">
                                <a href="{{ route('shop.details', $product->slug) }}" target="_blank"
                                    class="flex-1 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 text-xs font-medium text-center transition-colors">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <button
                                    wire:click="edit({{ $product->id }})"
                                    class="flex-1 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 text-xs font-medium text-center transition-colors"
                                >
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button
                                    wire:click="delete({{ $product->id }})"
                                    wire:confirm="Are you sure you want to delete this product?"
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
                    <i class="fas fa-box-open text-4xl sm:text-5xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">No products found.</p>
                    @if($search)
                        <p class="text-sm text-gray-400 mt-1">Try adjusting your search criteria</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="border-t border-gray-200 px-4 sm:px-6 py-4 bg-gray-50">
                {{ $products->links() }}
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
                        {{ $formType === 'create' ? 'Create New Product' : 'Edit Product' }}
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
                <form wire:submit="save" class="space-y-4 sm:space-y-6" enctype="multipart/form-data">
                    <!-- SEO Analysis Panel -->
                    <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-3 gap-2">
                            <h4 class="text-sm sm:text-md font-semibold text-gray-900">SEO Analysis</h4>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs sm:text-sm font-medium text-gray-700">Score:</span>
                                <span class="text-base sm:text-lg font-bold {{ $seoScore >= 80 ? 'text-green-600' : ($seoScore >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ number_format($seoScore, 0) }}%
                                </span>
                            </div>
                        </div>

                        <!-- Score Bar -->
                        <div class="bg-gray-200 rounded-full h-2 mb-4">
                            <div class="h-2 rounded-full {{ $seoScore >= 80 ? 'bg-green-500' : ($seoScore >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                style="width: {{ $seoScore }}%"></div>
                        </div>

                        <!-- SEO Metrics -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
                            @foreach($seoMetrics as $metric)
                            <div class="flex justify-between items-center p-2 rounded text-xs sm:text-sm {{ $metric['status'] ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                                <span class="font-medium text-gray-700">{{ $metric['name'] }}</span>
                                <span class="{{ $metric['status'] ? 'text-green-600' : 'text-red-600' }} font-medium">
                                    {{ $metric['message'] }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <!-- Name -->
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Product Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="name"
                                wire:keyup="analyzeSeo"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] disabled:opacity-50 text-sm"
                                placeholder="Enter product name"
                            >
                            @error('name')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                            <input
                                type="text"
                                wire:model="slug"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] disabled:opacity-50 text-sm"
                                placeholder="auto-generated from name"
                            >
                            @error('slug')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Categories -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select
                                wire:model="category_id"
                                wire:change="analyzeSeo"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm"
                            >
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sub Category</label>
                            <select
                                wire:model="sub_category_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm {{ empty($subCategories) ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                {{ empty($subCategories) ? 'disabled' : '' }}
                            >
                                <option value="">Select Sub Category</option>
                                @foreach($subCategories as $subCategory)
                                    <option value="{{ $subCategory['id'] }}">{{ $subCategory['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Fields -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Price <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                step="0.01"
                                wire:model="price"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm"
                                placeholder="0.00"
                            >
                            @error('price')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Discounted Price</label>
                            <input
                                type="number"
                                step="0.01"
                                wire:model="discounted_price"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm"
                                placeholder="0.00"
                            >
                            @error('discounted_price')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Stock & MOQ -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Stock <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                wire:model="stock"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm"
                                placeholder="0"
                            >
                            @error('stock')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Order Quantity (MOQ)</label>
                            <input
                                type="number"
                                wire:model="moq"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm"
                                placeholder="1"
                            >
                        </div>

                        <!-- Identifiers -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                            <input
                                type="text"
                                wire:model="sku"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm"
                                placeholder="Product SKU"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">UPC</label>
                            <input
                                type="text"
                                wire:model="upc"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm"
                                placeholder="Universal Product Code"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ASIN</label>
                            <input
                                type="text"
                                wire:model="asin"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm"
                                placeholder="Amazon Standard ID"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Manufacturer</label>
                            <input
                                type="text"
                                wire:model="manufacturer"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm"
                                placeholder="Manufacturer name"
                            >
                        </div>

                        <!-- Product Type -->
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Product Type
                                <span class="text-xs text-gray-500">(Optional)</span>
                            </label>
                            <select
                                wire:model="type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm"
                            >
                                <option value="">Select Type</option>
                                <option value="featured">Featured</option>
                                <option value="best_seller">Best Seller</option>
                            </select>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea
                            wire:model="description"
                            wire:keyup="analyzeSeo"
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm resize-y"
                            placeholder="Product description"
                        ></textarea>
                    </div>

                    <!-- Main Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>

                        <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                            @if ($image && is_object($image))
                                <div class="relative flex-shrink-0">
                                    <img
                                        src="{{ $image->temporaryUrl() }}"
                                        class="h-24 w-24 rounded-xl object-cover border"
                                    >
                                    <button
                                        type="button"
                                        wire:click="removeCurrentImage"
                                        class="absolute -top-2 -right-2 bg-red-600 text-white h-6 w-6 rounded-full text-xs hover:bg-red-700"
                                    >
                                        ×
                                    </button>
                                </div>
                            @elseif ($existingImage)
                                <div class="relative flex-shrink-0">
                                    <img
                                        src="{{ asset('storage/' . $existingImage) }}"
                                        class="h-24 w-24 rounded-xl object-cover border"
                                    >
                                    <button
                                        type="button"
                                        wire:click="removeCurrentImage"
                                        class="absolute -top-2 -right-2 bg-red-600 text-white h-6 w-6 rounded-full text-xs hover:bg-red-700"
                                    >
                                        ×
                                    </button>
                                </div>
                            @endif

                            <div class="relative w-full">
                                <input
                                    type="file"
                                    wire:model="image"
                                    accept="image/*"
                                    class="w-full text-xs sm:text-sm text-gray-500 file:mr-4 file:py-1.5 sm:file:py-2 file:px-3 sm:file:px-4 file:rounded-md file:border-0 file:text-xs sm:file:text-sm file:font-semibold file:bg-[#5C9F01] file:text-white hover:file:bg-[#4a7e01] transition-colors cursor-pointer disabled:opacity-50"
                                    wire:loading.attr="disabled"
                                >
                                <div wire:loading wire:target="image" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-md">
                                    <div class="flex items-center space-x-2">
                                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-[#5C9F01]"></div>
                                        <span class="text-xs text-gray-500">Uploading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Multiple Product Images Gallery -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>

                        <div class="relative w-full">
                            <input
                                type="file"
                                wire:model="galleryImages"
                                multiple
                                accept="image/*"
                                class="w-full text-xs sm:text-sm text-gray-500 file:mr-4 file:py-1.5 sm:file:py-2 file:px-3 sm:file:px-4 file:rounded-md file:border-0 file:text-xs sm:file:text-sm file:font-semibold file:bg-[#5C9F01] file:text-white hover:file:bg-[#4a7e01] transition-colors cursor-pointer disabled:opacity-50"
                                wire:loading.attr="disabled"
                            >
                            <div wire:loading wire:target="galleryImages" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-md">
                                <div class="flex items-center space-x-2">
                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-[#5C9F01]"></div>
                                    <span class="text-xs text-gray-500">Uploading...</span>
                                </div>
                            </div>
                        </div>

                        <!-- New Gallery Images Preview -->
                        @if ($galleryImages && count($galleryImages) > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-3">
                                @foreach ($galleryImages as $index => $galleryImage)
                                    @if(is_object($galleryImage))
                                        <div class="relative">
                                            <img
                                                src="{{ $galleryImage->temporaryUrl() }}"
                                                class="h-24 w-full rounded-xl object-cover border"
                                            >
                                            <button
                                                type="button"
                                                wire:click="removeNewImage({{ $index }})"
                                                class="absolute -top-2 -right-2 bg-red-600 text-white h-6 w-6 rounded-full text-xs hover:bg-red-700"
                                            >
                                                ×
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <!-- Existing Gallery Images -->
                        @if(!empty($existingGalleryImages))
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-3">
                            @foreach($existingGalleryImages as $gallery)
                            @php
                                $images = is_string($gallery['images']) ? json_decode($gallery['images'], true) : $gallery['images'];
                                $imagePath = is_array($images) ? $images[0] : $images;
                            @endphp
                            <div class="relative">
                                <img
                                    src="{{ asset('storage/' . $imagePath) }}"
                                    class="h-24 w-full rounded-xl object-cover border"
                                >
                                <button
                                    type="button"
                                    wire:click="removeExistingImage({{ $gallery['id'] }}, '{{ $imagePath }}')"
                                    class="absolute -top-2 -right-2 bg-red-600 text-white h-6 w-6 rounded-full text-xs hover:bg-red-700"
                                >
                                    ×
                                </button>
                            </div>
                        @endforeach
                            </div>
                        @endif

                        @error('galleryImages.*')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deal of the Day -->
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            wire:model="deal_of_the_day"
                            id="deal_of_the_day"
                            class="rounded border-gray-300 text-[#5C9F01] focus:ring-[#5C9F01] h-4 w-4"
                        >
                        <label for="deal_of_the_day" class="ml-2 text-sm text-gray-700">Deal of the Day</label>
                    </div>

                    <!-- SEO Section -->
                    <div class="border-t border-gray-200 pt-4 sm:pt-6">
                        <h4 class="text-sm sm:text-md font-semibold text-gray-900 mb-3 sm:mb-4">SEO Settings</h4>

                        <div class="space-y-3 sm:space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Focus Keyword
                                </label>
                                <input
                                    type="text"
                                    wire:model="focus_keyword"
                                    wire:keyup="analyzeSeo"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm"
                                    placeholder="e.g., organic cotton t-shirt"
                                >
                                @if(!empty($focus_keyword))
                                    <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full inline-block mt-2">
                                        <i class="fas fa-check-circle"></i> Keyword set: {{ Str::limit($focus_keyword, 30) }}
                                    </span>
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                                <input
                                    type="text"
                                    wire:model="meta_title"
                                    wire:keyup="analyzeSeo"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm"
                                    placeholder="Meta title for SEO"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                                <textarea
                                    wire:model="meta_description"
                                    wire:keyup="analyzeSeo"
                                    rows="2"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm resize-y"
                                    placeholder="Meta description for SEO"
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                                <input
                                    type="text"
                                    wire:model="meta_keywords"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm"
                                    placeholder="keyword1, keyword2, keyword3"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Tags</label>
                                <textarea
                                    wire:model="meta_tags"
                                    rows="2"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm resize-y"
                                    placeholder="Additional meta tags"
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Page Schemas (JSON)</label>
                                <textarea
                                    wire:model="page_schemas"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] font-mono text-xs sm:text-sm resize-y"
                                    placeholder=''
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            wire:model="is_active"
                            id="is_active"
                            class="rounded border-gray-300 text-[#5C9F01] focus:ring-[#5C9F01] h-4 w-4"
                        >
                        <label for="is_active" class="ml-2 text-sm text-gray-700">Active Product</label>
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
                            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-[#5C9F01] rounded-md hover:bg-[#4a7e01] transition-colors flex items-center justify-center space-x-2 disabled:opacity-50 order-1 sm:order-2"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove wire:target="save">
                                {{ $formType === 'create' ? 'Create Product' : 'Update Product' }}
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

    <!-- Import Excel Modal -->
    @if($showImportModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" wire:click.self="closeImportModal">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="flex justify-between items-center px-4 sm:px-6 py-4 border-b border-gray-200">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Import Products from Excel</h3>
                <button wire:click="closeImportModal" class="text-gray-400 hover:text-gray-600 transition-colors p-1">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-4 sm:p-6">
                <form wire:submit="importProducts">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Excel File (.xlsx, .xls, .csv)
                        </label>
                        <div class="relative w-full">
                            <input
                                type="file"
                                wire:model="excelFile"
                                accept=".xlsx,.xls,.csv"
                                class="w-full text-xs sm:text-sm text-gray-500 file:mr-4 file:py-1.5 sm:file:py-2 file:px-3 sm:file:px-4 file:rounded-md file:border-0 file:text-xs sm:file:text-sm file:font-semibold file:bg-[#5C9F01] file:text-white hover:file:bg-[#4a7e01] transition-colors cursor-pointer disabled:opacity-50"
                                wire:loading.attr="disabled"
                            >
                            <div wire:loading wire:target="excelFile" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-md">
                                <div class="flex items-center space-x-2">
                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-[#5C9F01]"></div>
                                    <span class="text-xs text-gray-500">Uploading...</span>
                                </div>
                            </div>
                        </div>
                        @error('excelFile')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-md p-3 mb-4">
                        <p class="text-xs text-blue-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>Required columns:</strong> name, price<br>
                            <strong>Optional:</strong> description, stock, discounted_price, sku, upc, asin, manufacturer, moq, category_id, sub_category_id, meta_title, meta_description, meta_keywords, meta_tags, page_schemas, focus_keyword, type, is_active
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                        <button
                            type="button"
                            wire:click="closeImportModal"
                            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors order-2 sm:order-1"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-[#5C9F01] rounded-md hover:bg-[#4a7e01] transition-colors flex items-center justify-center space-x-2 disabled:opacity-50 order-1 sm:order-2"
                            wire:loading.attr="disabled"
                            wire:target="importProducts,excelFile"
                        >
                            <span wire:loading.remove wire:target="importProducts">
                                <i class="fas fa-upload"></i>
                                <span>Import</span>
                            </span>
                            <span wire:loading wire:target="importProducts" class="flex items-center space-x-2">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                                <span>Importing...</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="border-t border-gray-200 px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 rounded-b-lg">
                <button
                    wire:click="downloadTemplate"
                    class="text-sm text-[#5C9F01] hover:text-[#4a7e01] flex items-center space-x-1 w-full justify-center sm:w-auto"
                >
                    <i class="fas fa-download"></i>
                    <span>Download Excel Template</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
