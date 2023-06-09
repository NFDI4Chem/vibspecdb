<?php

namespace App\Http\Controllers\Uppy;

use App\Http\Controllers\Controller;

use Aws\S3\S3Client;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

use App\Models\FileSystemObject;
use App\Models\Project;
use App\Models\Study;

class S3MinioController extends Controller
{
    protected $client;

    protected $bucket;

    public function __construct()
    {
        $this->client = Storage::disk('s3')->getClient();

        $this->bucket = config('filesystems.disks.s3.bucket');
    }

    protected function encodeURIComponent($str)
    {
        if (!function_exists('encodeURIComponent')) {
            $revert = ['%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')', '%2F'=>'/'];

            return strtr(rawurlencode($str), $revert);
        }

        return encodeURIComponent($str);
    }

    public function createMultipartUploadOptions(Request $request)
    {
        header('Access-Control-Allow-Headers: Authorization, Content-Type, X-CSRF-TOKEN');

        return response([
            'message' => 'No content',
        ], 204);
    }

    protected function filterString(String $str) {
        return mb_convert_encoding($str, 'UTF-8', 'UTF-8');
    }

    protected function getFileAtributes(Request $request) {
        try {
            $type = $request->input('type') ?? '';
            $name = $this->filterString($request->input('filename') ?? '');
            $relativePath = $FileMetadata['path'] ?? '/';

            $FileMetadata = $request->get('metadata');
            $project = Project::find($FileMetadata['project_id'] ?? -1);
            $study = Study::find($FileMetadata['study_id'] ?? -1);

            // $environment = env('APP_ENV', 'local');
            $baseDataFolder = "UserData";
            $filePath = str_replace('//', '/', implode('/', [
                $baseDataFolder, 
                ($project->uuid ?? 'common'),
                ($study->uuid ?? 'common') . $relativePath,
                ""
            ]));

            return [
                'path' => $filePath,
                'type' => $type,
                'name' => $name,
            ];

        } catch (Throwable $exception) {
            return response()
                ->json([
                    'message' => $exception->getMessage(),
                ]);
        }
    }

    /*
        S3 Syntax:
            https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#createmultipartupload
            https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateMultipartUpload.html
            $result = $client->createMultipartUpload([
                'ACL' => 'private|public-read|public-read-write|authenticated-read|aws-exec-read|bucket-owner-read|bucket-owner-full-control',
                'Bucket' => '<string>', // REQUIRED
                'BucketKeyEnabled' => true || false,
                'CacheControl' => '<string>',
                'ContentDisposition' => '<string>',
                'ContentEncoding' => '<string>',
                'ContentLanguage' => '<string>',
                'ContentType' => '<string>',
                'ExpectedBucketOwner' => '<string>',
                'Expires' => <integer || string || DateTime>,
                'GrantFullControl' => '<string>',
                'GrantRead' => '<string>',
                'GrantReadACP' => '<string>',
                'GrantWriteACP' => '<string>',
                'Key' => '<string>', // REQUIRED
                'Metadata' => ['<string>', ...],
                'ObjectLockLegalHoldStatus' => 'ON|OFF',
                'ObjectLockMode' => 'GOVERNANCE|COMPLIANCE',
                'ObjectLockRetainUntilDate' => <integer || string || DateTime>,
                'RequestPayer' => 'requester',
                'SSECustomerAlgorithm' => '<string>',
                'SSECustomerKey' => '<string>',
                'SSECustomerKeyMD5' => '<string>',
                'SSEKMSEncryptionContext' => '<string>',
                'SSEKMSKeyId' => '<string>',
                'ServerSideEncryption' => 'AES256|aws:kms',
                'StorageClass' => 'STANDARD|REDUCED_REDUNDANCY|STANDARD_IA|ONEZONE_IA|INTELLIGENT_TIERING|GLACIER|DEEP_ARCHIVE|OUTPOSTS',
                'Tagging' => '<string>',
                'WebsiteRedirectLocation' => '<string>',
            ]);
        Called by Uppy on:
            https://github.com/transloadit/uppy/blob/master/packages/%40uppy/aws-s3-multipart/src/index.js#L78
            return this.client.post('s3/multipart', {
              filename: file.name,
              type: file.type,
              metadata
            }).then(assertServerError)
    */
    public function createMultipartUpload(Request $request)
    {

        $fileObject = $this->getFileAtributes($request);
        // return $fileObject;

        $folder = $fileObject['path'];
        $key = str_replace('//', '/', $folder.$fileObject['name']);

        try {
            $result = $this->client->createMultipartUpload([
                'Bucket'             => $this->bucket,
                'Key'                => $key,
                'ContentType'        => $fileObject['type'],
                'ContentDisposition' => 'inline',
            ]);
        } catch (Throwable $exception) {
            return response()
                ->json([
                    'message' => $exception->getMessage(),
                ]);
        }

        return response()
            ->json([
                'uploadId' => $result['UploadId'],
                'key'      => $result['Key'],
            ]);
    }

    /*
        List multipart upload parts.
        S3 Syntax:
            https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#listparts
            $result = $client->listParts([
                'Bucket' => '<string>', // REQUIRED
                'ExpectedBucketOwner' => '<string>',
                'Key' => '<string>', // REQUIRED
                'MaxParts' => <integer>,
                'PartNumberMarker' => <integer>,
                'RequestPayer' => 'requester',
                'UploadId' => '<string>', // REQUIRED
            ]);
        Called by Uppy on:
            https://github.com/transloadit/uppy/blob/master/packages/%40uppy/aws-s3-multipart/src/index.js#L96
            return this.client.get(`s3/multipart/${uploadId}?key=${filename}`)
            .then(assertServerError)
    */
    public function getUploadedParts(Request $request, $uploadId)
    {
        $key = $request->input('key');

        $parts = $this->listPartsPage($key, $uploadId, 0);

        return response()
            ->json($parts);
    }

    private function listPartsPage($key, $uploadId, $partIndex, $parts = null)
    {
        $parts = $parts ?? collect();

        $results = $this->client->listParts([
            'Bucket'           => $this->bucket,
            'Key'              => $key,
            'UploadId'         => $uploadId,
            'PartNumberMarker' => $partIndex,
        ]);

        if ($results['Parts']) {
            $parts = $parts->concat($results['Parts']);

            if ($results['IsTruncated']) {
                $results = $this->listPartsPage($key, $uploadId, $results['NextPartNumberMarker'], $parts);
                $parts = $parts->concat($results['Parts']);
            }
        }

        return $parts;
    }

    /*
        Generates a signed URL to upload a single part.
        S3 upload part:
            $result = $client->uploadPart([
                'Body' => <string || resource || Psr\Http\Message\StreamInterface>,
                'Bucket' => '<string>', // REQUIRED
                'ContentLength' => <integer>,
                'ContentSHA256' => '<string>',
                'ExpectedBucketOwner' => '<string>',
                'Key' => '<string>', // REQUIRED
                'PartNumber' => <integer>, // REQUIRED
                'RequestPayer' => 'requester',
                'SSECustomerAlgorithm' => '<string>',
                'SSECustomerKey' => '<string>',
                'SSECustomerKeyMD5' => '<string>',
                'SourceFile' => '<string>',
                'UploadId' => '<string>', // REQUIRED
            ]);
        Called by Uppy on:
            https://github.com/transloadit/uppy/blob/master/packages/%40uppy/aws-s3-multipart/src/index.js#L104
            return this.client.get(`s3/multipart/${uploadId}/batch?key=${filename}&partNumbers=${partNumbers.join(',')}`)
            .then(assertServerError)
    */
    public function prepareUploadParts(Request $request, $uploadId)
    {
        $key = $this->encodeURIComponent($request->input('key'));

        $partNumbers = explode(',', $request->input('partNumbers'));

        $presignedUrls = [];

        foreach ($partNumbers as $partNumber) {
            $command = $this->client->getCommand('uploadPart', [
                'Bucket'     => $this->bucket,
                'Key'        => $key,
                'UploadId'   => $uploadId,
                'PartNumber' => (int) $partNumber,
                'Body'       => '',
            ]);

            $presignedUrls[$partNumber] = (string) $this->client->createPresignedRequest($command, config('uppy-s3-multipart-upload.s3.presigned_url.expiry_time'))->getUri();
        }

        return response()
            ->json([
                'presignedUrls' => $presignedUrls,
            ]);
    }

    /*
        Completes a multipart upload by assembling previously uploaded parts.
        S3 Syntax:
            https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#completemultipartupload
            $result = $client->completeMultipartUpload([
                'Bucket' => '<string>', // REQUIRED
                'ExpectedBucketOwner' => '<string>',
                'Key' => '<string>', // REQUIRED
                'MultipartUpload' => [
                    'Parts' => [
                        [
                            'ETag' => '<string>',
                            'PartNumber' => <integer>,
                        ],
                        // ...
                    ],
                ],
                'RequestPayer' => 'requester',
                'UploadId' => '<string>', // REQUIRED
            ]);
        Called by Uppy on:
            https://github.com/transloadit/uppy/blob/master/packages/%40uppy/aws-s3-multipart/src/index.js#L112
            return this.client.post(`s3/multipart/${uploadIdEnc}/complete?key=${filename}`, { parts })
            .then(assertServerError)
    */
    public function completeMultipartUpload(Request $request, $uploadId)
    {
        $key = $this->encodeURIComponent($request->input('key'));

        $parts = $request->input('parts');

        // TODO: isValid Part
        // if (!Array.isArray(parts) || !parts.every(isValidPart)) {
        // return res.status(400).json({ error: 's3: `parts` must be an array of {ETag, PartNumber} objects.' })
        // }
        //
        // function isValidPart (part) {
        //     return part && typeof part === 'object' && typeof part.PartNumber === 'number' && typeof part.ETag === 'string'
        //}

        $result = $this->client->completeMultipartUpload([
            'Bucket'          => $this->bucket,
            'Key'             => $key,
            'UploadId'        => $this->encodeURIComponent($uploadId),
            'MultipartUpload' => ['Parts' => $parts],
        ]);

        $location = $result['Location'];

        return response()
            ->json([
                'location' => $location
            ]);
    }

    /*
        Aborts a multipart upload.
        S3 Syntax:
            https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#abortmultipartupload
            $result = $client->abortMultipartUpload([
                'Bucket' => '<string>', // REQUIRED
                'ExpectedBucketOwner' => '<string>',
                'Key' => '<string>', // REQUIRED
                'RequestPayer' => 'requester',
                'UploadId' => '<string>', // REQUIRED
            ]);
        Called by Uppy on:
            https://github.com/transloadit/uppy/blob/master/packages/%40uppy/aws-s3-multipart/src/index.js#L121
            return this.client.delete(`s3/multipart/${uploadIdEnc}?key=${filename}`)
            .then(assertServerError)
    */
    public function abortMultipartUpload(Request $request, $uploadId)
    {
        $key = $request->input('key');

        $result = $this->client->abortMultipartUpload([
            'Bucket'   => $this->bucket,
            'Key'      => $this->encodeURIComponent($key),
            'UploadId' => $this->encodeURIComponent($uploadId),
        ]);

        return response()
            ->json([]);
    }
}