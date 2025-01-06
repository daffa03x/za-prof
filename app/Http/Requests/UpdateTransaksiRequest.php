<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransaksiRequest extends FormRequest
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
            'id_event' => 'required|max:50',
            'jumlah_tiket' => 'required|min:1|max:50',
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'telepon' => 'required|numeric',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'id_payment' => 'required|string'
        ];
    }
}
