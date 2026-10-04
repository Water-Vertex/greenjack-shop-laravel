<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @livewireStyles
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div>
                <div class="mx-auto h-12 w-12 bg-[#5C9F01] rounded-full flex items-center justify-center">
                    <i class="fas fa-box text-white text-xl"></i>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    {{ $title ?? 'Welcome' }}
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    {{ $description ?? '' }}
                </p>
            </div>

            <!-- Content -->
            <div class="bg-white py-8 px-6 shadow rounded-lg sm:px-10">
                {{ $slot }}
            </div>

            <!-- Footer Links -->
            <div class="text-center">
                {{ $footer ?? '' }}
            </div>
        </div>
    </div>
    @livewireScripts
</body>
</html>