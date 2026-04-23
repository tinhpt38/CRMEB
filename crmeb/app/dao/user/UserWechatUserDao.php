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

namespace app\dao\user;

use think\model;
use app\dao\BaseDao;
use app\model\user\User;
use app\model\wechat\WechatUser;

/**
 *
 * Class UserWechatUserDao
 * @package app\dao\user
 */
class UserWechatUserDao extends BaseDao
{
    /**
     * @var string
     */
    protected $alias = '';

    /**
     * @var string
     */
    protected $join_alis = '';

    /**
     * Danh sách trắng tìm kiếm chính xác
     * @var string[]
     */
    protected $withField = ['uid', 'nickname', 'user_type', 'phone'];

    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return User::class;
    }

    public function joinModel(): string
    {
        return WechatUser::class;
    }

    /**
     * mô hình liên kết
     * @param string $alias
     * @param string $join_alias
     * @return \crmeb\basic\BaseModel
     */
    public function getModel(string $alias = 'u', string $join_alias = 'w', $join = 'left')
    {
        $this->alias = $alias;
        $this->join_alis = $join_alias;
        /** @var WechatUser $wechcatUser */
        $wechcatUser = app()->make($this->joinModel());
        $table = $wechcatUser->getName();
        return parent::getModel()->alias($alias)->join($table . ' ' . $join_alias, $alias . '.uid = ' . $join_alias . '.uid', $join);
    }

    public function getList(array $where, $field = '*', int $page, int $limit)
    {
        return $this->getModel()->where($where)->field($field)->page($page, $limit)->select()->toArray();
    }

    /**
     * Nhận tổng số
     * @param array $where
     * @return int
     */
    public function getCount(array $where): int
    {
        return $this->getModel()->where($where)->count();
    }

    /**
     * Số lượng mục mô hình điều kiện kết hợp
     * @param Model $model
     * @return int
     */
    public function getCountByWhere(array $where): int
    {
        return $this->searchWhere($where)->group($this->alias . '.uid')->count();
    }

    /**
     * Danh sách truy vấn mô hình điều kiện kết hợp
     * @param Model $model
     * @return array
     */
    public function getListByModel(array $where, string $field = '', string $order = '', int $page, int $limit): array
    {
        return $this->searchWhere($where)->field($field)->page($page, $limit)->group($this->alias . '.uid')->order(($order ? $order . ' ,' : '') . $this->alias . '.uid desc')->select()->toArray();
    }

    /**
     * Đặt tiêu chí tìm kiếm
     * @param $where array mảng có điều kiện
     * @param array|null $field Các trường cần truy vấn
     * @return \crmeb\basic\BaseModel
     */
    public function searchWhere($where, ?array $field = [])
    {
        $model = $this->getModel();
        $userAlias = $this->alias . '.';
        $wechatUserAlias = $this->join_alis . '.';
        
        // --- Mô-đun lọc thời gian ---
        // Kiểu bộ lọc：visitno(Chưa ghé thăm), visit(thời gian truy cập), add_time(Thời gian đăng ký)
        if (isset($where['user_time_type']) && isset($where['user_time'])) {
            // Lọc người dùng chưa truy cập trong một thời gian nhất định
            if ($where['user_time_type'] == 'visitno' && $where['user_time'] != '') {
                list($startTime, $endTime) = explode('-', $where['user_time']);
                if ($startTime && $endTime) {
                    $endTime = strtotime($endTime) + 24 * 3600;
                    // last_time Nhỏ hơn thời gian bắt đầu hoặc lớn hơn thời gian kết thúc (nghĩa là không được truy cập trong khoảng thời gian này）
                    $model = $model->where($userAlias . "last_time < " . strtotime($startTime) . " OR " . $userAlias . "last_time > " . $endTime);
                }
            }
            // Lọc người dùng đã truy cập trong một thời gian nhất định
            if ($where['user_time_type'] == 'visit' && $where['user_time'] != '') {
                list($startTime, $endTime) = explode('-', $where['user_time']);
                if ($startTime && $endTime) {
                    $model = $model->where($userAlias . 'last_time', '>', strtotime($startTime));
                    $model = $model->where($userAlias . 'last_time', '<', strtotime($endTime) + 24 * 3600);
                }
            }
            // Lọc người dùng đã đăng ký trong một thời gian nhất định
            if ($where['user_time_type'] == 'add_time' && $where['user_time'] != '') {
                list($startTime, $endTime) = explode('-', $where['user_time']);
                if ($startTime && $endTime) {
                    $model = $model->where($userAlias . 'add_time', '>', strtotime($startTime));
                    $model = $model->where($userAlias . 'add_time', '<', strtotime($endTime) + 24 * 3600);
                }
            }
        }

        // --- Bộ lọc số lượng mua hàng (giá trị duy nhất) ---
        // pay_count: -1(0hạng hai), Các giá trị khác(lớn hơn giá trị này)
        if (isset($where['pay_count']) && $where['pay_count'] != '') {
            if ($where['pay_count'] == '-1') {
                $model = $model->where($userAlias . 'pay_count', 0);
            } else {
                $model = $model->where($userAlias . 'pay_count', '>', $where['pay_count']);
            }
        }

        // --- Bộ lọc số lượng mua hàng (khoảng thời gian) ---
        // pay_count_num: [min, max]
        if (isset($where['pay_count_num']) && count($where['pay_count_num']) == 2) {
            if ($where['pay_count_num'][0] != '' && $where['pay_count_num'][1] != '') {
                // Truy vấn khoảng thời gian
                $model = $model->whereBetween($userAlias . 'pay_count', $where['pay_count_num']);
            } elseif ($where['pay_count_num'][0] != '' && $where['pay_count_num'][1] == '') {
                // lớn hơn mức tối thiểu
                $model = $model->where($userAlias . 'pay_count', '>', $where['pay_count_num'][0]);
            } elseif ($where['pay_count_num'][0] == '' && $where['pay_count_num'][1] != '') {
                // ít hơn mức tối đa
                $model = $model->where($userAlias . 'pay_count', '<', $where['pay_count_num'][1]);
            }
        }

        // --- Lọc theo lượng tiêu thụ (khoảng thời gian) ---
        // pay_count_money: [min, max] Thống kê số tiền thanh toán thực tế trong bảng store_order
        if (isset($where['pay_count_money']) && count($where['pay_count_money']) == 2) {
            $min = $where['pay_count_money'][0];
            $max = $where['pay_count_money'][1];
            
            if ($min !== '' || $max !== '') {
                $model = $model->where(function ($query) use ($userAlias, $min, $max) {
                    // Truy vấn con: Tìm các bản ghi thỏa mãn điều kiện trong bảng store_order
                    $query->whereExists(function ($q) use ($userAlias, $min, $max) {
                        $q->name('store_order')
                            ->whereColumn('uid', $userAlias . 'uid')
                            ->where('paid', 1) // trả
                            ->where('refund_status', 0); // Không hoàn lại tiền
                        
                        // Lọc dựa trên điều kiện khoảng pay_price
                        if ($min !== '' && $max !== '') {
                            $q->whereBetween('pay_price', [$min, $max]);
                        } elseif ($min !== '' && $max === '') {
                            $q->where('pay_price', '>', $min);
                        } elseif ($min === '' && $max !== '') {
                            $q->where('pay_price', '<', $max);
                        }
                    });
                    
                    // Xử lý đặc biệt: Nếu giá trị tối thiểu là 0 hoặc trống, cần đưa vào người dùng không có bản ghi đơn hàng (nghĩa là người dùng có số lượng tiêu thụ là 0）
                    if ($min === '' || $min == 0) {
                        $query->whereOr(function ($q) use ($userAlias) {
                            $q->whereNotExists(function ($sub) use ($userAlias) {
                                $sub->name('store_order')
                                    ->whereColumn('uid', $userAlias . 'uid')
                                    ->where('paid', 1)
                                    ->where('refund_status', 0);
                            });
                        });
                    }
                });
            }
        }

        // --- Lọc số lần sạc (khoảng thời gian) ---
        // recharge_count: [min, max] Đếm số lượng bản ghi trong bảng user_recharge
        if (isset($where['recharge_count']) && count($where['recharge_count']) == 2) {
            $min = $where['recharge_count'][0];
            $max = $where['recharge_count'][1];
            
            if ($min !== '' || $max !== '') {
                $model = $model->where(function ($query) use ($userAlias, $min, $max) {
                    // Truy vấn con: Đếm số lượng bản ghi nạp tiền theo nhóm
                    $query->whereExists(function ($q) use ($userAlias, $min, $max) {
                        $q->name('user_recharge')
                            ->whereColumn('uid', $userAlias . 'uid')
                            ->field('uid')
                            ->group('uid')
                            ->having('COUNT(*) BETWEEN ' . (int)$min . ' AND ' . (int)$max);
                    });
                    
                    // Đối xử đặc biệt: bao gồm cả người dùng không có hồ sơ nạp tiền
                    if ($min === '' || $min == 0) {
                        $query->whereOr(function ($q) use ($userAlias) {
                            $q->whereNotExists(function ($sub) use ($userAlias) {
                                $sub->name('user_recharge')
                                    ->whereColumn('uid', $userAlias . 'uid');
                            });
                        });
                    }
                });
            }
        }

        // --- Bộ lọc cân bằng (khoảng thời gian) ---
        // balance: [min, max]
        if (isset($where['balance']) && count($where['balance']) == 2) {
            if ($where['balance'][0] != '' && $where['balance'][1] != '') {
                $model = $model->whereBetween($userAlias . 'now_money', $where['balance']);
            } elseif ($where['balance'][0] != '' && $where['balance'][1] == '') {
                $model = $model->where($userAlias . 'now_money', '>', $where['balance'][0]);
            } elseif ($where['balance'][0] == '' && $where['balance'][1] != '') {
                $model = $model->where($userAlias . 'now_money', '<', $where['balance'][1]);
            }
        }

        // --- Bộ lọc điểm (khoảng thời gian) ---
        // integral: [min, max]
        if (isset($where['integral']) && count($where['integral']) == 2) {
            if ($where['integral'][0] != '' && $where['integral'][1] != '') {
                $model = $model->whereBetween($userAlias . 'integral', $where['integral']);
            } elseif ($where['integral'][0] != '' && $where['integral'][1] == '') {
                $model = $model->where($userAlias . 'integral', '>', $where['integral'][0]);
            } elseif ($where['integral'][0] == '' && $where['integral'][1] != '') {
                $model = $model->where($userAlias . 'integral', '<', $where['integral'][1]);
            }
        }

        // --- Lọc thuộc tính cơ bản ---
        // Cấp độ người dùng
        if (isset($where['level']) && $where['level']) {
            $model = $model->where($userAlias . 'level', $where['level']);
        }
        // Nhóm người dùng
        if (isset($where['group_id']) && $where['group_id']) {
            $model = $model->where($userAlias . 'group_id', $where['group_id']);
        }
        // Trạng thái người dùng
        if (isset($where['status']) && $where['status'] != '') {
            $model = $model->where($userAlias . 'status', $where['status']);
        }
        // Bạn có phải là người quảng bá?
        if (isset($where['is_promoter']) && $where['is_promoter'] != '') {
            $model = $model->where($userAlias . 'is_promoter', $where['is_promoter']);
        }
        
        // --- Bộ lọc thẻ ---
        // label_id: ID đơn hoặc mảng ID/chuỗi được phân tách bằng dấu phẩy
        if (isset($where['label_id']) && $where['label_id']) {
            $model = $model->whereIn($userAlias . 'uid', function ($query) use ($where) {
                if (is_array($where['label_id'])) {
                    $label_ids = array_map('intval', $where['label_id']);
                    $query->name('user_label_relation')->whereIn('label_id', $label_ids)->field('uid')->select();
                } else {
                    if (strpos($where['label_id'], ',') !== false) {
                        $label_ids = array_map('intval', explode(',', $where['label_id']));
                        $query->name('user_label_relation')->whereIn('label_id', $label_ids)->field('uid')->select();
                    } else {
                        $query->name('user_label_relation')->where('label_id', (int)$where['label_id'])->field('uid')->select();
                    }
                }
            });
        }
        
        // --- Bộ lọc trạng thái thành viên ---
        // isMember: 0(không phải thành viên), 1(thành viên)
        if (isset($where['isMember']) && $where['isMember'] != '') {
            if ($where['isMember'] == 0) {
                $model = $model->where($userAlias . 'is_money_level', 0);
            } else {
                $model = $model->where($userAlias . 'is_money_level', '>', 0);
            }
        }

        // --- tìm kiếm từ khóa ---
        // field_key: Chỉ định các trường tìm kiếm (nickname, phone, uid)
        // nickname: Tìm kiếm từ khóa
        $fieldKey = $where['field_key'] ?? '';
        $nickname = $where['nickname'] ?? '';
        if ($fieldKey && $nickname && in_array($fieldKey, $this->withField)) {
            switch ($fieldKey) {
                case "nickname":
                case "phone":
                    $model = $model->where($userAlias . trim($fieldKey), 'like', "%" . trim($nickname) . "%");
                    break;
                case "uid":
                    $model = $model->where($userAlias . trim($fieldKey), trim($nickname));
                    break;
            }
        } else if (!$fieldKey && $nickname) {
            // Khi không có trường nào được chỉ định, tìm kiếm mờ cho biệt danh, UID hoặc số điện thoại di động
            $model = $model->where($userAlias . 'nickname|' . $userAlias . 'uid|' . $userAlias . 'phone', 'LIKE', "%$where[nickname]%");
        }

        // --- Lọc địa lý ---
        // country: domestic(nội địa), abroad(nước ngoài)
        if (isset($where['country']) && $where['country']) {
            if ($where['country'] == 'domestic') {
                $model = $model->where($wechatUserAlias . 'country', 'in', ['Trung Quốc', 'China']);
            } else if ($where['country'] == 'abroad') {
                $model = $model->where($wechatUserAlias . 'country', 'not in', ['Trung Quốc', '']);
            }
        }
        
        // --- Bộ lọc loại khách hàng ---
        // user_type: app, wechat, routine Chờ đợi
        if (isset($where['user_type']) && $where['user_type']) {
            if ($where['user_type'] == 'app') {
                $model = $model->whereIn($userAlias . 'user_type', ['app', 'apple']);
            } else {
                $model = $model->where($userAlias . 'user_type', $where['user_type']);
            }
        }

        // --- Sàng lọc giới tính ---
        // sex: 1(nam giới), 2(nữ giới), 0(không rõ)
        if (isset($where['sex']) && $where['sex'] !== '' && in_array($where['sex'], [0, 1, 2])) {
            $model = $model->where($wechatUserAlias . 'sex', $where['sex']);
        }
        
        // --- Bộ lọc tỉnh ---
        if (isset($where['province']) && $where['province']) {
            $model = $model->where($wechatUserAlias . 'province', $where['province']);
        }
        
        // --- Bộ lọc thành phố ---
        if (isset($where['city']) && $where['city']) {
            $model = $model->where($wechatUserAlias . 'city', $where['city']);
        }

        // --- Bộ lọc thời gian phổ quát ---
        // Sử dụng trình tìm kiếm mô hình time
        if (isset($where['time'])) {
            $model->withSearch(['time'], ['time' => $where['time'], 'timeKey' => 'u.add_time']);
        }

        // --- Xóa bộ lọc trạng thái ---
        if (isset($where['is_del'])) {
            $model->where($userAlias . 'is_del', $where['is_del']);
        }

        // --- Lọc theo ID được chỉ định ---
        if (isset($where['ids']) && count($where['ids'])) {
            $model->whereIn($userAlias . 'uid', $where['ids']);
        }

        // --- Bộ lọc cấp đại lý ---
        if (isset($where['agent_level']) && $where['agent_level'] != '') {
            $model->where($userAlias . 'agent_level', $where['agent_level']);
        }

        return $field ? $model->field($field) : $model;
    }

    /**
     * Nhận giới tính người dùng
     * @param $time
     * @param $userType
     * @return mixed
     */
    public function getSex($time, $userType)
    {
        return $this->getModel()->when($userType != '', function ($query) use ($userType) {
            $query->where($this->join_alis . '.user_type', $userType);
        })->where(function ($query) use ($time) {
            if ($time[0] == $time[1]) {
                $query->whereDay($this->join_alis . '.add_time', $time[0]);
            } else {
                $time[1] = date('Y/m/d', strtotime($time[1]) + 86400);
                $query->whereTime($this->join_alis . '.add_time', 'between', $time);
            }
        })->field('count(' . $this->alias . '.uid) as value,' . $this->join_alis . '.sex as name')
            ->group($this->join_alis . '.sex')->select()->toArray();
    }
}
