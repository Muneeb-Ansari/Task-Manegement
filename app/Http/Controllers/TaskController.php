<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;

class TaskController extends Controller
{
    //
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $tasks = Task::with(['creator', 'assignee'])->latest()->paginate(15);
        } else {
            $tasks = Task::with(['creator', 'assignee'])
                ->forUser($user->id)
                ->latest()
                ->paginate(15);
        }

        return view('tasks.index', compact('tasks'));
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
        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return view('tasks.show', compact('task'));
    }
    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $users = User::all();
        return view('tasks.edit', compact('task', 'users'));
    }
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to' => 'required|exists:users,id',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update($validated);
        return redirect()->route('tasks.show', $task)->with('success', 'Task updated successfully!');
    }
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Deleted');
    }
}
