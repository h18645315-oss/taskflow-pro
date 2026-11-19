<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Project ke andar naya task add karega
    public function store(Request $request, Project $project)
    {
        if ($project->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',
        ]);

        $project->tasks()->create($validated);

        return back()->with('success', 'Task added!');
    }

    // Task ka status update karega (To-Do -> In Progress -> Done)
    public function updateStatus(Request $request, Task $task)
    {
        if ($task->project->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,done',
        ]);

        $task->update($validated);

        return back()->with('success', 'Task status updated!');
    }

    // Task delete karega
    public function destroy(Task $task)
    {
        if ($task->project->user_id !== Auth::id()) {
            abort(403);
        }

        $task->delete();
        return back()->with('success', 'Task removed.');
    }
}

