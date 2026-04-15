<template>
  <div>
    <el-dialog :visible.sync="isTemplate" :title="$t('message.couponList.title')" append-to-body width="1000px">
      <el-table
        :data="couponList"
        ref="couponTable"
        class="mt20"
        v-loading="loading"
        highlight-current-row
        :row-key="getRowKey"
        :no-userFrom-text="$t('message.couponList.empty')"
        :no-filtered-userFrom-text="$t('message.couponList.noResult')"
        @selection-change="changeCheckbox"
      >
        <el-table-column v-if="!luckDraw" type="selection" width="55" :reserve-selection="true"> </el-table-column>
        <el-table-column v-else width="50">
          <template slot-scope="scope">
            <el-radio v-model="templateRadio" :label="scope.row.id" @change.native="getTemplateRow(scope.row)"
              >&nbsp;</el-radio
            >
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.couponList.id')" width="70">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.couponList.name')" min-width="120">
          <template slot-scope="scope">
            <span>{{ scope.row.title }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.couponList.type')" min-width="100">
          <template slot-scope="scope">
            <span v-if="scope.row.type === 1">{{ $t('message.couponList.categoryCoupon') }}</span>
            <span v-else-if="scope.row.type === 2">{{ $t('message.couponList.productCoupon') }}</span>
            <span v-else-if="scope.row.type === 3">{{ $t('message.couponList.memberCoupon') }}</span>
            <span v-else>{{ $t('message.couponList.universalCoupon') }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.couponList.amount')" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.coupon_price }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.couponList.minAmount')" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.use_min_price }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.couponList.publishCount')" min-width="100">
          <template slot-scope="scope">
            <span v-if="scope.row.is_permanent">{{ $t('message.couponList.unlimited') }}</span>
            <div v-else>
              <span class="fa">{{ $t('message.couponList.published') }}：{{ scope.row.total_count }}</span>
              <span class="sheng ml10">{{ $t('message.couponList.remain') }}：{{ scope.row.remain_count }}</span>
            </div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.couponList.validPeriod')" min-width="100">
          <template slot-scope="scope">
            <div v-if="scope.row.start_time">
              {{ scope.row.start_time | formatDate }} - {{ scope.row.end_time | formatDate }}
            </div>
            <span v-else>{{ $t('message.couponList.noLimitTime') }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.couponList.status')" min-width="100">
          <template slot-scope="scope">
            <el-tag size="medium" v-show="scope.row.status === 1">{{ $t('message.couponList.normal') }}</el-tag>
            <el-tag size="medium" type="danger" v-show="scope.row.status === 0">{{
              $t('message.couponList.notStarted')
            }}</el-tag>
            <el-tag size="medium" type="info" v-show="scope.row.status === -1">{{ $t('message.couponList.expired') }}</el-tag>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="tableFrom.page"
          :limit.sync="tableFrom.limit"
          @pagination="tableList"
        />
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button v-db-click @click="cancel">{{ $t('message.couponList.cancel') }}</el-button>
        <el-button type="primary" v-db-click @click="ok">{{ $t('message.couponList.confirm') }}</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { releasedListApi } from '@/api/marketing';
import { formatDate } from '@/utils/validate';

export default {
  name: 'index',
  filters: {
    formatDate(time) {
      if (time !== 0) {
        let date = new Date(time * 1000);
        return formatDate(date, 'yyyy-MM-dd hh:mm');
      }
    },
  },
  props: {
    couponids: {
      type: Array,
    },
    updateIds: {
      type: Array,
    },
    updateName: {
      type: Array,
    },
    luckDraw: {
      type: Boolean,
      default: false,
    },
    status: {
      type: Number,
      default: null,
    },
    receiveType: {
      type: [Number, String],
      default: 3,
    },
  },
  data() {
    return {
      templateRadio: 0,

      currentid: 0,
      productRow: {},
      isTemplate: false,
      loading: false,
      tableFrom: {
        receive_type: 3,
        page: 1,
        limit: 15,
      },
      total: 0,
      ids: [],
      texts: [],
      couponList: [],
      selectedIds: [],
      selectedNames: [],
      multipleSelection: [],
    };
  },
  mounted() {},
  watch: {
    updateIds: function (newVal) {
      this.selectedIds = newVal;
    },
    updateName: function (newVal) {
      this.selectedNames = newVal;
      this.multipleSelection = newVal;
    },
    isTemplate(n) {
      if (n) {
        this.tableFrom.page = 1;
        this.tableList();
      }
    },
  },
  created() {},
  methods: {
    getRowKey(row) {
      return row.id;
    },
    getTemplateRow(row) {
      this.currentid = row.id;
      this.productRow = row;
    },
    //对象数组去重；
    unique(arr) {
      const res = new Map();
      return arr.filter((arr) => !res.has(arr.id) && res.set(arr.id, 1));
    },
    changeCheckbox(selection) {
      let uniqueArr = [];
      let cups = [];
      let ids = [];
      let arr = this.unique(selection);
      for (let i = 0; i < arr.length; i++) {
        const item = arr[i];
        if (!ids.includes(item.id)) {
          let obj = {
            id: item.id,
            title: item.title,
            full_reduction: item.full_reduction, // 满
            use_min_price: item.use_min_price, // 满
            coupon_price: item.coupon_price, // 减
          };
          cups.push(obj);
          ids.push(item.id);
          uniqueArr.push(item);
        }
      }
      this.selectedIds = ids;
      this.selectedNames = cups;
      this.multipleSelection = uniqueArr;
    },
    cancel() {
      this.isTemplate = false;
      if (this.luckDraw) {
        this.currentid = 0;
      }
    },
    tableList() {
      this.loading = true;
      this.tableFrom.receive_type = this.receiveType === 'all' ? '' : this.receiveType;
      if (this.status !== null) {
        this.tableFrom.status = this.status;
      }
      releasedListApi(this.tableFrom).then((res) => {
        let data = res.data;
        this.couponList = data.list;
        this.total = data.count;
        this.$nextTick(() => {
          //确保dom加载完毕
          this.selectedIds.length && this.setChecked();
          this.showSelectData();
        });
        this.loading = false;
      });
    },
    setChecked() {
      //将new Set()转化为数组
      let ids = [...this.selectedIds];
      this.couponList.forEach((row) => {
        if (ids.includes(row.id)) {
          this.$refs.couponTable.toggleRowSelection(row, true);
        } else {
          this.$refs.couponTable.toggleRowSelection(row, false);
        }
      });
    },
    ok() {
      if (this.luckDraw) {
        this.$emit('getCouponId', this.productRow);
        this.currentid = 0;
      } else {
        this.$emit('nameId', this.selectedIds, this.selectedNames);
      }
      this.isTemplate = false;
    },
    pageChange(index) {
      this.tableFrom.page = index;
      this.tableList();
    },
    limitChange(limit) {
      this.tableFrom.limit = limit;
      this.tableList();
    },
    showSelectData() {
      if (this.multipleSelection.length > 0) {
        // 判断是否存在勾选过的数据
        this.couponList.forEach((row) => {
          // 获取数据列表接口请求到的数据
          this.multipleSelection.forEach((item) => {
            // 勾选到的数据
            if (row.id === item.id) {
              // this.$refs.table.toggleRowSelection(item, true); // 若有重合，则回显该条数据
            }
          });
        });
      }
    },
  },
};
</script>

<style scoped></style>
