<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Authorize request
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        $userId = $this->route('user'); // for update

        return [
            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email,'.$userId,

            'password' => $this->isMethod('post')
                ? 'required|min:6|confirmed'
                : 'nullable|min:6|confirmed',

            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role' => 'required',
            'status' => 'required|in:active,inactive',
        ];
    }

    /**
     * Custom messages (optional but pro)
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',

            'email.required' => 'Email is required',
            'email.unique' => 'Email already exists',

            'password.required' => 'Password is required',
            'password.confirmed' => 'Password does not match',

            'profile_image.image' => 'File must be an image',
        ];
    }
}
