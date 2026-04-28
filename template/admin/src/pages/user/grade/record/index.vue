<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="formValidate"
          :model="formValidate"
          :label-width="labelWidth"
          :label-position="labelPosition"
          inline
          @submit.native.prevent
          class="tabform"
        >
          <el-form-item label="Loại thành viên：">
            <el-select v-model="formValidate.member_type" clearable @change="userSearchs" class="form_content_width">
              <el-option v-for="item in treeSelect" :value="item.id" :key="item.id" :label="item.label"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Phương thức thanh toán：">
            <el-select v-model="formValidate.pay_type" clearable @change="paySearchs" class="form_content_width">
              <el-option v-for="item in payList" :value="item.val" :key="item.val" :label="item.label"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Thời gian mua hàng：">
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
            ></el-date-picker>
          </el-form-item>
          <el-form-item label="Tìm kiếm：">
            <el-input
              clearable
              placeholder="Vui lòng nhập tên người dùng để tìm kiếm"
              v-model="formValidate.name"
              class="form_content_width"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="userSearchs">Tìm kiếm</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt mt16">
      <el-table
        :data="tbody"
        ref="table"
        v-loading="loading"
        size="small"
        no-userFrom-text="Chưa có dữ liệu"
        no-filtered-userFrom-text="Chưa có kết quả lọc nào"
      >
        <el-table-column label="Số đơn hàng" width="170">
          <template slot-scope="scope">
            <span>{{ scope.row.order_id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Tên người dùng" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.user.nickname }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Số điện thoại" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.user.phone || '--' }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Loại thành viên" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.member_type }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Thời hạn hiệu lực (ngày）" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.vip_day === -1 ? 'Vĩnh viễn' : scope.row.vip_day }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Số tiền thanh toán (đồng）" min-width="50">
          <template slot-scope="scope">
            <span>{{ scope.row.pay_price }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Phương thức thanh toán" min-width="30">
          <template slot-scope="scope">
            <span>{{ scope.row.pay_type }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Thời gian mua hàng" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.pay_time }}</span>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="tablePage.page"
          :limit.sync="tablePage.limit"
          @pagination="getMemberRecord"
        />
      </div>
    </el-card>
  </div>
</template>

<script>
import { userMemberCard, memberRecord } from '@/api/user';
import { mapState } from 'vuex';

export default {
  name: 'card',
  data() {
    return {
      treeSelect: [
        {
          id: 'free',
          label: 'thử',
        },
        {
          id: 'card',
          label: 'bạch đậu khấu',
        },
        {
          id: 'month',
          label: 'thẻ hàng tháng',
        },
        {
          id: 'quarter',
          label: 'thẻ mùa giải',
        },
        {
          id: 'year',
          label: 'Vé hàng năm',
        },
        {
          id: 'ever',
          label: 'Vĩnh viễn',
        },
      ],
      payList: [
        {
          val: 'free',
          label: 'miễn phí',
        },
        {
          val: 'yue',
          label: 'Số dư',
        },
        {
          val: 'weixin',
          label: 'WeChat',
        },
        {
          val: 'alipay',
          label: 'Alipay',
        },
      ],
      tbody: [],
      loading: false,
      total: 0,
      formValidate: {
        name: '',
        member_type: '',
        pay_type: '',
        add_time: '',
      },
      pickerOptions: this.$timeOptions,
      timeVal: [],
      tablePage: {
        page: 1,
        limit: 15,
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
    this.getMemberRecord();
  },
  methods: {
    // Tìm kiếm tên người dùng；
    selChange() {
      this.tablePage.page = 1;
      this.getMemberRecord();
    },
    //Tìm kiếm kiểu người dùng；
    userSearchs() {
      this.tablePage.page = 1;
      this.getMemberRecord();
    },
    //Tìm kiếm phương thức thanh toán；
    paySearchs() {
      this.tablePage.page = 1;
      this.getMemberRecord();
    },
    // ngày cụ thể
    onchangeTime(e) {
      this.timeVal = e || [];
      this.formValidate.add_time = this.timeVal[0] ? (this.timeVal ? this.timeVal.join('-') : '') : '';
      this.tablePage.page = 1;
      this.getMemberRecord();
    },
    getMemberRecord() {
      this.loading = true;
      let data = {
        page: this.tablePage.page,
        limit: this.tablePage.limit,
        member_type: this.formValidate.member_type,
        pay_type: this.formValidate.pay_type,
        add_time: this.formValidate.add_time,
        name: this.formValidate.name,
      };
      memberRecord(data)
        .then((res) => {
          this.loading = false;
          const { list, count } = res.data;
          this.tbody = list;
          this.total = count;
        })
        .catch((err) => {
          this.loading = false;
          this.$message.error(err.msg);
        });
    },
  },
};
</script>
