@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mt-4">
            @include("tasks.dasboardCard")
            @can('viewAny',  App\Models\User::class)
                @include("users.dashboardCard")
            @endcan
        </div>
    </div>
@endsection
