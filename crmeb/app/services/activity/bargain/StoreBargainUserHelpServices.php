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

namespace app\services\activity\bargain;

use app\services\BaseServices;
use app\dao\activity\bargain\StoreBargainUserHelpDao;
use app\services\user\UserServices;
use crmeb\exceptions\AdminException;
use crmeb\exceptions\ApiException;

/**
 *
 * Class StoreBargainUserHelpServices
 * @package app\services\activity
 * @method getHelpAllCount(array $where)
 * @method count(array $where)
 */class StoreBargainUserHelpServices extends BaseServices
{

    /**
     * StoreBargainUserHelpServices constructor.
     * @param StoreBargainUserHelpDao $dao
     */    public function __construct(StoreBargainUserHelpDao $dao)
    {
        $this->dao = $dao;
    }

//    /**
//     * TODO Nhận số tiền thương lượng còn lại của Khách hàng
//     * @param int $bargainId $bargainId Số mặt hàng mặc cả
//     * @param int $bargainUserUid $bargainUserUid Kích hoạt ID Khách hàng thương lượng
//     * @return float
//     * @throws \think\db\exception\DataNotFoundException
//     * @throws \think\db\exception\ModelNotFoundException
//     * @throws \think\exception\DbException
//     */
//    public function getSurplusPrice($bargainId = 0, $bargainUserUid = 0)
//    {
//        /** @var StoreBargainServices $bargainUserService */
//        $bargainUserService = app()->make(StoreBargainServices::class);
//        $bargainUserTableId = $bargainUserService->getBargainUserTableId($bargainId, $bargainUserUid);// TODO Lấy số bảng thương lượng tham gia của Khách hàng
//        $coverPrice = $bargainUserService->getBargainUserDiffPriceFloat($bargainUserTableId);//TODO Nhận số tiền mà Khách hàng có thể cắt giảm. Nhận số tiền thương lượng sau khi người bạn mặc cả.
//        $alreadyPrice = $bargainUserService->getBargainUserPrice($bargainUserTableId);//TODO Giá mà Khách hàng đã cắt giảm. Nhận mức giá mà Khách hàng đã cắt sau khi người bạn đã mặc cả.
//        $surplusPrice = (float)bcsub((string)$coverPrice, (string)$alreadyPrice, 2);//TODO Mức giá mà thặng dư Khách hàng cần phải được cắt giảm
//        return $surplusPrice;
//    }

    /**
     * Nhận danh sách trợ giúp thương lượng
     * @param int $bid
     * @param int $page
     * @param int $limit
     * @return array
     */    public function getHelpList(int $bid, int $page = 0, int $limit = 0)
    {
        $list = $this->dao->getHelpList($bid, $page, $limit);
        if ($list) {
            $ids = array_unique(array_column($list, 'uid'));
            /** @var UserServices $userService */            $userService = app()->make(UserServices::class);
            $userInfos = $userService->getColumn([['uid', 'in', $ids]], 'nickname,avatar', 'uid');
            foreach ($list as $key => &$value) {
                $userInfo = $userInfos[$value['uid']] ?? [];
                if ($userInfo) {
                    $value['nickname'] = $userInfo['nickname'];
                    $value['avatar'] = $userInfo['avatar'];
                } else {
                    $value['nickname'] = 'Người dùng này đã hết hạn';
                    $value['avatar'] = '';
                }
                unset($value['id']);
            }
        }
        return array_values($list);
    }

    /**
     * Xác định xem bạn có thể mặc cả hay không
     * @param $bargainId
     * @param $bargainUserTableId
     * @param $uid
     * @return bool
     */    public function isBargainUserHelpCount($bargainId, $bargainUserTableId, $uid)
    {
        $count = $this->dao->count(['bargain_id' => $bargainId, 'bargain_user_id' => $bargainUserTableId, 'uid' => $uid]);
        if (!$count) return true;
        else return false;
    }

    /**
     * Người dùng mặc cả và viết biên bản mặc cả
     * @param $uid
     * @param $bargainUserInfo
     * @param $bargainInfo
     * @return false|string
     */    public function setBargainRecord($uid, $bargainUserInfo, $bargainInfo)
    {
        //Số người tham gia thương lượng
        $people = $this->dao->count(['bargain_user_id' => $bargainUserInfo['id']]);
        //Số tiền ưu đãi còn lại
        $coverPrice = bcsub((string)$bargainUserInfo['bargain_price'], (string)$bargainUserInfo['bargain_price_min'], 2);
        $surplusPrice = bcsub((string)$coverPrice, (string)$bargainUserInfo['price'], 2);//TODO Mức giá mà thặng dư Khách hàng cần phải được cắt giảm
        if (0.00 === (float)$surplusPrice) throw new ApiException('Cuộc thương lượng đã kết thúc');
        if (($bargainInfo['people_num'] - $people) == 1) {
            $price = $surplusPrice;
        } else {
            /** @var UserServices $userServices */            $userServices = app()->make(UserServices::class);
            $userInfo = $userServices->get($uid);
            $price = $this->randomFloat($surplusPrice, $bargainInfo['people_num'] - $people, $userInfo->add_time == $userInfo->last_time && !$this->dao->count(['uid' => $uid]));
        }
        $allPrice = bcadd((string)$bargainUserInfo['price'], (string)$price, 2);
        if ($bargainUserInfo['uid'] == $uid) {
            $type = 1;
        } else {
            //Giới hạn số lần hack
            $count = $this->dao->count(['uid' => $uid, 'bargain_id' => $bargainInfo['id'], 'type' => 0]);
            if ($count >= $bargainInfo['bargain_num']) throw new ApiException('Bạn không còn có thể giúp bán mặt hàng này');
            $type = 0;
        }
        /** @var StoreBargainUserServices $bargainUserService */        $bargainUserService = app()->make(StoreBargainUserServices::class);
        $res1 = $bargainUserService->update($bargainUserInfo['id'], ['price' => $allPrice]);
        $res2 = $this->dao->save([
            'uid' => $uid,
            'bargain_id' => $bargainInfo['id'],
            'bargain_user_id' => $bargainUserInfo['id'],
            'price' => $price,
            'add_time' => time(),
            'type' => $type,
        ]);
        $res = $res1 && $res2;
        if (!$res) throw new AdminException('Thương lượng thất bại');
        return $price;
    }


    /**
     * số tiền ngẫu nhiên
     * @param $price
     * @param $people
     * @param $type
     * @return string
     */    public function randomFloat($price, $people, $type = false)
    {
        //Tính toán số tiền giữ lại dựa trên số lượng người
        $retainPrice = bcmul((string)$people, '0.01', 2);
        //số tiền thực tế còn lại
        $price = bcsub((string)$price, $retainPrice, 2);
        //Tính tỷ lệ
        if ($type) {
            $percent = '0.5';
        } else {
            $percent = bcdiv((string)mt_rand(20, 50), '100', 2);
        }
        //Số tiền thực tế cắt giảm
        $cutPrice = bcmul($price, $percent, 2);
        //Nếu giá trị được tính toán là 0, nó sẽ bị cắt theo mặc định.0.01
        return $cutPrice != '0.00' ? $cutPrice : '0.01';
    }

    /**
     * Lấy số lượng người đã giảm giá sản phẩm ở mức giá ưu đãi
     * @return array
     */    public function getNums()
    {
        $nums = $this->dao->getNums();
        $dat = [];
        foreach ($nums as $item) {
            $dat[$item['bargain_user_id']] = $item['num'];
        }
        return $dat;
    }
}
