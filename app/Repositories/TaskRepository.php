<?php

namespace App\Repositories;

use App\Models\{Task, User};
use App\Events\TaskAssigned;
use App\Jobs\DispatchDueDateReminders;
use App\Helpers\ErrorHandler;
use Illuminate\Support\Facades\Storage;

class TaskRepository
{
    public function index()
    {
        $user = auth()->user();

        return Task::with(['creator', 'assignee'])
            ->when($user->role !== 'admin', fn($q) => $q->forUser($user->id))
            ->latest()
            ->paginate(10);
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
            ErrorHandler::fail($e, 'Unable to store the task');
        }
    }

    public function update($request, $task)
    {
        try {
            //code...
            $task->load('assignee');

            $validated = $request->validated();
            $assignee = User::findOrFail($validated['assigned_to']);

            if ($request->hasFile('image')) {
                if ($task->image) {
                    Storage::disk('public')->delete($task->image);
                }
                $data['image'] = $request->file('image')->store('tasks', 'public');
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
            ErrorHandler::fail($e, 'Unable to update the task');
        }
    }

    public function destroy($task)
    {
        if ($task->image) {
            Storage::disk('public')->delete($task->image);
        }

        return $task->delete();
    }
}
