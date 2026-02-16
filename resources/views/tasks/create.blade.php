@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Create New Task</h1>

        <!-- Task Create Form -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('tasks.store') }}" enctype="multipart/form-data" method="POST">
                    @csrf

                    <!-- Title -->
                    <div class="mb-3">
                        <x-input-label for="title">Task Title</x-input-label>
                        {{-- <label for="title" class="form-label">Task Title</label> --}}
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}"
                            required>
                        <x-input-error :messages="$errors->get('title')" class="mt-1" />
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <x-input-label for="description">Task Description</x-input-label>
                        {{-- <label for="description" class="form-label">Task Description</label> --}}
                        <textarea name="description" id="description" rows="4" class="form-control" required>{{ old('description') }}</textarea>

                        <x-input-error :messages="$errors->get('description')" class="mt-1" />
                    </div>

                    <!-- image field -->
                    <div class="mb-3">
                         <x-input-label for="image">Task Image</x-input-label>
                        {{-- <label for="image" class="form-label">Task Image</label> --}}
                        <input type="file" name="image" id="image" class="form-control">

                    </div>

                    <!-- Assignee -->
                    <div class="mb-3">
                        <x-input-label for="assigned_to">Assign To</x-input-label>
                        {{-- <label for="assigned_to" class="form-label">Assign To</label> --}}
                        <select name="assigned_to" id="assigned_to" class="form-select" required>
                            <option value="">-- Select User --</option>
                            @foreach ($users as $user)
                                @if ($user->role !== 'admin')
                                    <option value="{{ $user->id }}"
                                        {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('assigned_to')" class="mt-1" />

                    </div>

                    <!-- Priority -->
                    <div class="mb-3">
                        <x-input-label for="priority">Priority</x-input-label>
                        <select name="priority" id="priority" class="form-select" required>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="low" {{ old('priority', 'low') == 'low' ? 'selected' : '' }}>Low</option>
                        </select>
                        <x-input-error :messages="$errors->get('priority')" class="mt-1" />
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <x-input-label for="Status">Status</x-input-label>
                        {{-- <label for="status" class="form-label">Status</label> --}}
                        <select name="status" id="status" class="form-select" required>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress
                            </option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                        </select>

                        <x-input-error :messages="$errors->get('status')" class="mt-1" />
                    </div>

                    <!-- Due date -->
                    <div class="mb-3">
                        <x-input-label for="due_date">Due date</x-input-label>
                        {{-- <label for="due_date" class="form-label"> Due date</label> --}}
                        <input type="text" name="due_date" id="due_date" class="form-control"
                            value="{{ old('due_date') }}">

                        {{-- <x-input-error :messages="$errors->get('due_date')" class="mt-1" /> --}}
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-success">Create Task</button>
                    <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function() {
        $("#due_date").datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: 0,
            changeMonth: true,
            changeYear: true,
            yearRange: '2024:2030'
        });
    });
</script>
@endpush
