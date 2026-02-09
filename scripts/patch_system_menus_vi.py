#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Đọc crmeb.sql, sinh patch đa ngôn ngữ cho menu (eb_system_menus_lang, lang_type_id=10 vi-VN).
Chạy: python scripts/patch_system_menus_vi.py
Đầu vào: crmeb/public/install/crmeb.sql
Đầu ra: crmeb/public/install/patch_system_menus_lang_vi.sql (giữ CREATE TABLE, ghi đè INSERT)
"""

import re
from pathlib import Path

REPO_ROOT = Path(__file__).resolve().parent.parent
SQL_FILE = REPO_ROOT / "crmeb/public/install/crmeb.sql"
PATCH_FILE = REPO_ROOT / "crmeb/public/install/patch_system_menus_lang_vi.sql"
LANG_TYPE_VI = 10


def parse_sql_values(line: str) -> list:
    """Parse một dòng VALUES (v1, v2, 'str', ...) thành list các giá trị (string hoặc int)."""
    line = line.strip()
    if not line.startswith("(") or not line.endswith(")"):
        return []
    inner = line[1:-1]
    values = []
    i = 0
    n = len(inner)
    while i < n:
        while i < n and inner[i] in " \t":
            i += 1
        if i >= n:
            break
        if inner[i] == "'":
            # quoted string
            i += 1
            start = i
            while i < n:
                if inner[i] == "\\":
                    i += 2
                    continue
                if inner[i] == "'":
                    values.append(inner[start:i])
                    i += 1
                    break
                i += 1
            continue
        if inner[i] in "0123456789-":
            start = i
            if inner[i] == "-":
                i += 1
            while i < n and inner[i] in "0123456789":
                i += 1
            values.append(int(inner[start:i]) if inner[start:i] != "" else 0)
            continue
        if inner[i] == ",":
            i += 1
            continue
        i += 1
    return values


# Từ điển menu_name / header / mark: Trung -> Việt (dùng cho cả menu_name, header, mark khi khớp)
MENU_ZH_TO_VI = {
    "商品": "Sản phẩm",
    "商品管理": "Quản lý sản phẩm",
    "商品分类": "Danh mục sản phẩm",
    "商品评论": "Đánh giá sản phẩm",
    "商品规格": "Quy cách sản phẩm",
    "商品添加": "Thêm sản phẩm",
    "订单": "Đơn hàng",
    "订单管理": "Quản lý đơn hàng",
    "主页": "Trang chủ",
    "用户": "Người dùng",
    "用户管理": "Quản lý người dùng",
    "用户等级": "Cấp người dùng",
    "用户分组": "Nhóm người dùng",
    "用户标签": "Nhãn người dùng",
    "设置": "Cài đặt",
    "管理权限": "Quản lý quyền",
    "角色管理": "Quản lý vai trò",
    "管理员列表": "Danh sách quản trị viên",
    "权限设置": "Cài đặt quyền",
    "菜单管理": "Quản lý menu",
    "系统设置": "Cài đặt hệ thống",
    "维护": "Bảo trì",
    "分销": "Phân phối",
    "分销设置": "Cài đặt phân phối",
    "分销员管理": "Quản lý đại lý",
    "分销等级": "Cấp phân phối",
    "营销": "Marketing",
    "优惠券": "Phiếu giảm giá",
    "优惠券列表": "Danh sách phiếu giảm giá",
    "用户领取记录": "Lịch sử nhận phiếu",
    "砍价管理": "Quản lý kênh giá",
    "砍价商品": "Sản phẩm kênh giá",
    "砍价列表": "Danh sách kênh giá",
    "砍价添加": "Thêm kênh giá",
    "拼团管理": "Quản lý nhóm mua",
    "拼团商品": "Sản phẩm nhóm mua",
    "拼团列表": "Danh sách nhóm mua",
    "拼团添加": "Thêm nhóm mua",
    "秒杀管理": "Quản lý flash sale",
    "秒杀商品": "Sản phẩm flash sale",
    "秒杀配置": "Cấu hình flash sale",
    "积分管理": "Quản lý điểm",
    "积分配置": "Cấu hình điểm",
    "积分商品": "Sản phẩm đổi điểm",
    "积分订单": "Đơn đổi điểm",
    "积分记录": "Lịch sử điểm",
    "积分统计": "Thống kê điểm",
    "财务": "Tài chính",
    "财务操作": "Thao tác tài chính",
    "财务记录": "Ghi nhận tài chính",
    "佣金记录": "Lịch sử hoa hồng",
    "提现申请": "Yêu cầu rút tiền",
    "充值记录": "Lịch sử nạp tiền",
    "资金流水": "Dòng tiền",
    "账单记录": "Ghi nhận hóa đơn",
    "余额记录": "Lịch sử số dư",
    "内容": "Nội dung",
    "文章管理": "Quản lý bài viết",
    "文章分类": "Danh mục bài viết",
    "文章添加": "Thêm bài viết",
    "系统日志": "Nhật ký hệ thống",
    "开发配置": "Cấu hình phát triển",
    "刷新缓存": "Làm mới cache",
    "安全维护": "Bảo trì an toàn",
    "清除数据": "Xóa dữ liệu",
    "数据库管理": "Quản lý cơ sở dữ liệu",
    "公众号": "Công khai WeChat",
    "微信菜单": "Menu WeChat",
    "一号通": "Nhất thông",
    "一号通页面": "Trang Nhất thông",
    "图文管理": "Quản lý tin bài",
    "添加图文消息": "Thêm tin bài",
    "配置分类": "Danh mục cấu hình",
    "组合数据": "Dữ liệu nhóm",
    "关注回复": "Trả lời khi theo dõi",
    "自动回复": "Tự động trả lời",
    "关键字回复": "Trả lời từ khóa",
    "无效词回复": "Trả lời mặc định",
    "数据配置": "Cấu hình dữ liệu",
    "应用": "Ứng dụng",
    "提货点设置": "Cài đặt điểm nhận hàng",
    "物流公司": "Công ty vận chuyển",
    "城市数据": "Dữ liệu thành phố",
    "运费模板": "Mẫu phí vận chuyển",
    "提货点": "Điểm nhận hàng",
    "核销员": "Nhân viên xác nhận",
    "核销记录": "Lịch sử xác nhận",
    "核销订单": "Đơn xác nhận",
    "发货设置": "Cài đặt giao hàng",
    "素材管理": "Quản lý tài nguyên",
    "客服": "Chăm sóc khách hàng",
    "客服列表": "Danh sách CSKH",
    "客服话术": "Kịch bản CSKH",
    "用户留言": "Phản hồi người dùng",
    "直播管理": "Quản lý livestream",
    "直播间管理": "Quản lý phòng livestream",
    "直播商品管理": "Quản lý sản phẩm livestream",
    "主播管理": "Quản lý streamer",
    "配送员管理": "Quản lý giao hàng",
    "付费会员": "Hội viên trả phí",
    "会员类型": "Loại hội viên",
    "卡密会员": "Hội viên thẻ",
    "会员记录": "Lịch sử hội viên",
    "会员权益": "Quyền lợi hội viên",
    "会员配置": "Cấu hình hội viên",
    "发票管理": "Quản lý hóa đơn",
    "收银订单": "Đơn thu ngân",
    "售后订单": "Đơn hoàn trả / đổi",
    "消息管理": "Quản lý tin nhắn",
    "装修": "Trang trí",
    "首页装修": "Trang chủ",
    "页面设计": "Thiết kế trang",
    "主题风格": "Phong cách giao diện",
    "PC商城": "Cửa hàng PC",
    "PC端": "Phiên bản PC",
    "PC端装修": "Trang trí PC",
    "PC端配置": "Cấu hình PC",
    "抽奖管理": "Quản lý rút thăm",
    "渠道码": "Mã kênh",
    "金额设置": "Cài đặt số tiền",
    "充值配置": "Cấu hình nạp tiền",
    "小程序": "Mini app",
    "小程序下载": "Tải mini app",
    "小程序配置": "Cấu hình mini app",
    "APP": "APP",
    "APP配置": "Cấu hình APP",
    "版本管理": "Quản lý phiên bản",
    "接口配置": "Cấu hình giao diện",
    "商品采集配置": "Cấu hình thu thập sản phẩm",
    "物流查询配置": "Cấu hình tra cứu vận chuyển",
    "电子面单配置": "Cấu hình phiếu điện tử",
    "短信接口配置": "Cấu hình SMS",
    "商城支付配置": "Cấu hình thanh toán cửa hàng",
    "协议设置": "Cài đặt điều khoản",
    "对外接口": "Giao diện đối ngoại",
    "账号管理": "Quản lý tài khoản",
    "语言设置": "Cài đặt ngôn ngữ",
    "语言列表": "Danh sách ngôn ngữ",
    "语言详情": "Chi tiết ngôn ngữ",
    "地区列表": "Danh sách quốc gia/vùng",
    "文件管理": "Quản lý file",
    "数据维护": "Bảo trì dữ liệu",
    "接口管理": "Quản lý giao diện",
    "代码生成": "Sinh mã",
    "开发工具": "Công cụ phát triển",
    "定时任务": "Tác vụ theo lịch",
    "系统存储配置": "Cấu hình lưu trữ hệ thống",
    "系统信息": "Thông tin hệ thống",
    "公众号配置": "Cấu hình công khai",
}


def translate(s: str) -> str:
    if not s or not s.strip():
        return s
    return MENU_ZH_TO_VI.get(s.strip(), s)


def main():
    if not SQL_FILE.exists():
        print(f"Không tìm thấy {SQL_FILE}")
        return
    content = SQL_FILE.read_text(encoding="utf-8", errors="replace")
    lines_list = content.splitlines()

    # Chỉ xử lý dòng nằm trong khối INSERT INTO `eb_system_menus` ... VALUES
    rows = []
    in_menus_block = False
    for line in lines_list:
        stripped = line.strip()
        if "INSERT INTO `eb_system_menus`" in line:
            in_menus_block = True
            # Dòng INSERT có thể chứa tuple đầu: VALUES (1, 0, ...
            if "VALUES" in line:
                idx = line.find("VALUES")
                after = line[idx + 6:].strip()
                if after.startswith("("):
                    end = after.rfind(")") + 1
                    part = after[:end].rstrip(",").rstrip(";")
                    vals = parse_sql_values(part)
                    if len(vals) >= 22:
                        rows.append((vals[0], vals[3], vals[17], vals[21]))
            continue
        if in_menus_block and stripped.startswith("("):
            part = stripped.rstrip(",").rstrip(";")
            vals = parse_sql_values(part)
            if len(vals) >= 22:
                rows.append((vals[0], vals[3], vals[17], vals[21]))
            continue
        if in_menus_block and stripped and not stripped.startswith("(") and "INSERT INTO `eb_system_menus`" not in line:
            in_menus_block = False

    # Dedup by menu id (giữ bản ghi cuối nếu trùng id)
    by_id = {}
    for mid, mname, header, mark in rows:
        by_id[mid] = (mname, header, mark)
    rows = [(mid, translate(mname), translate(header), translate(mark)) for mid, (mname, header, mark) in sorted(by_id.items())]

    def esc(s):
        return s.replace("\\", "\\\\").replace("'", "\\'")

    lines = []
    lines.append("-- Patch: Đa ngôn ngữ cho menu (INSERT vào eb_system_menus_lang, không UPDATE bảng gốc)")
    lines.append("-- Sinh bởi scripts/patch_system_menus_vi.py từ crmeb.sql")
    lines.append("-- lang_type_id = 10 (vi-VN). Backend đọc menu_name/header/mark theo header cb-lang.")
    lines.append("")
    lines.append("CREATE TABLE IF NOT EXISTS `eb_system_menus_lang` (")
    lines.append("  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',")
    lines.append("  `menu_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'eb_system_menus.id',")
    lines.append("  `lang_type_id` int(11) NOT NULL DEFAULT '0' COMMENT 'eb_lang_type.id (10=vi-VN)',")
    lines.append("  `menu_name` varchar(32) NOT NULL DEFAULT '' COMMENT 'Tên menu theo ngôn ngữ',")
    lines.append("  `header` varchar(50) NOT NULL DEFAULT '' COMMENT 'Header/top menu theo ngôn ngữ',")
    lines.append("  `mark` varchar(500) NOT NULL DEFAULT '' COMMENT 'Ghi chú theo ngôn ngữ',")
    lines.append("  PRIMARY KEY (`id`),")
    lines.append("  UNIQUE KEY `menu_lang` (`menu_id`,`lang_type_id`),")
    lines.append("  KEY `lang_type_id` (`lang_type_id`)")
    lines.append(") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Bản dịch menu (menu_name, header, mark)';")
    lines.append("")
    lines.append("INSERT INTO `eb_system_menus_lang` (`menu_id`, `lang_type_id`, `menu_name`, `header`, `mark`) VALUES")

    value_lines = []
    for mid, mname, header, mark in rows:
        mname_s = mname.replace("\\", "\\\\").replace("'", "\\'")
        header_s = header.replace("\\", "\\\\").replace("'", "\\'")
        mark_s = mark.replace("\\", "\\\\").replace("'", "\\'")
        value_lines.append(f"({mid}, {LANG_TYPE_VI}, '{mname_s}', '{header_s}', '{mark_s}')")
    lines.append(",\n".join(value_lines))
    lines.append("ON DUPLICATE KEY UPDATE `menu_name` = VALUES(`menu_name`), `header` = VALUES(`header`), `mark` = VALUES(`mark`);")

    PATCH_FILE.write_text("\n".join(lines) + "\n", encoding="utf-8")
    print(f"Đã ghi {len(rows)} dòng vào {PATCH_FILE}")


if __name__ == "__main__":
    main()
