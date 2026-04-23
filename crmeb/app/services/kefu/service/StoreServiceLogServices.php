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

namespace app\services\kefu\service;


use app\dao\service\StoreServiceLogDao;
use app\services\BaseServices;
use app\services\order\StoreOrderServices;
use app\services\product\product\StoreProductServices;

/**
 * Lịch sử trò chuyện dịch vụ khách hàng
 * Class StoreServiceLogServices
 * @package app\services\kefu\service
 * @method whereByCount(array $where) Lấy số lượng vật phẩm dựa trên điều kiện
 * @method getServiceList(array $where, int $page, int $limit, array $field = ['*']) Nhận lịch sử trò chuyện và phân trang
 * @method saveAll(array $data) Chèn dữ liệu
 * @method getMessageNum(array $where) Lấy số lượng bản ghi trò chuyện
 */
class StoreServiceLogServices extends BaseServices
{
    /**
     * Loại tin nhắn
     * @var array  1=Từ 2=sự biểu lộ 3=hình ảnh 4=tiếng nói 5 = Liên kết sản phẩm 6 = Loại lệnh
     */
    const MSN_TYPE = [1, 2, 3, 4, 5, 6];

    /**
     * Loại thông báo liên kết sản phẩm
     */
    const MSN_TYPE_GOODS = 5;

    /**
     * Loại tin nhắn thông tin đặt hàng
     */
    const MSN_TYPE_ORDER = 6;

    /**
     * Người xây dựng
     * StoreServiceLogServices constructor.
     * @param StoreServiceLogDao $dao
     */
    public function __construct(StoreServiceLogDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Lấy uid và uid trong lịch sử trò chuyệnto_uid
     * @param int $uid
     * @return array
     */
    public function getChatUserIds(int $uid)
    {
        $list = $this->dao->getServiceUserUids($uid);
        $arr_user = $arr_to_user = [];
        foreach ($list as $key => $value) {
            array_push($arr_user, $value["uid"]);
            array_push($arr_to_user, $value["to_uid"]);
        }
        $uids = array_merge($arr_user, $arr_to_user);
        $uids = array_flip(array_flip($uids));
        $uids = array_flip($uids);
        unset($uids[$uid]);
        return array_flip($uids);
    }

    /**
     * Nhận lịch sử trò chuyện dịch vụ khách hàng của người dùng
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getChatLogList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getServiceList($where, $page, $limit);
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * Nhận danh sách lịch sử trò chuyện
     * @param array $where
     * @param int $uid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getChatList(array $where, int $uid)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getServiceList($where, $page, $limit);
        return $this->tidyChat($list);
    }

    /**
     * Định dạng danh sách trò chuyện
     * @param array $list
     * @param int $uid
     * @return array
     */
    public function tidyChat(array $list)
    {
        $productIds = $orderIds = $productList = $orderInfo = $toUser = $user = [];
        $toUid = $list[0]['to_uid'] ?? 0;
        $uid = $list[0]['uid'] ?? 0;
        foreach ($list as &$item) {
            $item['_add_time'] = $item['add_time'];
            $item['add_time'] = strtotime($item['_add_time']);
            $item['productInfo'] = $item['orderInfo'] = null;
            if ($item['msn_type'] == self::MSN_TYPE_GOODS && $item['msn']) {
                $productIds[] = $item['msn'];
            } elseif ($item['msn_type'] == self::MSN_TYPE_ORDER && $item['msn']) {
                $orderIds[] = $item['msn'];
            }
        }
        if ($productIds) {
            /** @var StoreProductServices $productServices */
            $productServices = app()->make(StoreProductServices::class);
            $where = [
                ['id', 'in', $productIds],
                ['is_del', '=', 0],
                ['is_show', '=', 1],
            ];
            $productList = get_thumb_water($productServices->getProductArray($where, '*', 'id'),'mid');
        }
        /** @var StoreOrderServices $orderServices */
        $orderServices = app()->make(StoreOrderServices::class);
        if ($orderIds) {
            $orderWhere = [
                ['order_id|unique', 'in', $orderIds],
                ['is_del', '=', 0],
            ];
            $orderInfo = $orderServices->getColumn($orderWhere, '*', 'order_id');
        }
        if ($toUid && $uid) {
            /** @var StoreServiceRecordServices $recordServices */
            $recordServices = app()->make(StoreServiceRecordServices::class);
            $toUser = $recordServices->get(['user_id' => $uid, 'to_uid' => $toUid], ['nickname', 'avatar']);
            $user = $recordServices->get(['user_id' => $toUid, 'to_uid' => $uid], ['nickname', 'avatar']);
        }

        foreach ($list as &$item) {
            if ($item['msn_type'] == self::MSN_TYPE_GOODS && $item['msn']) {
                $item['productInfo'] = $productList[$item['msn']] ?? null;
            } elseif ($item['msn_type'] == self::MSN_TYPE_ORDER && $item['msn']) {
                $order = $orderInfo[$item['msn']] ?? null;
                if ($order) {
                    $order = $orderServices->tidyOrder($order, true, true);
                    $order['add_time_y'] = date('Y-m-d', $order['add_time']);
                    $order['add_time_h'] = date('H:i:s', $order['add_time']);
                    $item['orderInfo'] = $order;
                } else {
                    $item['orderInfo'] = null;
                }
            }
            $item['msn_type'] = (int)$item['msn_type'];
            if (!isset($item['nickname'])) {
                $item['nickname'] = '';
            }
            if (!isset($item['avatar'])) {
                $item['avatar'] = '';
            }

            if (!$item['avatar'] && !$item['nickname']) {
                if ($item['uid'] == $uid && $item['to_uid'] == $toUid) {
                    $item['nickname'] = $user['nickname'] ?? '';
                    $item['avatar'] = $user['avatar'] ?? '';
                }
                if ($item['uid'] == $toUid && $item['to_uid'] == $uid) {
                    $item['nickname'] = $toUser['nickname'] ?? '';
                    $item['avatar'] = $toUser['avatar'] ?? '';
                }
            }
            if ($item['avatar']) $item['avatar'] = set_file_url($item['avatar']);
        }
        return $list;
    }

    /**
     * Nhận lịch sử trò chuyện
     * @param array $where
     * @param int $page
     * @param int $limit
     * @param bool $isUp
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getServiceChatList(array $where, int $limit, int $upperId)
    {
        return $this->dao->getChatList($where, $limit, $upperId);
    }
}
