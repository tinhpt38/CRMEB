import { Local } from '@/utils/storage.js';

/**
 * Xác định xem khóa đến có tồn tại trong mảng mảng không
 * @param {string} key - Sợi dây được đánh giá
 * @returns {boolean} - Trả về giá trị Boolean cho biết có được phép hay không
 */
function checkArray(key) {
  // seckill flash sale mặc cả mặc cả kết hợp nhóm chiến đấu
  let arr = Local.get('PERMISSIONS') || ['seckill', 'bargain', 'combination']; // Xác định một mảng chứa ba loại
  let index = arr.indexOf(key); // Lấy chỉ mục của khóa trong mảng
  if (index > -1) {
    // Nếu chỉ số lớn hơn -1 thì khóa tồn tại trong mảng
    return true; // Có thẩm quyền
  } else {
    return false; // Không có sự cho phép
  }
}

/**
 * @description Lệnh Vue dùng để điều khiển hiển thị và ẩn các thành phần
 * @param {Object} el - Phần tử DOM mà lệnh được ràng buộc
 * @param {Object} binding - Đối tượng mà lệnh bị ràng buộc
 */
const permission = {
  inserted: function (el, binding) {
    let permission = binding.value; // Nhận giá trị của quyền v
    if (permission) {
      let hasPermission = checkArray(permission); // Gọi hàm checkArray để xác định xem có quyền hay không
      if (!hasPermission) {
        // Không có quyền xóa phần tử Dom
        el.parentNode && el.parentNode.removeChild(el);
      }
    }
  },
};

export default permission;
