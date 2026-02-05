<template>
  <el-card :bordered="false" shadow="never" class="ivu-mt-16">
    <div class="acea-row row-between-wrapper mb20">
      <h4 class="statics-header-title">{{ $t('message.pages.statistic.product.productRanking.title') }}</h4>
      <div class="acea-row">
        <el-select v-model="formValidate.sort" style="width: 200px" class="mr20" @change="changeMenu">
          <el-option v-for="item in list" :value="item.val" :key="item.val" :label="item.name"></el-option>
        </el-select>
        <el-date-picker
          :editable="false"
          clearable
          @change="onchangeTime"
          v-model="timeVal"
          format="yyyy/MM/dd"
          type="datetimerange"
          value-format="yyyy/MM/dd"
          range-separator="-"
          :start-placeholder="$t('message.pages.statistic.product.productRanking.startDate')"
          :end-placeholder="$t('message.pages.statistic.product.productRanking.endDate')"
          class="mr20"
        ></el-date-picker>
        <el-button type="primary" class="mr20" v-db-click @click="getList">{{ $t('message.pages.statistic.product.productRanking.query') }}</el-button>
      </div>
    </div>
    <el-table ref="selection" :data="tabList" v-loading="loading" :empty-text="$t('message.pages.statistic.product.productRanking.noData')" highlight-current-row>
      <el-table-column :label="$t('message.pages.statistic.product.productRanking.id')" min-width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.product_id }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.pages.statistic.product.productRanking.productImage')" min-width="90">
        <template slot-scope="scope">
          <div class="tabBox_img" v-viewer>
            <img v-lazy="scope.row.image" />
          </div>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.pages.statistic.product.productRanking.productName')" min-width="130">
        <template slot-scope="scope">
          <span>{{ scope.row.store_name }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.pages.statistic.product.productRanking.browse')" min-width="90">
        <template slot-scope="scope">
          <span>{{ scope.row.visit }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.pages.statistic.product.productRanking.visitor')" min-width="90">
        <template slot-scope="scope">
          <span>{{ scope.row.user }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.pages.statistic.product.productRanking.cartCount')" min-width="90">
        <template slot-scope="scope">
          <span>{{ scope.row.cart }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.pages.statistic.product.productRanking.orderCount')" min-width="90">
        <template slot-scope="scope">
          <span>{{ scope.row.orders }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.pages.statistic.product.productRanking.payCount')" min-width="90">
        <template slot-scope="scope">
          <span>{{ scope.row.pay }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.pages.statistic.product.productRanking.payAmount')" min-width="110">
        <template slot-scope="scope">
          <span>{{ scope.row.price }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.pages.statistic.product.productRanking.collectCount')" min-width="90">
        <template slot-scope="scope">
          <span>{{ scope.row.collect }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.pages.statistic.product.productRanking.visitorPayRate')" min-width="140">
        <template slot-scope="scope">
          <span>{{ $tools.accMul(scope.row.changes, 100) + '%' }}</span>
        </template>
      </el-table-column>
    </el-table>
    <!-- 商品弹窗 -->
    <div v-if="isProductBox">
      <div class="bg" v-db-click @click="isProductBox = false"></div>
      <goodsDetail :goodsId="goodsId"></goodsDetail>
    </div>
  </el-card>
</template>

<script>
import { statisticProductListApi } from '@/api/statistic';
import goodsDetail from '../components/goodsDetail';
import { formatDate } from '@/utils/validate';
export default {
  name: 'productRanking',
  components: {
    goodsDetail,
  },
  data() {
    return {
      validateFun: this.$validateFun,
      options: this.$timeOptions,
      name: '',
      timeVal: [],
      dataTime: '',
      formValidate: {
        limit: 10,
        page: 1,
        sort: 'visit',
        data: '',
      },
      loading: false,
      tabList: [],
      total: 0,
      goodsId: '',
      isProductBox: false,
      list: [],
    };
  },
  created() {
    this.name = this.$t('message.pages.statistic.product.productRanking.last30Days');
    this.list = [
      { val: 'visit', name: this.$t('message.pages.statistic.product.productRanking.sortVisit') },
      { val: 'user', name: this.$t('message.pages.statistic.product.productRanking.sortVisitor') },
      { val: 'cart', name: this.$t('message.pages.statistic.product.productRanking.sortCart') },
      { val: 'orders', name: this.$t('message.pages.statistic.product.productRanking.sortOrders') },
      { val: 'price', name: this.$t('message.pages.statistic.product.productRanking.sortPrice') },
      { val: 'collect', name: this.$t('message.pages.statistic.product.productRanking.sortCollect') },
      { val: 'changes', name: this.$t('message.pages.statistic.product.productRanking.sortChanges') },
    ];
    const end = new Date();
    const start = new Date();
    start.setTime(start.setTime(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() - 29)));
    this.timeVal = [start, end];
    this.formValidate.data = formatDate(start, 'yyyy/MM/dd') + '-' + formatDate(end, 'yyyy/MM/dd');
  },
  mounted() {
    this.getList();
  },
  methods: {
    // 具体日期
    onchangeTime(e) {
      this.timeVal = e;
      this.formValidate.data = this.timeVal ? this.timeVal.join('-') : '';
      this.name = this.formValidate.data;
    },
    changeMenu(name) {
      this.formValidate.sort = name;
      this.getList();
    },
    // 列表
    getList() {
      this.loading = true;
      statisticProductListApi(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tabList = data;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    look(row) {
      this.goodsId = row.product_id;
      this.isProductBox = true;
    },
  },
};
</script>

<style scoped lang="scss">
.header {
  &-title {
    font-size: 16px;
    color: rgba(0, 0, 0, 0.85);
  }
  &-time {
    font-size: 12px;
    color: #000000;
    opacity: 0.45;
  }
}
</style>
<style lang="scss" scoped>
.bg {
  position: fixed;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  z-index: 11;
}
::v-deep .happy-scroll-content {
  width: 100%;
  .demo-spin-icon-load {
    animation: ani-demo-spin 1s linear infinite;
  }

  @-webkit-keyframes ani-demo-spin {
    from {
      transform: rotate(0deg);
    }
    50% {
      transform: rotate(180deg);
    }
    to {
      transform: rotate(360deg);
    }
  }

  @-moz-keyframes ani-demo-spin {
    from {
      transform: rotate(0deg);
    }
    50% {
      transform: rotate(180deg);
    }
    to {
      transform: rotate(360deg);
    }
  }

  @-ms-keyframes ani-demo-spin {
    from {
      transform: rotate(0deg);
    }
    50% {
      transform: rotate(180deg);
    }
    to {
      transform: rotate(360deg);
    }
  }

  @-o-keyframes ani-demo-spin {
    from {
      transform: rotate(0deg);
    }
    50% {
      transform: rotate(180deg);
    }
    to {
      transform: rotate(360deg);
    }
  }

  @keyframes ani-demo-spin {
    from {
      transform: rotate(0deg);
    }
    50% {
      transform: rotate(180deg);
    }
    to {
      transform: rotate(360deg);
    }
  }
  .demo-spin-col {
    height: 100px;
    position: relative;
    border: 1px solid #eee;
  }
}
</style>
