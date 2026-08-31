<?php

namespace App\Http\Requests\Backend\colors;

use App\Helpers\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ShowColorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_user = $this->user();

        return PermissionHelper::checkPermission($auth_user, 'Show Color');
    }

    protected function failedAuthorization()
    {
        throw new AccessDeniedHttpException('You are not authorized to show a color.');
    }
}
