<div>
    <!-- Header Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Printing Requests</h1>
                <p class="text-sm text-gray-600 mt-1">Manage and track all customer printing service requests</p>
            </div>
            <div class="text-sm text-gray-500 bg-gray-100 px-3 py-1.5 rounded-lg">
                Total Requests: <span class="font-semibold text-gray-900">{{ $totalRequests }}</span>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div class="sm:col-span-1 lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Search by name, email, phone, service..."
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
            <div class="flex items-end">
                @if($search || $perPage != 10 || $sortField != 'created_at' || $sortDirection != 'desc')
                <button wire:click="clearFilters"
                    class="w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-undo-alt mr-2"></i> Clear
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Printing Requests Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" wire:click="sortBy('name')">
                            Customer
                            @if($sortField == 'name') <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ml-1"></i> @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" wire:click="sortBy('created_at')">
                            Date
                            @if($sortField == 'created_at') <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ml-1"></i> @endif
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($requests as $request)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $request->name }}</div>
                            <div class="text-xs text-gray-500">ID: {{ $request->id }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->phone ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ Str::limit($request->service, 30) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $request->created_at->format('M d, Y') }}
                            <div class="text-xs text-gray-400">{{ $request->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button wire:click="viewRequest({{ $request->id }})"
                                class="text-blue-600 hover:text-blue-800 transition p-1" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">No printing requests found</p>
                            @if($search)
                            <button wire:click="clearFilters" class="mt-3 text-sm text-[#5C9F01] hover:underline">
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
            @forelse($requests as $request)
            <div class="p-4 hover:bg-gray-50 transition">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium text-gray-500">#{{ $request->id }}</span>
                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ Str::limit($request->service, 20) }}
                            </span>
                        </div>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $request->name }}</p>
                        <div class="mt-1 space-y-1">
                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                <i class="fas fa-envelope w-4 text-gray-400"></i>
                                {{ $request->email }}
                            </p>
                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                <i class="fas fa-phone w-4 text-gray-400"></i>
                                {{ $request->phone ?? 'N/A' }}
                            </p>
                            <p class="text-xs text-gray-400 flex items-center gap-1">
                                <i class="fas fa-calendar w-4 text-gray-400"></i>
                                {{ $request->created_at->format('M d, Y h:i A') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center ml-2 flex-shrink-0">
                        <button wire:click="viewRequest({{ $request->id }})"
                            class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-4 py-12 text-center">
                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">No printing requests found</p>
                @if($search)
                <button wire:click="clearFilters" class="mt-3 text-sm text-[#5C9F01] hover:underline">
                    <i class="fas fa-undo-alt mr-1"></i> Clear search
                </button>
                @endif
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($requests->hasPages())
        <div class="border-t border-gray-200 px-4 sm:px-6 py-4 bg-gray-50">
            {{ $requests->links() }}
        </div>
        @endif
    </div>

    <!-- View Request Modal -->
    @if($showViewModal && $viewingRequest)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="$set('showViewModal', false)"></div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full w-full">
                <div class="bg-gray-50 px-4 sm:px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Printing Request #{{ $viewingRequest->id }}</h3>
                        <button wire:click="$set('showViewModal', false)" class="text-gray-400 hover:text-gray-500 p-1">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="px-4 sm:px-6 py-4 max-h-[70vh] overflow-y-auto">
                    <!-- Customer Information -->
                    <div class="mb-6">
                        <h4 class="text-sm sm:text-md font-semibold text-gray-900 mb-3 border-b pb-2">Customer Information</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <span class="text-xs sm:text-sm text-gray-500">Full Name:</span>
                                <p class="text-sm sm:text-base font-medium text-gray-900 mt-1">{{ $viewingRequest->name }}</p>
                            </div>
                            <div>
                                <span class="text-xs sm:text-sm text-gray-500">Email Address:</span>
                                <p class="text-sm sm:text-base text-gray-900 mt-1 break-all">
                                    <a href="mailto:{{ $viewingRequest->email }}" class="text-blue-600 hover:underline">
                                        {{ $viewingRequest->email }}
                                    </a>
                                </p>
                            </div>
                            <div>
                                <span class="text-xs sm:text-sm text-gray-500">Phone Number:</span>
                                <p class="text-sm sm:text-base text-gray-900 mt-1">
                                    <a href="tel:{{ $viewingRequest->phone }}" class="text-blue-600 hover:underline">
                                        {{ $viewingRequest->phone ?? 'N/A' }}
                                    </a>
                                </p>
                            </div>
                            <div>
                                <span class="text-xs sm:text-sm text-gray-500">Submitted On:</span>
                                <p class="text-sm sm:text-base text-gray-900 mt-1">{{ $viewingRequest->created_at->format('F d, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Service Information -->
                    <div class="mb-6">
                        <h4 class="text-sm sm:text-md font-semibold text-gray-900 mb-3 border-b pb-2">Service Information</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <span class="text-xs sm:text-sm text-gray-500">Service Required:</span>
                                <p class="text-sm sm:text-base text-gray-900 mt-1">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                        {{ $viewingRequest->service }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <span class="text-xs sm:text-sm text-gray-500">Request Status:</span>
                                <div class="mt-1">
                                    <select wire:change="updateStatus({{ $viewingRequest->id }}, $event.target.value)"
                                        class="text-sm px-3 py-1 rounded-lg border border-gray-300 focus:ring-[#5C9F01] focus:border-[#5C9F01] w-full sm:w-auto">
                                        <option value="pending" {{ $viewingRequest->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $viewingRequest->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="completed" {{ $viewingRequest->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $viewingRequest->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message -->
                    @if($viewingRequest->message)
                    <div class="mb-6">
                        <h4 class="text-sm sm:text-md font-semibold text-gray-900 mb-3 border-b pb-2">Customer Message</h4>
                        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap break-words">{{ $viewingRequest->message }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Additional Info -->
                    <div class="mb-6">
                        <h4 class="text-sm sm:text-md font-semibold text-gray-900 mb-3 border-b pb-2">Additional Information</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <span class="text-xs sm:text-sm text-gray-500">Request ID:</span>
                                <p class="text-sm sm:text-base text-gray-900 mt-1">#{{ $viewingRequest->id }}</p>
                            </div>
                            <div>
                                <span class="text-xs sm:text-sm text-gray-500">Last Updated:</span>
                                <p class="text-sm sm:text-base text-gray-900 mt-1">{{ $viewingRequest->updated_at->format('F d, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 sm:px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-2 sm:space-x-3">
                    <button wire:click="$set('showViewModal', false)"
                        class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 order-2 sm:order-1">
                        Close
                    </button>
                    <button wire:click="deleteRequest({{ $viewingRequest->id }})"
                        wire:confirm="Are you sure you want to delete this request?"
                        class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 order-1 sm:order-2">
                        <i class="fas fa-trash mr-2"></i> Delete Request
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Flash Messages -->
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
