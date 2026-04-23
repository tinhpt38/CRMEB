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
          <el-form-item label="Lựa chọn thời gian：">
            <el-date-picker
              clearable
              v-model="timeVal"
              type="daterange"
              :editable="false"
              @change="onchangeTime"
              format="yyyy/MM/dd"
              value-format="yyyy/MM/dd"
              start-placeholder="ngày bắt đầu"
              end-placeholder="ngày kết thúc"
              :picker-options="pickerOptions"
              style="width: 250px"
              class="mr20"
            ></el-date-picker>
          </el-form-item>
          <el-form-item label="tìm kiếm：" label-for="status">
            <el-input
              clearable
              placeholder="Vui lòng nhập tên và số điện thoại của bạn、UID"
              v-model="formValidate.nickname"
              class="form_content_width"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="userSearchs">Truy vấn</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <cards-data :cardLists="cardLists" v-if="cardLists.length >= 0"></cards-data>
    <el-card :bordered="false" shadow="never">
      <el-button v-auth="['export-userAgent']" class="export" v-db-click @click="exports">Xuất khẩu</el-button>
      <el-table
        ref="selection"
        :data="tableList"
        class="mt14"
        v-loading="loading"
        empty-text="Chưa có dữ liệu"
        highlight-current-row
      >
        <el-table-column label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.uid }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Hình ảnh sản phẩm" min-width="90">
          <template slot-scope="scope">
            <div class="tabBox_img" v-viewer>
              <img v-lazy="scope.row.headimgurl ? scope.row.headimgurl : require('../../assets/images/moren.jpg')" />
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Thông tin người dùng" width="150">
          <template slot-scope="scope">
            <div class="name">
              <div class="item">biệt danh:{{ scope.row.nickname }}</div>
              <div class="item">Tên:{{ scope.row.real_name }}</div>
              <div class="item">Điện thoại:{{ scope.row.phone }}</div>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Cấp độ phân phối" min-width="120">
          <template slot-scope="scope">
            <div>{{ scope.row.agentLevel ? scope.row.agentLevel.name : '--' }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Số lượng người dùng được thăng cấp" min-width="120">
          <template slot-scope="scope">
            <span>{{ scope.row.spread_count }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Số lượng đặt hàng khuyến mãi" min-width="120">
          <template slot-scope="scope">
            <div>{{ scope.row.spread_order.order_count }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Số lượng đặt hàng khuyến mãi" min-width="120">
          <template slot-scope="scope">
            <div>{{ scope.row.spread_order.order_price || '0.00' }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Tổng số tiền hoa hồng" min-width="120">
          <template slot-scope="scope">
            <div>{{ scope.row.brokerage_money }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Số tiền đã rút" min-width="120">
          <template slot-scope="scope">
            <div>{{ scope.row.extract_count_price }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Số lần rút tiền" min-width="120">
          <template slot-scope="scope">
            <div>{{ scope.row.extract_count_num }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Số tiền mặt chưa rút" min-width="120">
          <template slot-scope="scope">
            <div>{{ scope.row.new_money }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Nhà quảng bá cấp cao" min-width="120">
          <template slot-scope="scope">
            <div>{{ scope.row.spread_name }}</div>
          </template>
        </el-table-column>
        <el-table-column label="vận hành" fixed="right" width="120">
          <template slot-scope="scope">
            <a v-db-click @click="promoters(scope.row, 'man')">người quảng bá</a>
            <el-divider direction="vertical"></el-divider>
            <template>
              <el-dropdown size="small" @command="changeMenu(scope.row, $event, scope.$index)" :transfer="true">
                <span class="el-dropdown-link">Hơn<i class="el-icon-arrow-down el-icon--right"></i> </span>
                <el-dropdown-menu slot="dropdown">
                  <el-dropdown-item command="1">Đơn hàng khuyến mại</el-dropdown-item>
                  <el-dropdown-item command="2">Quảng cáo mã QR</el-dropdown-item>
                  <el-dropdown-item command="3">Sửa đổi trình quảng bá ưu việt</el-dropdown-item>
                  <el-dropdown-item command="4" v-if="scope.row.spread_uid">Rõ ràng các nhà quảng bá vượt trội</el-dropdown-item>
                  <el-dropdown-item command="5">Bị loại khỏi chương trình khuyến mãi</el-dropdown-item>
                  <el-dropdown-item command="6">Sửa đổi mức phân phối</el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
            </template>
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
    <!-- Danh sách nhà quảng bá-->
    <promoters-list ref="promotersLists"></promoters-list>
    <!-- Quảng cáo mã QR-->
    <el-dialog :visible.sync="modals" title="Quảng cáo mã QR" :close-on-click-modal="false" width="540px">
      <div class="acea-row row-around" v-loading="spinShow">
        <div class="acea-row row-column-around row-between-wrapper">
          <div class="QRpic" v-if="code_src"><img v-lazy="code_src" /></div>
          <span class="QRpic_sp1 mt10" v-db-click @click="getWeChat">Mã QR khuyến mãi tài khoản công khai</span>
        </div>
        <div class="acea-row row-column-around row-between-wrapper">
          <div class="QRpic" v-if="code_xcx"><img v-lazy="code_xcx" /></div>
          <span class="QRpic_sp2 mt10" v-db-click @click="getXcx">Mã QR khuyến mãi chương trình nhỏ</span>
        </div>
        <div class="acea-row row-column-around row-between-wrapper">
          <div class="QRpic" v-if="code_h5"><img v-lazy="code_h5" /></div>
          <span class="QRpic_sp2 mt10" v-db-click @click="getH5">H5Quảng cáo mã QR</span>
        </div>
      </div>
    </el-dialog>
    <!--Sửa đổi người quảng bá-->
    <el-dialog :visible.sync="promoterShow" title="Sửa đổi người quảng bá" width="540px" :show-close="true">
      <el-form ref="formInline" :model="formInline" label-width="100px" @submit.native.prevent>
        <el-form-item label="Hình đại diện của người dùng：" prop="image">
          <div class="picBox" v-db-click @click="customer">
            <div class="pictrue" v-if="formInline.image">
              <img v-lazy="formInline.image" />
            </div>
            <div class="upLoad acea-row row-center-wrapper" v-else>
              <i class="el-icon-picture-outline" style="font-size: 24px"></i>
            </div>
          </div>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button v-db-click @click="cancel('formInline')">Hủy bỏ</el-button>
        <el-button type="primary" v-db-click @click="putSend('formInline')">nộp</el-button>
      </span>
    </el-dialog>
    <el-dialog :visible.sync="customerShow" title="Vui lòng chọn một người dùng trung tâm mua sắm" :show-close="true" width="1000px">
      <customerInfo v-if="customerShow" @imageObject="imageObject"></customerInfo>
    </el-dialog>
  </div>
</template>

<script>
import cardsData from '@/components/cards/cards';
import searchFrom from '@/components/publicSearchFrom';
import { mapState } from 'vuex';
import {
  agentListApi,
  statisticsApi,
  lookCodeApi,
  lookxcxCodeApi,
  lookh5CodeApi,
  userAgentApi,
  agentSpreadApi,
} from '@/api/agent';
import promotersList from './handle/promotersList';
import customerInfo from '@/components/customerInfo';
import { membershipDataAddApi } from '@/api/membershipLevel';
export default {
  name: 'agentManage',
  components: { cardsData, searchFrom, promotersList, customerInfo },
  data() {
    return {
      customerShow: false,
      promoterShow: false,
      modals: false,
      spinShow: false,
      pickerOptions: this.$timeOptions,
      rows: {},
      formValidate: {
        nickname: '',
        data: '',
        page: 1,
        limit: 15,
      },
      date: 'all',
      total: 0,
      cardLists: [],
      loading: false,
      tableList: [],
      timeVal: [],
      code_src: '',
      code_xcx: '',
      code_h5: '',
      formInline: {
        uid: 0,
        spread_uid: 0,
        image: '',
      },
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
  created() {
    this.getList();
    this.getStatistics();
  },
  methods: {
    // nộp
    putSend(name) {
      this.$refs[name].validate((valid) => {
        if (valid) {
          if (!this.formInline.spread_uid) {
            return this.$message.error('Vui lòng tải lên người dùng');
          }
          agentSpreadApi(this.formInline)
            .then((res) => {
              this.promoterShow = false;
              this.$message.success(res.msg);
              this.getList();
              this.$refs[name].resetFields();
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
        }
      });
    },
    // Xuất khẩu
    exports() {
      let formValidate = this.formValidate;
      let data = {
        data: formValidate.data,
        nickname: formValidate.nickname,
      };
      userAgentApi(data)
        .then((res) => {
          location.href = res.data[0];
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // vận hành
    changeMenu(row, name, index) {
      switch (name) {
        case '1':
          this.promoters(row, 'order'); //Đơn hàng quảng bá
          break;
        case '2':
          this.spreadQR(row); //Phương thức khuyến mãi mã QR
          break;
        case '3':
          this.editS(row); //Sửa đổi trình quảng bá ưu việt
          break;
        case '4': //Rõ ràng các nhà quảng bá vượt trội
          this.del_parent(row, 'Thông thoáng【 ' + row.nickname + ' 】nhà quảng bá cấp cao', index);
          break;
        case '5': //Bị loại khỏi chương trình khuyến mãi
          this.del_agent(row, 'Hủy bỏ【 ' + row.nickname + ' 】trình độ thăng tiến', index);
          break;
        case '6': //Sửa đổi mức khuyến mãi
          this.$modalForm(membershipDataAddApi({ uid: row.uid }, '/agent/get_level_form')).then(() => this.getList());
          break;
        default:
          break;
      }
    },
    editS(row) {
      this.promoterShow = true;
      this.formInline.uid = row.uid;
    },
    customer() {
      this.customerShow = true;
    },
    imageObject(e) {
      this.customerShow = false;
      this.formInline.spread_uid = e.uid;
      this.formInline.image = e.image;
    },
    // Mối quan hệ cấp trên rõ ràng
    del_parent(rows, titile, num) {
      let delfromDatap = {
        title: titile,
        num: num,
        url: `agent/stair/delete_spread/${rows.uid}`,
        method: 'PUT',
        ids: '',
      };
      this.$modalSure(delfromDatap)
        .then((res) => {
          this.$message.success(res.msg);
          this.getList();
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // Hủy tư cách thăng tiến của bạn
    del_agent(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `agent/stair/delete_system_spread/${row.uid}`,
        method: 'PUT',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.getList();
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    edit(row) {
      this.promoterShow = true;
      this.formInline.uid = row.uid;
    },
    cancel(name) {
      this.promoterShow = false;
      this.$refs[name].resetFields();
    },
    // Thứ tự danh sách nhà quảng bá
    promoters(row, tit) {
      this.$refs.promotersLists.modals = true;
      this.$refs.promotersLists.getList(row, tit);
    },
    // thống kê
    getStatistics() {
      let data = {
        nickname: this.formValidate.nickname,
        data: this.formValidate.data,
      };
      statisticsApi(data)
        .then(async (res) => {
          let data = res.data;
          this.cardLists = data.res;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // ngày cụ thể
    onchangeTime(e) {
      this.timeVal = e;
      this.formValidate.data = this.timeVal ? this.timeVal.join('-') : '';
      this.formValidate.page = 1;
      if (!e[0]) {
        this.formValidate.data = '';
      }
      this.getList();
      this.getStatistics();
    },
    // Chọn thời gian
    selectChange(tab) {
      this.formValidate.page = 1;
      this.formValidate.data = tab;
      this.timeVal = [];
      this.getList();
      this.getStatistics();
    },
    // danh sách
    getList() {
      this.loading = true;
      agentListApi(this.formValidate)
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
      this.getStatistics();
    },
    // mã QR
    spreadQR(row) {
      this.modals = true;
      this.rows = row;
      this.getWeChat();
      this.getXcx();
      this.getH5();
    },
    // Mã QR khuyến mãi tài khoản công khai
    getWeChat() {
      this.spinShow = true;
      let data = {
        uid: this.rows.uid,
        action: 'wechant_code',
      };
      lookCodeApi(data)
        .then(async (res) => {
          let data = res.data;
          this.code_src = data.code_src;
          this.spinShow = false;
        })
        .catch((res) => {
          this.spinShow = false;
          this.$message.error(res.msg);
        });
    },
    // Mã QR khuyến mãi chương trình nhỏ
    getXcx() {
      this.spinShow = true;
      let data = {
        uid: this.rows.uid,
      };
      lookxcxCodeApi(data)
        .then(async (res) => {
          let data = res.data;
          this.code_xcx = data.code_src;
          this.spinShow = false;
        })
        .catch((res) => {
          this.spinShow = false;
          this.$message.error(res.msg);
        });
    },
    getH5() {
      this.spinShow = true;
      let data = {
        uid: this.rows.uid,
      };
      lookh5CodeApi(data)
        .then(async (res) => {
          let data = res.data;
          this.code_h5 = data.code_src;
          this.spinShow = false;
        })
        .catch((res) => {
          this.spinShow = false;
          this.$message.error(res.msg);
        });
    },
  },
};
</script>
<style lang="scss" scoped>
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
  .iconfont {
    color: #898989;
  }
}
.QRpic {
  width: 180px;
  height: 180px;

  img {
    width: 100%;
    height: 100%;
  }
}
.QRpic_sp1 {
  font-size: 13px;
  color: #19be6b;
  cursor: pointer;
}
.QRpic_sp2 {
  font-size: 13px;
  color: #2d8cf0;
  cursor: pointer;
}

img {
  height: 36px;
  display: block;
}
.ivu-mt .name .item {
  margin: 3px 0;
}
.tabform {
  margin-bottom: 10px;
}
.Refresh {
  font-size: 12px;
  color: var(--prev-color-primary);
  cursor: pointer;
}
.ivu-form-item {
  margin-bottom: 10px;
}

/* .ivu-mt ::v-deep .ivu-table-header */
/* border-top:1px dashed #ddd!important */
</style>
