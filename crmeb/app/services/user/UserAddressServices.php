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

namespace app\services\user;

use app\api\validate\user\AddressValidate;
use app\services\BaseServices;
use app\dao\user\UserAddressDao;
use app\services\shipping\SystemCityServices;
use app\services\wechat\WechatUserServices;
use crmeb\exceptions\AdminException;
use crmeb\exceptions\ApiException;

/**
 *
 * Class UserAddressServices
 * @package app\services\user
 * @method getOne(array $where, ?string $field = '*', array $with = []) Lấy một phần dữ liệu
 * @method be($map, string $field = '') Xác minh dữ liệu tồn tại
 */
class UserAddressServices extends BaseServices
{

    /**
     * UserAddressServices constructor.
     * @param UserAddressDao $dao
     */
    public function __construct(UserAddressDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận một địa chỉ duy nhất
     * @param $id
     * @param $field
     * @return array
     */
    public function getAddress($id, $field = [])
    {
        return $this->dao->get($id, $field);
    }

    /**
     * Nhận tất cả địa chỉ
     * @param array $where
     * @param string $field
     * @return array
     */
    public function getAddressList(array $where, string $field = '*'): array
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getList($where, $field, $page, $limit);
        $count = $this->getAddresCount($where);
        return compact('list', 'count');
    }

    /**
     * Nhận tất cả địa chỉ của người dùng
     * @param int $uid
     * @param string $field
     * @return array
     */
    public function getUserAddressList(int $uid, string $field = '*'): array
    {
        [$page, $limit] = $this->getPageValue();
        $where = ['uid' => $uid];
        $where['is_del'] = 0;
        return $this->dao->getList($where, $field, $page, $limit);
    }

    /**
     * Nhận địa chỉ mặc định của người dùng
     * @param int $uid
     * @param string $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserDefaultAddress(int $uid, string $field = '*')
    {
        return $this->dao->getOne(['uid' => $uid, 'is_default' => 1, 'is_del' => 0], $field);
    }

    /**
     * Lấy số lượng mặt hàng
     * @param array $where
     * @return int
     */
    public function getAddresCount(array $where): int
    {
        return $this->dao->count($where);
    }

    /**
     * Thêm địa chỉ
     * @param array $data
     * @return bool
     */
    public function create(array $data)
    {
        if (!$this->dao->save($data))
            throw new AdminException('Thêm không thành công');
        return true;
    }

    /**
     * Sửa đổi địa chỉ
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateAddress(int $id, array $data)
    {
        if (!$this->dao->update($id, $data))
            throw new AdminException('Sửa đổi không thành công');
        return true;
    }

    /**
     * Đặt tùy chỉnh mặc định
     * @param int $uid
     * @param int $id
     * @return bool
     */
    public function setDefault(int $uid, int $id)
    {
        if (!$this->getAddress($id)) {
            throw new ApiException('Địa chỉ không tồn tại');
        }
        if (!$this->dao->update($uid, ['is_default' => 0], 'uid'))
            throw new ApiException('Không thể hủy địa chỉ mặc định ban đầu');
        if (!$this->dao->update($id, ['is_default' => 1]))
            throw new ApiException('Không đặt được địa chỉ mặc định');
        return true;
    }

    /**
     * Nhận một địa chỉ duy nhất
     * @param int $id
     * @return mixed
     */
    public function address(int $id)
    {
        $addressInfo = $this->getAddress($id);
        if (!$addressInfo || $addressInfo['is_del'] == 1) {
            throw new ApiException('Dữ liệu không tồn tại');
        }
        return $addressInfo->toArray();
    }

    /**
     * Thêm vào|Sửa đổi địa chỉ
     * @param int $uid
     * @param array $addressInfo
     * @return mixed
     */
    public function editAddress(int $uid, array $addressInfo)
    {
        if ($addressInfo['id'] == 0) {
            $where = [
                ['uid', '=', $uid],
                ['real_name', '=', $addressInfo['real_name']],
                ['phone', '=', $addressInfo['phone']],
                ['detail', '=', $addressInfo['detail']],
                ['is_del', '=', 0]
            ];
            if (isset($addressInfo['address']['city_id'])) {
                $where += ['city_id', '=', $addressInfo['address']['city_id']];
            }
            $res = $this->dao->getCount($where);
            if ($res) throw new ApiException('Địa chỉ đã tồn tại');
        }

        if ($addressInfo['type'] == 1 && !$addressInfo['id']) {
            $city = $addressInfo['address']['city'];
            /** @var SystemCityServices $systemCity */
            $systemCity = app()->make(SystemCityServices::class);
            $cityInfo = $systemCity->getOne([['name', '=', $city], ['parent_id', '<>', 0]]);
            if ($cityInfo && $cityInfo['city_id']) {
                $addressInfo['address']['city_id'] = $cityInfo['city_id'];
            } else {
                $cityInfo = $systemCity->getOne([['name', 'like', "%$city%"], ['parent_id', '<>', 0]]);
                if (!$cityInfo) {
                    throw new ApiException('Lỗi định dạng địa chỉ giao hàng');
                }
                $addressInfo['address']['city_id'] = $cityInfo['city_id'];
            }
        }
        if (!isset($addressInfo['address']['city_id']) || $addressInfo['address']['city_id'] == 0) throw new ApiException('Thêm không thành công');
        $addressInfo['province'] = $addressInfo['address']['province'];
        $addressInfo['city'] = $addressInfo['address']['city'];
        $addressInfo['city_id'] = $addressInfo['address']['city_id'] ?? 0;
        $addressInfo['district'] = $addressInfo['address']['district'];
        $addressInfo['uid'] = $uid;
        unset($addressInfo['address'], $addressInfo['type']);
        //Xác thực dữ liệu
        validate(AddressValidate::class)->check($addressInfo);
        $address_check = [];
        if ($addressInfo['id']) {
            $address_check = $this->getAddress((int)$addressInfo['id']);
        }
        if ($addressInfo['is_default']) {
            app()->make(WechatUserServices::class)->update(['uid' => $uid], ['province' => $addressInfo['province']]);
        }
        if ($address_check && $address_check['is_del'] == 0 && $address_check['uid'] = $uid) {
            $id = (int)$addressInfo['id'];
            unset($addressInfo['id']);
            if (!$this->dao->update($id, $addressInfo, 'id')) {
                throw new ApiException('Sửa đổi không thành công');
            }
            if ($addressInfo['is_default']) {
                $this->setDefault($uid, $id);
            }
            return ['type' => 'edit', 'msg' => 'Chỉnh sửa địa chỉ thành công', 'data' => []];
        } else {
            $addressInfo['add_time'] = time();

            //Khi thêm địa chỉ lần đầu tiên, địa chỉ đó sẽ tự động được đặt làm địa chỉ mặc định.
            $addrCount = $this->getAddresCount(['uid' => $uid]);
            if (!$addrCount) $addressInfo['is_default'] = 1;

            if (!$address = $this->dao->save($addressInfo)) {
                throw new ApiException('Thêm không thành công');
            }
            if ($addressInfo['is_default']) {
                $this->setDefault($uid, (int)$address->id);
            }
            return ['type' => 'add', 'msg' => 'Đã thêm địa chỉ thành công', 'data' => ['id' => $address->id]];
        }
    }

    /**
     * Xóa địa chỉ
     * @param int $uid
     * @param int $id
     * @return bool
     */
    public function delAddress(int $uid, int $id)
    {
        $addressInfo = $this->getAddress($id);
        if (!$addressInfo || $addressInfo['is_del'] == 1 || $addressInfo['uid'] != $uid) {
            throw new ApiException('Dữ liệu không tồn tại');
        }
        if ($this->dao->update($id, ['is_del' => '1'], 'id'))
            return true;
        else
            throw new ApiException('Xóa không thành công');
    }

    /**
     * Đặt địa chỉ người dùng mặc định
     * @param $id
     * @param $uid
     * @return bool
     */
    public function setDefaultAddress(int $id, int $uid)
    {
        $res1 = $this->dao->update($uid, ['is_default' => 0], 'uid');
        $res2 = $this->dao->update(['id' => $id, 'uid' => $uid], ['is_default' => 1]);
        $res = $res1 !== false && $res2 !== false;
        return $res;
    }
}
