<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Kelola') - SIM-KERJASAMA STIKes Panti Waluya</title>
    
    <!-- Custom Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.svg') }}">
    
    <!-- Google Fonts -->
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
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js & FontAwesome & Chart.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full font-sans text-slate-800 antialiased flex flex-col" x-data="{ sidebarOpen: false }">

    @php
        $expiringCountNotification = \App\Models\Cooperation::whereBetween('end_date', [\Carbon\Carbon::today(), \Carbon\Carbon::today()->addDays(90)])
            ->orWhere('status', 'Akan Berakhir')
            ->count();
    @endphp

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 transform transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0 flex flex-col shadow-xl"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center justify-between px-6 bg-slate-950 border-b border-slate-800">
                <a href="{{ route('public.index') }}" class="flex items-center gap-3 group">
                    <div class="w-9 h-9 rounded-lg bg-stikes-600 flex items-center justify-center text-white font-bold text-lg">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                    <div>
                        <h1 class="font-extrabold text-sm text-white tracking-tight">SIM-KERJASAMA</h1>
                        <p class="text-[10px] text-slate-400 font-medium">STIKes Panti Waluya</p>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <div class="px-3 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Menu Utama</div>

                @if(Auth::user()->role === 'pimpinan')
                    <a href="{{ route('dashboard.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('dashboard.index') ? 'bg-stikes-600 text-white shadow-md shadow-stikes-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-chart-pie w-4 text-center text-sm"></i>
                        <span>Dashboard Analytics</span>
                    </a>
                @endif

                <a href="{{ route('admin.cooperations.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.cooperations.*') ? 'bg-stikes-600 text-white shadow-md shadow-stikes-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-folder-open w-4 text-center text-sm"></i>
                    <span>Kelola Data Kerjasama</span>
                </a>

                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.nav_menus.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.nav_menus.*') ? 'bg-stikes-600 text-white shadow-md shadow-stikes-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-bars-staggered w-4 text-center text-sm"></i>
                        <span>Kelola Menu Navbar</span>
                    </a>
                @endif

                <a href="{{ route('admin.notifications') }}" 
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.notifications') ? 'bg-stikes-600 text-white shadow-md shadow-stikes-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-bell w-4 text-center text-sm"></i>
                        <span>Pemantauan & Notifikasi</span>
                    </div>
                    @if($expiringCountNotification > 0)
                        <span class="px-2 py-0.5 text-[10px] font-bold bg-amber-500 text-slate-950 rounded-full">
                            {{ $expiringCountNotification }}
                        </span>
                    @endif
                </a>

                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'pimpinan')
                    <div class="pt-6 px-3 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Laporan & Akreditasi</div>
                    <a href="{{ route('dashboard.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('dashboard.index') ? 'bg-stikes-600 text-white shadow-md shadow-stikes-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-file-excel w-4 text-center text-sm text-emerald-400"></i>
                        <span>Rekapitulasi Borang</span>
                    </a>
                @endif

                <div class="pt-6 px-3 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Pintas Publik</div>
                <a href="{{ route('public.index') }}" target="_blank" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fa-solid fa-arrow-up-right-from-square w-4 text-center text-sm"></i>
                    <span>Buka Katalog Publik</span>
                </a>
            </nav>

            <!-- User Info Sidebar Footer -->
            <div class="p-4 bg-slate-950 border-t border-slate-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-stikes-400 text-xs">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-stikes-400 uppercase tracking-wider font-semibold">{{ Auth::user()->role }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Topbar Header -->
            <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 z-10 shadow-sm">
                
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-slate-600 hover:text-slate-900 p-2 rounded-lg hover:bg-slate-100">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight">
                        @yield('page-title', 'Dashboard System')
                    </h2>
                </div>

                <!-- Right Topbar Controls -->
                <div class="flex items-center gap-3" x-data="{ userDropdown: false }">
                    
                    <!-- Notification Bell Link -->
                    <a href="{{ route('admin.notifications') }}" class="relative p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors">
                        <i class="fa-solid fa-bell text-lg"></i>
                        @if($expiringCountNotification > 0)
                            <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-amber-500 rounded-full ring-2 ring-white"></span>
                        @endif
                    </a>

                    <!-- User Profile Dropdown -->
                    <div class="relative">
                        <button @click="userDropdown = !userDropdown" class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-slate-100 transition-colors border border-slate-200">
                            <div class="w-8 h-8 rounded-lg bg-stikes-600 text-white font-bold flex items-center justify-center text-xs">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <span class="text-xs font-semibold text-slate-700 hidden sm:inline-block">{{ Auth::user()->name }}</span>
                            <i class="fa-solid fa-chevron-down text-slate-400 text-[10px]"></i>
                        </button>

                        <div x-show="userDropdown" @click.away="userDropdown = false" x-cloak
                             class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-200 py-2 z-50">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs font-bold text-slate-900">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-slate-500">{{ Auth::user()->email }}</p>
                            </div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 flex items-center gap-2 transition-colors">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    <span>Keluar (Logout)</span>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </header>

            <!-- Page Body -->
            <main class="flex-1 overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8">
                
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm font-semibold flex items-center justify-between shadow-sm" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-700 hover:text-emerald-950">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
