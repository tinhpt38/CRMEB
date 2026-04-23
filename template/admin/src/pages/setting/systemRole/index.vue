<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mb-16" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="formValidate"
          :model="formValidate"
          :label-width="labelWidth"
          :label-position="labelPosition"
          @submit.native.prevent
          inline
        >
          <el-form-item label="tình trạng：" label-for="status">
            <el-select
              v-model="formValidate.status"
              placeholder="Vui lòng chọn"
              @change="userSearchs"
              clearable
              class="form_content_width"
            >
              <el-option value="1" label="trình diễn"></el-option>
              <el-option value="0" label="Không hiển thị"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Biệt danh nhận dạng：" label-for="role_name">
            <el-input
              clearable
              placeholder="Vui lòng nhập biệt hiệu của bạn"
              v-model="formValidate.role_name"
              class="form_content_width"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="userSearchs">Truy vấn</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" v-loading="spinShow">
      <el-button v-auth="['setting-system_role-add']" type="primary" v-db-click @click="add('Thêm vào')"
        >Thêm danh tính</el-button
      >
      <el-table
        :data="tableList"
        ref="table"
        class="mt14"
        v-loading="loading"
        highlight-current-row
        no-userFrom-text="Chưa có dữ liệu"
        no-filtered-userFrom-text="Chưa có kết quả lọc nào"
      >
        <el-table-column label="ID" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Biệt danh nhận dạng" min-width="180" show-overflow-tooltip>
          <template slot-scope="scope">
            <span>{{ scope.row.role_name }}</span>
          </template>
        </el-table-column>
        <!-- <el-table-column label="Quyền" min-width="1000">
          <template slot-scope="scope">
            <span class="line1">{{ scope.row.rules }}</span>
          </template>
        </el-table-column> -->
        <el-table-column label="tình trạng" min-width="120">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.status"
              :value="scope.row.status"
              @change="onchangeIsShow(scope.row)"
              size="large"
              active-text="trình diễn"
              inactive-text="trốn"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column label="vận hành" fixed="right" width="150">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row, 'biên tập')">biên tập</a>
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
    <!-- Thêm trình soạn thảo mới-->
    <el-dialog
      :visible.sync="modals"
      :title="`${modelTit}danh tính`"
      :close-on-click-modal="false"
      :show-close="true"
      width="540px"
      @closed="closed"
    >
      <el-form
        ref="formInline"
        :model="formInline"
        :rules="ruleValidate"
        label-width="100px"
        :label-position="labelPosition2"
        @submit.native.prevent
      >
        <el-form-item label="Tên nhận dạng：" label-for="role_name" prop="role_name">
          <el-input placeholder="Vui lòng nhập biệt hiệu của bạn" v-model="formInline.role_name" />
        </el-form-item>
        <el-form-item label="Có nên bật không：" prop="status">
          <el-radio-group v-model="formInline.status">
            <el-radio :label="1">bật lên</el-radio>
            <el-radio :label="0">đóng cửa</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="Quyền：">
          <div class="trees-coadd">
            <div class="scollhide">
              <div class="iconlist">
                <el-tree
                  :data="menusList"
                  node-key="id"
                  check-strictly
                  show-checkbox
                  highlight-current
                  ref="tree"
                  :default-checked-keys="selectIds"
                  :props="defaultProps"
                  @check="clickDeal"
                  :default-expand-all="defaultExpandAll"
                ></el-tree>
              </div>
            </div>
            <span class="iconlist-btn" @click="changeExpandAll">{{ defaultExpandAll ? 'nếp gấp' : 'Mở rộng' }}</span>
          </div>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button v-db-click @click="onCancel">Hủy bỏ</el-button>
        <el-button type="primary" v-db-click @click="handleSubmit('formInline')">nộp</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>
import { mapState } from 'vuex';
import { roleListApi, roleSetStatusApi, menusListApi, roleCreateApi, roleInfoApi } from '@/api/setting';
export default {
  name: 'systemrRole',
  data() {
    return {
      spinShow: false,
      modals: false,
      total: 0,
      grid: {
        xl: 7,
        lg: 7,
        md: 12,
        sm: 24,
        xs: 24,
      },
      loading: false,
      defaultExpandAll: false,
      formValidate: {
        status: '',
        role_name: '',
        page: 1,
        limit: 20,
      },
      tableList: [],
      formInline: {
        role_name: '',
        status: 0,
        checked_menus: [],
        id: 0,
      },
      menusList: [],
      selectIds: [],
      modelTit: '',
      ruleValidate: {
        role_name: [{ required: true, message: 'Vui lòng nhập biệt hiệu của bạn', trigger: 'blur' }],
        status: [{ required: true, type: 'number', message: 'Vui lòng chọn có bật hay không', trigger: 'change' }],
        // checked_menus: [
        //     { required: true, validator: validateStatus, trigger: 'change' }
        // ]
      },
      defaultProps: {
        children: 'children',
        label: 'title',
      },
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : 'auto';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
    labelPosition2() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  created() {
    this.getList();
  },
  methods: {
    changeExpandAll() {
      // Nút điều khiển mất tiêu điểm sau khi nhấp vào
      if (this.defaultExpandAll) {
        this.defaultExpandAll = false;
        for (let key in this.$refs.tree.store.nodesMap) {
          this.$refs.tree.store.nodesMap[key].expanded = false;
        }
      } else {
        this.defaultExpandAll = true;
        for (let key in this.$refs.tree.store.nodesMap) {
          this.$refs.tree.store.nodesMap[key].expanded = true;
        }
      }
    },
    closed() {
      this.formInline = {
        role_name: '',
        status: 0,
        checked_menus: [],
        id: 0,
      };
      this.selectIds = [];
    },
    // Thêm vào
    add(name) {
      this.formInline.id = 0;
      this.modelTit = name;
      this.modals = true;
      this.getmenusList();
    },
    // xóa bỏ
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `setting/role/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.tableList.splice(num, 1);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // Sửa đổi xem có hiển thị hay không
    onchangeIsShow(row) {
      let data = {
        id: row.id,
        status: row.status,
      };
      roleSetStatusApi(data)
        .then(async (res) => {
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // danh sách
    getList() {
      this.loading = true;
      this.formValidate.status = this.formValidate.status || '';
      roleListApi(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tableList = data.list;
          this.total = res.data.count;
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
    // biên tập
    edit(row, name) {
      this.modelTit = name;
      this.formInline.id = row.id;
      this.modals = true;
      this.rows = row;
      this.getIofo(row);
    },
    // Danh sách thực đơn
    getmenusList() {
      this.spinShow = true;
      menusListApi()
        .then(async (res) => {
          let data = res.data;
          this.menusList = data.menus;
          this.menusList.map((item, index) => {
            if (item.title === 'Trang chủ') {
              // item.checked = true;
              // item.disableCheckbox = true;
              if (item.children.length) {
                item.children.map((v) => {
                  // v.checked = true;
                  // v.disableCheckbox = true;
                });
              }
            }
            item.expand = false;
          });
          this.spinShow = false;
        })
        .catch((res) => {
          this.spinShow = false;
          this.$message.error(res.msg);
        });
    },
    // Chi tiết
    getIofo(row) {
      this.spinShow = true;
      roleInfoApi(row.id)
        .then(async (res) => {
          let data = res.data;
          this.formInline = data.role || this.formInline;
          this.formInline.checked_menus = this.formInline.rules;
          this.$nextTick((e) => {
            this.selectIds = this.formInline.rules.split(',');
            this.tidyRes(data.menus);
            // this.$refs.tree.setCheckedKeys(Array(arr));
          });
          this.spinShow = false;
        })
        .catch((res) => {
          this.spinShow = false;
          this.$message.error(res.msg);
        });
    },
    forChildrenChecked(arr, status, pid) {
      if (arr.length) {
        let len = arr.length;
        for (var j = 0; j < len; j++) {
          var childNode = this.$refs.tree.getNode(arr[j].id).data;
          if (status) {
            this.$refs.tree.setChecked(childNode.id, true);
            childNode.checked = true;
          }
          if (!status) {
            this.$refs.tree.setChecked(childNode.id, false);
            childNode.checked = false;
          }
          if (childNode.children.length) {
            this.forChildrenChecked(childNode.children, status);
          }
        }
      }
    },

    clickDeal(currentObj, treeStatus, ccc) {
      // Được sử dụng cho: Khi nút cha và nút con hoàn toàn không liên quan đến nhau, khi dấu kiểm của nút cha thay đổi, nút con sẽ được thông báo về sự thay đổi được đồng bộ hóa để đạt được liên kết một chiều.。
      let selected = treeStatus.checkedKeys.indexOf(currentObj.id); // -1Không được chọn
      // chọn
      if (selected !== -1) {
        // Nút con được chọn miễn là nút cha được chọn.
        this.selectedParent(currentObj);
        // Thống nhất việc xử lý các nút con vào cùng trạng thái được kiểm tra
        this.uniteChildSame(currentObj, true);
      } else {
        // Tất cả các nút con đang xử lý đều không được chọn
        if (currentObj.children.length !== 0) {
          this.uniteChildSame(currentObj, false);
        }
        let selParent = false;
        let parentNode = currentObj.pid ? this.$refs.tree.getNode(currentObj.pid).data : undefined;
        if (parentNode && parentNode.children.length) {
          for (let i = 0; i < parentNode.children.length; i++) {
            if (treeStatus.checkedKeys.includes(parentNode.children[i].id)) {
              selParent = true;
            }
          }
        }
        if (!selParent && currentObj.pid) this.$refs.tree.setChecked(currentObj.pid, false);
      }
    },
    // Thống nhất việc xử lý các nút con vào cùng trạng thái được kiểm tra
    uniteChildSame(treeList, isSelected) {
      this.$refs.tree.setChecked(treeList.id, isSelected);
      for (let i = 0; i < treeList.children.length; i++) {
        this.uniteChildSame(treeList.children[i], isSelected);
      }
    },
    // Xử lý thống nhất các nút cha như đã chọn
    selectedParent(currentObj) {
      let currentNode = this.$refs.tree.getNode(currentObj);
      if (currentNode.parent.key !== undefined) {
        this.$refs.tree.setChecked(currentNode.parent, true);
        this.selectedParent(currentNode.parent);
      }
    },
    tidyRes(menus) {
      let data = [];
      menus.map((menu) => {
        if (menu.title === 'Trang chủ') {
          menu.checked = true;
          // menu.disabled = true;
          if (menu.children.length) {
            menu.children.map((v) => {
              v.checked = true;
            });
          }
          data.push(menu);
        } else {
          data.push(this.initMenu(menu));
        }
      });
      this.$set(this, 'menusList', data);
    },
    initMenu(menu) {
      let data = {},
        checkMenus = ',' + this.formInline.checked_menus + ',';
      data.title = menu.title;
      data.id = menu.id;
      data.pid = menu.pid;
      data.children = menu.children;
      data.checked = menu.checked;

      if (menu.children && menu.children.length > 0) {
        data.children = [];
        menu.children.map((child) => {
          data.children.push(this.initMenu(child));
        });
      } else {
        data.checked = checkMenus.indexOf(String(',' + data.id + ',')) !== -1;
        data.expand = !data.checked;
      }
      return data;
    },
    // nộp
    handleSubmit(name) {
      this.$refs[name].validate((valid) => {
        if (valid) {
          this.formInline.checked_menus = [
            ...this.$refs.tree.getCheckedKeys(),
            ...this.$refs.tree.getHalfCheckedKeys(),
          ];
          if (this.formInline.checked_menus.length === 0) return this.$message.warning('Vui lòng chọn ít nhất một quyền');
          roleCreateApi(this.formInline)
            .then(async (res) => {
              this.$message.success(res.msg);
              this.modals = false;
              this.getList();
              this.$refs[name].resetFields();
              this.formInline.checked_menus = [];
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
        } else {
          return false;
        }
      });
    },
    onCancel() {
      this.$refs['formInline'].resetFields();
      this.formInline.checked_menus = [];
      this.selectIds = [];
      this.modals = false;
    },
  },
};
</script>

<style scoped lang="scss">
.trees-coadd {
  width: 100%;
  height: 385px;
  display: flex;
  .scollhide {
    position: relative;
    width: 100%;
    height: 100%;
    margin-top: 4px;
    overflow-y: scroll;
  }
  .iconlist-btn {
    white-space: nowrap;
    cursor: pointer;
    color: var(--prev-color-primary);
  }
}
// margin-left: 18px;
.scollhide::-webkit-scrollbar {
  display: none;
}
</style>
