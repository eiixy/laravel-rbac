<?php


namespace Eiixy\Rbac\Http\Controllers;

use Sczts\Skeleton\Http\Controllers\Controller;
use Sczts\Skeleton\Http\StatusCode;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin', ['except' => ['login']]);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth('admin')->attempt($credentials)) {
            return $this->json(StatusCode::LOGIN_FAILED,[],200);
        }

        $user = auth('admin')->user();
        return $this->json(StatusCode::SUCCESS,[
            'token' => $token,
            'user' => $user,
            'permissions' => $user->permission(),
            'expires_in' => auth('admin')->factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {
        auth('admin')->logout();
        return $this->json(StatusCode::SUCCESS, ['msg' => '退出登录成功']);
    }

    public function refresh()
    {
        $user = auth('admin')->user();
        return $this->json(StatusCode::SUCCESS,[
            'token' => auth('admin')->refresh(),
            'user' => $user,
            'permissions' => $user->permission(),
            'expires_in' => auth('admin')->factory()->getTTL() * 60
        ]);
    }
}
