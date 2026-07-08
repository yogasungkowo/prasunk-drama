<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFrontendAjaxRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->ajax()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $origin = $request->headers->get('origin');
        $referer = $request->headers->get('referer');
        $host = $request->getHost();

        if ($origin && parse_url($origin, PHP_URL_HOST) !== $host) {
            abort(Response::HTTP_FORBIDDEN);
        }

        if ($referer && parse_url($referer, PHP_URL_HOST) !== $host) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
