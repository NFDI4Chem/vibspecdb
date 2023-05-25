<?php

namespace App\Actions\UserAlert;

use App\Models\UserAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CreateNewUserAlert
{
    /**
     * Create a UserAlert.
     *
     * @param  array  $input
     * @return \App\Models\UserAlert
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'status' => ['required', 'string', 'max:255'],
            // 'status' => Rule::in(['done', 'failed', 'running']),
            'user_id' => ['required'],
            // 'argo_job_id' => ['required'],
            'study_id' => ['required']
        ])->validate();

        return DB::transaction(function () use ($input) {
            return tap(UserAlert::create([
                'uuid'  => Str::uuid(),
                'status' => array_key_exists('status', $input) ? $input['status'] : 'undefined',
                'shown' => false,
                'user_id' => $input['user_id'] ?? null,
                'text' => $input['text'] ?? '',
                'study_id' => $input['study_id'] ?? null,
                'argo_job_id' => $input['argo_job_id'] ?? 0,
            ]), function (UserAlert $study) {
            });
        });
    }
}
