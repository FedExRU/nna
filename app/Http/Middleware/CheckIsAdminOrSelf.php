<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIsAdminOrSelf
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $requestedUserId = $request
            ->route()
            ->parameter('id');
        if(
            2 === Auth::user()->role ||
            $requestedUserId === Auth::user()->id
        ) {
            return $next($request);
        }
        else {
            return response()->json([
                'error' => 'Пользователь не авторизован',
            ], 403);
        }
    }
}
