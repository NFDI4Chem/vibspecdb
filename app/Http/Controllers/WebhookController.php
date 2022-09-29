<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Str;
use Carbon\Carbon;

// use App\Actions\ArgoJob\CreateNewArgoJob;
use App\Actions\ArgoJob\UpdateArgoJob;

use App\Models\ArgoJob;


class WebhookController extends Controller
{
    public function webhook(Request $request, UpdateArgoJob $updater) {

        $input = $request->all();

        try {
            switch ($input['type']) {
                case 'jobstatus':

                    $job = ArgoJob::where('argo_uid', $input['uuid'])->first();
                        
                    $updater->update(
                        $job,
                        array_merge($input, ['finishedAt'  => Carbon::now()]) 
                    );
                    break;
                default:
                    break;
            }

            return response()
                ->json([
                    'message' => 'Job:' . ($input['uuid'] ?? 'undefined') . ' status updated to ' . ($input['status'] ?? 'undefined'),
                ]);

        } catch (Throwable $exception) {
            return response()
                ->json([
                    'error' => $exception->getMessage(),
                ]);
        }
    }



}
