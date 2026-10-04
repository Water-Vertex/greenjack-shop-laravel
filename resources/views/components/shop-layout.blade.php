<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
    $settings = \App\Models\Setting::find(1);
    @endphp
    @yield('meta')
   <link rel="canonical" href="{{ url()->current() }}" />
   @if($settings){!! $settings->head_tags !!}@endif
    <link href="@if($settings){{asset('storage/'.$settings->favicon)}}@endif" rel="icon"/>
     @livewireStyles
     @vite(['resources/css/app.css','resources/js/app.js'])
     <x-user-styles/>
     <style>
        /* Alpine.js Cloak */
[x-cloak] {
    display: none !important;
}

/* Mobile Bottom Sheet Styles */
@media (max-width: 640px) {
    .fixed.inset-x-0.bottom-0 {
        animation: slideUp 0.3s ease-out;
    }

    .fixed.inset-x-0.bottom-0[x-show="false"] {
        animation: slideDown 0.3s ease-in;
    }

    @keyframes slideUp {
        from {
            transform: translateY(100%);
        }
        to {
            transform: translateY(0);
        }
    }

    @keyframes slideDown {
        from {
            transform: translateY(0);
        }
        to {
            transform: translateY(100%);
        }
    }
}

/* Backdrop animation */
.fixed.inset-0.bg-black\/50 {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Safe area for iOS */
@supports (-webkit-touch-callout: none) {
    .fixed.inset-x-0.bottom-0 {
        padding-bottom: env(safe-area-inset-bottom);
    }
}

/* Handle bar */
.w-12.h-1.bg-gray-300 {
    border-radius: 9999px;
}
     </style>
</head>

<body>
     <x-shop-header/>

    {{$slot}}

    @if (session()->has('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
         class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

@if (session()->has('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <div class="flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    </div>
@endif
    <x-shop-footer/>

        <!-- Footer -->


    @livewireScripts
 {{-- ✅ Global Toaster Container --}}
    <div id="global-toaster" class="fixed top-20 right-4 z-[9999] space-y-3 pointer-events-none"></div>

    {{-- ✅ Global Toaster Script --}}
    <script>
        (function () {
            function ensureContainer() {
                let c = document.getElementById('global-toaster');
                if (!c) {
                    c = document.createElement('div');
                    c.id = 'global-toaster';
                    c.className = 'fixed top-20 right-4 z-[9999] space-y-3 pointer-events-none';
                    document.body.appendChild(c);
                }
                return c;
            }

            window.showToast = function (message, type = 'success', duration = 4000) {
                if (!message) return;
                const container = ensureContainer();

                const colors = {
                    success: { bg: 'bg-green-50',  border: 'border-green-200',  text: 'text-green-800',  icon: 'fa-check-circle text-green-600' },
                    error:   { bg: 'bg-red-50',    border: 'border-red-200',    text: 'text-red-800',    icon: 'fa-exclamation-triangle text-red-600' },
                    info:    { bg: 'bg-blue-50',   border: 'border-blue-200',   text: 'text-blue-800',   icon: 'fa-info-circle text-blue-600' },
                    warning: { bg: 'bg-yellow-50', border: 'border-yellow-200', text: 'text-yellow-800', icon: 'fa-exclamation-circle text-yellow-600' },
                };
                const c = colors[type] || colors.success;

                const toast = document.createElement('div');
                toast.className = `pointer-events-auto ${c.bg} ${c.border} ${c.text} border px-4 py-3 rounded-lg shadow-lg flex items-start gap-3 min-w-[280px] max-w-md transform transition-all duration-300 translate-x-full opacity-0`;
                toast.innerHTML = `
                    <i class="fas ${c.icon} mt-0.5"></i>
                    <div class="flex-1 text-sm">${message}</div>
                    <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                `;

                container.appendChild(toast);

                requestAnimationFrame(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                });

                setTimeout(() => {
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => toast.remove(), 300);
                }, duration);
            };

            // ✅ Livewire dispatch('toast', ...) listener
            document.addEventListener('livewire:init', () => {
                Livewire.on('toast', (event) => {
                    const data = Array.isArray(event) ? event[0] : event;
                    if (data && data.message) {
                        window.showToast(data.message, data.type || 'success', data.duration || 4000);
                    }
                });
            });

            // ✅ Custom event listener (blade se dispatch karne ke liye)
            window.addEventListener('show-toast', (e) => {
                const d = e.detail || {};
                window.showToast(d.message, d.type || 'success', d.duration || 4000);
            });

            // ✅ Page load pe session flash → toaster
            document.addEventListener('DOMContentLoaded', () => {
                @if(session()->has('success'))
                    window.showToast(@json(session('success')), 'success');
                @endif
                @if(session()->has('error'))
                    window.showToast(@json(session('error')), 'error');
                @endif
                @if(session()->has('info'))
                    window.showToast(@json(session('info')), 'info');
                @endif
                @if(session()->has('warning'))
                    window.showToast(@json(session('warning')), 'warning');
                @endif
            });
        })();
    </script>

</body>
</html>
