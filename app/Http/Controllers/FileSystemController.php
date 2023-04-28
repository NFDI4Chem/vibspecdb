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

use App\Services\RamanService;

class FileSystemController extends Controller
{

    private RamanService $ramanService;

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

    public function updateFilesMeta(Request $request, RamanService $ramanService, FileSystemObject $file) {
        try {
            // $input = $request->all();
            // return $file->children;
            $list = [];
            $this->cascadeExtract($file->children, $list);

            $data = $ramanService->getSpectra(['files' => $list]);
            $meta = $data['meta'] ?? [];
            $info = $data['info'] ?? [];

            $missing_metadata = $data['warnings']['metadata_missing'] ?? false;

            $updated_meta = $this->extractMetadata($meta);
            $this->extractFilesId($list, $updated_meta);


            collect($updated_meta)->map(function ($item) use ($missing_metadata) {
                if ($file = FileSystemObject::find($item['id'])) {
                    unset($item['id']);
                    if ($missing_metadata) { $item = []; }
                    if (!$file->syncMeta($item)) {
                        return back()->withErrors(
                            ["metadata" => "Can not update metadata for the file."]
                        );
                    }
                }
            });

            return back()->withSuccess('files meta updated');

        } catch (Throwable $exception) {
            return redirect()->back()->withErrors([
                'create' => 'Failed to update meta data'
            ]);
        }
    }

    private function getFileId($list, $path) {
        foreach ($list as $list_item) {
            if ($list_item['path'] == implode('/', ['',$path])) {
                return $list_item['id'];
            }
        }
        return -1;
    }

    private function extractFilesId($files, &$list) {
        foreach ($list as $key=>$list_item) {
            $list[$key]['id'] =  $this->getFileId($files, $list_item['filename']);
        }
    }

    private function extractMetadata($meta) {
        $updated_meta = [];
        foreach (array_keys($meta) as $meta_key=>$meta_item) {
            foreach ($meta[$meta_item] as $file_id=>$item) {
                $updated_meta[$file_id][$meta_item] = $item;
            }
        }
        return $updated_meta;
    }

    private function cascadeExtract($childrens, &$fileList) {
        foreach ($childrens as $child) {
            if (!in_array($child->type, ['directory', 'dataset'])) {
                if (
                    ($child->type == 'metafile' && FileSystemObject::isMetadataFile($child)) || 
                    !FileSystemObject::isMetadataFile($child)
                ) {
                    $fileList[] = [
                        'id' => $child->id,
                        'name' => $child->name,
                        'path' => $child->relative_url,
                        'src' => $child->path,
                    ];
                }
            }
            $this->cascadeExtract($child->children, $fileList);
        }
    }



    public function create(Request $request, CreateFileObject $creator) {
        try {
            $input = $request->all();
            $files  = isset($input['name']) ? [$input] : $input;
  
            foreach ($files as $file) {
                $fileObject = $creator->create($file);
                $this->extractzip($fileObject);
                FileSystemObject::addMetafile($fileObject);
            }
        } catch (Throwable $exception) {
            return redirect()->back()->withErrors([
                'create' => 'Failed to store data'
            ]);
        }
        return back()->withSuccess('objects-created');
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
