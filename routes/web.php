<?php

use App\Http\Controllers\AdminCooperationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardReportController;
use App\Http\Controllers\PublicCatalogController;
use Illuminate\Support\Facades\Route;

// 1. Public Catalog Routes (No login required)
Route::get('/', [PublicCatalogController::class, 'index'])->name('public.index');
Route::get('/cooperations/export-excel', [PublicCatalogController::class, 'exportExcel'])->name('public.exportExcel');
Route::get('/cooperations/export-pdf', [PublicCatalogController::class, 'exportPdf'])->name('public.exportPdf');
Route::get('/cooperations/{id}/detail', [PublicCatalogController::class, 'show'])->name('public.show');
Route::get('/cooperations/{id}/download', [PublicCatalogController::class, 'download'])->name('public.download');

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
    Route::post('/admin/settings/toggle-hide-expired', [AdminCooperationController::class, 'toggleHideExpired'])->name('admin.settings.toggleHideExpired');
    Route::post('/admin/settings/update-footer', [AdminCooperationController::class, 'updateFooterSetting'])->name('admin.settings.updateFooter');
    
    // Navbar Menu Management (Kelola Menu Navbar)
    Route::get('/admin/nav-menus', [\App\Http\Controllers\NavMenuController::class, 'index'])->name('admin.nav_menus.index');
    Route::post('/admin/nav-menus', [\App\Http\Controllers\NavMenuController::class, 'store'])->name('admin.nav_menus.store');
    Route::put('/admin/nav-menus/{id}', [\App\Http\Controllers\NavMenuController::class, 'update'])->name('admin.nav_menus.update');
    Route::delete('/admin/nav-menus/{id}', [\App\Http\Controllers\NavMenuController::class, 'destroy'])->name('admin.nav_menus.destroy');
    Route::patch('/admin/nav-menus/{id}/toggle', [\App\Http\Controllers\NavMenuController::class, 'toggleStatus'])->name('admin.nav_menus.toggle');
    
    // Expiration Notifications
    Route::get('/admin/notifications', [AdminCooperationController::class, 'notifications'])->name('admin.notifications');
    Route::post('/admin/notifications/{id}/send-reminder', [AdminCooperationController::class, 'sendReminder'])->name('admin.notifications.sendReminder');

    // Dashboard Analysis & Accreditation Reports
    Route::get('/dashboard', [DashboardReportController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/export-excel', [DashboardReportController::class, 'exportExcel'])->name('dashboard.exportExcel');
    Route::get('/dashboard/export-pdf', [DashboardReportController::class, 'exportPdf'])->name('dashboard.exportPdf');
});
