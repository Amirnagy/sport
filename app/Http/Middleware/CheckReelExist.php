<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Reel;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Middleware\AbstractMiddlewareResponse;

class CheckReelExist extends AbstractMiddlewareResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // $request->validate([
        //     'id' => 'required|integer|exists:reels,id',
        // ]);


        $reel = Reel::find($request->id);
        if(!$reel) {
            return $this->finalResponse('faild',400,null,null,"video didn't exist");
        }
        return $next($request);
    }
}
