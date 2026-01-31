-- Patch: Dữ liệu mặc định tiếng Việt (Giai đoạn 5)
-- Chạy sau khi import crmeb.sql. PREFIX theo .env (eb_).
-- Cập nhật: eb_agreement (title), eb_agent_level (name), eb_category (name).
-- Nội dung dài (agreement.content) nên sửa trong Admin → Cài đặt → Thỏa thuận.

-- 1. eb_agreement – tiêu đề thỏa thuận
UPDATE eb_agreement SET title = 'Thỏa thuận hội viên trả phí' WHERE id = 1;
UPDATE eb_agreement SET title = 'Quy định đại lý' WHERE id = 2;
UPDATE eb_agreement SET title = 'Chính sách bảo mật' WHERE id = 3;
UPDATE eb_agreement SET title = 'Thỏa thuận người dùng' WHERE id = 4;
UPDATE eb_agreement SET title = 'Thỏa thuận hủy tài khoản' WHERE id = 5;
UPDATE eb_agreement SET title = 'Thỏa thuận tích điểm' WHERE id = 6;
UPDATE eb_agreement SET title = 'Về chúng tôi' WHERE id = 7;
UPDATE eb_agreement SET title = 'Hướng dẫn phân phối' WHERE id = 8;

-- 2. eb_agent_level – tên cấp phân phối
UPDATE eb_agent_level SET name = 'Cấp phân phối 1' WHERE id = 1;
UPDATE eb_agent_level SET name = 'Cấp phân phối 2' WHERE id = 2;
UPDATE eb_agent_level SET name = 'Cấp phân phối 3' WHERE id = 3;
UPDATE eb_agent_level SET name = 'Cấp phân phối 4' WHERE id = 4;
UPDATE eb_agent_level SET name = 'Cấp phân phối 5' WHERE id = 5;

-- 3. eb_category – tên nhóm phân loại (dùng cho nhãn/tag khách hàng, vd. CRM)
UPDATE eb_category SET name = 'Giới tính khách hàng' WHERE id = 1;
UPDATE eb_category SET name = 'Nguồn khách hàng' WHERE id = 2;
UPDATE eb_category SET name = 'Đã dùng loại sản phẩm nào' WHERE id = 3;
UPDATE eb_category SET name = 'Độ tuổi khách hàng' WHERE id = 4;
UPDATE eb_category SET name = 'Số lần mua tích lũy' WHERE id = 5;
UPDATE eb_category SET name = 'Số tiền mua tích lũy' WHERE id = 6;
