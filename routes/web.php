<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\SoftwareController;

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
Route::prefix('contact')->group(function(){
    // Index
    Route::get('/', [HomeController::class, 'contact'])->name('contact');

    // Details
    Route::get('details', [ContactController::class, 'details'])->name('contact.details');
});

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
        // Admin add routes
        Route::get('add', [SoftwareController::class, 'add'])->name('admin.software.add');
        Route::post('add', [SoftwareController::class, 'store'])->name('admin.software.store');

        // Admin edit routes
        Route::get('edit/{project}', [SoftwareController::class, 'edit'])->name('admin.software.edit');
        Route::post('edit/{project}', [SoftwareController::class, 'update'])->name('admin.software.update');
    });

    // Resume
    Route::prefix('resume')->group(function(){
        // Index
        Route::get('/', [AdminController::class, 'resume'])->name('admin.resume');
    
        // View
        Route::get('view', [ResumeController::class, 'view'])->name('admin.resume.view');

        // Update
        Route::get('upload', [ResumeController::class, 'editResume'])->name('admin.resume.edit');
        Route::post('upload', [ResumeController::class, 'updateResume'])->name('admin.resume.update');
    });

    // Contact
    Route::prefix('contact')->group(function(){
        // Index
        Route::get('/', [AdminController::class, 'contact'])->name('admin.contact');

        // View contact details page
        Route::get('details', [ContactController::class, 'adminDetails'])->name('admin.contact.details');

        // Update phone number routes
        Route::get('phone-number', [ContactController::class, 'editPhoneNumber'])->name('admin.contact.edit.phone-number');
        Route::post('phone-number', [ContactController::class, 'updatePhoneNumber'])->name('admin.contact.update.phone-number');

        // Update email routes
        Route::get('email', [ContactController::class, 'editEmail'])->name('admin.contact.edit.email');
        Route::post('email', [ContactController::class, 'updateEmail'])->name('admin.contact.update.email');
    });
});