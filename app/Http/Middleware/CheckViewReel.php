<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ReelView;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckViewReel extends AbstractMiddlewareResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $request->validate([
            'id' => 'exists:reels,id'
        ]);
        $reel_id = $request->id;
        $reel = ReelView::where('reel_id',$reel_id)->where('user_id',$user->id)->first();
        if (!$reel) {
            return $next($request);
        }
        return $this->finalResponse('success',200,'view success');
    }
}
