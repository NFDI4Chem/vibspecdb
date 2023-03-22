<?php

namespace App\Http\Controllers;

use App\Actions\Project\CreateProject;
use App\Actions\Project\UpdateProject;
use App\Models\Project;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Actions\ConfirmPassword;

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

    public function show(Request $request, Project $project)
    {

        return Inertia::render('Project/Index', [
            'project' => $project,
            'studies' => collect($project->studies)->map(function ($study) {
                return $study->with_photo();
            })
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
