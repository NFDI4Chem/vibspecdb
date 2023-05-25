<?php

namespace App\Actions\FileSystem;

use App\Models\FileSystemObject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Actions\FileSystem\UpdateFileObject;
use App\Actions\FileSystem\ZipPreprocessing;

class ExtractZip
{
    /**
     * Create a project.
     *
     * @param  array  $input
     * @return \App\Models\FileSystemObject
     */
    public function extract(FileSystemObject $file)
    {

      if ($file->ftype !== 'application/zip') {
        return [
            'status' => false,
            'error' => 'extract error, file is not application/zip type.'
        ];
      }

      $zipextractor = new ZipPreprocessing();
      $updater = new UpdateFileObject();
      
      $fileName = pathinfo($file->name)['filename'] . ' (from archive)';
      $updater->update($file, [
          'type' => 'dataset',
          'name' => $fileName,
          'is_archived' => true,
      ]);
      $status = $zipextractor->extractzip($file->id);

      return [
          'status' => !empty($status),
          'error' => ''
      ];
    }
}
