<?php

namespace App\Http\Requests\Backend\products;

use App\Helpers\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CreateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $auth_user = $this->user();
        return PermissionHelper::checkPermission($auth_user, 'Add Product');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            // Product
            'category_id' => 'required|integer',
            'brand_id' => 'required|integer',
            'model' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'technical_specification' => 'nullable|string',
            'uses' => 'nullable|string',
            'warranty' => 'nullable|string',
            'lead_time' => 'nullable|string|max:255',
            'tax_id' => 'nullable|integer',

            'is_promo' => 'required|boolean',
            'is_featured' => 'required|boolean',
            'is_discounted' => 'required|boolean',
            'is_tranding' => 'required|boolean',

            'status' => 'required|in:active,inactive',

            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',

            /*
            |--------------------------------------------------------------------------
            | Product Images
            |--------------------------------------------------------------------------
            */

            'productImages' => 'array',

            'productImages.*.image' =>
                'required|image|mimes:jpeg,png,jpg,webp|max:2048',

            /*
            |--------------------------------------------------------------------------
            | Product Attributes
            |--------------------------------------------------------------------------
            */

            'productAttribute' => 'array',

            'productAttribute.*.color_id' =>
                'required|integer',

            'productAttribute.*.size_id' =>
                'required|integer',

            'productAttribute.*.sku' =>
                'required|string|max:100',

            'productAttribute.*.price' =>
                'required|numeric|min:0',

            'productAttribute.*.sale_price' =>
                'required|numeric|min:0',

            'productAttribute.*.quantity' =>
                'required|integer|min:0',

            'productAttribute.*.image' =>
                'required|image|mimes:jpeg,png,jpg,webp|max:2048',

        ];
    }

    public function messages()
{
    return [

        'category_id.required' => 'Please select a category.',
        'brand_id.required' => 'Please select a brand.',
        'model.required' => 'Please enter model.',
        'name.required' => 'Please enter product name.',

        /*
        |--------------------------------------------------------------------------
        | Product Images
        |--------------------------------------------------------------------------
        */

        'productImages.*.image.required'
            => 'Please select product image.',

        'productImages.*.image.image'
            => 'Product image must be an image.',

        'productImages.*.image.mimes'
            => 'Product image must be jpeg, jpg, png or webp.',

        /*
        |--------------------------------------------------------------------------
        | Product Attributes
        |--------------------------------------------------------------------------
        */

        'productAttribute.*.color_id.required'
            => 'Please select color.',

        'productAttribute.*.size_id.required'
            => 'Please select size.',

        'productAttribute.*.sku.required'
            => 'SKU is required.',

        'productAttribute.*.price.required'
            => 'Price is required.',

        'productAttribute.*.quantity.required'
            => 'Quantity is required.',

        'productAttribute.*.image.image'
            => 'Attribute image must be an image.',

        'productAttribute.*.sale_price.sale_price'
                => 'Sale price is Required',
    ];
}

    protected function failedAuthorization()
    {
        throw new AccessDeniedHttpException('You are not authorized to add a product.');
    }
}
