<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    // ->mixedCase()
                    ->numbers()
                    // ->symbols()
                    // ->uncompromised()
            ],
            'password_confirmation' => 'required|same:password',
            'role' => 'required|in:user,admin,manager'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'User name is required.',
            'name.regex' => 'Name can only contain letters and spaces.',
            'email.unique' => 'This email is already registered.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password_confirmation.same' => 'Password confirmation does not match.',
            'role.in' => 'Please select a valid role.',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'email' => strtolower($this->email),
            'name' => strip_tags($this->name)
        ]);
    }
}
