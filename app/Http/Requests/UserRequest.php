<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.$this->id,
                'password' => 'same:confirm-password',
                'roles' => 'required'
            ];
        }else{
            $rule = [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|same:confirm-password',
                'roles' => 'required'
            ];
        }
        return $rule;
    }

    public function messages()
    {
        return [
            'name.required' => __('Name is required'),
            'email.required' => __('Email Id is required'),
            'email.unique' => __('Email Id already exists'),
            'password.required' => __('Password is required'),
            'roles.required' => __('Role is required'),
            'password.same' => __('Password and Confirm Password do not match'),
            'confirm-password.same' => __('Password and Confirm Password do not match'),
            'confirm-password.required' => __('Confirm Password is required'),
        ];
    }
}
