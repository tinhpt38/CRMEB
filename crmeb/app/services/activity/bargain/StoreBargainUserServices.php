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

use app\Request;
use app\services\BaseServices;
use app\dao\activity\bargain\StoreBargainUserDao;

/**
 *
 * Class StoreBargainUserServices
 * @package app\services\activity
 * @method getAllCount(array $where)
 * @method count(array $where)
 * @method value(array $where, ?string $field)
 * @method getBargainUserTableId(int $bargainId, int $bargainUserUid)
 * @method update(int $bargainId, array $data)
 * @method getOne(array $where, ?string $field = '*', array $with = [])
 * @method updateBargainStatus(int $id, ?int $status = 3)
 */class StoreBargainUserServices extends BaseServices
{

    /**
     * StoreBargainUserServices constructor.
     * @param StoreBargainUserDao $dao
     */    public function __construct(StoreBargainUserDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * TODO Lấy số lượng người tham gia dựa trên số lượng sản phẩm thương lượng
     * @param int $bargainId $bargainId  Sản phẩm trả giáID
     * @param int $status $status  Trạng thái 1 Đang tiến hành 2 Không thể kết thúc 3 Đã kết thúc thành công
     * @return array
     */    public function getUserIdList($bargainId = 0, $status = 1)
    {
        $ids = $this->dao->getColumn(['bargain_id' => $bargainId], 'id');
        /** @var StoreBargainUserHelpServices $bargainHelp */        $bargainHelp = app()->make(StoreBargainUserHelpServices::class);
        return $bargainHelp->getCount([['bargain_user_id', 'in', $ids], ['bargain_id', '=', $bargainId]]);
    }

    /**
     * Nhận được một món hời
     * @param Request $request
     * @param int $bargainId
     * @param int $bargainUserUid
     * @return mixed
     */    public function helpCount(Request $request, int $bargainId, int $bargainUserUid)
    {
        $bargainUserTableId = $this->dao->value(['bargain_id' => $bargainId, 'uid' => $bargainUserUid, 'is_del' => 0, 'status' => 1]);//TODO Lấy số bảng thương lượng tham gia của Khách hàng
        $data['userBargainStatus'] = $this->isBargainUserHelpCount($bargainId, $request->uid(), $bargainUserTableId);
        /** @var StoreBargainUserHelpServices $helpService */        $helpService = app()->make(StoreBargainUserHelpServices::class);
        if ($bargainUserTableId) {
            $count = $helpService->count(['bargain_user_id' => $bargainUserTableId, 'bargain_id' => $bargainId]);//TODO Lấy tổng số người giúp đỡ thương lượng
            $price = $this->getSurplusPrice($bargainUserTableId, 1);//TODO Nhận số tiền còn lại của món hời
            $alreadyPrice = $this->dao->value(['id' => $bargainUserTableId], 'price');//TODO Giá mà Khách hàng đã cắt giảm. Nhận mức giá mà Khách hàng đã cắt sau khi người bạn đã mặc cả.
            $pricePercent = $this->getSurplusPrice($bargainUserTableId, 2);//TODO Nhận thanh tiến trình thương lượng
            $data['count'] = $count;
            $data['price'] = $price;
            $data['status'] = $this->dao->value(['id' => $bargainUserTableId], 'status') ?? 0;
            $data['alreadyPrice'] = $alreadyPrice;
            $data['pricePercent'] = $pricePercent > 10 ? $pricePercent : 10;
        } else {
            /** @var StoreBargainServices $bargainService */            $bargainService = app()->make(StoreBargainServices::class);
            $data['count'] = 0;
            $data['price'] = $bargainService->value(['id' => $bargainId], 'price - min_price');
            $data['status'] = $this->dao->value(['id' => $bargainUserTableId], 'status') ?? 0;
            $data['alreadyPrice'] = 0;
            $data['pricePercent'] = 0;
        }
        return $data;
    }

    /**
     * Nhận trạng thái thương lượng
     * @param int $bargainId
     * @param int $bargainUserUid
     * @param int $bargainUserHelpUid
     * @param $bargainUserTableId
     * @return bool
     */    public function isBargainUserHelpCount($bargainId, $bargainUserHelpUid, $bargainUserTableId)
    {
        /** @var StoreBargainUserHelpServices $userHelp */        $userHelp = app()->make(StoreBargainUserHelpServices::class);
        $count = $userHelp->count(['bargain_id' => $bargainId, 'bargain_user_id' => $bargainUserTableId, 'uid' => $bargainUserHelpUid]);
        if (!$count) return true;
        else return false;
    }

    /**
     * Nhận số tiền còn lại của món hời hoặc tỷ lệ phần trăm của món hời
     * @param $bargainUserTableId
     * @param $type
     * @return float
     */    public function getSurplusPrice($bargainUserTableId, $type)
    {
        $coverPrice = $this->getBargainUserDiffPriceFloat($bargainUserTableId);//TODO Nhận số tiền mà Khách hàng có thể cắt giảm. Nhận số tiền thương lượng sau khi người bạn mặc cả.
        $alreadyPrice = $this->dao->value(['id' => $bargainUserTableId], 'price');//TODO Giá mà Khách hàng đã cắt giảm. Nhận mức giá mà Khách hàng đã cắt sau khi người bạn đã mặc cả.
        if ($type == 1) {
            return (float)bcsub((string)$coverPrice, (string)$alreadyPrice, 2);//TODO Mức giá mà thặng dư Khách hàng cần phải được cắt giảm
        } else {
            if ($alreadyPrice) return (int)bcmul((string)bcdiv((string)$alreadyPrice, (string)$coverPrice, 2), '100', 0);
            else return 100;
        }
    }

    /**
     * Nhận số tiền mà Khách hàng có thể cắt giảm. Nhận số tiền thương lượng sau khi người bạn mặc cả.
     * @param $id
     * @return float
     */    public function getBargainUserDiffPriceFloat($id)
    {
        $price = $this->dao->get($id);
        return (float)bcsub((string)$price['bargain_price'], (string)$price['bargain_price_min'], 2);
    }

    /**
     * Thêm thông tin thương lượng
     * @param int $bargainId
     * @param int $bargainUserUid
     * @param array $bargainInfo
     * @return mixed
     */    public function setBargain(int $bargainId, int $bargainUserUid, array $bargainInfo)
    {
        $data['bargain_id'] = $bargainId;
        $data['uid'] = $bargainUserUid;
        $data['bargain_price_min'] = $bargainInfo['min_price'];
        $data['bargain_price'] = $bargainInfo['price'];
        $data['price'] = 0;
        $data['status'] = 1;
        $data['is_del'] = 0;
        $data['add_time'] = time();
        return $this->dao->save($data);
    }


    /**
     * Sửa đổi trạng thái thương lượng
     * @param $uid
     * @return bool
     */    public function editBargainUserStatus($uid)
    {
        $currentBargain = $this->dao->getColumn(['uid' => $uid, 'is_del' => 0, 'status' => 1], 'bargain_id');
        /** @var StoreBargainServices $bargainService */        $bargainService = app()->make(StoreBargainServices::class);
        $bargainProduct = $bargainService->validWhere()->column('id');
        $closeBargain = [];
        foreach ($currentBargain as $key => &$item) {
            if (!in_array($item, $bargainProduct)) {
                $closeBargain[] = $item;
            }
        }// TODO Nhận hàng giá hời đã hết
        if (count($closeBargain)) $this->dao->update([['uid', '=', $uid], ['status', '=', 1], ['bargain_id', 'in', implode(',', $closeBargain)]], ['status' => 2]);
    }


    /**
     * TODO Nhận vật phẩm giá hời của Khách hàng
     * @param int $bargainUserUid $bargainUserUid  Kích hoạt ID Khách hàng thương lượng
     * @return array
     */    public function getBargainUserAll(int $bargainUserUid)
    {
        if (!$bargainUserUid) return [];
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->userAll($bargainUserUid, $page, $limit);
        $bargainHelpServices = app()->make(StoreBargainUserHelpServices::class);
        foreach ($list as &$item) {
            $item['residue_price'] = bcsub((string)$item['bargain_price'], (string)$item['price'], 2);
            if ($item['status'] == 3) {
                $item['success_time'] = date('Y-m-d H:i:s', (int)$bargainHelpServices->getMax(['bargain_user_id' => $item['id']], 'add_time'));
            } else {
                $item['success_time'] = '';
            }
        }
        return $list;
    }

    /**
     * Hủy bỏ thương lượng
     * @param $bargainId
     * @param $uid
     * @return mixed
     */    public function cancelBargain($bargainId, $uid)
    {
        $status = $this->dao->getBargainUserStatus($bargainId, $uid);
        if ($status != 1) return app('json')->fail('Hủy không thành công');
        $id = $this->dao->value(['bargain_id' => $bargainId, 'uid' => $uid, 'is_del' => 0], 'id');
        return $this->dao->update($id, ['is_del' => 1, 'status' => 2]);
    }

    /**
     * Sửa đổi trạng thái thương lượng khi xóa và xóa món hời. Cuộc mặc cả thất bại.
     * @param $bargain_id
     */    public function userBargainStatusFail($bargain_id, $is_true)
    {
        if ($is_true) {
            $this->dao->delete(['bargain_id' => $bargain_id]);
            /** @var StoreBargainUserHelpServices $service */            $service = app()->make(StoreBargainUserHelpServices::class);
            $service->delete(['bargain_id' => $bargain_id]);
        } else {
            $this->dao->update(['bargain_id' => $bargain_id, 'status' => 1], ['status' => 2]);
        }

    }

    /**
     * Lịch sử trả giá
     * @param $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function bargainUserList($where)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->bargainUserList($where, $page, $limit);
        $count = $this->dao->count($where);
        /** @var StoreBargainUserHelpServices $bargainUserHelpService */        $bargainUserHelpService = app()->make(StoreBargainUserHelpServices::class);
        $nums = $bargainUserHelpService->getNums();
        foreach ($list as &$item) {
            $item['num'] = $item['people_num'] - $nums[$item['id']];
            $item['already_num'] = $nums[$item['id']] ?? 0;
            $item['now_price'] = bcsub((string)$item['bargain_price'], (string)$item['price'], 2);
            $item['add_time'] = $item['add_time'] ? date('Y-m-d H:i:s', (int)$item['add_time']) : '';
            if ($item['datatime'] < time() && $item['status'] == 1) {
                $item['status'] = 2;
            }
            $item['datatime'] = $item['datatime'] ? date('Y-m-d H:i:s', (int)$item['datatime']) : '';
        }
        return compact('list', 'count');
    }
}
