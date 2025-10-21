<?php

namespace App\Http\Controllers;

use App\Http\Requests\{StoreTaskRequest, UpdateTaskRequest};
use App\Models\{User, Task};
use App\Repositories\TaskRepository;
use App\Helpers\ErrorHandler;

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
        try {
            $tasks = $this->taskRepository->index();
            return view('tasks.index', compact('tasks'));
        } catch (\Exception $e) {
            return ErrorHandler::fail($e, 'Unable to display the task');
        }
    }

    public function create()
    {
        try {
            //code...
            $users = User::where('role', 'user')->get();
            return view('tasks.create', compact('users'));
        } catch (\Exception $e) {
            return ErrorHandler::fail($e, 'Unable to Create the task');
        }
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
        try {
            //code...
            $task = Task::find($id);
            $this->authorize('view', $task);

            return view('tasks.show', compact('task'));

        } catch (\Exception $e) {
            return ErrorHandler::fail($e, 'Unable to show the task');
        }
    }
    public function edit(String $id)
    {
        try {
            //code...
            $task = Task::with('assignee')->findOrFail($id);
            $users = User::where('role', 'user')->get();

            $this->authorize('update', $task);

            return view('tasks.edit', compact('task', 'users'));
        } catch (\Exception $e) {
            
           return ErrorHandler::fail($e, 'Unable to edit the task');
        }


    }
    public function update(UpdateTaskRequest $request, String $id)
    {
        $res = $this->taskRepository->update($request, $id);
        if ($res['updated']) {
            return redirect()->route('tasks.show', $res['task'])->with('success', 'Task updated successfully!');
        } else {
            return redirect()->route('tasks.show', $res['task'])->with('danger', 'Task not updated');
        }
    }
    public function destroy($id)
    {
        $task = Task::find( $id);
        $this->authorize('delete', $task);
        $deleted = $task->delete();
        if ($deleted) {
            return redirect()->route('tasks.index')->with('success', 'Task Deleted successfully!');
        } else {
            return redirect()->route('tasks.index')->with('success', 'Task not Deleted');
        }
    }
}
