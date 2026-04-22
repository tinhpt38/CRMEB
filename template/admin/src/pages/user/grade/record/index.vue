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
          <el-form-item :label="$t('userGrade.memberType') + '：'">
            <el-select v-model="formValidate.member_type" clearable @change="userSearchs" class="form_content_width">
              <el-option v-for="item in treeSelect" :value="item.id" :key="item.id" :label="item.label"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('userGrade.payType') + '：'">
            <el-select v-model="formValidate.pay_type" clearable @change="paySearchs" class="form_content_width">
              <el-option v-for="item in payList" :value="item.val" :key="item.val" :label="item.label"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('userGrade.purchaseTime') + '：'">
            <el-date-picker
              clearable
              v-model="timeVal"
              type="daterange"
              :editable="false"
              @change="onchangeTime"
              format="yyyy/MM/dd"
              value-format="yyyy/MM/dd"
              :start-placeholder="$t('userGrade.startDate')"
              :end-placeholder="$t('userGrade.endDate')"
              :picker-options="pickerOptions"
              style="width: 250px"
            ></el-date-picker>
          </el-form-item>
          <el-form-item :label="$t('userGrade.search') + '：'">
            <el-input
              clearable
              :placeholder="$t('userGrade.pleaseInputUserName')"
              v-model="formValidate.name"
              class="form_content_width"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="userSearchs">{{ $t('userGrade.query') }}</el-button>
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
        :no-userFrom-text="$t('userGrade.noData')"
        :no-filtered-userFrom-text="$t('userGrade.noFilteredResult')"
      >
        <el-table-column :label="$t('userGrade.orderNo')" width="170">
          <template slot-scope="scope">
            <span>{{ scope.row.order_id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('userGrade.userName')" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.user.nickname }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('userGrade.mobile')" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.user.phone || '--' }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('userGrade.memberType')" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.member_type }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('userGrade.validPeriodDays')" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.vip_day === -1 ? $t('userGrade.forever') : scope.row.vip_day }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('userGrade.payAmountYuan')" min-width="50">
          <template slot-scope="scope">
            <span>{{ scope.row.pay_price }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('userGrade.payType')" min-width="30">
          <template slot-scope="scope">
            <span>{{ scope.row.pay_type }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('userGrade.purchaseTime')" min-width="80">
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
          label: this.$t('userGrade.trial'),
        },
        {
          id: 'card',
          label: this.$t('userGrade.cardSecret'),
        },
        {
          id: 'month',
          label: this.$t('userGrade.monthCard'),
        },
        {
          id: 'quarter',
          label: this.$t('userGrade.quarterCard'),
        },
        {
          id: 'year',
          label: this.$t('userGrade.yearCard'),
        },
        {
          id: 'ever',
          label: this.$t('userGrade.forever'),
        },
      ],
      payList: [
        {
          val: 'free',
          label: this.$t('userGrade.free'),
        },
        {
          val: 'yue',
          label: this.$t('userGrade.balance'),
        },
        {
          val: 'weixin',
          label: this.$t('userGrade.wechat'),
        },
        {
          val: 'alipay',
          label: this.$t('userGrade.alipay'),
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
    // 用户名搜索；
    selChange() {
      this.tablePage.page = 1;
      this.getMemberRecord();
    },
    //用户类型搜索；
    userSearchs() {
      this.tablePage.page = 1;
      this.getMemberRecord();
    },
    //支付方式搜索；
    paySearchs() {
      this.tablePage.page = 1;
      this.getMemberRecord();
    },
    // 具体日期
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
