# TASK TEMPLATE (CRMEB)

## 1) Muc tieu
- [Mo ta ket qua cuoi cung mong muon]

## 2) Pham vi
- Backend: [vd: app/adminapi/controller/system/**, app/services/system/**]
- Frontend: [vd: template/admin/src/pages/system/**]
- Quy uoc: "module admin/system" = `admin/system/**` va tat ca trang con, component con.

## 3) Quy trinh bat buoc
1. Doc backend truoc: route -> controller -> service -> model/repository -> response data shape.
2. Lien ket frontend sau: API call file -> page/component render -> state/filter/form lien quan.
3. Tom tat luong du lieu backend -> frontend truoc khi sua.
4. Chi implement sau khi xac nhan da hieu dung pham vi.

## 4) Output truoc khi code
- Pham vi hieu duoc: [...]
- File du kien sua: [...]
- Rui ro anh huong: [...]

## 5) Rang buoc
- Khong doi API contract neu chua duoc xac nhan.
- Khong them package moi.
- Giu style/convention hien tai cua du an.
- Khong sua ngoai pham vi da chot.

## 6) Tieu chi hoan thanh
- [ ] Dung logic nghiep vu
- [ ] Dung UI/render theo module yeu cau
- [ ] Khong loi lint/build trong pham vi thay doi
- [ ] Co huong dan verify ngan gon

## 7) Verify
- Commands: [vd: npm run lint], [vd: npm run build]
- Manual checks: [cac buoc test tay cu the]

## 8) Tu dien du an (rat quan trong)
- "doc ngu canh" = doc code lien quan truoc, chua sua ngay.
- "lien ket backend -> frontend" = tim endpoint + payload + noi goi API + noi render.
- "module admin/system" = toan bo `admin/system/**`.
