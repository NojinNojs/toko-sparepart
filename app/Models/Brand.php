<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brand';

    protected $fillable = [
        'nama',
        'slug',
    ];

    /**
     * Generate unique slug to avoid collisions.
     */
    private static function generateUniqueSlug(string $nama, ?int $ignoreId = null): string
    {
        $slug = Str::slug($nama);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->where('id', '!=', $ignoreId)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    /**
     * Boot the model — auto-generate slug from nama.
     */
    protected static function booted(): void
    {
        static::creating(function (Brand $brand) {
            if (empty($brand->slug)) {
                $brand->slug = static::generateUniqueSlug($brand->nama);
            }
        });

        static::updating(function (Brand $brand) {
            if ($brand->isDirty('nama') && ! $brand->isDirty('slug')) {
                $brand->slug = static::generateUniqueSlug($brand->nama, $brand->id);
            }
        });
    }

    /**
     * Brand has many Produk.
     */
    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class);
    }
}
