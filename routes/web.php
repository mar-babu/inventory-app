<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

// report routes
Route::get('/reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
