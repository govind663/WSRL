<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        if ($this->id){
            $rule = [
                'name' => 'required|string|min:2',
                'description' => 'required|min:30',
                'total_quantity' => 'required|numeric|min:0',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            ];
        }else{
            $rule = [
                'name' => 'required|string|min:2',
                'description' => 'required|min:30',
                'total_quantity' => 'required|numeric|min:0',
                'price' => 'required|numeric|min:0',
                'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
            ];
        }
        return $rule;
    }

    public function messages()
    {
        return [
            'name.required' => __('Product Name is required'),
            'name.string' => __('Product Name should be a string'),
            'name.min' => __('Product Name should not be less than 2 characters'),

            'description.required' => __('Description is required'),
            'description.min' => __('Description should not be less than 30 characters'),

            'total_quantity.required' => __('Total Quantity is required'),
            'total_quantity.numeric' => __('Total Quantity should be a number'),
            'total_quantity.min' => __('Total Quantity should not be less than 0'),

            'price.required' => __('Price is required'),
            'price.numeric' => __('Price should be a number'),
            'price.min' => __('Price should not be less than 0'),

            'image.required' => __('Product Image is required'),
            'image.image' => __('Product Image should be an image'),
            'image.mimes' => __('Product Image should be a file of type: jpeg, png, jpg, svg'),
            'image.max' => __('Product Image should not be greater than 2048 kilobytes'),
        ];
    }
}
