#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Đọc và phân tích crmeb.sql, sinh patch dịch tiếng Việt theo cơ chế đa ngôn ngữ (INSERT, không UPDATE):
- eb_system_config_tab  → INSERT vào eb_system_config_tab_lang (title theo lang_type_id)
- eb_system_config      → INSERT vào eb_system_config_lang (info, desc, parameter theo lang_type_id)

Chạy: python scripts/patch_system_config_vi.py
Đầu vào: crmeb/public/install/crmeb.sql
Đầu ra: crmeb/public/install/patch_system_config_tab_lang_vi.sql
        crmeb/public/install/patch_system_config_lang_vi.sql  (bảng eb_system_config_lang + INSERT)
        scripts/system_config_translate_report.json
"""

import re
import json
import os
from pathlib import Path

# --- Đường dẫn ---
REPO_ROOT = Path(__file__).resolve().parent.parent
SQL_FILE = REPO_ROOT / "crmeb/public/install/crmeb.sql"
PATCH_TAB_LANG = REPO_ROOT / "crmeb/public/install/patch_system_config_tab_lang_vi.sql"
PATCH_CONFIG_LANG = REPO_ROOT / "crmeb/public/install/patch_system_config_lang_vi.sql"
REPORT_JSON = REPO_ROOT / "scripts/system_config_translate_report.json"
LANG_TYPE_VI = 10  # eb_lang_type.id cho vi-VN

# --- Bảng dịch tên tab: (tab_id, title_zh) -> title_vi (để phân biệt các "基础配置" khác nhau) ---
TAB_ID_TITLE_ZH_TO_VI = {
    (1, "基础配置"): "Cấu hình cơ bản",
    (31, "基础配置"): "Cấu hình cơ bản",
    (89, "基础配置"): "Cấu hình cơ bản (thu thập)",
    (91, "基础配置"): "Cấu hình cơ bản (logistics)",
    (93, "基础配置"): "Cấu hình cơ bản (phiếu)",
    (97, "基础配置"): "Cấu hình cơ bản (SMS)",
    (103, "基础配置"): "Cấu hình cơ bản (đối ngoại)",
    (109, "基础配置"): "Cấu hình cơ bản (thanh toán)",
    (2, "公众号配置(H5)"): "Cấu hình công khai (H5)",
    (130, "公众号配置"): "Cấu hình công khai",
    (4, "微信支付配置"): "Cấu hình thanh toán WeChat",
    (7, "小程序配置"): "Cấu hình mini app",
    (132, "小程序配置"): "Cấu hình mini app",
    (9, "分销配置"): "Cấu hình phân phối",
    (11, "用户积分配置"): "Cấu hình điểm người dùng",
    (18, "一号通"): "Nhất thông (SMS)",
    (23, "商城支付配置"): "Cấu hình thanh toán cửa hàng",
    (28, "用户充值配置"): "Cấu hình nạp tiền",
    (41, "采集商品配置"): "Cấu hình thu thập sản phẩm",
    (45, "用户等级配置"): "Cấu hình cấp độ người dùng",
    (50, "发票功能配置"): "Cấu hình hóa đơn",
    (63, "支付宝支付配置"): "Cấu hình thanh toán Alipay",
    (64, "物流查询配置"): "Cấu hình tra cứu logistics",
    (65, "接口设置"): "Cài đặt giao diện",
    (66, "电子面单配置"): "Cấu hình phiếu điện tử",
    (67, "付费会员配置"): "Cấu hình hội viên trả phí",
    (69, "客服配置"): "Cấu hình dịch vụ khách hàng",
    (70, "分享配置"): "Cấu hình chia sẻ",
    (71, "售后退款配置"): "Cấu hình hoàn tiền / đổi trả",
    (72, "分销模式"): "Chế độ phân phối",
    (73, "返佣设置"): "Cài đặt hoa hồng",
    (74, "提现设置"): "Cài đặt rút tiền",
    (75, "PC站点配置"): "Cấu hình site PC",
    (77, "APP配置"): "Cấu hình APP",
    (78, "应用配置"): "Cấu hình ứng dụng",
    (79, "系统存储配置"): "Cấu hình lưu trữ hệ thống",
    (80, "七牛云配置"): "Cấu hình Qiniu",
    (81, "阿里云配置"): "Cấu hình Aliyun",
    (82, "腾讯云配置"): "Cấu hình Tencent Cloud",
    (90, "99api配置"): "Cấu hình 99api",
    (92, "阿里云配置"): "Cấu hình Aliyun (logistics)",
    (94, "一号通配置"): "Cấu hình Nhất thông (phiếu)",
    (96, "短信接口配置"): "Cấu hình SMS",
    (98, "阿里云配置"): "Cấu hình Aliyun (SMS)",
    (99, "腾讯云配置"): "Cấu hình Tencent (SMS)",
    (100, "用户配置"): "Cấu hình người dùng",
    (102, "对外接口配置"): "Cấu hình giao diện đối ngoại",
    (104, "推送配置"): "Cấu hình đẩy (đối ngoại)",
    (105, "新用户设置"): "Cài đặt người dùng mới",
    (106, "翻译配置"): "Cấu hình dịch trực tuyến",
    (108, "通联支付"): "Thanh toán Thông Liên",
    (110, "京东云配置"): "Cấu hình JD Cloud",
    (111, "华为云配置"): "Cấu hình Huawei Cloud",
    (112, "天翼云配置"): "Cấu hình Tianyi Cloud",
    (113, "订单配置"): "Cấu hình đơn hàng",
    (114, "包邮设置"): "Cài đặt miễn phí vận chuyển",
    (115, "订单取消配置"): "Cấu hình hủy đơn",
    (116, "自动收货配置"): "Cấu hình tự nhận hàng",
    (117, "自动评价配置"): "Cấu hình tự đánh giá",
    (119, "到店自提配置"): "Cấu hình tự nhận tại cửa hàng",
    (120, "警戒库存配置"): "Cấu hình tồn kho cảnh báo",
    (122, "LOGO配置"): "Cấu hình LOGO",
    (123, "自定义JS"): "JS tùy chỉnh",
    (124, "地图配置"): "Cấu hình bản đồ",
    (125, "备案配置"): "Cấu hình ICP/beian",
    (126, "用户签到配置"): "Cấu hình điểm danh",
    (129, "系统配置"): "Cấu hình hệ thống",
    (131, "服务器域名配置"): "Cấu hình tên miền máy chủ",
    (133, "消息推送配置"): "Cấu hình đẩy tin",
    (134, "模块配置"): "Cấu hình module",
    (135, "远程登录配置"): "Cấu hình đăng nhập từ xa",
    (136, "商品配置"): "Cấu hình sản phẩm",
    (137, "WAF配置"): "Cấu hình WAF",
}
# Fallback khi (tab_id, zh) không có trong TAB_ID_TITLE_ZH_TO_VI
TAB_TITLE_ZH_TO_VI_FALLBACK = {
    "基础配置": "Cấu hình cơ bản",
    "公众号配置(H5)": "Cấu hình công khai (H5)",
    "公众号配置": "Cấu hình công khai",
    "微信支付配置": "Cấu hình thanh toán WeChat",
    "小程序配置": "Cấu hình mini app",
    "分销配置": "Cấu hình phân phối",
    "用户积分配置": "Cấu hình điểm người dùng",
    "一号通": "Nhất thông (SMS)",
    "商城支付配置": "Cấu hình thanh toán cửa hàng",
    "用户充值配置": "Cấu hình nạp tiền",
    "采集商品配置": "Cấu hình thu thập sản phẩm",
    "用户等级配置": "Cấu hình cấp độ người dùng",
    "发票功能配置": "Cấu hình hóa đơn",
    "支付宝支付配置": "Cấu hình thanh toán Alipay",
    "物流查询配置": "Cấu hình tra cứu logistics",
    "接口设置": "Cài đặt giao diện",
    "电子面单配置": "Cấu hình phiếu điện tử",
    "付费会员配置": "Cấu hình hội viên trả phí",
    "客服配置": "Cấu hình dịch vụ khách hàng",
    "分享配置": "Cấu hình chia sẻ",
    "售后退款配置": "Cấu hình hoàn tiền / đổi trả",
    "分销模式": "Chế độ phân phối",
    "返佣设置": "Cài đặt hoa hồng",
    "提现设置": "Cài đặt rút tiền",
    "PC站点配置": "Cấu hình site PC",
    "APP配置": "Cấu hình APP",
    "应用配置": "Cấu hình ứng dụng",
    "系统存储配置": "Cấu hình lưu trữ hệ thống",
    "七牛云配置": "Cấu hình Qiniu",
    "阿里云配置": "Cấu hình Aliyun",
    "腾讯云配置": "Cấu hình Tencent Cloud",
    "99api配置": "Cấu hình 99api",
    "一号通配置": "Cấu hình Nhất thông (phiếu)",
    "短信接口配置": "Cấu hình SMS",
    "用户配置": "Cấu hình người dùng",
    "对外接口配置": "Cấu hình giao diện đối ngoại",
    "推送配置": "Cấu hình đẩy (đối ngoại)",
    "新用户设置": "Cài đặt người dùng mới",
    "翻译配置": "Cấu hình dịch trực tuyến",
    "通联支付": "Thanh toán Thông Liên",
    "京东云配置": "Cấu hình JD Cloud",
    "华为云配置": "Cấu hình Huawei Cloud",
    "天翼云配置": "Cấu hình Tianyi Cloud",
    "订单配置": "Cấu hình đơn hàng",
    "包邮设置": "Cài đặt miễn phí vận chuyển",
    "订单取消配置": "Cấu hình hủy đơn",
    "自动收货配置": "Cấu hình tự nhận hàng",
    "自动评价配置": "Cấu hình tự đánh giá",
    "到店自提配置": "Cấu hình tự nhận tại cửa hàng",
    "警戒库存配置": "Cấu hình tồn kho cảnh báo",
    "LOGO配置": "Cấu hình LOGO",
    "自定义JS": "JS tùy chỉnh",
    "地图配置": "Cấu hình bản đồ",
    "备案配置": "Cấu hình ICP/beian",
    "用户签到配置": "Cấu hình điểm danh",
    "系统配置": "Cấu hình hệ thống",
    "服务器域名配置": "Cấu hình tên miền máy chủ",
    "消息推送配置": "Cấu hình đẩy tin",
    "模块配置": "Cấu hình module",
    "远程登录配置": "Cấu hình đăng nhập từ xa",
    "商品配置": "Cấu hình sản phẩm",
    "WAF配置": "Cấu hình WAF",
}

# Từ/cụm thường dùng trong info, desc, parameter (eb_system_config). Cụm dài đặt trước để ưu tiên.
CONFIG_ZH_TO_VI = {
    "网站名称": "Tên website",
    "网站地址": "Địa chỉ website",
    "网站关键词": "Từ khóa website",
    "网站描述": "Mô tả website",
    "后台大LOGO": "LOGO lớn quản trị",
    "联系电话": "Số điện thoại liên hệ",
    "关注二维码": "Mã QR theo dõi",
    "微信分享图片": "Hình ảnh chia sẻ WeChat",
    "微信分享标题": "Tiêu đề chia sẻ WeChat",
    "微信分享简介": "Giới thiệu chia sẻ WeChat",
    "消息加解密方式": "Cách thức mã hóa/giải mã tin nhắn",
    "警戒库存": "Tồn kho cảnh báo",
    "退货理由": "Lý do hoàn trả",
    "腾讯地图KEY": "KEY bản đồ Tencent",
    "开发者ID": "ID nhà phát triển",
    "应用ID": "ID ứng dụng",
    "应用密钥": "Khóa bí mật ứng dụng",
    "终端号": "Số thiết bị",
    "开启": "Bật",
    "关闭": "Tắt",
    "配置": "Cấu hình",
    "基础": "Cơ bản",
    "设置": "Cài đặt",
    "开关": "Công tắc",
    "类型": "Loại",
    "地址": "Địa chỉ",
    "名称": "Tên",
    "链接": "Liên kết",
    "电话": "Điện thoại",
    "用户": "Người dùng",
    "订单": "Đơn hàng",
    "支付": "Thanh toán",
    "微信": "WeChat",
    "小程序": "Mini app",
    "公众号": "Công khai",
    "网站": "Website",
    "后台": "Quản trị",
    "上传": "Tải lên",
    "下载": "Tải xuống",
    "存储": "Lưu trữ",
    "本地": "Cục bộ",
    "密钥": "Khóa bí mật",
    "证书": "Chứng chỉ",
    "商户": "Merchant",
    "提现": "Rút tiền",
    "充值": "Nạp tiền",
    "积分": "Điểm",
    "分销": "Phân phối",
    "返佣": "Hoa hồng",
    "一级": "Cấp 1",
    "二级": "Cấp 2",
    "比例": "Tỷ lệ",
    "金额": "Số tiền",
    "时间": "Thời gian",
    "单位": "Đơn vị",
    "小时": "Giờ",
    "天": "Ngày",
    "分钟": "Phút",
    "默认": "Mặc định",
    "可选": "Tùy chọn",
    "必填": "Bắt buộc",
    "请输入": "Vui lòng nhập",
    "选择": "Chọn",
    "显示": "Hiển thị",
    "隐藏": "Ẩn",
    "开启后": "Sau khi bật",
    "关闭后": "Sau khi tắt",
    "建议": "Khuyến nghị",
    "注意": "Lưu ý",
    "说明": "Mô tả",
    "描述": "Mô tả",
    "简介": "Giới thiệu",
    "标题": "Tiêu đề",
    "图片": "Hình ảnh",
    "文件": "Tệp",
    "上传类型": "Loại tải lên",
    "本地存储": "Lưu trữ cục bộ",
    "七牛云存储": "Lưu trữ Qiniu",
    "阿里云OSS": "Aliyun OSS",
    "腾讯COS": "Tencent COS",
    "包邮": "Miễn phí vận chuyển",
    "不包邮": "Không miễn phí vận chuyển",
    "满额包邮": "Đủ số tiền thì miễn phí vận chuyển",
    "银行卡": "Thẻ ngân hàng",
    "支付宝": "Alipay",
    "余额": "Số dư",
    "明文模式": "Chế độ văn bản rõ",
    "兼容模式": "Chế độ tương thích",
    "安全模式": "Chế độ bảo mật",
    "需要审核": "Cần duyệt",
    "无需审核": "Không cần duyệt",
    "指定分销": "Phân phối chỉ định",
    "人人分销": "Phân phối mọi người",
    "满额分销": "Phân phối theo hạn mức",
    "永久": "Vĩnh viễn",
    "有效期": "Có hiệu lực",
    "临时": "Tạm thời",
    "一级分销": "Phân phối cấp 1",
    "二级分销": "Phân phối cấp 2",
    "退还": "Hoàn trả",
    "不退还": "Không hoàn trả",
    "手动线下转账": "Chuyển khoản thủ công",
    "自动转账到零钱": "Tự động chuyển vào ví lẻ",
    "自动转账到余额": "Tự động chuyển vào số dư",
    "商户号绑定": "Liên kết số merchant",
    "非商户号绑定": "Không liên kết số merchant",
    "密钥": "Khóa",
    "证书": "Chứng chỉ",
    "拦截": "Chặn",
    "过滤": "Lọc",
    "普通商品": "Sản phẩm thường",
    "卡密/网盘": "Mã thẻ / Ổ đĩa mạng",
    "优惠券": "Phiếu giảm giá",
    "虚拟商品": "Sản phẩm ảo",
    "无限制": "Không giới hạn",
    "周循环": "Theo tuần",
    "月循环": "Theo tháng",
    "短信": "SMS",
    "站内信": "Tin nội bộ",
    "易联云": "Yi Lian Yun",
    "飞鹅云": "Fei E Yun",
    "一号通": "Nhất thông",
    "阿里云": "Aliyun",
    "腾讯云": "Tencent",
    # --- Bổ sung để tránh lẫn lộn Trung–Việt (cụm dài trước) ---
    "后台，PC，H5，页面分享，海报上的网站名称等位置使用": "Dùng cho quản trị, PC, H5, chia sẻ trang, poster và các vị trí hiển thị tên website",
    "系统安装时会自动配置；建议不要轻易修改！更换会影响网站访问、接口请求、本地文件储存、支付回调、微信授权、支付、小程序图片访问、部分二维码、官方授权等": "Khi cài đặt hệ thống sẽ tự cấu hình; không nên sửa tùy tiện. Thay đổi ảnh hưởng truy cập website, API, lưu trữ file, callback thanh toán, ủy quyền WeChat, thanh toán, ảnh mini app, mã QR, ủy quyền chính thức.",
    "菜单展开左上角logo,建议尺寸[170*50]": "Logo góc trái trên khi mở menu, kích thước khuyến nghị [170x50]",
    "微信公众号的AppID": "AppID tài khoản WeChat công khai",
    "微信公众号的AppSecret": "AppSecret tài khoản WeChat công khai",
    "微信验证TOKEN": "TOKEN xác thực WeChat",
    "如需使用安全模式请在管理中心修改，仅限服务号和认证订阅号": "Dùng chế độ bảo mật cần sửa trong trung tâm quản lý WeChat; chỉ áp dụng tài khoản dịch vụ và đăng ký đã xác thực",
    "公众号消息加解密Key,在使用安全模式情况下要填写该值，请先在管理中心修改，然后填写该值，仅支持认证服务号": "Khóa mã hóa/giải mã tin nhắn công khai; khi dùng chế độ bảo mật cần điền giá trị này (lấy từ trung tâm quản lý WeChat); chỉ hỗ trợ tài khoản dịch vụ đã xác thực",
    "若填写此图片地址，则分享网页出去时会分享此图片。可有效防止分享图片变形，图片比例5:4，建议小于50KB": "Nếu điền URL ảnh này, khi chia sẻ trang sẽ dùng ảnh đó. Tránh ảnh bị méo; tỷ lệ 5:4, nên dưới 50KB",
    "引导关注公众号显示的公众号关注二维码": "Mã QR theo dõi công khai hiển thị khi hướng dẫn người dùng theo dõi",
    "微信商户商户号，微信商户商户号": "Số merchant WeChat",
    "微信支付证书，在微信商家平台中可以下载！文件名一般为apiclient_cert.pem": "Chứng chỉ WeChat Pay; tải tại nền tảng merchant WeChat, tên file thường là apiclient_cert.pem",
    "微信支付证书密钥，在微信商家平台中可以下载！文件名一般为apiclient_key.pem": "Khóa chứng chỉ WeChat Pay; tải tại nền tảng merchant WeChat, tên file thường là apiclient_key.pem",
    "商户支付密钥Key。审核通过后，在微信发送的邮件中查看。": "Khóa bí mật thanh toán merchant. Xem trong email WeChat gửi sau khi duyệt.",
    "请选择微信支付通道，关闭用户端不展示": "Chọn kênh WeChat Pay; tắt thì phía người dùng không hiển thị",
    "通联": "Thông Liên",
    "商城商品满多少金额即可包邮，此项优先于其他的运费设置": "Đơn hàng đủ bao nhiêu tiền thì miễn phí vận chuyển; ưu tiên hơn cài đặt vận chuyển khác",
    "用户选择线下支付时是否包邮": "Khi người dùng chọn thanh toán tại chỗ thì có miễn phí vận chuyển không",
    "积分抵用比例(1积分抵多少金额)单位：元": "Tỷ lệ đổi điểm (1 điểm = bao nhiêu tiền), đơn vị: VND",
    "用户单次最低充值金额": "Số tiền nạp tối thiểu mỗi lần của người dùng",
    "快递查询密钥": "Khóa tra cứu vận chuyển",
    "订单交易成功后给上级返佣的比例0 - 100,例:5 = 返订单商品金额的5%": "Tỷ lệ hoa hồng cho cấp trên sau khi đơn hàng thành công (0–100); ví dụ 5 = 5% giá trị đơn hàng",
    "用户提现最低金额限制": "Hạn mức rút tiền tối thiểu của người dùng",
    "配置服务器域名使用的接口地址，直接复制输入框内容（此项系统生成，无法修改）": "Địa chỉ API dùng cho cấu hình tên miền máy chủ; copy nội dung ô nhập (do hệ thống tạo, không sửa)",
    "商品库存数量低于多少时，提示库存不足": "Khi tồn kho dưới bao nhiêu thì cảnh báo hết hàng",
    "配置退货理由，一行一个理由": "Cấu hình lý do hoàn trả, mỗi dòng một lý do",
    "分销模式": "Chế độ phân phối",
    "人人分销默认每个人都可以分销，指定分销仅可后台手动设置推广员，满额分销指用户购买商品满足消费金额后自动开启分销": "Phân phối mọi người: mặc định ai cũng có thể phân phối; Chỉ định: chỉ admin cấu hình đại lý; Theo hạn mức: tự bật khi người dùng mua đủ số tiền",
    "配置提现银行卡类型，每个银行换行": "Cấu hình loại thẻ ngân hàng rút tiền, mỗi ngân hàng một dòng",
    "文件储存配置，注意：一旦配置就不要轻易修改，会导致文件不能使用": "Cấu hình lưu trữ file; lưu ý: sau khi cấu hình không nên sửa, dễ gây lỗi file",
    "普通未支付订单取消": "Hủy đơn hàng thường chưa thanh toán",
    "活动未支付订单取消": "Hủy đơn hàng sự kiện chưa thanh toán",
    "砍价未支付订单取消": "Hủy đơn hàng kên giá chưa thanh toán",
    "秒杀未支付订单取消": "Hủy đơn hàng flash sale chưa thanh toán",
    "拼团未支付订单取消": "Hủy đơn hàng nhóm mua chưa thanh toán",
    "普通商品未支付订单的自动取消时间，单位（小时）": "Thời gian tự hủy đơn hàng thường chưa thanh toán (giờ)",
    "活动商品未支付的订单自动取消时间，单位（小时）": "Thời gian tự hủy đơn hàng sự kiện chưa thanh toán (giờ)",
    "砍价未支付订单自动取消时间，单位（小时），如果为0将使用默认活动取消时间，优先使用单独活动配置": "Thời gian tự hủy đơn kên giá chưa thanh toán (giờ); 0 = dùng mặc định sự kiện",
    "秒杀未支付订单自动取消时间，单位（小时），如果为0将使用默认活动取消时间，优先使用单独活动配置": "Thời gian tự hủy đơn flash sale chưa thanh toán (giờ); 0 = dùng mặc định sự kiện",
    "拼团未支付订单自动取消时间，单位（小时），如果为0将使用默认活动取消时间，优先使用单独活动配置": "Thời gian tự hủy đơn nhóm mua chưa thanh toán (giờ); 0 = dùng mặc định sự kiện",
    "商城订单发货之后，用户如果不手动点击收货，则在N天后自动收货，设置0为不自动收货，单位（天）": "Sau khi giao hàng, nếu người dùng không bấm xác nhận thì sau N ngày tự xác nhận; 0 = không tự xác nhận (đơn vị: ngày)",
    "一号通账号内创建应用的AppId": "AppId ứng dụng tạo trong tài khoản Nhất thông",
    "一号通账号内创建应用的AppSecret": "AppSecret ứng dụng tạo trong tài khoản Nhất thông",
    "用户默认头像，后台添加用户以及用户登录的默认头像显示，尺寸(80*80)": "Ảnh đại diện mặc định; dùng khi admin thêm user và khi user đăng nhập; kích thước 80x80",
    "线下支付请选择开启或关闭": "Thanh toán tại chỗ: chọn bật hoặc tắt",
    "仅小程序端的充值开关，小程序提交审核前,需要关闭此功能": "Chỉ công tắc nạp tiền phía mini app; cần tắt trước khi gửi mini app duyệt",
    "腾讯地图KEY，申请地址：https://lbs.qq.com": "KEY bản đồ Tencent; đăng ký tại https://lbs.qq.com",
    "开启后下单页面支持到店自提，需要在设置->发货设置->提货点设置中添加提货点，关闭则隐藏此功能": "Bật thì trang đặt hàng hỗ trợ nhận tại cửa hàng; thêm điểm nhận trong Cài đặt > Giao hàng > Điểm nhận; tắt thì ẩn",
    "支付成功自动小票打印功能，需要购买易联云K4或者K6无线打印机，或者购买飞鹅云V58系列": "In phiếu tự động khi thanh toán thành công; cần mua máy in Yi Lian Yun K4/K6 hoặc Fei E Yun V58",
    "易联云申请应用后页面开发者信息中的用户ID": "ID người dùng trong thông tin nhà phát triển sau khi tạo ứng dụng Yi Lian Yun",
    "易联云申请应用后页面开发者信息中的应用密钥": "Khóa bí mật ứng dụng trong thông tin nhà phát triển Yi Lian Yun",
    "易联云申请应用后页面开发者信息中的应用ID": "ID ứng dụng trong thông tin nhà phát triển Yi Lian Yun",
    "易联云打印机标签上的终端号": "Số thiết bị in trên nhãn máy in Yi Lian Yun",
    "支付成功提醒开关": "Công tắc nhắc thanh toán thành công",
    "发货提醒开关": "Công tắc nhắc giao hàng",
    "确认收货提醒开关": "Công tắc nhắc xác nhận nhận hàng",
    "用户下单管理员提醒开关": "Công tắc nhắc admin khi có đơn mới",
    "用户支付成功管理员提醒开关": "Công tắc nhắc admin khi thanh toán thành công",
    "用户退款管理员提醒开关": "Công tắc nhắc admin khi hoàn tiền",
    "用户确认收货管理员短信提醒": "Nhắc admin bằng SMS khi người dùng xác nhận nhận hàng",
    "充值注意事项": "Lưu ý khi nạp tiền",
    "防止用户退款，佣金被提现了，所以需要设置佣金冻结时间(天)": "Tránh người dùng hoàn tiền trong khi đã rút hoa hồng; cần đặt thời gian đóng băng hoa hồng (ngày)",
    "满额分销满足金额开通分销权限": "Phân phối theo hạn mức: đủ số tiền thì được quyền phân phối",
    "改价短信提醒开关": "Công tắc nhắc SMS khi đổi giá",
    "后台菜单缩进小LOGO，尺寸180*180": "Logo nhỏ thu menu quản trị; kích thước 180x180",
    "余额支付请选择开启或关闭": "Thanh toán bằng số dư: chọn bật hoặc tắt",
    "后台登录页LOGO，建议尺寸270x75": "Logo trang đăng nhập quản trị; kích thước khuyến nghị 270x75",
    "七牛云accessKey": "accessKey Qiniu",
    "七牛云secretKey": "secretKey Qiniu",
    "腾讯云accessKey": "accessKey Tencent Cloud",
    "腾讯云secretKey": "secretKey Tencent Cloud",
    "注册99api采集接口在个人中心复制key": "Đăng ký 99api, copy key tại trung tâm cá nhân",
    "商城余额功能启用或者关闭": "Bật hoặc tắt chức năng số dư trong商城",
    "商城分销功能开启|关闭": "Bật/tắt chức năng phân phối",
    "下单支付金额按比例赠送积分（实际支付1元赠送多少积分）": "Tặng điểm theo tỷ lệ tiền thanh toán (1 đơn vị tiền = bao nhiêu điểm)",
    "商城用户等级功能开启|关闭": "Bật/tắt chức năng cấp người dùng",
    "商品付费会员价是否展示": "Có hiển thị giá hội viên trả phí cho sản phẩm không",
    "用户在授权之后强制绑定手机号，可以实现用户多端统一": "Sau khi ủy quyền, bắt buộc bind SĐT để đồng bộ nhiều thiết bị",
    "强制": "Bắt buộc",
    "不强制": "Không bắt buộc",
    "下单赠送用户经验比例（实际支付1元赠送多少经验）": "Tỷ lệ tặng kinh nghiệm khi đặt hàng (1 đơn vị tiền = bao nhiêu kinh nghiệm)",
    "签到赠送用户经验值": "Tặng kinh nghiệm khi điểm danh",
    "邀请一个新用户赠送用户经验值": "Mời một người dùng mới được tặng kinh nghiệm",
    "所有用户指所有没有上级推广人的用户点击或扫推广人码绑定分销关系，新用户指新注册的用户或首次进入系统的用户才会绑定推广关系": "Tất cả: user chưa có người giới thiệu bấm/quét mã để bind; Mới: chỉ user mới đăng ký hoặc lần đầu vào hệ thống mới bind",
    "未支付短信提醒开关": "Công tắc nhắc SMS đơn chưa thanh toán",
    "短信验证码过期时间（分钟）": "Thời gian hết hạn mã SMS (phút)",
    "发票功能开启|关闭": "Bật/tắt chức năng hóa đơn",
    "专用发票功能开启|关闭": "Bật/tắt hóa đơn chuyên dụng",
    "请选择支付宝通道，关闭用户端不显示": "Chọn kênh Alipay; tắt thì phía user không hiển thị",
    "支付宝加签完成后生成的支付宝公钥": "Khóa công khai Alipay sau khi hoàn tất ký Alipay",
    "支付应用私钥": "Khóa riêng ứng dụng thanh toán",
    "支付应用Appid": "Appid ứng dụng thanh toán",
    "建议使用一号通更方便不用配置密钥，阿里云云市场购买链接": "Khuyến nghị dùng Nhất thông, không cần cấu hình khóa; link mua Aliyun: https://market.aliyun.com/...",
    "采集商品接口选择，一号通快速注册开通使用不用配置apikey，或者去99api网址注册帐号，推荐一号通方便快捷": "Chọn giao diện thu thập sản phẩm; Nhất thông đăng ký nhanh không cần apikey; hoặc đăng ký 99api",
    "快递公司": "Công ty vận chuyển",
    "快递模板": "Mẫu vận chuyển",
    "快递面单发货人姓名": "Tên người gửi trên phiếu gửi hàng",
    "快递面单发货人电话": "Số điện thoại người gửi trên phiếu",
    "快递面单发货人详细地址": "Địa chỉ chi tiết người gửi trên phiếu",
    "请购买快递100二代云打印机(KX100L3)": "Mua máy in mây KX100L3 (ví dụ Kuaidi 100 thế hệ 2)",
    "暂无客服在线是，联系客服跳转的客服反馈页面的显示文字": "Chữ hiển thị khi chuyển tới trang phản hồi khi chưa có客服 trực tuyến",
    "单次下单积分使用上限,0不限制": "Giới hạn điểm dùng mỗi đơn; 0 = không giới hạn",
    "付费会员开关": "Công tắc hội viên trả phí",
    "个人中心分销海报图片，建议尺寸600x1000": "Ảnh poster phân phối trong trang cá nhân; kích thước 600x1000",
    "电子面单是否开启，开启请在物流公司列表配置月结账号": "Có bật phiếu điện tử không; nếu bật cần cấu hình tài khoản trả theo tháng trong danh sách đơn vị vận chuyển",
    "移动端登录logo，建议尺寸86x86，建议png格式": "Logo đăng nhập phiên bản mobile; kích thước 86x86, định dạng PNG",
    "PC端LOGO": "Logo phiên bản PC",
    "网站的备案号，显示在H5和PC端底部": "Số đăng ký website; hiển thị ở chân H5 và PC",
    "跟随系统：跟随系统使用默认客服、电话或者跳转链接；小程序客服：需要在小程序管理配置客服用户；": "Theo hệ thống: dùng客服/điện thoại/liên kết mặc định; Mini app: cần cấu hình user客服 trong quản lý mini app",
    "站点开启|关闭（用于升级等暂时关闭），关闭后前端会弹窗显示站点升级中，请稍后访问": "Bật/tắt site (để bảo trì nâng cấp); khi tắt frontend hiện \"Đang nâng cấp, vui lòng quay lại sau\"",
    "分销推广佣金单价（每推广一个用户）": "Đơn giá hoa hồng giới thiệu (mỗi user được giới thiệu)",
    "每日推广佣金上限（0:不发佣金-1:不限制；注最好是推广佣金单价的整数倍）": "Hạn mức hoa hồng giới thiệu mỗi ngày (0: không phát; -1: không giới hạn; nên là bội số của đơn giá)",
    "是否开启自购佣金（开启：分销员自己购买商品，享受一级佣金，上级享受二级佣金； 关闭：分销员自己购买商品没有佣金）": "Có bật hoa hồng tự mua không (bật: đại lý mua được hưởng cấp 1, cấp trên hưởng cấp 2; tắt: đại lý mua không có hoa hồng)",
    "永久一次绑定永久有效，有效期绑定后一段时间内有效，临时临时有效": "Vĩnh viễn: bind một lần có hiệu lực lâu dài; Có hiệu lực: có hiệu lực trong khoảng thời gian; Tạm thời: tạm thời",
    "绑定有效期（绑定后N天内有效）": "Hiệu lực bind (trong N ngày sau khi bind)",
    "用户退货退款管理员同意之后，显示在退货订单详情显示的接受退货的人员姓名": "Tên người nhận hàng hoàn trả hiển thị trong chi tiết đơn hoàn trả sau khi admin đồng ý",
    "用户退货退款管理员同意之后，显示在退货订单详情显示的接受退货的人员电话": "Số điện thoại người nhận hàng hoàn trả (hiển thị trong chi tiết đơn sau khi admin đồng ý)",
    "用户退货退款管理员同意之后，显示在退货订单详情显示的接受退货的地址信息": "Địa chỉ nhận hàng hoàn trả (hiển thị trong chi tiết đơn sau khi admin đồng ý)",
    "分销推广用户获取佣金": "User được giới thiệu nhận hoa hồng",
    "微信开放平台申请网页应用后给予的AppID": "AppID do nền tảng mở WeChat cấp sau khi đăng ký ứng dụng web",
    "微信开放平台申请网页应用后给予的AppSecret": "AppSecret do nền tảng mở WeChat cấp sau khi đăng ký ứng dụng web",
    "PC底部显示的联系电话": "Số điện thoại hiển thị ở chân trang PC",
    "PC底部显示的公司地址": "Địa chỉ công ty hiển thị ở chân trang PC",
    "商品详情手机购买显示公众号码或者小程序码": "Mã công khai hoặc mã mini app hiển thị khi mua trên điện thoại tại trang chi tiết sản phẩm",
    "首页配置精品推荐个数": "Số mục sản phẩm đề xuất trên trang chủ",
    "首页配置首发新品个数": "Số mục hàng mới ra mắt trên trang chủ",
    "系统客服：点击联系客服使用系统的自带客服；拨打电话：点击联系客服拨打客服电话；跳转链接：跳转外部链接联系客服": "客服 hệ thống: dùng客服 tích hợp; Gọi điện: bấm gọi SĐT客服; Liên kết: chuyển tới link ngoài",
    "客服类型选择跳转链接时，跳转的链接地址": "Khi chọn loại客服 là chuyển link thì điền URL",
    "客服类型选择拨打电话时，用户点击联系客服的联系电话": "Khi chọn gọi điện thì điền SĐT khi user bấm liên hệ客服",
    "微信开放平台申请移动应用后给予的APPID": "APPID do nền tảng mở WeChat cấp cho ứng dụng mobile",
    "微信开放平台申请移动应用后给予的AppSecret": "AppSecret do nền tảng mở WeChat cấp cho ứng dụng mobile",
    "在移动端加载的自定义JS": "JS tùy chỉnh tải trên phiên bản mobile",
    "配置微信网页授权域名时候下载的微信校验文件，在此处上传": "File xác thực WeChat tải khi cấu hình tên miền ủy quyền web; tải lên tại đây",
    "缩略大图（单位：px）：宽": "Ảnh thu nhỏ lớn (px): rộng",
    "缩略大图（单位：px）：高": "Ảnh thu nhỏ lớn (px): cao",
    "缩略中图（单位：px）：宽": "Ảnh thu nhỏ vừa (px): rộng",
    "缩略中图（单位：px）：高": "Ảnh thu nhỏ vừa (px): cao",
    "缩略小图（单位：px）： 宽": "Ảnh thu nhỏ nhỏ (px): rộng",
    "缩略小图（单位：px）： 高": "Ảnh thu nhỏ nhỏ (px): cao",
    "是否开启水印": "Có bật watermark không",
    "图片水印是否开服": "Có bật watermark ảnh không",
    "水印类型": "Loại watermark",
    "图片": "Hình ảnh",
    "文字": "Chữ",
    "水印图片链接": "Link ảnh watermark",
    "左上": "Trên trái",
    "上中": "Trên giữa",
    "右上": "Trên phải",
    "左中": "Giữa trái",
    "右中": "Giữa phải",
    "左下": "Dưới trái",
    "下中": "Dưới giữa",
    "右下": "Dưới phải",
    "水印图片透明度": "Độ trong suốt ảnh watermark",
    "水印图片倾斜度": "Độ nghiêng ảnh watermark",
    "水印文字": "Chữ watermark",
    "水印文字大小（单位：px）": "Cỡ chữ watermark (px)",
    "水印字体颜色": "Màu chữ watermark",
    "水印字体旋转角度": "Góc xoay chữ watermark",
    "水印横坐标偏移量（单位：px）": "Lệch hoành độ watermark (px)",
    "水印纵坐标偏移量（单位：px）": "Lệch tung độ watermark (px)",
    "程序ICO图标，更换后需要清除浏览器缓存": "Icon ICO chương trình; sau khi đổi cần xóa cache trình duyệt",
    "开启后用户才可以选择该提现方式": "Bật thì user mới chọn được phương thức rút tiền này",
    "积分冻结(天)，0为不冻结": "Đóng băng điểm (ngày); 0 = không đóng băng",
    "打印平台选择": "Chọn nền tảng in",
    "电子面单类型": "Loại phiếu điện tử",
    "如果客服链接填写企业微信客服，小程序需要跳转企业微信客服的话需要配置此项，并且在小程序客服中绑定企业ID": "Nếu link客服 là企业微信客服 và mini app cần chuyển tới đó thì cấu hình mục này và bind企业ID trong客服 mini app",
    "用户关注公众号之后是否生成商城用户": "Sau khi user theo dõi công khai có tạo user商城 không",
    "好友代付开关，关闭后付款类型不显示好友代付": "Công tắc trả hộ; tắt thì không hiển thị lựa chọn trả hộ",
    "分销层级，一级是只返上级一层的佣金，二级是返上级和上上级的佣金": "Cấp phân phối: cấp 1 chỉ hoa hồng cho cấp trên trực tiếp; cấp 2 gồm cấp trên và cấp trên nữa",
    "短信类型，选择发送的短信类型": "Loại SMS; chọn loại SMS gửi đi",
    "阿里云AccessKeyId": "AccessKeyId Aliyun",
    "阿里云AccessKeySecret": "AccessKeySecret Aliyun",
    "阿里云RegionId": "RegionId Aliyun",
    "短信签名": "Chữ ký SMS",
    "腾讯短信应用SKD APPID": "APPID SDK ứng dụng SMS Tencent",
    "腾讯AccessKeyId": "AccessKeyId Tencent",
    "腾讯AccessKeySecret": "AccessKeySecret Tencent",
    "腾讯短信签名": "Chữ ký SMS Tencent",
    "短信区域ap-beijing、ap-guangzhou、ap-nanjing任选其一填写": "Vùng SMS: điền một trong ap-beijing, ap-guangzhou, ap-nanjing",
    "小程序开发管理有支付管理的请选择非商户号绑定": "Nếu quản lý mini app có quản lý thanh toán thì chọn không liên kết số merchant",
    "小程序开通支付管理生成的商户号": "Số merchant do mini app tạo khi bật quản lý thanh toán",
    "商城订单在收货之后，用户如果不主动评价订单商品，则在N天后自动评价，设置0为永远不自动评价": "Sau khi nhận hàng, nếu user không đánh giá thì sau N ngày tự đánh giá; 0 = không tự đánh giá",
    "自动评价显示的评价文字": "Chữ đánh giá tự động hiển thị",
    "支付接口类型v2对应微信支付旧版v2支付。v3对应微信支付v3支付接口。支付证书可以通用一个。支付秘钥和v2旧版支付有区别": "Loại API thanh toán: v2 = WeChat Pay phiên bản cũ; v3 = WeChat Pay v3; chứng chỉ dùng chung được; khóa v3 khác v2",
    "商户API证书的证书序列号，可以在证书管理里面查看": "Số seri chứng chỉ API merchant; xem trong quản lý chứng chỉ",
    "V3支付秘钥": "Khóa bí mật thanh toán V3",
    "新用户奖励金额，必须大于等于0，0为不赠送": "Tiền thưởng user mới; ≥0; 0 = không tặng",
    "新用户奖励积分，必须大于等于0，0为不赠送": "Điểm thưởng user mới; ≥0; 0 = không tặng",
    "机器翻译仅支持火山翻译，注册地址https://console.volcengine.com，在访问控制里面新建api密钥": "Dịch máy chỉ hỗ trợ Volcano; đăng ký https://console.volcengine.com, tạo API key trong Access Control",
    "飞鹅云管理注册账号": "Tài khoản đăng ký quản lý Fei E Yun",
    "飞鹅云管理注册账号后生成的UKEY 【备注：这不是填打印机的KEY】": "UKEY do Fei E Yun cấp sau khi đăng ký (không phải KEY máy in)",
    "打印机标签上的编号，必须要在管理里添加打印机或调用API接口添加之后，才能调用API": "Mã số trên nhãn máy in; cần thêm máy in trong quản lý hoặc qua API trước khi gọi in",
    "通联支付的MD5私钥，可以在商户管理设置中进行配置": "Khóa MD5 Thanh toán Thông Liên; cấu hình trong quản lý merchant",
    "通联支付商户号，由贵公司申请获得": "Số merchant Thông Liên do công ty bạn đăng ký",
    "通联商户管理的设置-》对接设置中查看": "Xem trong Cài đặt > Đối tiếp của quản lý merchant Thông Liên",
    "是否启用消息队列，启用后提升程序运行速度，启用前必须配置Redis缓存，文档地址：https://doc.crmeb.com/single/v52/8646": "Có bật message queue không; bật giúp chạy nhanh hơn; cần cấu hình Redis trước; tài liệu: https://doc.crmeb.com/...",
    "是否在小程序用户授权之后，弹窗获取用户的昵称和头像": "Sau khi user mini app ủy quyền có hiện popup lấy nickname và avatar không",
    "公众号生成的推广码类型：商城：扫码直接进入商城，公众号：扫码进入公众号后推送商城的链接": "Loại mã giới thiệu công khai: 商城: quét vào商城; Công khai: quét vào công khai rồi đẩy link商城",
    "购买付费会员是否按照设置的佣金比例进行佣金": "Mua hội viên trả phí có tính hoa hồng theo tỷ lệ đã cấu hình không",
    "选择佣金类型，按照商品价格佣金（按照商品售价计算佣金金额）以及按照实际支付价格佣金（按照商品的实际支付价格计算佣金）": "Chọn loại hoa hồng: theo giá bán sản phẩm hoặc theo giá thanh toán thực tế",
    "公安部门登记的备案信息，显示在PC和H5底部": "Thông tin đăng ký với công an; hiển thị ở chân PC và H5",
    "H5和PC底部显示的ICP备案号点击跳转的链接": "Link khi bấm vào số đăng ký ICP ở chân H5/PC",
    "H5和PC底部显示的网安备案号点击跳转的链接": "Link khi bấm vào số đăng ký an ninh mạng ở chân H5/PC",
    "小程序有订单发货管理时，请打开此开关，否则会导致订单资金冻结": "Nếu mini app có quản lý giao hàng đơn thì bật công tắc này, không sẽ đóng băng tiền đơn",
    "京东云accessKey": "accessKey JD Cloud",
    "京东云secretKey": "secretKey JD Cloud",
    "华为云accessKey": "accessKey Huawei Cloud",
    "华为云secretKey": "secretKey Huawei Cloud",
    "天翼云secretKey": "secretKey Tianyi Cloud",
    "天翼云accessKey": "accessKey Tianyi Cloud",
    "优惠券是否退回开关，商品成功退款后，退回/不退回使用的优惠券": "Có trả lại phiếu giảm giá khi hoàn tiền không",
    "京东云storageRegion": "storageRegion JD Cloud",
    "签到开关，商城是否开启签到功能，关闭后隐藏签到入口": "Công tắc điểm danh; tắt thì ẩn lối vào điểm danh",
    "不限，累积和连续签到不会清零，周循环，每周一会清理累积和连续的记录为0，重新开始计算，月循环，每月一号会清理累积和连续的记录为0，重新开始计算": "Không giới hạn: không xóa; Theo tuần: mỗi thứ Hai về 0; Theo tháng: mùng 1 về 0",
    "是否开启签到提醒，提醒方式为短信以及站内信": "Có bật nhắc điểm danh không; nhắc qua SMS và tin nội bộ",
    "选择每日未签到提醒的通知时间": "Chọn thời gian nhắc mỗi ngày chưa điểm danh",
    "签到每日提醒的提醒方式，支持短信和站内信": "Cách nhắc điểm danh hằng ngày: SMS hoặc tin nội bộ",
    "签到赠送积分，每日签到赠送的积分值": "Điểm tặng khi điểm danh mỗi ngày",
    "商户类型，目前支持普通微信商户模式和普通微信服务商模式；": "Loại merchant: merchant WeChat thường hoặc nhà cung cấp dịch vụ WeChat",
    "微信支付服务商子商户商户号": "Số merchant con của nhà cung cấp WeChat Pay",
    "提现手续费百分比，范围0-100，0为无提现手续费，例：设置10，即收取10%手续费，提现100元，到账90元，10元手续费": "Phần trăm phí rút tiền (0–100); 0 = không phí; ví dụ 10%: rút 100 còn 90",
    "小程序获取手机号的方式，微信授权和手机号验证码": "Cách mini app lấy SĐT: ủy quyền WeChat hoặc nhập mã xác thực SĐT",
    "订单收货之后，在多少天内可以进行退款，超出天数前端不显示退款按钮，设置0则永远显示": "Sau khi nhận hàng, trong bao nhiêu ngày được hoàn tiền; quá ngày thì ẩn nút hoàn tiền; 0 = luôn hiển thị",
    "配置小程序消息推送使用的接口地址，直接复制输入框内容（此项系统生成，无法修改）": "Địa chỉ API dùng cho push tin nhắn mini app; copy từ ô nhập (do hệ thống tạo)",
    "小程序消息推送中的消息加密方式，暂时仅支持明文模式": "Cách mã hóa tin nhắn push mini app; hiện chỉ hỗ trợ chế độ văn bản rõ",
    "消息加密密钥由43位字符组成，字符范围为A-Z,a-z,0-9": "Khóa mã hóa tin nhắn gồm 43 ký tự A-Z, a-z, 0-9",
    "模块配置，选中展示对应的模块，取消选中则前后端不展示模块相关内容": "Cấu hình module; chọn thì hiển thị module tương ứng; bỏ chọn thì ẩn",
    "秒杀": "Flash sale",
    "砍价": "Kên giá",
    "拼团": "Nhóm mua",
    "主商户APPID，开启服务商支付，需要配置主商户申请的时候开通的公众号服务号的APPID": "APPID merchant chính; khi bật thanh toán nhà cung cấp cần APPID công khai dịch vụ của merchant chính",
    "佣金悬浮窗开关，关闭之后，商品详情不显示佣金悬浮窗": "Công tắc cửa sổ nổi hoa hồng; tắt thì trang chi tiết sản phẩm không hiện",
    "是否开启电子发票": "Có bật hóa đơn điện tử không",
    "是否开启自动开票功能": "Có bật tự động xuất hóa đơn không",
    "电子发票的商品分类": "Phân loại sản phẩm cho hóa đơn điện tử",
    "请填写电子发票的税率，填写0-100直接的整数，如：13%的税率请填写13": "Điền thuế suất hóa đơn điện tử (0–100), ví dụ 13% điền 13",
    "电子发票分类对应的名称，用于回显": "Tên phân loại hóa đơn điện tử dùng để hiển thị",
    "内嵌商城跳转h5页面链接携带（remote_token=远程用户生成的token）参数时，可自动登录商城，若remote_token为空的时候，本系统认定在外部系统中未登录，会跳转此地址进行登录": "Khi link H5 nhúng商城 có tham số remote_token thì tự đăng nhập; nếu không có token thì chuyển tới địa chỉ này để đăng nhập",
    "WAF配置验证参数，过滤掉不需要的参数或拦截请求，多个参数用回车换行分隔": "Tham số kiểm tra cấu hình WAF; lọc bỏ tham số không cần hoặc chặn request; nhiều tham số cách nhau bằng xuống dòng",
    "用户提交商品评论是否需要管理员审核，需要则审核完成后显示，无需审核则用户提交后显示": "Comment sản phẩm có cần admin duyệt không; có thì hiện sau khi duyệt; không thì hiện ngay sau khi gửi",
    "类型：关闭（所有参数都能正常请求），拦截（匹配到WAF配置的参数阻断接口请求），过滤（匹配到WAF配置的参数过滤参数，正常请求接口）": "Loại: Tắt (mọi tham số đều qua); Chặn (chặn request khớp WAF); Lọc (bỏ tham số khớp WAF rồi cho qua)",
    "商品类型配置，可以配置添加商品时可选择的商品类型": "Cấu hình loại sản phẩm khi thêm sản phẩm",
    "方式：手动线下转账，自动转账到零钱(需开通商家转账到零钱)": "Cách: Chuyển khoản thủ công; Tự động chuyển vào ví lẻ (cần bật chuyển merchant tới ví lẻ)",
    "方式：手动线下转账，自动转账到余额(需开通支付宝转账)": "Cách: Chuyển khoản thủ công; Tự động chuyển vào số dư (cần bật chuyển Alipay)",
    "v3支付公钥，新版本使用公钥请填写": "Khóa công khai thanh toán v3; điền nếu dùng phiên bản mới",
    "v3支付公钥证书，使用新版本支付公钥上传此证书": "Chứng chỉ khóa công khai v3; tải lên khi dùng khóa công khai mới",
    "微信自动提现场景值": "Giá trị scene rút tiền tự động WeChat",
    "在管理端加载的自定义JS": "JS tùy chỉnh tải ở quản trị",
    "在PC端加载的自定义JS": "JS tùy chỉnh tải ở PC",
    "应用公钥证书，支付宝接口加签完成之后下载的": "Chứng chỉ khóa công khai ứng dụng; tải sau khi hoàn tất ký Alipay",
    "支付宝接口加签完成下载的支付宝公钥证书": "Chứng chỉ khóa công khai Alipay tải sau khi ký",
    "支付宝接口加签完成下载的支付宝根证书": "Chứng chỉ gốc Alipay tải sau khi ký",
    "接口加签类型：密钥或证书": "Loại ký giao diện: Khóa hoặc Chứng chỉ",
    "是否开启缩略图": "Có bật ảnh thu nhỏ không",
    # Cụm dài thêm cho lần sinh sau (tránh lẫn Trung–Việt)
    "跟随系统": "Theo hệ thống",
    "物流查询": "tra cứu vận chuyển",
    "采集商品": "thu thập sản phẩm",
    "快速注册开通使用": "đăng ký nhanh, kích hoạt sử dụng",
    "月结账号": "tài khoản trả theo tháng",
    "支持企业付款到零钱": "Hỗ trợ chuyển doanh nghiệp tới ví lẻ",
    "支持Merchant转账到零钱": "Hỗ trợ chuyển merchant tới ví lẻ",
    "可以在Chứng chỉ管理里面查看": "có thể xem trong quản lý chứng chỉ",
    "消息队列": "message queue",
    "购买付费会员是否按照Cài đặt的佣金Tỷ lệ进行Hoa hồng": "Mua hội viên trả phí có tính hoa hồng theo tỷ lệ cài đặt không",
    "系统客服": "chăm sóc khách hàng hệ thống",
    "好友代付": "Trả hộ bạn bè",
    "企业ID": "ID doanh nghiệp",
    "关注是否生成Người dùng": "Theo dõi có tạo user cửa hàng không",
    "商城": "cửa hàng",
}


def parse_sql_value(s: str):
    """Parse one value from SQL: number or unquote/unescape string."""
    s = s.strip()
    if not s:
        return ""
    if s.startswith("'") and s.endswith("'"):
        inner = s[1:-1].replace("\\'", "'").replace("\\n", "\n").replace("\\r", "\r")
        return inner
    try:
        return int(s)
    except ValueError:
        pass
    try:
        return float(s)
    except ValueError:
        pass
    return s


def parse_sql_row(line: str):
    """Split a VALUES row into list of values (handles quoted strings with commas)."""
    out = []
    i = 0
    n = len(line)
    while i < n:
        while i < n and line[i] in " \t":
            i += 1
        if i >= n:
            break
        if line[i] == "'":
            start = i
            i += 1
            while i < n:
                if line[i] == "\\" and i + 1 < n:
                    i += 2
                    continue
                if line[i] == "'":
                    i += 1
                    break
                i += 1
            out.append(line[start:i])
        else:
            j = i
            while j < n and line[j] != "," and line[j] != ")":
                j += 1
            out.append(line[i:j].strip())
            i = j
        if i < n and line[i] == ",":
            i += 1
    return [parse_sql_value(v) for v in out]


def extract_insert_block(content: str, table_name: str):
    """Find INSERT INTO `table_name` ... VALUES; return list of rows (one row per line)."""
    pattern = rf"INSERT INTO `{re.escape(table_name)}`\s*\([^)]+\)\s*VALUES\s*\n"
    m = re.search(pattern, content)
    if not m:
        return []
    start = m.end()
    lines = content[start:].split("\n")
    rows = []
    for line in lines:
        line = line.strip()
        if not line:
            continue
        if line.startswith("--") or line.startswith("CREATE TABLE"):
            break
        if line.startswith("("):
            # Remove trailing ), or );
            if line.endswith(");"):
                line = line[:-2]
            elif line.endswith("),"):
                line = line[:-2]
            vals = parse_sql_row(line[1:])  # skip leading (
            if vals:
                rows.append(vals)
        if line.rstrip().endswith(");"):
            break
    return rows


def translate_text_vi(text: str, zh_to_vi: dict) -> str:
    """Replace Chinese phrases with Vietnamese (longest match first)."""
    if not text or not isinstance(text, str):
        return text
    result = text
    # Sort by key length descending to replace longer phrases first
    for zh, vi in sorted(zh_to_vi.items(), key=lambda x: -len(x[0])):
        result = result.replace(zh, vi)
    return result


def translate_parameter_vi(param: str) -> str:
    """Translate parameter string format: 0=>关闭\n1=>开启 -> 0=>Tắt\n1=>Bật."""
    if not param or not isinstance(param, str):
        return param
    out = []
    for part in param.split("\n"):
        if "=>" in part:
            k, v = part.split("=>", 1)
            v_vi = translate_text_vi(v.strip(), CONFIG_ZH_TO_VI)
            out.append(f"{k.strip()}=>{v_vi}")
        else:
            out.append(translate_text_vi(part, CONFIG_ZH_TO_VI))
    return "\n".join(out)


def escape_sql(s: str) -> str:
    """Escape string for SQL single-quoted value."""
    if s is None:
        return "''"
    return "'" + str(s).replace("\\", "\\\\").replace("'", "\\'").replace("\n", "\\n").replace("\r", "\\r") + "'"


def main():
    if not SQL_FILE.exists():
        print(f"Không tìm thấy {SQL_FILE}")
        return 1

    content = SQL_FILE.read_text(encoding="utf-8", errors="replace")

    # --- 1. Parse eb_system_config_tab ---
    tab_rows = extract_insert_block(content, "eb_system_config_tab")
    # Columns: id, pid, title, eng_title, status, info, icon, type, sort, menus_id
    # Index:   0    1    2      3          4       5      6     7     8    9
    tab_titles = {}  # id -> (zh_title, vi_title)
    for row in tab_rows:
        if len(row) >= 3:
            tid = row[0]
            zh_title = row[2] if isinstance(row[2], str) else str(row[2])
            vi_title = (
                TAB_ID_TITLE_ZH_TO_VI.get((tid, zh_title))
                or TAB_TITLE_ZH_TO_VI_FALLBACK.get(zh_title)
                or translate_text_vi(zh_title, CONFIG_ZH_TO_VI)
                or zh_title
            )
            tab_titles[tid] = (zh_title, vi_title)

    # --- 2. Parse eb_system_config ---
    config_rows = extract_insert_block(content, "eb_system_config")
    # id, menu_name, type, input_type, config_tab_id, parameter, upload_type, required, width, high, value, info, desc, sort, status, level, link_id, link_value
    # 0  1           2     3           4              5          6            7         8      9    10     11    12    13    14      15    16       17
    configs = []
    report = {"config_missing_vi": [], "tab_missing_vi": []}
    for row in config_rows:
        if len(row) < 13:
            continue
        cid = row[0]
        info_zh = row[11] if isinstance(row[11], str) else str(row[11])
        desc_zh = row[12] if isinstance(row[12], str) else str(row[12])
        param_zh = row[5] if isinstance(row[5], str) else str(row[5])
        info_vi = translate_text_vi(info_zh, CONFIG_ZH_TO_VI) or info_zh
        desc_vi = translate_text_vi(desc_zh, CONFIG_ZH_TO_VI) or desc_zh
        param_vi = translate_parameter_vi(param_zh) if param_zh else param_zh
        if info_vi == info_zh and any("\u4e00" <= c <= "\u9fff" for c in info_zh):
            report["config_missing_vi"].append({"id": cid, "field": "info", "zh": info_zh})
        if desc_vi == desc_zh and any("\u4e00" <= c <= "\u9fff" for c in desc_zh):
            report["config_missing_vi"].append({"id": cid, "field": "desc", "zh": desc_zh[:200]})
        configs.append((cid, info_vi, desc_vi, param_vi))

    for tid, (zh, vi) in tab_titles.items():
        if vi == zh and any("\u4e00" <= c <= "\u9fff" for c in zh):
            report["tab_missing_vi"].append({"tab_id": tid, "zh": zh})

    # --- 3. Write patch_system_config_tab_lang_vi.sql ---
    PATCH_TAB_LANG.parent.mkdir(parents=True, exist_ok=True)
    lines = [
        "-- Patch: Đa ngôn ngữ tab Cài đặt hệ thống (INSERT bản dịch tiếng Việt)",
        "-- Sinh bởi scripts/patch_system_config_vi.py từ crmeb.sql",
        "-- lang_type_id = 10 (vi-VN). Chạy sau khi đã import crmeb.sql.",
        "",
        "CREATE TABLE IF NOT EXISTS `eb_system_config_tab_lang` (",
        "  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',",
        "  `tab_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'ID tab (eb_system_config_tab.id)',",
        "  `lang_type_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Ngôn ngữ (10=vi-VN)',",
        "  `title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Tên tab theo ngôn ngữ',",
        "  PRIMARY KEY (`id`),",
        "  UNIQUE KEY `tab_lang` (`tab_id`,`lang_type_id`),",
        "  KEY `lang_type_id` (`lang_type_id`)",
        ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Bản dịch tên tab cấu hình';",
        "",
        "INSERT INTO `eb_system_config_tab_lang` (`tab_id`, `lang_type_id`, `title`) VALUES",
    ]
    values = [f"({tid}, 10, {escape_sql(vi_title)})" for tid, (_, vi_title) in sorted(tab_titles.items())]
    lines.append(",\n".join(values))
    lines.append("ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);")
    PATCH_TAB_LANG.write_text("\n".join(lines) + "\n", encoding="utf-8")
    print(f"Đã ghi: {PATCH_TAB_LANG} ({len(tab_titles)} tab)")

    # --- 4. Write patch_system_config_lang_vi.sql (CREATE TABLE + INSERT, đa ngôn ngữ) ---
    lines2 = [
        "-- Patch: Đa ngôn ngữ cho form cấu hình (INSERT vào eb_system_config_lang, không UPDATE bảng gốc)",
        "-- Sinh bởi scripts/patch_system_config_vi.py từ crmeb.sql",
        "-- lang_type_id = 10 (vi-VN). Backend đọc info/desc/parameter theo cb-lang.",
        "",
        "CREATE TABLE IF NOT EXISTS `eb_system_config_lang` (",
        "  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',",
        "  `config_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'eb_system_config.id',",
        "  `lang_type_id` int(11) NOT NULL DEFAULT '0' COMMENT 'eb_lang_type.id (10=vi-VN)',",
        "  `info` varchar(255) NOT NULL DEFAULT '' COMMENT 'Tên hiển thị theo ngôn ngữ',",
        "  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT 'Mô tả theo ngôn ngữ',",
        "  `parameter` varchar(255) NOT NULL DEFAULT '' COMMENT 'Tham số option theo ngôn ngữ',",
        "  PRIMARY KEY (`id`),",
        "  UNIQUE KEY `config_lang` (`config_id`,`lang_type_id`),",
        "  KEY `lang_type_id` (`lang_type_id`)",
        ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Bản dịch cấu hình (info, desc, parameter)';",
        "",
        "INSERT INTO `eb_system_config_lang` (`config_id`, `lang_type_id`, `info`, `desc`, `parameter`) VALUES",
    ]
    values = [
        f"({cid}, {LANG_TYPE_VI}, {escape_sql(info_vi)}, {escape_sql(desc_vi)}, {escape_sql(param_vi)})"
        for cid, info_vi, desc_vi, param_vi in configs
    ]
    lines2.append(",\n".join(values))
    lines2.append("ON DUPLICATE KEY UPDATE `info` = VALUES(`info`), `desc` = VALUES(`desc`), `parameter` = VALUES(`parameter`);")
    PATCH_CONFIG_LANG.write_text("\n".join(lines2) + "\n", encoding="utf-8")
    print(f"Đã ghi: {PATCH_CONFIG_LANG} ({len(configs)} dòng)")

    # --- 5. Report ---
    REPORT_JSON.parent.mkdir(parents=True, exist_ok=True)
    with open(REPORT_JSON, "w", encoding="utf-8") as f:
        json.dump(report, f, ensure_ascii=False, indent=2)
    print(f"Báo cáo chuỗi còn tiếng Trung: {REPORT_JSON}")
    return 0


if __name__ == "__main__":
    exit(main())
