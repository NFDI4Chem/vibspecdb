<?php

namespace App\Actions\Image;

use App\Models\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreateImage
{
    /**
     * Create a image.
     *
     * @param  array  $input
     * @return \App\Models\Image
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'path' => ['required', 'string', 'max:255'],
            'owner_id' => ['required', 'numeric'],
            'type' => ['string'],
            'size' => ['numeric'],
            'project_id' => ['numeric'],
            'study_id' => ['numeric'],
            'user_id' => ['numeric'],
        ])->validate();

        return DB::transaction(function () use ($input) {
            return tap(Image::create([
                'name' => $input['name'],
                'path' => $input['path'],
                'owner_id' => $input['owner_id'],
                'type' => array_key_exists('type', $input) ? $input['type'] : null,
                'size' => array_key_exists('size', $input) ? $input['size'] : null,
                'project_id' => array_key_exists('project_id', $input) ? $input['project_id'] : null,
                'study_id' => array_key_exists('study_id', $input) ? $input['study_id'] : null,
                'user_id' => array_key_exists('user_id', $input) ? $input['user_id'] : null,
            ]), function (Image $image) {
            });
        });
    }
}
