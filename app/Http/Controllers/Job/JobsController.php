<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RenokiCo\PhpK8s\KubernetesCluster;
use Inertia\Inertia;
use Illuminate\Support\Str;

use App\Jobs\JobSubmitArgo;
use App\Jobs\JobUnzipUpload;
use App\Events\SendUserMessage;

use App\Actions\Webhook\WebhookAuth;
use App\Actions\FileSystem\ExtractZip;

use App\Models\ArgoJob;
use App\Models\FileSystemObject;

class JobsController extends Controller
{

    public function submit(Request $request) {

        try {
            $input = $request->all();

            $hookAuth = new WebhookAuth();
            $authToken = $hookAuth->encrypt([
                'type' => 'jobstatus',
                'owner_id' => auth()->id(),
            ]);

            $data = array_merge(
                $input, ['token' => $authToken]
            );

            JobSubmitArgo::dispatch(auth()->user(), $data)
                ->onQueue('jobs')
                ->delay(now()->addSeconds(0));


        } catch (Throwable $exception) {
            return response()
                ->json([
                    'error' => $exception->getMessage(),
                ]);
        }

        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'job-submitted');
        // return redirect()->back()->with('notification', [
        //     'type' => 'Success',
        //     'title' => 'Success',
        //     'message'=> 'The Job has been submitted',
        // ]);
    }

    public function show(Request $request) {
        return Inertia::render('Dashboard', [
            'study' => [],
            'project' => [],
            'jobs' => []
        ]);
    }

    public function check(Request $request, string $type) {

        /*
        $job = ArgoJob::find(26);
        $job->files()->sync([283,284]);
        return $job;
        */

        /*
        $job = ArgoJob::find(26);
        $job->files()->sync([283,284]);
        return $job;
        */

        /*
        $file = FileSystemObject::find(283);
        // $file->jobs()->sync([26]);
        return $file->jobs;
        */

        event(new SendUserMessage(auth()->user(), [
            'type' => 'Success',
            'title' => 'Success',
            "id" => (string) Str::uuid(),
            "message" => "Event broadcasting tested. It works. Type: {$type}",
            "errors" =>  "",
        ]));

        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'event-submitted');
    }

    private function cascadeDelete($files = []) {
        foreach ($files as $child) {
            $this->cascadeDelete($child->children);
            $child->forceDelete();
            // TODO add remove from Minio
        }
      }

    public function ziprunner(Request $request) {


        $files = FileSystemObject::onlyTrashed()
            ->where('deleted_at', '<=', now()->subWeek())->get();   
            
        collect($files)->map(function ($file) {
            $this->cascadeDelete($file->children);
            $file->forceDelete();
        });

        return [
            'data' => $files,
        ];
        
        $type = 'success';

        event(new SendUserMessage(auth()->user(), [
            'type' => 'Success',
            'title' => 'Success',
            "id" => (string) Str::uuid(),
            "message" => "Event broadcasting tested. It works. Type: {$type}",
            "errors" =>  "",
        ]));

        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'event-submitted');
        
        /*
        $zip_extractor = new ExtractZip();
        $file = FileSystemObject::where('id', 723)->get()->first();
        $files = FileSystemObject::where('id', 723)->get();
        $zip_extractor->extract($file);

        return $files;

        try {
            $input = $request->all();
            $files  = isset($input['name']) ? [$input] : $input;
  
            foreach ($files as $file) {
                $fileObject = $creator->create($file);
                // $this->extractzip($fileObject);

                JobUnzipUpload::dispatch(auth()->user(), $file)
                    ->onQueue('unzip')
                    ->delay(now()->addSeconds(0));

                FileSystemObject::addMetafile($fileObject);
            }
        } catch (Throwable $exception) {
            return redirect()->back()->withErrors([
                'create' => 'Failed to store data'
            ]);
        }
        return back()->withSuccess('objects-created');

        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'event-submitted');
        */

    }

}
