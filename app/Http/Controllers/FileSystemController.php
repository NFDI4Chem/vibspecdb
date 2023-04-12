<?php

namespace App\Http\Controllers;

use App\Models\FileSystemObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

use App\Actions\FileSystem\CreateFileObject;
use App\Actions\FileSystem\UpdateFileObject;
use App\Actions\FileSystem\ZipPreprocessing;

class FileSystemController extends Controller
{
    public function create(Request $request, CreateFileObject $creator) {
        $fileObject = $creator->create($request->all());
        return $request->wantsJson() ? new JsonResponse($fileObject, 200) : back()->with('status', 'object-created');
    }

    public function extractzip(Request $request, FileSystemObject $file) {

        $zipextractor = new ZipPreprocessing();
        $updater = new UpdateFileObject();

        if ($file->ftype !== 'application/zip') {
            return [
                'status' => false,
                'error' => 'extract error, file is not application/zip type.'
            ];
        }
        
        $updater->update($file, ['type' => 'directory']);
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
