<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isCustomer() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'produk_id' => ['required', 'exists:produk,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * Pesan validasi dalam Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'produk_id.required' => 'Produk wajib dipilih.',
            'produk_id.exists' => 'Produk yang dipilih tidak valid.',
            'jumlah.required' => 'Jumlah pembelian wajib diisi.',
            'jumlah.integer' => 'Jumlah harus berupa bilangan bulat.',
            'jumlah.min' => 'Jumlah minimal :min.',
        ];
    }
}
