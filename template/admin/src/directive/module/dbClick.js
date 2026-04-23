/**
 * @description Lệnh Vue dùng để điều khiển hiển thị và ẩn các thành phần
 * @param {Object} el - Phần tử DOM mà lệnh được ràng buộc
 * @param {Object} binding - Đối tượng mà lệnh bị ràng buộc
 */
const dbClick = {
  inserted(el, binding) {
    el.addEventListener('click', (e) => {
      if (!el.disabled) {
        el.disabled = true;
        el.style.cursor = 'not-allowed';
        setTimeout(() => {
          el.style.cursor = 'pointer';
          el.disabled = false;
        }, 1000);
      }
    });
  },
};

export default dbClick;
