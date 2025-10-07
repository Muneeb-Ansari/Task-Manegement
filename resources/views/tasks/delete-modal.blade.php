{{-- 2) Hidden DELETE form (submitted by modal confirm) --}}
<form id="delete-task-form-{{ $task->id }}" action="{{ route('tasks.destroy', $task->id) }}" method="POST"
    class="d-none">
    @csrf
    @method('DELETE')
</form>

{{-- 3) Confirmation modal --}}
<x-modal name="confirm-task-delete-{{ $task->id }}" maxWidth="sm" focusable>
    <div class="p-6">
        <h2 class="text-lg font-semibold">Delete Task?</h2>
        <p class="mt-2 text-sm text-gray-600">
            Are you sure you want to delete
            <span class="font-medium">“{{ $task->title }}”</span>? This action cannot be
            undone.
        </p>

        <div class="mt-6 flex justify-end gap-2">
            <x-secondary-button type="button" x-data @click="$dispatch('close')">
                Cancel
            </x-secondary-button>

            <x-danger-button type="button" x-data
                @click="document.getElementById('delete-task-form-{{ $task->id }}').submit()">
                Yes, Delete
            </x-danger-button>
        </div>
    </div>
</x-modal>
