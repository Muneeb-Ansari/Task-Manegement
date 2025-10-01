@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tasks List</h1>

        @can('create', App\Models\Task::class)
            <a href="{{ route('tasks.create') }}" class="btn btn-success">
                + Create Task
            </a>
        @endcan
    </div>

    @if ($tasks->count())
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Creator</th>
                    <th>Assignee</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $index => $task)
                    <tr>
                        <td>{{ $tasks->firstItem() + $index }}</td>
                        <td>{{ $task->title }}</td>
                        <td>{{ Str::limit($task->description, 50) }}</td>
                        <td>{{ $task->creator->name ?? 'N/A' }}</td>
                        <td>{{ $task->assignee->name ?? 'Unassigned' }}</td>
                        <td>
                            @if($task->status === 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($task->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($task->status) }}</span>
                            @endif
                        </td>
                        <td>{{ $task->due_date ?? '' }}</td>
                        <td>{{ $task->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-sm btn-info">View</a>
                            {{-- @can('update', $task) --}}
                                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            {{-- @endcan --}}
                            @can('delete', $task)
                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $tasks->links() }}
        </div>
    @else
        <div class="alert alert-info">
            No tasks found.
        </div>
    @endif
</div>
@endsection
