<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    // Dashboard par user ke saare projects aur unke tasks ka count dikhayega
    public function index()
    {
        $projects = Project::where('user_id', Auth::id())
            ->withCount(['tasks', 'tasks as completed_tasks_count' => function ($query) {
                $query->where('status', 'done');
            }])
            ->latest()
            ->get();

        return view('dashboard', compact('projects'));
    }

    // Naya project database mein save karega
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        $validated['user_id'] = Auth::id();
        Project::create($validated);

        return redirect()->route('dashboard')->with('success', 'New project created successfully!');
    }

    // Ek single project aur uske saare tasks dikhayega
    public function show(Project $project)
    {
        // Security Check: Koi aur user URL change karke kisi aur ka project na dekh le
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Unauthorized Access');
        }

        $project->load(['tasks' => function ($query) {
            $query->latest();
        }]);

        return view('projects.show', compact('project'));
    }

    // Project delete karne ke liye
    public function destroy(Project $project)
    {
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Unauthorized Access');
        }

        $project->delete();
        return redirect()->route('dashboard')->with('success', 'Project deleted.');
    }
}
