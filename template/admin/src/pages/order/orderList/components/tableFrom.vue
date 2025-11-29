<template>
  <div class="table_box">
    <div class="padding-add">
      <el-form
        ref="orderData"
        :model="orderData"
        label-width="80px"
        label-position="right"
        inline
        @submit.native.prevent
      >
        <el-form-item :label="$t('message.orderList.orderType')">
          <el-select v-model="orderData.status" clearable @change="selectChange2" :placeholder="$t('message.orderList.all')">
            <el-option :label="$t('message.orderList.allOrders')" value="" />
            <el-option :label="$t('message.orderList.normalOrder')" value="1" />
            <el-option v-permission="'combination'" :label="$t('message.orderList.groupOrder')" value="2" />
            <el-option v-permission="'seckill'" :label="$t('message.orderList.seckillOrder')" value="3" />
            <el-option v-permission="'bargain'" :label="$t('message.orderList.bargainOrder')" value="4" />
            <el-option :label="$t('message.orderList.advanceOrder')" value="5" />
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('message.orderList.payMethod')">
          <el-select
            v-model="orderData.pay_type"
            clearable
            @change="userSearchs"
            :placeholder="$t('message.orderList.all')"
            class="form_content_width"
          >
            <el-option v-for="item in payList" :value="item.val" :label="item.label" :key="item.id" />
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('message.orderList.createTime')">
          <el-date-picker
            clearable
            v-model="timeVal"
            type="daterange"
            @change="onchangeTime"
            format="yyyy/MM/dd"
            value-format="yyyy/MM/dd"
            :start-placeholder="$t('message.orderList.startDate')"
            :end-placeholder="$t('message.orderList.endDate')"
            :picker-options="pickerOptions"
            style="width: 250px"
          ></el-date-picker>
        </el-form-item>
        <el-form-item :label="$t('message.orderList.orderSearch')" prop="real_name" label-for="real_name">
          <el-input clearable v-model="orderData.real_name" :placeholder="$t('message.orderList.pleaseInput')" class="form_content_width">
            <el-select v-model="orderData.field_key" slot="prepend" style="width: 100px">
              <el-option value="all" :label="$t('message.orderList.allOption')"></el-option>
              <el-option value="order_id" :label="$t('message.orderList.orderId')"></el-option>
              <el-option value="uid" :label="$t('message.orderList.uid')"></el-option>
              <el-option value="real_name" :label="$t('message.orderList.userName')"></el-option>
              <el-option value="user_phone" :label="$t('message.orderList.userPhone')"></el-option>
              <el-option value="title" :label="$t('message.orderList.productName')"></el-option>
            </el-select>
          </el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" v-db-click @click="orderSearch">{{ $t('message.orderList.query') }}</el-button>
          <el-button v-db-click @click="handleReset">{{ $t('message.orderList.reset') }}</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex';
import { putWrite } from '@/api/order';
import { exportOrderList } from '@/api/export';
import timeOptions from '@/libs/timeOptions';
export default {
  name: 'table_from',
  data() {
    return {
      fromList: {
        title: this.$t('message.orderList.selectTime'),
        custom: true,
        fromTxt: [
          { text: this.$t('message.orderList.all'), val: '' },
          { text: this.$t('message.orderList.today'), val: 'today' },
          { text: this.$t('message.orderList.yesterday'), val: 'yesterday' },
          { text: this.$t('message.orderList.lately7'), val: 'lately7' },
          { text: this.$t('message.orderList.lately30'), val: 'lately30' },
          { text: this.$t('message.orderList.thisMonth'), val: 'month' },
          { text: this.$t('message.orderList.thisYear'), val: 'year' },
        ],
      },
      currentTab: '',
      grid: {
        xl: 8,
        lg: 8,
        md: 8,
        sm: 24,
        xs: 24,
      },
      // 搜索条件
      orderData: {
        status: '',
        data: '',
        real_name: '',
        field_key: 'all',
        pay_type: '',
        type: '',
      },
      modalTitleSs: '',
      statusType: '',
      time: '',
      value2: [],
      modals2: false,
      timeVal: [],
      payList: [
        { label: this.$t('message.orderList.all'), val: '' },
        { label: this.$t('message.orderList.wechatPay'), val: '1' },
        { label: this.$t('message.orderList.alipay'), val: '4' },
        { label: this.$t('message.orderList.balancePay'), val: '2' },
        { label: this.$t('message.orderList.offlinePay'), val: '3' },
      ],
      pickerOptions: timeOptions,
    };
  },
  computed: {
    ...mapState('order', ['orderChartType', 'isDels', 'delIdList', 'orderType']),

    today() {
      const end = new Date();
      const start = new Date();
      var datetimeStart = start.getFullYear() + '/' + (start.getMonth() + 1) + '/' + start.getDate();
      var datetimeEnd = end.getFullYear() + '/' + (end.getMonth() + 1) + '/' + end.getDate();
      return [datetimeStart, datetimeEnd];
    },
  },
  watch: {
    $route() {
      if (this.$route.fullPath === this.$routeProStr + '/order/list?status=1') {
        this.getPath();
      }
    },
    'orderData.field_key': function (val, oval) {
      this.getfieldKey(val);
    },
  },
  created() {
    this.setOrderKeyword('');
    if (this.$route.fullPath === this.$routeProStr + '/order/list?status=1') {
      this.getPath();
    }
  },
  methods: {
    ...mapMutations('order', [
      'getOrderStatus',
      'getOrderType',
      'getOrderTime',
      'onChangeTabs',
      'setOrderKeyword',
      'getfieldKey',
      'resetSearch',
    ]),
    getPath() {
      this.orderData.status = this.$route.query.status.toString();
      this.getOrderStatus(this.orderData.status);
      this.$emit('getList', 1);
    },
    // 导出
    async exportList() {
      this.orderData.type = this.orderType === 0 ? '' : this.orderType;
      let [th, filekey, data, fileName] = [[], [], [], ''];
      let excelData = JSON.parse(JSON.stringify(this.orderData));
      excelData.page = 1;
      excelData.limit = 200;
      excelData.ids = this.delIdList;
      for (let i = 0; i < excelData.page + 1; i++) {
        let lebData = await this.getExcelData(excelData);
        if (!fileName) fileName = lebData.filename;
        if (!filekey.length) {
          filekey = lebData.fileKey;
        }
        if (!th.length) th = lebData.header;
        if (lebData.export.length) {
          data = data.concat(lebData.export);
          excelData.page++;
        } else {
          this.$exportExcel(th, filekey, fileName, data);
          return;
        }
      }
    },
    getExcelData(excelData) {
      return new Promise((resolve, reject) => {
        exportOrderList(excelData).then((res) => {
          resolve(res.data);
        });
      });
    },
    // 具体日期
    onchangeTime(e) {
      this.timeVal = e || [];
      this.orderData.data = this.timeVal[0] ? (this.timeVal ? this.timeVal.join('-') : '') : '';
      this.$store.dispatch('order/getOrderTabs', {
        type: this.orderData.status,
        data: this.orderData.data,
        pay_type: this.orderData.pay_type,
        field_key: this.orderData.field_key,
        real_name: this.orderData.real_name,
      });
      this.getOrderTime(this.orderData.data);
      this.$emit('getList', 1);
    },
    // 选择时间
    selectChange(tab) {
      this.$store.dispatch('order/getOrderTabs', {
        type: this.orderData.status,
        data: this.orderData.data,
        pay_type: this.orderData.pay_type,
        field_key: this.orderData.field_key,
        real_name: this.orderData.real_name,
      });
      this.orderData.data = tab;
      this.getOrderTime(this.orderData.data);
      this.timeVal = [];
      this.$emit('getList');
    },
    // 订单选择状态
    selectChange2(tab) {
      this.onChangeTabs(Number(tab));
      this.$store.dispatch('order/getOrderTabs', {
        type: this.orderData.status,
        data: this.orderData.data,
        pay_type: this.orderData.pay_type,
        field_key: this.orderData.field_key,
        real_name: this.orderData.real_name,
      });
      // this.$emit('getList', 1);
    },
    userSearchs(type) {
      this.getOrderType(type);
      this.$store.dispatch('order/getOrderTabs', {
        type: this.orderData.status,
        data: this.orderData.data,
        pay_type: this.orderData.pay_type,
        field_key: this.orderData.field_key,
        real_name: this.orderData.real_name,
      });
      this.$emit('getList', 1);
    },
    // 时间状态
    timeChange(time) {
      this.getOrderTime(time);
      this.$emit('getList');
    },
    // 订单号搜索
    orderSearch() {
      this.setOrderKeyword(this.orderData.real_name);
      this.getfieldKey(this.orderData.field_key);
      this.$emit('getList', 1);
      this.$store.dispatch('order/getOrderTabs', {
        type: this.orderData.status,
        data: this.orderData.data,
        pay_type: this.orderData.pay_type,
        field_key: this.orderData.field_key,
        real_name: this.orderData.real_name,
      });
    },
    // 点击订单类型
    onClickTab() {
      this.$emit('onChangeType', this.currentTab);
    },
    // 批量删除
    delAll() {
      if (this.delIdList.length === 0) {
        this.$message.error(this.$t('message.orderList.pleaseSelectOrder'));
      } else {
        if (this.isDels) {
          let idss = {
            ids: this.delIdList,
          };
          let delfromData = {
            title: this.$t('message.orderList.deleteOrder'),
            url: `/order/dels`,
            method: 'post',
            ids: idss,
          };
          this.$modalSure(delfromData)
            .then((res) => {
              this.$message.success(res.msg);
              this.$emit('getList');
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
        } else {
          this.$message.error(this.$t('message.orderList.cannotDelete'));
        }
      }
    },
    // 刷新
    Refresh() {
      this.$emit('getList');
    },
    //
    handleReset() {
      this.timeVal = [];
      this.time = '';
      this.resetSearch();
      this.$emit('getList');
    },
  },
};
</script>
