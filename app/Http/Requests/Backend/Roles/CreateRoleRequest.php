<?php

namespace App\Http\Requests\Backend\Roles;

use App\Helpers\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CreateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_user = $this->user();
        return PermissionHelper::checkPermission($auth_user, 'Add Role');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Enter a role name',
        ];
    }

    protected function failedAuthorization()
    {
        throw new AccessDeniedHttpException('You are not authorized to add a role.');
    }
}
