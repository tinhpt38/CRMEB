import directive from './directives';

const importDirective = (Vue) => {
  /**
   * Lệnh kéo và thả v-draggable="options"
   * options = {
   *  trigger: /Ở đây, bộ chọn CSS được sử dụng làm trình kích hoạt kéo được chuyển vào/,
   *  body:    /Ở đây bộ chọn CSS cần di chuyển vùng chứa được chuyển vào/,
   *  recover: /Có trở về vị trí ban đầu sau khi kéo xong hay không/
   * }
   */
  Vue.directive('draggable', directive.draggable);
  /**
   * clipboardchỉ dẫn v-draggable="options"
   * options = {
   *  value:    /Sử dụng giá trị được giới hạn bởi v-model trong hộp nhập/,
   *  success:  /Gọi lại sau khi sao chép thành công/,
   *  error:    /Gọi lại sau khi sao chép thất bại/
   * }
   */
  Vue.directive('clipboard', directive.clipboard);
  /**
   * v-auth="['string-string']"
   * */
  Vue.directive('auth', directive.auth);

  Vue.directive('permission', directive.permission);
  Vue.directive('dbClick', directive.dbClick);
};

export default importDirective;
