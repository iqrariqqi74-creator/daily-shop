<?php

namespace App\Http\Requests\Backend\transactions;

use App\Helpers\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CreateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_user = $this->user();
        return PermissionHelper::checkPermission($auth_user, 'Add Transaction');
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
            'transaction_date_time' => 'required|date',
            'transaction_type' => 'required',
            'service_type' => 'required',
            'amount' => 'required',


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
                'transaction_date_time.required' => 'Select a transaction date and time',
                'transaction_date_time.date' => 'Transaction date and time must be a valid date',
                'transaction_type.required' => 'Select a transaction type',
                'service_type.required' => 'Select a service type',
                'amount.required' => 'Enter an amount',
        ];
    }

    protected function failedAuthorization()
    {
        throw new AccessDeniedHttpException('You are not authorized to add a transaction.');
    }
}
