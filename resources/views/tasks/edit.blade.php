@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Edit Task</h1>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Task Edit Form -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Task Title</label>
                        <input type="text" name="title" id="title" class="form-control"
                            value="{{ old('title', $task->title) }}" {{ auth()->user()->role === 'user' ? 'readonly' : '' }}
                            required>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Task Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control"
                            {{ auth()->user()->role === 'user' ? 'readonly' : '' }} required>{{ old('description', $task->description) }}
                            
                        </textarea>
                    </div>

                    <!-- Assignee -->
                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Assign To</label>
                        <select name="assigned_to" id="assigned_to" class="form-select"
                            {{ auth()->user()->role === 'user' ? 'readonly' : '' }} required>
                            <option value="{{ auth()->user()->role === 'user' ? $task->assignee->id : '' }}">
                                {{ auth()->user()->role === 'user' ? $task->assignee->name : '--Select User--' }}</option>
                            @if (auth()->user()->role === 'admin')
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>
                            <option value="in_progress"
                                {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>
                                In Progress
                            </option>
                            <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>
                        </select>
                    </div>

                    <!-- Due date -->
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="form-control"
                            value="{{ old('due_date', $task->due_date) }}" required>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-primary">Update Task</button>
                    <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>

                    @if (auth()->user()->role === 'user')
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                You can only update the status and due date of this task.
                            </small>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
