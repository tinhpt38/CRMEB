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

namespace app\services;

use app\services\user\UserServices;
use crmeb\exceptions\ApiException;
use crmeb\utils\JwtAuth;
use think\facade\Db;
use think\facade\Config;
use think\facade\Route as Url;
use think\Model;

/**
 * Class BaseServices
 * @package app\services
 * @method array|Model|null get($id, ?array $field = []) Lấy một phần dữ liệu
 * @method array|Model|null getOne(array $where, ?string $field = '*') Nhận một phần dữ liệu (không cần sử dụng công cụ tìm kiếm)）
 * @method string|null batchUpdate(array $ids, array $data, ?string $key = null) Sửa hàng loạt
 * @method float sum(array $where, string $field, bool $search = false) Tổng
 * @method mixed update($id, array $data, ?string $field = '') Sửa đổi dữ liệu
 * @method bool be($map, string $field = '') Tìm kiếm xem một phần dữ liệu có tồn tại không
 * @method mixed value(array $where, string $field) Nhận dữ liệu theo các điều kiện được chỉ định
 * @method int count(array $where = []) Số mục dữ liệu được đọc
 * @method int getCount(array $where = []) Lấy tổng số điều kiện nhất định (không cần sử dụng công cụ tìm kiếm)）
 * @method array getColumn(array $where, string $field, string $key = '') Nhận một mảng trường nhất định (không cần sử dụng công cụ tìm kiếm)）
 * @method mixed delete($id, ?string $key = null) Xóa
 * @method mixed save(array $data) lưu dữ liệu
 * @method mixed saveAll(array $data) Lưu dữ liệu theo lô
 * @method Model selectList(array $where, string $field = '*', int $page = 0, int $limit = 0, string $order = '', array $with = [], bool $search = false) Nhận danh sách
 * @method bool bcInc($key, string $incField, string $inc, string $keyField = null, int $acc = 2) Phép cộng có độ chính xác cao
 * @method bool bcDec($key, string $decField, string $dec, string $keyField = null, int $acc = 2) Phép trừ có độ chính xác cao
 * @method mixed decStockIncSales(array $where, int $num, string $stock = 'stock', string $sales = 'sales') Giảm hàng tồn kho và tăng doanh số bán hàng
 * @method mixed incStockDecSales(array $where, int $num, string $stock = 'stock', string $sales = 'sales') Tăng hàng tồn kho và giảm doanh số bán hàng
 */abstract class BaseServices
{

    /**
     * Tiêm mô hình
     * @var object
     */    protected $dao;

    /**
     * Nhận cấu hình phân trang
     * @param bool $isPage
     * @param bool $isRelieve
     * @return int[]
     */    public function getPageValue(bool $isPage = true, bool $isRelieve = true)
    {
        $page = $limit = 0;
        if ($isPage) {
            $page = app()->request->param(Config::get('database.page.pageKey', 'page') . '/d', 0);
            $limit = app()->request->param(Config::get('database.page.limitKey', 'limit') . '/d', 0);
        }
        $limitMax = Config::get('database.page.limitMax');
        $defaultLimit = Config::get('database.page.defaultLimit', 10);
        if ($limit > $limitMax && $isRelieve) {
            $limit = $limitMax;
        }
        return [(int)$page, (int)$limit, (int)$defaultLimit];
    }

    /**
     * Hoạt động giao dịch cơ sở dữ liệu
     * @param callable $closure
     * @param bool $isTran
     * @return mixed
     */    public function transaction(callable $closure, bool $isTran = true)
    {
        return $isTran ? Db::transaction($closure) : $closure();
    }

    /**
     * tạo nêntoken
     * @param int $id
     * @param $type
     * @param string $pwd
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */    public function createToken(int $id, $type, $pwd = '')
    {
        /** @var JwtAuth $jwtAuth */        $jwtAuth = app()->make(JwtAuth::class);
        if ($type == 'api' && !app()->make(UserServices::class)->value(['uid' => $id], 'status')) {
            throw new ApiException('Bạn đã bị cấm đăng nhập, vui lòng liên hệ với quản trị viên');
        }
        if ($type == 'api') {
            $user = app()->make(UserServices::class)->get($id);
            $user = $user->toArray();
            //Đăng nhập Khách hàng tin nhắn tùy chỉnh thành công
            $user['last_time'] = date('Y-m-d H:i:s', $user['last_time']);
            $user['time'] = date('Y-m-d H:i:s');
            event('CustomNoticeListener', [$id, $user, 'login_success']);

            //Đăng nhập Khách hàng sự kiện tùy chỉnh
            event('CustomEventListener', ['user_login', [
                'uid' => $user['uid'],
                'nickname' => $user['nickname'],
                'phone' => $user['phone'],
                'add_time' => date('Y-m-d H:i:s', $user['add_time']),
                'login_time' => date('Y-m-d H:i:s'),
                'time' => $user['time'],
                'last_time' => $user['last_time'],
                'user_type' => $user['user_type']
            ]]);
        }
        return $jwtAuth->createToken($id, $type, ['pwd' => md5($pwd)]);
    }

    /**
     * Nhận địa chỉ định tuyến
     * @param string $path
     * @param array $params
     * @param bool $suffix
     * @param bool $isDomain
     * @return \think\route\Url
     */    public function url(string $path, array $params = [], bool $suffix = false, bool $isDomain = false)
    {
        return Url::buildUrl($path, $params)->suffix($suffix)->domain($isDomain)->build();
    }

    /**
     * Mã hóa băm mật khẩu
     * @param string $password
     * @return false|string|null
     */    public function passwordHash(string $password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        return call_user_func_array([$this->dao, $name], $arguments);
    }

    /**
     * Xử lý dữ liệu thành phố
     * @param $address
     * @return array
     */    public function addressHandle($address)
    {
        if ($address) {
            try {
                preg_match('/(.*?(Tỉnh|khu tự trị|Bắc Kinh|thành phố Thiên Tân|Thượng Hải|thành phố Trùng Khánh|Đặc khu hành chính Macao|Đặc khu hành chính Hồng Kông))/', $address, $matches);
                if (count($matches) > 1) {
                    $province = $matches[count($matches) - 2];
                    $address = preg_replace('/(.*?(Tỉnh|khu tự trị|Bắc Kinh|thành phố Thiên Tân|Thượng Hải|thành phố Trùng Khánh|Đặc khu hành chính Macao|Đặc khu hành chính Hồng Kông))/', '', $address, 1);
                }
                preg_match('/(.*?(thành phố|tỉnh tự trị|khu vực|phân công|quận))/', $address, $matches);
                if (count($matches) > 1) {
                    $city = $matches[count($matches) - 2];
                    $address = str_replace($city, '', $address);
                }
                preg_match('/(.*?(huyện|quận|thị trấn|thị trấn|đường phố))/', $address, $matches);
                if (count($matches) > 1) {
                    $area = $matches[count($matches) - 2];
                    $address = str_replace($area, '', $address);
                }
            } catch (\Throwable $e) {
            }
        }
        return [
            'province' => $province ?? '',
            'city' => $city ?? '',
            'district' => $area ?? '',
            "address" => $address
        ];
    }
}
