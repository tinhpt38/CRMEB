#!/usr/bin/env python3
import json
import re
from pathlib import Path


REPO = Path(__file__).resolve().parents[3]
DOWNLOADS = Path("/Users/tinhp/Downloads")
MENU_SQL = DOWNLOADS / "eb_system_menus (1).sql"
ROUTE_SQL = DOWNLOADS / "eb_system_route (1).sql"

MENU_KEY_RE = re.compile(r"^(sm_[a-f0-9]{24}|k_[A-Za-z0-9_-]+|[A-Za-z_][A-Za-z0-9_.-]*)$")
ROUTE_KEY_RE = re.compile(r"^(sr_[a-f0-9]{24}|k_[A-Za-z0-9_-]+|[A-Za-z_][A-Za-z0-9_.-]*)$")


def parse_sql_rows(sql_text: str):
    # Extract id + 4th string field in tuple: menu_name/name
    rows = []
    for match in re.finditer(r"\((\d+),\s*\d+,\s*'[^']*',\s*'((?:[^'\\]|\\.)*)'", sql_text):
        row_id = int(match.group(1))
        label = match.group(2).replace("\\'", "'")
        rows.append((row_id, label))
    return rows


def parse_js_map(path: Path):
    text = path.read_text(encoding="utf-8")
    return dict(re.findall(r"'([^']+)'\s*:\s*'([^']*)'", text))


def build_report():
    menu_sql = MENU_SQL.read_text(encoding="utf-8", errors="ignore")
    route_sql = ROUTE_SQL.read_text(encoding="utf-8", errors="ignore")
    menu_rows = parse_sql_rows(menu_sql)
    route_rows = parse_sql_rows(route_sql)

    menu_non_key = [{"id": row_id, "value": value} for row_id, value in menu_rows if value and not MENU_KEY_RE.match(value)]
    route_non_key = [{"id": row_id, "value": value} for row_id, value in route_rows if value and not ROUTE_KEY_RE.match(value)]

    locales = ["vi", "en", "zh-cn", "zh-tw"]
    menu_locale_maps = {
        loc: parse_js_map(REPO / f"template/admin/src/i18n/pages/systemMenusDb/{loc}.js") for loc in locales
    }
    route_locale_maps = {
        loc: parse_js_map(REPO / f"template/admin/src/i18n/pages/systemRouteDb/{loc}.js") for loc in locales
    }

    def shared_same_values(locale_maps):
        keys = set.intersection(*[set(m.keys()) for m in locale_maps.values()])
        out = []
        for key in sorted(keys):
            values = {loc: locale_maps[loc][key] for loc in locales}
            if len(set(values.values())) == 1:
                out.append({"key": key, "value": values["vi"]})
        return out

    return {
        "summary": {
            "menu_rows": len(menu_rows),
            "route_rows": len(route_rows),
            "menu_non_key_rows": len(menu_non_key),
            "route_non_key_rows": len(route_non_key),
            "menu_all_locale_same_value_keys": len(shared_same_values(menu_locale_maps)),
            "route_all_locale_same_value_keys": len(shared_same_values(route_locale_maps)),
        },
        "menu_non_key_rows": menu_non_key[:200],
        "route_non_key_rows": route_non_key[:200],
        "menu_all_locale_same_value_keys_sample": shared_same_values(menu_locale_maps)[:500],
        "route_all_locale_same_value_keys_sample": shared_same_values(route_locale_maps)[:500],
    }


def main():
    report = build_report()
    out_path = REPO / "template/admin/scripts/output/system-i18n-audit.json"
    out_path.write_text(json.dumps(report, ensure_ascii=False, indent=2), encoding="utf-8")
    print(f"Wrote report: {out_path}")
    print(json.dumps(report["summary"], ensure_ascii=False, indent=2))


if __name__ == "__main__":
    main()

