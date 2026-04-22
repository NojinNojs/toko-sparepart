<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'harga',
        'stok',
        'gambar',
        'brand_id',
        'kategori_id',
    ];

    /**
     * Eager load relationships default to avoid N+1 queries.
     */
    protected $with = ['brand', 'kategori'];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'stok' => 'integer',
        ];
    }

    /**
     * Produk belongs to Brand.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Produk belongs to Kategori.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Produk has many Pembelian.
     */
    public function pembelian(): HasMany
    {
        return $this->hasMany(Pembelian::class);
    }
}
