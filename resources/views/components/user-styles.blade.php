<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fb;
        }
        /* Custom dropdown hover */
        .group:hover .group-hover\:block {
            display: block;
        }
        /* Mega menu styles */
        .mega-menu {
            display: none;
        }
        .group:hover .mega-menu {
            display: block;
        }
        /* Category dropdown */
        .category-dropdown {
            display: none;
        }
        .category-trigger:hover + .category-dropdown,
        .category-dropdown:hover {
            display: block;
        }
        /* Sidebar menu */
        .sidebar-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }
        .sidebar-menu.open {
            transform: translateX(0);
        }
        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
        }
        ::-webkit-scrollbar-track {
            background: #e2e8f0;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
    </style>