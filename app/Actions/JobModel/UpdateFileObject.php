<?php

namespace App\Actions\JobModel;

use App\Models\JobModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UpdateJobModel
{
    /**
     * Create a project.
     *
     * @param  array  $input
     * @return \App\Models\JobModel
     */
    public function update(JobModel $job_model, array $input)
    {

        return DB::transaction(function () use ($input, $job_model) {
            $job_model->forceFill([
                'type'  => array_key_exists('type', $input) ? $input['type'] : null,
                'description'  => array_key_exists('name', $input) ? $input['name'] : null,
            ])->save();
        });
    }
}
