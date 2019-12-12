<?php


namespace Eiixy\Rbac\Http\Middleware;


use Closure;

class CheckPermissions
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
