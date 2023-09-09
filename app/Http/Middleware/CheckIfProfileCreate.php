<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class CheckIfProfileCreate extends AbstractMiddlewareResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        // check if $user have any relation with models
        if($user->player || $user->coach || $user->pe){
            return $this->finalResponse('faild',400,null,null,"you already have profile");
        }

        return $next($request);
    }



}
