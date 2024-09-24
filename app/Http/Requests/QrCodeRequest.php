<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QrCodeRequest extends FormRequest
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
        if ($this->id){
            $rule = [
                'quantity' => 'required|numeric|min:1',
            ];
        }else{
            $rule = [
                'quantity' => 'required|numeric|min:1',
            ];
        }
        return $rule;
    }

    public function messages()
    {
        return [
            'quantity.required' => __('Quantity is required'),
            'quantity.min'  => __('Quantity should be greater than or equal to 1'),
            'quantity.numeric'  => __('Quantity should be a number'),
        ];
    }
}
