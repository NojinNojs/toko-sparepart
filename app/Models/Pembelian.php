<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';

    protected $fillable = [
        'invoice_no',
        'user_id',
        'produk_id',
        'jumlah',
        'total',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'jumlah' => 'integer',
        ];
    }

    /**
     * Pembelian belongs to User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Pembelian belongs to Produk.
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
}
