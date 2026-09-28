<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\SocialLinkController;
use App\Http\Controllers\AppearanceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\LinkClickController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard (or login if guest)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// User Dashboard & Management (Requires Auth)
Route::middleware(['auth', 'active'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        // Profile Editor
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
        
        // Links Manager
        Route::get('/links', [LinkController::class, 'index'])->name('links');
        Route::post('/links', [LinkController::class, 'store'])->name('links.store');
        Route::patch('/links/{link}', [LinkController::class, 'update'])->name('links.update');
        Route::patch('/links/{link}/toggle', [LinkController::class, 'toggle'])->name('links.toggle');
        Route::delete('/links/{link}', [LinkController::class, 'destroy'])->name('links.destroy');
        Route::post('/links/reorder', [LinkController::class, 'reorder'])->name('links.reorder');
        
        // Social Links Editor
        Route::get('/social', [SocialLinkController::class, 'index'])->name('social');
        Route::post('/social', [SocialLinkController::class, 'store'])->name('social.store');
        Route::delete('/social/{socialLink}', [SocialLinkController::class, 'destroy'])->name('social.destroy');
        
        // Appearance
        Route::get('/appearance', [AppearanceController::class, 'edit'])->name('appearance');
        Route::post('/appearance', [AppearanceController::class, 'update'])->name('appearance.update');
        
        // Settings
        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
        
        // Analytics
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    });
});

// Admin Panel
Route::middleware(['auth', 'active', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('users', AdminUserController::class)->except(['show']);
    Route::patch('users/{user}/status', [AdminUserController::class, 'toggleStatus'])->name('users.status');
});

require __DIR__.'/auth.php';

// Public Routes
Route::get('/l/{link}', [LinkClickController::class, 'redirect'])->name('link.redirect');
Route::get('/{username}', [PublicProfileController::class, 'show'])
    ->where('username', '[A-Za-z0-9\-_]+')
    ->name('public.profile');
