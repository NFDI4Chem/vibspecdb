<?php

namespace App\Http\Controllers;


use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;

use Inertia\Inertia;

use App\Models\Project;

use App\Http\Resources\ProjectResource;

class DashboardController extends Controller
{
    public function index(Request $request, Project $project)
    {

        $user = $request->user();
        $team = $user->currentTeam;
        if ($team) {
            $team->users = $team->allUsers();
        }
        $projects = Project::where('owner_id', $user->id)->where('team_id', $team->id)->get();
        $projects = collect($projects)->map(function ($project) {
            return (new ProjectResource($project))->tree();
        });

        // return $projects;

        return Inertia::render('Dashboard/Index', [
            'team' => $team,
            'projects' => $projects
        ]);

    }
}
