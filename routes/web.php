<?php

use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromptController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

// Redirect / to dashboard if authenticated, otherwise let auth handle it
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer resource routes
    Route::resource('customers', CustomerController::class);

    // Prompt resource routes
    Route::resource('prompts', PromptController::class);

    // Settings routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // PDF Analysis routes
    Route::get('/analysis', [AnalysisController::class, 'index'])->name('analysis.index');
    Route::post('/analysis/analyze', [AnalysisController::class, 'analyzePdf'])->name('analysis.analyze');

    // API routes for AJAX
    Route::get('/api/customers/{customer}/prompts', [PromptController::class, 'getByCustomer'])->name('api.customers.prompts');
    Route::get('/api/prompts/{prompt}', [PromptController::class, 'getPromptContent'])->name('api.prompts.show');
    Route::get('/api/models/{provider}/versions', [SettingsController::class, 'getModelVersions'])->name('api.models.versions');
});

require __DIR__.'/auth.php';
