<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Allow all users to attempt login.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for login.
     */
     public function rules(): array
    {
        return [
            'token'    => 'required|string',
            'email'    => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

     public function messages(): array
        {
            return [
                'email.required' => 'The email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.exists' => 'We can\'t find a user with that email address.',
                'token.required' => 'The reset token is missing.',
                'password.required' => 'Please enter your new password.',
                'password.min' => 'The new password must be at least 8 characters.',
                'password.confirmed' => 'Password confirmation does not match.',
            ];
        }
}
