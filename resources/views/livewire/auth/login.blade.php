<div>
    <form wire:submit="login" class="space-y-6">
        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
            <input 
                wire:model="email"
                type="email" 
                id="email"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                placeholder="Enter your email"
            >
            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input 
                wire:model="password"
                type="password" 
                id="password"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#5C9F01] focus:border-[#5C9F01]"
                placeholder="Enter your password"
            >
            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input 
                    wire:model="remember"
                    type="checkbox" 
                    id="remember"
                    class="h-4 w-4 text-[#5C9F01] focus:ring-[#5C9F01] border-gray-300 rounded"
                >
                <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
            </div>

            @if (Route::has('password.request'))
            <div class="text-sm">
                <a href="{{ route('password.request') }}" class="font-medium text-[#5C9F01] hover:text-[#5C9F01]">
                    Forgot your password?
                </a>
            </div>
            @endif
        </div>

        <!-- Submit Button -->
        <div>
            <button 
                type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#5C9F01] hover:bg-[#5C9F01] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#5C9F01]"
            >
                <span wire:loading.remove>Sign in</span>
                <span wire:loading>Signing in...</span>
            </button>
        </div>
    </form>

    
</div>