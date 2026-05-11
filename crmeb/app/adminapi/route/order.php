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
use think\facade\Route;

/**
 * Định tuyến đơn hàng
 */
Route::group('order', function () {
    //Nhận thông tin chuyển phát nhanh
    Route::get('kuaidi_coms', 'v1.order.StoreOrder/getKuaidiComs')->option(['real_name' => 'Nhận thông tin chuyển phát nhanh']);
    //Hủy vận chuyển của người bán
    Route::post('shipment_cancel_order/:id', 'v1.order.StoreOrder/shipmentCancelOrder')->option(['real_name' => 'Hủy vận chuyển của người bán']);
    //Lệnh in
    Route::get('print/:id', 'v1.order.StoreOrder/order_print')->name('StoreOrderPrint')->option(['real_name' => 'Lệnh in']);
    //danh sách đặt hàng
    Route::get('list', 'v1.order.StoreOrder/lst')->name('StoreOrderList')->option(['real_name' => 'danh sách đặt hàng']);
    //Dữ liệu đặt hàng
    Route::get('chart', 'v1.order.StoreOrder/chart')->name('StoreOrderChart')->option(['real_name' => 'Dữ liệu tiêu đề đơn hàng']);
    //Xóa đơn hàng
    Route::post('write', 'v1.order.StoreOrder/write_order')->name('writeOrder')->option(['real_name' => 'Xóa đơn hàng']);
    //Xóa số đơn đặt hàng
    Route::put('write_update/:order_id', 'v1.order.StoreOrder/write_update')->name('writeOrderUpdate')->option(['real_name' => 'Xóa số đơn đặt hàng']);
    //Nhận mẫu chỉnh sửa đơn hàng
    Route::get('edit/:id', 'v1.order.StoreOrder/edit')->name('StoreOrderEdit')->option(['real_name' => 'Nhận mẫu chỉnh sửa đơn hàng']);
    //Sửa đổi thứ tự
    Route::put('update/:id', 'v1.order.StoreOrder/update')->name('StoreOrderUpdate')->option(['real_name' => 'Sửa đổi thứ tự']);
    //xác nhận đã nhận hàng
    Route::put('take/:id', 'v1.order.StoreOrder/take_delivery')->name('StoreOrderTakeDelivery')->option(['real_name' => 'Xác nhận nhận hàng']);
    //Lô hàng số lượng lớn
    Route::get('delivery/import_express', 'v1.order.StoreOrder/importExpress')->name('importExpress')->option(['real_name' => 'Giao hàng loạt']);
    //Gửi hàng
    Route::put('delivery/:id', 'v1.order.StoreOrder/update_delivery')->name('StoreOrderUpdateDelivery')->option(['real_name' => 'Đã giao cho ĐVVC']);
    //Nhận số tiền vận chuyển từ người bán
    Route::post('price', 'v1.order.StoreOrder/getPrice')->name('getPrice')->option(['real_name' => 'Nhận số tiền vận chuyển từ người bán']);
    //Lấy danh sách các mặt hàng có thể chia nhỏ trong một đơn hàng
    Route::get('split_cart_info/:id', 'v1.order.StoreOrder/split_cart_info')->name('StoreOrderSplitCartInfo')->option(['real_name' => 'Lấy danh sách các mặt hàng có thể chia nhỏ trong một đơn hàng']);
    //Chia đơn hàng và gửi hàng
    Route::put('split_delivery/:id', 'v1.order.StoreOrder/split_delivery')->name('StoreOrderSplitDelivery')->option(['real_name' => 'Chia đơn hàng và gửi hàng']);
    //Lấy danh sách đơn hàng phụ chia nhỏ
    Route::get('split_order/:id', 'v1.order.StoreOrder/split_order')->name('StoreOrderSplitOrder')->option(['real_name' => 'Lấy danh sách đơn hàng phụ chia nhỏ']);
    //Mẫu hoàn tiền đơn hàng
    Route::get('refund/:id', 'v1.order.StoreOrder/refund')->name('StoreOrderRefund')->option(['real_name' => 'Mẫu hoàn tiền đơn hàng']);
    //Hoàn tiền đơn hàng
    Route::put('refund/:id', 'v1.order.StoreOrder/update_refund')->name('StoreOrderUpdateRefund')->option(['real_name' => 'Hoàn tiền đơn hàng']);
    //Nhận mẫu biểu mẫu điện tử
    Route::get('express/temp', 'v1.order.StoreOrder/express_temp')->option(['real_name' => 'Mẫu biểu mẫu điện tử của công ty chuyển phát nhanh']);
    //Nhận thông tin hậu cần
    Route::get('express/:id', 'v1.order.StoreOrder/get_express')->name('StoreOrderUpdateExpress')->option(['real_name' => 'Nhận thông tin hậu cần']);
    //Nhận công ty hậu cần
    Route::get('express_list', 'v1.order.StoreOrder/express')->name('StoreOrdeRexpressList')->option(['real_name' => 'Nhận công ty hậu cần']);
    //Chi tiết đặt hàng
    Route::get('info/:id', 'v1.order.StoreOrder/order_info')->name('StoreOrderorInfo')->option(['real_name' => 'Chi tiết đơn hàng']);
    //Nhận mẫu thông tin vận chuyển
    Route::get('distribution/:id', 'v1.order.StoreOrder/distribution')->name('StoreOrderorDistribution')->option(['real_name' => 'Nhận mẫu thông tin vận chuyển']);
    //Sửa đổi thông tin vận chuyển
    Route::put('distribution/:id', 'v1.order.StoreOrder/update_distribution')->name('StoreOrderorUpdateDistribution')->option(['real_name' => 'Sửa đổi thông tin vận chuyển']);
    //Nhận mẫu đơn không hoàn lại tiền
    Route::get('no_refund/:id', 'v1.order.StoreOrder/no_refund')->name('StoreOrderorNoRefund')->option(['real_name' => 'Nhận mẫu đơn không hoàn lại tiền']);
    //Sửa lý do không hoàn tiền
    Route::put('no_refund/:id', 'v1.order.StoreOrder/update_un_refund')->name('StoreOrderorUpdateNoRefund')->option(['real_name' => 'Sửa lý do không hoàn tiền']);
    //Thanh toán ngoại tuyến
    Route::post('pay_offline/:id', 'v1.order.StoreOrder/pay_offline')->name('StoreOrderorPayOffline')->option(['real_name' => 'Thanh toán ngoại tuyến']);
    //Nhận mẫu hoàn trả điểm
    Route::get('refund_integral/:id', 'v1.order.StoreOrder/refund_integral')->name('StoreOrderorRefundIntegral')->option(['real_name' => 'Nhận mẫu hoàn trả điểm']);
    //Sửa đổi hoàn trả điểm
    Route::put('refund_integral/:id', 'v1.order.StoreOrder/update_refund_integral')->name('StoreOrderorUpdateRefundIntegral')->option(['real_name' => 'Sửa đổi hoàn trả điểm']);
    //Sửa đổi thông tin nhận xét
    Route::put('remark/:id', 'v1.order.StoreOrder/remark')->name('StoreOrderorRemark')->option(['real_name' => 'Sửa đổi thông tin nhận xét']);
    //Nhận trạng thái đơn hàng
    Route::get('status/:id', 'v1.order.StoreOrder/status')->name('StoreOrderorStatus')->option(['real_name' => 'Nhận trạng thái đơn hàng']);
    //Xóa một đơn hàng
    Route::delete('del/:id', 'v1.order.StoreOrder/del')->name('StoreOrderorDel')->option(['real_name' => 'Xóa một đơn hàng']);
    //Xóa đơn hàng theo đợt
    Route::post('dels', 'v1.order.StoreOrder/del_orders')->name('StoreOrderorDels')->option(['real_name' => 'Xóa đơn hàng theo đợt']);
    //Thông tin cấu hình mặc định của thứ tự khuôn mặt
    Route::get('sheet_info', 'v1.order.StoreOrder/getDeliveryInfo')->option(['real_name' => 'Thông tin cấu hình mặc định của thứ tự khuôn mặt']);
    //Nhận mã QR thanh toán ngoại tuyến
    Route::get('offline_scan', 'v1.order.OtherOrder/offline_scan')->name('OfflineScan')->option(['real_name' => 'Nhận mã QR thanh toán ngoại tuyến']);
    //Danh sách thu ngân ngoại tuyến
    Route::get('scan_list', 'v1.order.OtherOrder/scan_list')->name('ScanList')->option(['real_name' => 'Danh sách thu ngân ngoại tuyến']);
    //Thống kê tiêu đề danh sách hóa đơn
    Route::get('invoice/chart', 'v1.order.StoreOrderInvoice/chart')->name('StoreOrderorInvoiceChart')->option(['real_name' => 'Thống kê tiêu đề danh sách hóa đơn']);
    //Yêu cầu danh sách hóa đơn
    Route::get('invoice/list', 'v1.order.StoreOrderInvoice/list')->name('StoreOrderorInvoiceList')->option(['real_name' => 'Yêu cầu danh sách hóa đơn']);
    //Đặt trạng thái hóa đơn
    Route::post('invoice/set/:id', 'v1.order.StoreOrderInvoice/set_invoice')->name('StoreOrderorInvoiceSet')->option(['real_name' => 'Đặt trạng thái hóa đơn']);
    //Chi tiết đơn hàng hóa đơn
    Route::get('invoice_order_info/:id', 'v1.order.StoreOrderInvoice/orderInfo')->name('StoreOrderorInvoiceOrderInfo')->option(['real_name' => 'Chi tiết đơn sản phẩm đơn']);
    //Lấy địa chỉ iframe của trang phát hành hóa đơn
    Route::get('invoice_issuance_url/:id', 'v1.order.StoreOrderInvoice/invoiceIssuanceUrl')->name('invoiceIssuanceUrl')->option(['real_name' => 'Lấy địa chỉ iframe của trang phát hành hóa đơn']);
    //Lưu thông tin hóa đơn
    Route::post('save_invoice_info/:id', 'v1.order.StoreOrderInvoice/saveInvoiceInfo')->name('saveInvoiceInfo')->option(['real_name' => 'Lưu thông tin hóa đơn']);
    //Phân loại hóa đơn điện tử
    Route::get('invoice_category', 'v1.order.StoreOrderInvoice/invoiceCategory')->name('invoiceCategory')->option(['real_name' => 'Phân loại hóa đơn điện tử']);
    //Xuất hóa đơn
    Route::post('invoice_issuance', 'v1.order.StoreOrderInvoice/invoiceIssuance')->name('invoiceIssuance')->option(['real_name' => 'Xuất hóa đơn']);
    //Xem chi tiết hóa đơn
    Route::get('invoice_info/:id', 'v1.order.StoreOrderInvoice/invoiceInfo')->name('invoiceInfo')->option(['real_name' => 'Xem chi tiết hóa đơn']);
    //Xuất hóa đơn âm
    Route::get('red_invoice_issuance/:id', 'v1.order.StoreOrderInvoice/redInvoiceIssuance')->name('redInvoiceIssuance')->option(['real_name' => 'Xuất hóa đơn âm']);
    //Tải hóa đơn xuống
    Route::get('down_invoice/:id', 'v1.order.StoreOrderInvoice/downInvoice')->name('downInvoice')->option(['real_name' => 'Tải hóa đơn xuống']);
    //Cấu hình hoá đơn điện tử
    Route::get('elec_invoice_config', 'v1.order.StoreOrderInvoice/elecInvoiceConfig')->name('elecInvoiceConfig')->option(['real_name' => 'Cấu hình hoá đơn điện tử']);
    //Danh sách người giao hàng
    Route::get('delivery/index', 'v1.order.DeliveryService/index')->option(['real_name' => 'Danh sách người giao hàng']);
    //Thêm hình thức vận chuyển
    Route::get('delivery/add', 'v1.order.DeliveryService/add')->option(['real_name' => 'Thêm hình thức vận chuyển']);
    //Lưu dữ liệu mới tạo
    Route::post('delivery/save', 'v1.order.DeliveryService/save')->option(['real_name' => 'Lưu người giao hàng mới']);
    //Chỉnh sửa mẫu người giao hàng
    Route::get('delivery/:id/edit', 'v1.order.DeliveryService/edit')->option(['real_name' => 'Chỉnh sửa mẫu người giao hàng']);
    //Lưu dữ liệu đã chỉnh sửa
    Route::put('delivery/update/:id', 'v1.order.DeliveryService/update')->option(['real_name' => 'Sửa đổi người giao hàng']);
    //xóa bỏ
    Route::delete('delivery/del/:id', 'v1.order.DeliveryService/delete')->option(['real_name' => 'Xóa người giao hàng']);
    //Sửa đổi trạng thái
    Route::get('delivery/set_status/:id/:status', 'v1.order.DeliveryService/set_status')->option(['real_name' => 'Sửa đổi trạng thái người giao hàng']);
    //Nhận người giao hàng từ danh sách đơn hàng
    Route::get('delivery/list', 'v1.order.DeliveryService/get_delivery_list')->option(['real_name' => 'Nhận người giao hàng từ danh sách đơn hàng']);
    //Danh sách mẫu biểu mẫu điện tử
    Route::get('expr/temp', 'v1.order.StoreOrder/expr_temp')->option(['real_name' => 'Danh sách mẫu biểu mẫu điện tử']);
    //In hóa đơn
    Route::get('print/shipping/:order_id', 'v1.order.StoreOrder/printShipping')->option(['real_name' => 'In hóa đơn']);
    //Thêm thao tác in biểu mẫu điện tử
    Route::get('order_dump/:order_id', 'v1.order.StoreOrder/order_dump')->option(['real_name' => 'Thêm thao tác in biểu mẫu điện tử']);
    //Sửa đổi địa chỉ giao hàng cho các đơn hàng chưa được vận chuyển
    Route::post('edit_address/:id', 'v1.order.StoreOrder/editAddress')->option(['real_name' => 'Sửa đổi địa chỉ giao hàng cho các đơn hàng chưa được vận chuyển']);
    //Lý do hủy đơn (mẫu admin)
    Route::get('cancel_reasons', 'v1.order.StoreOrder/cancel_reasons')->option(['real_name' => 'Danh sách lý do hủy đơn admin']);
    //Hủy đơn admin (chưa thanh toán)
    Route::post('admin_cancel/:id', 'v1.order.StoreOrder/admin_cancel')->option(['real_name' => 'Hủy đơn hàng (admin)']);
    //Sửa số lượng chi tiết đơn (admin, chưa thanh toán)
    Route::post('admin_update_cart_num/:id', 'v1.order.StoreOrder/admin_update_cart_num')->option(['real_name' => 'Sửa số lượng dòng đơn hàng (admin)']);
})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'order', 'mark_name' => 'Quản lý đơn hàng']);

/**
 * Các tuyến đường liên quan đến hậu mãi
 */
Route::group('refund', function () {
    //Danh sách hậu mãi
    Route::get('list', 'v1.order.RefundOrder/getRefundList')->option(['real_name' => 'Danh sách đơn hàng sau bán hàng']);
    //Người bán đồng ý hoàn tiền và chờ người dùng trả lại hàng
    Route::get('agree/:id', 'v1.order.RefundOrder/agreeExpress')->option(['real_name' => 'Người bán đồng ý hoàn tiền và chờ người dùng trả lại hàng']);
    //Ghi chú đơn hàng sau bán hàng
    Route::put('remark/:id', 'v1.order.RefundOrder/remark')->option(['real_name' => 'Ghi chú đơn hàng sau bán hàng']);
    //Mẫu hoàn tiền đơn hàng sau bán hàng
    Route::get('refund/:id', 'v1.order.RefundOrder/refund')->option(['real_name' => 'Mẫu hoàn tiền đơn hàng sau bán hàng']);
    //Hoàn tiền đơn hàng sau bán hàng
    Route::put('refund/:id', 'v1.order.RefundOrder/refundPrice')->option(['real_name' => 'Hoàn tiền đơn hàng sau bán hàng']);
    //Nhận mẫu đơn không hoàn lại tiền
    Route::get('no_refund/:id', 'v1.order.RefundOrder/noRefund')->option(['real_name' => 'Nhận mẫu đơn không hoàn lại tiền']);
    //Sửa lý do không hoàn tiền
    Route::put('no_refund/:id', 'v1.order.RefundOrder/refuseRefund')->option(['real_name' => 'Sửa lý do không hoàn tiền']);
    //Thông tin đơn hàng hoàn tiền
    Route::get('info/:uni', 'v1.order.RefundOrder/getRefundInfo')->option(['real_name' => 'Nhận chi tiết đơn hàng hoàn tiền']);
})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'refund', 'mark_name' => 'Lệnh hoàn tiền']);
