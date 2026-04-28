<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Pembelian — merepresentasikan satu transaksi pembelian.
 *
 * Siklus hidup status:
 *   pending → dikonfirmasi (stok sudah dikurangi)
 *   pending → ditolak     (stok dikembalikan oleh PembelianController@updateStatus)
 *
 * Relasi:
 *   - Satu pembelian DIMILIKI OLEH satu User (pembeli)
 *   - Satu pembelian DIMILIKI OLEH satu Produk (yang dibeli)
 */
class Pembelian extends Model
{
    use HasFactory;

    /** Override nama tabel agar sesuai dengan nama yang dibuat di migration. */
    protected $table = 'pembelian';

    /**
     * Kolom yang boleh diisi via mass-assignment (Pembelian::create([...])).
     * 'invoice_no' di-generate manual oleh CheckoutController (bukan auto-increment).
     * 'status' defaultnya 'pending', diubah oleh admin lewat updateStatus().
     */
    protected $fillable = [
        'invoice_no', // Format: INV-YYYYMMDD-XXXX (random 4 karakter)
        'user_id',    // Foreign key ke tabel users
        'produk_id',  // Foreign key ke tabel produk
        'jumlah',     // Berapa unit yang dibeli
        'total',      // Hasil harga × jumlah (snapshot harga saat beli)
        'status',     // pending | dikonfirmasi | ditolak
    ];

    /**
     * Casting tipe data kolom:
     *   'total'  → decimal 2 angka di belakang koma
     *   'jumlah' → integer (tidak boleh pecahan)
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total'  => 'decimal:2',
            'jumlah' => 'integer',
        ];
    }

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Pembelian ini DILAKUKAN OLEH (belongs to) User.
     * Foreign key: user_id di tabel pembelian → id di tabel users.
     * Contoh: $pembelian->user->name
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Pembelian ini UNTUK (belongs to) Produk.
     * Foreign key: produk_id di tabel pembelian → id di tabel produk.
     * Contoh: $pembelian->produk->nama
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
}
