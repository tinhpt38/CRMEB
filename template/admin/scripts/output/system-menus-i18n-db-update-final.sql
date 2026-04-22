-- Final SQL update: force all non-key labels to i18n key format.
-- This handles cases like "Order", "Quản lý đơn hàng", etc.
-- Key format generated: k_<base64url(original_text)>
-- Compatible with existing resolver:
--   systemMenusDbRawKey/menuRouteDbRawKey in systemMenus pages.
START TRANSACTION;

-- 1) Normalize menu names in eb_system_menus
UPDATE eb_system_menus
SET menu_name = CONCAT(
  'k_',
  REPLACE(
    REPLACE(
      TRIM(TRAILING '=' FROM TO_BASE64(CONVERT(menu_name USING utf8mb4))),
      '+',
      '-'
    ),
    '/',
    '_'
  )
)
WHERE menu_name IS NOT NULL
  AND menu_name <> ''
  AND menu_name NOT REGEXP '^(sm_[a-f0-9]{24}|k_[A-Za-z0-9_-]+)$';

-- 2) Normalize route names in eb_system_route
UPDATE eb_system_route
SET name = CONCAT(
  'k_',
  REPLACE(
    REPLACE(
      TRIM(TRAILING '=' FROM TO_BASE64(CONVERT(name USING utf8mb4))),
      '+',
      '-'
    ),
    '/',
    '_'
  )
)
WHERE name IS NOT NULL
  AND name <> ''
  AND name NOT REGEXP '^(sr_[a-f0-9]{24}|k_[A-Za-z0-9_-]+)$';

-- Verify
SELECT id, menu_name FROM eb_system_menus WHERE menu_name NOT REGEXP '^(sm_[a-f0-9]{24}|k_[A-Za-z0-9_-]+)$' LIMIT 20;
SELECT id, name FROM eb_system_route WHERE name NOT REGEXP '^(sr_[a-f0-9]{24}|k_[A-Za-z0-9_-]+)$' LIMIT 20;

COMMIT;
-- ROLLBACK;
