<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

use App\Models\Study;
use App\Models\UserAlert;

// use App\Actions\FileSystem\CreateNewObject;
// use App\Actions\FileSystem\UpdateFileObject;

class UserController extends Controller
{

    public function alerts(Request $request) {
        $alerts = [];
        try {
                $alerts = auth()->user()->alerts;
                foreach ($alerts as &$alert) {
                    $study = Study::find($alert['study_id'])->only(['id', 'name']);
                    $alert['study'] = $study;
                }
        } catch (Exception $e) {
                // TODO add log here
        }

        return $request->wantsJson() ? 
            new JsonResponse($alerts, 200) : 
            back()->with('status', 'get-alerts');
    }

    public function clear_alerts(Request $request) {
        UserAlert::where('user_id', auth()->user()->id)->update(['shown' => true]);
        return $request->wantsJson() ? 
            new JsonResponse([], 200) : 
            back()->with('status', 'clear-alerts');
    }

}
