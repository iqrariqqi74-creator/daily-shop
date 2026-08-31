<?php

namespace App\Http\Requests\Backend\coupons;

use App\Helpers\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CreateCouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        $auth_user = $this->user();

        return PermissionHelper::checkPermission(
            $auth_user,
            'Add Coupon'
        );
    }

    /**
     * Validation rules.
     */
    public function rules()
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            // Coupon code must be unique
            'code' => [
                'required',
                'string',
                'max:100',
                'unique:coupons,code',
            ],

            'is_one_time' => [
                'required',
                'boolean',
            ],

            'minimum_order_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages()
    {
        return [
            'title.required' =>
                'The title field is required.',

            'title.string' =>
                'The title must be a string.',

            'title.max' =>
                'The title may not be greater than 255 characters.',

            'code.required' =>
                'The coupon code is required.',

            'code.string' =>
                'The coupon code must be a string.',

            'code.max' =>
                'The coupon code may not be greater than 100 characters.',

            'code.unique' =>
                'This coupon code already exists. Please use a different code.',

            'value.required' =>
                'The coupon value is required.',

            'value.numeric' =>
                'The coupon value must be a number.',

            'value.min' =>
                'The coupon value cannot be negative.',

            'is_one_time.required' =>
                'Please specify whether the coupon is one-time use.',

            'is_one_time.boolean' =>
                'The one-time field must be true or false.',

            'minimum_order_amount.numeric' =>
                'The minimum order amount must be a number.',

            'minimum_order_amount.min' =>
                'The minimum order amount cannot be negative.',
        ];
    }

    /**
     * Authorization error.
     */
    protected function failedAuthorization()
    {
        throw new AccessDeniedHttpException(
            'You are not authorized to add a Coupon.'
        );
    }
}