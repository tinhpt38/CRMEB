<template>
  <div>
    <el-dialog :visible.sync="modals" :z-index="100" :title="$t('message.common.sendCoupon')" :close-on-click-modal="false" width="1000px">
      <div class="acea-row">
        <span class="sp">{{ $t('message.common.couponName') }}：</span
        ><el-input clearable v-model="page.coupon_title" :placeholder="$t('message.common.pleaseInputCouponName')" class="form_content_width" />
        <el-button type="primary" v-db-click @click="userSearchs" class="ml15">{{ $t('message.common.query') }}</el-button>
      </div>
      <el-table
        :data="couponList"
        ref="table"
        class="mt14"
        v-loading="loading"
        highlight-current-row
        :no-userFrom-text="$t('message.common.noData')"
        :no-filtered-userFrom-text="$t('message.common.noFilteredResults')"
      >
        <el-table-column :label="$t('message.common.couponName')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.title }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.common.couponFaceValue')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.coupon_price }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.common.couponMinConsumption')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.use_min_price }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.common.couponValidityPeriod')" min-width="130">
          <template slot-scope="scope">
            <div v-if="scope.row.coupon_time">{{ scope.row.coupon_time }}</div>
            <div v-else>{{ scope.row.use_time }}</div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.common.operation')" fixed="right" width="90">
          <template slot-scope="scope">
            <a v-db-click @click="sendGrant(scope.row, $t('message.common.sendCoupon'), index)">{{ $t('message.common.send') }}</a>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination v-if="total" :total="total" :page.sync="page.page" :limit.sync="page.limit" @pagination="getList" />
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { couponApi } from '@/api/user';
export default {
  name: 'send',
  props: {
    userIds: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      modals: false,
      loading: false,
      couponList: [],
      page: {
        page: 1, // 当前页
        limit: 15,
        coupon_title: '',
        receive_type: 3,
      },
      total: 0, // 总条数
    };
  },
  methods: {
    // 优惠券列表
    getList(id) {
      this.loading = true;
      couponApi(this.page)
        .then(async (res) => {
          if (res.status === 200) {
            let data = res.data;
            this.couponList = data.list;
            this.total = data.count;
            this.loading = false;
          } else {
            this.loading = false;
            this.$message.error(res.msg);
          }
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // 表格搜索
    userSearchs() {
      this.getList();
    },
    // 发送
    sendGrant(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `marketing/coupon/user/grant`,
        method: 'post',
        ids: {
          id: row.id,
          uid: this.userIds,
        },
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.sp {
  font-size: 12px;
  color: #606266;
  line-height: 32px;
}
</style>
