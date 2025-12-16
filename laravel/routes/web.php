<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WpAutoLoginController;

Route::get('/', function () {
    return view('welcome');
});

// WordPress Auto-Login (SSO)
Route::middleware(['auth'])->group(function () {
    Route::get('/wp-redirect', [WpAutoLoginController::class, 'redirectToWordPress'])
        ->name('wp.redirect');
});

// API endpoint for WordPress to validate token (no auth required)
Route::get('/api/wp-validate-token', [WpAutoLoginController::class, 'validateToken'])
    ->name('wp.validate-token');
