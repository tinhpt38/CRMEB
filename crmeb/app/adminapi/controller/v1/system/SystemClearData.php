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
namespace app\adminapi\controller\v1\system;


use app\services\product\product\StoreDescriptionServices;
use app\services\product\product\StoreProductCateServices;
use app\services\product\product\StoreProductCouponServices;
use app\services\product\product\StoreProductReplyServices;
use app\services\product\sku\StoreProductAttrResultServices;
use app\services\product\sku\StoreProductAttrServices;
use app\services\product\sku\StoreProductAttrValueServices;
use think\facade\App;
use app\adminapi\controller\AuthController;
use app\services\system\SystemClearServices;
use app\services\product\product\StoreProductServices;
use app\services\system\attachment\SystemAttachmentServices;


/**
 * Xóa bộ điều khiển dữ liệu mặc định
 * Class SystemClearData
 * @package app\admin\controller\system
 */
class SystemClearData extends AuthController
{
    /**
     * Người xây dựng
     * SystemClearData constructor.
     * @param App $app
     * @param SystemClearServices $services
     */
    public function __construct(App $app, SystemClearServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * cách tiếp cận thống nhất
     * @param $type
     * @return mixed
     */
    public function index($type)
    {
        switch ($type) {
            case 'temp':
                return $this->userTemp();
            case 'recycle':
                return $this->recycleProduct();
            case 'store':
                return $this->storeData();
            case 'category':
                return $this->categoryData();
            case 'order':
                return $this->orderData();
            case 'kefu':
                return $this->kefuData();
            case 'wechat':
                return $this->wechatData();
            case 'article':
                return $this->articleData();
            case 'attachment':
                return $this->attachmentData();
            case 'system':
                return $this->systemData();
            case 'user':
                return $this->userRelevantData();
            default:
                return app('json')->fail('Lỗi tham số');
        }
    }

    /**
     * Xóa các tệp đính kèm tạm thời do người dùng tạo
     * @return mixed
     */
    public function userTemp()
    {
        /** @var SystemAttachmentServices $services */
        $services = app()->make(SystemAttachmentServices::class);
        $imageUrl = $services->getColumn(['module_type' => 2], 'att_dir');
        foreach ($imageUrl as $item) {
            @unlink(app()->getRootPath() . 'public' . $item);
        }
        $services->delete(2, 'module_type');
        $this->services->clearData(['qrcode'], true);
        return app('json')->success('Xóa dữ liệu thành công');
    }

    /**
     * Xóa các mục khỏi thùng rác
     * @return mixed
     */
    public function recycleProduct($id = 0)
    {
        /** @var StoreProductServices $product */
        $product = app()->make(StoreProductServices::class);
        if ($id) {
            $ids = [$id];
        } else {
            $ids = $product->getColumn(['is_del' => 1], 'id');
        }
        //Xóa dữ liệu bảng thông số kỹ thuật
        /** @var StoreProductAttrServices $ProductAttr */
        $productAttr = app()->make(StoreProductAttrServices::class);
        $productAttr->delete([['product_id', 'in', $ids], ['type', '=', '0']]);

        /** @var StoreProductAttrResultServices $productAttrResult */
        $productAttrResult = app()->make(StoreProductAttrResultServices::class);
        $productAttrResult->delete([['product_id', 'in', $ids], ['type', '=', '0']]);

        /** @var StoreProductAttrValueServices $productAttrValue */
        $productAttrValue = app()->make(StoreProductAttrValueServices::class);
        $productAttrValue->delete([['product_id', 'in', $ids], ['type', '=', '0']]);

        //Xóa chi tiết sản phẩm
        /** @var StoreDescriptionServices $productDescription */
        $productDescription = app()->make(StoreDescriptionServices::class);
        $productDescription->delete([['product_id', 'in', $ids], ['type', '=', '0']]);

        //Xóa dữ liệu phân loại liên quan đến sản phẩm
        /** @var StoreProductCateServices $productCate */
        $productCate = app()->make(StoreProductCateServices::class);
        $productCate->delete([['product_id', 'in', $ids]]);

        //Xóa dữ liệu phiếu giảm giá liên quan đến sản phẩm
        /** @var StoreProductCouponServices $productCoupon */
        $productCoupon = app()->make(StoreProductCouponServices::class);
        $productCoupon->delete([['product_id', 'in', $ids]]);

        //Xóa hồ sơ thu thập sản phẩm
        /** @var StoreProductReplyServices $productRelation */
        $productRelation = app()->make(StoreProductReplyServices::class);
        $productRelation->delete([['product_id', 'in', $ids], ['reply_type', '=', 'product']]);

        //Xóa bình luận sản phẩm
        /** @var StoreProductReplyServices $productReply */
        $productReply = app()->make(StoreProductReplyServices::class);
        $productReply->delete([['product_id', 'in', $ids]]);

        /** @var StoreProductServices $services */
        $services = app()->make(StoreProductServices::class);
        if ($id) {
            $services->delete($id);
            return true;
        } else {
            $services->delete(1, 'is_del');
            return app('json')->success('Xóa dữ liệu thành công');
        }
    }

    /**
     * Xóa dữ liệu người dùng
     * @return mixed
     */
    public function userRelevantData()
    {
        $this->services->clearData([
            'agent_level_task_record',
            'member_card',
            'member_card_batch',
            'capital_flow',
            'delivery_service',
            'division_agent_apply',
            'luck_lottery_record',
            'other_order',
            'other_order_status',
            'qrcode',
            'sms_record',
            'store_bargain_user',
            'store_bargain_user_help',
            'store_cart',
            'store_coupon_issue_user',
            'store_coupon_user',
            'store_integral_order',
            'store_integral_order_status',
            'store_order',
            'store_order_cart_info',
            'store_order_economize',
            'store_order_invoice',
            'store_order_refund',
            'store_order_status',
            'store_pink',
            'store_product_relation',
            'store_product_reply',
            'store_service',
            'store_service_feedback',
            'store_service_log',
            'store_service_record',
            'store_visit',
            'system_store_staff',
            'user',
            'user_address',
            'user_bill',
            'user_brokerage',
            'user_brokerage_frozen',
            'user_cancel',
            'user_enter',
            'user_extract',
            'user_friends',
            'user_group',
            'user_invoice',
            'user_label',
            'user_label_relation',
            'user_level',
            'user_money',
            'user_notice',
            'user_notice_see',
            'user_recharge',
            'user_search',
            'user_sign',
            'user_spread',
            'user_visit',
            'wechat_user',
        ], true);
        $this->services->delDirAndFile('./public/uploads/store/comment');
        return app('json')->success('Xóa dữ liệu thành công');
    }

    /**
     * Xóa dữ liệu cửa hàng
     * @return mixed
     */
    public function storeData()
    {
        $this->services->clearData([
            'agent_level_task',
            'agent_level_task_record',
            'article',
            'article_category',
            'article_content',
            'auxiliary',
            'cache',
            'capital_flow',
            'category',
            'delivery_service',
            'division_agent_apply',
            'live_anchor',
            'live_goods',
            'live_room',
            'live_room_goods',
            'luck_lottery',
            'luck_lottery_record',
            'luck_prize',
            'member_card',
            'member_card_batch',
            'message_system',
            'other_order',
            'other_order_status',
            'qrcode',
            'sms_record',
            'store_advance',
            'store_bargain',
            'store_bargain_user',
            'store_bargain_user_help',
            'store_cart',
            'store_category',
            'store_combination',
            'store_coupon_issue',
            'store_coupon_issue_user',
            'store_coupon_product',
            'store_coupon_user',
            'store_integral',
            'store_integral_order',
            'store_integral_order_status',
            'store_order',
            'store_order_cart_info',
            'store_order_economize',
            'store_order_invoice',
            'store_order_refund',
            'store_order_status',
            'store_pink',
            'store_product',
            'store_product_attr',
            'store_product_attr_result',
            'store_product_attr_value',
            'store_product_cate',
            'store_product_coupon',
            'store_product_description',
            'store_product_log',
            'store_product_relation',
            'store_product_reply',
            'store_product_rule',
            'store_product_virtual',
            'store_seckill',
            'store_service',
            'store_service_feedback',
            'store_service_log',
            'store_service_record',
            'store_visit',
            'system_file',
            'system_log',
            'system_notice',
            'system_notice_admin',
            'system_store',
            'system_store_staff',
            'user',
            'user_address',
            'user_bill',
            'user_brokerage',
            'user_brokerage_frozen',
            'user_cancel',
            'user_enter',
            'user_extract',
            'user_friends',
            'user_group',
            'user_invoice',
            'user_label',
            'user_label_relation',
            'user_level',
            'user_money',
            'user_notice',
            'user_notice_see',
            'user_recharge',
            'user_search',
            'user_sign',
            'user_spread',
            'user_visit',
            'wechat_key',
            'wechat_media',
            'wechat_message',
            'wechat_news_category',
            'wechat_qrcode',
            'wechat_qrcode_cate',
            'wechat_qrcode_record',
            'wechat_reply',
            'wechat_user',
        ], true);
        return app('json')->success('Xóa dữ liệu thành công');
    }

    /**
     * Xóa danh mục sản phẩm
     * @return mixed
     */
    public function categoryData()
    {
        $this->services->clearData(['store_category'], true);
        return app('json')->success('Xóa dữ liệu thành công');
    }

    /**
     * Xóa dữ liệu đơn hàng
     * @return mixed
     */
    public function orderData()
    {
        $this->services->clearData([
            'other_order',
            'other_order_status',
            'store_cart',
            'store_integral_order',
            'store_integral_order_status',
            'store_order',
            'store_order_cart_info',
            'store_order_economize',
            'store_order_invoice',
            'store_order_refund',
            'store_order_status',
            'store_pink',
        ], true);
        return app('json')->success('Xóa dữ liệu thành công');
    }

    /**
     * Xóa dữ liệu dịch vụ khách hàng
     * @return mixed
     */
    public function kefuData()
    {
        $this->services->clearData([
            'store_service',
            'store_service_log',
            'store_service_record',
            'store_service_feedback',
            'store_service_speechcraft'
        ], true);
        $this->services->delDirAndFile('./public/uploads/store/service');
        return app('json')->success('Xóa dữ liệu thành công');
    }

    /**
     * Xóa dữ liệu quản lý WeChat
     * @return mixed
     */
    public function wechatData()
    {
        $this->services->clearData([
            'cache',
            'wechat_key',
            'wechat_media',
            'wechat_message',
            'wechat_news_category',
            'wechat_qrcode',
            'wechat_qrcode_cate',
            'wechat_qrcode_record',
            'wechat_reply'
        ], true);
        $this->services->delDirAndFile('./public/uploads/wechat');
        return app('json')->success('Xóa dữ liệu thành công');
    }

    /**
     * Xóa tất cả tệp đính kèm
     * @return mixed
     */
    public function attachmentData()
    {
        $this->services->clearData([
            'system_attachment',
            'system_attachment_category'
        ], true);
        $this->services->delDirAndFile('./public/uploads/');
        return app('json')->success('Xóa dữ liệu thành công');
    }

    //Xóa danh mục nội dung
    public function articleData()
    {
        $this->services->clearData([
            'article_category',
            'article',
            'article_content'
        ], true);
        return app('json')->success('Xóa dữ liệu thành công');
    }

    //Xóa nhật ký hệ thống
    public function systemData()
    {
        $this->services->clearData([
            'system_notice_admin',
            'system_log'
        ], true);
        return app('json')->success('Xóa dữ liệu thành công');
    }

    /**
     * Cách thay thế tên miền
     * @return mixed
     */
    public function replaceSiteUrl()
    {
        list($url) = $this->request->postMore([
            ['url', '']
        ], true);
        if (!$url)
            return app('json')->fail('Vui lòng nhập tên miền cần thay đổi');
        if (!verify_domain($url))
            return app('json')->fail('Tên miền là bất hợp pháp');
        $this->services->replaceSiteUrl($url);
        return app('json')->success('Thay thế thành công');
    }
}
