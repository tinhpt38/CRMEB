<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="tableFrom"
          :model="tableFrom"
          :label-width="labelWidth"
          :label-position="labelPosition"
          @submit.native.prevent
          inline
        >
          <el-form-item :label="$t('message.pages.marketing.storeCouponIssue.couponName')" label-for="coupon_title">
            <el-input
              v-model="tableFrom.coupon_title"
              :placeholder="$t('message.pages.marketing.storeCouponIssue.inputCouponName')"
              class="form_content_width"
              maxlength="18"
              show-word-limit
            />
          </el-form-item>
          <el-form-item :label="$t('message.pages.marketing.storeCouponIssue.couponType')" label-for="coupon_type">
            <el-select
              v-model="tableFrom.coupon_type"
              :placeholder="$t('message.pages.marketing.common.pleaseSelect')"
              clearable
              @change="userSearchs"
              class="form_content_width"
            >
              <el-option value="0" :label="$t('message.pages.marketing.storeCouponIssue.generalCoupon')"></el-option>
              <el-option value="1" :label="$t('message.pages.marketing.storeCouponIssue.categoryCoupon')"></el-option>
              <el-option value="2" :label="$t('message.pages.marketing.storeCouponIssue.productCoupon')"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('message.pages.marketing.storeCouponIssue.validStatus')" label-for="status">
            <el-select
              v-model="tableFrom.status"
              :placeholder="$t('message.pages.marketing.common.pleaseSelect')"
              clearable
              @change="userSearchs"
              class="form_content_width"
            >
              <el-option value="1" :label="$t('message.pages.marketing.storeCouponIssue.normal')"></el-option>
              <el-option value="0" :label="$t('message.pages.marketing.storeCouponIssue.notOpen')"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('message.pages.marketing.storeCouponIssue.receiveType')" label-for="status">
            <el-select
              v-model="receive_type"
              :placeholder="$t('message.pages.marketing.common.pleaseSelect')"
              clearable
              @change="userSearchs"
              class="form_content_width"
            >
              <el-option value="all" :label="$t('message.pages.marketing.storeCouponIssue.all')"></el-option>
              <el-option value="1" :label="$t('message.pages.marketing.storeCouponIssue.userReceive')"></el-option>
              <el-option value="2" :label="$t('message.pages.marketing.storeCouponIssue.systemGive')"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="userSearchs">{{ $t('message.pages.marketing.common.query') }}</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt mt16">
      <el-button v-auth="['admin-marketing-store_coupon-add']" type="primary" icon="md-add" v-db-click @click="add"
        >{{ $t('message.pages.marketing.storeCouponIssue.addCoupon') }}</el-button
      >
      <el-table
        :data="tableList"
        ref="table"
        class="mt14"
        v-loading="loading"
        highlight-current-row
        :no-data-text="$t('message.pages.marketing.common.noData')"
        :no-filtered-data-text="$t('message.pages.marketing.common.noFilterResult')"
      >
        <el-table-column :label="$t('message.pages.marketing.common.id')" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeCouponIssue.couponNameCol')" min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.coupon_title }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeCouponIssue.couponTypeCol')" min-width="80">
          <template slot-scope="scope">
            <span v-if="scope.row.type === 1">{{ $t('message.pages.marketing.storeCouponIssue.categoryCoupon') }}</span>
            <span v-else-if="scope.row.type === 2">{{ $t('message.pages.marketing.storeCouponIssue.productCoupon') }}</span>
            <span v-else-if="scope.row.type === 3">{{ $t('message.pages.marketing.storeCouponIssue.memberCoupon') }}</span>
            <span v-else>{{ $t('message.pages.marketing.storeCouponIssue.generalCoupon') }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeCouponIssue.faceValue')" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.coupon_price }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeCouponIssue.receiveMethod')" min-width="150">
          <template slot-scope="scope">
            <span v-if="scope.row.receive_type === 1 || scope.row.receive_type === 4">{{ $t('message.pages.marketing.storeCouponIssue.userReceive') }}</span>
            <span v-else>{{ $t('message.pages.marketing.storeCouponIssue.systemGive') }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeCouponIssue.receiveDate')" min-width="100">
          <template slot-scope="scope">
            <div v-if="scope.row.start_time">
              {{ scope.row.start_time | formatDate }} - {{ scope.row.end_time | formatDate }}
            </div>
            <span v-else>{{ $t('message.pages.marketing.storeCouponIssue.unlimited') }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeCouponIssue.useTime')" min-width="100">
          <template slot-scope="scope">
            <div v-if="scope.row.start_use_time">
              {{ scope.row.start_use_time | formatDate }} -
              {{ scope.row.end_use_time | formatDate }}
            </div>
            <div v-else>{{ scope.row.coupon_time }}天</div>
          </template>
        </el-table-column>
        <el-table-column label="发布数量" min-width="100">
          <template slot-scope="scope">
            <span v-if="scope.row.is_permanent">不限量</span>
            <div v-else>
              <span class="fa">发布：{{ scope.row.total_count }}</span>
              <span class="sheng ml10">剩余：{{ scope.row.remain_count }}</span>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="是否开启" min-width="100">
          <template slot-scope="scope">
            <el-switch
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.status"
              :value="scope.row.status"
              size="large"
              @change="openChange(scope.row)"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column label="操作" fixed="right" width="200">
          <template slot-scope="scope">
            <a v-db-click @click="receive(scope.row)">领取记录</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="edit(scope.row)">编辑</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="copy(scope.row)">复制</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="couponDel(scope.row, '删除发布的优惠券', scope.$index)">删除</a>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="tableFrom.page"
          :limit.sync="tableFrom.limit"
          @pagination="getList"
        />
      </div>
    </el-card>
    <!-- 领取记录 -->
    <el-dialog :visible.sync="modals2" title="领取记录" :close-on-click-modal="false" width="720px">
      <el-table
        :data="receiveList"
        ref="table"
        v-loading="loading2"
        highlight-current-row
        height="500"
        no-userFrom-text="暂无数据"
        no-filtered-userFrom-text="暂无筛选结果"
      >
        <el-table-column label="ID" min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.uid }}</span>
          </template>
        </el-table-column>
        <el-table-column label="用户名" min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.nickname }}</span>
          </template>
        </el-table-column>
        <el-table-column label="用户头像" min-width="150">
          <template slot-scope="scope">
            <div class="tabBox_img" v-viewer>
              <img v-lazy="scope.row.avatar" />
            </div>
          </template>
        </el-table-column>
        <el-table-column label="领取时间" min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
      </el-table>
    </el-dialog>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import {
  releasedListApi,
  releasedissueLogApi,
  releaseStatusApi,
  delCouponReleased,
  couponStatusApi,
} from '@/api/marketing';
import { formatDate } from '@/utils/validate';
export default {
  name: 'marketing_storeCouponIssue',
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
      modals2: false,
      grid: {
        xl: 7,
        lg: 7,
        md: 12,
        sm: 24,
        xs: 24,
      },
      loading: false,

      tableFrom: {
        status: '',
        coupon_type: '',
        coupon_title: '',
        receive_type: '',
        page: 1,
        limit: 15,
      },
      receive_type: '',
      tableList: [],
      total: 0,
      FromData: null,
      receiveList: [],
      loading2: false,
      total2: 0,
      receiveFrom: {
        page: 1,
        limit: 15,
      },
      rows: {},
    };
  },
  activated() {
    this.getList();
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '90px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  methods: {
    // 失效
    couponInvalid(row, tit, num) {
      this.delfromData = {
        title: tit,
        num: num,
        url: `marketing/coupon/status/${row.id}`,
        method: 'PUT',
        ids: '',
      };
      this.$refs.modelSure.modals = true;
    },
    // 领取记录
    receive(row) {
      this.modals2 = true;
      this.rows = row;
      this.getReceivelist(row);
    },
    getReceivelist(row) {
      this.loading2 = true;
      releasedissueLogApi(row.id, this.receiveFrom)
        .then(async (res) => {
          let data = res.data;
          this.receiveList = data.list;
          this.total2 = res.data.count;
          this.loading2 = false;
        })
        .catch((res) => {
          this.loading2 = false;
          this.$message.error(res.msg);
        });
    },
    // 领取记录改变分页
    receivePageChange(index) {
      this.receiveFrom.page = index;
      this.getReceivelist(this.rows);
    },
    // 删除
    couponDel(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `marketing/coupon/released/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.tableList.splice(num, 1);
          this.total--;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 列表
    getList() {
      this.loading = true;
      this.tableFrom.receive_type = this.receive_type === 'all' ? '' : this.receive_type;
      this.tableFrom.status = this.tableFrom.status || '';
      releasedListApi(this.tableFrom)
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
    // 表格搜索
    userSearchs() {
      this.tableFrom.page = 1;
      this.getList();
    },
    // 添加优惠券
    add() {
      this.$router.push({ path: this.$routeProStr + '/marketing/store_coupon_issue/create' });
    },
    // 复制
    copy(data) {
      this.$router.push({
        path: this.$routeProStr + `/marketing/store_coupon_issue/create/${data.id}`,
      });
    },
    // 复制
    edit(data) {
      this.$router.push({
        path: this.$routeProStr + `/marketing/store_coupon_issue/create/${data.id}/1`,
      });
    },
    // 是否开启
    openChange(data) {
      couponStatusApi(data).then(() => this.getList());
    },
  },
};
</script>

<style lang="scss" scoped>
.fa {
  color: #0a6aa1;
  display: block;
}
.sheng {
  color: #ff0000;
  display: block;
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
