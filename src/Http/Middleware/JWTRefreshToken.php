<?php

namespace Sczts\Skeleton\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;


class JWTRefreshToken
{
    const INTERVAL = 3600; // Token 刷新间隔

    /**
     * 计算token使用时间，判断是否刷新token
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // 计算token使用时长
        $ttl = config('jwt.ttl');
        $exp = JWTAuth::parseToken()->getPayload()->get('exp');
        $use_time = abs($exp - time() - ($ttl * 60));

        // 判断是否刷新Token
        if ($use_time > self::INTERVAL) {
            $token = JWTAuth::parseToken()->refresh();
            $response->header('JWTRefreshToken',$token);
            $json = json_decode($response->content(),true);
            $json['refresh_token'] = $token;
            return response()->json($json);
        }

        return $response;
    }
}
