<?php

use App\Http\Controllers\Api\v1\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('api')
    ->prefix('v1')
    ->group(function () {
        Route::prefix('categories')->group(static function () {
            Route::get('/{category:slug}/', [CategoryController::class, 'show']);
            Route::get('/', [CategoryController::class, 'index']);
            Route::delete('/{category}/', [CategoryController::class, 'destroy']);
        });
    });
