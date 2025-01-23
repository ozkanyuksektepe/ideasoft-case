<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomerCreateRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'since' => 'required',
            'revenue' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Müşteri adı gereklidir.',
            'since.required' => 'Tarih gereklidir.',
            'revenue.required' => 'Tutar bilgisi gereklidir.',
        ];
    }
}
