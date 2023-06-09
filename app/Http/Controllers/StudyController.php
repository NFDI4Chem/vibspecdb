<?php

namespace App\Http\Controllers;

use App\Actions\Study\CreateStudy;
use App\Actions\Study\UpdateStudy;
use App\Models\FileSystemObject;
use App\Models\ArgoJob;
use App\Models\Study;
use App\Models\Project;
use Spatie\Tags\Tag;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use App\Http\Resources\StudyResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\FileSystemObjectResource;

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

        if (isset($params['tags']) && is_array($params['tags'])) {
            $study->syncTagsWithType($params['tags'], 'Study');
        }

        if (isset($params['metadata']) && is_array($params['metadata'])) {
            if (!$study->syncMeta($params['metadata'])) {
                return back()->withErrors(["metadata" => "Can not update metadata for Study."]);
            }
        }

        return $request->wantsJson() ? 
            new JsonResponse('', 200) : 
            back()->with('status', 'study-updated');
    }

    public function index(Request $request, Study $study)
    {

        Validator::make($request->all(), [
            'tab' => ['numeric'],
        ])->validate();

        $tab = $request->has('tab') ? $request->query('tab') : 1;

        // Old way to get a tree:
        // $tree = $this->getStudyFiles($study);

        
        // Models way to get tree:
        $study_root = FileSystemObject::where('study_id', $study->id)->where('is_root', true)->first();
        // $nfiles = FileSystemObject::where('study_id', $study->id)->count();



        $tree = [];

        $tree = !empty($study_root) ?
            (new FileSystemObjectResource($study_root))->lite(false, ['children']) : [];

        // return [
        //     'study_root' => $study_root,
        //     'count' => $nfiles,
        //     'tree' => $tree
        // ];

        $user = $request->user();
        $projects = Project::where('owner_id', $user->id)->get();
        $projects = collect($projects)->map(function ($project) {
            return (new ProjectResource($project))->tree();
        });

        return Inertia::render('Study/Index', [
            'study' => (new StudyResource($study))->lite(false, ['tags', 'metadata']),
            'project' => $study->project,
            'projects' => $projects,
            // 'files' => Inertia::lazy(fn () => [$tree]),
            'files' => fn () => [$tree],
            'tab' => $tab,
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
        try {
            if ( $study->owner_id !== auth()->user()->id) {
                throw ValidationException::withMessages([
                    'password' => __('You are not the study owner.'),
                ]);
            }
    
            $study->delete();
            // return redirect()->route('dashboard');
            return redirect()->back()->with([
                'destroy' => 'Success. Study has been deleted.'
            ]);
        } catch(Throwable $e) {
            return redirect()->back()->withErrors([
                'destroy' => 'Failed to delete the study'
            ]);
        }
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
