<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menambahkan security headers ke setiap response HTTP.
 * Headers ini melindungi dari serangan XSS, clickjacking, MIME sniffing, dll.
 */
class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Mencegah browser menebak MIME type
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Mencegah halaman dimuat di iframe (anti-clickjacking)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Aktifkan XSS filter browser
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Batasi informasi yang dikirim di Referrer header
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Nonaktifkan fitur browser yang tidak digunakan
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Strict Transport Security (hanya aktif di production HTTPS)
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
