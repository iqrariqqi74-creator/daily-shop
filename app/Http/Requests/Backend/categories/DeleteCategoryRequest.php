<?php

namespace App\Http\Requests\Backend\categories;

use App\Helpers\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class DeleteCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_user = $this->user();
        return PermissionHelper::checkPermission($auth_user, 'Delete Category');
    }

    protected function failedAuthorization()
    {
        throw new AccessDeniedHttpException('You are not authorized to delete a category.');
    }
}
