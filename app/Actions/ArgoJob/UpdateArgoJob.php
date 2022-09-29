<?php

namespace App\Actions\ArgoJob;

use App\Models\ArgoJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UpdateArgoJob
{
    /**
     * Create a project.
     *
     * @param  array  $input
     * @return \App\Models\ArgoJob
     */
    public function update(ArgoJob $argo_job, array $input)
    {
        Validator::make($input, [
            'name' => ['string', 'max:255'],
            'argo_uid' => ['string', 'max:255'],
            'status' => ['string', 'max:255'],
            'errors' => [],
            'description' => [],
        ])->validate();

        return DB::transaction(function () use ($input, $argo_job) {
            $argo_job->forceFill([
                'name' => array_key_exists('name', $input) ? $input['name'] : null,
                'description' => array_key_exists('description', $input) ? $input['description'] : null,
                'finishedAt' => array_key_exists('finishedAt', $input) ? $input['finishedAt'] : null,
                'status' => array_key_exists('status', $input) ? $input['status'] : null,
                'errors' => array_key_exists('errors', $input) ? $input['errors'] : null,
            ])->save();
        });
    }
}
