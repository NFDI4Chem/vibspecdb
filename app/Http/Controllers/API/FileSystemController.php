<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FileSystemObject;
use Illuminate\Http\Request;

class FileSystemController extends Controller
{
    public function children(Request $request, $study, $fileId)
    {
        $files = FileSystemObject::with('children')->where([
            ['id', $fileId],
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
