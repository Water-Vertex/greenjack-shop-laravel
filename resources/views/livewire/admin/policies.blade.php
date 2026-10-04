<div>
    <!-- Header Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Policies Management</h1>
                <p class="text-sm text-gray-600 mt-1">Manage your website Policies with SEO optimization</p>
            </div>
            <button
                wire:click="create"
                wire:loading.attr="disabled"
                class="w-full sm:w-auto px-4 py-2 bg-[#5C9F01] text-white rounded-md hover:bg-[#4a8501] transition-colors flex items-center justify-center sm:justify-start space-x-2 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span wire:loading.remove wire:target="create">
                    <i class="fas fa-plus"></i>
                    <span>Add Policy</span>
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
                        placeholder="Search policies..."
                        class="bg-transparent border-none focus:outline-none focus:ring-0 w-full text-sm"
                    >
                </div>
            </div>

            <!-- Sort and Per Page -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <select wire:model.live="sortField" class="bg-gray-100 border-none rounded-lg px-3 py-2 text-sm focus:ring-0 w-full sm:w-auto">
                    <option value="title">Sort by Title</option>
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

    <!-- Policies Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <!-- Desktop Table Header -->
        <div class="hidden md:block border-b border-gray-200">
            <div class="grid grid-cols-12 gap-4 px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">
                <div class="col-span-3">Policy</div>
                <div class="col-span-2">Meta Title</div>
                <div class="col-span-2">Type</div>
                <div class="col-span-2">Last Updated</div>
                <div class="col-span-3 text-center">Actions</div>
            </div>
        </div>

        <!-- Table Body -->
        <div class="divide-y divide-gray-200">
            @if($policies->count() > 0)
                @foreach($policies as $policy)
                <!-- Desktop View -->
                <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 items-center hover:bg-gray-50 transition-colors">
                    <!-- Policy Info -->
                    <div class="col-span-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200 flex-shrink-0">
                                <i class="fas fa-file text-gray-400"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $policy->title }}</p>
                                <p class="text-xs text-gray-500 truncate">/{{ $policy->slug }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Meta Title -->
                    <div class="col-span-2">
                        @if($policy->meta_title)
                            <p class="text-sm text-gray-900 truncate" title="{{ $policy->meta_title }}">
                                {{ Str::limit($policy->meta_title, 50) }}
                            </p>
                        @else
                            <span class="text-sm text-gray-400">-</span>
                        @endif
                    </div>

                    <!-- Type -->
                    <div class="col-span-2">
                        @if($policy->is_repairing_policy)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-tools mr-1"></i> Repair
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                <i class="fas fa-file-alt mr-1"></i> General
                            </span>
                        @endif
                    </div>

                    <!-- Last Updated -->
                    <div class="col-span-2">
                        <span class="text-sm text-gray-600">{{ $policy->updated_at->format('M d, Y') }}</span>
                        <p class="text-xs text-gray-400">{{ $policy->updated_at->format('h:i A') }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="col-span-3">
                        <div class="flex items-center justify-center space-x-2">
                            <a
                                href="{{ route('policy', $policy->slug) }}"
                                target="_blank"
                                class="px-3 py-1 bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition-colors text-xs font-medium flex items-center space-x-1"
                                title="View Policy"
                            >
                                <i class="fas fa-eye text-xs"></i>
                                <span class="hidden sm:inline">View</span>
                            </a>

                            <button
                                wire:click="edit({{ $policy->id }})"
                                wire:loading.attr="disabled"
                                class="px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors text-xs font-medium flex items-center space-x-1 disabled:opacity-50"
                                title="Edit Policy"
                            >
                                <span wire:loading.remove wire:target="edit({{ $policy->id }})">
                                    <i class="fas fa-edit text-xs"></i>
                                    <span class="hidden sm:inline">Edit</span>
                                </span>
                                <span wire:loading wire:target="edit({{ $policy->id }})" class="flex items-center space-x-1">
                                    <div class="animate-spin rounded-full h-3 w-3 border-b-2 border-blue-700"></div>
                                    <span class="hidden sm:inline">Loading...</span>
                                </span>
                            </button>

                            <button
                                wire:click="delete({{ $policy->id }})"
                                wire:confirm="Are you sure you want to delete this policy?"
                                wire:loading.attr="disabled"
                                class="px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors text-xs font-medium flex items-center space-x-1 disabled:opacity-50"
                                title="Delete Policy"
                            >
                                <span wire:loading.remove wire:target="delete({{ $policy->id }})">
                                    <i class="fas fa-trash text-xs"></i>
                                    <span class="hidden sm:inline">Delete</span>
                                </span>
                                <span wire:loading wire:target="delete({{ $policy->id }})" class="flex items-center space-x-1">
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
                        <!-- Icon -->
                        <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                            <i class="fas fa-file text-gray-400"></i>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $policy->title }}</p>
                                    <p class="text-xs text-gray-500 truncate">/{{ $policy->slug }}</p>
                                </div>
                                <div class="flex flex-col items-end ml-2 flex-shrink-0">
                                    @if($policy->is_repairing_policy)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-tools mr-1"></i> Repair
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            <i class="fas fa-file-alt mr-1"></i> General
                                        </span>
                                    @endif
                                    <span class="text-xs text-gray-400 mt-1">{{ $policy->updated_at->format('M d, Y') }}</span>
                                </div>
                            </div>

                            @if($policy->meta_title)
                                <p class="text-xs text-gray-500 mt-1 truncate">
                                    <span class="font-medium">Meta:</span> {{ Str::limit($policy->meta_title, 30) }}
                                </p>
                            @endif

                            <!-- Mobile Actions -->
                            <div class="flex items-center space-x-2 mt-2 pt-2 border-t border-gray-100">
                                <a
                                    href="{{ route('policy', $policy->slug) }}"
                                    target="_blank"
                                    class="flex-1 px-3 py-1.5 bg-green-100 text-green-700 rounded-md hover:bg-green-200 text-xs font-medium text-center transition-colors"
                                >
                                    <i class="fas fa-eye"></i> View
                                </a>

                                <button
                                    wire:click="edit({{ $policy->id }})"
                                    class="flex-1 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 text-xs font-medium text-center transition-colors"
                                >
                                    <i class="fas fa-edit"></i> Edit
                                </button>

                                <button
                                    wire:click="delete({{ $policy->id }})"
                                    wire:confirm="Are you sure you want to delete this policy?"
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
                    <i class="fas fa-file-alt text-4xl sm:text-5xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">No policies found.</p>
                    @if($search)
                        <p class="text-sm text-gray-400 mt-1">Try adjusting your search criteria</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($policies->hasPages())
            <div class="border-t border-gray-200 px-4 sm:px-6 py-4 bg-gray-50">
                {{ $policies->links() }}
            </div>
        @endif
    </div>

    <!-- Modal with Blur Overlay -->
    @if($showForm)
    <!-- Blur Backdrop -->
    <div class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm transition-all duration-300" wire:click="resetForm"></div>

    <!-- Modal Container -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
            <!-- Modal Header -->
            <div class="flex justify-between items-center px-4 sm:px-6 py-4 border-b border-gray-200 sticky top-0 bg-white z-10">
                <div class="flex items-center min-w-0">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">
                        {{ $formType === 'create' ? 'Create New Policy' : 'Edit Policy' }}
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
                    <!-- SEO Analysis Panel -->
                    <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-3 gap-2">
                            <h4 class="text-sm sm:text-md font-semibold text-gray-900">SEO Analysis</h4>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs sm:text-sm font-medium text-gray-700">Score:</span>
                                <span class="text-base sm:text-lg font-bold {{ $seoScore >= 80 ? 'text-green-600' : ($seoScore >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $seoScore }}%
                                </span>
                            </div>
                        </div>

                        <!-- Score Bar -->
                        <div class="score-bar bg-gray-200 rounded-full h-2 mb-4">
                            <div class="score-fill h-2 rounded-full {{ $seoScore >= 80 ? 'bg-green-500' : ($seoScore >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                 style="width: {{ $seoScore }}%"></div>
                        </div>

                        <!-- SEO Metrics -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
                            @foreach($seoMetrics as $metric)
                            <div class="metric flex justify-between items-center p-2 rounded text-xs sm:text-sm {{ $metric['status'] ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                                <span class="font-medium text-gray-700">{{ $metric['name'] }}</span>
                                <span class="{{ $metric['status'] ? 'text-green-600' : 'text-red-600' }} font-medium">
                                    {{ $metric['message'] }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 gap-4 sm:gap-6">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Policy Title <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="title"
                                wire:keyup="analyzeSeo"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] disabled:opacity-50 text-sm"
                                placeholder="Enter policy title"
                            >
                            @error('title')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Repairing Policy Checkbox -->
                        <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
                            <div class="flex items-start space-x-3">
                                <div class="flex items-center h-5 mt-0.5">
                                    <input
                                        type="checkbox"
                                        wire:model="is_repairing_policy"
                                        id="is_repairing_policy"
                                        class="w-4 h-4 text-[#5C9F01] border-gray-300 rounded focus:ring-[#5C9F01]"
                                        wire:loading.attr="disabled"
                                    >
                                </div>
                                <div class="flex-1 min-w-0">
                                    <label for="is_repairing_policy" class="text-sm font-medium text-gray-700 cursor-pointer">
                                        Repairing Policy
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Enable if this policy relates to device repair services.
                                    </p>
                                    <div class="mt-2 flex flex-wrap items-center gap-2">
                                        <span class="text-xs font-medium {{ $is_repairing_policy ? 'text-green-600' : 'text-gray-400' }}">
                                            <i class="fas {{ $is_repairing_policy ? 'fa-check-circle' : 'fa-circle' }} mr-1"></i>
                                            {{ $is_repairing_policy ? 'Enabled' : 'Disabled' }}
                                        </span>
                                        @if($is_repairing_policy)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-tools mr-1"></i> Repair Policy
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CKEditor Content -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Content <span class="text-red-500">*</span>
                            </label>
                            <div wire:ignore>
                                <textarea
                                    id="editor"
                                    wire:model="description"
                                    x-data="{
                                        init() {
                                            const editorElement = this.$el;
                                            ClassicEditor
                                                .create(editorElement, {
                                                    toolbar: {
                                                        items: [
                                                            'heading', '|',
                                                            'bold', 'italic', 'strikethrough', 'underline', '|',
                                                            'link', '|',
                                                            'bulletedList', 'numberedList', '|',
                                                            'blockQuote', 'insertTable', '|',
                                                            'undo', 'redo'
                                                        ]
                                                    },
                                                    heading: {
                                                        options: [
                                                            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                                                            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                                                            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                                                            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                                                        ]
                                                    }
                                                })
                                                .then(editor => {
                                                    editor.model.document.on('change:data', () => {
                                                        @this.set('description', editor.getData());
                                                        @this.analyzeSeo();
                                                    });

                                                    editor.setData(@this.description);

                                                    Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                                                        succeed(({ snapshot, effect }) => {
                                                            if (component.id === @this.__instance.id && @this.description !== editor.getData()) {
                                                                editor.setData(@this.description);
                                                            }
                                                        });
                                                    });
                                                })
                                                .catch(error => {
                                                    console.error('CKEditor error:', error);
                                                });
                                        }
                                    }"
                                    class="hidden"
                                ></textarea>
                            </div>
                            @error('description')
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
                                    wire:keyup="analyzeSeo"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] disabled:opacity-50 text-sm"
                                    placeholder="Meta title for SEO (defaults to policy title)"
                                >
                                <div class="flex flex-col sm:flex-row justify-between mt-1 gap-1">
                                    <p class="text-xs text-gray-500">Recommended length: 50-60 characters</p>
                                    <span class="text-xs {{ strlen($meta_title ?? '') <= 60 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ strlen($meta_title ?? '') }}/60
                                    </span>
                                </div>
                                @error('meta_title')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Meta Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                                <textarea
                                    wire:model="meta_description"
                                    wire:keyup="analyzeSeo"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] disabled:opacity-50 text-sm resize-y"
                                    placeholder="Meta description for SEO"
                                ></textarea>
                                <div class="flex flex-col sm:flex-row justify-between mt-1 gap-1">
                                    <p class="text-xs text-gray-500">Recommended length: 150-160 characters</p>
                                    <span class="text-xs {{ strlen($meta_description ?? '') <= 160 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ strlen($meta_description ?? '') }}/160
                                    </span>
                                </div>
                                @error('meta_description')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Page Schema -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Page Schema (JSON-LD)</label>
                                <textarea
                                    wire:model="page_schema"
                                    rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5C9F01] focus:border-[#5C9F01] disabled:opacity-50 font-mono text-xs sm:text-sm resize-y"
                                    placeholder=''
                                ></textarea>
                                @error('page_schema')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Enter valid JSON-LD schema for rich snippets. Leave empty if not needed.</p>
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
                                {{ $formType === 'create' ? 'Create Policy' : 'Update Policy' }}
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

@push('scripts')
<script src="{{ asset('js/ckeditor.js') }}"></script>
<style>
.score-bar {
    background: #e9ecef;
    border-radius: 10px;
    height: 8px;
    overflow: hidden;
}

.score-fill {
    height: 100%;
    transition: width 0.3s ease;
}

/* Prevent body scroll when modal is open */
body:has(.fixed.inset-0.z-40) {
    overflow: hidden;
}

/* CKEditor responsive */
.ck-editor__editable {
    min-height: 200px !important;
}

@media (min-width: 640px) {
    .ck-editor__editable {
        min-height: 300px !important;
    }
}

@media (max-width: 640px) {
    .ck-toolbar {
        flex-wrap: wrap !important;
    }
    .ck-toolbar .ck-toolbar__items {
        flex-wrap: wrap !important;
    }
}
</style>
@endpush
