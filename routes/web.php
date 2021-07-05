<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Laravel ui auth
Auth::routes([
    'register' => false, // Disable Registration Routes...
    'reset' => false, // Disable Password Reset Routes...
    'verify' => false, // Disable Email Verification Routes...
]);

// Index
Route::get('/', [HomeController::class, 'index'])->name('index');

// Contact
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Admin Panel
Route::group(['prefix' => 'admin',  'middleware' => 'admin.alert'], function(){
    // Index
    Route::get('/', [AdminController::class, 'index'])->name('admin');

    // Photography
    Route::prefix('photography')->group(function(){
        // Index
        Route::get('/', [AdminController::class, 'index'])->name('admin.photography');
    });

    // Software
    Route::prefix('software')->group(function(){
        // Index
        Route::get('/', [AdminController::class, 'index'])->name('admin.software');
    });

    // Resume
    Route::prefix('resume')->group(function(){
        // Index
        Route::get('/', [AdminController::class, 'index'])->name('admin.resume');
    
    });

    // Contact
    Route::prefix('contact')->group(function(){
        // Index
        Route::get('/', [AdminController::class, 'index'])->name('admin.contact');
    });
});
