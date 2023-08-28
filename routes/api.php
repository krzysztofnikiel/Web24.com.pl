<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompaniesController;
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


Route::group(['prefix' => 'v1',], function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth.api:sanctum')->group(function () {
        Route::get('companies', [CompaniesController::class, 'index']);
        Route::get('companies/{id}', [CompaniesController::class, 'read']);
        Route::post('companies/create', [CompaniesController::class, 'create']);
        Route::delete('companies/delete/{id}', [CompaniesController::class, 'delete']);
        Route::patch('companies/patch/{id}', [CompaniesController::class, 'patch']);
        Route::put('companies/put/{id}', [CompaniesController::class, 'put']);
    });
});
