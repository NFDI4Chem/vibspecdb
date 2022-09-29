<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FileSystemObject;
use App\Models\Study;


class FileSystemController extends Controller
{
    public function children(Request $request, $study, $fileId)
    {

        $study = Study::find($study ?? -1);

        $files = FileSystemObject::with('children')->where([
            ['id', $fileId],
            ['project_id', $study->project->id ?? -1],
            ['study_id', $study->id ?? -1],
            ['is_processed', TRUE]
        ])->orderBy('type')->get();

        foreach ($files as $file) {
            $file['$droppable'] = ($file['type'] == 'directory' ? true : false);
            foreach ($file['children'] as $child) {
                $child['$droppable'] = ($child['type'] == 'directory' ? true : false);
            }
        }

        return [
            'files' => $files,
        ];
    }

}
