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
        $project = $creator->create($request->all());
        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'project-created');
    }

    public function update(Request $request, UpdateProject $updater, Project $project)
    {
        $updater->update($project, $request->all());
        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'project-updated');
    }

    public function show(Request $request, Project $project)
    {
        return Inertia::render('Project/Show', [
            'project' => $project,
            'studies' => $project->studies
        ]);
    }

    public function settings(Request $request, Project $project)
    {
        return Inertia::render('Project/Settings', [
            'project' => $project,
            'studies' => $project->studies
        ]);
    }

    public function destroy(Request $request, StatefulGuard $guard, Project $project)
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

        $project->delete();

        return redirect()->route('dashboard');
    }

    public function activity(Request $request, Project $project)
    {
        return response()->json(['audit' => $project->audits()->with('user')->orderBy('created_at', 'desc')->get()]);
    }
}
