<?php

namespace Boitata\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware to disable browser cache. This middleware prevent browser to store cache
 * when user clicks the back button, for example.
 */
class NoCache
{
    /**
     * Handle the request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        return $response->header('Cache-Control', 'no-cache,no-store,max-age=0');
    }
}
