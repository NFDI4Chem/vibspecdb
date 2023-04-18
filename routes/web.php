<?php

use App\Http\Controllers\Admin\ConsoleController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\FileSystemController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\JobModelsController;
use App\Http\Controllers\Job\JobsController;
use App\Http\Controllers\Job\PodcastController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StudyController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\Uppy\S3MinioController;
use App\Models\Project;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Notifications\JobCompleted;

use App\Http\Middleware\WebHook;

use App\Events\JobCompletedEvent;
use App\Events\OrderEvent;
use App\Events\ServerCreated;

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

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth:sanctum'])->get('/explorer', function (Request $request) {
    $team = $request->user()->currentTeam;
    $team->users = $team->allUsers();
    return Inertia::render('Explorer', [
        'team' => $team
    ]);
})->name('explorer');

// // AWS S3 Multipart Upload Route

Route::get('/upload', [UploadController::class, 'store']);
Route::get('/jobs', [PodcastController::class, 'store']);
Route::get('/logging', [PodcastController::class, 'logging']);
// Route::get('/jobs/create', [JobsController::class, 'create']);
// Route::get('/micro/check', [MicroserviceController::class, 'check']);


Route::group(['middleware' => ['auth']], function () {
    Route::get('jobs', [JobsController::class, 'show'])
        ->name('jobs');
    Route::get('/jobs/check/{type}', [JobsController::class, 'check'])
        ->name('jobs.check');
    Route::post('/jobs/submit', [JobsController::class, 'submit'])
        ->name('jobs.submit');
});
/// project and study, assay routes ///

Route::group(['middleware' => ['auth']], function () {

    Route::get('/testrun', [FileSystemController::class, 'testrun']);

    Route::post('/storage/signed-storage-url', [FileSystemController::class, 'signedStorageURL']);

    Route::get('user/alerts', [UserController::class, 'alerts'])
    ->name('users.alerts');
    Route::get('user/clear_alerts', [UserController::class, 'clear_alerts'])
    ->name('users.clear_alerts');
    Route::put('users/settings', [SettingsController::class, 'update'])
    ->name('users.settings');

    Route::put('/image/store', [ImageUploadController::class, 'store'])
        ->name('image.store');


    Route::post('/files/create', [FileSystemController::class, 'create'])
        ->name('files.create');
    Route::delete('/files/{file}', [FileSystemController::class, 'destroy'])
        ->name('files.destroy');
    Route::get('/files/{file}', [FileSystemController::class, 'download'])
        ->name('files.downloadbyid');
    Route::get('/files/download/{jobid}/{key}', [FileSystemController::class, 'downloadByPath'])
        ->name('files.downloadbypath');
    Route::put('files/{file}/update', [FileSystemController::class, 'update'])
        ->name('files.update');
    Route::get('/extractzip/{file}', [FileSystemController::class, 'extractzip'])
        ->name('files.extractzip');

    Route::get('job_models', [JobModelsController::class, 'index'])
        ->name('job_models');
    Route::get('job_models/{job_model}', [JobModelsController::class, 'show'])
        ->name('job_model');
    Route::post('/job_models/create', [JobModelsController::class, 'create'])
        ->name('job_model.create');
    Route::delete('/job_models/{job_model}', [JobModelsController::class, 'destroy'])
        ->name('job_model.destroy');
    Route::put('job_models/{job_model}/update', [JobModelsController::class, 'update'])
        ->name('job_model.update');
    Route::get('job_models/{job_model}/settings', [JobModelsController::class, 'settings'])
        ->name('job_model.settings');
    Route::get('job_models/{job_model}/activity', [JobModelsController::class, 'activity'])
        ->name('job_model.activity');            

    Route::get('projects/{project}', [ProjectController::class, 'index'])
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
    
    Route::get('studies/{study}', [StudyController::class, 'index'])
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
    Route::get('studies/{study}/models', [StudyController::class, 'models'])
        ->name('study.models');
    Route::get('studies/{study}/file-upload', [StudyController::class, 'fileUpload'])
        ->name('study.file-upload');
    Route::post('studies/{study}/file-upload/update', [StudyController::class, 'fileUploadForm'])
        ->name('study.file-upload.update');


    Route::get('assays/{assay}', [AssayController::class, 'show'])
        ->name('assay');
    Route::get('assays/{assay}/settings', [AssayController::class, 'settings'])
        ->name('assay.settings');
    Route::delete('assays/{assay}', [AssayController::class, 'destroy'])
        ->name('assay.destroy');
    Route::post('assays/create', [AssayController::class, 'store'])
        ->name('assays.create');
    Route::put('assays/{assay}/update', [AssayController::class, 'update'])
        ->name('assays.update');
    Route::get('assays/{assay}/activity', [AssayController::class, 'activity'])
        ->name('assays.activity');
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

Route::middleware(['auth.webhook'])->group(function () {
    Route::post('service', [WebhookController::class, 'webhook']);
});

Route::get('test/notify', function(){
  
    $user = auth()->user();
    $user->notify(new JobCompleted());
    dd('send notify successfully !!', $user);
});

Route::get('test/event', function(){
  
    $user = auth()->user();
    event(new ServerCreated($user));
    event(new JobCompletedEvent());
    // broadcast(new OrderEvent('123'));
    // event(new \App\Events\JobCompletedEvent());
    dd('send event successfully !!');
});



Route::post('/s3/multipart', [S3MinioController::class, 'createMultipartUpload']);
Route::options('/s3/multipart', [S3MinioController::class, 'createMultipartUploadOptions']);
Route::get('/s3/multipart/{uploadId}', [S3MinioController::class, 'getUploadedParts']);
Route::get('/s3/multipart/{uploadId}/batch', [S3MinioController::class, 'prepareUploadParts']);
Route::post('/s3/multipart/{uploadId}/complete', [S3MinioController::class, 'completeMultipartUpload']);
Route::delete('/s3/multipart/{uploadId}', [S3MinioController::class, 'abortMultipartUpload']);
