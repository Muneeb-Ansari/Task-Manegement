<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $task = $this->route('task');
        
        // Admin can update any task
        if (auth()->user()->role === 'admin') {
            return true;
        }
        
        // User can only update tasks assigned to them
        if (auth()->user()->role === 'user') {
            return true;
        }
        
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to' => 'required',
            'status' => 'required|in:pending,in_progress,completed',
            'due_date'    => 'nullable|date|after_or_equal:today',
        ];
    }
}
