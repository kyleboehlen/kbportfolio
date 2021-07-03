<?php

use Illuminate\Support\Facades\Route;

// Controllers
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

Auth::routes([
    'register' => false, // Disable Registration Routes...
    'reset' => false, // Disable Password Reset Routes...
    'verify' => false, // Disable Email Verification Routes...
]);

// Index
Route::get('/', function () {
    return view('splash');
});

// Admin home -- need to change naming here, and change home redirect
Route::get('/home', [HomeController::class, 'index'])->name('home');
