<template>
  <div>
    <el-card :bordered="false" shadow="never" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="formValidate"
          :model="formValidate"
          :label-width="labelWidth"
          :label-position="labelPosition"
          @submit.native.prevent
          inline
        >
          <el-form-item label="tìm kiếm：">
            <el-input
              clearable
              placeholder="Vui lòng nhập tên、UID"
              v-model="formValidate.keyword"
              class="form_content_width"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="userSearchs">Truy vấn</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt mt16">
      <el-row class="ivu-mt box-wrapper">
        <el-col :xs="24" :sm="24" ref="rightBox">
          <el-button type="primary" v-db-click @click="groupAdd('0')">Thêm đơn vị kinh doanh</el-button>
          <el-tooltip placement="right-start">
            <i class="el-icon-question ml10"></i>
            <div slot="content">
              <div>
                Mô tả cấp độ đơn vị kinh doanh: đơn vị kinh doanh-đại lý-nhân viên. Đơn vị kinh doanh tương đương với tổng đại lý hoặc đại lý khu vực. Sau khi được đặt làm đơn vị kinh doanh, những người dùng được liên kết sẽ xóa những người quảng bá cấp trên của họ.
              </div>
              <div>
                Danh tính quản trị viên khi thêm cần đặt vai trò tương ứng trong Cài đặt-Quyền quản lý-Quản lý vai trò. Bộ phận kinh doanh có thể sử dụng tài khoản quản trị viên và mật khẩu đã đặt khi thêm để đăng nhập vào phần phụ trợ.
              </div>
            </div>
          </el-tooltip>
          <el-table
            :data="userLists"
            ref="table"
            class="mt14"
            v-loading="loading"
            highlight-current-row
            no-formValidate-text="Chưa có dữ liệu"
            no-filtered-formValidate-text="Chưa có kết quả lọc nào"
          >
            <el-table-column label="người dùngUID" width="100">
              <template slot-scope="scope">
                <span>{{ scope.row.uid }}</span>
              </template>
            </el-table-column>
            <el-table-column label="hình đại diện" min-width="90">
              <template slot-scope="scope">
                <div class="tabBox_img" v-viewer>
                  <img v-lazy="scope.row.avatar" />
                </div>
              </template>
            </el-table-column>
            <el-table-column label="tên" min-width="130">
              <template slot-scope="scope">
                <div class="acea-row">
                  <div v-text="scope.row.division_name"></div>
                </div>
              </template>
            </el-table-column>
            <el-table-column label="Mã mời" min-width="130">
              <template slot-scope="scope">
                <span>{{ scope.row.division_invite }}</span>
              </template>
            </el-table-column>
            <el-table-column label="Tỷ lệ phân phối" min-width="130">
              <template slot-scope="scope">
                <span> {{ scope.row.division_percent }}%</span>
              </template>
            </el-table-column>
            <el-table-column label="Số lượng đại lý" min-width="130">
              <template slot-scope="scope">
                <span>{{ scope.row.agent_count }}</span>
              </template>
            </el-table-column>
            <el-table-column label="Thời hạn" min-width="130">
              <template slot-scope="scope">
                <span>{{ scope.row.division_end_time }}</span>
              </template>
            </el-table-column>
            <el-table-column label="tình trạng" min-width="130">
              <template slot-scope="scope">
                <el-switch
                  :active-value="1"
                  :inactive-value="0"
                  v-model="scope.row.division_status"
                  :value="scope.row.division_status"
                  @change="onchangeIsShow(scope.row)"
                  size="large"
                >
                </el-switch>
              </template>
            </el-table-column>
            <el-table-column label="vận hành" fixed="right" width="170">
              <template slot-scope="scope">
                <a v-db-click @click="jump(scope.row.uid)">Xem đại lý</a>
                <el-divider direction="vertical"></el-divider>
                <a v-db-click @click="groupAdd(scope.row.uid)">biên tập</a>
                <el-divider direction="vertical"></el-divider>
                <a v-db-click @click="del(scope.row, 'Xóa đơn vị kinh doanh', scope.$index)">xóa bỏ</a>
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
        </el-col>
      </el-row>
    </el-card>
    <el-dialog :visible.sync="staffModal" title="Danh sách đại lý" class="order_box" width="1000px">
      <el-table
        :data="clerkLists"
        ref="table"
        class="mt20"
        v-loading="loading"
        highlight-current-row
        no-formValidate-text="Chưa có dữ liệu"
        no-filtered-formValidate-text="Chưa có kết quả lọc nào"
      >
        <el-table-column label="người dùngUID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.uid }}</span>
          </template>
        </el-table-column>
        <el-table-column label="hình đại diện" min-width="90">
          <template slot-scope="scope">
            <div class="tabBox_img" v-viewer>
              <img v-lazy="scope.row.avatar" />
            </div>
          </template>
        </el-table-column>
        <el-table-column label="tên" min-width="130">
          <template slot-scope="scope">
            <div class="acea-row">
              <div v-text="scope.row.division_name" class="ml10"></div>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Tỷ lệ phân phối" min-width="130">
          <template slot-scope="scope">
            <span> {{ scope.row.division_percent }}%</span>
          </template>
        </el-table-column>
        <el-table-column label="Thời gian hết hạn" min-width="130">
          <template slot-scope="scope">
            <span> {{ scope.row.division_end_time | formatDate }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Số lượng nhân viên" min-width="130">
          <template slot-scope="scope">
            <span> {{ scope.row.agent_count }}</span>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total2"
          :total="total2"
          :page.sync="clerkReqData.page"
          :limit.sync="clerkReqData.limit"
          @pagination="getClerkList"
        />
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { regionList, regionFrom, isShowApi, clerkList } from '@/api/agent';
import { formatDate } from '@/utils/validate';
export default {
  name: 'agent_extra',
  data() {
    return {
      grid: {
        xl: 7,
        lg: 7,
        md: 12,
        sm: 24,
        xs: 24,
      },
      total: 0,
      total2: 0,
      userLists: [],
      formInline: {
        uid: 0,
        proportion: 0,
        image: '',
      },
      FromData: null,
      loading: false,
      current: 0,
      formValidate: {
        page: 1,
        limit: 15,
        keyword: '',
      },
      staffModal: false,
      clerkReqData: {
        uid: 0,
        page: 1,
        limit: 15,
      },
      clerkLists: [],
    };
  },
  filters: {
    formatDate(time) {
      if (time !== 0) {
        let date = new Date(time * 1000);
        return formatDate(date, 'yyyy-MM-dd');
      }
    },
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '50px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  mounted() {
    this.getList();
  },
  methods: {
    // tìm kiếm
    userSearchs() {
      this.formValidate.page = 1;
      this.getList();
    },
    jump(uid) {
      this.clerkReqData.uid = uid;
      this.getClerkList();
    },
    getClerkList() {
      this.clerkReqData.division_type = 2;
      clerkList(this.clerkReqData).then((res) => {
        this.clerkLists = res.data.list;
        this.total2 = res.data.count;
        this.staffModal = true;
      });
    },
    // danh sách
    getList() {
      this.loading = true;
      this.formValidate.division_type = 1;
      regionList(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.userLists = data.list;
          this.total = data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // Thêm biểu mẫu
    groupAdd(id) {
      this.$modalForm(regionFrom(id))
        .then((res) => {
          this.getList();
        })
        .catch((err) => {});
    },
    // Sửa đổi xem có hiển thị hay không
    onchangeIsShow(row) {
      let data = {
        id: row.uid,
        status: row.division_status,
      };
      isShowApi(data)
        .then(async (res) => {
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // biên tập
    edit(row) {},
    // xóa bỏ
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        method: 'DELETE',
        uid: row.uid,
        url: `agent/division/del/1/${row.uid}`,
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.userLists.splice(num, 1);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.ivu-form-item {
  margin-bottom: 0;
}
.picBox {
  display: inline-block;
  cursor: pointer;
  .upLoad {
    width: 58px;
    height: 58px;
    line-height: 58px;
    border: 1px dotted rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    background: rgba(0, 0, 0, 0.02);
  }
  .pictrue {
    width: 60px;
    height: 60px;
    border: 1px dotted rgba(0, 0, 0, 0.1);
    margin-right: 10px;

    img {
      width: 100%;
      height: 100%;
    }
  }
}
::v-deep .ivu-menu-vertical .ivu-menu-item-group-title {
  display: none;
}
::v-deep .ivu-menu-vertical.ivu-menu-light:after {
  display: none;
}
.left-wrapper {
  height: 904px;
  background: #fff;
  border-right: 1px solid #f2f2f2;
}
.menu-item {
  z-index: 50;
  position: relative;
  display: flex;
  justify-content: space-between;
  word-break: break-word;
  overflow-wrap: anywhere;
  &:hover .icon-box {
    display: block;
  }
}
.icon-box {
  z-index: 3;
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
  display: none;
}

.right-menu {
  z-index: 10;
  position: absolute;
  right: -106px;
  top: -11px;
  width: auto;
  min-width: 121px;
}
.tabBox_img {
  width: 36px;
  height: 36px;
  border-radius: 4px;
  cursor: pointer;
  img {
    width: 100%;
    height: 100%;
  }
}
</style>
