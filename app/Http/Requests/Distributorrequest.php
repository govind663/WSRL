<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Distributorrequest extends FormRequest
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
                'distributor_code' => 'required|string|max:255',
                'distributor_name' => 'required|string|max:255',
                'contact_person' => 'required',
                'email' => 'required',
                'address' => 'required|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'postal_code' => 'required|numeric',
                'country' => 'required|string|max:255',
            ];
        }else{
            $rule = [
                'distributor_code' => 'required|string|max:255',
                'distributor_name' => 'required|string|max:255',
                'contact_person' => 'required|numeric|unique:distributors,contact_person',
                'email' => 'required|string|email|unique:distributors,email',
                'address' => 'required|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'postal_code' => 'required|numeric',
                'country' => 'required|string|max:255',
            ];
        }
        return $rule;
    }

    public function messages()
    {
        return [
            'distributor_code.required' => 'Distributor Code is required',
            'distributor_code.string' => 'Distributor Code should be a string',
            'distributor_code.max' => 'Distributor Code should not exceed 255 characters',

            'distributor_name.required' => 'Distributor Name is required',
            'distributor_name.string' => 'Distributor Name should be a string',
            'distributor_name.max' => 'Distributor Name should not exceed 255 characters',

            'contact_person.required' => 'Contact Person is required',
            'contact_person.numeric' => 'Contact Person should be a number',
            'contact_person.max' => 'Contact Person should not exceed 10 characters',

            'email.required' => 'Email Id is required',
            'email.email' => 'Please enter a valid Email address',

            'address.required' => 'Address is required',
            'address.max' => 'Address should not exceed 255 characters',

            'city.required' => 'City is required',
            'city.string' => 'City should be a string',
            'city.max' => 'City should not exceed 255 characters',

            'state.required' => 'State is required',
            'state.string' => 'State should be a string',
            'state.max' => 'State should not exceed 255 characters',

            'postal_code.required' => 'Postal Code is required',
            'postal_code.numeric' => 'Postal Code should be a number',
            'postal_code.max' => 'Postal Code should not exceed 6 characters',

            'country.required' => 'Country is required',
            'country.string' => 'Country should be a string',
            'country.max' => 'Country should not exceed 255 characters',
        ];
    }
}
