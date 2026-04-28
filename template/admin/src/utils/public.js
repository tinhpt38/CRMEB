// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

import { tableDelApi } from '@/api/common';
export function modalSure(delfromData) {
  return new Promise((resolve, reject) => {
    let content = `<p>Chắc chắn${delfromData.title}?？</p>`;
    if (!delfromData.info) {
      delfromData.info = '';
    }
    const h = this.$createElement;
    this.$msgbox({
      title: 'gợi ý',
      message: h('p', null, [h('div', null, `chắc chắn${delfromData.title}?？`), h('div', null, `${delfromData.info}`)]),
      showCancelButton: true,
      cancelButtonText: 'Hủy bỏ',
      confirmButtonText: 'Chắc chắn',
      iconClass: 'el-icon-warning',
      confirmButtonClass: 'btn-custom-cancel',
    })
      .then(() => {
        if (delfromData.success) {
          delfromData.success
            .then(async (res) => {
              resolve(res);
            })
            .catch((res) => {
              reject(res);
            });
        } else {
          tableDelApi(delfromData)
            .then(async (res) => {
              resolve(res);
            })
            .catch((res) => {
              reject(res);
            });
        }
      })
      .catch(() => {});
  });
}

export function HandlePrice(num, type) {
  let obj = [];
  if (typeof num == 'number') {
    obj = num.toString().split('.');
  } else {
    obj = num.split('.');
  }
  if (type) {
    if (obj.length && obj[1]) {
      return '.' + obj[1];
    } else {
      return '';
    }
  } else {
    return obj[0];
  }
}
