@section('meta')
@php $metas = \App\Models\PageMeta::find(1); @endphp
@if($metas)
    <title>{{ $metas->contact_title }}</title>
    <meta name="title" content="{{ $metas->contact_meta_title }}"/>
    <meta name="description" content="{{ $metas->contact_meta_description }}"/>
    <meta name="keywords" content="{{ $metas->contact_meta_keywords }}"/>
    <meta name="tags" content="{{ $metas->contact_meta_tags }}"/>
    @if($metas->contact_page_schema)
    <script type="application/ld+json">
        {!! is_array($metas->contact_page_schema) ? json_encode($metas->contact_page_schema) : $metas->contact_page_schema !!}
    </script>
    @endif
@endif
@endsection

<div>
    @php
    $settings = \App\Models\Setting::find(1);
    @endphp
    




    <!-- Breadcrumb Section -->
<div class="breadcumb-wrapper relative bg-gray-100 py-16 md:py-20 overflow-hidden">
    <!-- Background Decoration -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%235B9F01" fill-opacity="0.05"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>

    <div class="container mx-auto px-4 max-w-7xl relative z-10">
        <div class="text-center">
            <!-- Page Title -->
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800 mb-3">
                Contact
            </h1>

            <!-- Breadcrumb Navigation -->
            <ul class="flex items-center justify-center gap-2 text-sm text-gray-500">
                <li>
                    <a href="https://greenjackshop.com" class="hover:text-[#5B9F01] transition duration-300">
                        Home
                    </a>
                </li>
                <li>
                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                </li>
                <li class="text-[#5B9F01] font-medium">
                    Contact
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- Contact Info Section -->
<section class="py-10 bg-gray-50">
    <div class="container mx-auto px-4 max-w-7xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-0">

            <!-- Card 1 - Office Location -->
            <div class="bg-white p-8 text-center md:rounded-l-2xl shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="contact-feature_icon mb-4">
                    <div class="w-16 h-16 bg-[#5B9F01]/10 rounded-full flex items-center justify-center mx-auto">
                        <img decoding="async" src="http://technician.watervertex.com/wp-content/uploads/2026/04/map-marker-1.png" alt="map marker" class="w-8 h-8 object-contain">
                    </div>
                </div>
                <span class="text-[#5B9F01] text-sm font-semibold uppercase tracking-wider block mb-2">OUR OFFICE LOCATION</span>
                <h3 class="text-xl font-bold text-gray-800">@if($settings){{$settings->address}}@endif</h3>
                <p class="text-gray-500 text-sm mt-2"></p>
            </div>

            <!-- Card 2 - Phone (Highlighted with Green Background) -->
            <div class="bg-[#5B9F01] p-8 text-center shadow-md hover:shadow-lg transition-shadow duration-300 relative overflow-hidden">
                <!-- Decorative pattern -->
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>

                <div class="relative z-10">
                    <div class="contact-feature_icon mb-4">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto">
                            <img decoding="async" src="http://technician.watervertex.com/wp-content/uploads/2026/04/phone-1.png" alt="phone" class="w-8 h-8 object-contain brightness-0 invert">
                        </div>
                    </div>
                    <span class="text-white/80 text-sm font-semibold uppercase tracking-wider block mb-2">Have A Question?</span>
                    <h3 class="text-xl md:text-2xl font-bold text-white">
                        <a href="tel:+6143325066" class="hover:underline">@if($settings){{$settings->phone}}@endif</a>
                    </h3>
                    <!--<p class="text-white/70 text-sm mt-2">Mon-Sat: 9am - 7pm</p>-->
                </div>
            </div>

            <!-- Card 3 - Email -->
            <div class="bg-white p-8 text-center md:rounded-r-2xl shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="contact-feature_icon mb-4">
                    <div class="w-16 h-16 bg-[#5B9F01]/10 rounded-full flex items-center justify-center mx-auto">
                        <img decoding="async" src="http://technician.watervertex.com/wp-content/uploads/2026/04/envelope-1.png" alt="envelope" class="w-8 h-8 object-contain">
                    </div>
                </div>
                <span class="text-[#5B9F01] text-sm font-semibold uppercase tracking-wider block mb-2">Email Us On</span>
                <h3 class="text-base md:text-lg font-bold text-gray-800 break-all">
                    <a href="mailto:sales.thegreenjack@gmail.com" class="hover:text-[#5B9F01] transition">@if($settings){{$settings->email}}@endif</a>
                </h3>
                <p class="text-gray-500 text-sm mt-2">24/7 Online Support</p>
            </div>
        </div>
    </div>
</section>
<!-- Contact Form Section -->
<section class="py-10 bg-gray-50 relative overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-10" style="background-image: url('http://technician.watervertex.com/wp-content/uploads/2026/04/contact_bg_2-1.png');"></div>

    <div class="container mx-auto px-4 max-w-7xl relative z-10">
        <div class="flex flex-col lg:flex-row gap-8 items-stretch">
            <!-- Map Section - Left Side -->
            <div class="lg:w-1/2 rounded-2xl overflow-hidden shadow-xl min-h-[400px] lg:min-h-[600px]">
                <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3052.942570656488!2d-82.95135379999999!3d40.0766931!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88388b20602e80f3%3A0x27e52ed2482cd98d!2s5244%20Cleveland%20Ave%2C%20Columbus%2C%20OH%2043231%2C%20USA!5e0!3m2!1sen!2s!4v1789913679510!5m2!1sen!2s"
            width="100%"
            height="100%"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            class="w-full h-full"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
            </div>

            <!-- Form Section - Right Side -->
            <div class="lg:w-1/2">
                <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 lg:p-10 h-full">
                    <!-- Form Header -->
                    <h4 class="text-2xl md:text-3xl font-bold text-gray-800 text-center mb-6">
                        Get in <span class="text-[#5B9F01]">contact</span> with us
                    </h4>

                    <!-- Success Message -->
                    @if($successMessage)
                        <div class="mt-4 text-center bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                            ✅ {{ $successMessage }}
                        </div>
                    @endif

                    <!-- Contact Form -->
                    <form wire:submit.prevent="save" class="contact-form">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Your Name -->
                            <div class="form-group md:col-span-2">
                                <input type="text" wire:model="name"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-[#5B9F01] focus:ring-2 focus:ring-[#5B9F01]/20 transition-all duration-300"
                                    placeholder="Your Name">
                                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="form-group">
                                <input type="email" wire:model="email"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-[#5B9F01] focus:ring-2 focus:ring-[#5B9F01]/20 transition-all duration-300"
                                    placeholder="Email Address">
                                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Phone Number -->
                            <div class="form-group">
                                <input type="tel" wire:model="phone_number"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-[#5B9F01] focus:ring-2 focus:ring-[#5B9F01]/20 transition-all duration-300"
                                    placeholder="Phone Number">
                                @error('phone_number') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Message -->
                            <div class="form-group md:col-span-2">
                                <textarea wire:model="message" rows="5"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-[#5B9F01] focus:ring-2 focus:ring-[#5B9F01]/20 transition-all duration-300 resize-none"
                                    placeholder="Your Message"></textarea>
                                @error('message') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group md:col-span-2 text-center">
                                <button type="submit"
                                    class="bg-[#5B9F01] hover:bg-[#4a7f01] text-white font-semibold px-8 py-3 rounded-full transition duration-300 shadow-md hover:shadow-lg w-full md:w-auto min-w-[200px]">
                                    <span wire:loading.remove wire:target="save">Submit Now</span>
                                    <span wire:loading wire:target="save">Sending...</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
