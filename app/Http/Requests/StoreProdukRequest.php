<?php

namespace App\Http\Requests;

use App\Models\Produk;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProdukRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode'            => ['required', 'string', 'max:20', 'unique:produk,kode'],
            'nama'            => ['required', 'string', 'max:255'],
            'deskripsi'       => ['nullable', 'string'],
            'harga'           => ['required', 'numeric', 'min:0'],
            'stok'            => ['required', 'integer', 'min:0'],
            // 'in:' mengambil keys dari konstanta TIPE_KENDARAAN di model
            // Hasilnya: 'in:motor,mobil,truk,universal'
            'tipe_kendaraan'  => ['nullable', 'in:'.implode(',', array_keys(Produk::TIPE_KENDARAAN))],
            'gambar'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=100,min_height=100,max_width=4096,max_height=4096'],
            'brand_id'        => ['required', 'exists:brand,id'],
            'kategori_id'     => ['required', 'exists:kategori,id'],
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
            'kode.required'           => 'Kode produk wajib diisi.',
            'kode.unique'             => 'Kode produk sudah digunakan.',
            'kode.max'                => 'Kode produk maksimal :max karakter.',
            'nama.required'           => 'Nama produk wajib diisi.',
            'nama.max'                => 'Nama produk maksimal :max karakter.',
            'harga.required'          => 'Harga produk wajib diisi.',
            'harga.numeric'           => 'Harga harus berupa angka.',
            'harga.min'               => 'Harga minimal :min.',
            'stok.required'           => 'Stok produk wajib diisi.',
            'stok.integer'            => 'Stok harus berupa bilangan bulat.',
            'stok.min'                => 'Stok minimal :min.',
            'tipe_kendaraan.in'       => 'Tipe kendaraan tidak valid.',
            'gambar.image'            => 'File harus berupa gambar.',
            'gambar.mimes'            => 'Format gambar harus JPG, JPEG, PNG, atau WebP.',
            'gambar.max'              => 'Ukuran gambar maksimal 5 MB.',
            'gambar.dimensions'       => 'Dimensi gambar harus antara 100x100 hingga 4096x4096 piksel.',
            'brand_id.required'       => 'Merek wajib dipilih.',
            'brand_id.exists'         => 'Merek yang dipilih tidak valid.',
            'kategori_id.required'    => 'Kategori wajib dipilih.',
            'kategori_id.exists'      => 'Kategori yang dipilih tidak valid.',
        ];
    }
}
