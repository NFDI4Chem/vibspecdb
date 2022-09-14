<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\JobsController;

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

Route::prefix('v1')->group(function () {

    Route::prefix('files')->group(function () {
        Route::get('/children/{study}/{file}', [FileSystemController::class, 'children']);
        Route::post('/create', [FileSystemController::class, 'create']);
        Route::delete('/delete/{id}', [FileSystemController::class, 'delete']);
    });

    Route::prefix('jobs')->group(function () {
        Route::get('/check/{type}', [JobsController::class, 'check']);
    });


});

# sanctum api route:
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->get('/info', function (Request $request) {
    return "Hello world";
});
