@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Users List</h1>

            @can('create', App\Models\Task::class)
                <a href="{{ route('users.create') }}">
                     <x-success-button>
                        + Create User
                    </x-success-button>
                </a>
            @endcan
        </div>

        @if ($users->count())
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created at</th>
                        <th>Updated at</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ Str::limit($user->email, 50) }}</td>
                            <td>{{ $user->role ?? 'N/A' }}</td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>{{ $user->updated_at->format('d M Y') ?? 'Unassigned' }}</td>
                            <td>
                                {{-- <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info">View</a> --}}
                                @can('update', $user)
                                    <a href="{{ route('users.edit', $user->id) }}">
                                        <x-secondary-button>
                                            Edit
                                        </x-secondary-button>
                                    </a>
                                @endcan
                                @can('delete', $user)
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <x-danger-button onclick="return confirm('Are you sure?')">
                                            Delete
                                        </x-danger-button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        @else
            <div class="alert alert-info">
                No users found.
            </div>
        @endif
    </div>
@endsection
