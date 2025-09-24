@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Task</h1>

    <!-- Validation Errors -->
    {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}

    <!-- Task Edit Form -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div class="mb-3">
                    <label for="title" class="form-label">Task Title</label>
                    <input type="text" name="title" id="title" class="form-control"
                           value="{{ old('title', $user->name) }}" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" name="email" id="email" class="form-control"
                           value="{{ old('email', $user->email) }}" required>
                </div>

                <!-- password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="text" name="password" id="password" class="form-control"
                           value="{{ old('password', $user->password) }}" required>
                </div>

                <!-- role -->
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <input type="text" name="role" id="role" class="form-control"
                           value="{{ old('role', $user->role) }}" required>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary">Update Task</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
