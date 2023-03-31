<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

use App\Models\UserSetting;

class SettingsController extends Controller
{

    public function update(Request $request) {

        $input =  $request->all();
        $user = $request->user();

        try {

            if (array_key_exists('show_project_tree', $input)) {

                $settings = UserSetting::where('user_id', $user->id)->first();
 
                if ($settings !== null) {
                    $settings->update([
                        'key' => 'show_project_tree',
                        'value' => $input['show_project_tree'] || false,
                    ]);
                } else {
                    UserSetting::create([
                        'user_id' => $user->id,
                        'key' => 'show_project_tree',
                        'value' => $input['show_project_tree'] || false,
                    ]);
                }
            }

        } catch (Exception $e) {
            return redirect()->back()->withErrors([
                'settings' => 'Failed to update user settings.'
            ]);
        }

        return back()->with('success', 'Settings has been updated.');
            
    }


}
