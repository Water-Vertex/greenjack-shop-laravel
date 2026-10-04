<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
    $settings = \App\Models\Setting::find(1);
    @endphp
    @yield('meta')
    
    <title>@if($settings) {{$settings->site_title}} @endif</title>
    <link href="@if($settings){{asset('storage/'.$settings->favicon)}}@endif" rel="icon"/>
     @livewireStyles
     @vite(['resources/css/app.css','resources/js/app.js'])
     <x-user-styles/>
</head>

<body>
     <x-user-header/>

    {{$slot}}
    <x-user-footer/>
    
        <!-- Footer -->
    @livewireScripts
</body>
</html>