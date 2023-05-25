<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

use App\Actions\FileSystem\CreateFileObject;
use App\Actions\FileSystem\UpdateFileObject;
use App\Actions\FileSystem\ZipPreprocessing;
use App\Actions\UserReport\UserReport;

use App\Models\FileSystemObject;
use App\Models\Project;
use App\Models\Study;
use App\Http\Resources\FileSystemObjectResource;

use App\Services\RamanService;
use App\Services\ParseMetadata;

use App\Jobs\JobUnzipUpload;

class FileSystemController extends Controller
{

    // private RamanService $ramanService;

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

    public function updateFilesMeta(FileSystemObject $file) {

        try {
            
            $metadataParser = new ParseMetadata($file);
            $metadataParser->updateFilesMeta();

        } catch (Throwable $exception) {
            return redirect()->back()->withErrors([
                'create' => 'Failed to update meta data'
            ]);
        }
    }


    public function create(Request $request, CreateFileObject $creator) {
        try {
            $input = $request->all();
            $files  = isset($input['name']) ? [$input] : $input;
            $user = auth()->user() ?? null;
  
            foreach ($files as $file) {
                $fileObject = $creator->create($file);

                if ($fileObject->ftype == 'application/zip') {

                    JobUnzipUpload::dispatch($user, $fileObject)
                        ->onQueue('unzip')
                        ->delay(now()->addSeconds(0));


                    $JOB_STATUS = true;
                    $JOB_ERRORS = '';
                    $report = new UserReport();
                    $messages = [
                      'alert_message' => $JOB_STATUS ? "ZIP: Job to extract ZIP-archive has been submitted to a queue." : "ZIP: Can not submit a job (Zip extraction).",
                      'event_message' => $JOB_STATUS ? "ZIP: Job to extract ZIP-archive has been submitted to a queue." : "ZIP: Can not submit a job (Zip extraction)."
                    ];
                    $data = [
                      'action' => 'update_alerts',
                      'status' => $JOB_STATUS,
                      'errors' => $JOB_ERRORS,
                      'messages' => $messages,
                      'user' => $user,
                      'file' => $fileObject,
                    ];
                    $report->send($data);

                }
                
                // $this->extractzip($fileObject);
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

    public function update(Request $request, UpdateFileObject $updater, ParseMetadata $metadataParser, FileSystemObject $file)
    {
        $updater->update($file, $request->all());
        $metadataParser->updateParseParent($file);

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
