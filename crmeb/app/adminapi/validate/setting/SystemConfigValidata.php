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

namespace app\adminapi\validate\setting;


use think\Validate;

/**
 * Class SystemConfigValidata
 * @package app\adminapi\validate\setting
 */class SystemConfigValidata extends Validate
{

    protected $regex = ['float_two' => '/^[0-9]+(.[0-9]{1,2})?$/'];
    /**
     * Xác định quy tắc xác thực
     *Định dạng：'Tên trường'    =>    ['luật lệ1','luật lệ2'...]
     *
     * @var array
     */    protected $rule = [
        'site_url' => 'url',
        'store_brokerage_ratio' => 'float|egt:0|elt:100|regex:float_two',
        'store_brokerage_two' => 'float|egt:0|elt:100|regex:float_two',
        'user_extract_min_price' => 'float|gt:0',
        'extract_time' => 'number|between:0,180',
        'replenishment_num' => 'number',
        'store_stock' => 'number',
        'store_brokerage_price' => 'float',
        'integral_ratio' => 'float|egt:0|elt:1000|regex:float_two',
        'order_give_integral' => 'float|egt:0|elt:1000',
        'order_cancel_time' => 'float|egt:0',
        'order_activity_time' => 'float|egt:0',
        'order_bargain_time' => 'float|egt:0',
        'order_seckill_time' => 'float|egt:0',
        'order_pink_time' => 'float|egt:0',
        'system_delivery_time' => 'float',
        'store_free_postage' => 'float',
        'integral_rule_number' => 'number|gt:0',
        'express_rule_number' => 'number|gt:0',
        'sign_rule_number' => 'number|gt:0',
        'offline_rule_number' => 'number|gt:0',
        'order_give_exp' => 'number|egt:0',
        'invite_user_exp' => 'number|egt:0',
        'config_export_to_name' => 'chs|length:2,10',
        'config_export_to_tel' => 'mobile|number',
        'config_export_to_address' => 'chsAlphaNum|length:10,100',
        'config_export_siid' => 'alphaNum|length:10,50',
        'service_feedback' => 'length:10,90',
        'thumb_big_height' => 'number|egt:0',
        'thumb_big_width' => 'number|egt:0',
        'thumb_mid_height' => 'number|egt:0',
        'thumb_mid_width' => 'number|egt:0',
        'thumb_small_height' => 'number|egt:0',
        'thumb_small_width' => 'number|egt:0',
        'watermark_opacity' => 'number|between:0,100',
        'watermark_text' => 'chsAlphaNum|length:1,10',
        'watermark_text_size' => 'number|egt:0',
        'watermark_x' => 'number|egt:0',
        'watermark_y' => 'number|egt:0',
    ];

    /**
     * Xác định thông báo lỗi
     *Định dạng：'Tên trường. Tên quy tắc'    =>    'thông báo lỗi'
     *
     * @var array
     */    protected $message = [
        'site_url.url' => 'Vui lòng nhập một URL hợp lệ',
        'store_brokerage_ratio.float' => 'Tỷ lệ giảm giá cấp đầu tiên phải là một con số',
        'store_brokerage_ratio.regex' => 'Tỷ lệ giảm giá cấp đầu tiên có thể được đặt tối đa là hai chữ số thập phân.',
        'store_brokerage_ratio.egt' => 'Phạm vi tỷ lệ giảm giá cấp đầu tiên là từ 0-100',
        'store_brokerage_ratio.elt' => 'Phạm vi tỷ lệ giảm giá cấp đầu tiên là từ 0-100',
        'store_brokerage_two.float' => 'Tỷ lệ giảm giá thứ cấp phải là một con số',
        'store_brokerage_two.regex' => 'Tỷ lệ giảm giá cấp thứ hai có thể lên tới hai chữ số thập phân.',
        'store_brokerage_two.egt' => 'Phạm vi điền vào tỷ lệ giảm giá cấp thứ hai là từ 0-100',
        'store_brokerage_two.elt' => 'Phạm vi điền vào tỷ lệ giảm giá cấp thứ hai là từ 0-100',
        'replenishment_num.number' => 'Số lượng cần bổ sung phải là một con số',
        'store_stock.number' => 'Cổ phiếu cảnh báo phải là số',
        'store_brokerage_two.between' => 'Phạm vi điền vào tỷ lệ giảm giá cấp thứ hai là từ 0-100',
        'user_extract_min_price.float' => 'Số tiền rút tối thiểu chỉ có thể là số',
        'user_extract_min_price.gt' => 'Số tiền rút tối thiểu phải lớn hơn0',
        'extract_time.number' => 'Phạm vi thời gian đóng băng hoa hồng là từ 0-180',
        'extract_time.between' => 'Phạm vi thời gian đóng băng hoa hồng là từ 0-180',
        'store_brokerage_price.float' => 'Số tiền phân phối đầy đủ phải là một con số',
        'integral_ratio.float' => 'Tỷ lệ đổi điểm phải là số',
        'integral_ratio.regex' => 'Tỷ lệ quy đổi điểm có thể lên tới hai chữ số thập phân.',
        'integral_ratio.egt' => 'Tỷ lệ quy đổi điểm phải nằm Trong khoảng từ 0-1000',
        'integral_ratio.elt' => 'Tỷ lệ quy đổi điểm phải nằm Trong khoảng từ 0-1000',
        'order_give_integral.float' => 'Điểm thưởng khi đặt hàng phải là số',
        'order_give_integral.egt' => 'Điểm thưởng khi đặt hàng phải nằm Trong khoảng từ 0-1000',
        'order_give_integral.elt' => 'Điểm thưởng khi đặt hàng phải nằm Trong khoảng từ 0-1000',
        'order_cancel_time.float' => 'Thời gian hủy đơn hàng thường phải là số',
        'order_cancel_time.egt' => 'Thời gian hủy đơn hàng thường phải lớn hơn hoặc bằng 0',
        'order_activity_time.float' => 'Thời gian hủy đơn hàng hoạt động phải là số',
        'order_activity_time.egt' => 'Thời gian hủy đơn hàng hoạt động phải lớn hơn hoặc bằng 0',
        'order_bargain_time.float' => 'Thời gian hủy đơn hàng mặc cả phải là số',
        'order_bargain_time.egt' => 'Thời gian hủy đơn hàng mặc cả phải lớn hơn hoặc bằng 0',
        'order_seckill_time.float' => 'Thời gian hủy đơn hàng flash sale phải là số',
        'order_seckill_time.egt' => 'Thời gian hủy đơn hàng flash sale phải lớn hơn hoặc bằng 0',
        'order_pink_time.float' => 'Thời gian hủy đơn hàng nhóm mua phải là số',
        'order_pink_time.egt' => 'Thời gian hủy đơn hàng nhóm mua phải lớn hơn hoặc bằng 0',
        'system_delivery_time.float' => 'Thời gian nhận hàng tự động sau khi đơn hàng được vận chuyển phải là số',
        'store_free_postage.float' => 'Số tiền vận chuyển miễn phí phải là một con số',
        'integral_rule_number.number' => 'Hệ số điểm phải lớn hơn0',
        'express_rule_number.number' => 'Số giảm giá phải lớn hơn0',
        'sign_rule_number.number' => 'Hệ số điểm phải lớn hơn0',
        'offline_rule_number.number' => 'Số giảm giá phải lớn hơn0',
        'order_give_exp.number' => 'Tỷ lệ trải nghiệm miễn phí khi đặt hàng phải là một con số',
        'order_give_exp.egt' => 'Tỷ lệ trải nghiệm miễn phí khi đặt hàng phải lớn hơn0',
        'invite_user_exp.number' => 'Mời Khách hàng mới trải nghiệm quà tặng phải là một con số',
        'invite_user_exp.egt' => 'Việc mời Khách hàng mới trải nghiệm quà tặng phải lớn hơn0',
        'config_export_to_name.chs' => 'Tên người gửi hàng phải bằng tiếng Trung',
        'config_export_to_name.length' => 'Độ dài của tên người gửi hàng phải từ 2 đến 10 ký tự.',
        'config_export_to_tel.number' => 'Số điện thoại của người gửi hàng phải là số',
        'config_export_to_tel.mobile' => 'Vui lòng điền số điện thoại di động hợp lệ cho số điện thoại của người gửi hàng',
        'config_export_to_address.chsAlphaNum' => 'Địa chỉ người gửi hàng chỉ có thể là ký tự, chữ cái và số tiếng Trung.',
        'config_export_to_address.length' => 'Độ dài địa chỉ người gửi hàng là 10-100 chữ số',
        'config_export_siid.alphaNum' => 'Số máy in biểu mẫu điện tử phải là số hoặc chữ cái',
        'config_export_siid.length' => 'Độ dài số máy in biểu mẫu điện tử là 10-50 chữ số',
        'service_feedback.length' => 'Độ dài phản hồi của CSKH dao động từ 10 đến 90 ký tự',
        'thumb_big_height.number' => 'Kích thước hình thu nhỏ (chiều cao) phải là số',
        'thumb_big_height.egt' => 'Kích thước ảnh thu nhỏ (chiều cao) phải lớn hơn hoặc bằng0',
        'thumb_big_width.number' => 'Kích thước hình thu nhỏ (chiều rộng) phải là số',
        'thumb_big_width.egt' => 'Kích thước hình ảnh thu nhỏ (chiều rộng) phải lớn hơn hoặc bằng0',
        'thumb_mid_height.number' => 'Kích thước hình ảnh (chiều cao) trong hình thu nhỏ phải là số',
        'thumb_mid_height.egt' => 'Kích thước hình ảnh (chiều cao) trong hình thu nhỏ phải lớn hơn hoặc bằng0',
        'thumb_mid_width.number' => 'Kích thước (chiều rộng) hình ảnh trong hình thu nhỏ phải là số',
        'thumb_mid_width.egt' => 'Kích thước (chiều rộng) hình ảnh trong hình thu nhỏ phải lớn hơn hoặc bằng0',
        'thumb_small_height.number' => 'Kích thước hình thu nhỏ (chiều cao) phải là số',
        'thumb_small_height.egt' => 'Kích thước hình thu nhỏ (chiều cao) phải lớn hơn hoặc bằng0',
        'thumb_small_width.number' => 'Kích thước hình thu nhỏ (chiều rộng) phải là số',
        'thumb_small_width.egt' => 'Kích thước hình ảnh thu nhỏ (chiều rộng) phải lớn hơn hoặc bằng0',
        'watermark_text.chsAlphaNum' => 'Văn bản hình mờ chỉ có thể là ký tự, chữ cái và số tiếng Trung',
        'watermark_text.length' => 'Độ dài văn bản hình mờ là 1-10 ký tự',
        'watermark_text_size.number' => 'Kích thước văn bản hình mờ phải là số',
        'watermark_text_size.egt' => 'Kích thước văn bản hình mờ phải lớn hơn hoặc bằng0',
        'watermark_x.number' => 'Phần bù trục hoành của hình mờ phải là một số',
        'watermark_x.egt' => 'Độ lệch trục hoành của hình mờ phải lớn hơn hoặc bằng0',
        'watermark_y.number' => 'Độ lệch tọa độ dọc của hình mờ phải là một số',
        'watermark_y.egt' => 'Độ lệch tọa độ dọc của hình mờ phải lớn hơn hoặc bằng0',
    ];

    protected $scene = [

    ];
}
