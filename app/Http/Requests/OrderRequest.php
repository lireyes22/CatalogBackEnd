<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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

    private function getRulesMethod(): array
    {
        if ($this->isMethod('post')) {
            return $this->rulesForStore();
        } elseif ($this->isMethod('put')) {
            return $this->rulesForUpdatePut();
        } elseif ($this->isMethod('patch')) {
            return $this->rulesForUpdatePatch();
        }
        return [];
    }

    private function rulesForStore(): array
    {
        return [
            'Order_PaymentMethod' => 'required|string',
            'Products' => 'required|array',
            'Products.*' => 'required|string',
            'Client' => 'required|string',
        ];
    }

    private function rulesForUpdatePut(): array
    {
        return [
            'Order_PaymentMethod' => 'required|string',
            'Products' => 'required|array',
            'Products.*' => 'required|string',
            //'Client' => 'required|string',
        ];
    }

    private function rulesForUpdatePatch(): array
    {
        return [
            'Order_PaymentMethod' => 'sometimes|string',
            'Products' => 'sometimes|array',
            'Products.*' => 'sometimes|string',
            //'Client' => 'sometimes|string',
        ];
    }

}
