<?php

namespace App\Repositories;

use App\Models\{Task, User};
use App\Events\TaskAssigned;

class TaskRepository
{
    public function index($user)
    {
        if (isset($user) && $user->role === 'admin') {
            $tasks = Task::with(['creator', 'assignee'])->latest()->paginate(15);
        } else {
            $tasks = Task::with(['creator', 'assignee'])
                ->forUser($user->id)
                ->latest()
                ->paginate(15);
        }
        return $tasks;
    }

    public function store($request, $validated)
    {
        $task = Task::create($validated);
        $assignee = User::findOrFail($validated['assigned_to']);
        event(new TaskAssigned($task, $assignee, $request->user()));
        return $task;
    }
}
