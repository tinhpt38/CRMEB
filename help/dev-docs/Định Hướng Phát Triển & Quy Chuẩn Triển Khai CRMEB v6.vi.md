# Định Hướng Phát Triển & Quy Chuẩn Triển Khai CRMEB v6

## 1) Mục tiêu tài liệu

Tài liệu này là baseline kỹ thuật cho các vòng phát triển tiếp theo của dự án CRMEB v6, dùng để:

- Đồng nhất cách hiểu kiến trúc giữa backend, admin frontend, mobile frontend.
- Chuẩn hóa quy trình thêm/sửa tính năng để giảm regressions.
- Xác định thứ tự ưu tiên đầu tư kỹ thuật (test, CI, hiệu năng, vận hành).
- Làm checklist bắt buộc trước khi merge/release.

---

## 2) Hiện trạng dự án (từ source + tài liệu)

## 2.1 Kiến trúc tổng thể

- Backend: `ThinkPHP 6` theo mô hình nhiều ứng dụng (`app/adminapi`, `app/api`, `app/kefuapi`, `app/outapi`).
- Tầng nghiệp vụ tách lớp tương đối rõ: `controller -> services -> dao -> model`.
- Core framework nội bộ nằm ở `crmeb/crmeb` (services, utils, command, interfaces...).
- Frontend tách 2 dự án chính:
  - Admin: `template/admin` (Vue2 + ElementUI).
  - Mobile: `template/uni-app` (UniApp, chạy qua HBuilderX).

## 2.2 Entry points vận hành chính

- Backend runtime:
  - Queue: `php think queue:listen --queue`
  - Workerman: `php think workerman start --d`
  - Timer: `php think timer start --d`
- Frontend admin:
  - Dev: `npm run dev`
  - Build: `npm run build`
  - Lint: `npm run eslint`
- Docker có trong `help/docker` với `docker-compose.yml` và `run.sh`.

## 2.3 Vấn đề/rủi ro cần xử lý sớm

- Thiếu chuẩn test/CI thống nhất ở cấp repo.
- Tài liệu nhiều bản ngôn ngữ dễ lệch nội dung theo thời gian.
- Runtime phụ thuộc các tiến trình queue/workerman/timer, cần chuẩn hóa healthcheck và giám sát.
- Có dấu hiệu lẫn artifact frontend (build/dependency) trong cây source, dễ tăng rủi ro release.

---

## 3) Nguyên tắc phát triển bắt buộc

## 3.1 Backend (PHP/ThinkPHP)

- Không viết truy vấn trực tiếp trong Controller.
- Controller chỉ làm: nhận request, validate, gọi service, trả `success/fail`.
- Service xử lý nghiệp vụ, transaction, orchestration giữa DAO + external services.
- DAO chỉ chứa truy vấn dữ liệu, không nhúng business rules.
- Model chỉ giữ mapping bảng, quan hệ, scope tìm kiếm.
- Validate tách file theo module, không validate inline trong controller.
- Throw exception chuẩn (`ApiException`, `AdminException`, `AuthException`) thay vì trả lỗi tự do.

## 3.2 Frontend (Admin/UniApp)

- API client tách theo module trong `api/`.
- Không gọi API trực tiếp trong component nếu có thể gom vào layer api/service.
- Mọi thay đổi endpoint phải cập nhật đồng thời: backend route + frontend api wrapper + mô tả tài liệu.
- Chuẩn hóa xử lý lỗi API ở 1 lớp chung (interceptor/handler), tránh copy/paste.

## 3.3 Dữ liệu và migration

- Mọi thay đổi schema phải có script migration/versioning.
- Không sửa cấu trúc DB trực tiếp trên production không qua script.
- Chỉ số hiệu năng (index, explain query, query chậm) phải được đánh giá khi thêm tính năng dữ liệu lớn.

---

## 4) Quy trình chỉnh sửa chi tiết theo loại yêu cầu

## 4.1 Thêm mới API backend

1. Tạo route trong file route tương ứng (`adminapi` hoặc `api`).
2. Tạo controller method mới (chỉ nhận tham số + gọi service).
3. Tạo/điều chỉnh validate class cho input.
4. Bổ sung service method (nghiệp vụ chính).
5. Nếu cần truy vấn dữ liệu mới: bổ sung DAO + model scope.
6. Nếu có side effects async: đẩy queue/job thay vì chạy đồng bộ.
7. Trả response theo chuẩn `success/fail`, không trả cấu trúc tùy ý.
8. Cập nhật tài liệu API (ít nhất request/response mẫu + error code).

## 4.2 Sửa logic nghiệp vụ đã có

1. Xác định service đang là nguồn sự thật của nghiệp vụ.
2. Đánh giá tác động chéo: đơn hàng, user, thanh toán, marketing.
3. Bọc transaction cho các khối ghi đa bảng.
4. Đảm bảo idempotent cho thao tác callback/thanh toán/retry queue.
5. Bổ sung test case tối thiểu cho nhánh thay đổi.
6. Log có cấu trúc để truy vết (request id, uid, order id, action).

## 4.3 Thêm màn hình admin

1. Tạo route frontend (`template/admin/src/router`).
2. Tạo page trong `pages/` và component dùng lại trong `components/` nếu có.
3. Tạo file API trong `src/api`.
4. Ràng buộc quyền menu/nút theo RBAC.
5. Kiểm tra trạng thái loading/error/empty và phân trang.
6. Chạy `npm run eslint` trước khi merge.

## 4.4 Thêm chức năng mobile (uni-app)

1. Tạo page/subpackage phù hợp trong `template/uni-app`.
2. Bổ sung API wrapper tương ứng.
3. Kiểm tra hành vi đa nền tảng (H5 + mini program + app nếu áp dụng).
4. Kiểm tra hiệu năng render danh sách dài (lazy load/pagination).
5. Đồng bộ tài liệu nghiệp vụ để tránh lệch với admin/backend.

---

## 5) Chuẩn chất lượng trước khi merge

## 5.1 Checklist kỹ thuật bắt buộc

- [ ] Đúng layering (`controller -> service -> dao -> model`).
- [ ] Có validate cho input quan trọng.
- [ ] Không lộ dữ liệu nhạy cảm trong response/log.
- [ ] Có xử lý lỗi chuẩn bằng exception phù hợp.
- [ ] Có kiểm thử tối thiểu cho luồng chính và luồng lỗi.
- [ ] Đã cập nhật tài liệu khi thay đổi API/quy trình.

## 5.2 Checklist vận hành

- [ ] Nếu tác động queue/workerman/timer: có mô tả deploy/restart tương ứng.
- [ ] Nếu có migration: có kế hoạch rollback.
- [ ] Nếu có thay đổi cache key: có kế hoạch clear cache an toàn.
- [ ] Có đánh giá ảnh hưởng hiệu năng (DB/API).

---

## 6) Kế hoạch phát triển theo giai đoạn

## Giai đoạn 1 (ngắn hạn: 2-4 tuần) - Ổn định nền tảng

- Chuẩn hóa coding convention backend/frontend vào 1 tài liệu chính thức.
- Bổ sung script kiểm tra tối thiểu (lint backend/frontend + smoke test API).
- Định nghĩa danh sách service trọng yếu cần test trước:
  - auth/token
  - order create/pay/status flow
  - inventory reservation/release
  - payment callback idempotency

## Giai đoạn 2 (trung hạn: 1-2 tháng) - Nâng chất lượng triển khai

- Thiết kế pipeline CI cơ bản:
  - Backend static check + unit test tối thiểu.
  - Frontend eslint + build verify.
- Chuẩn hóa release checklist (migrate, queue, timer, cache, rollback).
- Thiết lập logging tiêu chuẩn và dashboard theo module chính.

## Giai đoạn 3 (dài hạn: 2-3 tháng) - Tối ưu hiệu năng và khả năng mở rộng

- Rà soát truy vấn chậm và chiến lược index cho bảng lớn.
- Phân loại dữ liệu nóng/lạnh, tối ưu cache key và TTL.
- Tách rõ domain boundaries (order/payment/user/marketing) để dễ scale nhóm phát triển.
- Chuẩn hóa kiến trúc sự kiện nội bộ cho các tác vụ bất đồng bộ.

---

## 7) Mẫu quy trình cho mỗi tính năng mới (Feature Delivery Template)

Mỗi ticket nên có tối thiểu các mục:

1. **Bối cảnh nghiệp vụ**: mục tiêu, actor, điều kiện vào.
2. **Phạm vi thay đổi**:
   - Backend files/module.
   - Admin frontend files/module.
   - UniApp files/module.
   - DB migration/config ảnh hưởng.
3. **API contract**: endpoint, request, response, error code.
4. **Quy tắc nghiệp vụ**: case chính + edge cases.
5. **Kế hoạch test**:
   - unit/integration/manual.
   - dữ liệu kiểm thử.
6. **Kế hoạch deploy/rollback**:
   - thứ tự deploy.
   - lệnh runtime cần chạy lại.
   - điều kiện rollback.

---

## 8) Đề xuất tổ chức tài liệu để tránh lệch phiên bản

- Thiết lập 1 “nguồn chuẩn” tài liệu kiến trúc/phát triển (tiếng Việt).
- Các bản ngôn ngữ khác là bản dịch, có tag version bám theo bản chuẩn.
- Mọi PR đổi logic API bắt buộc cập nhật tài liệu cùng commit/PR.
- Định kỳ mỗi sprint rà soát diff giữa tài liệu và source.

---

## 9) Định nghĩa Done (DoD) khuyến nghị cho đội

Một thay đổi chỉ được coi là hoàn tất khi:

- Đã qua review kỹ thuật và đúng chuẩn layer.
- Đã qua kiểm tra lint/build bắt buộc.
- Đã test luồng chính + luồng lỗi.
- Đã cập nhật tài liệu và checklist release.
- Có kế hoạch giám sát sau deploy (log/metric/error alert).

---

## 10) Hành động ưu tiên ngay sau tài liệu này

1. Tạo checklist PR template theo mục 5 + mục 7.
2. Lập danh sách 10 API quan trọng để viết regression tests đầu tiên.
3. Chuẩn hóa lệnh kiểm tra chung ở root (script thống nhất cho backend/frontend).
4. Chuẩn hóa tài liệu vận hành queue/workerman/timer theo môi trường.

---

Tài liệu này nên được xem là “phiên bản gốc” cho định hướng phát triển CRMEB v6 trong đội kỹ thuật.
