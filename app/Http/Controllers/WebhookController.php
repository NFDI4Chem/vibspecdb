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

                    $job = ArgoJob::where([
                        'argo_uid' => $input['uuid'],
                        'owner_id' => $input['owner_id'],
                    ])->first();

                    if (!$job) {
                        return response()
                            ->json([
                                'error' => 'JOB_NOT_FOUND',
                            ]);
                    }

                    $updates = [
                        'status' => array_key_exists('status', $input) ? $input['status'] : 'Undefined',
                        'errors' => array_key_exists('errors', $input) ? $input['errors'] : null,
                        'finishedAt' => Carbon::now()
                    ];
                        
                    $updater->update(
                        $job,
                        $updates
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
