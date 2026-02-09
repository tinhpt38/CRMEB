#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Gọi API Admin lang_code/list (type_id=10 = tiếng Việt) để lấy toàn bộ bản dịch,
lưu ra JSON dùng cho bổ sung chi tiết ngôn ngữ tiếng Việt.

Cách 1 - Qua API (cần token Admin):
  export CRMEB_ADMIN_URL="https://crmeb.local:7890/adminapi"
  export ADMIN_TOKEN="your_bearer_token"
  python scripts/fetch_lang_code_vi.py

Cách 2 - Đọc trực tiếp từ MySQL (dùng .env):
  python scripts/fetch_lang_code_vi.py --from-db

Đầu ra:
  scripts/lang_code_vi.json         - map code -> lang_explain (tiếng Việt)
  scripts/lang_code_vi_full.json   - danh sách đầy đủ (id, code, remarks, lang_explain, is_admin)
"""

import json
import os
import re
import sys
from pathlib import Path
from urllib.parse import urlencode

REPO_ROOT = Path(__file__).resolve().parent.parent
ENV_FILE = REPO_ROOT / "crmeb/.env"
OUT_MAP = REPO_ROOT / "scripts/lang_code_vi.json"
OUT_FULL = REPO_ROOT / "scripts/lang_code_vi_full.json"

TYPE_ID_VI = 10  # eb_lang_type.id cho vi-VN
DEFAULT_LIMIT = 100


def load_env():
    """Đọc biến từ crmeb/.env (dạng KEY = value hoặc [SECTION])."""
    env = {}
    if not ENV_FILE.exists():
        return env
    with open(ENV_FILE, "r", encoding="utf-8") as f:
        section = ""
        for line in f:
            line = line.strip()
            if line.startswith("["):
                section = line[1:-1].strip()
                continue
            if "=" in line and not line.startswith("#"):
                k, v = line.split("=", 1)
                k, v = k.strip(), v.strip()
                if section:
                    k = section + "_" + k
                env[k] = v
    return env


def fetch_via_api(base_url: str, token: str) -> list:
    """Gọi API GET setting/lang_code/list với type_id=10, phân trang, trả về list đầy đủ."""
    try:
        import urllib.request
    except ImportError:
        import urllib.request  # noqa
    headers = {
        "Accept": "application/json",
        "Authorization": f"Bearer {token}",
    }
    all_list = []
    page = 1
    while True:
        params = {
            "is_admin": 0,
            "type_id": TYPE_ID_VI,
            "page": page,
            "limit": DEFAULT_LIMIT,
        }
        url = f"{base_url.rstrip('/')}/setting/lang_code/list?{urlencode(params)}"
        req = urllib.request.Request(url, headers=headers, method="GET")
        try:
            with urllib.request.urlopen(req, timeout=30) as resp:
                data = json.loads(resp.read().decode("utf-8"))
        except Exception as e:
            print(f"Lỗi gọi API: {e}", file=sys.stderr)
            return all_list
        # CRMEB trả về { status: 200, data: { list: [], count: N } }
        body = data.get("data") or data
        lst = body.get("list") or []
        if not lst:
            break
        all_list.extend(lst)
        count = body.get("count", 0)
        if len(all_list) >= count:
            break
        page += 1
    # Lấy thêm is_admin=1 (interface language)
    page = 1
    while True:
        params = {
            "is_admin": 1,
            "type_id": TYPE_ID_VI,
            "page": page,
            "limit": DEFAULT_LIMIT,
        }
        url = f"{base_url.rstrip('/')}/setting/lang_code/list?{urlencode(params)}"
        req = urllib.request.Request(url, headers=headers, method="GET")
        try:
            with urllib.request.urlopen(req, timeout=30) as resp:
                data = json.loads(resp.read().decode("utf-8"))
        except Exception:
            break
        body = data.get("data") or data
        lst = body.get("list") or []
        if not lst:
            break
        all_list.extend(lst)
        if len(lst) < DEFAULT_LIMIT:
            break
        page += 1
    return all_list


def fetch_via_mysql(env: dict) -> list:
    """Đọc bảng eb_lang_code từ MySQL (type_id=10), dùng cấu hình DATABASE trong .env."""
    try:
        import pymysql
    except ImportError:
        print("Cần cài pymysql: pip install pymysql", file=sys.stderr)
        return []
    # Keys từ [DATABASE] => DATABASE_TYPE, DATABASE_HOSTNAME, ...
    prefix = env.get("DATABASE_PREFIX") or env.get("PREFIX", "eb_")
    host = env.get("DATABASE_HOSTNAME", "127.0.0.1")
    port = int(env.get("DATABASE_HOSTPORT", "3306"))
    user = env.get("DATABASE_USERNAME", "root")
    password = env.get("DATABASE_PASSWORD", "")
    db = env.get("DATABASE_DATABASE", "crmeb")
    table = f"{prefix}lang_code"
    conn = pymysql.connect(
        host=host,
        port=port,
        user=user,
        password=password,
        database=db,
        charset="utf8mb4",
    )
    rows = []
    try:
        with conn.cursor(pymysql.cursors.DictCursor) as cur:
            cur.execute(
                f"SELECT id, type_id, code, remarks, lang_explain, is_admin FROM `{table}` WHERE type_id = %s ORDER BY id",
                (TYPE_ID_VI,),
            )
            rows = cur.fetchall()
    finally:
        conn.close()
    return rows


def main():
    env = load_env()
    base_url = os.environ.get("CRMEB_ADMIN_URL") or "https://crmeb.local:7890/adminapi"
    token = os.environ.get("ADMIN_TOKEN", "").strip()

    from_db = "--from-db" in sys.argv or "-d" in sys.argv

    if from_db:
        print("Đọc từ MySQL (crmeb/.env)...")
        rows = fetch_via_mysql(env)
    else:
        if not token:
            print("Chưa có ADMIN_TOKEN. Chạy: export ADMIN_TOKEN='<token>'", file=sys.stderr)
            print("Hoặc dùng đọc từ DB: python scripts/fetch_lang_code_vi.py --from-db", file=sys.stderr)
            return 1
        print(f"Gọi API {base_url} (type_id={TYPE_ID_VI})...")
        rows = fetch_via_api(base_url, token)

    if not rows:
        print("Không có bản ghi nào.", file=sys.stderr)
        return 1

    # Map code -> lang_explain (dùng cho getLang / bổ sung)
    code_map = {}
    for r in rows:
        code = r.get("code") or r.get("code", "")
        explain = r.get("lang_explain") or ""
        if code:
            code_map[code] = explain

    # Danh sách đầy đủ
    full_list = [
        {
            "id": r.get("id"),
            "code": r.get("code"),
            "remarks": r.get("remarks"),
            "lang_explain": r.get("lang_explain"),
            "is_admin": r.get("is_admin"),
        }
        for r in rows
    ]

    OUT_MAP.parent.mkdir(parents=True, exist_ok=True)
    with open(OUT_MAP, "w", encoding="utf-8") as f:
        json.dump(code_map, f, ensure_ascii=False, indent=2)
    with open(OUT_FULL, "w", encoding="utf-8") as f:
        json.dump(full_list, f, ensure_ascii=False, indent=2)

    print(f"Đã lưu: {OUT_MAP} ({len(code_map)} code)")
    print(f"Đã lưu: {OUT_FULL} ({len(full_list)} dòng)")
    return 0


if __name__ == "__main__":
    sys.exit(main())
