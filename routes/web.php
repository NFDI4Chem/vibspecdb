<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\Uppy\AwsS3MultipartController;
use App\Http\Controllers\UploadController;

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

Route::group([
    'prefix' => 'auth'
], function () {
    Route::get('/login/{service}', [SocialController::class, 'redirectToProvider']);
    Route::get('/login/{service}/callback', [SocialController::class, 'handleProviderCallback']);    
});

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function (Request $request) {
    $team = $request->user()->currentTeam;
    $team->users = $team->allUsers();
    return Inertia::render('Dashboard', [
        'team' => $team
    ]);
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/explorer', function (Request $request) {
    $team = $request->user()->currentTeam;
    $team->users = $team->allUsers();
    return Inertia::render('Explorer', [
        'team' => $team
    ]);
})->name('explorer');


// // AWS S3 Multipart Upload Routes
// Route::name('s3.multipart.')->prefix('s3/multipart')
//     ->group(function () {
//         Route::post('/', ['as' => 'createMultipartUpload', 'uses' => 'AwsS3MultipartController@createMultipartUpload']);
//         Route::get('{uploadId}', ['as' => 'getUploadedParts', 'uses' => 'AwsS3MultipartController@getUploadedParts']);
//         Route::get('{uploadId}/{partNumber}', ['as' => 'signPartUpload', 'uses' => 'AwsS3MultipartController@signPartUpload']);
//         Route::post('{uploadId}/complete', ['as' => 'completeMultipartUpload', 'uses' => 'AwsS3MultipartController@completeMultipartUpload']);
//         Route::delete('{uploadId}', ['as' => 'abortMultipartUpload', 'uses' => 'AwsS3MultipartController@abortMultipartUpload']);
//     });

Route::get('/upload', [UploadController::class, 'store']);