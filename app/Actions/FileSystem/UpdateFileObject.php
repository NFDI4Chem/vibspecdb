<?php

namespace App\Actions\FileSystem;

use App\Models\FileSystemObject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UpdateFileObject
{
    /**
     * Create a project.
     *
     * @param  array  $input
     * @return \App\Models\FileSystemObject
     */
    public function update(FileSystemObject $file, array $input)
    {

        return DB::transaction(function () use ($input, $file) {
            $file->forceFill([
                'type'  => $input["type"] ?? $file['type'],
                'name'  => $input["name"] ?? $file['name'],
            ])->save();
        });
    }
}
