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
