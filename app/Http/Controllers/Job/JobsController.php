<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RenokiCo\PhpK8s\KubernetesCluster;
use Inertia\Inertia;

class JobsController extends Controller
{

    public function submit(Request $request) {
        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'job-submitted');
    }

    public function show(Request $request) {
        return Inertia::render('Dashboard', [
            'study' => [],
            'project' => [],
            'jobs' => []
        ]);
    }
}
