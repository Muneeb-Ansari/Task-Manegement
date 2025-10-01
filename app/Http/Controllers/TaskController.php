<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\{StoreTaskRequest, UpdateTaskRequest};
use App\Models\{User, Task};
use App\Repositories\TaskRepository;

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
        $user = auth()->user();
        $tasks = $this->taskRepository->index($user);
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $users = User::where('role', 'user')->get();
        return view('tasks.create', compact('users'));
    }

    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();
        $validated['created_by'] = auth()->id();
        $task = $this->taskRepository->store($request, $validated);
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
    public function edit(Task $task)
    {
        //need to update this method
        $this->authorize('update', $task);
        $users = User::all();
        return view('tasks.edit', compact('task', 'users'));
    }
    public function update(UpdateTaskRequest $request, String $id)
    {
        $task =  Task::find($id);
        $this->authorize('update', $task);

        $validated = $request->validated();

        $updated = $task->update($validated);
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
