<?php


namespace Eiixy\Rbac\Http\Controllers;

use Sczts\Skeleton\Http\Controllers\Controller;
use Sczts\Skeleton\Http\StatusCode;

abstract class AuthController extends Controller
{
    protected $guard;

    public function __construct()
    {
        $this->guard = config('rbac.guard');
        $this->middleware('auth:'.$this->guard, ['except' => ['login']]);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth($this->guard)->attempt($credentials)) {
            return $this->json(StatusCode::LOGIN_FAILED, [], 200);
        }

        $user = auth($this->guard)->user();
        return $this->json(StatusCode::SUCCESS, [
            'token' => $token,
            'user' => $user,
            'permissions' => $user->permission(),
            'expires_in' => auth($this->guard)->factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {
        auth($this->guard)->logout();
        return $this->json(StatusCode::SUCCESS, ['msg' => '退出登录成功']);
    }

    public function refresh()
    {
        $user = auth($this->guard)->user();
        return $this->json(StatusCode::SUCCESS, [
            'token' => auth($this->guard)->refresh(),
            'user' => $user,
            'permissions' => $user->permission(),
            'expires_in' => auth($this->guard)->factory()->getTTL() * 60
        ]);
    }
}
