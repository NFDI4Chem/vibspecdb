<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FileSystemObject;
use Illuminate\Http\Request;

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
    
    public function create(Request $request) {

        $input = $request->all();

        $folderName = "NewFolder";
        $relativeURL = $input["relative_url"] != '/' ? $input["relative_url"] . "/" . $folderName : "/" . $folderName;

        $object = FileSystemObject::create([
            "has_children" => FALSE,
            "description" => $folderName,
            "color" => "#fff",
            "\$droppable" => TRUE,
            "\$draggable" => TRUE,
            "compressionInfo" => NULL,
            "key" =>  $folderName,
            "slug" =>  strtolower($folderName),
            "parent_id" => $input["id"],
            "name" => $folderName,
            "project_id" => $input["project_id"],
            "study_id" => $input["study_id"],
            "type" => "directory",
            "relative_url" => $relativeURL,
            "level" =>(int)$input["level"] + 1,
            "owner_id" => $input["owner_id"],
            "children" => []
        ]);

        FileSystemObject::where("id",(int)$input["id"])
            ->update([
                    "id" => (int)$input["id"],
                    "has_children" => TRUE,
                ]);

        return response()->json($object);
    }

    public function delete(Request $request, $id) {
       
        $files = FileSystemObject::find($id);

        $files->children()->delete();
        $files->delete();

        return response()->json([
            'success' => TRUE
        ]);
    }
}
