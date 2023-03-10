<?php

namespace App\Actions\Image;

use App\Models\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UpdateImage
{
    /**
     * Create a image.
     *
     * @param  array  $input
     * @return \App\Models\Image
     */
    public function update(Image $image, array $input)
    {
        Validator::make($input, [
            'name' => ['string', 'max:255'],
            'path' => ['string', 'max:255'],
            'owner_id' => ['numeric'],
            'type' => ['string'],
            'size' => ['numeric'],
            'project_id' => ['numeric'],
            'study_id' => ['numeric'],
            'user_id' => ['numeric'],
        ])->validate();

        return DB::transaction(function () use ($input, $image) {
            $image->forceFill([
                'name' => array_key_exists('name', $input) ? $input['name'] : $image['name'],
                'path' => array_key_exists('path', $input) ? $input['path'] : $image['path'],
                'owner_id' => array_key_exists('owner_id', $input) ? $input['owner_id'] : $image['owner_id'],
                'type' => array_key_exists('type', $input) ? $input['type'] : $image['type'],
                'size' => array_key_exists('size', $input) ? $input['size'] : $image['size'],
                'project_id' => array_key_exists('project_id', $input) ? $input['project_id'] : $image['project_id'],
                'study_id' => array_key_exists('study_id', $input) ? $input['study_id'] : $image['study_id'],
                'user_id' => array_key_exists('user_id', $input) ? $input['user_id'] : $image['user_id'],
            ])->save();
        });
    }
}
