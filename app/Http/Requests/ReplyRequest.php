<?php

namespace App\Http\Requests;

use App\Http\Responses\CustomJsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ReplyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string'],
        ];
    }

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
