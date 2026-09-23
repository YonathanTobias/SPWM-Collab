<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pengelola System - STIKes Panti Waluya Malang</title>
    
    <!-- Custom Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.svg') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
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
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="h-full font-sans antialiased text-slate-800 flex items-center justify-center p-4 bg-gradient-to-br from-slate-950 via-slate-900 to-stikes-900 relative overflow-hidden">

    <!-- Ambient Glowing Backdrop -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-stikes-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        
        <!-- Top Back Link -->
        <div class="mb-6 text-center">
            <a href="{{ route('public.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-white transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Katalog Publik</span>
            </a>
        </div>

        <!-- Login Card -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl">
            
            <div class="text-center mb-8">
                <div class="w-14 h-14 rounded-2xl bg-stikes-600 text-white flex items-center justify-center text-2xl mx-auto shadow-lg shadow-stikes-600/40 mb-4">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-white tracking-tight">Portal Pengelola System</h2>
                <p class="text-xs text-slate-300 font-medium mt-1">SIM-KERJASAMA STIKes Panti Waluya Malang</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="p-4 rounded-2xl bg-rose-500/20 border border-rose-500/40 text-rose-200 text-xs font-semibold">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-200 mb-1.5 uppercase tracking-wider">Email Pengelola / Pimpinan</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               placeholder="admin@stikespantiwaluya.ac.id" 
                               class="w-full pl-11 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-slate-400 text-xs focus:ring-2 focus:ring-stikes-400 focus:bg-white/20 focus:outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-200 mb-1.5 uppercase tracking-wider">Kata Sandi (Password)</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               placeholder="••••••••" 
                               class="w-full pl-11 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-slate-400 text-xs focus:ring-2 focus:ring-stikes-400 focus:bg-white/20 focus:outline-none transition-all">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs text-slate-300 pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded bg-white/10 border-white/20 text-stikes-600 focus:ring-0">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 bg-stikes-600 hover:bg-stikes-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-stikes-600/30 transition-all hover:scale-[1.02]">
                    Masuk ke System &rarr;
                </button>
            </form>

            <!-- Quick Demo Credentials Hint Box -->
            <div class="mt-8 p-4 rounded-2xl bg-white/5 border border-white/10 text-slate-300 text-[11px]">
                <p class="font-bold text-white mb-1.5 uppercase tracking-wider text-[10px]">
                    <i class="fa-solid fa-[#10b981] fa-key me-1 text-stikes-400"></i> Akun Demo Pengujian:
                </p>
                <div class="space-y-1 font-mono text-[10px] text-slate-300">
                    <p>• Admin: <span class="text-stikes-300 font-bold">admin@stikespantiwaluya.ac.id</span> / password</p>
                    <p>• Pimpinan: <span class="text-blue-300 font-bold">pimpinan@stikespantiwaluya.ac.id</span> / password</p>
                </div>
            </div>

        </div>
        
        <p class="text-center text-xs text-slate-500 mt-6">&copy; {{ date('Y') }} STIKes Panti Waluya Malang</p>
    </div>

</body>
</html>
