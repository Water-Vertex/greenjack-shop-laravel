<div>
    <div class="container mx-auto px-4 max-w-7xl py-12 md:py-16">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-[#5B9F01]/10 rounded-full mb-4">
                        <i class="fas fa-key text-[#5B9F01] text-2xl"></i>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Forgot Password</h1>
                    <p class="text-gray-500 mt-2">Enter your email to receive reset instructions</p>
                </div>

                <!-- Success Messages -->
                @if(session()->has('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session()->has('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Check if token exists in URL -->
                @php
                    $hasToken = request()->has('token') || isset($token);
                @endphp

                <!-- Error for invalid token -->
                @if($hasToken && !$hasValidToken)
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Invalid or expired reset token. Please request a new reset link.
                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ route('customer.forgot-password') }}" class="text-[#5B9F01] font-semibold hover:underline">
                            <i class="fas fa-arrow-left mr-2"></i> Request New Reset Link
                        </a>
                    </div>
                @endif

                <!-- Reset Form (when no token) -->
                @if(!$hasToken && !$showEmailSent)
                <form wire:submit.prevent="sendResetLink">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email"
                                   wire:model="email"
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('email') border-red-500 @enderror"
                                   placeholder="Enter your email address"
                                   autocomplete="email">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="w-full bg-[#5B9F01] text-white py-3 rounded-lg font-semibold hover:bg-[#4a7f01] transition shadow-md">
                        <span wire:loading.remove>
                            <i class="fas fa-paper-plane mr-2"></i> Send Reset Link
                        </span>
                        <span wire:loading>
                            <svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Sending...
                        </span>
                    </button>

                    <!-- Back to Login -->
                    <div class="mt-6 text-center border-t border-gray-100 pt-6">
                        <p class="text-sm text-gray-600">
                            Remember your password?
                            <a href="{{ route('customer.login') }}" class="text-[#5B9F01] font-semibold hover:underline">
                                Back to Login
                            </a>
                        </p>
                    </div>
                </form>
                @endif

                <!-- Email Sent Confirmation -->
                @if($showEmailSent && !$hasToken)
                <div class="text-center py-8">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-4xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Check Your Email</h3>
                    <p class="text-gray-600 mb-4">
                        We've sent a password reset link to your email address.
                        Please check your inbox and follow the instructions.
                    </p>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-left">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            Didn't receive the email? Check your spam folder or
                            <button wire:click="$set('showEmailSent', false)" class="text-[#5B9F01] font-semibold hover:underline" type="button">
                                try again
                            </button>
                        </p>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('customer.login') }}" class="text-[#5B9F01] font-semibold hover:underline">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Login
                        </a>
                    </div>
                </div>
                @endif

                <!-- Reset Password Form (when token is present and valid) -->
                @if($hasToken && $hasValidToken)
                <div>
                    <div class="text-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Set New Password</h2>
                        <p class="text-sm text-gray-500 mt-1">Please enter your new password</p>
                    </div>

                    <form wire:submit.prevent="resetPassword">
                        <input type="hidden" wire:model="resetToken">
                        <input type="hidden" wire:model="resetEmail">

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                New Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="{{ $showNewPassword ? 'text' : 'password' }}"
                                       wire:model="newPassword"
                                       class="w-full pl-10 pr-12 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('newPassword') border-red-500 @enderror"
                                       placeholder="Enter new password"
                                       autocomplete="new-password">
                                <button type="button"
                                        wire:click="toggleNewPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i class="fas {{ $showNewPassword ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                </button>
                            </div>
                            @error('newPassword')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-check-circle text-gray-400"></i>
                                </div>
                                <input type="{{ $showConfirmPassword ? 'text' : 'password' }}"
                                       wire:model="confirmPassword"
                                       class="w-full pl-10 pr-12 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('confirmPassword') border-red-500 @enderror"
                                       placeholder="Confirm new password"
                                       autocomplete="new-password">
                                <button type="button"
                                        wire:click="toggleConfirmPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i class="fas {{ $showConfirmPassword ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                </button>
                            </div>
                            @error('confirmPassword')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                                wire:loading.attr="disabled"
                                class="w-full bg-[#5B9F01] text-white py-3 rounded-lg font-semibold hover:bg-[#4a7f01] transition shadow-md">
                            <span wire:loading.remove>
                                <i class="fas fa-save mr-2"></i> Reset Password
                            </span>
                            <span wire:loading>
                                <svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Resetting...
                            </span>
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>