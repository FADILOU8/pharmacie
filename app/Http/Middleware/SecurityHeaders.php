<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and append security headers to the response.
     *
     * These headers implement defense-in-depth for a medical/pharmacy application
     * handling sensitive patient data (HIPAA-adjacent requirements).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent MIME-type sniffing — forces browser to respect declared Content-Type.
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Deny framing entirely to block clickjacking attacks.
        $response->headers->set('X-Frame-Options', 'DENY');

        // Legacy XSS filter for older browsers (modern browsers use CSP instead).
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // HTTP Strict Transport Security — enforce HTTPS for 1 year, include subdomains.
        // Only sent over HTTPS to avoid breaking local HTTP development.
        if ($request->isSecure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        // Content Security Policy — restrict resource origins to mitigate XSS.
        // 'unsafe-inline' is permitted for styles/scripts to support Blade-rendered
        // inline assets; tighten further by moving to nonce-based CSP when ready.
        $response->headers->set(
            'Content-Security-Policy',
            implode('; ', [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
                "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com",
                "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com",
                "img-src 'self' data: https:",
                "connect-src 'self'",
                "frame-ancestors 'none'",
                "base-uri 'self'",
                "form-action 'self'",
            ])
        );

        // Control how much referrer information is sent with requests.
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Prevent browsers from accessing sensitive features not needed by this app.
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=()'
        );

        return $response;
    }
}
