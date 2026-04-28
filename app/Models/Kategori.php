<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Model Kategori — sama persis logikanya dengan Brand.
 *
 * Memiliki fitur auto-slug dan satu relasi ke produk.
 * Relasi: Satu kategori MEMILIKI BANYAK produk.
 */
class Kategori extends Model
{
    use HasFactory;

    /** Override nama tabel karena default Laravel akan menggunakan 'kategoris'. */
    protected $table = 'kategori';

    protected $fillable = [
        'nama',
        'slug',
    ];

    // =========================================================================
    // SLUG GENERATOR
    // =========================================================================

    /**
     * Generate slug unik dari nama kategori.
     * Identik dengan Brand::generateUniqueSlug() — logika yang sama.
     *
     * @param  string   $nama      Nama yang akan dijadikan slug
     * @param  int|null $ignoreId  ID kategori yang dikecualikan (saat update)
     */
    private static function generateUniqueSlug(string $nama, ?int $ignoreId = null): string
    {
        $slug = Str::slug($nama); // "Mesin Motor" → "mesin-motor"
        $originalSlug = $slug;
        $count = 1;

        // Loop: tambah suffix angka sampai slug benar-benar unik
        while (static::where('slug', $slug)->where('id', '!=', $ignoreId)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    // =========================================================================
    // MODEL EVENTS
    // =========================================================================

    /**
     * Auto-generate slug dari nama saat kategori dibuat atau namanya diubah.
     * Jalankan otomatis oleh Eloquent — tidak perlu dipanggil manual dari controller.
     */
    protected static function booted(): void
    {
        // Saat buat kategori baru
        static::creating(function (Kategori $kategori) {
            if (empty($kategori->slug)) {
                $kategori->slug = static::generateUniqueSlug($kategori->nama);
            }
        });

        // Saat update: regenerate slug hanya jika nama berubah
        static::updating(function (Kategori $kategori) {
            if ($kategori->isDirty('nama') && ! $kategori->isDirty('slug')) {
                $kategori->slug = static::generateUniqueSlug($kategori->nama, $kategori->id);
            }
        });
    }

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Kategori MEMILIKI BANYAK Produk.
     * Foreign key: kategori_id di tabel produk → id di tabel kategori.
     */
    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class);
    }
}
