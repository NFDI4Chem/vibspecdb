<?php

namespace App\Actions\Project;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UpdateProject
{
    /**
     * Create a project.
     *
     * @param  array  $input
     * @return \App\Models\Project
     */
    public function update(Project $project, array $input)
    {
        Validator::make($input, [
            'name' => ['string', 'max:255'],
            // 'description' => ['string'],
        ])->validate();

        return DB::transaction(function () use ($input, $project) {
            $project->forceFill([
                'name' => array_key_exists('name', $input) ? $input['name'] : $project['name'],
                'slug' => array_key_exists('name', $input) ? Str::slug($input['name'], '-') : $project['slug'],
                'description' => array_key_exists('description', $input) ? $input['description'] : $project['description'],
                'color' => array_key_exists('color', $input) ? $input['color'] : $project['color'],
                'starred'  => array_key_exists('starred', $input) ?$input['starred'] : $project['starred'],
                'location' => array_key_exists('location', $input) ?$input['location'] : $project['location'],
                'url'  => array_key_exists('url', $input) ?$input['url'] : $project['url'],
                'type'  => array_key_exists('type', $input) ?$input['type'] : $project['type'],
                'access'  => array_key_exists('access', $input) ?$input['access'] : 'restricted',
                'access_type'  => array_key_exists('access_type', $input) ? $input['access_type'] : 'viewer',
                'is_public'  => array_key_exists('is_public', $input) ? $input['is_public'] : $project['is_public'],
                'project_photo_path' => array_key_exists('project_photo_path', $input) ? $input['project_photo_path'] : $project['project_photo_path'],
            ])->save();
        });
    }
}
