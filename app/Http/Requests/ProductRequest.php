<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->getRulesMethod();
    }
    /**
     * Obtén reglas dinámicas según el método HTTP
     */
    private function getRulesMethod(): array
    {
        if ($this->isMethod('post')) {
            return $this->rulesForStore();
        } elseif ($this->isMethod('put')) {
            return $this->rulesForPut();
        } elseif ($this->isMethod('patch')) {
            return $this->rulesForPatch();
        }

        return [];
    }

    /**
     * Reglas para crear un nuevo producto (POST)
     */
    private function rulesForStore(): array
    {
        return [
            'Product_Category' => 'required|string',
            'Product_Name' => 'required|string|unique:Products,Product_Name',
            //'Product_Tags' => 'array',
            //'Product_Images' => 'sometimes|array',
            'Product_Description' => 'required|string',
            //'Product_Availability' => 'boolean',
            'Product_Stock' => 'required|numeric|min:0',
            //'Product_Ratings' => 'nullable',
            //'Product_PromoCodes' => 'nullable',
            //'Product_ShippingMethods' => 'nullable',
            //'Product_PaymentMethodsAvailable' => 'required|array',
            //'Product_PaymentMethodsAvailable.*.Product_PaymentMethod' => 'required|string',
            //'Product_PaymentMethodsAvailable.*.Product_DiscountAvailable' => 'required|boolean',
            //'Product_PaymentMethodsAvailable.*.Product_Price' => 'required|numeric|min:0',
            //'Product_PaymentMethodsAvailable.*.Product_Discount' => 'required|numeric|min:0',
        ];
    }

    /**
     * Reglas para actualizar un producto completo (PUT)
     */
    private function rulesForPut(): array
    {
        return [
            'Product_Category' => 'required|string',
            'Product_Name' => [
                'required',
                'string',
                Rule::unique('Products', 'Product_Name')->ignore(request()->route('product._id'), '_id'),
            ],
            'Product_Tags' => 'required|nullable|array',
            'Product_Tags.*' => 'required|string',
            'Product_Images' => 'required|array',
            'Product_Images.*' => 'required|string',
            'Product_Description' => 'required|string',
            'Product_Availability' => 'required|boolean',
            'Product_Stock' => 'required|numeric',
            /* 'Product_Ratings' => 'required|array',
            'Product_Ratings.*.Product_ClientID' => 'required|string', */
            'Product_Ratings.*.Product_Rating' => 'required|numeric|min:0|max:5',
            'Product_PromoCodes' => 'required|nullable|array',
            'Product_PromoCodes.*.Product_PromoCode' => 'required|string',
            'Product_PromoCodes.*.Product_Discount' => 'required|numeric',
            //'Product_ShippingMethods' => 'nullable',
            'Product_PaymentMethodsAvailable' => 'required|array',
            'Product_PaymentMethodsAvailable.*.Product_PaymentMethod' => 'required|string',
            'Product_PaymentMethodsAvailable.*.Product_DiscountAvailable' => 'required|boolean',
            'Product_PaymentMethodsAvailable.*.Product_Price' => 'required|numeric',
            'Product_PaymentMethodsAvailable.*.Product_Discount' => 'required|numeric',
        ];
    }

    /**
     * Reglas para actualizar parcialmente un producto (PATCH)
     */
    private function rulesForPatch(): array
    {
        /* echo request()->route('product._id');
        echo 'hola'; */
        return [
            'Product_Category' => 'sometimes|string',
            'Product_Name' => [
                'sometimes',
                'string',
                Rule::unique('Products', 'Product_Name')->ignore(request()->route('product._id'), '_id'),
            ],
            'Product_Tags' => 'sometimes|nullable|array',
            'Product_Tags.*' => 'sometimes|string',
            'Product_Images' => 'sometimes|array',
            'Product_Images.*' => 'sometimes|string',
            'Product_Description' => 'sometimes|string',
            'Product_Availability' => 'sometimes|boolean',
            'Product_Stock' => 'sometimes|numeric',
            /* 'Product_Ratings' => 'sometimes|array',
            'Product_Ratings.*.Product_ClientID' => 'required|string', */
            'Product_Ratings.*.Product_Rating' => 'required|numeric|min:0|max:5',
            'Product_PromoCodes' => 'sometimes|nullable|array',
            'Product_PromoCodes.*.Product_PromoCode' => 'required|string',
            'Product_PromoCodes.*.Product_Discount' => 'required|numeric',
            //'Product_ShippingMethods' => 'nullable',
            'Product_PaymentMethodsAvailable' => 'sometimes|array',
            'Product_PaymentMethodsAvailable.*.Product_PaymentMethod' => 'required|string',
            'Product_PaymentMethodsAvailable.*.Product_DiscountAvailable' => 'required|boolean',
            'Product_PaymentMethodsAvailable.*.Product_Price' => 'required|numeric',
            'Product_PaymentMethodsAvailable.*.Product_Discount' => 'required|numeric',
        ];
    }
}
