<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RenokiCo\PhpK8s\KubernetesCluster;
use Inertia\Inertia;
use Illuminate\Support\Str;

use App\Jobs\JobSubmitArgo;
use App\Events\SendUserMessage;

use App\Actions\Webhook\WebhookAuth;

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

        event(new SendUserMessage(auth()->user(), [
            'type' => 'Success',
            'title' => 'Success',
            "id" => (string) Str::uuid(),
            "message" => "Event broadcasting tested. It works. Type: {$type}",
            "errors" =>  "",
        ]));

        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'event-submitted');
    }
}
