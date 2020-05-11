<?php

namespace App\Http\Middleware;

use Closure;
use \Illuminate\Auth\Middleware\Authenticate as Middleware;
use \Illuminate\Http\{
    Request,
    JsonResponse
};

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param Closure  $next
     * @param string[] ...$guards
     *
     * @return JsonResponse
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if ('authentication_error' === $this->authenticate($request, $guards)) {
            return response()->json(['error' => 'Пользователь не авторизован!']);
        }
        return $next($request);
    }

    /**
     * @param Request $request
     * @param array   $guards
     *
     * @return string|void
     */
    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards = [];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                $this->auth->shouldUse($guard);
                return;
            }
        }
        return 'authentication_error';
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
