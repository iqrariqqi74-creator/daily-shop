<?php

namespace App\Http\Requests\Backend\vendors;

use App\Helpers\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CreateVendorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_user = $this->user();
        return PermissionHelper::checkPermission($auth_user, 'Add Customer');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:vendors,email',
            'phone_number' => 'required|numeric|max:999999999999999',
            'address' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The vendor name is required.',
            'email.required' => 'The email address is required.',
            'phone_number.required' => 'The phone number is required.',

            'end_date_time.after' => 'The end date and time must be after the start date and time.',
            'email.unique' => 'The email address has already been taken.',
            'email.email' => 'The email address must be a valid email format.',
            'phone_number.numeric' => 'The phone number must be a valid number.',
            'phone_number.max' => 'The phone number may not be greater than 15 digits.',
        ];
    }

    protected function failedAuthorization()
    {
        throw new AccessDeniedHttpException('You are not authorized to add a user.');
    }
}