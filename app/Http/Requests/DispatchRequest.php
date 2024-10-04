<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DispatchRequest extends FormRequest
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
                'dispatch_code' => 'required|string|min:2',
                'distributor_id' => 'required|numeric|min:0',
                'product_id' => 'required|numeric|min:0',
                'quantity' => 'required|numeric',
                'remarks' => 'required|string',
                'dispatched_at' => 'required',
            ];
        }else{
            $rule = [
                'dispatch_code' => 'required|string|min:2',
                'distributor_id' => 'required|numeric|min:0',
                'product_id' => 'required|numeric|min:0',
                'quantity' => 'required|numeric',
                'remarks' => 'required|string',
                'dispatched_at' => 'required',
            ];
        }
        return $rule;
    }

    public function messages(){

        return [
            'dispatch_code.required' => __('Dispatch Code is required'),
            'dispatch_code.string' => __('Dispatch Code should be a string'),
            'dispatch_code.min' => __('Dispatch Code should not be less than 2 characters'),

            'distributor_id.required' => __('Distributor Name is required'),
            'distributor_id.numeric' => __('Distributor Name should be a number'),

            'product_id.required' => __('Product Name is required'),
            'product_id.numeric' => __('Product Name should be a number'),

            'quantity.required' => __('Quantity is required'),
            'quantity.numeric' => __('Quantity should be a number'),

            'remarks.required' => __('Remarks is required'),
            'remarks.string' => __('Remarks should be a string'),

            'dispatched_at.required' => __('Dispatched Date is required'),

        ];
    }
}
