<?php

namespace App\Actions\FileSystem;

use App\Models\FileSystemObject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use Aws\S3\S3Client;
use Aws\Middleware;
use Psr\Http\Message\RequestInterface;

use App\Models\Study;

use App\Actions\FileSystem\CreateFileObject;
use App\Actions\FileSystem\UpdateFileObject;

class ZipPreprocessing
{
    /**
     * Process a Zip archive.
     *
     * @param  array  $input
     * @return \App\Models\FileSystemObject
     */

    public function extractzip($fileId) {
        
        try {
            $archive = FileSystemObject::find($fileId);
            $studyobj = Study::with('project')->find($archive->study_id ?? -1);
            $baseURL = implode('/', [$archive->path, '']);
            $filekeys = $this->listZipArchive($archive->path); 

            if (!$archive || !$studyobj || !$filekeys) { abort(404); }

            $filetree = $this->formatTree($filekeys, $baseURL, $studyobj, (int)$fileId);
            return $filetree;
        } catch (ModelNotFoundException $e) {
            throw ValidationException::withMessages([
                'not_exists' => $e->getMessage(),
            ]);
        } 
        catch(Throwable $e)
        {
            throw ValidationException::withMessages([
                'exception' => $e->getMessage(),
            ]); 
        }

    }


    public function listZipArchive($path = '') {

        $result = $this->getFilesV2($path);
        $baseURL = implode('/', [$path, '']);

        $s3JobOutFiles = [];

        foreach (($result['Contents'] ?? []) as $file) {
            array_push($s3JobOutFiles, str_replace($baseURL, "", $file['Key']));
        }

        return $s3JobOutFiles;
    }

    public function formatTree($files, $basepath, $study, $zipfileId) {

        $tree = [];
        $id = 0;

        $creator = new CreateFileObject();


        foreach ($files as $file) {

            $tok = strtok($file, "/");
            $relative_url = '';
            $level = 1;

            while ($tok !== false) {

                $level++;
                $parent = $this->find_object($tree, $relative_url);
                $relative_url = implode('/', [$relative_url, $tok]);

                $found_object = $this->find_object($tree, $relative_url);

                if (empty($found_object)) {
                    $folder = $this->is_folder(ltrim($relative_url, '/'), $files);
                    $filedata = [
                        'id' => $id++,
                        'name' => $tok,
                        'slug' => strtolower($tok),
                        'description' => $tok,
                        'relative_url' => $relative_url,
                        'path' => $basepath . ltrim($relative_url, "/"),
                        'parent_id' => $parent['id'] ?? $zipfileId,
                        'type' => $folder ? 'directory' : 'file',
                        'has_children' => $folder,
                        'level' => $level,
                        'is_processed' => true,
                        'is_archived' => true,
                        'project_id' => $study->project->id ?? -1,
                        'study_id' => $study->id ?? -1,
                    ];
                    $fileObject = $creator->create($filedata, 'zip');
                    $filedata['id'] = $fileObject->id;
                    $tree[] = $filedata;
                }

                $tok = strtok("/");
            }
        }

        return $tree;
    }

    protected function find_object($array, $path) {
        $result = null;
        foreach ($array as $object) {
            if ($object['relative_url'] === $path) {
                $result = $object;
                break;
            }
        }
        unset($object);
        return $result ?? [];
    }

    protected function getFilesV2(string $path, string $bucket = '') {

        try {
            $s3Client = $this->storageClient();

            $bucket = $bucket ?: config('filesystems.disks.minio.bucket');

            $command = $s3Client->getCommand('ListObjectsV2');

            $command->getHandlerList()->appendBuild(
                Middleware::mapRequest(function (RequestInterface $request) {
                    // Return a new request with the added header
                    return $request->withHeader('X-Minio-Extract', 'true');
                }),
                'add-header'
            );

            $command['Bucket'] = $bucket;
            $command['Prefix'] = $path.'/';

            $result = $s3Client->execute($command);
            return $result;
        } catch ( \Aws\S3\Exception\S3Exception $e ) {
            return [];
        }
    }

    /**
     * A version of in_array() that does a sub string match on $needle
     *
     * @param  mixed   $needle    The searched value
     * @param  array   $haystack  The array to search in
     * @return boolean
     */
    private function substr_in_array($needle, array $haystack)
    {
        return array_reduce($haystack, function ($inArray, $item) use ($needle) {
            return $inArray || false !== strpos($item, $needle);
        }, false);
    }

    private function is_folder($path = '', $objects = []) {
        return $this->substr_in_array($path . "/", array_diff($objects, [$path]));
    }

    protected function storageClient() {
        $config = [
            'region' => config('filesystems.disks.minio.region'),
            'version' => 'latest',
            'use_path_style_endpoint' => true,
            'url' => config('filesystems.disks.minio.endpoint'),
            'endpoint' => config('filesystems.disks.minio.endpoint'),
            'credentials' => [
                'key' => config('filesystems.disks.minio.key'),
                'secret' => config('filesystems.disks.minio.secret'),
            ],
        ];

        return S3Client::factory($config);
    }
}
