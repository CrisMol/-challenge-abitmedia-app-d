<?php

use App\Http\Controllers\api\v1\LicenseController;
use App\Http\Controllers\api\v1\ServiceController;
use App\Http\Controllers\api\v1\SoftwareController;
use App\Http\Controllers\auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [LoginController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function() {
    
    /**
     * Servicios
     */
    Route::resource('v1/services', ServiceController::class)->except(['create', 'edit']);

    /**
     * Licencias
     */
    Route::resource('v1/licenses', LicenseController::class)->except(['create', 'edit']);

    /**
     * Software
     */
    Route::resource('v1/software', SoftwareController::class)->except(['create', 'edit']);
});