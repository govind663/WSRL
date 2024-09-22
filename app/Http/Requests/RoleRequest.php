<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
                'name' => 'required|unique:roles,name,'.$this->id,
                'permission' => 'required',
            ];
        }else{
            $rule = [
                'name' => 'required|unique:roles,name',
                'permission' => 'required',
            ];
        }
        return $rule;
    }

    public function messages()
    {
        return [
            'name.required' => __('Name is required'),
            'name.unique' => __('Name already exists'),
            'permission.required' => __('Permission is required'),
            'permission.unique' => __('Permission already exists'),
        ];
    }
}
