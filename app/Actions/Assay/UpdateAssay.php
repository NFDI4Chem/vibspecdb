<?php

namespace App\Actions\Assay;

use App\Models\Assay;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UpdateAssay
{
    /**
     * Create a Assay.
     *
     * @param  array  $input
     * @return \App\Models\Assay
     */
    public function update(Assay $assay, array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'description' => [],
        ])->validate();

        return DB::transaction(function () use ($input, $assay) {
            $assay->forceFill([
            'name' => $input['name'],
            'slug' => Str::slug($input['name'], '-'),
            'description' => $input['description'],
            'color' => array_key_exists('color', $input) ? $input['color'] : $assay['color'],
            'starred'  => array_key_exists('starred', $input) ?$input['starred'] : $assay['starred'],
            'location' => array_key_exists('location', $input) ?$input['location'] : $assay['location'],
            'url'  => array_key_exists('url', $input) ?$input['url'] : $assay['url'],
            'type'  => array_key_exists('type', $input) ?$input['type'] : $assay['type'],
            'access'  => array_key_exists('access', $input) ?$input['access'] : 'restricted',
            'access_type'  => array_key_exists('access_type', $input) ? $input['access_type'] : 'viewer',
            'is_public'  => $input['is_public'],
            'assay_photo_path' => array_key_exists('assay_photo_path', $input) ? $input['assay_photo_path'] : $assay['assay_photo_path'],
            ])->save();
        });
    }
}
