<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\{StoreTaskRequest, UpdateTaskRequest};
use App\Models\{User, Task};
use App\Repositories\TaskRepository;
use App\Events\TaskAssigned;

class TaskController extends Controller
{
    //
    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function index()
    {
        $tasks = $this->taskRepository->index();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $users = User::where('role', 'user')->get();
        return view('tasks.create', compact('users'));
    }

    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskRepository->store($request);
        if ($task) {
            return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
        } else {
            return redirect()->route('tasks.index')->with('Danger', 'Problem occured during task creation');
        }
    }

    public function show(String $id)
    {
        $task = Task::find($id);
        $this->authorize('view', $task);
        return view('tasks.show', compact('task'));
    }
    public function edit(String $id)
    {
        $task = Task::with('assignee')->findOrFail($id);
        $users = User::where('role', 'user')->get();
        $this->authorize('update', $task);
        

        return view('tasks.edit', compact('task', 'users'));
    }
    public function update(UpdateTaskRequest $request, String $id)
    {
        $task = Task::with('assignee')->findOrFail($id);
        $this->authorize('update', $task);

        $validated = $request->validated();
        $assignee = User::findOrFail($validated['assigned_to']);

        $updated = $task->update($validated);

        if ($task->assignee->role === 'user') {
            # code...
            event(new TaskAssigned($task, $assignee, $request->user(), 'updated_by_user'));
        } else {
            # code...
            event(new TaskAssigned($task, $assignee, $request->user(), 'updated_by_admin'));
        }
        if ($updated) {
            return redirect()->route('tasks.show', $task)->with('success', 'Task updated successfully!');
        } else {
            return redirect()->route('tasks.show', $task)->with('danger', 'Task not updated');
        }
    }
    public function destroy(Task $task, $id)
    {
        $this->authorize('delete', $task);
        $deleted = $task->find($id)->delete();
        if ($deleted) {
            return redirect()->route('tasks.index')->with('success', 'Task Deleted successfully!');
        } else {
            return redirect()->route('tasks.index')->with('success', 'Task not Deleted');
        }
    }
}
