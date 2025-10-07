@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Tasks List</h1>

            @can('create', App\Models\Task::class)
                <a href="{{ route('tasks.create') }}">
                    <x-success-button>
                        + Create Task
                    </x-success-button>
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
                        <th>Image</th>
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
                            <td>
                                @if ($task->image)
                                    <a href="{{ asset('storage/' . $task->image) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $task->image) }}" alt="Task Image" width="60"
                                            height="60" class="rounded">
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $task->creator->name ?? 'N/A' }}</td>
                            <td>{{ $task->assignee->name ?? 'Unassigned' }}</td>
                            <td>
                                @if ($task->status === 'completed')
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
                                <a href="{{ route('tasks.show', $task->id) }}">
                                    <x-info-button>
                                        View
                                    </x-info-button>
                                </a>
                                {{-- @can('update', $task) --}}
                                <a href="{{ route('tasks.edit', $task->id) }}">
                                    <x-secondary-button>
                                        Edit
                                    </x-secondary-button>
                                </a>
                                {{-- @endcan --}}
                                @can('delete', $task)
                                    <x-danger-button type="button" x-data
                                        @click="$dispatch('open-modal', 'confirm-task-delete-{{ $task->id }}')">
                                        Delete
                                    </x-danger-button>
                                    @include("tasks.delete-modal",['task'=> $task,])

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
