<div>
    <div class="container mx-auto px-4 max-w-7xl py-8">
        <!-- Success Message -->
        @if(session()->has('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Message -->
        @if(session()->has('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden sticky top-8">
                    <!-- Profile Header -->
                    <div class="bg-[#5B9F01] p-6 text-center">
                        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 border-2 border-white/30">
                            <i class="fas fa-user text-white text-3xl"></i>
                        </div>
                        <h3 class="text-white font-semibold text-lg">{{ $customer->firstname ?? 'Customer' }} {{ $customer->lastname ?? '' }}</h3>
                        <p class="text-white/80 text-sm">{{ $customer->email ?? '' }}</p>
                    </div>

                    <!-- Navigation -->
                    <nav class="p-4">
                        <button wire:click="$set('activeTab', 'dashboard')"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left
                                {{ $activeTab == 'dashboard' ? 'bg-[#5B9F01]/10 text-[#5B9F01]' : 'hover:bg-gray-50 text-gray-700' }}">
                            <i class="fas fa-chart-pie"></i>
                            <span>Dashboard</span>
                        </button>

                        <button wire:click="$set('activeTab', 'profile')"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left mt-1
                                {{ $activeTab == 'profile' ? 'bg-[#5B9F01]/10 text-[#5B9F01]' : 'hover:bg-gray-50 text-gray-700' }}">
                            <i class="fas fa-user-edit"></i>
                            <span>Profile Settings</span>
                        </button>

                        <button wire:click="$set('activeTab', 'repair')"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left mt-1
                                {{ $activeTab == 'repair' ? 'bg-[#5B9F01]/10 text-[#5B9F01]' : 'hover:bg-gray-50 text-gray-700' }}">
                            <i class="fas fa-tools"></i>
                            <span>Repair Requests</span>
                            <span class="ml-auto bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full">{{ $repairRequestCount }}</span>
                        </button>

                        <button wire:click="$set('activeTab', 'printing')"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left mt-1
                                {{ $activeTab == 'printing' ? 'bg-[#5B9F01]/10 text-[#5B9F01]' : 'hover:bg-gray-50 text-gray-700' }}">
                            <i class="fas fa-print"></i>
                            <span>Printing Requests</span>
                            <span class="ml-auto bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full">{{ $printingRequestCount }}</span>
                        </button>

                        <button wire:click="$set('activeTab', 'settings')"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left mt-1
                                {{ $activeTab == 'settings' ? 'bg-[#5B9F01]/10 text-[#5B9F01]' : 'hover:bg-gray-50 text-gray-700' }}">
                            <i class="fas fa-cog"></i>
                            <span>Account Settings</span>
                        </button>

                        <div class="border-t border-gray-200 mt-4 pt-4">
                            <button wire:click="logout"
                                    class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition text-left">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-9">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8">

                    <!-- Dashboard Tab -->
                    @if($activeTab == 'dashboard')
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Dashboard</h2>
                            <p class="text-gray-500 mb-6">Welcome back, {{ $customer->firstname ?? 'Customer' }}!</p>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-tools text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Repair Requests</p>
                                            <p class="text-2xl font-bold text-gray-800">{{ $repairRequestCount }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-print text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Printing Requests</p>
                                            <p class="text-2xl font-bold text-gray-800">{{ $printingRequestCount }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Member Since</p>
                                            <p class="text-sm font-semibold text-gray-800">{{ $customer->created_at ? $customer->created_at->format('M d, Y') : 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Repair Requests -->
                            @if($recentRepairRequests && $recentRepairRequests->count() > 0)
                            <div class="mt-8">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="font-semibold text-gray-800">Recent Repair Requests</h3>
                                    <button wire:click="$set('activeTab', 'repair')" class="text-sm text-[#5B9F01] hover:underline">
                                        View All →
                                    </button>
                                </div>
                                <div class="space-y-3">
                                    @foreach($recentRepairRequests as $request)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-100">
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">#{{ $request->id }}</p>
                                            <p class="text-xs text-gray-500">{{ $request->created_at->format('M d, Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ $request->category->name ?? 'N/A' }} - {{ $request->brand->name ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $this->getStatusBadgeClass($request->status) }}">
                                                {{ ucfirst(str_replace('_', ' ', $request->status ?? 'pending')) }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Quick Actions -->
                            <div class="mt-8 p-6 bg-gray-50 rounded-xl border border-gray-200">
                                <h3 class="font-semibold text-gray-800 mb-2">Quick Actions</h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <button wire:click="$set('activeTab', 'profile')"
                                            class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition text-center border border-gray-200">
                                        <i class="fas fa-user-edit text-[#5B9F01] text-xl"></i>
                                        <p class="text-xs text-gray-600 mt-1">Edit Profile</p>
                                    </button>
                                    <a href="{{ route('device-repair-page') }}"
                                       class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition text-center border border-gray-200 block">
                                        <i class="fas fa-tools text-[#5B9F01] text-xl"></i>
                                        <p class="text-xs text-gray-600 mt-1">New Repair</p>
                                    </a>
                                    <a href="{{ route('printing') }}"
                                       class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition text-center border border-gray-200 block">
                                        <i class="fas fa-print text-[#5B9F01] text-xl"></i>
                                        <p class="text-xs text-gray-600 mt-1">New Printing</p>
                                    </a>
                                    <button wire:click="$set('activeTab', 'settings')"
                                            class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition text-center border border-gray-200">
                                        <i class="fas fa-cog text-[#5B9F01] text-xl"></i>
                                        <p class="text-xs text-gray-600 mt-1">Settings</p>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Profile Tab -->
                    @if($activeTab == 'profile')
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-800">Profile Settings</h2>
                                    <p class="text-gray-500 text-sm">Update your personal information</p>
                                </div>
                                @if(!$editMode)
                                    <button wire:click="toggleEdit"
                                            class="bg-[#5B9F01] text-white px-4 py-2 rounded-lg hover:bg-[#4a7f01] transition">
                                        <i class="fas fa-edit mr-2"></i> Edit Profile
                                    </button>
                                @endif
                            </div>

                            <form wire:submit.prevent="updateProfile">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            First Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               wire:model="firstname"
                                               {{ !$editMode ? 'disabled' : '' }}
                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('firstname') border-red-500 @enderror
                                               {{ !$editMode ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                                        @error('firstname')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Last Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               wire:model="lastname"
                                               {{ !$editMode ? 'disabled' : '' }}
                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('lastname') border-red-500 @enderror
                                               {{ !$editMode ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                                        @error('lastname')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email"
                                           wire:model="email"
                                           {{ !$editMode ? 'disabled' : '' }}
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('email') border-red-500 @enderror
                                           {{ !$editMode ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Phone Number <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel"
                                           wire:model="phone"
                                           {{ !$editMode ? 'disabled' : '' }}
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('phone') border-red-500 @enderror
                                           {{ !$editMode ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                                    @error('phone')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           wire:model="address"
                                           {{ !$editMode ? 'disabled' : '' }}
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('address') border-red-500 @enderror
                                           {{ !$editMode ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                                    @error('address')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Country <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               wire:model="country"
                                               {{ !$editMode ? 'disabled' : '' }}
                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('country') border-red-500 @enderror
                                               {{ !$editMode ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                                        @error('country')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            State <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               wire:model="state"
                                               {{ !$editMode ? 'disabled' : '' }}
                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('state') border-red-500 @enderror
                                               {{ !$editMode ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                                        @error('state')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            City <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               wire:model="city"
                                               {{ !$editMode ? 'disabled' : '' }}
                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('city') border-red-500 @enderror
                                               {{ !$editMode ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                                        @error('city')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Postal Code <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               wire:model="postalcode"
                                               {{ !$editMode ? 'disabled' : '' }}
                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('postalcode') border-red-500 @enderror
                                               {{ !$editMode ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                                        @error('postalcode')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                @if($editMode)
                                    <div class="flex gap-3 mt-6">
                                        <button type="submit"
                                                wire:loading.attr="disabled"
                                                class="bg-[#5B9F01] text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-[#4a7f01] transition">
                                            <span wire:loading.remove>
                                                <i class="fas fa-save mr-2"></i> Save Changes
                                            </span>
                                            <span wire:loading>
                                                <svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Saving...
                                            </span>
                                        </button>
                                        <button type="button"
                                                wire:click="toggleEdit"
                                                class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-semibold hover:bg-gray-300 transition">
                                            Cancel
                                        </button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    @endif

                    <!-- Repair Requests Tab -->
                    @if($activeTab == 'repair')
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-800">Repair Requests</h2>
                                    <p class="text-gray-500 text-sm">View all your repair requests</p>
                                </div>
                                <a href="{{ route('device-repair-page') }}"
                                   class="bg-[#5B9F01] text-white px-4 py-2 rounded-lg hover:bg-[#4a7f01] transition">
                                    <i class="fas fa-plus mr-2"></i> New Repair
                                </a>
                            </div>

                            @if($repairRequests && $repairRequests->count() > 0)
                                <div class="space-y-4">
                                    @foreach($repairRequests as $request)
                                    <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition">
                                        <!-- Request Header -->
                                        <div class="bg-gray-50 px-6 py-3 flex flex-wrap items-center justify-between gap-3">
                                            <div class="flex items-center gap-4">
                                                <span class="text-sm font-semibold text-gray-800">Request #{{ $request->id }}</span>
                                                <span class="text-xs text-gray-500">{{ $request->created_at->format('M d, Y') }}</span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $this->getStatusBadgeClass($request->status) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $request->status ?? 'pending')) }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Request Body -->
                                        <div class="p-6">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div>
                                                    <p class="text-xs text-gray-500 mb-1">Device</p>
                                                    <p class="text-sm font-medium text-gray-700">{{ $request->category->name ?? 'N/A' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500 mb-1">Brand</p>
                                                    <p class="text-sm font-medium text-gray-700">{{ $request->brand->name ?? 'N/A' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500 mb-1">Model</p>
                                                    <p class="text-sm font-medium text-gray-700">{{ $request->model->name ?? 'N/A' }}</p>
                                                </div>
                                            </div>

                                            @if($request->description)
                                                <div class="mt-3 pt-3 border-t border-gray-100">
                                                    <p class="text-xs text-gray-500 mb-1">Issue Description</p>
                                                    <p class="text-sm text-gray-700">{{ $request->description }}</p>
                                                </div>
                                            @endif

                                            <!-- Request Actions -->
                                            <div class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap gap-3">
                                                @if($request->status == 'pending' || $request->status == 'confirmed')
                                                    <button class="text-sm text-red-600 hover:underline font-medium">
                                                        <i class="fas fa-times mr-1"></i> Cancel Request
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                @if($repairRequests->count() > 10)
                                    <div class="mt-6">
                                        {{ $repairRequests->links() }}
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-12">
                                    <i class="fas fa-tools text-gray-300 text-6xl mb-4"></i>
                                    <h3 class="text-xl font-semibold text-gray-700">No Repair Requests</h3>
                                    <p class="text-gray-500 mt-2">You haven't submitted any repair requests yet</p>
                                    <a href="{{ route('device-repair-page') }}" class="inline-block mt-4 bg-[#5B9F01] text-white px-6 py-2 rounded-lg hover:bg-[#4a7f01] transition">
                                        Submit a Repair Request
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Printing Requests Tab -->
                    @if($activeTab == 'printing')
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-800">Printing Requests</h2>
                                    <p class="text-gray-500 text-sm">View all your printing requests</p>
                                </div>
                                <a href="{{ route('printing') }}"
                                   class="bg-[#5B9F01] text-white px-4 py-2 rounded-lg hover:bg-[#4a7f01] transition">
                                    <i class="fas fa-plus mr-2"></i> New Printing
                                </a>
                            </div>

                            @if($printingRequests && $printingRequests->count() > 0)
                                <div class="space-y-4">
                                    @foreach($printingRequests as $request)
                                    <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition">
                                        <!-- Request Header -->
                                        <div class="bg-gray-50 px-6 py-3 flex flex-wrap items-center justify-between gap-3">
                                            <div class="flex items-center gap-4">
                                                <span class="text-sm font-semibold text-gray-800">Printing #{{ $request->id }}</span>
                                                <span class="text-xs text-gray-500">{{ $request->created_at->format('M d, Y') }}</span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $this->getPrintingStatusBadgeClass($request->status) }}">
                                                    {{ ucfirst($request->status ?? 'pending') }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Request Body -->
                                        <div class="p-6">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div>
                                                    <p class="text-xs text-gray-500 mb-1">Service</p>
                                                    <p class="text-sm font-medium text-gray-700">{{ $request->service ?? 'N/A' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500 mb-1">Quantity</p>
                                                    <p class="text-sm font-medium text-gray-700">{{ $request->quantity ?? 'N/A' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500 mb-1">Total Amount</p>
                                                    <p class="text-sm font-medium text-gray-700">${{ number_format($request->total_amount ?? 0, 2) }}</p>
                                                </div>
                                            </div>

                                            @if($request->message)
                                                <div class="mt-3 pt-3 border-t border-gray-100">
                                                    <p class="text-xs text-gray-500 mb-1">Message</p>
                                                    <p class="text-sm text-gray-700">{{ $request->message }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                @if($printingRequests->count() > 10)
                                    <div class="mt-6">
                                        {{ $printingRequests->links() }}
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-12">
                                    <i class="fas fa-print text-gray-300 text-6xl mb-4"></i>
                                    <h3 class="text-xl font-semibold text-gray-700">No Printing Requests</h3>
                                    <p class="text-gray-500 mt-2">You haven't submitted any printing requests yet</p>
                                    <a href="{{ route('printing') }}" class="inline-block mt-4 bg-[#5B9F01] text-white px-6 py-2 rounded-lg hover:bg-[#4a7f01] transition">
                                        Submit a Printing Request
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Settings Tab -->
                    @if($activeTab == 'settings')
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Account Settings</h2>
                            <p class="text-gray-500 mb-6">Manage your account security</p>

                            <!-- Change Password -->
                            <div class="border border-gray-200 rounded-xl p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="font-semibold text-gray-800">Change Password</h3>
                                        <p class="text-sm text-gray-500">Update your password to keep your account secure</p>
                                    </div>
                                    <button wire:click="togglePasswordForm"
                                            class="text-[#5B9F01] hover:underline text-sm font-medium">
                                        {{ $showPasswordForm ? 'Cancel' : 'Change Password' }}
                                    </button>
                                </div>

                                @if($showPasswordForm)
                                    <form wire:submit.prevent="updatePassword" class="space-y-4 mt-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Current Password <span class="text-red-500">*</span>
                                            </label>
                                            <input type="password"
                                                   wire:model="current_password"
                                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('current_password') border-red-500 @enderror"
                                                   placeholder="Enter current password">
                                            @error('current_password')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                New Password <span class="text-red-500">*</span>
                                            </label>
                                            <input type="password"
                                                   wire:model="new_password"
                                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('new_password') border-red-500 @enderror"
                                                   placeholder="Enter new password (min 8 characters)">
                                            @error('new_password')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Confirm New Password <span class="text-red-500">*</span>
                                            </label>
                                            <input type="password"
                                                   wire:model="new_password_confirmation"
                                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('new_password_confirmation') border-red-500 @enderror"
                                                   placeholder="Confirm new password">
                                            @error('new_password_confirmation')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <button type="submit"
                                                wire:loading.attr="disabled"
                                                class="bg-[#5B9F01] text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-[#4a7f01] transition">
                                            <span wire:loading.remove>
                                                <i class="fas fa-key mr-2"></i> Update Password
                                            </span>
                                            <span wire:loading>
                                                <svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Updating...
                                            </span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
