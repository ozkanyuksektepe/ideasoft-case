<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
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
            'category' => 'required',
            'price' => 'required',
            'stock' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Ürün adı gereklidir.',
            'category.required' => 'Ürün kategorisi gereklidir.',
            'price.required' => 'Tutar bilgisi gereklidir.',
            'stock.required' => 'Stok bilgisi gereklidir.',
        ];
    }
}
