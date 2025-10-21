<?php

namespace App\Repositories;

use App\Models\{Task, User};
use App\Events\TaskAssigned;
use App\Jobs\DispatchDueDateReminders;

class TaskRepository
{
    public function index()
    {
        $user = auth()->user();
        if (isset($user) && $user->role === 'admin') {
            $tasks = Task::with(['creator', 'assignee'])->latest()->paginate(10);
        } else {
            $tasks = Task::with(['creator', 'assignee'])
                ->forUser($user->id)
                ->latest()
                ->paginate(10);
        }
        return $tasks;
    }

    public function store($request)
    {
        try {
            //code...
            $validated = $request->validated();
            $validated['created_by'] = auth()->id();

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('tasks', 'public');
            }

            $task = Task::create($validated);
            $assignee = User::findOrFail($validated['assigned_to']);

            event(new TaskAssigned($task, $assignee, $request->user(), 'created'));

            return $task;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function update($request, $id)
    {
        try {
            //code...
            $task = Task::with('assignee')->findOrFail($id);
            // $this->authorize('update', $task);

            $validated = $request->validated();
            $assignee = User::findOrFail($validated['assigned_to']);
            // $oldDueDate = $task->due_date;
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('tasks', 'public');
            }

            $updated = $task->update($validated);

            if ($task->assignee->role === 'user') {
                event(new TaskAssigned($task, $assignee, $request->user(), 'updated_by_user'));
                
                DispatchDueDateReminders::dispatch($task->id);
            } else {
                event(new TaskAssigned($task, $assignee, $request->user(), 'updated_by_admin'));
            }
            return [
                'task' => $task,
                'updated' => $updated,
            ];
        } catch (\Exception $e) {
            return $e;
        }
    }
}
