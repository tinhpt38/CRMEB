import E from 'wangeditor'; // npm Cài đặt
// const E = window.wangEditor // CDN Phương pháp giới thiệu
import util from '../../utils/bus';

// Lấy các biến cần thiết sẽ được sử dụng bên dưới
const { $, BtnMenu, DropListMenu, PanelMenu, DropList, Panel, Tooltip } = E;
var _this = null;
export default class AlertMenu extends BtnMenu {
  constructor(editor) {
    _this = editor;
    // data-titleThuộc tính cho biết mô tả chức năng ngắn gọn của nút khi chuột di chuột qua nút.
    const $elem = E.$(
      `<div class="w-e-menu" data-title="băng hình">
                <div class="iconfont iconshipin"></div>
            </div>`,
    );
    super($elem, editor);
  }
  // Sự kiện bấm vào menu
  clickHandler() {
    util.$emit('Video');
    // getvideoint()
  }
  tryChangeActive() {
    this.active();
  }
}
