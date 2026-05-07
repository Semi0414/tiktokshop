<?php

namespace Webkul\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecureHeaders
{
    /**
     * Unwanted header list.
     *
     * @var array
     */
    private $unwantedHeaderList = [];

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->removeUnwantedHeaders();

        $response = $next($request);

        $this->setHeaders($response, $request);

        return $response;
    }

    /**
     * Set headers.
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @param  Request  $request
     * @return void
     */
    private function setHeaders($response, $request)
    {
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        // SAMEORIGIN allows embedding only from the same site.
        // DENY blocks all iframes including same-origin wrappers.
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        // HSTS only over HTTPS — avoids odd browser behaviour on plain HTTP / local dev.
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }
        $response->headers->set('X-Built-With', 'Bagisto');
    }

    /**
     * Remove unwanted headers.
     *
     * @return void
     */
    private function removeUnwantedHeaders()
    {
        if (headers_sent()) {
            return;
        }

        foreach ($this->unwantedHeaderList as $header) {
            header_remove($header);
        }
    }
}
