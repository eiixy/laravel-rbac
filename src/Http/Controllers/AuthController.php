<?php


namespace Sczts\Rbac\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sczts\Skeleton\Traits\JsonResponse;

abstract class AuthController extends Controller
{
    use JsonResponse;

    protected $guard;

    public function __construct()
    {
        $this->guard = config('rbac.guard');
        $this->middleware(config('rbac.middleware'), ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->input(['email', 'password']);
        if (!$token = auth($this->guard)->attempt($credentials)) {
            return $this->failed('登录认证失败');
        }

        $user = auth($this->guard)->user();
        return $this->success([
            'token' => $token,
            'user' => $user,
            'permissions' => $user->permissions(),
            'expires_in' => auth($this->guard)->factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {
        auth($this->guard)->logout();
        return $this->message('退出登录成功');
    }

    public function refresh()
    {
        $user = auth($this->guard)->user();
        return $this->success([
            'token' => auth($this->guard)->refresh(),
            'user' => $user,
            'permissions' => $user->permission(),
            'expires_in' => auth($this->guard)->factory()->getTTL() * 60
        ]);
    }
}
