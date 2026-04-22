# PLAYBOOK I18N - admin/user (cung cach lam voi product)

## 1) Muc tieu
- Chuyen toan bo text hard-code trong module `template/admin/src/pages/user/**` sang i18n.
- Dam bao UI giu nguyen y nghia khi doi ngon ngu (`zh-cn`, `zh-tw`, `en`, `vi`).

## 2) Pattern ap dung cho admin/user
0. QUY UOC BAT BUOC (THONG NHAT 1 KIEU):
   - Toan bo code i18n trong project nay DUNG key co prefix `message.`.
   - VI DU DUNG: `this.$t('message.userGroup.addGroup')`, `{{ $t('message.userList.search') }}`.
   - KHONG DUNG key root-level nhu: `userGroup.addGroup`, `userList.search`.
   - Ly do: he thong i18n hien tai goc du lieu nam trong namespace `message`.
1. Dat key theo namespace theo tung man/chuc nang:
   - `message.userList.*`
   - `message.userLabel.*`
   - `message.userGroup.*`
   - `message.userLevel.*`
   - `message.userGrade.*`
   - `message.userCancel.*`
   - `message.userCommon.*` (text dung chung)
2. Trong template Vue:
   - Text thuong: `{{ $t('message.userList.search') }}`
   - Label co dau `:`: `:label="$t('message.userList.nickname') + '：'"`
3. Trong script Vue:
   - Toast/confirm/warning: `this.$t('message.userList.selectUserFirst')`
4. Trong file JS khong co `this`:
   - `import { i18n } from '@/i18n/index.js'`
   - `const t = (key) => i18n.t(key)`
   - Dung `t('message.userLevel.xxx')` cho constants/rules/options.

## 3) Trinh tu lam i18n cho user
1. Quet hard-code theo tung man trong `user/**`:
   - `list/**`
   - `label/index.vue`
   - `group/index.vue`
   - `level/**`
   - `grade/**`
   - `cancel/**`
2. Gom key theo context: search/filter/button/table/dialog/form/validate.
3. Them day du key vao 4 file ngon ngu:
   - `template/admin/src/i18n/lang/zh-cn.js`
   - `template/admin/src/i18n/lang/zh-tw.js`
   - `template/admin/src/i18n/lang/en.js`
   - `template/admin/src/i18n/lang/vi.js`
4. Thay text trong Vue/JS bang `$t(...)` hoac `i18n.t(...)`.
5. Verify nhanh UI voi 4 language.

## 4) Quy tac dat key
- Khong dat key chung chung nhu `title1`, `tip1`.
- Uu tien key theo nghiep vu:
  - Tot: `userList.batchSetLabel`
  - Khong tot: `userList.text1`
- Text dung chung dua vao `userCommon` (`confirm`, `cancel`, `save`, `enable`, `disable`...).
- Bat buoc khi goi key trong code:
  - Dung: `message.userList.batchSetLabel`
  - Khong dung: `userList.batchSetLabel`

## 5) Checklist truoc khi ket thuc
- [ ] Khong con text hard-code trong cac file user da xu ly.
- [ ] Khong anh huong logic form/validate/toast/confirm.
- [ ] Du key cho ca 4 ngon ngu, khong thieu namespace.
- [ ] Chuyen language khong hien key raw (`message.userXxx.yyy`).
- [ ] Khong con key root-level trong code (`userXxx.yyy`), tat ca da theo `message.userXxx.yyy`.
- [ ] Bat buoc i18n DAY DU cac thanh phan UI cua module:
  - Dialog: `title`, `button`, `empty text`.
  - Form/Input: `label`, `placeholder`, option label.
  - Message: `$message`, `$modalSure`, warning/error/success text.
  - Table/List: column title, action text, tabs/filter text.
- [ ] Kiem tra nhanh bang search:
  - Tim pattern sai: `\$t\('user[A-Za-z]+\.`
  - Ket qua mong muon: 0 match.

## 6) Loi thuong gap va cach tranh
- Quen i18n trong component con (dialog/table expand/form item):
  - Cach tranh: quet them `user/list/handle/**`, `tableExpand.vue`, `task.vue`.
- Quen i18n message trong methods:
  - Cach tranh: tim `this.$message`, `$modalSure`, `title`, `placeholder`, `rules`.
- Lech namespace giua code va lang files:
  - Cach tranh: chot namespace truoc, copy key tu code sang lang files de dien ban dich.

## 7) Ket qua mong muon (module user)
- Tat ca text UI trong `template/admin/src/pages/user/**` duoc doi sang i18n key.
- Co day du translation cho `zh-cn`, `zh-tw`, `en`, `vi`.
- Khong thay doi nghia/nghiep vu hien tai cua man user khi switch language.
- Khong con tinh trang hien key raw do lech namespace (`userGroup.addGroup`).
