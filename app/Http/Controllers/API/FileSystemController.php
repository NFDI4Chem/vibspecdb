<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FileSystemObject;
use App\Models\Study;

use Aws\S3\S3Client;
use Aws\Middleware;
use Psr\Http\Message\RequestInterface;

use App\Actions\FileSystem\CreateNewObject;
use App\Actions\FileSystem\UpdateFileObject;


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

    public function listZipArchive(Request $request, $path = '') {

        $result = $this->getFilesV2($path);
        $baseURL = implode('/', [$path, '']);

        $s3JobOutFiles = [];

        foreach (($result['Contents'] ?? []) as $file) {
            array_push($s3JobOutFiles, str_replace($baseURL, "", $file['Key']));
        }

        return $s3JobOutFiles;
    }

    private function find_object($array, $path) {
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

    private function formatTree($files, $basepath, $study, $zipfileId) {

        $tree = [];
        $id = 0;

        $creator = new CreateNewObject();
        $updater = new UpdateFileObject();


        foreach ($files as $file) {

            $tok = strtok($file, "/");
            $relative_url = '';
            $level = 1;

            while ($tok !== false) {

                $level++;
                $parent = $this->find_object($tree, $relative_url);
                $relative_url = implode('/', [$relative_url, $tok]);
                $file = $this->find_object($tree, $relative_url);

                if (empty($file)) {
                    $filedata = [
                        'id' => $id++,
                        'name' => $tok,
                        'slug' => strtolower($tok),
                        'description' => $tok,
                        'relative_url' => $relative_url,
                        'path' => $basepath . $relative_url,
                        'parent_id' => $parent['id'] ?? $zipfileId,
                        'type' => !pathinfo($relative_url, PATHINFO_EXTENSION) ? 'directory' : 'file',
                        'level' => $level,
                        'is_processed' => true,
                        'is_archived' => true,
                        'project_id' => $study->project->id ?? -1,
                        'study_id' => $study->id ?? -1,
                    ];
                    $fileObject = $creator->create($filedata);
                    $filedata['id'] = $fileObject->id;
                    $tree[] = $filedata;
                }

                $tok = strtok("/");
            }
        }

        return $tree;
    }

    public function extractzip(Request $request, $study, $fileId) {

        $archive = FileSystemObject::find($fileId);
        $studyobj = Study::with('project')->find($study ?? -1);
        $baseURL = implode('/', [$archive->path, '']);
        $filekeys = $this->listZipArchive($request, $archive->path); 

        if (!$archive || !$studyobj || !$filekeys) { abort(404); }

        $filetree = $this->formatTree($filekeys, $baseURL, $studyobj, (int)$fileId);

        return $filetree;

    }

    // TODO working as an API here may be not secure, to improve later.
    public function list(Request $request, string $jobid)
    {

        try {

            $basePath = implode('/', ['Jobs', $jobid]);

            // get Output files:
            $path = implode('/', ['Jobs', $jobid, 'Outdata']);
            $result = $this->getFiles($path, (string) $request->input('bucket'));

            $s3JobOutFiles = [];

            foreach (($result['Contents'] ?? []) as $file) {
                array_push($s3JobOutFiles, [
                    'name' => basename($file['Key']),
                    'key' => str_replace($basePath, "", $file['Key'])
                ]);
            }

            // get Log and error files:
            $pathLogs = implode('/', ['Jobs', $jobid, 'Logs']);
            $resultLogs = $this->getFiles($pathLogs, (string) $request->input('bucket'));

            $s3JobLogs = [];

            foreach (($resultLogs['Contents'] ?? []) as $file) {
                array_push($s3JobLogs, [
                    'name' => basename($file['Key']),
                    'key' => str_replace($basePath, "", $file['Key'])
                ]);
            }

            return [
                'output' => $s3JobOutFiles,
                'logs' => $s3JobLogs,
            ];
        } catch (Throwable $exception) {
            return [];
        }
    }


    public function content(Request $request)
    {

        try {

            $input = $request->all();

            $relativePath = base64_decode($input['path'] ?? '', true);

            if (!str_contains($relativePath, 'UserData')) {
                $key = implode('/', ['Jobs', ($input['jobid'] ?? '')]) . $relativePath;
            } else {
                $key = $relativePath;
            }

            $s3Client = $this->storageClient();
            $bucket = $request->input('bucket') ?: config('filesystems.disks.minio.bucket');
            
            $command = $s3Client->getCommand('GetObject', [
                'Bucket' => $bucket ,
                'Key'    => $key,
                'ResponseContentDisposition' => 'attachment'
            ]);

            $res = $s3Client->createPresignedRequest($command, '+ 5 minutes');

            return [
                'url' => (string) $res->getUri()
            ];

        } catch (Throwable $e) {
            return ['error' => $e->getMessage()];
        } catch ( \Aws\S3\Exception\S3Exception $e ) {
            return ['error' => $e->getMessage()];
        }
    }



    protected function getFiles(string $path, string $bucket = '')
    {
        try {
            $s3Client = $this->storageClient();

            $bucket = $bucket ?: config('filesystems.disks.minio.bucket');

            $command = $s3Client->getCommand('ListObjects');
            $command['Bucket'] = $bucket;
            $command['Prefix'] = $path.'/';

            $result = $s3Client->execute($command);
            return $result;
        } catch ( \Aws\S3\Exception\S3Exception $e ) {
            return [];
        }
    }


    protected function getFilesV2(string $path, string $bucket = '')
    {
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
     * Get the S3 storage client instance.
     *
     * @return \Aws\S3\S3Client
     */
    protected function storageClient()
    {
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
