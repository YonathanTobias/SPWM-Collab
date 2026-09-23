<?php

use App\Models\User;
use App\Models\NavMenu;
use Illuminate\Support\Facades\Hash;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Seed Users
User::updateOrCreate(
    ['email' => 'admin@stikespantiwaluya.ac.id'],
    [
        'name' => 'Super Admin Kerjasama',
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]
);

User::updateOrCreate(
    ['email' => 'pimpinan@stikespantiwaluya.ac.id'],
    [
        'name' => 'Pimpinan STIKes Panti Waluya',
        'password' => Hash::make('password'),
        'role' => 'pimpinan',
    ]
);

// Seed Parent and Sub Menus if empty
if (NavMenu::count() == 0) {
    $profilMenu = NavMenu::create([
        'parent_id' => null,
        'title' => 'Profil & Layanan',
        'url' => '#',
        'icon' => 'fa-solid fa-building-columns',
        'is_external' => false,
        'order' => 1,
        'is_active' => true,
    ]);

    NavMenu::create([
        'parent_id' => $profilMenu->id,
        'title' => 'Website Utama STIKes',
        'url' => 'https://stikespantiwaluya.ac.id',
        'icon' => 'fa-solid fa-globe',
        'is_external' => true,
        'order' => 1,
        'is_active' => true,
    ]);

    NavMenu::create([
        'parent_id' => $profilMenu->id,
        'title' => 'Panduan Prosedur Kerjasama',
        'url' => '#',
        'icon' => 'fa-solid fa-file-invoice',
        'is_external' => false,
        'order' => 2,
        'is_active' => true,
    ]);

    $akreditasiMenu = NavMenu::create([
        'parent_id' => null,
        'title' => 'Layanan Akreditasi',
        'url' => '#',
        'icon' => 'fa-solid fa-certificate',
        'is_external' => false,
        'order' => 2,
        'is_active' => true,
    ]);

    NavMenu::create([
        'parent_id' => $akreditasiMenu->id,
        'title' => 'Sistem Rekapitulasi Borang',
        'url' => '/dashboard',
        'icon' => 'fa-solid fa-chart-pie',
        'is_external' => false,
        'order' => 1,
        'is_active' => true,
    ]);

    NavMenu::create([
        'parent_id' => $akreditasiMenu->id,
        'title' => 'Portal Resmi LAM-PTKes',
        'url' => 'https://lamptkes.org',
        'icon' => 'fa-solid fa-arrow-up-right-from-square',
        'is_external' => true,
        'order' => 2,
        'is_active' => true,
    ]);
}

echo "Users and NavMenus seeded successfully.\n";
