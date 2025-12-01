<?php

declare(strict_types=1);

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', [PageController::class, 'home'])->name('home');

// Dynamic pages (must be last to avoid catching other routes)
Route::get('/{slug}', [PageController::class, 'show'])->name('page.show');
