---
name: crmeb-vn-assistant
description: Hướng dẫn phát triển CRMEB bằng tiếng Việt theo mô hình ThinkPHP6 + Admin Vue2 + UniApp, chuẩn phân tầng Service/DAO/Model, quy tắc API, commit và vận hành. Dùng khi người dùng chỉnh sửa thư mục crmeb, template/admin, template/uni-app, hoặc hỏi về kiến trúc/chuẩn code CRMEB.
---

# CRMEB VN Assistant

## Mục tiêu

Giúp tác vụ phát triển CRMEB nhất quán, nhanh và đúng chuẩn dự án hiện có:
- Backend: ThinkPHP 6, mô hình `Controller -> Services -> Dao -> Model`
- Frontend admin: Vue 2 + Element UI
- Mobile: UniApp
- Vận hành: Queue, Timer, Workerman

## Khi nào áp dụng

Áp dụng skill này khi có một trong các tín hiệu:
- Làm việc trong `crmeb/app/`, `crmeb/crmeb/`, `crmeb/config/`
- Làm việc trong `template/admin/` hoặc `template/uni-app/`
- Yêu cầu liên quan tới API, bảng dữ liệu `eb_*`, đơn hàng, thanh toán, marketing
- Yêu cầu chuẩn commit, chuẩn tài liệu, hoặc quy trình triển khai CRMEB

## Bản đồ kiến trúc nhanh

### Backend CRMEB
- `crmeb/app/adminapi|api|kefuapi|outapi`: các cổng API theo ngữ cảnh
- `crmeb/app/services`: nghiệp vụ chính
- `crmeb/app/dao`: truy cập dữ liệu
- `crmeb/app/model`: ánh xạ bảng
- `crmeb/crmeb/basic`: base class dùng chung
- `crmeb/config`: cấu hình DB/cache/queue/workerman

Luồng mặc định:
1. Controller nhận request, lấy tham số bằng `getMore/postMore`
2. Service xử lý nghiệp vụ, validate điều kiện business
3. Dao tạo query, tránh nhúng SQL nguy hiểm
4. Model chịu trách nhiệm quan hệ/attr mutator
5. Trả về JSON thống nhất qua `app('json')->success/error`

### Admin frontend
- `template/admin/src/api`: định nghĩa API
- `template/admin/src/pages`: màn hình
- `template/admin/src/components`: thành phần dùng lại
- `template/admin/src/router`, `store`, `utils`: điều hướng, state, tiện ích

### UniApp
- `template/uni-app/pages`, `components`, `api`, `utils`
- Cấu hình tại `pages.json`, `manifest.json`

## Quy ước cốt lõi cần giữ

### 1) Quy ước backend
- Dùng phân tầng, không dồn business logic vào Controller
- Tên lớp: `XxxServices`, `XxxDao`, `Xxx` (Model)
- Bảng dùng tiền tố `eb_`, khóa chính `id`, cờ mềm xóa `is_del`
- Tránh `SELECT *`, ưu tiên field cần thiết + index cho truy vấn chính

### 2) Quy ước API
- Dùng RESTful theo ngữ cảnh dự án
- Format phản hồi thống nhất:
  - Thành công: `code/msg/data`
  - Lỗi: thông điệp rõ ràng, không lộ thông tin nhạy cảm

### 3) Quy ước frontend
- Vue2/Element: tách `api` khỏi `pages/components`
- Dùng camelCase cho biến/hàm, tên component rõ nghĩa
- Với màn hình lớn, ưu tiên chia nhỏ component để tái sử dụng

### 4) Quy ước vận hành
- Queue: `php think queue:listen --queue`
- Timer: `php think timer start --d`
- Workerman: `php think workerman start --d`
- Tác vụ nặng (thống kê, thông báo, hậu xử lý thanh toán) nên đẩy queue

## Checklist khi tạo tính năng mới

- [ ] Xác định module nghiệp vụ (user/product/order/activity/agent/system)
- [ ] Thiết kế bảng `eb_*` theo chuẩn tên cột
- [ ] Tạo/điều chỉnh Model, Dao, Services, Controller
- [ ] Khai báo route và validate dữ liệu đầu vào
- [ ] Bổ sung API admin/uniapp tương ứng nếu cần
- [ ] Kiểm tra ảnh hưởng queue/timer/event
- [ ] Viết mô tả commit rõ ràng, không gộp thay đổi không liên quan

## Mẫu commit gợi ý cho CRMEB

Ưu tiên format có ngữ cảnh module:
- `[订单] 修复退款状态流转`
- `[商品] 新增多规格库存校验`
- `[前端文件] 优化管理端订单筛选`
- `[开发文档] 更新支付回调说明`

Nếu team đang dùng prefix thư mục, bám theo chuẩn repo hiện tại.

## Cách phản hồi mặc định khi dùng skill này

Khi nhận yêu cầu code, phản hồi theo trình tự:
1. Xác nhận module và lớp bị ảnh hưởng (Controller/Service/Dao/Model hoặc admin/uniapp)
2. Nêu thay đổi chính và rủi ro tương thích
3. Thực hiện sửa code theo chuẩn phân tầng
4. Đề xuất bước verify ngắn: API test, luồng nghiệp vụ, queue/timer nếu liên quan

## Không làm

- Không nâng cấp framework trái ràng buộc dự án (ví dụ TP7/Vue3) nếu chưa có yêu cầu
- Không phá vỡ cấu trúc thư mục CRMEB sẵn có
- Không commit chung nhiều mục tiêu không liên quan

## Việt hóa / fork VN (Phase 0–6)

- **Client chính VN**: `f-chan/` (Zalo Mini App), không rollout `template/uni-app` làm kênh chính
- **Patch DB**: `crmeb/public/install/patches/` — chạy theo `README.md` hoặc `apply-vn-patches.sh`
- **Deploy**: `docs/vn-deployment.md` · **Thanh toán VN**: `docs/vn-payment-setup.md`
- **Tiền tệ UI**: dùng `đ` / `format_vnd()`; không dùng `￥` trên luồng user-facing
- **SĐT**: regex VN (`PhoneValidate`, form admin/f-chan); giữ regex TQ chỉ ở SMS Trung Quốc
- **DIY/theme**: patch `vn_diy_label_migration.sql` + fallback `crmeb/crmeb/utils/DiyHomeLabelMap.php`
- **Sau patch SQL**: luôn `php think clear`
