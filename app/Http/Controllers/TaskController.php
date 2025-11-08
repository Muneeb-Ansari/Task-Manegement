<?php

namespace App\Http\Controllers;

use App\Http\Requests\{StoreTaskRequest, UpdateTaskRequest};
use App\Models\{User, Task};
use App\Repositories\TaskRepository;
use App\Helpers\ErrorHandler;

class TaskController extends Controller
{
    //
    public function __construct(protected TaskRepository $taskRepository)
    {
        $this->middleware('auth');
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
            return redirect()->route('tasks.index')->with('danger', 'Problem occured during task creation');
        }
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $task->load("assignee");
        $users = User::where('role', 'user')->get();

        return view('tasks.edit', compact('task', 'users'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $result = $this->taskRepository->update($request, $task);

        return redirect()
            ->route('tasks.show', $result['task'])
            ->with(
                $result['updated'] ? 'success' : 'danger',
                $result['updated']
                    ? 'Task updated successfully!'
                    : 'Task update failed.'
            );
    }


    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $deleted = $this->taskRepository->destroy($task);

        return redirect()
            ->route('tasks.index')
            ->with(
                $deleted ? 'success' : 'danger',
                $deleted ? 'Task deleted successfully!' : 'Task deletion failed.'
            );
    }
}
