<?php

namespace App\Http\Controllers\Job;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RenokiCo\PhpK8s\KubernetesCluster;

class JobsController extends Controller
{
    public function create(Request $request)
    {
        $production = env('APP_ENV') !== 'local';

        if (!$production) {
            return [
                'status' => false,
                'production' => $production
            ];
        }

        $cluster = KubernetesCluster::inClusterConfiguration();

        // foreach ($cluster->getAllServices() as $svc) {

        // }
        
        return [
            'status' => true,
            'production' => $production,
            'svcs' => $cluster->getAllServices()
        ];
    }
}
