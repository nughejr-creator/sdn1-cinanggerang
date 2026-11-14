<?php

namespace App\Http\Middleware;

use App\Services\VisitorCounterService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Hanya catat jika respons sukses (HTTP 200)
        if ($response->getStatusCode() === 200) {
            VisitorCounterService::recordVisit($request);
        }

        return $response;
    }
}
