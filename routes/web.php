<?php

use App\Http\Controllers\AdminCooperationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardReportController;
use App\Http\Controllers\PublicCatalogController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

// 1. Public Catalog & Content Routes (No login required)
Route::get('/', [PublicCatalogController::class, 'index'])->name('public.index');
Route::get('/cooperations/export-excel', [PublicCatalogController::class, 'exportExcel'])->name('public.exportExcel');
Route::get('/cooperations/export-pdf', [PublicCatalogController::class, 'exportPdf'])->name('public.exportPdf');
Route::get('/cooperations/{id}/detail', [PublicCatalogController::class, 'show'])->name('public.show');
Route::get('/cooperations/{id}/download', [PublicCatalogController::class, 'download'])->name('public.download');

// Public News & Articles (Berita Publik)
Route::get('/berita', [\App\Http\Controllers\PublicPostController::class, 'index'])->name('public.posts.index');
Route::get('/berita/cover/{id}', [\App\Http\Controllers\PublicPostController::class, 'thumbnail'])->name('public.posts.thumbnail');
Route::get('/berita/placeholder/default', [\App\Http\Controllers\PublicPostController::class, 'placeholder'])->name('public.posts.placeholder');
Route::get('/berita/{slug}', [\App\Http\Controllers\PublicPostController::class, 'show'])->name('public.posts.show');

// Public Static Pages (Halaman Statis CMS)
Route::get('/page/{slug}', [\App\Http\Controllers\PublicPageController::class, 'show'])->name('public.pages.show');

// 2. Authentication Routes (Custom Security Slug: /sugar)
Route::get('/sugar', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/sugar', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. Admin & Leadership Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // Admin Cooperation Management
    Route::get('/admin/cooperations', [AdminCooperationController::class, 'index'])->name('admin.cooperations.index');
    Route::get('/admin/cooperations/create', [AdminCooperationController::class, 'create'])->name('admin.cooperations.create');
    Route::post('/admin/cooperations', [AdminCooperationController::class, 'store'])->name('admin.cooperations.store');
    Route::get('/admin/cooperations/{id}/edit', [AdminCooperationController::class, 'edit'])->name('admin.cooperations.edit');
    Route::put('/admin/cooperations/{id}', [AdminCooperationController::class, 'update'])->name('admin.cooperations.update');
    Route::delete('/admin/cooperations/{id}', [AdminCooperationController::class, 'destroy'])->name('admin.cooperations.destroy');
    Route::patch('/admin/cooperations/{id}/toggle-public', [AdminCooperationController::class, 'togglePublic'])->name('admin.cooperations.togglePublic');
    // System Settings (Pengaturan Sistem)
    Route::get('/admin/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/admin/settings/toggle-hide-expired', [SettingController::class, 'toggleHideExpired'])->name('admin.settings.toggleHideExpired');
    Route::post('/admin/settings/update-footer', [SettingController::class, 'updateFooter'])->name('admin.settings.updateFooter');
    
    // User & Admin Account Management (Khusus Super Admin)
    Route::middleware(['super_admin'])->group(function () {
        Route::get('/admin/users', [\App\Http\Controllers\UserController::class, 'index'])->name('admin.users.index');
        Route::post('/admin/users', [\App\Http\Controllers\UserController::class, 'store'])->name('admin.users.store');
        Route::put('/admin/users/{id}', [\App\Http\Controllers\UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{id}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('admin.users.destroy');
    });

    // Navbar Menu Management (Kelola Menu Navbar)
    Route::get('/admin/nav-menus', [\App\Http\Controllers\NavMenuController::class, 'index'])->name('admin.nav_menus.index');
    Route::post('/admin/nav-menus', [\App\Http\Controllers\NavMenuController::class, 'store'])->name('admin.nav_menus.store');
    Route::put('/admin/nav-menus/{id}', [\App\Http\Controllers\NavMenuController::class, 'update'])->name('admin.nav_menus.update');
    Route::delete('/admin/nav-menus/{id}', [\App\Http\Controllers\NavMenuController::class, 'destroy'])->name('admin.nav_menus.destroy');
    Route::patch('/admin/nav-menus/{id}/toggle', [\App\Http\Controllers\NavMenuController::class, 'toggleStatus'])->name('admin.nav_menus.toggle');
    
    // CMS Posts / Berita Management
    Route::resource('admin/posts', \App\Http\Controllers\AdminPostController::class)->names('admin.posts')->except(['show']);
    Route::patch('admin/posts/{id}/toggle-publish', [\App\Http\Controllers\AdminPostController::class, 'togglePublish'])->name('admin.posts.togglePublish');

    // CMS Pages / Halaman Statis Management
    Route::resource('admin/pages', \App\Http\Controllers\AdminPageController::class)->names('admin.pages')->except(['show']);
    Route::patch('admin/pages/{id}/toggle-publish', [\App\Http\Controllers\AdminPageController::class, 'togglePublish'])->name('admin.pages.togglePublish');
    
    // Expiration Notifications
    Route::get('/admin/notifications', [AdminCooperationController::class, 'notifications'])->name('admin.notifications');
    Route::post('/admin/notifications/{id}/send-reminder', [AdminCooperationController::class, 'sendReminder'])->name('admin.notifications.sendReminder');

    // Dashboard Analysis & Accreditation Reports
    Route::get('/dashboard', [DashboardReportController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/export-excel', [DashboardReportController::class, 'exportExcel'])->name('dashboard.exportExcel');
    Route::get('/dashboard/export-pdf', [DashboardReportController::class, 'exportPdf'])->name('dashboard.exportPdf');
});
