# PLAYBOOK I18N - admin/product (ghi nho cho lan sau)

## 1) Muc tieu
- Chuyen toan bo text hard-code trong module `template/admin/src/pages/product/**` sang i18n.
- Dam bao UI khong doi nghia khi chuyen ngon ngu (`zh-cn`, `zh-tw`, `en`, `vi`).

## 2) Pattern da su dung trong admin/product
1. Dat key theo namespace man hinh/chuc nang:
   - `message.productList.*`
   - `message.productAdd.*`
   - `message.productCommon.*`
   - `message.productClassify.*`, `message.productSpec.*`, ...
2. Trong template Vue:
   - Text thuong: `{{ $t('message.productList.search') }}`
   - Label co dau `:`: ghep trong template `:label="$t('message.productList.searchProduct') + '：'"`
3. Trong script Vue:
   - Message/toast/confirm: `this.$t('message.productList.selectProductToEdit')`
4. Trong file JS thuần (khong co `this`):
   - `import { i18n } from '@/i18n/index.js'`
   - Tao helper: `const t = (key) => i18n.t(key)`
   - Dung `t('message.productAdd.xxx')` cho constants/rules/options.

## 3) Trinh tu lam i18n cho 1 man product
1. Quet text hard-code trong 1 file man hinh.
2. Gom nhom key theo context (search/filter/button/table/dialog/validate).
3. Them day du key vao 4 file:
   - `template/admin/src/i18n/lang/zh-cn.js`
   - `template/admin/src/i18n/lang/zh-tw.js`
   - `template/admin/src/i18n/lang/en.js`
   - `template/admin/src/i18n/lang/vi.js`
4. Quay lai file Vue/JS thay text bang `$t(...)` hoac `i18n.t(...)`.
5. Chay kiem tra nhanh tren UI voi 4 language.

## 4) Quy tac dat key (de de tim, de maintain)
- Khong dat key qua chung chung nhu `title1`, `text1`.
- Uu tien key theo y nghia nghiep vu:
  - Tot: `productList.selectProductToOnShelf`
  - Khong tot: `productList.tip1`
- Cac text dung chung trong product thi dua vao `productCommon` (`confirm`, `cancel`, `enable`, `disable`...).

## 5) Checklist truoc khi ket thuc
- [ ] Khong con text giao dien hard-code trong file da xu ly.
- [ ] Khong pha logic/form validation/message warning.
- [ ] Co du key o ca 4 ngon ngu (khong de thieu key mot ngon ngu).
- [ ] Chuyen doi ngon ngu khong hien key raw (`message.xxx.yyy`).

## 6) Loi da gap va cach tranh
- Quen i18n trong file JS config/table-head:
  - Cach tranh: tim cac file `defaultData.js`, constants, rules.
- Lech namespace giua Vue va lang files:
  - Cach tranh: copy key tu code dang dung, paste vao lang files roi dien translation.
- Bo sot text trong dialog/toast:
  - Cach tranh: quet them `this.$message`, `$modalSure`, `title`, `placeholder`.

## 7) Scope mo rong lien quan menu/router (neu co)
- Neu text menu tu backend tra ve la key route, xu ly fallback:
  1. Neu `this.$te(menuName)` thi `this.$t(menuName)`.
  2. Neu khong, thu `message.router.${menuName}`.
  3. Neu khong co nua thi tra ve nguyen ban.
- Pattern nay da dung cho `systemMenus` de hien thi ten menu chuan i18n.
