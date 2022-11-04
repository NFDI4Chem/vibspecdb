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


class JobSubmitArgo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $data)
    {
        $this->data = $data;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SubmitArgoJob $submitter, CreateNewArgoJob $creater, CreateNewUserAlert $alertCreater)
    {
        $JOB_ID = (string) strtolower(Str::random(32));
        $result = $submitter->submit($JOB_ID, $this->data);

        $ARGO_UID = ($result['response']->argo_uid ?? '');
        $JOB_NAME = ($result['response']->name ?? '');
        $JOB_STATUS = ($result['response']->status ?? '') == 'submitted';
        $JOB_ERRORS = ($result['response']->errors ?? null);

        $job = $creater->create([
            'status' => $JOB_STATUS ? 'Running' : 'Failed',
            'errors' => $JOB_ERRORS,
            'submit_uid' => $JOB_ID,
            'argo_uid' => $ARGO_UID,
            'type' => 'argo',
            'name' => $JOB_NAME,
            'team_id' => $this->data['team_id'] ?? null,
            'owner_id' => $this->user->id,
            'project_id' => $this->data['project_id'] ?? null,
            'study_id' => $this->data['study_id'] ?? null,
            'input_files' => $this->data['input_files'] ?? null,
        ]);

        $alertCreater->create([
            'status' => $JOB_STATUS ? 'running' : 'failed',
            'user_id' => $this->user->id,
            'argo_job_id' => $job->id,
            'study_id' => $this->data['study_id'] ?? null
        ]);

        event(new SendUserMessage($this->user, [
            'action' => 'update_alerts',
            'type' => $JOB_STATUS ? 'Success' : 'Error',
            'title' => $JOB_STATUS ? 'Success' : 'Error',
            "id" => (string) Str::uuid(),
            "message" => ($JOB_STATUS ? "Job \"{$ARGO_UID}\" has been submitted to a queue." : "Can not submit."),
            "errors" =>  (string) $JOB_ERRORS,
        ]));
        
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        // Send user notification of failure, etc...
    }
}
