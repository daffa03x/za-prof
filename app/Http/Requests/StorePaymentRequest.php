<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
            'type'   => ['required', 'in:manual,midtrans'],
            'name'   => ['required', 'string', 'max:100'],
            'no_rek' => ['nullable', 'string', 'max:100'],
            'image'  => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Tipe payment wajib dipilih.',
            'type.in'       => 'Tipe payment tidak valid.',
            'name.required' => 'Nama payment wajib diisi.',
            'image.required' => 'Logo payment wajib diupload.',
        ];
    }
}
