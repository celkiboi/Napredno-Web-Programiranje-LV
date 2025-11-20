<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        return view('projects.index', [
            'projects' => Auth::user()->managedProjects,
        ]);
    }

    public function create()
    {
        $users = User::all(); // for adding team members
        return view('projects.create', compact('users'));
    }

    public function store(Request $request)
    {
        $project = Auth::user()->managedProjects()->create($request->all());

        // attach team members
        $project->teamMembers()->attach($request->team_members);

        return redirect()->route('projects.index');
    }

    public function edit(Project $project)
    {
        $users = User::all();
        return view('projects.edit', compact('project', 'users'));
    }

    public function update(Request $request, Project $project)
    {
        if (Auth::id() !== $project->user_id) {
            // team member can update only completed_work
            $project->update([
                'completed_work' => $request->completed_work,
            ]);
            return back();
        }

        // manager can update everything
        $project->update($request->all());
        $project->teamMembers()->sync($request->team_members);

        return redirect()->route('projects.index');
    }
}

