<?php

namespace App\Http\Controllers;

use App\Actions\Study\CreateStudy;
use App\Actions\Study\UpdateStudy;
use App\Models\FileSystemObject;
use App\Models\ArgoJob;
use App\Models\Study;
use Spatie\Tags\Tag;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;

use Inertia\Inertia;
use Laravel\Fortify\Actions\ConfirmPassword;
use Auth;

class StudyController extends Controller
{
    public function store(Request $request, CreateStudy $creator)
    {
        $study = $creator->create($request->all());
        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'study-created');
    }

    public function update(Request $request, UpdateStudy $updater, Study $study)
    {
        $params = $request->all();
        $updater->update($study, $params);

        if ($params['tags'] && is_array($params['tags'])) {
            $study->syncTagsWithType($params['tags'], 'Study');
        }

        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'study-updated');
    }

    public function show(Request $request, Study $study)
    {

        $study = $study->with_photo()->with_tags_translated();

        $tree = $this->getStudyFiles($study);
        return Inertia::render('Study/Content', [
            'study' => $study,
            'project' => $study->project,
            'files' => $tree
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
        return Inertia::render('Study/SubmitJob/SubmitJob', [
            'study' => $study,
            'project' => $study->project,
            // 'files' => $files
        ]);

    }

    public function fileUpload(Request $request, Study $study)
    {
        $files = $this->getStudyFiles($study);

        return Inertia::render('Study/UploadFiles/UploadFiles', [
            'study' => $study,
            'project' => $study->project,
            'files' => $files
        ]);

    }


    public function fileUploadForm(Request $request, Study $study)
    {
        return redirect(url()->previous())->with('success', 'Your Data has been updated successfully');

    }

    
    public function Jobs(Request $request, Study $study)
    {

        $jobs = ArgoJob::where([
            'study_id' => $study->id,
            'owner_id' => auth()->id() 
        ])
            ->with('files')
            ->orderBy('updated_at', 'desc')
            ->get();

        return Inertia::render('Study/Jobs/Jobs', [
            'study' => $study,
            'project' => $study->project,
            'jobs' => $jobs
        ]);

    }


    public function Models(Request $request, Study $study)
    {
        $models = [
            [
                'id' => 1,
                'name' => 'Image Negative',
                'description' => 'Creates negative view of the image',
                'href' => '#',
                'price' => '$256',
                'options' => '8 colors',
                'imageSrc' => '/imgs/models/negative.jpg',
                'disabled' => FALSE,
                'imageAlt' => 'Eight shirts arranged on table in black, olive, grey, blue, white, red, mustard, and green.',
                'input' => 'JOBID/InputData',
                'output' => 'JOBID/OutputData',
                'startscript' => 'print_r(\'test here\')',
            ],
            [
                'id' => 2,
                'name' => 'Sample model',
                'description' => 'Some sample model',
                'href' => '#',
                'price' => '$256',
                'options' => '8 colors',
                'imageSrc' => '/imgs/models/gears.jpg',
                'disabled' => TRUE,
                'imageAlt' => 'Eight shirts arranged on table in black, olive, grey, blue, white, red, mustard, and green.',
                'input' => 'JOBID/InputData',
                'output' => 'JOBID/OutputData',
                'startscript' => 'print_r(\'test here\')',
            ]
        ];

        return Inertia::render('Study/SelectModel/Models', [
            'study' => $study,
            'project' => $study->project,
            'models' => $models
        ]);

    }


    public function Files(Request $request, Study $study)
    {

        $tree = $this->getStudyFiles($study);


        return Inertia::render('Study/Files/Files', [
            'study' => $study,
            'project' => $study->project,
            'files' => $tree
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


    private function getStudyFiles($study) {
        $files = FileSystemObject::where([
            ['project_id', $study->project->id],
            ['study_id', $study->id],
            ['is_processed', TRUE]
        ])
        ->orderBy('type')->get();

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
          "type" => "directory",
          "project_id" => $study->project->id,
          "study_id" => $study->id,
          "relative_url" =>  "/",
          "level" => 0,
          "children" => []
        ];
        
        return createTree($new, array($root));
    }
}
