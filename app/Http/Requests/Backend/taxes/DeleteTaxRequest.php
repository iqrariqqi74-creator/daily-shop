<?php

namespace App\Http\Requests\Backend\taxes;

use App\Helpers\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class DeleteTaxRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_user = $this->user();
        return PermissionHelper::checkPermission($auth_user, 'Delete Tax');
    }

    protected function failedAuthorization()
    {
        throw new AccessDeniedHttpException('You are not authorized to delete a Tax.');
    }
}
