<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model untuk tabel 'produk'.
 *
 * Merepresentasikan satu baris di tabel produk.
 * Relasi: Setiap produk DIMILIKI OLEH satu brand dan satu kategori,
 *         dan MEMILIKI BANYAK pembelian.
 */
class Produk extends Model
{
    use HasFactory;

    /**
     * Override nama tabel karena Laravel secara default
     * mengasumsikan nama tabel adalah bentuk plural dari model (produks).
     * Kita tentukan manual agar sesuai nama tabel yang dibuat.
     */
    protected $table = 'produk';

    /**
     * Kolom-kolom yang boleh diisi secara mass-assignment (via ::create() atau ->update()).
     * Kolom di luar daftar ini akan diabaikan demi keamanan.
     *
     * @see https://laravel.com/docs/eloquent#mass-assignment
     */
    /**
     * Nilai enum yang valid untuk kolom tipe_kendaraan.
     * Didefinisikan di sini agar bisa di-reuse di View (dropdown) dan Validation.
     */
    public const TIPE_KENDARAAN = [
        'motor'     => 'Motor',
        'mobil'     => 'Mobil',
        'truk'      => 'Truk',
        'universal' => 'Universal (Semua Kendaraan)',
    ];

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'harga',
        'stok',
        'tipe_kendaraan',
        'gambar',
        'brand_id',
        'kategori_id',
    ];

    /**
     * Eager load relasi brand & kategori secara otomatis di SETIAP query.
     *
     * Tujuan: Mencegah masalah N+1 Query.
     * Contoh masalah N+1: Kalau ada 100 produk dan kita loop $produk->brand->nama,
     * tanpa eager loading Laravel akan jalankan 1 query produk + 100 query brand = 101 query.
     * Dengan $with ini, Laravel hanya butuh 2 query (1 produk + 1 brand JOIN).
     *
     * @var array<string>
     */
    protected $with = ['brand', 'kategori'];

    /**
     * Casting tipe data kolom dari database ke tipe PHP.
     * 'decimal:2' → harga selalu 2 angka di belakang koma (misal: 150000.00)
     * 'integer'   → stok selalu integer, bukan string
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'harga'           => 'decimal:2',
            'stok'            => 'integer',
            // tipe_kendaraan disimpan sebagai string enum, tidak perlu cast khusus
        ];
    }

    // =========================================================================
    // RELASI (Eloquent Relationships)
    // =========================================================================

    /**
     * Produk DIMILIKI OLEH (belongs to) Brand.
     * Foreign key: brand_id di tabel produk → id di tabel brand.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Produk DIMILIKI OLEH (belongs to) Kategori.
     * Foreign key: kategori_id di tabel produk → id di tabel kategori.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Produk MEMILIKI BANYAK (has many) Pembelian.
     * Foreign key: produk_id di tabel pembelian → id di tabel produk.
     */
    public function pembelian(): HasMany
    {
        return $this->hasMany(Pembelian::class);
    }
}
