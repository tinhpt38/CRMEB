#!/usr/bin/env python3
"""
Tìm chính xác vị trí các text tiếng Trung trong template/admin/src và hỗ trợ áp dụng i18n.

Cách dùng:
  # Chỉ tìm và in ra (file:line:col, text)
  python scripts/find_chinese_i18n.py

  # Xuất JSON (để xử lý tiếp)
  python scripts/find_chinese_i18n.py --output json

  # Áp dụng i18n: thay chuỗi bằng $t('key'), tạo file gợi ý key cho zh-cn/vi-vn
  python scripts/find_chinese_i18n.py --apply [--dry-run]

  # Chỉ quét thư mục/ file cụ thể
  python scripts/find_chinese_i18n.py --dir pages/setting

  # Báo cáo chuỗi unique + gợi ý dịch Việt (ghi scripts/i18n_report/)
  python scripts/find_chinese_i18n.py --skip-comments --report
"""

import argparse
import json
import re
import os
import sys
from pathlib import Path
from collections import defaultdict

# Unicode range: CJK Unified Ideographs (chữ Hán)
CHINESE_PATTERN = re.compile(r'[\u4e00-\u9fff]+')
# Chuỗi tiếng Trung kèm dấu câu thường gặp (：|，。)
CHINESE_PHRASE_PATTERN = re.compile(r'[\u4e00-\u9fff]+[\uff1a\uff0c\u3002\uff5c]?|[\u4e00-\u9fff]+')

# Từ điển dịch nhanh Trung -> Việt (từ/cụm thường gặp)
ZH_VI_DICT = {
    '用户': 'Người dùng',
    '等级': 'Cấp',
    '启用': 'Bật',
    '商城': 'Gian hàng',
    '功能': 'Chức năng',
    '开启': 'Bật',
    '关闭': 'Tắt',
    '开启|关闭': 'Bật|Tắt',
    '是否': 'Có/Không',
    '开启关闭': 'Bật/Tắt',
    '保存': 'Lưu',
    '取消': 'Hủy',
    '确定': 'Xác nhận',
    '删除': 'Xóa',
    '编辑': 'Sửa',
    '添加': 'Thêm',
    '查询': 'Tìm kiếm',
    '搜索': 'Tìm kiếm',
    '提交': 'Gửi',
    '请': 'Vui lòng',
    '选择': 'Chọn',
    '输入': 'Nhập',
    '名称': 'Tên',
    '标题': 'Tiêu đề',
    '描述': 'Mô tả',
    '备注': 'Ghi chú',
    '操作': 'Thao tác',
    '状态': 'Trạng thái',
    '类型': 'Loại',
    '时间': 'Thời gian',
    '数量': 'Số lượng',
    '金额': 'Số tiền',
    '列表': 'Danh sách',
    '配置': 'Cấu hình',
    '设置': 'Cài đặt',
    '管理': 'Quản lý',
    '显示': 'Hiển thị',
    '隐藏': 'Ẩn',
    '全部': 'Tất cả',
    '暂无': 'Chưa có',
    '暂无数据': 'Chưa có dữ liệu',
    '暂无筛选结果': 'Chưa có kết quả lọc',
    '成功': 'Thành công',
    '失败': 'Thất bại',
    '提示': 'Nhắc nhở',
    '警告': 'Cảnh báo',
    '错误': 'Lỗi',
    '用户等级': 'Cấp người dùng',
    '用户等级启用': 'Bật cấp người dùng',
    '商城用户等级功能开启': 'Bật chức năng cấp người dùng gian hàng',
    '商城用户等级功能': 'Chức năng cấp người dùng gian hàng',
}

def translate_zh_to_vi(text: str) -> str:
    """Gợi ý bản dịch tiếng Việt từ từ điển (khớp cả cụm hoặc từ đơn)."""
    text = text.strip()
    if not text:
        return text
    if text in ZH_VI_DICT:
        return ZH_VI_DICT[text]
    # Thử thay từng từ đã có trong dict
    result = text
    for zh, vi in sorted(ZH_VI_DICT.items(), key=lambda x: -len(x[0])):
        if zh in result:
            result = result.replace(zh, vi, 1)
    if result == text:
        return ''  # Không dịch được -> để trống, cần dịch tay
    return result

# Bỏ qua thư mục không cần quét
SKIP_DIRS = {'node_modules', 'dist', 'i18n', '.git', 'vendor'}
# Chỉ quét các extension này
SCAN_EXTENSIONS = {'.vue', '.js'}

# Root project (thư mục chứa template/)
PROJECT_ROOT = Path(__file__).resolve().parent.parent
ADMIN_SRC = PROJECT_ROOT / 'template' / 'admin' / 'src'


def iter_scannable_files(root: Path, subdir: Path = None) -> list[Path]:
    """Liệt kê tất cả file .vue và .js cần quét (bỏ qua SKIP_DIRS)."""
    base = root / subdir if subdir else root
    if not base.exists():
        return []
    files = []
    for path in base.rglob('*'):
        if path.is_file() and path.suffix in SCAN_EXTENSIONS:
            if any(s in path.parts for s in SKIP_DIRS):
                continue
            files.append(path)
    return sorted(files)


def is_comment_only(line: str, column: int) -> bool:
    """True nếu vị trí column nằm trong comment (// hoặc <!-- ... -->)."""
    stripped = line[:column].strip()
    return stripped.startswith('//') or '<!--' in line and '-->' not in line[:column]


def find_chinese_in_file(filepath: Path, skip_comments: bool = False) -> list[dict]:
    """
    Trả về danh sách: [{ "line": 1, "column": 1, "text": "中文", "context": "..." }, ...]
    line/column 1-based.
    """
    try:
        content = filepath.read_text(encoding='utf-8', errors='replace')
    except Exception as e:
        return [{"error": str(e)}]
    results = []
    for i, line in enumerate(content.splitlines(), start=1):
        for m in CHINESE_PATTERN.finditer(line):
            if skip_comments and is_comment_only(line, m.start()):
                continue
            results.append({
                "line": i,
                "column": m.start() + 1,
                "text": m.group(0),
                "context": line.strip()[:120],
            })
    return results


def infer_page_key(relpath: Path) -> str:
    """
    Từ đường dẫn tương đối (từ admin/src) suy ra prefix key i18n.
    Ví dụ: pages/setting/devise/index.vue -> message.pages.setting.devise
           pages/setting/devise/components/uploadPic.vue -> message.pages.setting.devise
    """
    parts = relpath.parts
    if 'pages' in parts:
        idx = parts.index('pages')
        rest = parts[idx + 1:]
        # pages/setting/devise/... -> setting.devise
        if len(rest) >= 2 and rest[0] == 'setting':
            page = rest[1]  # devise, agreement, ...
            return f"message.pages.setting.{page}"
    return "message.pages.other"


def slug_key(text: str, index: int) -> str:
    """Tạo key dạng camelCase từ index (key1, key2, ...)."""
    return f"key{index}"


def apply_i18n_to_file(filepath: Path, occurrences: list[dict], key_prefix: str, dry_run: bool) -> list[dict]:
    """
    Thay từng chuỗi tiếng Trung trong file bằng $t('key_prefix.key').
    Trả về danh sách { "key": "...", "zh": "中文", "vi": "..." } để thêm vào zh-cn/vi-vn.
    """
    content = filepath.read_text(encoding='utf-8', errors='replace')
    lines = content.splitlines()
    keys_added = []
    seen = set()
    replacements = []
    for occ in occurrences:
        if occ.get("error"):
            continue
        line_no = occ["line"]
        text = occ["text"]
        col = occ["column"] - 1
        if (line_no, col, text) in seen:
            continue
        seen.add((line_no, col, text))
        key_slug = slug_key(text, len(keys_added) + 1)
        full_key = f"{key_prefix}.{key_slug}"
        replacements.append((line_no, col, text, full_key))
        keys_added.append({"key": full_key, "zh": text, "vi": text})  # vi giữ nguyên để dịch sau

    if dry_run or not replacements:
        return keys_added

    # Gom theo dòng, thay từ cột lớn → nhỏ để không lệch offset
    by_line = defaultdict(list)
    for line_no, col, text, full_key in replacements:
        by_line[line_no].append((col, text, full_key))
    for line_no in by_line:
        by_line[line_no].sort(key=lambda x: -x[0])

    for line_no, items in by_line.items():
        idx = line_no - 1
        line = lines[idx]
        for col, text, full_key in items:
            before = line[:col]
            after = line[col + len(text):]
            line = before + f"$t('{full_key}')" + after
        lines[idx] = line

    filepath.write_text("\n".join(lines) + ("\n" if content.endswith("\n") else ""), encoding='utf-8')
    return keys_added


def main():
    parser = argparse.ArgumentParser(description='Tìm text tiếng Trung và áp dụng i18n')
    parser.add_argument('--dir', type=str, default=None,
                        help='Quét chỉ thư mục này (tương đối từ template/admin/src), ví dụ: pages/setting')
    parser.add_argument('--output', choices=['text', 'json'], default='text', help='Định dạng xuất khi chỉ tìm')
    parser.add_argument('--apply', action='store_true', help='Áp dụng i18n: thay bằng $t() và tạo file gợi ý key')
    parser.add_argument('--dry-run', action='store_true', help='Chỉ in thay đổi sẽ làm, không ghi file')
    parser.add_argument('--skip-comments', action='store_true', help='Bỏ qua chuỗi nằm trong comment (// hoặc <!--)')
    parser.add_argument('--report', action='store_true',
                        help='Báo cáo chuỗi tiếng Trung (unique) kèm gợi ý dịch Việt, ghi file scripts/i18n_report/')
    args = parser.parse_args()

    root = ADMIN_SRC
    subdir = Path(args.dir) if args.dir else None
    files = iter_scannable_files(root, subdir)

    if not files:
        print('Không tìm thấy file .vue/.js nào để quét.', file=sys.stderr)
        sys.exit(1)

    all_occurrences = []
    file_occurrences = defaultdict(list)

    for f in files:
        rel = f.relative_to(root)
        occs = find_chinese_in_file(f, skip_comments=args.skip_comments)
        for o in occs:
            if o.get("error"):
                continue
            all_occurrences.append({
                "file": str(rel),
                "path": str(f),
                "line": o["line"],
                "column": o["column"],
                "text": o["text"],
                "context": o.get("context", ""),
            })
            file_occurrences[f].append(o)

    if args.report:
        # Nhóm theo text (zh), lấy file:line mẫu, gợi ý dịch vi
        by_text = defaultdict(list)
        for o in all_occurrences:
            t = o['text'].strip()
            if len(t) < 2:
                continue
            by_text[t].append(f"{o['file']}:{o['line']}")
        report = []
        for zh in sorted(by_text.keys(), key=lambda x: (-len(x), x)):
            locations = by_text[zh][:5]
            vi = translate_zh_to_vi(zh)
            report.append({
                'zh': zh,
                'vi_suggested': vi or zh,
                'vi_needs_review': not vi,
                'count': len(by_text[zh]),
                'sample': locations,
            })
        out_dir = PROJECT_ROOT / 'scripts' / 'i18n_report'
        out_dir.mkdir(parents=True, exist_ok=True)
        (out_dir / 'translate_report.json').write_text(
            json.dumps(report, ensure_ascii=False, indent=2), encoding='utf-8'
        )
        # File phẳng zh -> vi để merge vào locale
        flat = { r['zh']: r['vi_suggested'] for r in report }
        (out_dir / 'zh_to_vi_flat.json').write_text(
            json.dumps(flat, ensure_ascii=False, indent=2), encoding='utf-8'
        )
        # In ra text: zh -> vi (chỉ chuỗi >= 4 ký tự)
        with open(out_dir / 'translate_report.txt', 'w', encoding='utf-8') as f:
            for r in report:
                if len(r['zh']) >= 4:
                    f.write(f"{r['zh']}\n  -> {r['vi_suggested']}\n  (count={r['count']}, sample={r['sample'][:2]})\n\n")
        print(f'Đã ghi báo cáo: {out_dir}/translate_report.json, zh_to_vi_flat.json, translate_report.txt')
        print(f'Tổng {len(report)} chuỗi unique (chuỗi >= 4 ký tự: {len([r for r in report if len(r["zh"]) >= 4])})')
        return

    if args.apply:
        # Nhóm theo file, với mỗi file: infer key_prefix, gọi apply_i18n_to_file, thu thập keys
        all_keys = []
        for f in files:
            occs = file_occurrences.get(f, [])
            if not occs:
                continue
            rel = f.relative_to(root)
            key_prefix = infer_page_key(rel)
            keys = apply_i18n_to_file(f, occs, key_prefix, dry_run=args.dry_run)
            all_keys.extend(keys)
            if keys and args.dry_run:
                print(f"[dry-run] {rel}: would replace {len(keys)} string(s), keys: {[k['key'] for k in keys]}")

        # Ghi file gợi ý key (zh-cn và vi-vn) vào thư mục script
        if all_keys and not args.dry_run:
            out_dir = PROJECT_ROOT / 'scripts' / 'i18n_suggestions'
            out_dir.mkdir(parents=True, exist_ok=True)
            zh_cn_entries = {k["key"].split(".")[-1]: k["zh"] for k in all_keys}
            vi_vn_entries = {k["key"].split(".")[-1]: k["vi"] for k in all_keys}
            (out_dir / 'zh_cn_keys.json').write_text(
                json.dumps(zh_cn_entries, ensure_ascii=False, indent=2), encoding='utf-8'
            )
            (out_dir / 'vi_vn_keys.json').write_text(
                json.dumps(vi_vn_entries, ensure_ascii=False, indent=2), encoding='utf-8'
            )
            print(f'Đã áp dụng i18n cho {len(files)} file, {len(all_keys)} key. Gợi ý key: {out_dir}/zh_cn_keys.json, vi_vn_keys.json')
        elif all_keys and args.dry_run:
            print(f'[dry-run] Would add {len(all_keys)} keys across {len([f for f in files if file_occurrences.get(f)])} files.')
        return

    if args.output == 'json':
        print(json.dumps({"files_scanned": len(files), "occurrences": all_occurrences}, ensure_ascii=False, indent=2))
        return

    # text
    for o in all_occurrences:
        print(f"{o['file']}:{o['line']}:{o['column']}\t{o['text']}\t{o['context'][:60]}")


if __name__ == '__main__':
    main()
