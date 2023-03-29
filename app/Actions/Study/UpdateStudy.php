<?php

namespace App\Actions\Study;

use App\Models\Study;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UpdateStudy
{
    /**
     * Create a study.
     *
     * @param  array  $input
     * @return \App\Models\Study
     */
    public function update(Study $study, array $input)
    {
        Validator::make($input, [
            'name' => ['string', 'max:255'],
            'description' => ['string'],
        ])->validate();

        return DB::transaction(function () use ($input, $study) {
            $study->forceFill([
                'name' => array_key_exists('name', $input) ? $input['name'] : $study['name'],
                'slug' => array_key_exists('name', $input) ? Str::slug($input['name'], '-') : $study['slug'],
                'description' => array_key_exists('description', $input) ? $input['description'] : $study['description'],
                'color' => array_key_exists('color', $input) ? $input['color'] : $study['color'],
                'starred'  => array_key_exists('starred', $input) ?$input['starred'] : $study['starred'],
                'location' => array_key_exists('location', $input) ?$input['location'] : $study['location'],
                'url'  => array_key_exists('url', $input) ?$input['url'] : $study['url'],
                'type'  => array_key_exists('type', $input) ?$input['type'] : $study['type'],
                'access'  => array_key_exists('access', $input) ?$input['access'] : 'restricted',
                'access_type'  => array_key_exists('access_type', $input) ? $input['access_type'] : 'viewer',
                'is_public'  => array_key_exists('is_public', $input) ? $input['is_public'] : $study['is_public'],
                'project_id'  => array_key_exists('project_id', $input) ? $input['project_id'] : $study['project_id'],
                'study_photo_path' => array_key_exists('study_photo_path', $input) ? $input['study_photo_path'] : $study['study_photo_path'],
            ])->save();
        });
    }
}
