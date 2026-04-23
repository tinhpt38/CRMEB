// +---------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +---------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +---------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +---------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +---------------------------------------------------------------------

import {
  Confirm as confirm,
  Alert as alert,
  Toast as toast,
  Notify as notify,
  Loading as loading,
} from 'vue-ydui/dist/lib.rem/dialog';

const dialog = {
  confirm,
  alert,
  toast,
  notify,
  loading,
};

const icons = { error: 'Thao tác không thành công', success: 'Hoạt động thành công' };
Object.keys(icons).reduce((dialog, key) => {
  dialog[key] = (mes, obj = {}) => {
    return new Promise(function (resolve) {
      toast({
        mes: mes || icons[key],
        timeout: 1000,
        icon: key,
        callback: () => {
          resolve();
        },
        ...obj,
      });
    });
  };
  return dialog;
}, dialog);

dialog.message = (mes = 'Thao tác không thành công', obj = {}) => {
  return new Promise(function (resolve) {
    toast({
      mes,
      timeout: 1000,
      callback: () => {
        resolve();
      },
      ...obj,
    });
  });
};

dialog.validateError = (...args) => {
  validatorDefaultCatch(...args);
};

export function validatorDefaultCatch(err, type = 'message') {
  return dialog[type](err.errors[0].message);
}

export default dialog;
