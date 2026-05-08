# f-chan -> CRMEB Auth E2E Checklist

## Prerequisites
- `template.apiUrl` in `f-chan/app-config.json` points to reachable CRMEB `/api`.
- CRMEB has valid `zalo_app_secret` configured.
- At least one user label category can be created in CRMEB.

## Cases

1. New user, no existing `eb_user.phone`
   - Login from f-chan with valid Zalo `access_token`.
   - Expected:
     - New record created in `eb_user`.
     - Mapping created/updated in `eb_wechat_user` (`user_type = zalo`).
     - Tag `source:fchan` attached to user.
     - API returns `token`, `expires_time`, `source`.

2. Existing user matched by `eb_user.phone`
   - Call `/api/zalo/auth` with `phone` that already exists in `eb_user`.
   - Expected:
     - No new `eb_user` row.
     - User `last_time` and `last_ip` updated.
     - `nickname/avatar` updated only when those fields are empty.
     - Tag `source:fchan` attached if missing.

3. Existing user already has other source tags
   - User has tags such as `source:web` or `source:miniapp`.
   - Login via f-chan.
   - Expected:
     - Existing source tags remain.
     - `source:fchan` is added without replacing previous tags.

4. Social mapping fallback
   - Existing mapping in `eb_wechat_user` for Zalo openid, but no phone provided.
   - Login via f-chan.
   - Expected:
     - Login succeeds via mapping fallback.
     - No duplicate `eb_user`.

5. Token usability
   - Use returned token to call protected endpoints (e.g. `/order/list`, `/address/list`).
   - Expected:
     - Authenticated APIs succeed with returned CRMEB JWT.

## Negative checks
- Invalid/expired `access_token` returns business error from `/api/zalo/auth`.
- Missing token in `f-chan` prevents `/api/zalo/bind_phone`.
- Invalid OTP blocks `/api/zalo/bind_phone`.
