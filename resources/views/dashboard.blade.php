@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-6">
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

    </div>
</div>
@endsection
