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
        <ul id="tasks-list" class="list-group mb-3">
            @foreach ($tasks as $index => $task)
                <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $task->id }}">
                    <span>
                        <strong>{{ $tasks->firstItem() + $index }}</strong>.
                        {{ $task->title }} - {{ Str::limit($task->description, 50) }}
                    </span>
                    <span>
                        <a href="{{ route('tasks.show', $task->id) }}">
                            <x-info-button>View</x-info-button>
                        </a>
                        <a href="{{ route('tasks.edit', $task->id) }}">
                            <x-secondary-button>Edit</x-secondary-button>
                        </a>
                        @can('delete', $task)
                            <x-danger-button type="button" x-data
                                @click="$dispatch('open-modal', 'confirm-task-delete-{{ $task->id }}')">
                                Delete
                            </x-danger-button>
                            @include('tasks.delete-modal', ['task' => $task])
                        @endcan
                    </span>
                </li>
            @endforeach
        </ul>

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

@push('scripts')

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
$(function () {
    $("#tasks-list").sortable({
        placeholder: "ui-state-highlight",
        update: function (event, ui) {
            let order = [];
            $("#tasks-list li").each(function (index) {
                order.push($(this).data('id'));
                $(this).find('strong').text(index + 1);
            });

            $.ajax({
                url: "{{ route('tasks.reorder') }}",
                method: "POST",
                data: {
                    tasks: order,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    console.log(response.message);
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                }
            });
        }
    });
    $("#tasks-list").disableSelection();
});
</script>
@endpush
