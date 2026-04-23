<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2026 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

namespace crmeb\services;

use think\facade\Cache;
use think\facade\Config;
use think\cache\TagSet;

/**
 * CRMEB Lớp bộ đệm
 * Class CacheService
 * @package crmeb\services
 */
class CacheService
{
    /**
     * Thời gian hết hạn
     * @var int
     */
    protected static $expire;

    /**
     * ghi bộ đệm
     * @param string $name tên bộ đệm
     * @param mixed $value giá trị bộ đệm
     * @param int|null $expire Thời gian bộ đệm, bằng 0 để đọc thời gian bộ đệm của hệ thống.
     */
    public static function set(string $name, $value, int $expire = 0, string $tag = 'crmeb')
    {
        try {
            return Cache::tag($tag)->set($name, $value, $expire);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Nếu không có, ghi vào bộ đệm
     * @param string $name
     * @param mixed $default
     * @param int|null $expire
     * @param string $tag
     * @return mixed|string|null
     */
    public static function remember(string $name, $default = '', int $expire = 0, string $tag = 'crmeb')
    {
        try {
            return Cache::tag($tag)->remember($name, $default, $expire);
        } catch (\Throwable $e) {
            try {
                if (is_callable($default)) {
                    return $default();
                } else {
                    return $default;
                }
            } catch (\Throwable $e) {
                return null;
            }
        }
    }

    /**
     * đọc bộ đệm
     * @param string $name
     * @param mixed $default
     * @return mixed|string
     */
    public static function get(string $name, $default = '')
    {
        return Cache::get($name) ?? $default;
    }

    /**
     * Xóa bộ nhớ đệm
     * @param string $name
     * @return bool
     */
    public static function delete(string $name)
    {
        return Cache::delete($name);
    }

    /**
     * Xóa nhóm bộ nhớ đệm
     * @return bool
     */
    public static function clear(string $tag = 'crmeb')
    {
        return Cache::tag($tag)->clear();
    }

    /**
     * Xóa tất cả bộ nhớ đệm
     * @return bool
     * @tác giả Ngô triều
     * @email 442384644@qq.com
     * @date 2023/12/19
     */
    public static function clearAll()
    {
        return Cache::clear();
    }

    /**
     * Kiểm tra xem bộ đệm có tồn tại không
     * @param string $key
     * @return bool
     */
    public static function has(string $key)
    {
        try {
            return Cache::has($key);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Chỉ định loại bộ đệm
     * @param string $type
     * @param string $tag
     * @return TagSet
     */
    public static function store(string $type = 'file', string $tag = 'crmeb')
    {
        return Cache::store($type)->tag($tag);
    }

    /**
     * kiểm tra khóa
     * @param string $key
     * @param int $timeout
     * @return bool
     */
    public static function setMutex(string $key, int $timeout = 10): bool
    {
        $curTime = time();
        $readMutexKey = "redis:mutex:{$key}";
        $mutexRes = Cache::store('redis')->handler()->setnx($readMutexKey, $curTime + $timeout);
        if ($mutexRes) {
            return true;
        }
        //Ngay cả khi bạn thoát đột ngột, chìa khóa sẽ được kiểm tra vào lần sau khi bạn vào để tránh bế tắc.
        $time = Cache::store('redis')->handler()->get($readMutexKey);
        if ($curTime > $time) {
            Cache::store('redis')->handler()->del($readMutexKey);
            return Cache::store('redis')->handler()->setnx($readMutexKey, $curTime + $timeout);
        }
        return false;
    }

    /**
     * xóa khóa
     * @param string $key
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/11/22
     */
    public static function delMutex(string $key)
    {
        $readMutexKey = "redis:mutex:{$key}";
        Cache::store('redis')->handler()->del($readMutexKey);
    }


    /**
     * khóa cơ sở dữ liệu
     * @param $key
     * @param $fn
     * @param int $ex
     * @return mixed
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/03/01
     */
    public static function lock($key, $fn, int $ex = 6)
    {
        if (Config::get('cache.default') == 'file') {
            return $fn();
        }
        return app()->make(LockService::class)->exec($key, $fn, $ex);
    }
}
