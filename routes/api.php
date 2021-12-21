<?php

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

Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);

Route::group([
    'prefix' => 'lists',
    'middleware' => ['auth:api']
], static function () {
    Route::get('/', [\App\Http\Controllers\Api\ListsController::class, 'list']);
    Route::post('/{?list}', [\App\Http\Controllers\Api\ListsController::class, 'store']);
    Route::delete('/{list}', [\App\Http\Controllers\Api\ListsController::class, 'delete']);
    Route::group([
        'prefix' => 'tasks',
        'middleware' => 'auth:api'
    ], static function () {
        Route::post('/{?task}', [\App\Http\Controllers\Api\ListsController::class, 'storeTask']);
        Route::delete('/{task}', [\App\Http\Controllers\Api\ListsController::class, 'deleteTask']);
    });
});
