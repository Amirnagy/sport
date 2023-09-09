<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCouchProfile extends AbstractMiddlewareResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $coach = $request->user()->coach;

        if (!$coach) {
            return $this->finalResponse('failed', 400, null, null, "Didn't have a coach Couch");
        }
        return $next($request);
    }
}
