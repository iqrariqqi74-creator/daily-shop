<?php

namespace App\Http\Requests\Backend\sizes;

use App\Helpers\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class EditSizeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_user = $this->user();
        return PermissionHelper::checkPermission($auth_user, 'Edit Size');
    }


    protected function failedAuthorization()
    {
        throw new AccessDeniedHttpException('You are not authorized to edit a size.');
    }
}
