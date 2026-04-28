<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Model Brand (Merek produk).
 *
 * Fitur utama: Auto-generate slug dari nama brand saat create/update.
 * Slug digunakan sebagai URL-friendly identifier (misal: "Yamaha" → "yamaha").
 *
 * Relasi: Satu brand MEMILIKI BANYAK produk.
 */
class Brand extends Model
{
    use HasFactory;

    /**
     * Override nama tabel. Default Laravel: 'brands' (plural otomatis).
     * Kita set manual ke 'brand' agar sesuai nama tabel yang dibuat di migration.
     */
    protected $table = 'brand';

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     * 'slug' dimasukkan ke sini karena kita set secara manual di event 'creating'.
     */
    protected $fillable = [
        'nama',
        'slug',
    ];

    // =========================================================================
    // SLUG GENERATOR
    // =========================================================================

    /**
     * Generate slug unik dari nama brand.
     *
     * Cara kerja:
     * 1. Konversi nama ke slug (huruf kecil, spasi → tanda hubung): "Yamaha Motor" → "yamaha-motor"
     * 2. Cek apakah slug sudah ada di database
     * 3. Jika ada, tambah suffix angka: "yamaha-motor-1", "yamaha-motor-2", dst.
     *
     * @param  string   $nama      Nama yang akan dijadikan slug
     * @param  int|null $ignoreId  ID brand yang dikecualikan dari pengecekan (saat update)
     * @return string   Slug unik yang siap dipakai
     */
    private static function generateUniqueSlug(string $nama, ?int $ignoreId = null): string
    {
        $slug = Str::slug($nama); // Konversi ke format slug
        $originalSlug = $slug;
        $count = 1;

        // Loop sampai menemukan slug yang belum dipakai
        while (static::where('slug', $slug)->where('id', '!=', $ignoreId)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    // =========================================================================
    // MODEL EVENTS (booted)
    // =========================================================================

    /**
     * Event listener yang berjalan otomatis saat model digunakan.
     *
     * 'creating' → dijalankan SEBELUM record baru disimpan ke database
     * 'updating' → dijalankan SEBELUM record yang ada di-update
     *
     * Dengan ini, slug akan selalu tersinkronisasi dengan nama — tanpa perlu
     * input slug manual dari form atau controller.
     */
    protected static function booted(): void
    {
        // Saat Brand baru dibuat: generate slug dari nama
        static::creating(function (Brand $brand) {
            if (empty($brand->slug)) {
                $brand->slug = static::generateUniqueSlug($brand->nama);
            }
        });

        // Saat Brand di-update: perbarui slug HANYA jika nama berubah
        // isDirty('nama') → true jika nilai nama sudah berubah tapi belum disimpan
        // !isDirty('slug') → pastikan slug belum di-set manual (misal dari form)
        static::updating(function (Brand $brand) {
            if ($brand->isDirty('nama') && ! $brand->isDirty('slug')) {
                $brand->slug = static::generateUniqueSlug($brand->nama, $brand->id);
                // Pass $brand->id agar slug milik brand ini sendiri tidak dianggap duplikat
            }
        });
    }

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Brand MEMILIKI BANYAK Produk.
     * Foreign key: brand_id di tabel produk → id di tabel brand.
     */
    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class);
    }
}
