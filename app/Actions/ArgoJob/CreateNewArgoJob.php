<?php

namespace App\Actions\ArgoJob;

use App\Models\ArgoJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CreateNewArgoJob
{
    /**
     * Create a project.
     *
     * @param  array  $input
     * @return \App\Models\ArgoJob
     */
    public function create(array $input)
    {

        $messages = [
            'name' => 'The :attribute required must be string.',
            'description' => 'The :attribute required must be string.',
            'project_id' => 'The :attribute required must be integer.',
            'study_id' => 'The :attribute required must be integer.',
            'argo_uid' => 'The :attribute required must be string.',
        ];

        Validator::make($input, [
            'name' => ['string', 'max:255'],
            'argo_uid' => ['string', 'max:255'],
            'description' => [],
            'project_id' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'study_id' => ['required', 'numeric', 'min:0', 'not_in:0'],
        ])->validate();


        return DB::transaction(function () use ($input) {
            return tap(ArgoJob::create([
                'name' => array_key_exists('name', $input) ? $input['name'] : null,
                'description' => array_key_exists('description', $input) ? $input['description'] : null,
                'argo_uid' => array_key_exists('argo_uid', $input) ? $input['argo_uid'] : null,
                'finishedAt' => array_key_exists('finishedAt', $input) ? $input['finishedAt'] : null,
                'team_id'  => array_key_exists('team_id', $input) ? $input['team_id'] : null,
                'study_id'  => $input['study_id'],
                'project_id'  => $input['project_id'],
                'owner_id'  => auth()->id(),
                'finishedAt'  => Carbon::now(),
                'submit_uid'  => Str::uuid(),
            ]), function (ArgoJob $project) {
            });
        });
    }
}
