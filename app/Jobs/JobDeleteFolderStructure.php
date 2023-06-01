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

use App\Models\FileSystemObject;

use App\Actions\ArgoJob\SubmitArgoJob;
use App\Actions\ArgoJob\CreateNewArgoJob;
use App\Actions\UserAlert\CreateNewUserAlert;

use App\Actions\FileSystem\ExtractZip;
use App\Actions\UserReport\UserReport;

use Illuminate\Queue\Events\JobFailed;


class JobDeleteFolderStructure implements ShouldQueue
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

    private function cascadeDelete($files = []) {
      foreach ($files as $child) {
          $this->cascadeDelete($child->children);
          $child->delete();
          // TODO add remove from Minio
      }
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
      $STATUS_ACTION = 'succeeded';

      try {
        if (isset($this->data)) {
          $file = FileSystemObject::findOrFail($this->data->id);
          $this->cascadeDelete($file->children);
          $file->delete();
        }

        // TODO add remove from Minio
      } catch (Exception $e) {
        $JOB_STATUS = false;
        $JOB_ERRORS = $e->getMessage() ?? 'error_not_defined';
        $STATUS_ACTION = 'failed';
      }
      
      $report = new UserReport();
      $messages = [
        'alert_message' => $JOB_STATUS ? "Delete: the Folder structure has beed deleted." : "Delete: Can not delete folder structure.",
        'event_message' => $JOB_STATUS ? "Delete: the Folder structure has beed deleted." : "Delete: Can not delete folder structure.",
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
      $JOB_ERRORS = 'Job Error. Can not delete folder structure.' . $e->getMessage();
      $STATUS_ACTION = 'failed';

      \Log::error('Failed JOB:', ['error' => $e->getMessage()]);

      $report = new UserReport();
      $messages = [
        'alert_message' => $JOB_STATUS ? "Delete: the Folder structure has beed deleted." : "Delete: Can not delete folder structure.",
        'event_message' => $JOB_STATUS ? "Delete: the Folder structure has beed deleted." : "Delete: Can not delete folder structure.",
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
}
