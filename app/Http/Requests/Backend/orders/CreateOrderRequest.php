<?php

namespace App\Http\Requests\Backend\orders;

use App\Helpers\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_user = $this->user();
        return PermissionHelper::checkPermission($auth_user, 'Add Order');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'customer_id' => 'required',
            'vendor_id' => 'required',
            'payment_method' => 'required',
            'promo_code' => 'nullable',
            'order_start_date' => 'date',
            'order_end_date' => 'date|after:order_start_date',

        ];
    }

    public function messages()
    {
        return [
            'customer_id.required' => 'Select a customer',
            'vendor_id.required' => 'Select a vendor',
            'order_number.required' => 'Enter an order number',
            'order_number.unique' => 'Order number must be unique',
            'payment_method.required' => 'Select a payment method',
        ];
    }

    protected function failedAuthorization()
    {
        throw new AccessDeniedHttpException('You are not authorized to add a user.');
    }
}