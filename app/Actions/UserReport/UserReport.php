<?php

namespace App\Actions\UserReport;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Events\SendUserMessage;
use App\Actions\UserAlert\CreateNewUserAlert;

class UserReport
{

    // [
    //     'action' => 'update_alerts',
    //     'status' => $JOB_STATUS,
    //     'errors' => $JOB_ERRORS,
    //     'messages' => $messages,
    //     'user' => $user,
    //     'file' => $fileObject
    // ]
    public function send($data)
    {

        if (!isset($data['user']['id'])) { return; }
        if (!isset($data['status'])) { return; }
        if (!isset($data['messages'])) { return; }
        if (!isset($data['file'])) { return; }
        if (!isset($data['errors'])) { return; }
 

        $alertCreater = new CreateNewUserAlert();
        $alertCreater->create([
            'status' => $data['status'] ?? 'info',
            'user_id' => $data['user']['id'] ?? null,
            'study_id' => $data['file']['study_id'] ?? null,
            'argo_job_id' => 0,
            'text' => $data['messages']['alert_message'] ?? 'no_key_issue',
          ]);
    
          event(new SendUserMessage($data['user'], [
            'action' => $data['action'] ?? 'update_alerts',
            'type' => $data['status'] ?? 'info', # $data['status'] ? 'Success' : 'Error',
            'title' => ucfirst($data['status'] ?? 'Info'),
            "id" => (string) Str::uuid(),
            "message" => $data['messages']['event_message'] ?? 'no_key_issue',
            "errors" =>  (string) $data['errors'],
          ]));
    }
}
