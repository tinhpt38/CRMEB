# CRMEB Envelope Contract Cases

File liên quan:
- `src/utils/crmeb/envelope.ts` (`normalizeCrmebEnvelope`)

## 1) Success payload (plain `data`)
### Input (raw JSON)
```json
{
  "status": 200,
  "msg": "success",
  "data": { "hello": "world" }
}
```
### Expected (normalized result)
- `ok: true`
- `data` should equal `{ "hello": "world" }`

## 2) Success payload (business wrapper: `data.status` + `data.result`)
### Input
```json
{
  "status": 200,
  "msg": "Hoạt động thành công",
  "data": {
    "status": "NONE",
    "result": { "orderKey": "abc123" }
  }
}
```
### Expected
- `ok: true`
- `data` should unwrap to `{ "orderKey": "abc123" }`

## 3) Error payload (HTTP/business error surfaced as non-200 `status`)
### Input
```json
{
  "status": 400,
  "msg": "Invalid parameter"
}
```
### Expected
- `ok: false`
- `error.status` is `400`
- `error.msg` is `"Invalid parameter"`

## 4) Invalid envelope (missing `status` / `msg`)
### Input
```json
{ "data": {} }
```
### Expected
- `ok: false`
- `error.status` should be `"INVALID_ENVELOPE"`
- `error.msg` should indicate missing fields

