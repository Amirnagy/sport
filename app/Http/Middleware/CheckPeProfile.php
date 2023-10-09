<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class CheckPeProfile extends AbstractMiddlewareResponse
{


    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $pe = $request->user()->pe;
        if (!$pe) {
            return $this->finalResponse('failed', 400, null, null, "Didn't have a coach pe");
        }
        return $next($request);
    }
}
