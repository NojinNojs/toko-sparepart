<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User — merepresentasikan pengguna aplikasi.
 *
 * Dua role yang digunakan:
 *   - 'admin'    → bisa akses panel admin (CRUD produk, konfirmasi pesanan, dll)
 *   - 'customer' → bisa belanja dan melihat riwayat transaksi
 *
 * Menggunakan PHP 8 Attribute (#[Fillable], #[Hidden]) sebagai alternatif
 * dari properti $fillable dan $hidden yang lebih tradisional.
 */

/**
 * #[Fillable] → Kolom yang boleh diisi via mass-assignment (create/update).
 * Ini pengganti properti $fillable = ['name', 'email', ...].
 */
#[Fillable(['name', 'email', 'password', 'role'])]

/**
 * #[Hidden] → Kolom yang TIDAK akan tampil saat model di-convert ke JSON/array.
 * Penting untuk keamanan: password dan token tidak bocor ke response API.
 */
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Casting tipe data otomatis saat kolom dibaca dari database:
     *   - 'email_verified_at' → dikonversi ke objek Carbon (datetime)
     *   - 'password'          → otomatis di-hash saat di-set (pakai bcrypt)
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed', // Otomatis hash saat $user->password = 'plain'
        ];
    }

    // =========================================================================
    // HELPER METHODS (untuk cek role)
    // =========================================================================

    /**
     * Cek apakah user adalah admin.
     * Digunakan di FormRequest authorize() dan middleware.
     * Contoh: $user->isAdmin() → true/false
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah customer.
     * Digunakan di CheckoutRequest authorize().
     * Contoh: $user->isCustomer() → true/false
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    // =========================================================================
    // RELASI (Eloquent Relationships)
    // =========================================================================

    /**
     * Seorang user MEMILIKI BANYAK pembelian.
     * Foreign key: user_id di tabel pembelian → id di tabel users.
     * Contoh penggunaan: $user->pembelian()->latest()->get()
     */
    public function pembelian(): HasMany
    {
        return $this->hasMany(Pembelian::class);
    }
}
