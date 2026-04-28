<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt" v-loading="spinShow">
      <div v-if="isShowList" class="backs-box">
        <div class="backs">
          <span class="back" v-db-click @click="goBack(false)">
            <i class="el-icon-back icon" />
          </span>
          <span class="item" v-for="(item, index) in routeList" :key="index" v-db-click @click="jumpRoute(item)">
            <span class="key">{{ item.key }}</span>
            <i class="forward el-icon-arrow-right" v-if="index < routeList.length - 1" />
          </span>
        </div>
        <span class="refresh" v-db-click @click="refreshRoute">
          <i class="el-icon-refresh-right icon" />
        </span>
      </div>
      <el-table
        v-if="isShowList"
        ref="selection"
        :data="tabList"
        v-loading="loading"
        empty-text="Chưa có dữ liệu"
        class="mt14"
      >
        <el-table-column label="Tên tập tin/thư mục" min-width="150">
          <template slot-scope="scope">
            <div class="file-name" v-db-click @click="currentChange(scope.row)">
              <i v-if="scope.row.isDir" class="el-icon-folder mr5" />
              <i v-else class="el-icon-document mr5" />
              <span>{{ scope.row.filename }}</span>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Kích thước tập tin/thư mục" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.size }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Thời gian cập nhật" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.mtime }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Nhận xét" min-width="120">
          <template slot-scope="scope">
            <div class="mark">
              <div v-if="scope.row.is_edit" class="table-mark" v-db-click @click="isEditMark(scope.row)">
                {{ scope.row.mark }}
              </div>
              <el-input ref="mark" v-else v-model="scope.row.mark" @blur="isEditBlur(scope.row)"></el-input>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Thao tác" fixed="right" width="60">
          <template slot-scope="scope">
            <el-button type="text" v-db-click @click="open(scope.row)" v-if="scope.row.isDir">Mở</el-button>
            <el-button type="text" v-db-click @click="edit(scope.row)" v-else>Chỉnh sửa</el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-card>
    <el-dialog
      :visible.sync="modals"
      :custom-class="className"
      :close-on-click-modal="false"
      width="80%"
      top="5vh"
      @close="editModalChange"
      append-to-body
      :title="EditorIndex[indexEditor].title"
    >
      <p slot="header" class="diy-header" ref="diyHeader">
        <span>{{ title }}</span>
        <i
          v-db-click
          @click="winChanges"
          class="diy-header-icon"
          :class="className ? 'el-icon-cpu' : 'el-icon-full-screen'"
          style="font-size: 20px"
        />
      </p>
      <div style="height: 100%">
        <div class="top-button">
          <el-button type="primary" id="savefile" class="diy-button" v-db-click @click="savefile(indexEditor)"
            >Lưu</el-button
          >
          <el-button id="refresh" class="diy-button" v-db-click @click="refreshfile">Làm cho khỏe lại</el-button>
        </div>
        <div class="file-box">
          <div class="show-info">
            <div class="show-text" :title="NavItem.pathname">Mục lục: {{ navItem.pathname }}</div>
            <div class="diy-button-list">
              <el-button class="diy-button" v-db-click @click="goBack(true)">Trở về cấp độ trước đó</el-button>
              <el-button class="diy-button" v-db-click @click="getList(true, true)">Làm cho khỏe lại</el-button>
            </div>
          </div>
          <div class="file-left">
            <el-tree
              class="diy-tree-render"
              :data="navList"
              :render-content="renderContent"
              :load="loadData"
              @node-contextmenu="handleContextMenu"
              expand-node
              lazy
              :props="props"
            >
              <!-- <template transfer slot="contextMenu">
                <DropdownItem v-if="contextData && contextData.isDir" v-db-click @click.native="handleContextCreateFolder()"
                  >Tạo thư mục mới</DropdownItem
                >
                <DropdownItem v-if="contextData && contextData.isDir" v-db-click @click.native="handleContextCreateFile()"
                  >Tạo tập tin mới</DropdownItem
                >
                <DropdownItem v-db-click @click.native="handleContextRename()">Đổi tên</DropdownItem>
                <DropdownItem v-db-click @click.native="handleContextDelFolder()" style="color: #ed4014">Xóa</DropdownItem>
              </template> -->
            </el-tree>
          </div>
          <div class="file-fix"></div>
          <div class="file-content">
            <el-tabs
              type="card"
              v-model="indexEditor"
              style="height: 100%"
              @tab-click="toggleEditor"
              :animated="false"
              closable
              @tab-remove="handleTabRemove"
            >
              <el-tab-pane
                v-for="value in editorIndex"
                :key="value.index"
                :name="value.index.toString()"
                :label="value.title"
                :icon="value.icon"
                v-if="value.tab"
              >
                <div
                  ref="container"
                  :id="'container_' + value.index"
                  style="height: 100%; min-height: calc(80vh - 100px)"
                ></div>
              </el-tab-pane>
            </el-tabs>
          </div>
        </div>
      </div>
    </el-dialog>

    <div v-show="formShow" class="diy-from">
      <div class="diy-from-header">
        {{ formTitle
        }}<span :title="ContextData ? contextData.pathname : ''">{{ contextData ? contextData.pathname : '' }}</span>
      </div>
      <el-form ref="formInline" :model="formFile" :rules="ruleInline" inline>
        <el-form-item prop="filename" class="diy-file">
          <el-input type="text" class="diy-file" v-model="formFile.filename" placeholder="Vui lòng nhập tên">
            <i class="el-icon-folder-opened" slot="prepend"></i>
          </el-input>
        </el-form-item>
        <el-form-item>
          <el-button class="diy-button" v-db-click @click="handleSubmit('formInline')">Chắc chắn</el-button>
        </el-form-item>
        <el-form-item>
          <el-button class="diy-button" v-db-click @click="formExit()">Hủy bỏ</el-button>
        </el-form-item>
        <div class="form-mask" v-show="formShow"></div>
      </el-form>
    </div>
  </div>
</template>

<script>
import { resolveComponent } from 'vue';
import {
  opendirListApi,
  openfileApi,
  savefileApi,
  opendirLoginApi,
  createFolder,
  createFile,
  delFolder,
  rename,
  fileMark,
  markSave,
} from '@/api/system';
import CodeMirror from 'codemirror/lib/codemirror';
import loginFrom from './components/loginFrom';
import { setCookies, getCookies, removeCookies } from '@/libs/util';
// import Fullscreen from '@/layout/components/fullscreen';
import * as monaco from 'monaco-editor';
export default {
  name: 'opendir',
  data() {
    return {
      modals: false, //Công tắc soạn thảo
      editor: '', //đối tượng soạn thảo hiện tại
      editorIndex: [
        //mảng tab
        {
          tab: true,
          index: '0',
          title: '',
          icon: '',
        },
      ],
      editorList: [], //Mảng soạn thảo
      indexEditor: 0, //Chỉ mục biên tập hiện tại
      code: '', //Nội dung của tập tin hiện tại khi nó được mở
      navList: [], //Dữ liệu điều hướng bên trái
      navItem: {}, //Bấm vào điều hướng bên trái để chọn dữ liệu
      contextData: null, //Nhấp chuột phải vào điều hướng bên trái là đối tượng dữ liệu được tạo

      fileType: '', // Kiểu thao tác tập tin createFolder|Tạo thư mục createFile|Tạo tập tin delFolder|Xóa thư mục hoặc tập tin
      className: '', //Tên lớp toàn màn hình
      // fullscreen:false,  // Có để toàn màn hình không
      isSave: true, //Tệp hiện tại có được lưu hay không

      isShowLogn: false, // Đăng nhập
      isShowList: false, // Danh sách sau khi đăng nhập

      spinShow: false,
      loading: false,
      tabList: [],

      formItem: {
        //Ghi lại thông tin đường dẫn hiện tại và sử dụng nó khi lấy danh sách tập tin
        dir: '',
        superior: 0,
        filedir: '',
        fileToken: getCookies('file_token'),
      },
      dir: '', //Đường dẫn tập tin đầy đủ hiện tại
      // rows: {},  //
      pathname: '', // đường dẫn tập tin hiện tại
      title: '', //Tiêu đề tập tin hiện tại

      formFile: {
        //Đổi tên biểu mẫu
        filename: '',
      },
      ruleInline: {
        filename: [{ required: true, message: 'Vui lòng nhập tên file hoặc thư mục', trigger: 'blur' }],
      },
      formShow: false, //chuyển đổi hình thức
      formTitle: '', //tiêu đề biểu mẫu
      fileToken: getCookies('file_token'),
      routeList: [], //  mở đường dẫn tập tin
      props: {
        label: 'title',
        children: 'children',
        isLeaf: 'isLeaf',
      },
    };
  },

  components: {
    loginFrom,
  },
  mounted() {
    // this.initEditor();
  },
  created() {
    this.getList();
  },
  beforeDestroy() {
    removeCookies('file_token');
  },
  computed: {},
  methods: {
    // Bấm vào hàng
    currentChange(currentRow) {
      if (currentRow.isDir) {
        this.open(currentRow);
      } else {
        this.edit(currentRow);
      }
    },
    /**
     * danh sách tập tin
     * @param {Object} refresh   // Có tải lại không bool
     * @param {Object} is_edit   // Đây có phải là một bản làm mới trong trình soạn thảo? bool
     */
    getList(refresh, is_edit) {
      let params;
      if (refresh) {
        params = {
          dir: '',
          superior: 0,
          filedir: '',
          fileToken: this.fileToken,
        };
      } else {
        params = this.formItem;
        params.fileToken = this.fileToken;
      }
      if (!is_edit) this.loading = true;
      opendirListApi(params)
        .then(async (res) => {
          let data = res.data;
          this.routeList = data.routeList;

          if (is_edit) {
            this.navList = data.navList;
          } else {
            this.navListForTab = data.navList;
            this.tabList = data.list;
            // this.navList = data.navList;
            this.isShowList = true;
          }
          this.dir = data.dir;
          this.isShowLogn = false;
          this.loading = false;
        })
        .catch((res) => {
          this.catchFun(res);
        });
    },
    //Tải lại điều hướng bên trái sau khi tạo tệp mới
    getListItem(data) {
      opendirListApi(data)
        .then(async (res) => {
          this.$set(this.contextData, 'children', res.data.navList);
        })
        .catch((res) => {
          this.catchFun(res);
        });
    },

    // Trở về cấp độ trước đó
    goBack(is_edit) {
      this.formItem = {
        dir: this.dir,
        superior: 1,
        filedir: '',
      };
      this.getList(false, is_edit);
    },
    // Mở
    open(row) {
      // this.rows = row;
      this.formItem = {
        dir: row.path,
        superior: 0,
        filedir: row.filename,
        fileToken: this.fileToken,
      };
      this.getList(false, false);
    },
    jumpRoute(item) {
      let data = {
        path: item.route,
        filename: '',
      };
      this.open(data);
    },
    refreshRoute() {
      let data = {
        path: this.routeList[this.routeList.length - 1].route,
        filename: '',
      };
      this.open(data);
    },
    // biên tậpß
    edit(row) {
      this.navItem = row;
      this.spinShow = true;
      this.pathname = row.pathname;
      this.title = row.filename;
      this.editorIndex[0].title = row.filename;
      this.editorIndex[0].pathname = row.pathname;
      this.navList = this.navListForTab;
      this.dir = row.path;
      // Tạo vùng chứa mã
      if (this.editorList.length <= 0) {
        // this.initEditor();
      }
      this.openfile(row.pathname, false);
    },
    /**
     * Nhận xét
     */
    mark(row) {
      this.$modalForm(
        fileMark({
          path: row.pathname,
          fileToken: this.fileToken,
        }),
      ).then(() => This.getList(true, false));
    },
    /**
     * cứu
     * @param {Object} index   // chỉ số hiện tại
     * @param {Object} type    // true Không cập nhật dữ liệu cục bộ hiện tại, sai hoặc trống, cập nhật dữ liệu hiện tại
     */
    savefile(index, type) {
      let code = this.editorList[index].editor.getValue();
      let data = {
        comment: code,
        filepath: this.editorList[index].path,
        fileToken: this.fileToken,
      };
      let that = this;
      savefileApi(data)
        .then(async (res) => {
          if (!type) {
            that.code = code;
            that.isSave = true;
            that.editorIndex[index].icon = '';
            that.editorList[index].isSave = true;
          }
          that.$message.success(res.msg);
          that.$Modal.remove();
        })
        .catch((res) => {
          that.catchFun(res);
        });
    },
    // làm cho khỏe lại
    refreshfile() {
      // Làm mới trình chỉnh sửa
      if (this.editorList[this.indexEditor]) this.openfile(this.editorList[this.indexEditor].path, true);
    },
    //Tính thời gian hết hạn của mã thông báo
    getExpiresTime(expiresTime) {
      let nowTimeNum = Math.round(new Date() / 1000);
      let expiresTimeNum = expiresTime - nowTimeNum;
      return parseFloat(parseFloat(parseFloat(expiresTimeNum / 60) / 60) / 24);
    },
    // Thanh bên tải không đồng bộ
    loadData(item, callback) {
      if (!item.data.isLeaf) {
        this.formItem = {
          dir: item.data.path,
          superior: 0,
          filedir: item.data.title,
          fileToken: this.fileToken,
        };
        opendirListApi(this.formItem)
          .then(async (res) => {
            callback(res.data.navList);
          })
          .catch((res) => {
            if (res.status == 110008) {
              this.$message.error(res.msg);
              this.isShowLogn = true;
              this.isShowList = false;
              this.loading = false;
            } else {
              this.catchFun(res);
            }
          });
      }
    },
    // Hiển thị tùy chỉnh
    renderContent(h, { node, data, root }) {
      let that = this;
      return h(
        'span',
        {
          style: {
            display: 'inline-block',
            cursor: 'pointer',
            userSelect: 'null',
            color: '#cccccc',
            display: 'inline-block',
            width: '100%',
            borderRadis: '5px',
          },
          on: {
            click: () => {
              that.clickDir(data, root, node);
            },
            contextmenu: () => {
              // that.handleContextDelFolder(data,root,node);
            },
          },
        },
        [
          h('span', [
            h('Icon', {
              props: {
                type: !data.isLeaf ? 'md-folder' : 'ios-document-outline',
              },
              style: {
                marginRight: '8px',
              },
            }),
            h(
              'span',
              {
                attrs: {
                  title: data.title,
                },
              },
              data.title,
            ),
          ]),
        ],
      );
    },
    /**
     * Sự kiện nhấp vào thanh bên
     * @param {Object} data
     */
    clickDir(data, root, node) {
      let that = this;
      that.navItem = data;
      that.pathname = data.pathname;

      if (!data.isDir) {
        let i = that.editorIndex.findIndex((e) => {
          return e.pathname === data.pathname;
        });
        if (i > -1) {
          that.indexEditor = i.toString();
          that.toggleEditor();
        } else {
          let index = that.editorIndex.length;
          // tạo nêntabs
          that.editorIndex.push({
            tab: true,
            index: index.toString(),
            title: data.title,
            icon: '',
            pathname: data.pathname,
          });
          that.indexEditor = index.toString();
          // Tạo vùng chứa mã
          that.initEditor();
          that.openfile(data.pathname, true);
        }
      }
    },
    //Sự kiện nhấp chuột phải vào thanh bên
    handleContextMenu(data, event, position) {
      position.left = Number(position.left.slice(0, -2)) + 75 + 'px';
      this.contextData = data;
    },
    // Kiểu thao tác tập tin createFolder|Tạo thư mục createFile|Tạo tập tin delFolder|Xóa thư mục hoặc tập tin renameFile|Đổi tên tập tin
    //Tạo thư mục
    handleContextCreateFolder() {
      this.formFile.filename = '';
      this.formTitle = 'Tạo thư mục';
      this.formShow = true;
      this.fileType = 'createFolder';
    },
    //Tạo tập tin
    handleContextCreateFile() {
      this.formFile.filename = '';
      this.formTitle = 'Tạo tập tin';
      this.formShow = true;
      this.fileType = 'createFile';
    },
    //Xóa tập tin
    handleContextDelFolder() {
      let that = this;
      that.$Modal.confirm({
        title: 'Xóa thư mục và tập tin',
        content: 'Bạn có chắc chắn muốn xóa tập tin này không?？',
        loading: true,
        onOk: () => {
          let data = {
            path: that.contextData.pathname,
            fileToken: this.fileToken,
          };
          delFolder(data)
            .then(async (res) => {
              that.loopDel(that.navList, that.contextData.nodeKey);
              that.$Modal.remove();
              that.$message.success('Xóa thành công');
            })
            .catch((res) => {
              that.catchFun(res);
            });
        },
        onCancel: () => {
          that.$message.info('Phục hồi');
        },
      });
    },
    //Đổi tên
    handleContextRename() {
      this.formFile.filename = this.contextData.title;
      this.formTitle = 'Đổi tên tập tin';
      this.formShow = true;
      this.fileType = 'renameFile';
    },
    //mở tập tin
    openfile(path, is_edit) {
      let that = this;
      let params = {
        filepath: path,
        fileToken: this.fileToken,
      };

      openfileApi(params)
        .then(async (res) => {
          if (!is_edit) {
            that.modals = true;
            that.spinShow = false;
            this.initEditor();
          }
          let data = res.data;
          that.code = data.content;
          // Lưu thông tin tương đối

          that.editorList[that.indexEditor].oldCode = that.code;
          this.$nextTick((e) => {
            that.editorList[that.indexEditor || 0].path = path;
            that.editorList[that.indexEditor || 0].pathname = path;
          });
          //thay đổi thuộc tính
          that.changeModel(data.mode, that.code);
        })
        .catch((res) => {
          that.catchFun(res);
        });
    },
    /**
     * Khởi tạo trình soạn thảo
     */
    initEditor() {
      let that = this;
      that.$nextTick(() => {
        // Khởi tạo trình chỉnh sửa và đảm bảo rằng dom đã được hiển thị
        that.editor = monaco.editor.create(document.getElementById('container_' + that.indexEditor), {
          value: that.code, //Văn bản hiển thị ban đầu của trình soạn thảo
          language: 'sql', //Ngôn ngữ hỗ trợ tự kiểm trademo
          automaticLayout: true, //tự động thanh toán
          theme: 'vs', //Chính thức đi kèm với ba chủ đềvs, hc-black, or vs-dark
          foldingStrategy: 'indentation', // Mã có thể được gấp lại thành các phần nhỏ
          overviewRulerBorder: false, // Không có đường viền thanh cuộn
          scrollbar: {
            // Cài đặt thanh cuộn
            verticalScrollbarSize: 4, // thanh cuộn dọc
            horizontalScrollbarSize: 10, // thanh cuộn ngang
          },
          autoIndent: true, // tự động thanh toán
          tabSize: 4, // tabchiều dài thụt lề
          autoClosingOvertype: 'always',
        });
        //Thêm giám sát khóa
        that.editor.addCommand(monaco.KeyMod.CtrlCmd | monaco.KeyCode.KEY_S, function () {
          that.savefile(that.indexEditor);
        });
        that.editor.onKeyUp(() => {
          // Khi nhấn bàn phím, hãy xác định xem văn bản soạn thảo hiện tại có nhất quán với văn bản soạn thảo đã lưu hay không.
          if (that.editor.getValue() != that.code) {
            that.isSave = false;
            that.editorIndex[that.indexEditor].icon = 'md-warning';
            that.editorList[that.indexEditor].isSave = false;
          }
        });
        that.editorList.push({
          editor: that.editor,
          oldCode: that.code,
          path: this.pathname,
          isSave: true,
          index: that.indexEditor,
        });
      });
    },
    /**
     * chuyển đổi ngôn ngữ
     * @param {Object} mode
     */
    changeModel(mode, value) {
      var oldModel = this.editorList[this.indexEditor].editor.getModel(); //Lấy mẫu cũ
      // var value = this.editor.getValue();//Nhận văn bản cũ
      //Tạo một mô hình mới, giá trị là văn bản cũ và id là modeId, là ngôn ngữ (ngôn ngữ.id)
      //modesIds là ngôn ngữ được hỗ trợ
      // var modesIds = monaco.languages.getLanguages().map(function(lang) { return lang.id; });
      if (!mode) mode = oldModel.getLanguageId();
      // if(!value) value = this.editor.getValue();

      var newModel = monaco.editor.createModel(value, mode);
      //Phá hủy mô hình cũ
      if (oldModel) {
        oldModel.dispose();
      }
      //Thiết lập mô hình mới
      this.editorList[this.indexEditor].editor.setModel(newModel);
    },
    // Kiểu thao tác tập tin createFolder|Tạo thư mục createFile|Tạo tập tin delFolder|Xóa thư mục hoặc tập tin
    handleSubmit(name) {
      let that = this;
      let data = '';
      let dataItem = '';
      this.$refs[name].validate((valid) => {
        if (valid) {
          switch (that.fileType) {
            case 'createFolder':
              data = {
                path: that.contextData.pathname,
                name: that.formFile.filename,
                fileToken: this.fileToken,
              };
              createFolder(data)
                .then(async (res) => {
                  dataItem = {
                    dir: that.contextData.path,
                    superior: 0,
                    filedir: that.contextData.title,
                    fileToken: this.fileToken,
                  };
                  that.getListItem(dataItem);
                  if (that.formShow) that.formShow = false;
                  that.$message.success('Đã tạo thành công');
                })
                .catch((res) => {
                  that.catchFun(res);
                });
              break;
            case 'createFile':
              data = {
                path: that.contextData.pathname,
                name: that.formFile.filename,
                fileToken: this.fileToken,
              };
              createFile(data)
                .then(async (res) => {
                  dataItem = {
                    dir: that.contextData.path,
                    superior: 0,
                    filedir: that.contextData.title,
                    fileToken: this.fileToken,
                  };
                  that.getListItem(dataItem);
                  if (that.formShow) that.formShow = false;
                  that.$message.success('Đã tạo thành công');
                })
                .catch((res) => {
                  that.catchFun(res);
                });
              break;
            case 'renameFile':
              data = {
                newname: that.contextData.path + '\\' + that.formFile.filename,
                oldname: that.contextData.pathname,
                fileToken: this.fileToken,
              };
              rename(data)
                .then(async (res) => {
                  that.$set(that.contextData, 'title', that.formFile.filename);
                  that.$message.success('Sửa đổi thành công');
                  if (that.formShow) that.formShow = false;
                })
                .catch((res) => {
                  that.catchFun(res);
                });
              break;
          }
        } else {
          this.$message.error('Fail!');
        }
      });
    },
    /**
     * Biểu mẫu thoát
     */
    formExit() {
      this.formShow = false;
    },

    /**
     * Xử lý các cuộc gọi lại giao diện
     * @param {Object} res
     */
    catchFun(res) {
      if (res.status) {
        if (res.status == 400) this.$message.error(res.msg);
        if (res.status == 110008) {
          // this.$message.error(res.msg);
          this.isShowLogn = true;
          this.isShowList = false;
          this.loading = false;
        }
      } else {
        // this.$message.error('Mã hóa tệp không tương thích và không thể đọc tệp chính xác!');
      }
      //Tắt lớp mặt nạ
      if (this.spinShow) this.spinShow = false;
      // Đóng hiển thị danh sách tập tin
      if (this.loading) this.loading = false;
    },
    loopDel(data, nodeKey) {
      data.forEach((item, index) => {
        if (item.nodeKey === nodeKey) {
          return data.splice(index, 1);
        }
        if (item.children.length > 0) {
          return this.loopDel(item.children, nodeKey);
        }
      });
    },
    /**
     * Phóng to cửa sổ
     */
    winChanges() {
      if (this.className) {
        this.className = '';
      } else {
        this.className = 'diy-fullscreen';
      }
    },
    /**
     * Chuyển tab
     * @param {Object} index
     */
    toggleEditor() {
      let index = Number(this.indexEditor);
      this.code = this.editorList[index].oldCode; //Đặt mã khi mở tệp
      this.editor = this.editorList[index].editor; //Thiết lập phiên bản soạn thảo
    },
    isEditMark(row) {
      try {
        row.is_edit = true;
        this.$nextTick((e) => {
          this.$refs.mark.focus();
        });
      } catch (error) {
        console.log(error);
      }
    },
    isEditBlur(row) {
      row.is_edit = false;
      let data = {
        full_path: row.real_path,
        mark: row.mark,
      };
      markSave(this.fileToken, data)
        .then((res) => {
          // this.$message.success(res.msg);
        })
        .catch((err) => {
          this.$message.error(err.msg);
        });
    },
    handleTabRemove(index) {
      let that = this;

      // Đóng tab
      that.editorIndex[index].tab = false; // Đóng tab
      // Xác định xem file hiện tại đã được lưu chưa
      if (!that.editorList[index].isSave) {
        that.$Modal.confirm({
          title: 'Tệp chưa được lưu',
          content: 'Bạn có cần lưu tập tin hiện tại không',
          loading: true,
          onOk: () => {
            // lưu tập tin
            that.savefile(index);
          },
          onCancel: () => {
            that.$message.info('Hủy lưu');
          },
        });
      }
    },
    //Thay đổi trạng thái trình chỉnh sửa
    editModalChange() {
      let that = this;
      that.editorList.forEach(function (value, index) {
        if (value.isSave === false) {
          if (confirm(`${that.editorIndex[index].title}Tệp chưa được lưu,Bạn có muốn lưu tập tin này?`)) {
            // Lưu tập tin hiện tại
            that.savefile(index, true);
          } else {
            that.$message.info(`Đã hủy${that.editorIndex[index].title}Lưu tập tin`);
          }
        }
        // Phá hủy trình soạn thảo hiện tại
        that.editorList[index].editor.dispose();
        that.editorList[index].editor = null;
      });
      // dữ liệu cuộc gọi đầu tiên
      that.modals = false; //Công tắc soạn thảo
      that.editor = ''; //đối tượng soạn thảo hiện tại
      that.editorIndex = [
        //mảng tab
        {
          tab: true,
          index: '0',
          title: '',
          icon: '',
        },
      ];
      that.editorList = []; //Mảng soạn thảo
      that.indexEditor = '0'; //Chỉ mục biên tập hiện tại
      that.code = ''; //Nội dung của tập tin hiện tại khi nó được mở
      that.navList = []; //Dữ liệu điều hướng bên trái
      that.navItem = {}; //Bấm vào điều hướng bên trái để chọn dữ liệu
      that.contextData = null; //Nhấp chuột phải vào điều hướng bên trái là đối tượng dữ liệu được tạo
    },
  },
};
</script>
<style scoped>
.file-left ::v-deep .ivu-tree-title {
  font-weight: 500;
  font-family: "Google Sans", "Product Sans", sans-serif;
}
.file-content ::v-deep .ivu-tabs.ivu-tabs-card > .ivu-tabs-bar .ivu-tabs-tab-active {
  border-bottom: 1px solid orange;
}
</style>
<style lang="scss" scoped>
.file-left {
  padding-left: 10px;
  color: #cccccc;
}
.mr5 {
  margin-right: 5px;
}
.backs-box {
  display: flex;
  justify-content: space-between;
  min-width: 800px;
  max-width: max-content;
  border: 1px solid #cfcfcf;
  background: #f6f6f6;
  .refresh {
    background: #fff;
    border-left: 1px solid #cfcfcf;
    padding: 0 8px 0 10px;
    font-size: 16px;
    font-weight: bold;
  }
  .refresh {
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
  }
  .refresh:hover,
  .back:hover {
    background: #2d8cf0;
    border-color: #38983b;
    color: #fff;
  }
}
.file-name {
  cursor: pointer;
}
.backs {
  cursor: pointer;
  display: inline-block;
  display: flex;
  align-items: center;
  width: 100%;
  .back {
    height: 100%;
    background: #fff;
    border-right: 1px solid #cfcfcf;
    padding: 6px 8px 0 10px;
    font-size: 16px;
    font-weight: bold;
  }
  .item:last-child {
    padding-right: 5px !important;
  }
  .item {
    padding: 0 0 0 8px;
    font-size: 12px;
    line-height: 33px;
    color: #555;
    display: flex;
    align-items: center;
    .key {
      margin-right: 3px;
    }
  }
  .item:hover {
    background: #fff;
  }
}
::v-deep .CodeMirror {
  height: 70vh !important;
}
.file-box {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  position: relative;
  min-height: calc(100% - 35px);
  overflow: hidden;
}
.file-box {
  .file-left {
    position: absolute;
    top: 58px;
    left: 0;
    height: calc(100% - 58px);

    width: 25%;
    max-width: 250px;
    overflow: auto;
    background-color: #292929;
  }
  .file-fix {
    flex: 1;
    max-width: 250px;
    min-height: calc(100% - 35px);

    min-height: calc(100% - 35px);
    background-color: #292929;
  }
}
.file-box {
  .file-content {
    flex: 3;
    overflow: hidden;
    min-height: calc(100% - 35px);
    height: 100%;
  }
}
::v-deep .el-dialog__body {
  padding: 0 !important;
  height: 80vh;
  max-height: 80vh;
}
.diy-button {
  height: 35px;
  padding: 0 15px;
  font-size: 13px;
  text-align: center;
  color: #fff;
  border: 0;
  border-right: 1px solid #4c4c4c;
  cursor: pointer;
  border-radius: 0;
  background-color: #565656;
}
.form-mask {
  z-index: -1;
  width: 100%;
  height: 100%;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  margin: auto;
  background: rgba(0, 0, 0, 0.3);
}
.table-mark {
  cursor: text;
}
.table-mark:hover {
  border: 1px solid #c2c2c2;
  padding: 3px 5px;
}
.mark ::v-deep .el-input__inner {
  background: #fff;
  border-radius: 0.39rem;
}
.mark ::v-deep .el-input__inner,
.el-input__inner:hover,
.el-input__inner:focus {
  border: transparent;
  box-shadow: none;
}
.diy-from-header {
  height: 30px;
  line-height: 30px;
  background-color: #fff;
  text-align: left;
  padding-left: 20px;
  font-size: 16px;
  margin-bottom: 15px;

  span {
    display: inline-block;
    float: right;
    color: #999;
    text-align: right;
    font-size: 12px;
    width: 280px;
    word-break: keep-all; /* Không ngắt dòng */
    white-space: nowrap; /* Không ngắt dòng */
    overflow: hidden;
    text-overflow: ellipsis;
  }
}
.diy-from {
  z-index: 9999;
  width: 400px;
  height: 100px;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  margin: auto;
  text-align: center;
  background-color: #2f2f2f;
}
.top-button {
  background-color: #292929;
}
.show-info {
  background-color: #292929;
  color: #fff;
  width: 25%;
  max-width: 250px;
  position: absolute;
  top: 0;
  left: 0;
  z-index: 1122;
  .diy-button {
    width: 50%;
    height: 25px;
    line-height: 8px;
  }
  .diy-button-list {
    display: flex;
    align-items: center;
  }
  .show-text {
    padding-left: 10px;
    word-break: keep-all; /* Không ngắt dòng */
    white-space: nowrap; /* Không ngắt dòng */
    overflow: hidden;
    text-overflow: ellipsis;
    padding: 7px 5px;
  }
}

body ::v-deep .ivu-select-dropdown {
  background: #fff;
}
::v-deep .el-tabs__item {
  background-color: #fff;
}
::v-deep .el-tree {
  background-color: #292929 !important;
}
.file-box {
  .file-left::-webkit-scrollbar {
    width: 4px;
  }
}
.file-box {
  .file-left::-webkit-scrollbar-thumb {
    border-radius: 10px;
    -webkit-box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
    background: rgba(255, 255, 255, 0.2);
  }
}
.file-box {
  .file-left::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
    border-radius: 0;
    background: rgba(0, 0, 0, 0.1);
  }
}
.diy-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  .diy-header-icon {
    margin-right: 30px;
    cursor: pointer;
  }
  .diy-header-icon:hover {
    opacity: 0.8;
  }
}
::v-deep .diy-fullscreen {
  overflow: hidden;
  .ivu-modal {
    top: 0px;
    left: 0px;
    right: 0px;
    bottom: 0px;
    height: 100%;
    width: 100% !important;
    .ivu-modal-content {
      height: 100%;
      .ivu-modal-body {
        height: 100%;
      }
    }
    .ivu-tabs {
      .ivu-tabs-content-animated {
        height: 92%;
        background-color: #2f2f2f !important;
      }
    }
    .ivu-tabs-content {
      height: 100%;
    }
    .ivu-tabs {
      .ivu-tabs-tabpane {
        height: 92%;
      }
    }
  }
}
::v-deep .ivu-modal {
  top: 70px;
}
.ivu-modal-content {
  .ivu-modal-body {
    min-height: 632px;
    height: 80vh;
    overflow: hidden;
  }
}
.ivu-tabs {
  .ivu-tabs-content-animated {
    min-height: 580px;
    height: 73vh;
    margin-top: -1px;
  }
  .ivu-tabs-tabpane {
    min-height: 580px;
    height: 73vh;
    margin-top: -1px;
  }
}
.ivu-tabs-nav .ivu-tabs-tab .ivu-icon {
  color: #f00;
}
::v-deepbody .ivu-select-dropdown .ivu-dropdown-transfer {
  background: red !important;
}
.file-left ::v-deep .ivu-select-dropdown.ivu-dropdown-transfer .ivu-dropdown-menu .ivu-dropdown-item:hover {
  background-color: #e5e5e5 !important;
}
::v-deep .ivu-tabs.ivu-tabs-card > .ivu-tabs-bar .ivu-tabs-nav-container {
  background-color: #333;
}
</style>
