<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Katalog Kerjasama') - STIKes Panti Waluya Malang</title>
    
    <!-- Meta SEO -->
    <meta name="description" content="Sistem Informasi Manajemen Dokumen Kerjasama STIKes Panti Waluya Malang. Katalog transparan dokumen MoU, MoA, dan IA.">
    
    <!-- Custom Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.svg') }}">
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        stikes: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        },
                        navy: {
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine JS & FontAwesome/Lucide Icons -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- External Dedicated Custom CSS & JS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script defer src="{{ asset('js/main.js') }}"></script>
</head>
<body class="h-full font-sans text-slate-800 antialiased flex flex-col selection:bg-stikes-500 selection:text-white">

    <!-- Header Navigation -->
    <header class="sticky top-0 z-40 glass-header border-b border-slate-200/80 shadow-sm transition-all duration-200" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo & Brand Title -->
                <a href="{{ route('public.index') }}" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-stikes-700 via-stikes-600 to-emerald-400 p-0.5 shadow-md shadow-stikes-600/20 group-hover:scale-105 transition-transform">
                        <div class="w-full h-full bg-white rounded-[10px] flex items-center justify-center text-stikes-700 font-extrabold text-xl">
                            <i class="fa-solid me-0.5 fa-file-contract"></i>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-extrabold text-lg text-slate-900 tracking-tight leading-tight group-hover:text-stikes-700 transition-colors">
                                SPWM-Collab
                            </span>
                            <span class="text-[10px] font-bold px-2 py-0.5 bg-stikes-100 text-stikes-800 rounded-full uppercase tracking-wider">Publik</span>
                        </div>
                        <p class="text-xs text-slate-500 font-medium">STIKes Panti Waluya Malang</p>
                    </div>
                </a>

                @php
                    $topNavMenus = \App\Models\NavMenu::with('activeChildren')
                        ->whereNull('parent_id')
                        ->where('is_active', true)
                        ->orderBy('order', 'asc')
                        ->get();
                @endphp

                <!-- Desktop Navigation Menu (Visible on Medium & Desktop) -->
                <div class="hidden md:flex items-center gap-1 sm:gap-2">
                    @foreach($topNavMenus as $menu)
                        @if($menu->activeChildren->count() > 0)
                            <!-- Dropdown Parent Menu -->
                            <div class="relative" x-data="{ open: false }" @mouseleave="open = false">
                                <button @click="open = !open" @mouseenter="open = true" 
                                        class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 hover:text-stikes-700 px-3 py-2 rounded-xl hover:bg-slate-100 transition-all cursor-pointer">
                                    <i class="{{ $menu->icon ?: 'fa-solid fa-folder' }} text-stikes-600"></i>
                                    <span>{{ $menu->title }}</span>
                                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180 text-stikes-600' : ''"></i>
                                </button>

                                <!-- Sub-menu Dropdown List -->
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                                     x-cloak
                                     class="absolute left-0 mt-1 w-56 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-slate-200 py-2 z-50">
                                    
                                    @foreach($menu->activeChildren as $child)
                                        <a href="{{ $child->url }}" 
                                           @if($child->is_external) target="_blank" @endif
                                           class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-stikes-50 hover:text-stikes-800 transition-colors">
                                            <i class="{{ $child->icon ?: 'fa-solid fa-link' }} text-stikes-600 text-xs w-4 text-center"></i>
                                            <span>{{ $child->title }}</span>
                                            @if($child->is_external)
                                                <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-slate-400 ms-auto"></i>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <!-- Single Link Menu -->
                            <a href="{{ $menu->url }}" 
                               @if($menu->is_external) target="_blank" @endif 
                               class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 hover:text-stikes-700 px-3 py-2 rounded-xl hover:bg-slate-100 transition-all">
                                <i class="{{ $menu->icon ?: 'fa-solid fa-link' }} text-stikes-600"></i>
                                <span>{{ $menu->title }}</span>
                            </a>
                        @endif
                    @endforeach

                    @auth
                        <div class="flex items-center gap-2 ms-2">
                            <a href="{{ Auth::user()->role === 'pimpinan' ? route('dashboard.index') : route('admin.cooperations.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-stikes-600 hover:bg-stikes-700 rounded-xl shadow-sm shadow-stikes-600/30 transition-all hover:-translate-y-0.5">
                                <i class="fa-solid fa-gauge-high"></i>
                                <span>Panel {{ Auth::user()->role === 'pimpinan' ? 'Pimpinan' : 'Admin' }}</span>
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Mobile Hamburger Button (Visible only on mobile screens) -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-100 transition-colors shadow-sm">
                    <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark text-lg' : 'fa-bars text-lg'"></i>
                </button>

            </div>
        </div>

        <!-- Mobile Drawer Navigation Panel (Collapsible for Mobile/HP) -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             x-cloak 
             class="md:hidden bg-white/95 backdrop-blur-md border-b border-slate-200 px-4 py-4 space-y-3 shadow-xl">
            
            <div class="px-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Navigasi Utama</div>

            @foreach($topNavMenus as $menu)
                @if($menu->activeChildren->count() > 0)
                    <div x-data="{ subOpen: true }" class="space-y-1">
                        <button @click="subOpen = !subOpen" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-800 bg-slate-100/80 hover:bg-slate-200/80 transition-colors">
                            <div class="flex items-center gap-2">
                                <i class="{{ $menu->icon ?: 'fa-solid fa-folder' }} text-stikes-600"></i>
                                <span>{{ $menu->title }}</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform" :class="subOpen ? 'rotate-180 text-stikes-600' : ''"></i>
                        </button>
                        
                        <div x-show="subOpen" class="pl-4 space-y-1 border-l-2 border-stikes-200 ms-3 py-1">
                            @foreach($menu->activeChildren as $child)
                                <a href="{{ $child->url }}" @if($child->is_external) target="_blank" @endif class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:text-stikes-700 hover:bg-slate-100 transition-colors">
                                    <i class="{{ $child->icon ?: 'fa-solid fa-link' }} text-stikes-600 text-xs w-4 text-center"></i>
                                    <span>{{ $child->title }}</span>
                                    @if($child->is_external)
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-slate-400 ms-auto"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $menu->url }}" @if($menu->is_external) target="_blank" @endif class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-800 bg-slate-100/80 hover:bg-slate-200/80 transition-colors">
                        <i class="{{ $menu->icon ?: 'fa-solid fa-link' }} text-stikes-600"></i>
                        <span>{{ $menu->title }}</span>
                    </a>
                @endif
            @endforeach

            <!-- Login Action for Mobile (Authenticated Users Only) -->
            @auth
                <div class="pt-3 border-t border-slate-200">
                    <a href="{{ Auth::user()->role === 'pimpinan' ? route('dashboard.index') : route('admin.cooperations.index') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 text-xs font-bold text-white bg-stikes-600 hover:bg-stikes-700 rounded-xl shadow-md transition-all">
                        <i class="fa-solid fa-gauge-high"></i>
                        <span>Panel {{ Auth::user()->role === 'pimpinan' ? 'Pimpinan' : 'Admin' }}</span>
                    </a>
                </div>
            @endauth

        </div>

    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @if(session('info'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>{{ session('info') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 border-t border-slate-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <div class="flex items-center gap-2 text-white font-bold text-lg mb-3">
                        <i class="fa-solid fa-hospital-user text-stikes-500"></i>
                        <span>STIKes Panti Waluya Malang</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed mb-4">
                        Sistem Informasi Terpadu Pengelolaan Dokumen Kerjasama (MoU, MoA, dan IA) untuk keterbukaan informasi publik dan pemenuhan standar akreditasi institusi & program studi.
                    </p>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Informasi Kontak</h4>
                    <ul class="space-y-2 text-xs">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-location-dot text-stikes-500 mt-0.5"></i>
                            <span>Jl. Yulius Usman No.62, Kasin, Kec. Klojen, Kota Malang, Jawa Timur 65117</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-stikes-500"></i>
                            <span>kerjasama@stikespantiwaluya.ac.id</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-phone text-stikes-500"></i>
                            <span>(0341) 369003</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-3">Lokasi Kampus (Google Maps)</h4>
                    <div class="rounded-2xl overflow-hidden border border-slate-800 shadow-md bg-slate-800">
                        <iframe src="https://maps.google.com/maps?q=STIKes+Panti+Waluya+Malang,+Jl.+Yulius+Usman+No.62,+Kasin,+Kec.+Klojen,+Kota+Malang,+Jawa+Timur+65117&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                                width="100%" 
                                height="130" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                class="w-full h-32 rounded-2xl"></iframe>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500">
                <p>{{ \App\Models\SiteSetting::getByKey('footer_copyright', '© ' . date('Y') . ' STIKes Panti Waluya Malang. Seluruh hak cipta dilindungi undang-undang.') }}</p>
                <p class="mt-2 sm:mt-0 font-medium">{{ \App\Models\SiteSetting::getByKey('footer_tagline', 'SPWM-Collab v1.0 • Publik Directory') }}</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
