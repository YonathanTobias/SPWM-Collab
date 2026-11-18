@extends('layouts.app')

@section('title', 'Kelola Akun Admin & Pengguna')
@section('page-title', 'Pengelolaan Akun Admin & Hak Akses Sistem')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto" x-data="{ 
    createModal: false, 
    editModal: false, 
    currentUser: { id: '', name: '', email: '', role: 'admin' },
    openEdit(user) {
        this.currentUser = { ...user };
        this.editModal = true;
    }
}">

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-800 text-xs font-semibold shadow-sm animate-fade-in">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center gap-3 text-rose-800 text-xs font-semibold shadow-sm animate-fade-in">
            <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-xs font-semibold shadow-sm space-y-1">
            <div class="flex items-center gap-2 font-bold text-rose-900">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Terdapat kesalahan pada input:</span>
            </div>
            <ul class="list-disc list-inside ps-2 font-normal text-rose-700">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header & Action Card -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-50 text-purple-700 text-[11px] font-bold border border-purple-200 mb-2">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Hak Akses Super Admin</span>
            </div>
            <h3 class="text-xl font-extrabold text-slate-900">Manajemen Pengguna & Admin</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-xl leading-relaxed">
                Kelola hak akses pengelola sistem. <strong>Super Admin</strong> memiliki otoritas penuh terhadap sistem dan akun, sedangkan <strong>Admin</strong> bertanggung jawab atas operasional data kerja sama dan tata kelola navbar.
            </p>
        </div>

        <button @click="createModal = true" class="px-5 py-3 bg-stikes-600 hover:bg-stikes-700 text-white font-bold text-xs rounded-2xl shadow-lg shadow-stikes-600/30 transition-all inline-flex items-center gap-2 flex-shrink-0 hover:-translate-y-0.5">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Admin Baru</span>
        </button>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Pengguna</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $totalUsers }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Super Admin</p>
                <p class="text-2xl font-black text-purple-600 mt-1">{{ $superAdminCount }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 border border-purple-200 flex items-center justify-center text-lg">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Admin Operasional</p>
                <p class="text-2xl font-black text-stikes-600 mt-1">{{ $adminCount }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-stikes-50 text-stikes-600 border border-stikes-200 flex items-center justify-center text-lg">
                <i class="fa-solid fa-user-gear"></i>
            </div>
        </div>
    </div>

    <!-- User Table & Filter Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Filter Header -->
        <div class="p-5 border-b border-slate-200 bg-slate-50 flex flex-col md:flex-row gap-4 items-center justify-between">
            <form action="{{ route('admin.users.index') }}" method="GET" class="w-full md:w-auto flex flex-wrap items-center gap-3">
                <div class="relative flex-1 sm:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." 
                           class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-stikes-600 focus:outline-none">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                </div>

                <select name="role" onchange="this.form.submit()" class="py-2 px-3 text-xs bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-stikes-600 focus:outline-none">
                    <option value="">Semua Peran (Role)</option>
                    <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>

                @if(request('search') || request('role'))
                    <a href="{{ route('admin.users.index') }}" class="text-xs text-rose-600 hover:text-rose-800 font-semibold inline-flex items-center gap-1">
                        <i class="fa-solid fa-xmark"></i>
                        <span>Reset Filter</span>
                    </a>
                @endif
            </form>

            <span class="text-xs text-slate-500 font-semibold self-end md:self-center">{{ $users->total() }} Akun Ditemukan</span>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-600 font-extrabold text-[11px] uppercase tracking-wider border-b border-slate-200">
                        <th class="py-3.5 px-6">Pengguna</th>
                        <th class="py-3.5 px-6">Alamat Email</th>
                        <th class="py-3.5 px-6">Peran (Role)</th>
                        <th class="py-3.5 px-6">Terdaftar Sejak</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($users as $userItem)
                        <tr class="hover:bg-slate-50/80 transition-colors {{ $userItem->id === Auth::id() ? 'bg-stikes-50/20' : '' }}">
                            <!-- Name & Avatar -->
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-xs {{ $userItem->role === 'super_admin' ? 'bg-purple-100 text-purple-700 border border-purple-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                        {{ strtoupper(substr($userItem->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span>{{ $userItem->name }}</span>
                                            @if($userItem->id === Auth::id())
                                                <span class="px-2 py-0.5 text-[9px] font-extrabold rounded-md bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    Akun Anda
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-[10px] text-slate-400 font-normal">ID: #{{ $userItem->id }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="py-4 px-6 font-medium text-slate-600 font-mono text-[11px]">
                                {{ $userItem->email }}
                            </td>

                            <!-- Role Badge -->
                            <td class="py-4 px-6">
                                @if($userItem->role === 'super_admin')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-bold rounded-lg bg-purple-50 text-purple-700 border border-purple-200">
                                        <i class="fa-solid fa-crown text-[10px]"></i>
                                        <span>Super Admin</span>
                                    </span>
                                @elseif($userItem->role === 'admin')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-bold rounded-lg bg-blue-50 text-blue-700 border border-blue-200">
                                        <i class="fa-solid fa-user-gear text-[10px]"></i>
                                        <span>Admin</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-bold rounded-lg bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="fa-solid fa-briefcase text-[10px]"></i>
                                        <span>{{ ucfirst($userItem->role) }}</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Created At -->
                            <td class="py-4 px-6 text-slate-500 text-[11px]">
                                {{ $userItem->created_at ? $userItem->created_at->translatedFormat('d M Y, H:i') : '-' }}
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <button @click="openEdit({ id: '{{ $userItem->id }}', name: '{{ addslashes($userItem->name) }}', email: '{{ addslashes($userItem->email) }}', role: '{{ $userItem->role }}' })" 
                                            class="p-2 text-slate-500 hover:text-stikes-600 hover:bg-stikes-50 rounded-lg transition-colors" title="Edit Akun">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>

                                    @if($userItem->id !== Auth::id())
                                        <form action="{{ route('admin.users.destroy', $userItem->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun admin {{ addslashes($userItem->name) }}? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Akun">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="p-2 text-slate-300 cursor-not-allowed" title="Tidak dapat menghapus akun sendiri">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-users-slash text-3xl mb-2 text-slate-300 block"></i>
                                <p class="text-xs font-semibold">Tidak ada akun admin yang sesuai dengan pencarian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $users->links() }}
            </div>
        @endif

    </div>

    <!-- MODAL: Tambah Admin Baru -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="createModal = false"></div>

            <div class="relative bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 z-10 animate-scale-up">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-stikes-50 text-stikes-600 flex items-center justify-center">
                            <i class="fa-solid fa-user-plus text-base"></i>
                        </div>
                        <div>
                            <h4 class="text-base font-extrabold text-slate-900">Tambah Akun Admin Baru</h4>
                            <p class="text-[11px] text-slate-400">Daftarkan akun pengelola sistem baru</p>
                        </div>
                    </div>
                    <button @click="createModal = false" class="text-slate-400 hover:text-slate-600 p-2">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('admin.users.store') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="Contoh: Admin Bagian Kerja Sama" 
                               class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-stikes-600 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" required placeholder="admin.kerjasama@stikespantiwaluya.ac.id" 
                               class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-stikes-600 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Peran (Role) <span class="text-rose-500">*</span></label>
                        <select name="role" required class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-stikes-600 focus:bg-white focus:outline-none">
                            <option value="admin">Admin (Kelola Data Kerja Sama & Menu Navbar)</option>
                            <option value="super_admin">Super Admin (Otoritas Penuh & Kelola Pengguna)</option>
                        </select>
                        <p class="text-[10px] text-slate-400 mt-1">Hanya berikan peran Super Admin kepada staf yang berwenang mengelola akun sistem.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi <span class="text-rose-500">*</span></label>
                            <input type="password" name="password" required minlength="8" placeholder="Minimal 8 karakter" 
                                   class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-stikes-600 focus:bg-white focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Sandi <span class="text-rose-500">*</span></label>
                            <input type="password" name="password_confirmation" required minlength="8" placeholder="Ulangi sandi" 
                                   class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-stikes-600 focus:bg-white focus:outline-none">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <button type="button" @click="createModal = false" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-stikes-600 hover:bg-stikes-700 text-white shadow-md shadow-stikes-600/30 transition-all">
                            Simpan Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: Edit Akun Admin -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="editModal = false"></div>

            <div class="relative bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 z-10 animate-scale-up">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                            <i class="fa-solid fa-user-pen text-base"></i>
                        </div>
                        <div>
                            <h4 class="text-base font-extrabold text-slate-900">Perbarui Data Akun Admin</h4>
                            <p class="text-[11px] text-slate-400">Edit profil atau ubah kata sandi pengguna</p>
                        </div>
                    </div>
                    <button @click="editModal = false" class="text-slate-400 hover:text-slate-600 p-2">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form :action="'{{ url('admin/users') }}/' + currentUser.id" method="POST" class="mt-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="currentUser.name" required 
                               class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-stikes-600 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" x-model="currentUser.email" required 
                               class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-stikes-600 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Peran (Role) <span class="text-rose-500">*</span></label>
                        <select name="role" x-model="currentUser.role" required class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-stikes-600 focus:bg-white focus:outline-none">
                            <option value="admin">Admin (Kelola Data Kerja Sama & Menu Navbar)</option>
                            <option value="super_admin">Super Admin (Otoritas Penuh & Kelola Pengguna)</option>
                        </select>
                    </div>

                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-3 mt-2">
                        <div>
                            <p class="text-[11px] font-bold text-slate-700">Ubah Kata Sandi (Opsional)</p>
                            <p class="text-[10px] text-slate-400">Biarkan kedua kolom di bawah kosong jika tidak ingin mengganti kata sandi.</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Kata Sandi Baru</label>
                                <input type="password" name="password" minlength="8" placeholder="Minimal 8 karakter" 
                                       class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-stikes-600 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Ulangi Sandi Baru</label>
                                <input type="password" name="password_confirmation" minlength="8" placeholder="Konfirmasi sandi" 
                                       class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-stikes-600 focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <button type="button" @click="editModal = false" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-stikes-600 hover:bg-stikes-700 text-white shadow-md shadow-stikes-600/30 transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
