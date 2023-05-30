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
use App\Actions\UserReport\UserReport;


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
      $STATUS_ACTION = 'succeeded';

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

      $report = new UserReport();
      $messages = [
        'alert_message' => $JOB_STATUS ? "ZIP: Uploaded ZIP-archive has been extracted." : "ZIP: Can not extract ZIP-archive.",
        'event_message' => $JOB_STATUS ? "ZIP: Uploaded ZIP-archive has been extracted." : "ZIP: Can not extract ZIP-archive."
      ];
      $data = [
        'action' => 'update_alerts',
        'status' => $STATUS_ACTION,
        'errors' => $JOB_ERRORS,
        'messages' => $messages,
        'user' => $this->user,
        'file' => $this->data,
      ];
      $report->send($data);
        
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
      $STATUS_ACTION = 'failed';

      \Log::error('Failed JOB:', ['error' => $e->getMessage()]);

      $report = new UserReport();
      $messages = [
        'alert_message' => $JOB_STATUS ? "ZIP: Uploaded ZIP-archive has been extracted." : "ZIP: Can not extract ZIP-archive.",
        'event_message' => $JOB_STATUS ? "ZIP: Uploaded ZIP-archive has been extracted." : "ZIP: Can not extract ZIP-archive."
      ];
      $data = [
        'action' => 'update_alerts',
        'status' => $STATUS_ACTION,
        'errors' => $JOB_ERRORS,
        'messages' => $messages,
        'user' => $this->user,
        'file' => $this->data,
      ];
      $report->send($data);

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
