@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mt-4">

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="mb-0">Tasks</h4>
                    </div>

                    <div class="card-body text-center">
                        <p class="text-muted">Manage and view your assigned tasks here.</p>
                        <a href="{{ route('tasks.index') }}" class="btn btn-primary">
                            View Tasks
                        </a>
                    </div>
                </div>
            </div>

            @can('viewAny',  App\Models\User::class)
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h4 class="mb-0">Users</h4>
                        </div>

                        <div class="card-body text-center">
                            <p class="text-muted">Manage and view your user here.</p>
                            <a href="{{ route('users.index') }}" class="btn btn-primary">
                                View Users
                            </a>
                        </div>
                    </div>
                </div>
            @endcan

        </div>
    </div>
@endsection
