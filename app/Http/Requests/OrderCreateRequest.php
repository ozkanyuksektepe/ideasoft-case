<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderCreateRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }

    public function rules(): array
    {
        return [
            'customerId' => 'required|exists:customers,id',
            'total' => 'required|numeric',
            'items' => 'required|array',
            'items.*.productId' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unitPrice' => 'required|numeric',
            'items.*.total' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'customerId.required' => 'Müşteri ID gereklidir.',
            'customerId.exists' => 'Müşteri bulunamadı.',
            'total.required' => 'Toplam tutar gereklidir.',
            'total.numeric' => 'Toplam tutar sayısal olmalıdır.',
            'items.required' => 'Sipariş kalemleri gereklidir.',
            'items.array' => 'Sipariş kalemleri bir dizi olmalıdır.',
            'items.*.productId.required' => 'Ürün ID gereklidir.',
            'items.*.productId.exists' => 'Ürün bulunamadı.',
            'items.*.quantity.required' => 'Miktar gereklidir.',
            'items.*.quantity.integer' => 'Miktar tam sayı olmalıdır.',
            'items.*.quantity.min' => 'Miktar en az 1 olmalıdır.',
            'items.*.unitPrice.required' => 'Birim fiyat gereklidir.',
            'items.*.unitPrice.numeric' => 'Birim fiyat sayısal olmalıdır.',
            'items.*.total.required' => 'Toplam kalem tutarı gereklidir.',
            'items.*.total.numeric' => 'Toplam kalem tutarı sayısal olmalıdır.',
        ];
    }
}

