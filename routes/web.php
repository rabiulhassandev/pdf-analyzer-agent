<?php

use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProfileController;
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

    // PDF Analysis routes
    Route::get('/analysis', [AnalysisController::class, 'index'])->name('analysis.index');
    Route::post('/api/analysis', [AnalysisController::class, 'analyzePdfByAi'])->name('analysis.pdf');
});

require __DIR__.'/auth.php';
