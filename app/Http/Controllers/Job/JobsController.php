<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RenokiCo\PhpK8s\KubernetesCluster;
use Inertia\Inertia;

use Illuminate\Support\Str;

// use App\Actions\ArgoJob\SubmitArgoJob;
use App\Jobs\JobSubmitArgo;

// use App\Events\JobSubmitted;
// use App\Events\JobCompletedEvent;


class JobsController extends Controller
{

    public function submit(Request $request) {

        try {
            $input = $request->all();

            JobSubmitArgo::dispatch(auth()->user(), $input)
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
}
