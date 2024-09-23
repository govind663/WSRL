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
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'email' => [
                    'required',
                    'email',
                    'regex:/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/i',
                ],
                'department_id' => [
                    'required',
                    'numeric',
                ],
                'roles' => [
                    'required',
                ],
            ];
        }else{
            $rule = [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'email' => [
                    'required',
                    'email',
                    'regex:/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/i',
                ],
                'password' => [
                    'required',
                    'confirmed',
                    'min:8',
                ],
                'password_confirmation' => [
                    'required',
                    'same:password',
                ],
                'department_id' => [
                    'required',
                    'numeric',
                ],
                'roles' => [
                    'required',
                ],
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
            'roles.required' => __('Role is required'),
            'password.required' => __('Password is required'),
            'password.same' => __('Password and Confirm Password do not match'),
            'confirm-password.same' => __('Password and Confirm Password do not match'),
            'confirm-password.required' => __('Confirm Password is required'),
            'department_id.required' => __('Department is required'),
        ];
    }
}
