<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\BotController;
use App\Http\Controllers\PearController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'bots'], function() {
    Route::get('/', [BotController::class, 'list']);
});

Route::group(['prefix' => 'pear'], function() {
    Route::get('home/{category_id?}', [PearController::class, 'home']);
    Route::get('shoot/{slug}', [PearController::class, 'shoot']);
    Route::post('contact', [PearController::class, 'contact']);
});