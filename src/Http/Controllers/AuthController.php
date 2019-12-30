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
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth('admin')->user();
        $permissions = auth('admin')->user()->permission();
//        $menu = auth('admin')->user()->menu();
        return $this->json(StatusCode::SUCCESS,[
            'token' => $token,
            'user' => $user,
            'permissions' => $permissions,
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
        return $this->json(StatusCode::SUCCESS,[
            'token' => auth('admin')->refresh(),
            'expires_in' => auth('admin')->factory()->getTTL() * 60
        ]);
    }

    protected function respondWithToken($token)
    {
        return $this->json(StatusCode::SUCCESS,[
            'token' => $token,
            'expires_in' => auth('admin')->factory()->getTTL() * 60
        ]);
    }
}
