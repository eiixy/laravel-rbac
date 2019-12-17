<?php

namespace Eiixy\Rbac\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class JWTRoleAuth extends BaseMiddleware
{
    /**
     * JWT 检测当前登录的平台
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  null $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role = null)
    {
        try {
            // 解析token角色
            $tokenRole = $this->auth->parseToken()->getClaim('role');
        } catch (JWTException $e) {
            throw new UnauthorizedHttpException('jwt-auth', 'User no role is specified');
        }
        // 判断token角色。
        if ($tokenRole != $role) {
            throw new UnauthorizedHttpException('jwt-auth', 'User role error');
        }
        return $next($request);
    }
}
