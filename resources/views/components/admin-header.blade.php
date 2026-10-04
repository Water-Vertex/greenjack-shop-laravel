<!-- Modern Header - Tailwind CSS + Material UI -->
<header class="bg-white/80 backdrop-blur-md shadow-lg sticky top-0 z-20 border-b border-gray-100">
    <div class="px-3 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
        <!-- Left Section -->
        <div class="flex items-center gap-2 sm:gap-4">
            <!-- Mobile Menu Toggle -->
            <button id="mobileMenuBtn" class="lg:hidden p-2 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-all duration-200">
                <span class="material-icons text-2xl">menu</span>
            </button>

            <!-- Mobile Logo (visible on small screens) -->
            <div class="lg:hidden flex items-center">
                <img src="{{ asset('assets/images/logo/green-jack-white.png') }}"
                     alt="Logo"
                     class="h-8 w-auto">
            </div>

            <!-- Page Title & Greeting -->
            <div class="hidden sm:block">
                <h1 class="text-lg font-semibold text-gray-800">
                    @yield('page-title', 'Dashboard')
                </h1>
                <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                    <span class="material-icons text-sm text-gray-400">waving_hand</span>
                    Welcome back, {{ auth()->user() ? auth()->user()->name : 'Admin' }}
                </p>
            </div>
        </div>

        <!-- Right Section -->
        <div class="flex items-center gap-1 sm:gap-2">
            <!-- Search Bar (Desktop) -->
            <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-gray-50 border border-gray-200 focus-within:border-[#5C9F01] focus-within:ring-2 focus-within:ring-[#5C9F01]/20 transition-all">
                <span class="material-icons text-gray-400 text-xl">search</span>
                <input type="text" placeholder="Search..." class="bg-transparent border-none outline-none text-sm w-32 lg:w-48 text-gray-700 placeholder-gray-400">
            </div>

            <!-- Mobile Search Toggle -->
            <button id="mobileSearchToggle" class="md:hidden p-2 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-all duration-200">
                <span class="material-icons text-2xl">search</span>
            </button>

            <!-- User Profile Dropdown -->
            <div class="relative dropdown-container">
                <button class="dropdown-trigger-user flex items-center gap-2 p-1.5 pr-2 sm:pr-3 rounded-xl hover:bg-gray-100 transition-all duration-200 group">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-r from-[#5C9F01] to-[#4a7f01] flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                        <span class="material-icons text-white text-lg sm:text-2xl">person</span>
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-semibold text-gray-700">{{ auth()->user() ? auth()->user()->name : 'Admin User' }}</p>
                        <p class="text-xs text-gray-400">Administrator</p>
                    </div>
                    <span class="material-icons text-gray-400 text-xl hidden md:inline-block dropdown-user-icon transition-transform duration-200">expand_more</span>
                </button>

                <!-- User Dropdown Menu -->
                <div class="dropdown-user-content absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden hidden z-50">
                    <div class="p-3 border-b border-gray-100 bg-gradient-to-r from-[#5C9F01]/10 to-[#4a7f01]/10">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-[#5C9F01] to-[#4a7f01] flex items-center justify-center shadow-md">
                                <span class="material-icons text-white text-2xl">person</span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ auth()->user() ? auth()->user()->name : 'Admin User' }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user() ? auth()->user()->email : 'admin@example.com' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="py-2">
                        <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <span class="material-icons text-gray-500 text-xl">person_outline</span>
                            <span>My Profile</span>
                        </a>
                        <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <span class="material-icons text-gray-500 text-xl">settings</span>
                            <span>Account Settings</span>
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <span class="material-icons text-red-500 text-xl">logout</span>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Search Bar (Hidden by default) -->
    <div id="mobileSearch" class="md:hidden px-3 pb-3 hidden">
        <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-50 border border-gray-200 focus-within:border-[#5C9F01] focus-within:ring-2 focus-within:ring-[#5C9F01]/20 transition-all">
            <span class="material-icons text-gray-400 text-xl">search</span>
            <input type="text" placeholder="Search..." class="bg-transparent border-none outline-none text-sm w-full text-gray-700 placeholder-gray-400">
            <button id="mobileSearchClose" class="text-gray-400 hover:text-gray-600">
                <span class="material-icons text-xl">close</span>
            </button>
        </div>
    </div>
</header>
