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

namespace app\services\other;


use app\dao\other\CacheDao;
use app\services\BaseServices;

/**
 * Bộ đệm bảng cơ sở dữ liệu
 * Class CacheServices
 * @package app\services\other
 * @method delectDeOverdueDbCache() Xóa bộ nhớ đệm đã hết hạn
 */class CacheServices extends BaseServices
{

    public function __construct(CacheDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận bộ đệm dữ liệu
     * @param string $key
     * @param $default Nếu giá trị mặc định không tồn tại, hãy viết nó
     * @param int $expire
     * @return mixed|null
     */    public function getDbCache(string $key, $default, int $expire = 0)
    {
        $this->delectDeOverdueDbCache();
        $result = $this->dao->value(['key' => $key], 'result');
        if ($result) {
            return json_decode($result, true);
        } else {
            if ($default instanceof \Closure) {
                // Nhận dữ liệu được lưu trong bộ nhớ đệm
                $value = $default();
                if ($value) {
                    $this->setDbCache($key, $value, $expire);
                    return $value;
                }
            } else {
                $this->setDbCache($key, $default, $expire);
                return $default;
            }
            return null;
        }
    }

    /**
     * Đặt bộ đệm dữ liệu để cập nhật nếu nó tồn tại và ghi nếu không.
     * @param string $key
     * @param string | array $result
     * @param int $expire
     * @return void
     */    public function setDbCache(string $key, $result, $expire = 0)
    {
        $this->delectDeOverdueDbCache();
        $addTime = $expire ? time() + $expire : 0;
        if ($this->dao->count(['key' => $key])) {
            return $this->dao->update($key, [
                'result' => json_encode($result),
                'expire_time' => $addTime,
                'add_time' => time()
            ], 'key');
        } else {
            return $this->dao->save([
                'key' => $key,
                'result' => json_encode($result),
                'expire_time' => $addTime,
                'add_time' => time()
            ]);
        }
    }


    /**
     * Xóa bộ đệm
     * @param string $key
     * @return false|mixed
     */    public function delectDbCache(string $key = '')
    {
        if ($key)
            return $this->dao->delete($key, 'key');
        else
            return false;
    }

    /**
     * Kiểm tra xem bộ đệm có tồn tại không
     * @param string $key
     * @param $result
     * @return bool
     * @throws \ReflectionException
     */    public function checkDbCache(string $key = '', $result = ''): bool
    {
        // Kiểm tra xem bộ đệm có tồn tại không, nếu$valueNếu nó tồn tại, hãy kiểm tra xem giá trị được lưu trong bộ nhớ cache có nhất quán hay không.
        if ($key) {
            if ($result) {
                return $this->dao->count(['key' => $key, 'result' => json_encode($result)]) > 0;
            } else {
                return $this->dao->count(['key' => $key]) > 0;
            }
        } else {
            return false;
        }
    }
}
