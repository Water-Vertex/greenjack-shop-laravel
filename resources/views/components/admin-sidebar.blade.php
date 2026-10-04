<!-- Main Sidebar Container -->
<aside id="sidebar" class="sidebar-desktop fixed left-0 top-0 h-full w-72 bg-gray-900 shadow-2xl z-30 transition-all duration-300 overflow-y-auto sidebar-scroll">

    <!-- Brand Logo -->
    <div class="p-4 lg:p-5 border-b border-white/10 flex items-center justify-between">
        <a href="{{ route('admin.index') }}" wire:navigate class="flex items-center justify-center group flex-1">
            <img src="{{ asset('assets/images/logo/green-jack-white.png') }}"
                 alt="Logo"
                 class="h-12 lg:h-14 w-auto object-contain transition-transform group-hover:scale-105">
        </a>

        <!-- Mobile Close Button -->
        <button id="closeSidebar" class="lg:hidden text-white/60 hover:text-white transition-colors p-1">
            <span class="material-icons text-2xl">close</span>
        </button>
    </div>

    <!-- Sidebar Navigation -->
    <div class="px-2 lg:px-3 py-3 lg:py-4 sidebar-nav">
        <nav class="space-y-0.5">
            <!-- Dashboard -->
            <a href="{{ route('admin.index') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-[#5C9F01]/40 transition-all duration-200 group {{ request()->routeIs('admin.index') ? 'bg-[#5C9F01]/40 text-white' : '' }}">
                <span class="material-icons text-2xl">dashboard</span>
                <span class="text-sm font-medium">Dashboard</span>
            </a>

            <!-- ============ E-COMMERCE SECTION ============ -->
            <div class="pt-3 pb-1">
                <span class="text-[10px] uppercase tracking-wider text-white/30 px-3 font-semibold">E-Commerce</span>
            </div>

            <!-- Orders -->
            <a href="{{ route('admin.orders') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-[#5C9F01]/40 transition-all duration-200 group {{ request()->routeIs('admin.orders') ? 'bg-[#5C9F01]/40 text-white' : '' }}">
                <span class="material-icons text-2xl">shopping_bag</span>
                <span class="text-sm font-medium">Orders</span>
            </a>

            <!-- Categories -->
            <a href="{{ route('admin.categories') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-[#5C9F01]/40 transition-all duration-200 group {{ request()->routeIs('admin.categories') ? 'bg-[#5C9F01]/40 text-white' : '' }}">
                <span class="material-icons text-2xl">category</span>
                <span class="text-sm font-medium">Categories</span>
            </a>

            <!-- Sub Categories -->
            <a href="{{ route('admin.sub-categories') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-[#5C9F01]/40 transition-all duration-200 group {{ request()->routeIs('admin.sub-categories') ? 'bg-[#5C9F01]/40 text-white' : '' }}">
                <span class="material-icons text-2xl">subdirectory_arrow_right</span>
                <span class="text-sm font-medium">Sub Categories</span>
            </a>

            <!-- Products -->
            <a href="{{ route('admin.products') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-[#5C9F01]/40 transition-all duration-200 group {{ request()->routeIs('admin.products') ? 'bg-[#5C9F01]/40 text-white' : '' }}">
                <span class="material-icons text-2xl">inventory_2</span>
                <span class="text-sm font-medium">Products</span>
            </a>

            <!-- Brands -->
            <a href="{{ route('admin.brands') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-[#5C9F01]/40 transition-all duration-200 group {{ request()->routeIs('admin.brands') ? 'bg-[#5C9F01]/40 text-white' : '' }}">
                <span class="material-icons text-2xl">label</span>
                <span class="text-sm font-medium">Brands</span>
            </a>

            <!-- Brand Models -->
            <a href="{{ route('admin.brand-models') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-[#5C9F01]/40 transition-all duration-200 group {{ request()->routeIs('admin.brand-models') ? 'bg-[#5C9F01]/40 text-white' : '' }}">
                <span class="material-icons text-2xl">devices</span>
                <span class="text-sm font-medium">Brand Models</span>
            </a>

            <!-- Series -->
            <a href="{{ route('admin.series') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-[#5C9F01]/40 transition-all duration-200 group {{ request()->routeIs('admin.series') ? 'bg-[#5C9F01]/40 text-white' : '' }}">
                <span class="material-icons text-2xl">view_carousel</span>
                <span class="text-sm font-medium">Series</span>
            </a>

            <!-- ============ REPAIR SECTION ============ -->
            <div class="pt-3 pb-1">
                <span class="text-[10px] uppercase tracking-wider text-white/30 px-3 font-semibold">Repair Services</span>
            </div>

            <!-- Problems -->
            <a href="{{ route('admin.problems') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-[#5C9F01]/40 transition-all duration-200 group {{ request()->routeIs('admin.problems') ? 'bg-[#5C9F01]/40 text-white' : '' }}">
                <span class="material-icons text-2xl">question_answer</span>
                <span class="text-sm font-medium">Problems</span>
            </a>

            <!-- Repair Requests -->
            <a href="{{ route('admin.repair-requests') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-[#5C9F01]/40 transition-all duration-200 group {{ request()->routeIs('admin.repair-requests') ? 'bg-[#5C9F01]/40 text-white' : '' }}">
                <span class="material-icons text-2xl">assignment</span>
                <span class="text-sm font-medium">Repair Requests</span>
            </a>

            <!-- ============ PRINTING SECTION ============ -->
            <div class="pt-3 pb-1">
                <span class="text-[10px] uppercase tracking-wider text-white/30 px-3 font-semibold">Printing Services</span>
            </div>

            <!-- Printing Requests -->
            <a href="{{ route('admin.printing-requests') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-[#5C9F01]/40 transition-all duration-200 group {{ request()->routeIs('admin.printing-requests') ? 'bg-[#5C9F01]/40 text-white' : '' }}">
                <span class="material-icons text-2xl">print</span>
                <span class="text-sm font-medium">Printing Requests</span>
            </a>

            <!-- ============ CONTENT SECTION ============ -->
            <div class="pt-3 pb-1">
                <span class="text-[10px] uppercase tracking-wider text-white/30 px-3 font-semibold">Content</span>
            </div>

            <!-- Blog -->
            <a href="{{ route('admin.blogs') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-[#5C9F01]/40 transition-all duration-200 group {{ request()->routeIs('admin.blogs') ? 'bg-[#5C9F01]/40 text-white' : '' }}">
                <span class="material-icons text-2xl">article</span>
                <span class="text-sm font-medium">Blog</span>
            </a>

            <!-- Pages -->
            <a href="{{ route('admin.pages') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-[#5C9F01]/40 transition-all duration-200 group {{ request()->routeIs('admin.pages') ? 'bg-[#5C9F01]/40 text-white' : '' }}">
                <span class="material-icons text-2xl">description</span>
                <span class="text-sm font-medium">Pages</span>
            </a>

            <!-- Policies -->
            <a href="{{ route('admin.policies') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-[#5C9F01]/40 transition-all duration-200 group {{ request()->routeIs('admin.policies') ? 'bg-[#5C9F01]/40 text-white' : '' }}">
                <span class="material-icons text-2xl">gavel</span>
                <span class="text-sm font-medium">Policies</span>
            </a>

            <!-- Banners -->
            <a href="{{ route('admin.banners') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-[#5C9F01]/40 transition-all duration-200 group {{ request()->routeIs('admin.banners') ? 'bg-[#5C9F01]/40 text-white' : '' }}">
                <span class="material-icons text-2xl">campaign</span>
                <span class="text-sm font-medium">Banners</span>
            </a>

            <!-- Contacts -->
            <a href="{{ route('admin.contacts') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-[#5C9F01]/40 transition-all duration-200 group {{ request()->routeIs('admin.contacts') ? 'bg-[#5C9F01]/40 text-white' : '' }}">
                <span class="material-icons text-2xl">contact_mail</span>
                <span class="text-sm font-medium">Contacts</span>
            </a>

            <!-- ============ SEO SECTION ============ -->
            <div class="pt-3 pb-1">
                <span class="text-[10px] uppercase tracking-wider text-white/30 px-3 font-semibold">SEO</span>
            </div>

            <!-- SEO Settings -->
            <a href="{{ route('admin.seo-settings') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-[#5C9F01]/40 transition-all duration-200 group {{ request()->routeIs('admin.seo-settings') ? 'bg-[#5C9F01]/40 text-white' : '' }}">
                <span class="material-icons text-2xl">manage_search</span>
                <span class="text-sm font-medium">SEO Settings</span>
            </a>

            <!-- Programmatic SEO -->
            <a href="{{ route('admin.programatic-seo') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-[#5C9F01]/40 transition-all duration-200 group {{ request()->routeIs('admin.programatic-seo') ? 'bg-[#5C9F01]/40 text-white' : '' }}">
                <span class="material-icons text-2xl">travel_explore</span>
                <span class="text-sm font-medium">Programmatic SEO</span>
            </a>


            <a href="{{ route('admin.sitemap') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-[#5C9F01]/40 transition-all duration-200 group {{ request()->routeIs('admin.sitemap') ? 'bg-[#5C9F01]/40 text-white' : '' }}">
                <span class="material-icons text-2xl">map</span>
                <span class="text-sm font-medium">Sitemap</span>
            </a>



            <!-- ============ SETTINGS SECTION ============ -->
            <div class="pt-3 pb-1">
                <span class="text-[10px] uppercase tracking-wider text-white/30 px-3 font-semibold">Settings</span>
            </div>

            <!-- Site Settings -->
            <a href="{{ route('admin.settings') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-[#5C9F01]/40 transition-all duration-200 group {{ request()->routeIs('admin.settings') ? 'bg-[#5C9F01]/40 text-white' : '' }}">
                <span class="material-icons text-2xl">settings</span>
                <span class="text-sm font-medium">Site Settings</span>
            </a>

        </nav>
    </div>
</aside>

<!-- Sidebar Overlay -->
<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 bg-black/50 backdrop-blur-sm z-20 hidden"></div>
