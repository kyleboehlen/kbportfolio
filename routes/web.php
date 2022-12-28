<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BotController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PhotographyController;
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

// Spotify redirect
Route::get('spotify', [HomeController::class, 'spotify'])->name('spotify');

// Photography
Route::prefix('photography')->group(function() {
    // View show on home images
    Route::get('/', [PhotographyController::class, 'index'])->name('photography');

    // View shoot
    Route::get('shoot/{shoot}', [PhotographyController::class, 'viewShoot'])->name('photography.shoot');
});

// Software
Route::prefix('software')->group(function() {
    // View the list of software projects
    Route::get('/', [SoftwareController::class, 'projects'])->name('software');
});

// Contact
Route::prefix('contact')->group(function() {
    // Index
    Route::get('/', [HomeController::class, 'contact'])->name('contact');

    // Details
    Route::get('details', [ContactController::class, 'details'])->name('contact.details');
});

// Admin Panel
Route::group(['prefix' => 'admin',  'middleware' => 'admin.alert'], function() {
    // Index
    Route::get('/', [AdminController::class, 'index'])->name('admin');

    // Photography
    Route::prefix('photography')->group(function() {
        // Index
        Route::get('/', [AdminController::class, 'photography'])->name('admin.photography');

        // Shoots
        Route::prefix('shoots')->group(function(){
            // Add
            Route::get('add', [PhotographyController::class, 'addShoot'])->name('admin.photography.shoot.add');
            Route::post('add', [PhotographyController::class, 'storeShoot'])->name('admin.photography.shoot.store');

            // Manage
            Route::get('edit/{shoot?}', [PhotographyController::class, 'editShoot'])->name('admin.photography.shoot.edit');
            Route::post('edit/{shoot}', [PhotographyController::class, 'updateShoot'])->name('admin.photography.shoot.update');

            // Delete
            Route::post('destroy/{shoot}', [PhotographyController::class, 'destroyShoot'])->name('admin.photography.shoot.destroy');

            // Pear
            Route::get('pear/{shoot?}', [PhotographyController::class, 'editPear'])->name('admin.photography.shoot.pear');
            Route::post('pear/{shoot}', [PhotographyController::class, 'updatePear'])->name('admin.photography.shooot.toggle');
        });

        // Photos
        Route::prefix('photos')->group(function(){
            // Upload
            Route::get('upload/{shoot?}', [PhotographyController::class, 'uploadPhotos'])->name('admin.photography.photos.upload');
            Route::post('store/{shoot}', [PhotographyController::class, 'storePhoto'])->name('admin.photography.photo.store');

            // Manage
            Route::get('edit/{shoot?}', [PhotographyController::class, 'editPhotos'])->name('admin.photography.photos.edit');
            Route::post('edit/{photo}', [PhotographyController::class, 'updatePhoto'])->name('admin.photography.photo.update');

            // Delete
            Route::post('destroy/{photo}', [PhotographyController::class, 'destroyPhoto'])->name('admin.photography.photo.destroy');
        });
    });

    // Software
    Route::prefix('software')->group(function() {
        // Index
        Route::get('/', [AdminController::class, 'software'])->name('admin.software');

        // Admin add routes
        Route::get('add', [SoftwareController::class, 'add'])->name('admin.software.add');
        Route::post('add', [SoftwareController::class, 'store'])->name('admin.software.store');

        // Admin edit routes
        Route::get('edit/{project}', [SoftwareController::class, 'edit'])->name('admin.software.edit');
        Route::post('edit/{project}', [SoftwareController::class, 'update'])->name('admin.software.update');

        // Admin delete software project
        Route::post('destroy/{project}', [SoftwareController::class, 'destroy'])->name('admin.software.destroy');
    });

    // Resume
    Route::prefix('resume')->group(function() {
        // Index
        Route::get('/', [AdminController::class, 'resume'])->name('admin.resume');
    
        // View
        Route::get('view', [ResumeController::class, 'view'])->name('admin.resume.view');

        // Update
        Route::get('upload', [ResumeController::class, 'editResume'])->name('admin.resume.edit');
        Route::post('upload', [ResumeController::class, 'updateResume'])->name('admin.resume.update');
    });

    // Contact
    Route::prefix('contact')->group(function() {
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

    // Bots
    Route::prefix('bots')->group(function() {
        // Index
        Route::get('/', [AdminController::class, 'bots'])->name('admin.bots');

        // Add
        Route::get('add', [BotController::class, 'add'])->name('admin.bots.add');
        Route::post('add', [BotController::class, 'store'])->name('admin.bots.store');

        // Edit
        Route::get('edit/{bot}', [BotController::class, 'edit'])->name('admin.bots.edit');
        Route::post('edit/{bot}', [BotController::class, 'update'])->name('admin.bots.update');

        // Delete
        Route::post('destroy/{bot}', [BotController::class, 'destroy'])->name('admin.bots.destroy');
    });
});
