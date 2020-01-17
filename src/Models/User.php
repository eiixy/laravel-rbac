<?php


namespace Eiixy\Rbac\Models;


use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    const STATUS_NORMAL = 0;        // 正常
    const STATUS_FORBIDDEN = 1;     // 禁用

    const TYPE_ADMIN = 0;           // 管理员
    const TYPE_SUPER_ADMIN = 1;     // 超级管理员
    const TYPE_MEMBER = 2;          // 普通成员

    protected $table = 'rbac_users';
    protected $guarded = [];


    /**
     * 获取会储存到 jwt 声明中的标识
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * 返回包含要添加到 jwt 声明中的自定义键值对数组
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return ['role' => $this->attributes['type']];
    }

}
