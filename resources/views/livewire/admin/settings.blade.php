{{-- resources/views/livewire/admin/general-settings.blade.php --}}
<div>
    <!-- Header Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">General Settings</h1>
                <p class="text-gray-600 mt-1">Manage your website settings, social media, and SEO configuration</p>
            </div>
        </div>
    </div>

    <form wire:submit="save" class="space-y-6">
        <!-- Website Branding -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Website Branding</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Favicon -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                    <div class="flex items-center space-x-4">
                        @if($settings && $settings->favicon)
                            <div class="flex items-center space-x-2">
                                <img src="{{ asset('storage/'.$settings->favicon) }}" alt="Favicon" class="w-8 h-8">
                                <button type="button" wire:click="removeImage('favicon')" class="text-red-600 hover:text-red-800 text-sm">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endif
                        <input type="file" wire:model="favicon" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Recommended: 32x32px PNG/ICO</p>
                    @error('favicon') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Main Logo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Main Logo</label>
                    <div class="flex items-center space-x-4">
                        @if($settings && $settings->main_logo)
                            <div class="flex items-center space-x-2">
                                <img src="{{ asset('storage/'.$settings->main_logo) }}" alt="Main Logo" class="h-10">
                                <button type="button" wire:click="removeImage('main_logo')" class="text-red-600 hover:text-red-800 text-sm">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endif
                        <input type="file" wire:model="main_logo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Recommended: 200x60px PNG</p>
                    @error('main_logo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Footer Logo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Footer Logo</label>
                    <div class="flex items-center space-x-4">
                        @if($settings && $settings->footer_logo)
                            <div class="flex items-center space-x-2">
                                <img src="{{ asset('storage/'.$settings->footer_logo) }}" alt="Footer Logo" class="h-10">
                                <button type="button" wire:click="removeImage('footer_logo')" class="text-red-600 hover:text-red-800 text-sm">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endif
                        <input type="file" wire:model="footer_logo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Recommended: 200x60px PNG</p>
                    @error('footer_logo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Site Title -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Site Title</label>
                <input type="text" wire:model="site_title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Your Website Name">
                @error('site_title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" wire:model="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="contact@example.com">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" wire:model="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="+1 (555) 123-4567">
                    @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea wire:model="address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Your company address"></textarea>
                    @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">About Us</label>
                    <textarea wire:model="about" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Brief description about your company"></textarea>
                    @error('about') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Social Media Links -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Social Media Links</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Facebook</label>
                    <input type="url" wire:model="facebook" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="https://facebook.com/yourpage">
                    @error('facebook') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Twitter</label>
                    <input type="url" wire:model="twitter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="https://twitter.com/yourprofile">
                    @error('twitter') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">LinkedIn</label>
                    <input type="url" wire:model="linkedin" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="https://linkedin.com/company/yourcompany">
                    @error('linkedin') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Instagram</label>
                    <input type="url" wire:model="instagram" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="https://instagram.com/yourprofile">
                    @error('instagram') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pinterest</label>
                    <input type="url" wire:model="pintrest" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="https://pinterest.com/yourprofile">
                    @error('pintrest') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">YouTube</label>
                    <input type="url" wire:model="youtube" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="https://youtube.com/yourchannel">
                    @error('youtube') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">SEO Settings</h2>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                    <input type="text" wire:model="meta_title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Default meta title for your website">
                    @error('meta_title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                    <textarea wire:model="meta_desc" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Default meta description for your website"></textarea>
                    @error('meta_desc') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                    <textarea wire:model="meta_keywords" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="keyword1, keyword2, keyword3"></textarea>
                    @error('meta_keywords') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Tags</label>
                    <textarea wire:model="meta_tags" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Additional meta tags"></textarea>
                    @error('meta_tags') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Head Tags</label>
                    <textarea wire:model="head_tags" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-mono text-sm" placeholder="Additional tags for &lt;head&gt; section (e.g., Google Analytics)"></textarea>
                    @error('head_tags') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Body Tags</label>
                    <textarea wire:model="body_tags" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-mono text-sm" placeholder="Additional tags for &lt;body&gt; section"></textarea>
                    @error('body_tags') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" wire:loading.attr="disabled" class="px-6 py-3 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="save">
                    <i class="fas fa-save"></i>
                    <span>Save Settings</span>
                </span>
                <span wire:loading wire:target="save" class="flex items-center space-x-2">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                    <span>Saving...</span>
                </span>
            </button>
        </div>
    </form>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transition-all duration-300">
            <div class="flex items-center space-x-2">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
</div>