<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Material Admin Dashboard - {{ config('app.name') }}</title>

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/ckeditor.js'])

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <x-admin-styles />

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .main-content {
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
        }

        /* Mobile Sidebar Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 25;
            backdrop-filter: blur(4px);
            transition: all 0.3s ease;
        }

        .sidebar-overlay.active,
        .sidebar-overlay.block {
            display: block;
        }

        /* Loading state */
        .loading {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loading.hide {
            display: none;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0 !important;
            }

            .sidebar-desktop {
                transform: translateX(-100%);
            }

            .sidebar-desktop.open {
                transform: translateX(0);
            }
        }

        @media (min-width: 769px) {
            .sidebar-desktop {
                transform: translateX(0) !important;
            }
        }

        /* Body overflow hidden when sidebar open */
        body.overflow-hidden {
            overflow: hidden;
        }
    </style>
    @livewireStyles
    @yield('page-style')
</head>

<body class="bg-gray-50 antialiased">
    <!-- Loading Spinner -->
    <div class="loading hide" id="loading">
        <div class="w-12 h-12 border-4 border-[#5C9F01] border-t-transparent rounded-full animate-spin"></div>
    </div>

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <x-admin-sidebar />

    <!-- Page Content -->
    <main class="main-content md:ml-72 transition-all duration-300">
        <x-admin-header />

        <div class="p-4 sm:p-6">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <footer class="main-content md:ml-72 transition-all duration-300 mt-8">
            <div class="p-4 sm:p-6 text-center text-gray-500 text-sm border-t border-gray-200">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </footer>
    </main>

    <x-admin-scripts />
    @livewireScripts
    @stack('scripts')

    <!-- Single consolidated JavaScript -->
    <script>
    $(document).ready(function() {
        // ============ MOBILE SIDEBAR TOGGLE ============
        // Open sidebar
        $('#mobileMenuBtn').click(function(e) {
            e.preventDefault();
            $('#sidebar').addClass('open');
            $('#sidebarOverlay').addClass('active');
            $('body').addClass('overflow-hidden');
        });

        // Close sidebar with close button
        $('#closeSidebar').click(function(e) {
            e.preventDefault();
            $('#sidebar').removeClass('open');
            $('#sidebarOverlay').removeClass('active');
            $('body').removeClass('overflow-hidden');
        });

        // Close sidebar on overlay click
        $('#sidebarOverlay').click(function() {
            $('#sidebar').removeClass('open');
            $('#sidebarOverlay').removeClass('active');
            $('body').removeClass('overflow-hidden');
        });

        // Close sidebar on Escape key
        $(document).keydown(function(e) {
            if (e.key === 'Escape') {
                $('#sidebar').removeClass('open');
                $('#sidebarOverlay').removeClass('active');
                $('body').removeClass('overflow-hidden');
            }
        });

        // ============ USER DROPDOWN ============
        $('.dropdown-trigger-user').click(function(e) {
            e.stopPropagation();

            var $content = $('.dropdown-user-content');
            var $icon = $('.dropdown-user-icon');

            if ($content.hasClass('hidden')) {
                $('.dropdown-user-content').not($content).addClass('hidden');
                $('.dropdown-user-icon').not($icon).removeClass('rotate-180');

                $content.removeClass('hidden').slideDown(200);
                $icon.addClass('rotate-180');
            } else {
                $content.slideUp(200, function() {
                    $(this).addClass('hidden');
                });
                $icon.removeClass('rotate-180');
            }
        });

        // Close dropdown when clicking outside
        $(document).click(function(e) {
            if (!$(e.target).closest('.dropdown-container').length) {
                $('.dropdown-user-content').slideUp(200, function() {
                    $(this).addClass('hidden');
                });
                $('.dropdown-user-icon').removeClass('rotate-180');
            }
        });

        // ============ MOBILE SEARCH ============
        $('#mobileSearchToggle').click(function() {
            $('#mobileSearch').slideToggle(200);
            $(this).find('.material-icons').text(
                $('#mobileSearch').is(':visible') ? 'close' : 'search'
            );
        });

        $('#mobileSearchClose').click(function() {
            $('#mobileSearch').slideUp(200);
            $('#mobileSearchToggle').find('.material-icons').text('search');
        });

        // Close mobile search on Escape key
        $(document).keydown(function(e) {
            if (e.key === 'Escape' && $('#mobileSearch').is(':visible')) {
                $('#mobileSearch').slideUp(200);
                $('#mobileSearchToggle').find('.material-icons').text('search');
            }
        });

        // ============ SIDEBAR COLLAPSE (Desktop) ============
        let collapsed = false;
        $('#collapseBtn').click(function() {
            collapsed = !collapsed;
            const $sidebar = $('#sidebar');
            const $mainContent = $('.main-content');

            if (window.innerWidth > 768) {
                if (collapsed) {
                    $sidebar.addClass('w-20').removeClass('w-72');
                    $mainContent.addClass('md:ml-20').removeClass('md:ml-72');
                    $('#sidebar .brand-text, #sidebar .nav-label, #sidebar .dropdown-trigger span:not(.material-icons), #sidebar .dropdown-trigger .dropdown-icon, #sidebar .dropdown-content').hide();
                    $('#sidebar .dropdown-content').hide();
                    $(this).find('.material-icons').text('menu');
                    localStorage.setItem('sidebarCollapsed', 'true');
                } else {
                    $sidebar.addClass('w-72').removeClass('w-20');
                    $mainContent.addClass('md:ml-72').removeClass('md:ml-20');
                    $('#sidebar .brand-text, #sidebar .nav-label, #sidebar .dropdown-trigger span:not(.material-icons)').show();
                    $('#sidebar .dropdown-trigger .dropdown-icon').show();
                    $(this).find('.material-icons').text('menu_open');
                    localStorage.setItem('sidebarCollapsed', 'false');
                }
            }
        });

        // Restore sidebar state
        const savedState = localStorage.getItem('sidebarCollapsed');
        if (savedState === 'true' && window.innerWidth > 768) {
            $('#collapseBtn').click();
        }

        // Handle window resize
        $(window).resize(function() {
            if (window.innerWidth > 768) {
                $('#sidebar').removeClass('open');
                $('#sidebarOverlay').removeClass('active');
                $('body').removeClass('overflow-hidden');
            }
        });

        // ============ ACTIVE LINK HIGHLIGHTING ============
        var currentUrl = window.location.href;
        $('.dropdown-content a, .sidebar-nav a, .nav-item a').each(function() {
            if ($(this).attr('href') && currentUrl.indexOf($(this).attr('href')) !== -1) {
                $(this).addClass('bg-[#5C9F01]/40 text-white');
                // Expand parent dropdown if any
                $(this).closest('.dropdown-content').addClass('open').show();
                $(this).closest('.dropdown-content').prev('.dropdown-trigger').find('.dropdown-icon').addClass('rotate-180');
            }
        });

        // Close dropdowns when clicking outside
        $(document).click(function(e) {
            if (!$(e.target).closest('.dropdown-container').length && !$(e.target).closest('.dropdown-trigger').length) {
                $('.dropdown-content').slideUp(200, function() {
                    $(this).removeClass('open');
                });
                $('.dropdown-icon').removeClass('rotate-180');
            }
        });

        // ============ LOADING STATE FOR AJAX ============
        $(document).ajaxStart(function() {
            $('#loading').removeClass('hide');
        }).ajaxStop(function() {
            $('#loading').addClass('hide');
        });
    });
    </script>

    <style>
        /* Rotate animation */
        .rotate-180 {
            transform: rotate(180deg);
        }

        /* Smooth dropdown animation */
        .dropdown-user-content {
            display: none;
            animation: fadeInDown 0.2s ease-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mobile responsive adjustments */
        @media (max-width: 640px) {
            header {
                padding: 0;
            }

            header .px-3 {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            .dropdown-user-content {
                width: 280px !important;
                right: -10px !important;
            }
        }

        /* Smooth transitions for mobile elements */
        #mobileSearch {
            transition: all 0.2s ease-in-out;
        }

        /* Fix for material icons alignment */
        .material-icons {
            vertical-align: middle;
        }

        /* Hover effect for buttons */
        .hover\:bg-gray-100:hover {
            background-color: rgba(243, 244, 246, 0.8);
        }

        /* Notification badge pulse */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>

    @yield('page-script')
</body>
</html>
