<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
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
            'email' => 'required|email|exists:users,email', // use your correct users table
        ];
    }

    public function messages()
    {
        return [
            'email.exists' => 'We can\'t find a user with that email address.',
        ];
    }
}
