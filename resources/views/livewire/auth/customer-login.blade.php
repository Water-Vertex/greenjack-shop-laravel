<div>
    <div class="container mx-auto px-4 max-w-7xl py-12 md:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column - Login Form -->
            <div class="lg:col-span-7 lg:col-start-1">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-[#5B9F01]/10 rounded-full mb-4">
                            <i class="fas fa-sign-in-alt text-[#5B9F01] text-2xl"></i>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Welcome Back</h1>
                        <p class="text-gray-500 mt-2">Sign in to your account to continue</p>
                    </div>

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

                    <form wire:submit.prevent="login">
                        <!-- Email -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email"
                                       wire:model="email"
                                       wire:keydown.enter="login"
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('email') border-red-500 @enderror"
                                       placeholder="Enter your email address"
                                       autocomplete="email">
                            </div>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <a href="{{ route('customer.forgot-password') }}" wire:navigate class="text-xs text-[#5B9F01] hover:underline font-medium">
                                    Forgot Password?
                                </a>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="{{ $showPassword ? 'text' : 'password' }}"
                                       wire:model="password"
                                       wire:keydown.enter="login"
                                       class="w-full pl-10 pr-12 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('password') border-red-500 @enderror"
                                       placeholder="Enter your password"
                                       autocomplete="current-password">
                                <button type="button"
                                        wire:click="togglePassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i class="fas {{ $showPassword ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <input type="checkbox"
                                       wire:model="remember"
                                       id="remember"
                                       class="w-4 h-4 text-[#5B9F01] border-gray-300 rounded focus:ring-[#5B9F01]">
                                <label for="remember" class="ml-2 text-sm text-gray-600">
                                    Remember me
                                </label>
                            </div>
                        </div>

                        <!-- Login Button -->
                        <button type="submit"
                                wire:loading.attr="disabled"
                                class="w-full bg-[#5B9F01] text-white py-3 rounded-lg font-semibold hover:bg-[#4a7f01] transition shadow-md">
                            <span wire:loading.remove>
                                <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                            </span>
                            <span wire:loading>
                                <svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Signing in...
                            </span>
                        </button>

                        
                    </form>

                    <!-- Register Link -->
                    <div class="mt-6 text-center border-t border-gray-100 pt-6">
                        <p class="text-sm text-gray-600">
                            Don't have an account?
                            <a href="{{ route('customer.register') }}" class="text-[#5B9F01] font-semibold hover:underline">
                                Create one now
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Column - Benefits / Info -->
            <div class="lg:col-span-5">
                <div class="bg-gradient-to-br from-[#01285F] via-[#00349B] to-[#328429] rounded-2xl p-6 md:p-8 text-white sticky top-8">
                    <div class="text-center mb-6">
                        <i class="fas fa-store text-4xl text-[#FDCC02]"></i>
                        <h2 class="text-xl font-bold mt-3">Welcome Back!</h2>
                        <p class="text-white/80 text-sm mt-1">Sign in to access your account</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start gap-4 bg-white/10 rounded-xl p-4">
                            <div class="w-10 h-10 bg-[#FDCC02]/20 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-box text-[#FDCC02]"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold">View Your Orders</h3>
                                <p class="text-sm text-white/80">Track and manage all your orders in one place</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 bg-white/10 rounded-xl p-4">
                            <div class="w-10 h-10 bg-[#FDCC02]/20 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-heart text-[#FDCC02]"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold">Wishlist</h3>
                                <p class="text-sm text-white/80">Save your favorite items for later</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 bg-white/10 rounded-xl p-4">
                            <div class="w-10 h-10 bg-[#FDCC02]/20 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-tags text-[#FDCC02]"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold">Exclusive Deals</h3>
                                <p class="text-sm text-white/80">Get access to member-only discounts and offers</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 bg-white/10 rounded-xl p-4">
                            <div class="w-10 h-10 bg-[#FDCC02]/20 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-headset text-[#FDCC02]"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold">Quick Support</h3>
                                <p class="text-sm text-white/80">Get priority customer support when you need it</p>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Badges -->
                    <div class="mt-6 p-4 bg-white/10 rounded-xl">
                        <div class="flex items-center justify-center gap-4 text-xs text-white/80">
                            <span><i class="fas fa-lock mr-1 text-[#FDCC02]"></i> Secure Login</span>
                            <span>|</span>
                            <span><i class="fas fa-shield-alt mr-1 text-[#FDCC02]"></i> Privacy Protected</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
