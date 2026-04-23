<template>
  <el-dialog :visible.sync="modals_son" :title="title" :close-on-click-modal="false" width="900px">
    <el-button type="primary" id="savefile" class="mr5 mb15" v-db-click @click="savefile">cứu</el-button>
    <el-button id="undo" class="mr5 mb15" v-db-click @click="undofile">Hủy bỏ</el-button>
    <el-button id="redo" class="mr5 mb15" v-db-click @click="redofile">quay lại</el-button>
    <el-button id="refresh" class="mb15" v-db-click @click="refreshfile">làm cho khỏe lại</el-button>
    <textarea ref="mycode" class="codesql public_text" v-model="code" style="height: 80vh"></textarea>
  </el-dialog>
</template>

<script>
import { opendirLoginApi } from '@/api/system';
import CodeMirror from 'codemirror/lib/codemirror';
import 'codemirror/theme/ambiance.css';
import { setCookies, getCookies, removeCookies } from '@/libs/util';

// Phong cách cốt lõi
// import 'codemirror/lib/codemirror.css'
// Sau khi giới thiệu theme, bạn cần chỉ định theme trong tùy chọn để nó có hiệu lực.
import 'codemirror/theme/cobalt.css';

// Cần phải giới thiệu một thư viện tô sáng cú pháp cụ thể để có tác dụng tô sáng cú pháp tương ứng.
// codemirror chính thức hỗ trợ tải động thư viện tô sáng cú pháp tương ứng thông qua /addon/mode/loadmode.js và /mode/meta.js
// Nhưng vue dường như không thể tải động JS tương ứng sau khi phiên bản được khởi tạo, vì vậy JS tương ứng được giới thiệu trước ở đây.
// import 'codemirror/mode/javascript/javascript.js'
// import 'codemirror/mode/css/css.js'
// import 'codemirror/mode/xml/xml.js'
// import 'codemirror/mode/clike/clike.js'
// import 'codemirror/mode/markdown/markdown.js'
// import 'codemirror/mode/python/python.js'
// import 'codemirror/mode/r/r.js'
// import 'codemirror/mode/shell/shell.js'
// import 'codemirror/mode/sql/sql.js'
// import 'codemirror/mode/swift/swift.js'
// import 'codemirror/mode/vue/vue.js'

require('codemirror/mode/javascript/javascript');
export default {
  name: 'opendir',
  props: {
    rows: {
      type: Object,
      default: {},
    },
    code: {
      type: String,
      default: ' ',
    },
    modals: {
      type: Boolean,
      default: false,
    },
    title: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      editor: '',
      isShowLogn: false, // Đăng nhập
      isShowList: false, // Danh sách sau khi đăng nhập
      spinShow: false,
      loading: false,

      formItem: {
        dir: '',
        superior: 0,
        filedir: '',
      },
      pathname: '',
      modals_son: this.modals,
      fileToken: getCookies('file_token'),
    };
  },
  watch: {
    code: {
      handler(newValue, oldValue) {
        this.editor.setValue(newValue);
      },
      deep: true, // Giá trị mặc định là sai, cho biết có giám sát sâu hay không
    },
    modals: {
      handler(newValue, oldValue) {
        this.modals_son = newValue;
      },
      deep: true, // Giá trị mặc định là sai, cho biết có giám sát sâu hay không
    },
  },
  mounted() {
    this.editor = CodeMirror.fromTextArea(this.$refs.mycode, {
      value: 'http://www.crmeb.com', // Văn bản được hiển thị theo mặc định trong trường văn bản
      mode: 'text/javascript',
      theme: 'ambiance', // CSSLựa chọn phong cách
      indentUnit: 8, // Đơn vị thụt lề, mặc định2
      smartIndent: true, // Có nên sử dụng thụt lề thông minh hay không
      tabSize: 4, // Tabthụt lề, mặc định4
      readOnly: false, // Có chỉ đọc hay không, mặc địnhfalse
      showCursorWhenSelecting: true,
      lineNumbers: true, // Có hiển thị số dòng hay không

      indentWithTabs: true,
      matchBrackets: true,
      extraKeys: {
        Ctrl: 'autocomplete',
      }, //Phím tắt tùy chỉnh
    });
    //Chức năng nhắc nhở tự động về mã, hãy nhớ sử dụng sự kiện CursorActivity chứ không phải sự kiện thay đổi. Đây là một cạm bẫy và trang sẽ trực tiếp bị đóng băng.
    editor.on('cursorActivity', function () {
      editor.showHint();
    });
  },
  created() {
    // this.getList();
    this.onIsLogin();
  },
  methods: {
    // cứu
    savefile() {
      let data = {
        comment: this.editor.getValue(),
        filepath: this.pathname,
        fileToken: this.fileToken,
      };
      savefileApi(data)
        .then(async (res) => {
          this.$message.success(res.msg);
          this.modals = false;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // Hủy bỏ
    undofile() {
      this.editor.undo();
    },
    redofile() {
      this.editor.redo();
    },
    // làm cho khỏe lại
    refreshfile() {
      this.editor.refresh();
    },
  },
};
</script>
<style lang="scss" scoped>
::v-deep .CodeMirror {
  height: 70vh !important;
}
</style>
