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
namespace crmeb\services\easywechat\wechatlive;

use EasyWeChat\Core\AbstractAPI;
use EasyWeChat\Core\AccessToken;

/**
 * Class ProgramWechatLive
 * @package crmeb\services\wechatlive
 */
class ProgramWechatLive extends AbstractAPI
{

    /**
     * Nhận thông tin danh sách phát sóng trực tiếp
     */
    const API_WECHAT_LIVE = 'https://api.weixin.qq.com/wxa/business/getliveinfo';
    /**
     * Tạo phòng phát sóng trực tiếp
     */
    const CREATE_LIVE_ROOM = 'https://api.weixin.qq.com/wxaapi/broadcast/room/create';
    /**
     * Nhập sản phẩm vào phòng phát sóng trực tiếp
     */
    const LIVE_ROOM_ADD_GOODS = 'https://api.weixin.qq.com/wxaapi/broadcast/room/addgoods';

    /**
     * Nhận thông tin danh sách sản phẩm
     */
    const GOODS_LIST = 'https://api.weixin.qq.com/wxaapi/broadcast/goods/getapproved';
    /**
     * Bổ sung và đánh giá sản phẩm
     */
    const GOODS_ADD = 'https://api.weixin.qq.com/wxaapi/broadcast/goods/add';
    /**
     * Rút lại đánh giá
     */
    const GOODS_RESET_AUDIT = 'https://api.weixin.qq.com/wxaapi/broadcast/goods/resetaudit';
    /**
     * Gửi lại để xem xét
     */
    const GOODS_AUDIT = 'https://api.weixin.qq.com/wxaapi/broadcast/goods/autdit';
    /**
     * Xóa sản phẩm
     */
    const GOODS_DELETE = 'https://api.weixin.qq.com/wxaapi/broadcast/goods/delete';
    /**
     * Cập nhật sản phẩm
     */
    const GOODS_UPDATE = 'https://api.weixin.qq.com/wxaapi/broadcast/goods/update';
    /**
     * Nhận trạng thái sản phẩm
     */
    const GOODS_INFO = 'https://api.weixin.qq.com/wxa/business/getgoodswarehouse';
    /**
     * Nhận danh sách thành viên
     */
    const ROLE_LIST = 'https://api.weixin.qq.com/wxaapi/broadcast/role/getrolelist';
    /**
     * Thêm thông số phòng phát sóng trực tiếp
     * @var array
     */
    protected $create_data = [
        'name' => '',  // tên phòng
        'coverImg' => '',   // Tải lên qua uploadfile và điền vào mediaID
        'startTime' => 0,   // thời gian bắt đầu
        'endTime' => 0, // thời gian kết thúc
        'anchorName' => '',  // Biệt hiệu neo
        'anchorWechat' => '',  // ID WeChat cố định
        'shareImg' => '',  //Tải lên qua uploadfile và điền vào mediaID
        'feedsImg' => '',   //Tải lên qua uploadfile và điền vào mediaID
        'isFeedsPublic' => 1, // Có bật bộ sưu tập chính thức hay không, 1 là bật, 0 là tắt
        'type' => 1, // Loại phát sóng trực tiếp, 1 luồng đẩy 0 phát sóng trực tiếp trên thiết bị di động
        'screenType' => 0,  // 1：Cảnh 0: Màn hình dọc
        'closeLike' => 0, // Có nên đóng lượt thích 1 Đóng không
        'closeGoods' => 0, // Có đóng kệ sản phẩm hay không, 1: Đóng
        'closeComment' => 0, // Có bật bình luận hay không, 1: đã đóng
        'closeReplay' => 1, // Có đóng phát lại hay không 1 Đóng
        'closeShare' => 0,   //  Có đóng chia sẻ hay không 1 Đóng
        'closeKf' => 0 // Có đóng dịch vụ khách hàng hay không, 1 đóng
    ];

    /**
     * ProgramWechatLive constructor.
     * @param AccessToken $accessToken
     */
    public function __construct(AccessToken $accessToken)
    {
        parent::__construct($accessToken);
    }

    /**
     * Lấy danh sách phòng phát sóng trực tiếp
     * @param int $page
     * @param int $limit
     * @return \EasyWeChat\Support\Collection|null
     * @throws \EasyWeChat\Core\Exceptions\HttpException
     */
    public function getLiveInfo(int $page = 1, int $limit = 10)
    {
        $page = ($page - 1) * $limit;
        $params = [
            'start' => $page,
            'limit' => $limit
        ];
        return $this->parseJSON('json', [self::API_WECHAT_LIVE, $params]);
    }

    /**
     * Nhận phát lại phòng phát sóng trực tiếp
     * @param int $room_id
     * @param int $page
     * @param int $limit
     * @return \EasyWeChat\Support\Collection|null
     * @throws \EasyWeChat\Core\Exceptions\HttpException
     */
    public function getLivePlayback(int $room_id, int $page = 1, int $limit = 10)
    {
        $page = ($page - 1) * $limit;
        $params = [
            'action' => 'get_replay',
            'room_id' => $room_id,
            'start' => $page,
            'limit' => $limit
        ];
        return $this->parseJSON('json', [self::API_WECHAT_LIVE, $params]);
    }

    /**
     * Tạo phòng phát sóng trực tiếp
     * @param $data
     * @return \EasyWeChat\Support\Collection|null
     * @throws \EasyWeChat\Core\Exceptions\HttpException
     */
    public function createRoom(array $data)
    {
        $params = array_merge($this->create_data, $data);
        return $this->parseJSON('json', [self::CREATE_LIVE_ROOM, $params]);
    }

    /**
     * Nhập sản phẩm vào phòng phát sóng trực tiếp
     * @param int $room_id
     * @param $ids
     * @return \EasyWeChat\Support\Collection|null
     * @throws \EasyWeChat\Core\Exceptions\HttpException
     */
    public function roomAddGoods(int $room_id, $ids)
    {
        $params = [
            'ids' => $ids,
            'roomId' => $room_id
        ];
        return $this->parseJSON('json', [self::LIVE_ROOM_ADD_GOODS, $params]);
    }

    /**
     * Nhận danh sách sản phẩm
     * @param $status
     * @param int $page
     * @param int $limit
     * @return \EasyWeChat\Support\Collection|null
     * @throws \EasyWeChat\Core\Exceptions\HttpException
     */
    public function getGoodsList($status, int $page = 0, $limit = 30)
    {
        $params = [
            'offset' => $page * $limit,
            'limit' => $limit,
            'status' => $status
        ];
        return $this->parseJSON('json', [self::GOODS_LIST, $params]);
    }

    /**
     * Nhận chi tiết sản phẩm
     * @param $ids
     * @return \EasyWeChat\Support\Collection|null
     * @throws \EasyWeChat\Core\Exceptions\HttpException
     */
    public function getGooodsInfo($ids)
    {
        $params = [
            'goods_ids' => $ids
        ];
        return $this->parseJSON('json', [self::GOODS_INFO, $params]);
    }

    /**
     * Thêm sản phẩm
     * @param string $coverImgUrl
     * @param string $name
     * @param int $priceType
     * @param string $url
     * @param $price
     * @param string $price2
     * @return \EasyWeChat\Support\Collection|null
     * @throws \EasyWeChat\Core\Exceptions\HttpException
     */
    public function addGoods(string $coverImgUrl, string $name, int $priceType, string $url, $price, $price2 = '')
    {
        $params = ['goodsInfo' => [
            'coverImgUrl' => $coverImgUrl,
            'name' => $name,
            'priceType' => $priceType,
            'price' => $price,
            'url' => $url
        ]];
        if ($priceType != 1) $params['goodsInfo']['price2'] = $price2;
        return $this->parseJSON('json', [self::GOODS_ADD, $params]);
    }

    /**
     * Đánh giá rút sản phẩm
     * @param int $goodsId
     * @param int $auditId
     * @return \EasyWeChat\Support\Collection|null
     * @throws \EasyWeChat\Core\Exceptions\HttpException
     */
    public function resetauditGoods(int $goodsId, int $auditId)
    {
        $params = [
            'goodsId' => $goodsId,
            'auditId' => $auditId
        ];
        return $this->parseJSON('json', [self::GOODS_RESET_AUDIT, $params]);
    }

    /**
     * Sản phẩm được gửi lại để xem xét
     * @param int $goodsId
     * @return \EasyWeChat\Support\Collection|null
     * @throws \EasyWeChat\Core\Exceptions\HttpException
     */
    public function auditGoods(int $goodsId)
    {
        $params = [
            'goodsId' => $goodsId
        ];
        return $this->parseJSON('json', [self::GOODS_AUDIT, $params]);
    }

    /**
     * Xóa sản phẩm
     * @param int $goodsId
     * @return \EasyWeChat\Support\Collection|null
     * @throws \EasyWeChat\Core\Exceptions\HttpException
     */
    public function deleteGoods(int $goodsId)
    {
        $params = [
            'goodsId' => $goodsId
        ];
        return $this->parseJSON('json', [self::GOODS_DELETE, $params]);
    }

    /**
     * Cập nhật sản phẩm
     * @param int $goodsId
     * @param string $coverImgUrl
     * @param string $name
     * @param int $priceType
     * @param string $url
     * @param $price
     * @param string $price2
     * @return \EasyWeChat\Support\Collection|null
     * @throws \EasyWeChat\Core\Exceptions\HttpException
     */
    public function updateGoods(int $goodsId, string $coverImgUrl, string $name, int $priceType, string $url, $price, $price2 = '')
    {
        $params = ['goodsInfo' => [
            'goodsId' => $goodsId,
            'coverImgUrl' => $coverImgUrl,
            'name' => $name,
            'priceType' => $priceType,
            'price' => $price,
            'url' => $url
        ]];
        if ($priceType != 1) $params['goodsInfo']['price2'] = $price2;
        return $this->parseJSON('json', [self::GOODS_UPDATE, $params]);
    }

    /**
     * Nhận danh sách thành viên
     * @param int $goodsId
     * @return \EasyWeChat\Support\Collection|null
     * @throws \EasyWeChat\Core\Exceptions\HttpException
     */
    public function getRoleList($role = 2, int $page = 0, $limit = 30, $keyword = '')
    {
        $params = [
            'role' => $role,
            'offset' => $page * $limit,
            'limit' => $limit,
            'keyword' => $keyword
        ];
        return $this->parseJSON('get', [self::ROLE_LIST, $params]);
    }
}
