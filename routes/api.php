<?php

use App\Http\Controllers\API\PrankCategoryController;
use App\Http\Controllers\API\PrankIdeaController;
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

// Must Authenticated Routes
Route::group(['namespace' => 'API', 'prefix' => 'v1'], function () {
    // Prank Category Routes
    Route::group(['prefix' => 'category'], function () {
        Route::get('', [PrankCategoryController::class, 'index']);
    });

    // Prank Idea Routes
    Route::group(['prefix' => 'prank-idea'], function () {
        Route::get('', [PrankIdeaController::class, 'index']);
        Route::get('/{prankCategory}', [PrankIdeaController::class, 'category']);
    });
});
