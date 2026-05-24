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

namespace app\model\system;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * Mô hình quy tắc thực đơn
 * Class SystemMenus
 * @package app\model\system
 */class SystemMenus extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'system_menus';

    /**
     * Công cụ sửa đổi tham số
     * @param $value
     * @return false|string
     */    public function setParamsAttr($value)
    {
        $value = $value ? explode('/', $value) : [];
        $params = array_chunk($value, 2);
        $data = [];
        foreach ($params as $param) {
            if (isset($param[0]) && isset($param[1])) $data[$param[0]] = $param[1];
        }
        return json_encode($data);
    }

    /**
     * Bộ lấy thông số
     * @param $_value
     * @return mixed
     */    public function getParamsAttr($_value)
    {
        return json_decode($_value, true);
    }

    /**
     * pidGetter
     * @param $value
     * @return mixed|string
     */    public function getPidStrAttr($value)
    {
        return !$value ? 'Đứng đầu' : $this->where('pid', $value)->value('menu_name');
    }

    /**
     * Tìm kiếm điều kiện mặc định
     * @param Model $query
     * @param $value
     */    public function searchDefaultAttr($query)
    {
        $query->where(['is_show' => 1, 'access' => 1]);
    }

    /**
     * Có hiển thị cho người tìm kiếm hay không
     * @param Model $query
     * @param $value
     */    public function searchIsShowAttr($query, $value)
    {
        if ($value != '') {
            $query->where('is_show', $value);
        }
    }

    /**
     * Có nên xóa người tìm kiếm hay không
     * @param Model $query
     * @param $value
     */    public function searchIsDelAttr($query, $value)
    {
        $query->where('is_del', $value);
    }

    /**
     * PidNgười tìm kiếm
     * @param Model $query
     * @param $value
     */    public function searchPidAttr($query, $value)
    {
        $query->where('pid', $value ?? 0);
    }

    /**
     * Trình tìm kiếm thông số kỹ thuật
     * @param Model $query
     * @param $value
     */    public function searchRuleAttr($query, $value)
    {
        $query->whereIn('id', $value)->where('is_del', 0)->whereOr('pid', 0);
    }

    /**
     * Trình đơn tìm kiếm
     * @param Model $query
     * @param $value
     */    public function searchKeywordAttr($query, $value)
    {
        if ($value != '') {
            $query->whereLike('menu_name|id|pid', "%$value%");
        }
    }

    /**
     * công cụ tìm phương pháp
     * @param Model $query
     * @param $value
     */    public function searchActionAttr($query, $value)
    {
        $query->where('action', $value);
    }

    /**
     * Trình tìm kiếm bộ điều khiển
     * @param Model $query
     * @param $value
     */    public function searchControllerAttr($query, $value)
    {
        $query->where('controller', lcfirst($value));
    }

    /**
     * Truy cập công cụ tìm địa chỉ
     * @param Model $query
     * @param $value
     */    public function searchUrlAttr($query, $value)
    {
        $query->where('api_url', $value);
    }

    /**
     * Trình tìm kiếm tham số
     * @param Model $query
     * @param $value
     */    public function searchParamsAttr($query, $value)
    {
        $query->where(function ($query) use ($value) {
            $query->where('params', $value)->whereOr('params', "'[]'");
        });
    }

    /**
     * Trình tìm kiếm ID quyền
     * @param Model $query
     * @param $value
     */    public function searchUniqueAttr($query, $value)
    {
        $query->where('is_del', 0);
        if ($value) {
            $query->whereIn('id', $value);
        }
    }

    /**
     * Tìm kiếm thông số kỹ thuật menu
     * @param Model $query
     * @param $value
     */    public function searchRouteAttr($query, $value)
    {
        $query->where('auth_type', 1)->where('is_del', 0);
        if ($value) {
            $query->whereIn('id', $value);
        }
    }

    /**
     * IdNgười tìm kiếm
     * @param Model $query
     * @param $value
     */    public function searchIdAttr($query, $value)
    {
        $query->whereIn('id', $value);
    }

    /**
     * is_show_path
     * @param Model $query
     * @param $value
     */    public function searchIsShowPathAttr($query, $value)
    {
        $query->where('is_show_path', $value);
    }

    /**
     * auth_type
     * @param Model $query
     * @param $value
     */    public function searchAuthTypeAttr($query, $value)
    {
        if ($value !== '') {
            if ($value == 3) {
                $query->whereIn('auth_type', [1, 3]);
            } else {
                $query->where('auth_type', $value);
            }
        }
    }

    /**
     * Phát hiện mô-đun
     * @param Model $query
     * @param $value
     */    public function searchNoModelAttr($query, $value)
    {
        $query->when(!in_array('seckill', $value), function ($q1) {
            $q1->whereNotLike('menu_name', '%bán chớp nhoáng%');
        })->when(!in_array('bargain', $value), function ($q2) {
            $q2->whereNotLike('menu_name', '%Mặc cả%');
        })->when(!in_array('combination', $value), function ($q3) {
            $q3->whereNotLike('menu_name', '%Chia sẻ nhóm%');
        });
    }
}
