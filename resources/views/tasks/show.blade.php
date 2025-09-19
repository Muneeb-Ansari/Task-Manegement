@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Task Details</h1>

    <div class="card">
        <div class="card-header">
            <strong>{{ $task->title }}</strong>
        </div>
        <div class="card-body">
            <!-- Description -->
            <p><strong>Description:</strong></p>
            <p>{{ $task->description }}</p>

            <!-- Creator -->
            <p><strong>Created By:</strong> {{ $task->creator->name ?? 'N/A' }}</p>

            <!-- Assignee -->
            <p><strong>Assigned To:</strong> {{ $task->assignee->name ?? 'Unassigned' }}</p>

            <!-- Status -->
            <p><strong>Status:</strong>
                @if($task->status === 'completed')
                    <span class="badge bg-success">Completed</span>
                @elseif($task->status === 'pending')
                    <span class="badge bg-warning">Pending</span>
                @elseif($task->status === 'in_progress')
                    <span class="badge bg-primary">In Progress</span>
                @else
                    <span class="badge bg-secondary">{{ ucfirst($task->status) }}</span>
                @endif
            </p>

            <!-- Dates -->
            <p><strong>Created At:</strong> {{ $task->created_at->format('d M Y, h:i A') }}</p>
            <p><strong>Last Updated:</strong> {{ $task->updated_at->format('d M Y, h:i A') }}</p>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back</a>

            <div>
                @can('update', $task)
                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary">Edit</a>
                @endcan

                @can('delete', $task)
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this task?')">Delete</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
