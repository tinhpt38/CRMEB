// +---------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +---------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +---------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +---------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +---------------------------------------------------------------------

/**
 * @description Xác định xem danh sách 1 có chứa một mục trong danh sách 2 không
 * Vì quyền truy cập của người dùng là một mảng nên phương thức include không thể trực tiếp đưa ra kết luận.
 * */
function includeArray(list1, list2) {
  let status = false;
  if (list1 === true) {
    return true;
  } else {
    if (typeof list2 !== 'object') {
      return false;
    }
    list2.forEach((item) => {
      if (list1.includes(item)) status = true;
    });
    return status;
  }
}
export { includeArray };
