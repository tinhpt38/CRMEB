#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Đọc eb_lang_code.csv (xuất từ bảng eb_lang_code):
- Tìm các code có bản tiếng Trung (type_id=1) nhưng chưa có bản tiếng Việt (type_id=10).
- Tạo file SQL INSERT để thêm bản dịch tiếng Việt (type_id=10) vào eb_lang_code.

Chạy: python3 scripts/gen_lang_code_vi_sql.py [đường_dẫn_csv]
Mặc định CSV: ./eb_lang_code.csv (hoặc truyền từ tham số).
Output: crmeb/public/install/patch_lang_code_vi.sql
"""

import csv
import os
import sys
from collections import defaultdict


def escape_sql(s: str) -> str:
    """Escape single quote cho SQL: ' -> ''."""
    if s is None:
        return ""
    return str(s).replace("\\", "\\\\").replace("'", "''")


def main():
    default_csv = os.path.join(
        os.path.dirname(__file__), "..", "eb_lang_code.csv"
    )
    csv_path = sys.argv[1] if len(sys.argv) > 1 else default_csv
    if not os.path.isfile(csv_path):
        print(f"File không tồn tại: {csv_path}")
        print("Cách dùng: python3 gen_lang_code_vi_sql.py /đường/dẫn/eb_lang_code.csv")
        sys.exit(1)

    # (code, is_admin) -> { type_id: row }
    by_key = defaultdict(dict)
    with open(csv_path, "r", encoding="utf-8") as f:
        reader = csv.DictReader(f)
        for row in reader:
            code = (row.get("code") or "").strip()
            tid = (row.get("type_id") or "").strip()
            is_admin = (row.get("is_admin") or "").strip()
            key = (code, is_admin)
            by_key[key][tid] = row

    # Codes có tiếng Trung (type_id=1) nhưng chưa có tiếng Việt (type_id=10)
    missing_vi = []
    for (code, is_admin), types in by_key.items():
        if "1" in types and "10" not in types:
            r = types["1"]
            missing_vi.append({
                "code": code,
                "is_admin": is_admin,
                "remarks": r.get("remarks", ""),
                "lang_explain": r.get("lang_explain", ""),
            })

    # Tất cả bản ghi type_id=10 (tiếng Việt) trong CSV
    rows_vi = []
    for (code, is_admin), types in by_key.items():
        if "10" in types:
            rows_vi.append(types["10"])

    # Sắp xếp theo code, is_admin để SQL dễ đọc (tùy chọn)
    rows_vi.sort(key=lambda r: (r.get("code", ""), r.get("is_admin", "")))

    script_dir = os.path.dirname(os.path.abspath(__file__))
    out_sql = os.path.join(
        script_dir, "..", "crmeb", "public", "install", "patch_lang_code_vi.sql"
    )
    os.makedirs(os.path.dirname(out_sql), exist_ok=True)

    with open(out_sql, "w", encoding="utf-8") as out:
        out.write("-- Bản dịch tiếng Việt cho eb_lang_code (type_id=10, vi-VN)\n")
        out.write("-- Nguồn: eb_lang_code.csv. Chạy sau khi import crmeb.sql (PREFIX bảng: eb_).\n")
        out.write("-- Bảng không có UNIQUE (type_id, code, is_admin); nếu đã có bản ghi type_id=10 thì cân nhắc bỏ qua hoặc xóa trước.\n\n")
        if missing_vi:
            out.write(f"-- Cảnh báo: Trong CSV có {len(missing_vi)} code có tiếng Trung nhưng chưa có bản tiếng Việt (không thể sinh INSERT từ CSV).\n")
            out.write("-- Các code thiếu (cần bổ sung thủ công hoặc dịch):\n")
            for m in missing_vi[:50]:
                out.write(f"--   code={m['code']} is_admin={m['is_admin']} remarks={m['remarks']}\n")
            if len(missing_vi) > 50:
                out.write(f"--   ... và {len(missing_vi) - 50} code khác.\n")
            out.write("\n")
        out.write("-- INSERT bản tiếng Việt (type_id=10), không chỉ định id (AUTO_INCREMENT).\n\n")
        out.write(
            "INSERT INTO `eb_lang_code` (`type_id`, `code`, `remarks`, `lang_explain`, `is_admin`) VALUES\n"
        )
        values = []
        for r in rows_vi:
            code = escape_sql(r.get("code", ""))
            remarks = escape_sql(r.get("remarks", ""))
            lang_explain = escape_sql(r.get("lang_explain", ""))
            is_admin = (r.get("is_admin") or "1").strip()
            values.append(f"(10, '{code}', '{remarks}', '{lang_explain}', {is_admin})")
        out.write(",\n".join(values))
        out.write(";\n")

    print(f"Đã ghi: {out_sql}")
    print(f"  - Số bản ghi tiếng Việt (type_id=10) INSERT: {len(rows_vi)}")
    if missing_vi:
        print(f"  - Số code có tiếng Trung chưa có tiếng Việt trong CSV: {len(missing_vi)} (xem comment trong file SQL)")
    else:
        print("  - Trong CSV: mọi code có tiếng Trung đều đã có bản tiếng Việt.")


if __name__ == "__main__":
    main()
