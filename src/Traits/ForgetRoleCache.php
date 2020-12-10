<?php


namespace Sczts\Rbac\Traits;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

trait ForgetRoleCache
{
    /**
     * 删除包含该角色的所有缓存
     * @param $role_id
     */
    public function forgetRoleCache($role_id)
    {
        $keyword = 'role_cache_keys:' . $role_id;
        $cache_keys = Redis::smembers($keyword);
        Redis::del($keyword);
        foreach ($cache_keys as $key){
            Cache::forget($key);
        }
    }
}
