<?php

use App\Http\Controllers\Admin\ConsoleController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\FileSystemController;
use App\Http\Controllers\Job\JobsController;
use App\Http\Controllers\Job\PodcastController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StudyController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\Uppy\AwsS3MultipartController;
use App\Models\Project;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Notifications\JobCompleted;

use App\Http\Controllers\MicroserviceController;

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

Route::supportBubble();

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function (Request $request) {
    $user = $request->user();
    $team = $user->currentTeam;
    if ($team) {
        $team->users = $team->allUsers();
    }
    $projects = Project::where('owner_id', $user->id)->where('team_id', $team->id)->get();
    return Inertia::render('Dashboard', [
        'team' => $team,
        'projects' => $projects
    ]);
})->name('dashboard');

Route::middleware(['auth:sanctum'])->get('/explorer', function (Request $request) {
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
Route::get('/jobs', [PodcastController::class, 'store']);
Route::get('/logging', [PodcastController::class, 'logging']);
Route::get('/jobs/create', [JobsController::class, 'create']);
Route::get('/micro/check', [MicroserviceController::class, 'check']);



/// project and study routes ///

Route::group(['middleware' => ['auth']], function () {
    Route::post('/storage/signed-storage-url', [FileSystemController::class, 'signedStorageURL']);

    Route::get('projects/{project}', [ProjectController::class, 'show'])
        ->name('project');
    Route::get('projects/{project}/settings', [ProjectController::class, 'settings'])
        ->name('project.settings');
    Route::delete('projects/{project}', [ProjectController::class, 'destroy'])
        ->name('project.destroy');
    Route::post('projects/create', [ProjectController::class, 'store'])
        ->name('projects.create');
    Route::put('projects/{project}/update', [ProjectController::class, 'update'])
        ->name('projects.update');
    Route::get('projects/{project}/activity', [ProjectController::class, 'activity'])
        ->name('projects.activity');
    
    Route::get('studies/{study}', [StudyController::class, 'show'])
        ->name('study');
    Route::get('studies/{study}/settings', [StudyController::class, 'settings'])
        ->name('study.settings');
    Route::delete('studies/{study}', [StudyController::class, 'destroy'])
        ->name('study.destroy');
    Route::post('studies/create', [StudyController::class, 'store'])
        ->name('studies.create');
    Route::put('studies/{study}/update', [StudyController::class, 'update'])
        ->name('studies.update');
    Route::get('studies/{study}/activity', [StudyController::class, 'activity'])
        ->name('studies.activity');
    Route::get('studies/{study}/files', [StudyController::class, 'files'])
        ->name('study.files');
    Route::get('studies/{study}/jobs', [StudyController::class, 'jobs'])
        ->name('study.jobs');
    Route::get('studies/{study}/submit-job', [StudyController::class, 'submitJob'])
        ->name('study.submit-job');
});


Route::middleware(['auth:sanctum'])->get('/explorer', function (Request $request) {
    $team = $request->user()->currentTeam;
    $team->users = $team->allUsers();
    return Inertia::render('Explorer', [
        'team' => $team
    ]);
})->name('explorer');


// admin routes
Route::group([
    'prefix' => 'admin'
], function () {
    Route::group(['middleware' => ['auth', 'permission:manage roles|view statistics|manage platform']], function () {
        Route::get('console', [ConsoleController::class, 'index'])
        ->name('console');

        Route::group(['middleware' => ['auth', 'permission:manage roles|manage platform']], function () {
            // Users
            Route::get('users', [UsersController::class, 'index'])
            ->name('users');
        
            Route::get('users/create', [UsersController::class, 'create'])
            ->name('users.create');

            Route::post('users', [UsersController::class, 'store'])
            ->name('users.store');

            Route::get('users/edit/{user}', [UsersController::class, 'edit'])
            ->name('users.edit');

            Route::put('users/edit/{user}', [UsersController::class, 'update'])
            ->name('users.update');

            Route::put('users/edit/{user}/password', [UsersController::class, 'updatePassword'])
            ->name('users.update-password');

            Route::put('users/edit/{user}/role', [UsersController::class, 'updateRole'])
            ->name('users.update-role');

            Route::delete('users/edit/{user}/photo', [UsersController::class, 'destroyPhoto'])
            ->name('users.destroy-photo');
        });
    });
});


/// job tests:
Route::get('test/email', function(){
  
	$send_mail = $user = auth()->user()->email;
  
    dispatch(new App\Jobs\SendEmailJob($send_mail));
  
    dd('send mail successfully !!');
});

Route::get('test/notify', function(){
  
    $user = auth()->user();
    $user->notify(new JobCompleted());
    dd('send mail successfully !!', $user);
});
