<?php

namespace App\Http\Controllers;

use App\Actions\Study\CreateNewStudy;
use App\Actions\Study\UpdateStudy;
use App\Models\FileSystemObject;
use App\Models\Study;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Actions\ConfirmPassword;

class StudyController extends Controller
{
    public function store(Request $request, CreateNewStudy $creator)
    {
        $study = $creator->create($request->all());
        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'study-created');
    }

    public function update(Request $request, UpdateStudy $updater, Study $study)
    {
        $updater->update($study, $request->all());
        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'study-updated');
    }

    public function show(Request $request, Study $study)
    {
        return Inertia::render('Study/About', [
            'study' => $study,
            'project' => $study->project
        ]);
    }
    
    public function protocols(Request $request, Study $study)
    {
        return Inertia::render('Study/Protocols', [
            'study' => $study,
            'project' => $study->project
        ]);
    }

    public function assays(Request $request, Study $study)
    {
        return Inertia::render('Study/Assays', [
            'study' => $study,
            'project' => $study->project
        ]);
    }

    

    public function submitJob(Request $request, Study $study)
    {
        $files = [];

        return Inertia::render('Study/SubmitJob', [
            'study' => $study,
            'project' => $study->project,
            'files' => $files
        ]);

    }

    public function fileUpload(Request $request, Study $study)
    {
        $files = [];

        return Inertia::render('Study/UploadFiles', [
            'study' => $study,
            'project' => $study->project,
            'files' => $files
        ]);

    }

    public function Jobs(Request $request, Study $study)
    {
        $jobs = [];

        return Inertia::render('Study/Jobs', [
            'study' => $study,
            'project' => $study->project,
            'jobs' => $jobs
        ]);

    }


    public function Files(Request $request, Study $study)
    {

        // return [
        //     'files' => FileSystemObject::with('children')->where([
        //         ['level', 0],
        //         ['project_id', $study->project->id],
        //         ['study_id', $study->id]
        //     ])->orderBy('type')->get(),
        // ];

 
        $files = FileSystemObject::with('children')->where([
            // ['level', 0],
            ['project_id', $study->project->id],
            ['study_id', $study->id]
        ])->orderBy('type')->get();

        $new = array();
        foreach ($files as $a) {
          $id = $a['parent_id'] ? $a['parent_id'] : 0;

          # activate dropable:
          $a['$droppable'] = false;
          foreach ($a['children'] as $child) {
            $child['$droppable'] = ($child['type'] == 'directory' ? true : false);
          }
          $new[$id][] = $a;
          # # #
        }
        
        
        function createTree(&$list, $parent){
          $tree = array();
          foreach ($parent as $k=>$l){
              if(isset($list[$l['id']])){
                  $l['children'] = createTree($list, $list[$l['id']]);
                  $l['$droppable'] = ($l['type'] == 'directory' ? true : false);
              }
              $tree[] = $l;
          } 
          return $tree;
        }
        
        $root = [
          "id" => 0,
          "parent_id" => "",
          "name" => "/",
          "type" => "directory"
        ];
        
        $tree = createTree($new, array($root));

        return Inertia::render('Study/Files', [
            'study' => $study,
            'project' => $study->project,
            'files' => $tree
        ]);

    }

    public function MolecularIdentifications(Request $request, Study $study)
    {
        return Inertia::render('Study/MolecularIdentifications', [
            'study' => $study,
            'project' => $study->project
        ]);
    }

    public function Integrations(Request $request, Study $study)
    {
        return Inertia::render('Study/Integrations', [
            'study' => $study,
            'project' => $study->project
        ]);
    }

    public function Notifications(Request $request, Study $study)
    {
        return Inertia::render('Study/Notifications', [
            'study' => $study,
            'project' => $study->project
        ]);
    }

    public function settings(Request $request, Study $study)
    {
        return Inertia::render('Study/Settings', [
            'study' => $study,
            'project' => $study->project
        ]);
    }

    public function destroy(Request $request, StatefulGuard $guard, Study $study)
    {
        $confirmed = app(ConfirmPassword::class)(
            $guard,
            $request->user(),
            $request->password
        );

        if (! $confirmed) {
            throw ValidationException::withMessages([
                'password' => __('The password is incorrect.'),
            ]);
        }

        $study->delete();

        return redirect()->route('dashboard');
    }

    public function activity(Request $request, Study $study)
    {
        return response()->json(['audit' => $study->audits()->with('user')->orderBy('created_at', 'desc')->get()]);
    }
}
