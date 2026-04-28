<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * RoleMiddleware — memfilter akses halaman berdasarkan role user.
 *
 * Digunakan di routes/web.php seperti ini:
 *   Route::middleware(['auth', 'role:admin'])->group(...)
 *   Route::middleware(['auth', 'role:customer'])->group(...)
 *
 * 'role:admin' → Laravel meneruskan 'admin' sebagai parameter $role ke handle().
 */
class RoleMiddleware
{
    /**
     * Proses request masuk — cek apakah user berhak mengakses halaman ini.
     *
     * @param  string  $role  Role yang diizinkan: 'admin' atau 'customer'
     *                        (nilai ini datang dari parameter middleware di route)
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Dua kondisi yang menyebabkan 403 Forbidden:
        //   1. User belum login (!$request->user() → null)
        //   2. User login tapi rolenya berbeda (misal: customer akses halaman admin)
        //
        // Catatan: Middleware 'auth' di route biasanya sudah menangani kondisi 1,
        // tapi kita cek ulang di sini sebagai lapisan keamanan tambahan (defense in depth).
        if (! $request->user() || $request->user()->role !== $role) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // User punya role yang benar → lanjutkan request ke controller
        return $next($request);
    }
}
