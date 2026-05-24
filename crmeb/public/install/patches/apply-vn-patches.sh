#!/usr/bin/env bash
# Apply CRMEB VN SQL patches in recommended order.
# Usage: ./apply-vn-patches.sh "mysql -uUSER -pPASS DBNAME"
set -euo pipefail

if [ $# -lt 1 ]; then
  echo "Usage: $0 \"mysql -uUSER -pPASS DBNAME\"" >&2
  exit 1
fi

MYSQL_CMD=("$@")
DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

PATCHES=(
  vn_lang_defaults.sql
  vn_city_migration.sql
  vn_sys_config_defaults.sql
  vn_lang_currency_cleanup.sql
  vn_group_data_defaults.sql
  vn_status_labels_global.sql
  vn_pay_basic_cleanup.sql
  vn_pay_tabs_reorganize.sql
  vn_pay_admin_config.sql
  vn_pay_gateways.sql
)

OPTIONAL=(
  vn_diy_label_migration.sql
  vn_notice_channel_registry.sql
  vn_notification_telegram.sql
)

run_patch() {
  local file="$1"
  echo "==> $file"
  "${MYSQL_CMD[@]}" < "$DIR/$file"
}

for p in "${PATCHES[@]}"; do
  run_patch "$p"
done

if [ "${APPLY_OPTIONAL:-}" = "1" ]; then
  for p in "${OPTIONAL[@]}"; do
    if [ -f "$DIR/$p" ]; then
      run_patch "$p"
    fi
  done
else
  echo ""
  echo "Optional patches skipped. To include DIY/notify patches:"
  echo "  APPLY_OPTIONAL=1 $0 \"mysql ...\""
fi

echo ""
echo "Done. Run: cd crmeb && php think clear"
