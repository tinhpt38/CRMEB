// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

export function authLapse(data) {
  return new Promise((resolve, reject) => {
    const h = this.$createElement;
    this.$notify.warning({
      title: data.title,
      duration: 3000,
      message: h('div', [
        h(
          'a',
          {
            attrs: {
              href: 'http://www.crmeb.com',
              target: '_blank',
            },
          },
          data.info,
        ),
      ]),
    });
  });
}
