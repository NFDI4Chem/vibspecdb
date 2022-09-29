<?php

namespace App\Http\Controllers;

use App\Models\JobModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

use App\Actions\JobModel\CreateJobModel;
use App\Actions\JobModel\UpdateJobModel;

use Inertia\Inertia;

class JobModelsController extends Controller
{

    public function index(Request $request)
    {
        return Inertia::render('JobModel/Index', [
            'job_models' => JobModel::orderBy('project_id')
                ->get()
                ->transform(function ($job_model) {
                    return [
                        'id' => $job_model->id,
                        'uuid' => $job_model->uuid,
                        'description' => $job_model->description,
                        'team_id' => $job_model->team_id,
                        'project_id' => $job_model->project_id,
                        'study_id' => $job_model->study_id,
                        'owner' => $job_model->owner_id == auth()->user()->id,
                    ];
                }),
        ]);
    }

    public function show(Request $request, JobModel $job_model)
    {
        return Inertia::render('JobModel/Show', [
            'job_model' => $job_model
        ]);
    }

    public function create(Request $request, CreateJobModel $creator) {
        $model = $creator->create($request->all());
        return $request->wantsJson() ? new JsonResponse($model, 200) : back()->with('status', 'job-model-created');
    }

    public function destroy(Request $request, JobModel $job_model)
    {
        $job_model->delete();
        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'job-model-deleted');
    }

    public function update(Request $request, UpdateJobModel $updater, JobModel $job_model)
    {
        $updater->update($job_model, $request->all());
        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'job-model-updated');
    }
}
