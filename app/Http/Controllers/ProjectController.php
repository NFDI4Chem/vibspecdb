<?php

namespace App\Http\Controllers;

use App\Actions\Project\CreateProject;
use App\Actions\Project\UpdateProject;
use App\Models\Project;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Actions\ConfirmPassword;

use App\Http\Resources\ProjectResource;

class ProjectController extends Controller
{
    public function store(Request $request, CreateProject $creator)
    {
        try {
            $project = $creator->create($request->all());
            return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'project-created');
        } catch(Throwable $e) {
            return redirect()->back()->withErrors([
                'create' => 'Failed to create new project.'
            ]);
        }
    }

    public function update(Request $request, UpdateProject $updater, Project $project)
    {
        $updater->update($project, $request->all());
        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'project-updated');
    }

    public function index(Request $request, Project $project)
    {

        // return (new ProjectResource($project))->tree();
        $user = $request->user();
        $projects = Project::where('owner_id', $user->id)->get();
        $projects = collect($projects)->map(function ($project) {
            return (new ProjectResource($project))->tree();
        });

        return Inertia::render('Project/Index', [
            'project' => (new ProjectResource($project))->lite(false, ['studies']),
            'projects' => $projects
        ]);
    }

    public function settings(Request $request, Project $project)
    {
        return Inertia::render('Project/Settings', [
            'project' => $project,
            'studies' => $project->studies
        ]);
    }

    public function destroy(Request $request, Project $project)
    {

        try {
            if ( $project->owner_id !== auth()->user()->id) {
                throw ValidationException::withMessages([
                    'password' => __('You are not the project owner.'),
                ]);
            }
    
            $project->delete();
            return redirect()->route('dashboard');
        } catch(Throwable $e) {
            return redirect()->back()->withErrors([
                'create' => 'Failed to delete the project'
            ]);
        }
    }

    public function activity(Request $request, Project $project)
    {
        return response()->json(['audit' => $project->audits()->with('user')->orderBy('created_at', 'desc')->get()]);
    }
}
