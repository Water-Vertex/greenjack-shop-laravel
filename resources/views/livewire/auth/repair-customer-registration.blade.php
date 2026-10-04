<div>
    <div class="container mx-auto px-4 max-w-7xl py-8">
       <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Left Column - Registration Form (Centered) -->
            <div class="lg:col-span-7 lg:col-start-3 lg:col-end-10">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-[#5B9F01]/10 rounded-full mb-4">
                            <i class="fas fa-user-plus text-[#5B9F01] text-2xl"></i>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Create Account</h1>
                        <p class="text-gray-500 mt-2">Join us and enjoy exclusive benefits</p>
                    </div>

                    <!-- Progress Steps -->
                    <div class="flex items-center justify-center mb-8">
                        <div class="flex items-center w-full max-w-md">
                            @for($i = 1; $i <= 3; $i++)
                                <div class="flex-1 flex items-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-sm
                                            {{ $step >= $i ? 'bg-[#5B9F01] text-white' : 'bg-gray-200 text-gray-500' }}">
                                            {{ $i }}
                                        </div>
                                        <span class="text-xs mt-1 {{ $step >= $i ? 'text-[#5B9F01] font-medium' : 'text-gray-400' }}">
                                            {{ $i == 1 ? 'Personal' : ($i == 2 ? 'Address' : 'Account') }}
                                        </span>
                                    </div>
                                    @if($i < 3)
                                        <div class="flex-1 h-0.5 mx-2 {{ $step > $i ? 'bg-[#5B9F01]' : 'bg-gray-200' }}"></div>
                                    @endif
                                </div>
                            @endfor
                        </div>
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

                    <form wire:submit.prevent="register">
                        <!-- Step 1: Personal Information -->
                        @if($step == 1)
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            First Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                            wire:model="firstname"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('firstname') border-red-500 @enderror"
                                            placeholder="Enter first name">
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
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('lastname') border-red-500 @enderror"
                                            placeholder="Enter last name">
                                        @error('lastname')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email"
                                        wire:model="email"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('email') border-red-500 @enderror"
                                        placeholder="Enter email address">
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Phone Number <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel"
                                        wire:model="phone"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('phone') border-red-500 @enderror"
                                        placeholder="Enter phone number">
                                    @error('phone')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex justify-end">
                                    <button type="button"
                                            wire:click="nextStep"
                                            class="bg-[#5B9F01] text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-[#4a7f01] transition">
                                        Next Step <i class="fas fa-arrow-right ml-2"></i>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- Step 2: Address Information -->
                        @if($step == 2)
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                        wire:model="address"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('address') border-red-500 @enderror"
                                        placeholder="Enter street address">
                                    @error('address')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Country <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                            wire:model="country"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('country') border-red-500 @enderror"
                                            placeholder="Enter country">
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
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('state') border-red-500 @enderror"
                                            placeholder="Enter state">
                                        @error('state')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            City <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                            wire:model="city"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('city') border-red-500 @enderror"
                                            placeholder="Enter city">
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
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('postalcode') border-red-500 @enderror"
                                            placeholder="Enter postal code">
                                        @error('postalcode')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex justify-between">
                                    <button type="button"
                                            wire:click="previousStep"
                                            class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-semibold hover:bg-gray-300 transition">
                                        <i class="fas fa-arrow-left mr-2"></i> Back
                                    </button>
                                    <button type="button"
                                            wire:click="nextStep"
                                            class="bg-[#5B9F01] text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-[#4a7f01] transition">
                                        Next Step <i class="fas fa-arrow-right ml-2"></i>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- Step 3: Account Setup -->
                        @if($step == 3)
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password"
                                        wire:model="password"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('password') border-red-500 @enderror"
                                        placeholder="Create password (min 8 characters)">
                                    @error('password')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <div class="mt-2 flex items-center gap-2 text-xs text-gray-500">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Password must be at least 8 characters</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Confirm Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password"
                                        wire:model="password_confirmation"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5B9F01] focus:border-transparent @error('password_confirmation') border-red-500 @enderror"
                                        placeholder="Confirm your password">
                                    @error('password_confirmation')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-start gap-3 pt-2">
                                    <input type="checkbox"
                                        wire:model="terms"
                                        id="terms"
                                        class="mt-1 w-4 h-4 text-[#5B9F01] border-gray-300 rounded focus:ring-[#5B9F01]">
                                    <label for="terms" class="text-sm text-gray-600">
                                        I agree to the
                                         @php
                                            $terms_and_condition = \App\Models\Policy::where('is_repairing_policy', 1)->where('slug','terms-and-condition')->first();

                                            $privacy_policy = \App\Models\Policy::where('is_repairing_policy', 1)->where('slug','privacy-policy')->first();
                                        @endphp

                                        <a href="{{route('policy',$terms_and_condition->slug)}}" class="text-[#5B9F01] hover:underline">Terms of Service</a>
                                        and
                                        <a href="{{route('policy',$privacy_policy->slug)}}" class="text-[#5B9F01] hover:underline">Privacy Policy</a>
                                        <span class="text-red-500">*</span>
                                    </label>
                                </div>
                                @error('terms')
                                    <p class="text-red-500 text-xs">{{ $message }}</p>
                                @enderror

                                <div class="flex justify-between">
                                    <button type="button"
                                            wire:click="previousStep"
                                            class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-semibold hover:bg-gray-300 transition">
                                        <i class="fas fa-arrow-left mr-2"></i> Back
                                    </button>
                                    <button type="submit"
                                            wire:loading.attr="disabled"
                                            class="bg-[#FDCC02] text-gray-900 px-8 py-2.5 rounded-lg font-semibold hover:bg-[#FFE44D] transition shadow-md">
                                        <span wire:loading.remove>
                                            <i class="fas fa-user-plus mr-2"></i> Create Account
                                        </span>
                                        <span wire:loading>
                                            <svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Creating...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </form>

                    <!-- Login Link -->
                    <div class="mt-6 text-center border-t border-gray-100 pt-6">
                        <p class="text-sm text-gray-600">
                            Already have an account?
                            <a href="{{route('repair.customer.login')}}" wire:navigate class="text-[#5B9F01] font-semibold hover:underline">Login here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
