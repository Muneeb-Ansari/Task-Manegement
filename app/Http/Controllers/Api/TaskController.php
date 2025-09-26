<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Task, User};

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        # code...
        if (isset($user) && $user->role === 'admin') {
            $tasks = Task::with(['creator', 'assignee'])->latest()->paginate(15);
        } else {
            $tasks = Task::with(['creator', 'assignee'])
                ->forUser($user->id)
                ->latest()
                ->paginate(15);
        }
        return response()->json([
            "data" => $tasks,
            "message" => $tasks ? 'updated successfully' : 'update failed'
        ]);
    }

    public function create()
    {
        $users = User::where('role', 'user')->get();
        return view('tasks.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to' => 'required|exists:users,id',
            'status'      => 'required|in:pending,in_progress,completed',
        ]);
        $validated['created_by'] = auth()->id();
        $status = Task::create($validated);
        return response()->json([
            "status" => $status,
        ]);
    }
    public function show(Task $task, String $id)
    {
        $tas = $task->find($id);
        return response()->json([
            "data" => $tas
        ]);
    }
    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $users = User::all();
        return view('tasks.edit', compact('task', 'users'));
    }
    public function update(Request $request, Task $task, String $id)
    {
        $this->authorize('update', $task);
        $tas = $task->find($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to' => 'required|exists:users,id',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $saved = $tas->update($validated);
        return response()->json([
            "data" => $saved,
            "message" => "udpated successfully"
        ]);
    }
    public function destroy(Task $task, $id)
    {
        $this->authorize('delete', $task);
        $delete = $task->find($id)->delete();
        return response()->json([
            "res" => $delete,
        ]);
    }
}
