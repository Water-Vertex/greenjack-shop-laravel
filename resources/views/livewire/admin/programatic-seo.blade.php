<div class="p-3 sm:p-6">

    {{-- Header --}}
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Programatic SEO Management</h1>
                <p class="text-sm text-gray-600 mt-1">Upload CSV/Excel to manage SEO records</p>
            </div>
            <div class="text-sm text-gray-500 bg-gray-100 px-3 py-1.5 rounded-lg">
                Total Records: <span class="font-semibold text-gray-900">{{ $totalCount }}</span>
            </div>
        </div>
    </div>

    {{-- IMPORT CARD --}}
    <div class="bg-white rounded-lg border border-gray-200 mb-4 sm:mb-6 overflow-hidden">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-file-import text-blue-500"></i>
                Import CSV / Excel File
            </h3>
            <div wire:loading wire:target="csvFile" class="text-blue-600 text-xs font-bold animate-pulse flex items-center gap-1">
                <i class="fas fa-spinner fa-spin"></i> Uploading file...
            </div>
        </div>
        <div class="p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 items-start">
                <div class="flex-1 w-full">
                    <input type="file"
                           wire:model="csvFile"
                           wire:key="file-input-{{ $fileInputKey }}"
                           accept=".csv,.xls,.xlsx,.txt"
                           class="w-full text-xs sm:text-sm text-gray-500 border border-gray-300 rounded-lg bg-gray-50 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#5C9F01] file:mr-3 file:py-1.5 sm:file:py-2 file:px-3 sm:file:px-4 file:rounded-md file:border-0 file:text-xs sm:file:text-sm file:font-semibold file:bg-[#5C9F01] file:text-white hover:file:bg-[#4a8001] transition-colors">
                    @error('csvFile')
                        <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <button wire:click="importFile"
                        wire:loading.attr="disabled"
                        wire:target="importFile"
                        class="w-full sm:w-auto px-6 py-2 bg-[#5C9F01] text-white rounded-lg flex items-center justify-center font-semibold transition-all hover:bg-[#4a8001] disabled:opacity-50 min-w-[160px] text-sm">
                    <span wire:loading.remove wire:target="importFile">
                        <i class="fas fa-upload mr-2"></i>Import Now
                    </span>
                    <span wire:loading wire:target="importFile" class="flex items-center">
                        <svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </div>

            @if($importMessage)
                <div class="mt-4 p-3 sm:p-4 rounded-lg {{ $importSuccess ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200' }}">
                    <div class="flex items-center gap-2">
                        <i class="fas {{ $importSuccess ? 'fa-check-circle text-green-500' : 'fa-exclamation-circle text-red-500' }}"></i>
                        <p class="text-sm font-semibold">{{ $importMessage }}</p>
                    </div>
                    @if($importSuccess && ($importSummary['new'] > 0 || $importSummary['updated'] > 0))
                        <div class="flex flex-wrap gap-3 sm:gap-4 mt-2 text-xs font-bold uppercase">
                            <span class="text-green-600">New: {{ $importSummary['new'] }}</span>
                            <span class="text-blue-600">Updated: {{ $importSummary['updated'] }}</span>
                            <span class="text-gray-500">Skipped: {{ $importSummary['skipped'] }}</span>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- SEARCH & PER PAGE --}}
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
            <div class="sm:col-span-1 lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Search by keyword, title, heading or ID..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Per Page</label>
                <select wire:model.live="perPage"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>
        </div>
    </div>

    {{-- LOADING INDICATOR --}}
    <div wire:loading wire:target="search,perPage,sortBy,importFile"
        class="bg-white rounded-lg border border-gray-200 p-8 text-center mb-6">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#5C9F01] mx-auto"></div>
        <p class="text-gray-500 mt-2 text-sm">Loading...</p>
    </div>

    {{-- TABLE --}}
    <div wire:loading.remove wire:target="search,perPage,sortBy,importFile"
        class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th wire:click="sortBy('id')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 select-none">
                            ID @if($sortField=='id')<i class="fas fa-sort-{{ $sortDirection=='asc' ? 'up' : 'down' }} ml-1"></i>@endif
                        </th>
                        <th wire:click="sortBy('focus_keyword')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 select-none">
                            Focus Keyword @if($sortField=='focus_keyword')<i class="fas fa-sort-{{ $sortDirection=='asc' ? 'up' : 'down' }} ml-1"></i>@endif
                        </th>
                        <th wire:click="sortBy('title')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 select-none">
                            Title @if($sortField=='title')<i class="fas fa-sort-{{ $sortDirection=='asc' ? 'up' : 'down' }} ml-1"></i>@endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">H1 Heading</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th wire:click="sortBy('created_at')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 select-none">
                            Date @if($sortField=='created_at')<i class="fas fa-sort-{{ $sortDirection=='asc' ? 'up' : 'down' }} ml-1"></i>@endif
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($records as $record)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $record->id }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 max-w-[180px] truncate" title="{{ $record->focus_keyword }}">
                                {{ $record->focus_keyword }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-[180px]">
                            <span class="block truncate" title="{{ $record->title }}">{{ $record->title ?: '—' }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-[200px]">
                            <span class="block truncate" title="{{ $record->h1_heading }}">
                                {{ $record->h1_heading ? Str::limit($record->h1_heading, 50) : '—' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($record->image)
                                <img src="{{ $record->image }}" alt="{{ $record->image_alt }}"
                                    class="w-10 h-10 object-cover rounded-lg border border-gray-200"
                                    onerror="this.style.display='none'">
                            @else
                                <span class="text-gray-400 text-xs">No image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $record->created_at->format('M d, Y') }}
                            <div class="text-xs text-gray-400">{{ $record->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('seo.show', $record->slug ?? Str::slug($record->focus_keyword ?? '')) }}"
                                    target="_blank"
                                    class="text-blue-600 hover:text-blue-800 transition p-1"
                                    title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button wire:click="openEditModal({{ $record->id }})"
                                    class="text-orange-600 hover:text-orange-800 transition p-1"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="confirmDelete({{ $record->id }})"
                                    class="text-red-600 hover:text-red-800 transition p-1"
                                    title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <i class="fas fa-file-csv text-4xl text-gray-300 mb-3 block"></i>
                            <p class="text-gray-500">No SEO records found</p>
                            @if($search)
                            <button wire:click="$set('search', '')" class="mt-3 text-sm text-[#5C9F01] hover:underline">
                                <i class="fas fa-undo-alt mr-1"></i> Clear search
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden divide-y divide-gray-200">
            @forelse($records as $record)
            <div class="p-4 hover:bg-gray-50 transition">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium text-gray-500">#{{ $record->id }}</span>
                            <span class="text-xs font-medium text-gray-900 truncate">{{ $record->focus_keyword }}</span>
                        </div>
                        <p class="text-sm font-medium text-gray-900 mt-1 truncate">{{ $record->title ?: 'No title' }}</p>
                        <div class="mt-1 space-y-1">
                            @if($record->h1_heading)
                                <p class="text-xs text-gray-500 truncate">
                                    <span class="font-medium">H1:</span> {{ Str::limit($record->h1_heading, 40) }}
                                </p>
                            @endif
                            @if($record->image)
                                <div class="mt-1">
                                    <img src="{{ $record->image }}" alt="{{ $record->image_alt }}"
                                        class="w-12 h-12 object-cover rounded-lg border border-gray-200"
                                        onerror="this.style.display='none'">
                                </div>
                            @endif
                            <p class="text-xs text-gray-400">{{ $record->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1 ml-2 flex-shrink-0">
                        <a href="{{ route('seo.show', $record->slug ?? Str::slug($record->focus_keyword ?? '')) }}"
                            target="_blank"
                            class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition"
                            title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button wire:click="openEditModal({{ $record->id }})"
                            class="p-2 text-orange-600 hover:text-orange-800 hover:bg-orange-50 rounded-lg transition"
                            title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button wire:click="confirmDelete({{ $record->id }})"
                            class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition"
                            title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-4 py-12 text-center">
                <i class="fas fa-file-csv text-4xl text-gray-300 mb-3 block"></i>
                <p class="text-gray-500">No SEO records found</p>
                @if($search)
                <button wire:click="$set('search', '')" class="mt-3 text-sm text-[#5C9F01] hover:underline">
                    <i class="fas fa-undo-alt mr-1"></i> Clear search
                </button>
                @endif
            </div>
            @endforelse
        </div>

        @if($records->hasPages())
        <div class="border-t border-gray-200 px-4 sm:px-6 py-4 bg-gray-50">
            {{ $records->links() }}
        </div>
        @endif
    </div>

    {{-- EDIT MODAL --}}
    @if($showFormModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" wire:click="$set('showFormModal', false)"></div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full relative z-10 w-full">
                <div class="bg-gray-50 px-4 sm:px-6 py-4 border-b border-gray-200 sticky top-0 bg-white z-10">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas {{ $editingId ? 'fa-edit text-orange-500' : 'fa-plus-circle text-[#5C9F01]' }}"></i>
                            {{ $editingId ? 'Edit SEO Record' : 'Add New SEO Record' }}
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500 p-1">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="px-4 sm:px-6 py-4 sm:py-5 max-h-[70vh] overflow-y-auto space-y-4 sm:space-y-5">
                    {{-- Basic Info --}}
                    <div>
                        <h4 class="text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3 border-b pb-1">Basic Info</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Focus Keyword <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="focus_keyword"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#5C9F01] focus:border-[#5C9F01]">
                                @error('focus_keyword')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                <input type="text" wire:model.defer="title"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#5C9F01] focus:border-[#5C9F01]">
                                @error('title')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                                <input type="text" wire:model.defer="slug"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#5C9F01] focus:border-[#5C9F01]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">H1 Heading</label>
                                <input type="text" wire:model.defer="h1_heading"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#5C9F01] focus:border-[#5C9F01]">
                            </div>
                        </div>
                    </div>

                    {{-- SEO Meta --}}
                    <div>
                        <h4 class="text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3 border-b pb-1">SEO Meta</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                                <input type="text" wire:model.defer="meta_title"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#5C9F01] focus:border-[#5C9F01]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                                <input type="text" wire:model.defer="meta_keywords"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#5C9F01] focus:border-[#5C9F01]">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                                <textarea wire:model.defer="meta_description" rows="2"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#5C9F01] focus:border-[#5C9F01]"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Tags</label>
                                <input type="text" wire:model.defer="meta_tags"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#5C9F01] focus:border-[#5C9F01]">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Page Schema (JSON-LD)</label>
                                <textarea wire:model.defer="page_schema" rows="2"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:ring-[#5C9F01] focus:border-[#5C9F01]"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Main Content --}}
                    <div>
                        <h4 class="text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3 border-b pb-1">Main Content</h4>
                        <textarea wire:model.defer="content" rows="5"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:ring-[#5C9F01] focus:border-[#5C9F01]"></textarea>
                    </div>

                    {{-- Main Image --}}
                    <div>
                        <h4 class="text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3 border-b pb-1">Main Image</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Image URL</label>
                                <input type="text" wire:model.defer="image"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#5C9F01] focus:border-[#5C9F01]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alt Text</label>
                                <input type="text" wire:model.defer="image_alt"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#5C9F01] focus:border-[#5C9F01]">
                            </div>
                        </div>
                    </div>

                    {{-- Left Section --}}
                    <div>
                        <h4 class="text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3 border-b pb-1">Left Section</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Image Left URL</label>
                                <input type="text" wire:model.defer="image_left"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#5C9F01] focus:border-[#5C9F01]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Image Left Alt</label>
                                <input type="text" wire:model.defer="image_left_alt"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#5C9F01] focus:border-[#5C9F01]">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Content Left</label>
                                <textarea wire:model.defer="section_content_left" rows="3"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#5C9F01] focus:border-[#5C9F01]"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Right Section --}}
                    <div>
                        <h4 class="text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3 border-b pb-1">Right Section</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Image Right URL</label>
                                <input type="text" wire:model.defer="image_right"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#5C9F01] focus:border-[#5C9F01]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Image Right Alt</label>
                                <input type="text" wire:model.defer="image_right_alt"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#5C9F01] focus:border-[#5C9F01]">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Content Right</label>
                                <textarea wire:model.defer="section_content_right" rows="3"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#5C9F01] focus:border-[#5C9F01]"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- FAQs --}}
                    <div>
                        <h4 class="text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3 border-b pb-1">FAQs (JSON)</h4>
                        <textarea wire:model.defer="faqs" rows="4"
                            placeholder='[{"question":"...","answer":"..."}]'
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:ring-[#5C9F01] focus:border-[#5C9F01]"></textarea>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 sm:px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-2 sm:gap-3">
                    <button wire:click="$set('showFormModal', false)"
                        class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 order-2 sm:order-1">
                        Cancel
                    </button>
                    <button wire:click="save"
                        wire:loading.attr="disabled"
                        wire:target="save"
                        class="w-full sm:w-auto px-5 py-2 text-sm font-medium text-white bg-[#5C9F01] rounded-lg hover:bg-[#4a8001] flex items-center justify-center min-w-[120px] order-1 sm:order-2">
                        <span wire:loading.remove wire:target="save">
                            Save Changes
                        </span>
                        <span wire:loading wire:target="save" class="flex items-center">
                            <svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Saving...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- DELETE CONFIRMATION MODAL --}}
    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" wire:click="$set('showDeleteModal', false)"></div>

            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full relative z-10 w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center">
                        <h3 class="text-lg font-medium text-gray-900">Delete Record?</h3>
                        <p class="mt-2 text-sm text-gray-500">Are you sure you want to delete this record? This action cannot be undone.</p>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-2">
                    <button wire:click="deleteRecord"
                        wire:loading.attr="disabled"
                        wire:target="deleteRecord"
                        class="w-full sm:w-auto px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold text-sm flex items-center justify-center min-w-[110px] order-1 sm:order-2">
                        <span wire:loading.remove wire:target="deleteRecord">
                            Yes, Delete it
                        </span>
                        <span wire:loading wire:target="deleteRecord" class="flex items-center">
                            <svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Deleting...
                        </span>
                    </button>
                    <button wire:click="$set('showDeleteModal', false)"
                        class="w-full sm:w-auto px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold text-sm order-2 sm:order-1">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- FLASH MESSAGES --}}
    @if(session()->has('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
        class="fixed bottom-4 right-4 left-4 sm:left-auto z-50">
        <div class="bg-green-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
            <i class="fas fa-check-circle"></i>
            <span class="text-sm sm:text-base">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session()->has('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
        class="fixed bottom-4 right-4 left-4 sm:left-auto z-50">
        <div class="bg-red-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
            <i class="fas fa-exclamation-circle"></i>
            <span class="text-sm sm:text-base">{{ session('error') }}</span>
        </div>
    </div>
    @endif

</div>
