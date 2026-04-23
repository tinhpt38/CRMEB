<template>
  <div class="monaco-container">
    <div ref="container" class="monaco-editor"></div>
  </div>
</template>

<script>
import * as monaco from 'monaco-editor';
export default {
  name: '',
  props: {
    // Nội dung được hiển thị trong trình chỉnh sửa
    codes: {
      type: String,
      default: function () {
        return '';
      },
    },
    readOnly: {
      type: Boolean,
      default: function () {
        return false;
      },
    },
    // Cấu hình chính
    editorOptions: {
      type: Object,
      default: function () {
        return {
          selectOnLineNumbers: true,
          roundedSelection: false,
          readOnly: this.readOnly, // chỉ đọc
          cursorStyle: 'line', // Kiểu con trỏ
          automaticLayout: false, // tự động thanh toán
          glyphMargin: true, // cạnh glyph
          useTabStops: false,
          fontSize: 28, // cỡ chữ
          autoIndent: true, // tự động thanh toán
        };
      },
    },
  },

  data() {
    return {};
  },
  created() {},
  mounted() {
    this.monacoEditor = monaco.editor.create(this.$refs.container, {
      value: this.codes, // Nhìn thấyprops
      language: 'json',
      theme: 'vs', // Chủ đề biên tập：vs, hc-black, or vs-dark，Để có thêm lựa chọn, hãy xem trang web chính thức
      automaticLayout: true, //tự động thanh toán
      //   foldingStrategy: 'indentation', // Mã có thể được gấp lại thành các phần nhỏ
      scrollbar: {
        // Cài đặt thanh cuộn
        verticalScrollbarSize: 4, // thanh cuộn dọc
        horizontalScrollbarSize: 10, // thanh cuộn ngang
      },
      lineNumbersMinChars: 5,
      editorOptions: this.editorOptions, // như nhaucodes
    });
    setTimeout(() => {
      this.monacoEditor.trigger('anyString', 'editor.action.formatDocument');
      this.monacoEditor.setValue(this.monacoEditor.getValue());
    }, 100);
  },
  methods: {},
};
</script>
<style lang="scss" scoped>
.monaco-editor {
  min-height: 300px;
}
</style>
