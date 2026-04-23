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
declare (strict_types=1);

namespace app\model\activity\lottery;

use app\model\user\User;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 *
 * Class LuckLotteryRecordDao
 * @package app\model\activity\lottery
 */
class LuckLotteryRecord extends BaseModel
{

    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */
    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */
    protected $name = 'luck_lottery_record';

    /**
     * Biên nhận sửa đổi thông tin
     * @param $value
     * @return false|string
     */
    protected function setReceiveInfoAttr($value)
    {
        if ($value) {
            return is_array($value) ? json_encode($value) : $value;
        }
        return '';
    }

    /**
     * Người tiếp nhận thông tin
     * @param $value
     * @param $data
     * @return mixed
     */
    protected function getReceiveInfoAttr($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    /**
     * Công cụ sửa đổi thông tin vận chuyển
     * @param $value
     * @return false|string
     */
    protected function setDeliverInfoAttr($value)
    {
        if ($value) {
            return is_array($value) ? json_encode($value) : $value;
        }
        return '';
    }

    /**
     * Máy thu thập thông tin vận chuyển
     * @param $value
     * @param $data
     * @return mixed
     */
    protected function getDeliverInfoAttr($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    /**
     * Xổ số liên quan
     * @return \think\model\relation\HasOne
     */
    public function lottery()
    {
        return $this->hasOne(LuckLottery::class, 'id', 'lottery_id');
    }

    /**
     * Giải thưởng liên quan
     * @return \think\model\relation\HasOne
     */
    public function prize()
    {
        return $this->hasOne(LuckPrize::class, 'id', 'prize_id');
    }

    /**
     * Người dùng được liên kết
     * @return \think\model\relation\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'uid', 'uid')->field('uid,real_name,nickname,phone');
    }

    /**
     * Trình tìm kiếm uid người dùng
     * @param $query Model
     * @param $value
     */
    public function searchUidAttr($query, $value)
    {
        if ($value) $query->where('uid', $value);
    }

    /**
     * người tìm kiếm từ khóa
     * @param $query Model
     * @param $value
     */
    public function searchKeywordAttr($query, $value)
    {
        if ($value !== '') {
            $query->where(function ($query1) use ($value) {
                $query1->where('id|uid|lottery_id|prize_id', 'LIKE', '%' . $value . '%')->whereOr('uid', 'IN', function ($query1) use ($value) {
                    $query1->name('user')->field('uid')->where('account|nickname|phone|real_name|uid', 'LIKE', "%$value%")->select();
                })->whereOr('lottery_id', 'IN', function ($query1) use ($value) {
                    $query1->name('luck_lottery')->field('id')->where('name|desc|content', 'LIKE', "%$value%")->select();
                })->whereOr('prize_id', 'IN', function ($query1) use ($value) {
                    $query1->name('luck_prize')->field('id')->where('name|prompt', 'LIKE', "%$value%")->select();
                });
            });
        }
    }

    /**
     * Người tìm kiếm id xổ số
     * @param $query Model
     * @param $value
     */
    public function searchLotteryIdAttr($query, $value)
    {
        if ($value !== '') $query->where('lottery_id', $value);
    }

    /**
     * người tìm kiếm id giải thưởng
     * @param $query Model
     * @param $value
     */
    public function searchPrizeIdAttr($query, $value)
    {
        if ($value) $query->where('prize_id', $value);
    }

    /**
     * Công cụ tìm loại giải thưởng
     * @param $query Model
     * @param $value
     */
    public function searchTypeAttr($query, $value)
    {
        if ($value) {
            if (is_array($value)) {
                $query->whereIn('type', $value);
            } else {
                $query->where('type', $value);
            }
        }
    }

    /**
     * Giải thưởng không còn có sẵn trong loại tìm kiếm này
     * @param $query Model
     * @param $value
     */
    public function searchNotTypeAttr($query, $value)
    {
        if ($value) {
            if (is_array($value)) {
                $query->whereNotIn('type', $value);
            } else {
                $query->where('type', '<>', $value);
            }
        }
    }

    /**
     * Có nhận được không
     * @param $query Model
     * @param $value
     */
    public function searchIsReceiveAttr($query, $value)
    {
        if ($value !== '') $query->where('is_reveive', $value);
    }

    /**
     * Có gửi hàng hay không
     * @param $query Model
     * @param $value
     */
    public function searchIsDeliverAttr($query, $value)
    {
        if ($value !== '') $query->where('is_deliver', $value);
    }
}
