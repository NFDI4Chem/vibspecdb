<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

use App\Events\SendUserMessage;
// use App\Events\JobCompletedEvent;

use App\Actions\ArgoJob\SubmitArgoJob;
use App\Actions\ArgoJob\CreateNewArgoJob;
use App\Actions\UserAlert\CreateNewUserAlert;

use App\Actions\FileSystem\ExtractZip;


class JobUnzipUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $user;
    protected $job_id;

    public $tries = 3;
    public $backoff = [2, 10, 20];
    public $maxExceptions = 2;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $data)
    {
        $this->data = $data;
        $this->user = $user;
        $this->job_id = (string) strtolower(Str::random(32));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CreateNewArgoJob $creater, CreateNewUserAlert $alertCreater)
    {

      $JOB_STATUS = true;
      $JOB_ERRORS = '';
      $zip_extractor = new ExtractZip();

      $result = $zip_extractor->extract($this->data);
      $JOB_STATUS = $result['status'] ?? false;
      $JOB_ERRORS = $result['error'] ?? '';

      /*
      $job = $creater->create([
        'status' => $JOB_STATUS ? 'Running' : 'Failed',
        'errors' => $JOB_ERRORS,
        'submit_uid' => $this->job_id,
        'argo_uid' => 'unzip',
        'type' => 'unzip',
        'name' => '',
        'team_id' => $this->data['team_id'] ?? null,
        'owner_id' => $this->user->id,
        'project_id' => $this->data['project_id'] ?? null,
        'study_id' => $this->data['study_id'] ?? null,
        'input_files' => $this->data['input_files'] ?? null,
      ]);
      */

      $alertCreater->create([
        'status' => $JOB_STATUS ? 'succeeded' : 'failed',
        'user_id' => $this->user->id,
        'study_id' => $this->data['study_id'] ?? null,
        'argo_job_id' => null,
        'text' => ($JOB_STATUS ? "Uploaded ZIP-archive has been extracted." : "Can not extract ZIP-archive."),
      ]);

      event(new SendUserMessage($this->user, [
        'action' => 'update_alerts',
        'type' => $JOB_STATUS ? 'Success' : 'Error',
        'title' => $JOB_STATUS ? 'Success' : 'Error',
        "id" => (string) Str::uuid(),
        "message" => ($JOB_STATUS ? "Job to extract ZIP-archive has been submitted to a queue." : "Can not submit."),
        "errors" =>  (string) $JOB_ERRORS,
      ]));
        
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed($e)
    {

      $JOB_STATUS = false;
      $JOB_ERRORS = 'Job Error. Can not extract ZIP-archive.' . $e->getMessage();

      \Log::error('Failed JOB:', ['error' => $e->getMessage()]);

      event(new SendUserMessage($this->user, [
        'action' => 'update_alerts',
        'type' =>'Error',
        'title' => 'Error',
        "id" => (string) Str::uuid(),
        "message" => "Can not extract ZIP-archive.",
        "errors" =>  (string) $JOB_ERRORS,
      ]));

      $alertCreater = new CreateNewUserAlert();
      $alertCreater->create([
        'status' => 'failed',
        'user_id' => $this->user->id,
        'study_id' => $this->data['study_id'] ?? null,
        'argo_job_id' => null,
        'text' => "Can not extract ZIP-archive.",
      ]);

      /*
      $jobCreater = new CreateNewArgoJob();
      $job = $jobCreater->create([
        'status' => $JOB_STATUS ? 'Running' : 'Failed',
        'errors' => $JOB_ERRORS,
        'submit_uid' => $this->job_id,
        'argo_uid' => 'unzip',
        'type' => 'unzip',
        'name' => '',
        'team_id' => $this->data['team_id'] ?? null,
        'owner_id' => $this->user->id,
        'project_id' => $this->data['project_id'] ?? null,
        'study_id' => $this->data['study_id'] ?? null,
        'input_files' => $this->data['input_files'] ?? null,
      ]);
      */

    }
}
