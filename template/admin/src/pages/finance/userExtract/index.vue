<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mb-16" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="formValidate"
          :model="formValidate"
          :label-width="labelWidth"
          label-position="right"
          inline
          @submit.native.prevent
        >
          <el-form-item label="Lựa chọn thời gian：">
            <el-date-picker
              clearable
              v-model="timeVal"
              type="daterange"
              :editable="false"
              @change="onchangeTime"
              format="dd/MM/yyyy"
              value-format="yyyy-MM-dd"
              start-placeholder="ngày bắt đầu"
              end-placeholder="ngày kết thúc"
              :picker-options="pickerOptions"
              style="width: 250px"
              class="mr20"
            ></el-date-picker>
          </el-form-item>
          <el-form-item label="Trạng thái rút tiền：">
            <el-select
              clearable
              v-model="formValidate.status"
              placeholder="Vui lòng chọn một trạng thái"
              @change="selChange"
              class="form_content_width"
            >
              <el-option
                v-for="(item, index) in treeData.withdrawal"
                :key="index"
                :value="item.value"
                :label="item.title"
              ></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Phương thức rút tiền：">
            <el-select
              clearable
              v-model="formValidate.extract_type"
              placeholder="Vui lòng chọn một trạng thái"
              @change="selChange"
              class="form_content_width"
            >
              <el-option
                v-for="(item, index) in treeData.payment"
                :key="index"
                :value="item.value"
                :label="item.title"
              ></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Tìm kiếm：">
            <el-input
              clearable
              placeholder="Biệt danh/tên/tài khoản Alipay/số thẻ ngân hàng của WeChat"
              v-model="formValidate.nireid"
              class="form_content_width"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="selChange">Tìm kiếm</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <cards-data :cardLists="cardLists" v-if="extractStatistics"></cards-data>
    <el-card :bordered="false" shadow="never">
      <router-link :to="$routeProStr + '/finance/finance/commission'">
        <el-button type="primary">Hồ sơ ủy ban</el-button>
      </router-link>
      <el-table ref="table" :data="tabList" v-loading="loading" empty-text="Chưa có dữ liệu" class="mt14">
        <el-table-column label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Thông tin người dùng" min-width="130">
          <template slot-scope="scope">
            <div>
              Biệt hiệu của người dùng: {{ scope.row.nickname }} <br />
              Người dùngid:{{ scope.row.uid }}
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Số tiền rút" min-width="100">
          <template slot-scope="scope">
            <div>{{ scope.row.extract_price }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Phí rút tiền" min-width="100">
          <template slot-scope="scope">
            <div>{{ scope.row.extract_fee }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Số tiền nhận được" min-width="100">
          <template slot-scope="scope">
            <div class="f-price">{{ scope.row.receive_price }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Phương thức rút tiền" min-width="130">
          <template slot-scope="scope">
            <div class="type" v-if="scope.row.extract_type === 'bank'">
              <div class="item">Tên:{{ scope.row.real_name }}</div>
              <div class="item">Số thẻ ngân hàng:{{ scope.row.bank_code }}</div>
              <div class="item">Địa chỉ tài khoản ngân hàng:{{ scope.row.bank_address }}</div>
            </div>
            <div class="type" v-if="scope.row.extract_type === 'weixin'">
              <div class="item">Biệt danh:{{ scope.row.nickname }}</div>
              <div class="item">ID WeChat:{{ scope.row.wechat }}</div>
            </div>
            <div class="type" v-if="scope.row.extract_type === 'alipay'">
              <div class="item">Tên:{{ scope.row.real_name }}</div>
              <div class="item">Số Alipay:{{ scope.row.alipay_code }}</div>
            </div>
            <div class="type" v-if="scope.row.extract_type === 'balance'">
              <div class="item">Tên:{{ scope.row.real_name }}</div>
              <div class="item">Phương thức rút tiền: Hoa hồng được chuyển vào số dư</div>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Mã thanh toán" min-width="90">
          <template slot-scope="scope">
            <div
              class="tabBox_img"
              v-viewer
              v-if="scope.row.extract_type === 'weixin' || scope.row.extract_type === 'alipay'"
            >
              <img v-lazy="scope.row.qrcode_url" />
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Thời gian nộp đơn" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time | formatDate }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Nhận xét" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.mark }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Xem lại trạng thái" min-width="130">
          <template slot-scope="scope">
            <div class="status" v-if="scope.row.status === 0">
              <div class="statusVal">Áp dụng</div>
              <div></div>
            </div>
            <div class="statusVal" v-if="scope.row.status === 1">Rút tiền đã được thông qua</div>
            <div class="statusVal" v-if="scope.row.status === -1">
              Rút tiền không thành công<br />Lý do thất bại：{{ scope.row.fail_msg }}
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Thao tác" fixed="right" width="170">
          <template slot-scope="scope" v-if="scope.row.status == 0">
            <a href="javascript:void(0);" v-db-click @click="edit(scope.row)">Chỉnh sửa</a>
            <el-divider direction="vertical"></el-divider>
            <a class="item" v-db-click @click="adopt(scope.row, 'Tán thành', index)">Vượt qua</a>
            <el-divider direction="vertical"></el-divider>
            <a class="item" v-db-click @click="invalid(scope.row)">Từ chối</a>
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

    <!-- chỉnh sửa biểu mẫu-->
    <edit-from ref="edits" :FromData="FromData" @submitFail="submitFail"></edit-from>
    <!-- từ chối vượt qua-->
    <el-dialog :visible.sync="modals" title="Lý do thất bại" :close-on-click-modal="false" width="540px">
      <el-input v-model="fail_msg.message" type="textarea" :rows="4" placeholder="Vui lòng nhập lý do thất bại" />
      <div slot="footer">
        <el-button type="primary" size="small" v-db-click @click="oks">Chắc chắn</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import cardsData from '@/components/cards/cards';
import searchFrom from '@/components/publicSearchFrom';
import { mapState } from 'vuex';
import { cashListApi, cashEditApi, refuseApi } from '@/api/finance';
import { formatDate } from '@/utils/validate';
import editFrom from '@/components/from/from';
export default {
  name: 'cashApply',
  components: { cardsData, searchFrom, editFrom },
  filters: {
    formatDate(time) {
      if (time !== 0) {
        let date = new Date(time * 1000);
        return formatDate(date, 'yyyy-MM-dd hh:mm');
      }
    },
  },
  data() {
    return {
      images: ['1.jpg', '2.jpg'],
      modal_loading: false,
      fail_msg: {
        message: 'Thông tin nhập không đầy đủ hoặc sai!',
      },
      modals: false,
      total: 0,
      cardLists: [],
      loading: false,
      tabList: [],
      pickerOptions: this.$timeOptions,
      treeData: {
        withdrawal: [
          {
            title: 'Tất cả',
            value: '',
          },
          {
            title: 'thất bại',
            value: -1,
          },
          {
            title: 'Áp dụng',
            value: 0,
          },
          {
            title: 'Đi qua',
            value: 1,
          },
        ],
        payment: [
          {
            title: 'Tất cả',
            value: '',
          },
          {
            title: 'WeChat',
            value: 'wx',
          },
          {
            title: 'Alipay',
            value: 'alipay',
          },
          {
            title: 'thẻ ngân hàng',
            value: 'bank',
          },
        ],
      },
      formValidate: {
        status: '',
        extract_type: '',
        nireid: '',
        data: '',
        page: 1,
        limit: 20,
      },
      extractStatistics: {},
      timeVal: [],
      FromData: null,
      extractId: 0,
    };
  },
  watch: {
    $route() {
      if (this.$route.fullPath === this.$routeProStr + '/finance/user_extract/index?status=0') {
        this.getPath();
      }
    },
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
    if (this.$route.fullPath === this.$routeProStr + '/finance/user_extract/index?status=0') {
      this.getPath();
    } else {
      this.getList();
    }
  },
  methods: {
    getPath() {
      this.formValidate.page = 1;
      this.formValidate.status = parseInt(this.$route.query.status);
      this.getList();
    },
    // không hợp lệ
    invalid(row) {
      this.extractId = row.id;
      this.modals = true;
    },
    // Chắc chắn
    oks() {
      this.modal_loading = true;
      refuseApi(this.extractId, this.fail_msg)
        .then(async (res) => {
          this.$message.success(res.msg);
          this.modal_loading = false;
          this.modals = false;
          this.getList();
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // vượt qua
    adopt(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `finance/extract/adopt/${row.id}`,
        method: 'put',
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
    // ngày cụ thể
    onchangeTime(e) {
      this.timeVal = e;
      this.formValidate.data = this.timeVal ? this.timeVal.join('-') : '';
      this.formValidate.page = 1;
      this.getList();
    },
    // Chọn thời gian
    selectChange(tab) {
      this.formValidate.page = 1;
      this.formValidate.data = tab;
      this.timeVal = [];
      this.getList();
    },
    // chọn
    selChange() {
      this.formValidate.page = 1;
      this.getList();
    },
    // danh sách
    getList() {
      this.loading = true;
      cashListApi(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tabList = data.list.list;
          this.total = data.list.count;
          this.extractStatistics = data.extract_statistics;
          this.cardLists = [
            {
              col: 6,
              count: this.extractStatistics.brokerage_count,
              name: 'Tổng số tiền hoa hồng',
              className: 'iconyuezhifujine',
            },
            { col: 6, count: this.extractStatistics.price, name: 'Số tiền mặt cần rút', className: 'iconfufeihuiyuanjine' },
            { col: 6, count: this.extractStatistics.priced, name: 'Số tiền đã rút', className: 'iconzhifuyongjinjine' },
            {
              col: 6,
              count: this.extractStatistics.brokerage_not,
              name: 'Số tiền mặt chưa rút',
              className: 'iconshangpintuikuanjine',
            },
          ];
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // biên tập
    edit(row) {
      cashEditApi(row.id)
        .then(async (res) => {
          if (res.data.status === false) {
            return this.$authLapse(res.data);
          }
          this.FromData = res.data;
          this.$refs.edits.modals = true;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // Chỉnh sửa gửi thành công
    submitFail() {
      // this.getList();
    },
  },
};
</script>
<style lang="scss" scoped>
.ivu-mt .type .item {
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
.status ::v-deep .item ~ .item {
  margin-left: 6px;
}
.status ::v-deep .statusVal {
  margin-bottom: 7px;
}
/*.ivu-mt ::v-deep .ivu-table-header*/
/*    border-top:1px dashed #ddd!important*/
.type {
  padding: 3px 0;
  box-sizing: border-box;
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
.z-price {
  color: red;
}
.f-price {
  color: green;
}
</style>
