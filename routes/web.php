<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PublicStorageController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Fallback for environments where the /public/storage symlink isn't present.
Route::get('/storage/{path}', PublicStorageController::class)->where('path', '.*');
Route::get('/media/{path}', PublicStorageController::class)->where('path', '.*')->name('media');

// Disable user self-registration; only login routes are available.
Auth::routes(['register' => false]);

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('companies/{company}/can-delete', [CompanyController::class, 'canDelete'])->name('companies.can-delete');

    Route::resource('companies', CompanyController::class);
    Route::resource('employees', EmployeeController::class);

    Route::get('/search', SearchController::class)->name('search');
});
