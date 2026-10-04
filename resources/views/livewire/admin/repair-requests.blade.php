<div>
    <!-- Header Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Repair Requests</h1>
                <p class="text-sm text-gray-600 mt-1">Manage and track all customer repair requests</p>
            </div>
            <div class="text-sm text-gray-500 bg-gray-100 px-3 py-1.5 rounded-lg">
                Total Requests: <span class="font-semibold text-gray-900">{{ $statusCounts['total'] }}</span>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 mb-4 sm:mb-6">
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 cursor-pointer hover:shadow-md transition" wire:click="$set('statusFilter', '')">
            <div class="text-xs sm:text-sm text-gray-500">Total</div>
            <div class="text-lg sm:text-2xl font-bold text-gray-900">{{ $statusCounts['total'] }}</div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 cursor-pointer hover:shadow-md transition" wire:click="$set('statusFilter', 'pending')">
            <div class="text-xs sm:text-sm text-gray-500">Pending</div>
            <div class="text-lg sm:text-2xl font-bold text-yellow-600">{{ $statusCounts['pending'] }}</div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 cursor-pointer hover:shadow-md transition" wire:click="$set('statusFilter', 'confirmed')">
            <div class="text-xs sm:text-sm text-gray-500">Confirmed</div>
            <div class="text-lg sm:text-2xl font-bold text-blue-600">{{ $statusCounts['confirmed'] }}</div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 cursor-pointer hover:shadow-md transition" wire:click="$set('statusFilter', 'in_progress')">
            <div class="text-xs sm:text-sm text-gray-500">In Progress</div>
            <div class="text-lg sm:text-2xl font-bold text-purple-600">{{ $statusCounts['in_progress'] }}</div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 cursor-pointer hover:shadow-md transition" wire:click="$set('statusFilter', 'completed')">
            <div class="text-xs sm:text-sm text-gray-500">Completed</div>
            <div class="text-lg sm:text-2xl font-bold text-green-600">{{ $statusCounts['completed'] }}</div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 cursor-pointer hover:shadow-md transition" wire:click="$set('statusFilter', 'cancelled')">
            <div class="text-xs sm:text-sm text-gray-500">Cancelled</div>
            <div class="text-lg sm:text-2xl font-bold text-red-600">{{ $statusCounts['cancelled'] }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div class="sm:col-span-1 lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name, email, phone or ID..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Filter</label>
                <select wire:model.live="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Per Page</label>
                <select wire:model.live="perPage" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mt-3 sm:mt-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                <input type="date" wire:model.live="dateFrom" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                <input type="date" wire:model.live="dateTo" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm">
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" wire:click="sortBy('id')">
                            ID
                            @if($sortField == 'id') <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ml-1"></i> @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" wire:click="sortBy('first_name')">
                            Customer
                            @if($sortField == 'first_name') <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ml-1"></i> @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Problems</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" wire:click="sortBy('total_price')">
                            Total
                            @if($sortField == 'total_price') <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ml-1"></i> @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" wire:click="sortBy('status')">
                            Status
                            @if($sortField == 'status') <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ml-1"></i> @endif
                        </th>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $request->id }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $request->first_name }} {{ $request->last_name }}</div>
                            <div class="text-xs text-gray-500">{{ $request->email }}</div>
                            <div class="text-xs text-gray-500">{{ $request->mobile }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $request->category->name ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $request->brand->name ?? '-' }} - {{ $request->model->name ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600 max-w-xs truncate" title="{{ $request->problems_list }}">
                                {{ $request->problems_list }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-green-600">${{ number_format($request->total_price, 2) }}</div>
                            <div class="text-xs text-gray-500">Tax: ${{ number_format($request->tax, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $this->getStatusBadgeClass($request->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $request->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button wire:click="viewRequest({{ $request->id }})" class="text-blue-600 hover:text-blue-800 p-1" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button wire:click="openStatusModal({{ $request->id }}, '{{ $request->status }}', '{{ $request->admin_notes }}')" class="text-green-600 hover:text-green-800 p-1" title="Update Status">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="deleteRequest({{ $request->id }})" wire:confirm="Are you sure you want to delete this request?" class="text-red-600 hover:text-red-800 p-1" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">No repair requests found</p>
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
                            <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $this->getStatusBadgeClass($request->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </div>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $request->first_name }} {{ $request->last_name }}</p>
                        <div class="mt-1 space-y-1">
                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                <i class="fas fa-envelope w-4 text-gray-400"></i>
                                {{ $request->email }}
                            </p>
                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                <i class="fas fa-phone w-4 text-gray-400"></i>
                                {{ $request->mobile }}
                            </p>
                            <p class="text-xs text-gray-600">
                                <span class="font-medium">Device:</span> {{ $request->category->name ?? '-' }} - {{ $request->brand->name ?? '-' }}
                            </p>
                            <p class="text-xs text-gray-600 truncate">
                                <span class="font-medium">Problems:</span> {{ $request->problems_list }}
                            </p>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-green-600">${{ number_format($request->total_price, 2) }}</span>
                                <span class="text-xs text-gray-400">{{ $request->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-center space-y-1 ml-2 flex-shrink-0">
                        <button wire:click="viewRequest({{ $request->id }})" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button wire:click="openStatusModal({{ $request->id }}, '{{ $request->status }}', '{{ $request->admin_notes }}')" class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition" title="Update Status">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button wire:click="deleteRequest({{ $request->id }})" wire:confirm="Are you sure you want to delete this request?" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-4 py-12 text-center">
                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">No repair requests found</p>
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

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full w-full">
                <div class="bg-gray-50 px-4 sm:px-6 py-4 border-b border-gray-200 sticky top-0 bg-white z-10">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Repair Request #{{ $viewingRequest->id }}</h3>
                        <button wire:click="$set('showViewModal', false)" class="text-gray-400 hover:text-gray-500 p-1">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="px-4 sm:px-6 py-4 max-h-[70vh] overflow-y-auto space-y-4 sm:space-y-6">
                    <!-- Customer Information -->
                    <div>
                        <h4 class="text-sm sm:text-md font-semibold text-gray-900 mb-3 border-b pb-2">Customer Information</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div><span class="text-xs sm:text-sm text-gray-500">Name:</span> <span class="text-sm font-medium">{{ $viewingRequest->first_name }} {{ $viewingRequest->last_name }}</span></div>
                            <div><span class="text-xs sm:text-sm text-gray-500">Email:</span> <span class="text-sm break-all">{{ $viewingRequest->email }}</span></div>
                            <div><span class="text-xs sm:text-sm text-gray-500">Mobile:</span> <span class="text-sm">{{ $viewingRequest->mobile }}</span></div>
                            <div><span class="text-xs sm:text-sm text-gray-500">IMEI:</span> <span class="text-sm">{{ $viewingRequest->imei ?? 'N/A' }}</span></div>
                        </div>
                    </div>

                    <!-- Device Information -->
                    <div>
                        <h4 class="text-sm sm:text-md font-semibold text-gray-900 mb-3 border-b pb-2">Device Information</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div><span class="text-xs sm:text-sm text-gray-500">Category:</span> <span class="text-sm">{{ $viewingRequest->category->name ?? '-' }}</span></div>
                            <div><span class="text-xs sm:text-sm text-gray-500">Brand:</span> <span class="text-sm">{{ $viewingRequest->brand->name ?? '-' }}</span></div>
                            <div><span class="text-xs sm:text-sm text-gray-500">Model:</span> <span class="text-sm">{{ $viewingRequest->model->name ?? '-' }}</span></div>
                        </div>
                    </div>

                    <!-- Problems -->
                    <div>
                        <h4 class="text-sm sm:text-md font-semibold text-gray-900 mb-3 border-b pb-2">Problems</h4>
                        <p class="text-sm text-gray-600">{{ $viewingRequest->problems_list }}</p>
                    </div>

                    <!-- Pricing -->
                    <div>
                        <h4 class="text-sm sm:text-md font-semibold text-gray-900 mb-3 border-b pb-2">Pricing</h4>
                        <div class="space-y-1 max-w-xs">
                            <div class="flex justify-between"><span class="text-sm text-gray-600">Subtotal:</span> <span class="text-sm">${{ number_format($viewingRequest->subtotal, 2) }}</span></div>
                            <div class="flex justify-between"><span class="text-sm text-gray-600">Tax (10%):</span> <span class="text-sm">${{ number_format($viewingRequest->tax, 2) }}</span></div>
                            <div class="flex justify-between pt-2 border-t"><span class="text-sm font-bold">Total:</span> <span class="text-sm font-bold text-green-600">${{ number_format($viewingRequest->total_price, 2) }}</span></div>
                        </div>
                    </div>

                    <!-- Service Details -->
                    <div>
                        <h4 class="text-sm sm:text-md font-semibold text-gray-900 mb-3 border-b pb-2">Service Details</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div><span class="text-xs sm:text-sm text-gray-500">Service Type:</span> <span class="text-sm capitalize">{{ str_replace('_', ' ', $viewingRequest->service_type) }}</span></div>
                            <div><span class="text-xs sm:text-sm text-gray-500">Preferred Date:</span> <span class="text-sm">{{ $viewingRequest->preferred_date ? $viewingRequest->preferred_date->format('M d, Y') : 'N/A' }}</span></div>
                            <div><span class="text-xs sm:text-sm text-gray-500">Preferred Time:</span> <span class="text-sm">{{ $viewingRequest->preferred_time ? date('g:i A', strtotime($viewingRequest->preferred_time)) : 'N/A' }}</span></div>
                            <div><span class="text-xs sm:text-sm text-gray-500">Status:</span> <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $this->getStatusBadgeClass($viewingRequest->status) }}">{{ ucfirst(str_replace('_', ' ', $viewingRequest->status)) }}</span></div>
                        </div>
                    </div>

                    <!-- Address -->
                    @if($viewingRequest->address)
                    <div>
                        <h4 class="text-sm sm:text-md font-semibold text-gray-900 mb-3 border-b pb-2">Address</h4>
                        <p class="text-sm">{{ $viewingRequest->address }}</p>
                        <p class="text-sm">{{ $viewingRequest->city }}, {{ $viewingRequest->state }} {{ $viewingRequest->zip_code }}</p>
                        <p class="text-sm">{{ $viewingRequest->country }}</p>
                    </div>
                    @endif

                    <!-- Message -->
                    @if($viewingRequest->message)
                    <div>
                        <h4 class="text-sm sm:text-md font-semibold text-gray-900 mb-3 border-b pb-2">Customer Message</h4>
                        <p class="text-sm text-gray-600 break-words">{{ $viewingRequest->message }}</p>
                    </div>
                    @endif

                    <!-- Admin Notes -->
                    @if($viewingRequest->admin_notes)
                    <div>
                        <h4 class="text-sm sm:text-md font-semibold text-gray-900 mb-3 border-b pb-2">Admin Notes</h4>
                        <p class="text-sm text-gray-600 break-words">{{ $viewingRequest->admin_notes }}</p>
                    </div>
                    @endif
                </div>

                <div class="bg-gray-50 px-4 sm:px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button wire:click="$set('showViewModal', false)" class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-gray-500 rounded-md hover:bg-gray-600">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Update Status Modal -->
    @if($showStatusModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="$set('showStatusModal', false)"></div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full w-full mx-4">
                <div class="bg-gray-50 px-4 sm:px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Update Request Status</h3>
                </div>

                <div class="px-4 sm:px-6 py-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select wire:model="selectedStatus" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                        <textarea wire:model="adminNotes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#5C9F01] focus:border-[#5C9F01] text-sm resize-y" placeholder="Add notes about this request..."></textarea>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 sm:px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-2 sm:space-x-3">
                    <button wire:click="$set('showStatusModal', false)" class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 order-2 sm:order-1">Cancel</button>
                    <button wire:click="updateStatus" class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-[#5C9F01] rounded-md hover:bg-[#4a7e01] order-1 sm:order-2">Update Status</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Flash Messages -->
    @if(session()->has('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="fixed bottom-4 right-4 left-4 sm:left-auto z-50">
        <div class="bg-green-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
            <i class="fas fa-check-circle"></i>
            <span class="text-sm sm:text-base">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session()->has('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" class="fixed bottom-4 right-4 left-4 sm:left-auto z-50">
        <div class="bg-red-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
            <i class="fas fa-exclamation-circle"></i>
            <span class="text-sm sm:text-base">{{ session('error') }}</span>
        </div>
    </div>
    @endif
</div>
