<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

use App\Actions\FileSystem\CreateFileObject;
use App\Actions\FileSystem\UpdateFileObject;
use App\Actions\FileSystem\ZipPreprocessing;

use App\Models\FileSystemObject;
use App\Models\Project;
use App\Models\Study;
use App\Http\Resources\FileSystemObjectResource;

class FileSystemController extends Controller
{

    // TODO check by content type but not extension
    public $metatypes_ext = [
        'xlsx', 
        'xls',
        'csv'
    ];

    // 2462, 2360
    public function testrun(Request $request) {

        // $fileName = 'metadata.xlsx';
        // $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        // if (in_array($ext, $this->metatypes_ext)) {

        // } 
        // 
        // 
        // 

        // $tree = FileSystemObject::findOrFail(2360)->children()->get([
        //     'id', 'name','children.id', 'children.name']);
        // return $tree;
        
        // $file = FileSystemObject::findOrFail(3668); //->with('child')->get();
        // return $file;
        // return (new FileSystemObjectResource($file))->lite(false, ['children']);

        /*
        $zipextractor = new ZipPreprocessing();
        return $zipextractor->extractzip(2884);
        */

        /*
        $root_folder = [
            'name' => '/',
            'type' => 'directory',
            'project_id' => 7,
            'study_id' => 10,
            'parent_id' => 0,
            'level' => 0,
            'is_root' => true,
        ];

        $project = [];
        $study = [];

        // $project = Project::findOrFail(7);
        // $study = Study::findOrFail(10);

        // return [
        //     'p' => $project,
        //     's' => $study,
        // ];

        $root_item = FileSystemObject::where('study_id', 10)->where('is_root', true)->get();
        $root_creator = new CreateFileObject();
        $rd = $root_creator->create($root_folder, "directory");
        return $root_item;
        */
    }



    public function create(Request $request, CreateFileObject $creator) {
        try {
            $input = $request->all();
            $files  = isset($input['name']) ? [$input] : $input;
  
            foreach ($files as $file) {
                $fileObject = $creator->create($file);
                $this->extractzip($fileObject);
                $this->checkMetafile($fileObject);
            }
        } catch (Throwable $exception) {
            return redirect()->back()->withErrors([
                'create' => 'Failed to store data'
            ]);
        }
        return back()->withSuccess('objects-created');
    }

    public function checkMetafile(FileSystemObject $file) {

        $ext = pathinfo($file->name, PATHINFO_EXTENSION);
        // $basename = basename($file->name);

        if (!in_array($ext, $this->metatypes_ext)) {
            return [
                'status' => false,
                'error' => 'extract error, file is not metadata type.'
            ];
        }
        $updater = new UpdateFileObject();
        $updater->update($file, [
            'type' => 'metafile',
        ]);
        return [
            'status' => true,
            'error' => ''
        ];
    }

    public function extractzip(FileSystemObject $file) {

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
        $zipextractor->extractzip($file->id);

        return [
            'status' => true,
            'error' => ''
        ];

    }

    public function destroy(Request $request, FileSystemObject $file)
    {
        $this->cascadeDelete($file->children);
        $file->delete();
        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'file-system-object-deleted');
    }

    private function cascadeDelete($files) {
        foreach ($files as $child) {
            $this->cascadeDelete($child->children);
            $child->delete();
        }
    }

    public function update(Request $request, UpdateFileObject $updater, FileSystemObject $file)
    {
        $updater->update($file, $request->all());
        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'file-updated');
    }

    public function download(Request $request, FileSystemObject $file) 
    { 
        $headers = [
            'Content-Type'        => 'application/jpeg',
            'Content-Disposition' => 'attachment; filename="'. $file->name .'"',
        ];
        return \Response::make(Storage::disk('s3')->get($file->path), 200, $headers);
    }


    /**
     * Get the file from S3.
     *
     * @param  string  $key , relative file path in S3
     * @param  string  $jobid , jobID 
     * @return fileContent for Download
    */
    public function downloadByPath(Request $request, string $jobid, string $key)
    {
        try {

            $basePath = implode('/', ['Jobs', $jobid ?? '']);
            $path = implode('/', [$basePath, base64_decode($key, true) ?? '']);
            $name = basename($path);

            $headers = [
                'Content-Type'        => 'application/jpeg',
                'Content-Disposition' => 'attachment; filename="'.($name ?? '') .'"',
            ];
            
            return \Response::make(Storage::disk('s3')->get($path), 200, $headers); 
        } catch (Throwable $exception) {
            return [];
        }
    }

}
