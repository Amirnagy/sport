<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlayerProfile extends AbstractMiddlewareResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $player = $request->user()->player;
        if (!$player) {
            return $this->finalResponse('failed', 400, null, null, "Didn't have a coach Player");
        }
        return $next($request);
    }
}
