<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt" v-loading="spinShow">
      <el-button type="primary" v-db-click @click="groupAdd()" class="mr20">Thêm chức năng</el-button>
      <!-- <el-button type="success" v-db-click @click="buildCode()" class="mr20">đăng lại</el-button> -->
      <el-table
        :data="tabList"
        ref="table"
        class="mt14"
        v-loading="loading"
        highlight-current-row
        no-userFrom-text="Chưa có dữ liệu"
        no-filtered-userFrom-text="Chưa có kết quả lọc nào"
      >
        <el-table-column label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Tên thực đơn" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="tên bảng" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.table_name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Nhận xét bảng" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.table_comment }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Thêm thời gian" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column label="vận hành" fixed="right" width="200">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row, 'biên tập')">Xem mã</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="editItem(scope.row)">biên tập</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="downLoad(scope.row)">tải về</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, 'xóa bỏ', scope.$index)">xóa bỏ</a>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="formValidate.page"
          :limit.sync="formValidate.limit"
          @pagination="getList"
        />
      </div>
    </el-card>
    <el-drawer
      :visible.sync="modals"
      :custom-class="className"
      title="Create"
      size="80%"
      :wrapperClosable="false"
      :styles="styles"
      @closed="editModalChange"
    >
      <p slot="header" class="diy-header" ref="diyHeader">
        <span>{{ title }}</span>
      </p>
      <div class="file" style="height: 100%">
        <el-button class="save" type="primary" v-db-click @click="pwdModal = true">cứu</el-button>

        <div class="file-box">
          <div class="file-fix"></div>
          <div class="file-content">
            <!-- <el-tabs
              type="card"
              v-model="indexEditor"
              style="height: 100%"
              @on-click="toggleEditor"
              :animated="false"
              closable
              @on-tab-remove="handleTabRemove"
            >
              <el-tab-pane
                v-for="value in editorIndex"
                :key="value.index"
                :name="value.index.toString()"
                :label="value.title"
                :icon="value.icon"
              >
                <div
                  ref="container"
                  :id="'container_' + value.index"
                  style="height: 100%; min-height: calc(100vh - 110px)"
                ></div>
              </el-tab-pane>
            </el-tabs> -->
            <el-tabs v-model="indexEditor" type="card" @tab-click="toggleEditor">
              <el-tab-pane v-for="value in editorIndex" :key="value.index">
                <span slot="label">
                  <el-tooltip effect="light" class="item" :content="value.title" placement="top">
                    <span>{{ value.file_name }}</span>
                  </el-tooltip>
                </span>
                <div
                  ref="container"
                  :id="'container_' + value.index"
                  style="height: 100%; min-height: calc(100vh - 110px)"
                ></div>
              </el-tab-pane>
              <!-- <el-tab-pane label="Quản lý người dùng" name="first">Quản lý người dùng</el-tab-pane>
              <el-tab-pane label="Quản lý cấu hình" name="second">Quản lý cấu hình</el-tab-pane>
              <el-tab-pane label="quản lý vai trò" name="third">quản lý vai trò</el-tab-pane>
              <el-tab-pane label="Bồi thường nhiệm vụ theo lịch trình" name="fourth">Bồi thường nhiệm vụ theo lịch trình</el-tab-pane> -->
            </el-tabs>
          </div>
        </div>
      </div>
    </el-drawer>
    <el-dialog
      :visible.sync="buildModals"
      title="phần cuối"
      :show-close="true"
      :close-on-click-modal="false"
      width="720px"
      @close="editModalChange"
    >
      <el-alert type="warning" title="Thiết bị đầu cuối hiện tại không chạy trong dịch vụ cài đặt và một số lệnh có thể không được thực thi.."></el-alert>
      <div>
        <div v-for="(item, index) in codeBuildList" :key="index">{{ item }}</div>
      </div>
    </el-dialog>
    <el-dialog
      :visible.sync="pwdModal"
      width="470px"
      title="Mật khẩu quản lý tập tin"
      :show-close="true"
      :close-on-click-modal="false"
    >
      <el-input v-model="pwd" type="password" placeholder="Vui lòng nhập mật khẩu quản lý tập tin"></el-input>
      <span slot="footer" class="dialog-footer">
        <el-button v-db-click @click="pwdModal = false">Hủy bỏ</el-button>
        <el-button type="primary" v-db-click @click="crudSaveFile">Chắc chắn</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { crudList, crudDet, crudDownload, crudSaveFile } from '@/api/systemCodeGeneration';
import * as monaco from 'monaco-editor';
import { getCookies, removeCookies } from '@/libs/util';
import Setting from '@/setting';
export default {
  data() {
    return {
      grid: {
        xl: 7,
        lg: 7,
        md: 12,
        sm: 24,
        xs: 24,
      },
      formValidate: {
        page: 1,
        limit: 20,
        title: '',
      },
      styles: {
        height: 'calc(100% - 55px)',
        overflow: 'auto',
        paddingBottom: '53px',
        position: 'static',
      },
      loading: false,
      pwdModal: false,
      buildModals: false,
      pwd: '',
      tabList: [],
      codeBuildList: [],
      total: 0,
      columns1: [
        {
          title: 'ID',
          key: 'id',
          width: 80,
        },
        {
          title: 'Tên thực đơn',
          key: 'name',
          minWidth: 130,
        },
        {
          title: 'tên bảng',
          key: 'table_name',
          minWidth: 130,
        },
        {
          title: 'bộ ký tự',
          key: 'table_collation',
          minWidth: 130,
        },
        {
          title: 'Nhận xét bảng',
          key: 'table_comment',
          minWidth: 130,
        },
        {
          title: 'Thêm thời gian',
          key: 'add_time',
          minWidth: 130,
        },
        {
          title: 'vận hành',
          slot: 'action',
          fixed: 'right',
          minWidth: 150,
        },
      ],
      FromData: null,
      titleFrom: '',
      groupId: 0,
      addId: '',
      editorList: [], //Mảng soạn thảo
      indexEditor: 0, //Chỉ mục biên tập hiện tại
      code: '', //Nội dung của tập tin hiện tại khi nó được mở
      contextData: null, //Nhấp chuột phải vào điều hướng bên trái là đối tượng dữ liệu được tạo

      fileType: '', // Kiểu thao tác tập tin createFolder|Tạo thư mục createFile|Tạo tập tin delFolder|Xóa thư mục hoặc tập tin
      className: '', //Tên lớp toàn màn hình
      spinShow: false,
      modals: false, //Công tắc soạn thảo
      editor: '', //đối tượng soạn thảo hiện tại
      editorIndex: [],
      title: '',
      editId: 0,
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '80px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  mounted() {
    this.getList();
  },
  beforeDestroy() {
    if (this.source) {
      this.source.close(); //đóng cửaEventSource
    }
  },
  methods: {
    crudSaveFile() {
      let data = {
        filepath: this.editorIndex[this.indexEditor].pathname,
        comment: this.editorList[this.indexEditor].editor.getValue(),
        pwd: this.pwd,
      };
      crudSaveFile(this.editId, data)
        .then((res) => {
          this.pwd = '';
          this.$message.success(res.msg);
        })
        .catch((err) => {
          this.$message.error(err.msg);
        });
    },
    downLoad(row) {
      crudDownload(row.id).then((res) => {
        window.open(res.data.download_url, '_blank');
      });
    },
    buildCode() {
      this.buildModals = true;
      if (typeof EventSource !== 'undefined') {
        //ủng hộeventSource
        var postURL = Setting.apiBaseURL + '/system/crud/npm?token=' + getCookies('token');
        this.source = new EventSource(postURL);
        let self = this; //Vì con trỏ của this trong EventSource đã thay đổi nên nó cần được lưu trữ trước.
        this.source.onopen = function (res) {};
        this.source.onmessage = function (data) {};
        this.source.onerror = function (err) {
          //Sau khi liên kết không thành công, EventSource sẽ khởi tạo lại liên kết sau mỗi ba giây hoặc lâu hơn.
        };
      } else {
        console.log('Chưa được hỗ trợEventSource');
      }
    },
    // Chuyển đến trang danh sách dữ liệu kết hợp
    goList(row) {
      this.$router.push({
        path: this.$routeProStr + '/system/config/system_group/list/' + row.id,
      });
    },
    // danh sách
    getList() {
      this.loading = true;
      crudList(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tabList = data.list;
          this.total = data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // tìm kiếm bảng
    userSearchs() {
      this.formValidate.page = 1;
      this.getList();
    },
    // Bấm để thêm
    groupAdd() {
      this.$router.push({
        name: 'system_code_generation',
      });
    },
    // xóa bỏ
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `system/crud/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.tabList.splice(num, 1);
          this.getList();
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // biên tập
    edit(row) {
      this.spinShow = true;
      // Tạo vùng chứa mã
      this.title = row.name;
      this.$nextTick((e) => {
        this.openfile(row.id, false);
      });
    },
    editItem(row) {
      this.$router.push({
        name: 'system_code_generation',
        query: {
          id: row.id,
        },
      });
    },
    //mở tập tin
    openfile(id) {
      try {
        this.editId = id;
        let that = this;
        this.editorIndex = [];
        this.editorList = [];
        crudDet(id)
          .then(async (res) => {
            let data = res.data.file[0];
            res.data.file.map((i, index) => {
              let data = i;
              this.editorIndex.push({
                tab: true,
                index: index + '',
                title: data.name,
                file_name: data.file_name,
                pathname: data.path,
              });
              that.code = data.content;
              this.initEditor(index, data.content);
              this.$nextTick((e) => {
                // Lưu thông tin tương đối
                that.editorList[index].path = data.path;
                that.editorList[index].oldCode = that.content;
                that.editorIndex[index].title = data.name;
                that.editorIndex[index].file_name = data.file_name;
              });
            });
            that.modals = true;
            that.spinShow = false;
          })
          .catch((res) => {
            that.catchFun(res);
          });
      } catch (error) {
        console.log(error);
      }
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
     * Khởi tạo trình soạn thảo
     */
    initEditor(index, conetnt) {
      try {
        let that = this;
        that.$nextTick(() => {
          // Khởi tạo trình chỉnh sửa và đảm bảo rằng dom đã được hiển thị
          that.editor = monaco.editor.create(document.getElementById('container_' + index), {
            value: conetnt, //Văn bản hiển thị ban đầu của trình soạn thảo
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
            readOnly: false,
          });
          that.editorList.push({
            editor: that.editor,
            oldCode: that.code,
            path: '',
            index: index,
          });
        });
      } catch (error) {
        console.log(error);
      }
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
    //Thay đổi trạng thái trình chỉnh sửa
    editModalChange() {
      let that = this;
      that.editorList.forEach(function (value, index) {
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
      that.contextData = null; //Nhấp chuột phải vào điều hướng bên trái là đối tượng dữ liệu được tạo
    },
    /**
     * Chuyển tab
     * @param {Object} index
     */
    toggleEditor(index) {
      index = Number(index);
      this.code = this.editorList[index].oldCode; //Đặt mã khi mở tệp
      this.editor = this.editorList[index].editor; //Thiết lập phiên bản soạn thảo
    },
    handleTabRemove(index) {
      let that = this;
      // Đóng tab
      that.editorIndex[index].tab = false; // Đóng tab
    },
  },
};
</script>

<style lang="scss" scoped>
// Thu hẹp phương thức tùy chỉnh
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
    min-height: 560px;
    height: 73vh;
    margin-top: -1px;
  }

  .ivu-tabs-tabpane {
    min-height: 560px;
    margin-top: -1px;
  }
}

.ivu-tabs-nav .ivu-tabs-tab .ivu-icon {
  color: #f00;
}

::v-deepbody .ivu-select-dropdown .ivu-dropdown-transfer {
  background: red !important;
}

// Kiểu nhấp chuột phải vào thanh điều hướng không hợp lệ
.file-left ::v-deep .ivu-select-dropdown.ivu-dropdown-transfer .ivu-dropdown-menu .ivu-dropdown-item:hover {
  background-color: #e5e5e5 !important;
}

// Tiêu đề tab
::v-deep .ivu-tabs.ivu-tabs-card > .ivu-tabs-bar .ivu-tabs-nav-container {
  background-color: #fff;
}
.demo-drawer-footer {
  width: 100%;
  position: absolute;
  bottom: 0;
  left: 0;
  border-top: 1px solid #e8e8e8;
  padding: 10px 16px;
  text-align: right;
  background: #fff;
}
.file {
  position: relative;
  .save {
    position: absolute;
    left: 50%;
    bottom: -10px;
    z-index: 99;
  }
}
.file-box {
  height: 100%;
}
</style>
