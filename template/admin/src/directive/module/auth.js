// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2021 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
/**
 * @description Hướng dẫn xác thực
 * Khi người dùng hiện tại không được cấp quyền, thành phần sẽ bị xóa
 * Các trường hợp sử dụng：<Tag v-auth="['admin']">text</Tag>
 * */
import store from '@/store';
import { includeArray } from '@/libs/auth';

export default {
  inserted(el, binding, vnode) {
    const { value } = binding;
    const access = store.state.userInfo.access;
    if (value && value instanceof Array && value.length && access && access.length) {
      const isPermission = includeArray(value, access);
      if (!isPermission) {
        // el.parentNode && el.parentNode.removeChild(el);
      }
    }
  },
};
