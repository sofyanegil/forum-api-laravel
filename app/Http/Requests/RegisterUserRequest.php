<?php

namespace App\Http\Requests;

use App\Http\Responses\CustomJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class RegisterUserRequest extends FormRequest
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
        return [
            'fullname' => 'required|string',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'fullname.required' => 'Fullname is required',
            'username.required' => 'Username is required',
            'username.unique' => 'Username is already taken',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
        ];
    }

    /**
     * Override the failedValidation method to return a custom response.
     *
     * @param Validator $validator
     * @return CustomJsonResponse
     */

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array<string, string>
     */

    protected function failedValidation(Validator $validator)
    {
        $response =  CustomJsonResponse::validationError($validator->errors());
        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
