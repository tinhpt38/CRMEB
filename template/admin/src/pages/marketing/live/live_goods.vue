<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="formValidate"
          :model="formValidate"
          :label-width="labelWidth"
          label-position="right"
          inline
          class="tabform"
          @submit.native.prevent
        >
          <el-form-item :label="$t('message.pages.marketing.live.liveGoods.auditStatus')">
            <el-select v-model="formValidate.status" clearable @change="selChange" class="form_content_width">
              <el-option
                v-for="(item, index) in treeData.withdrawal"
                :value="item.value"
                :key="index"
                :label="item.title"
              ></el-option>
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('message.pages.marketing.live.liveGoods.search')">
            <el-input
              clearable
              :placeholder="$t('message.pages.marketing.live.liveGoods.inputProductNameId')"
              v-model="formValidate.kerword"
              class="form_content_width"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="selChange">{{ $t('message.pages.marketing.live.liveGoods.query') }}</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt mt16">
      <el-button v-auth="['setting-system_menus-add']" type="primary" v-db-click @click="menusAdd($t('message.pages.marketing.live.liveGoods.addLiveRoom'))"
        >{{ $t('message.pages.marketing.live.liveGoods.addGoods') }}
      </el-button>
      <!-- <el-button
        v-auth="['setting-system_menus-add']"
        type="success"
        v-db-click @click="syncGoods"
        style="margin-left: 20px"
        >同步商品
      </el-button> -->
      <el-table
        :data="tabList"
        ref="table"
        class="mt14"
        v-loading="loading"
        highlight-current-row
        :no-data-text="$t('message.pages.marketing.common.noData')"
        :no-filtered-data-text="$t('message.pages.marketing.common.noFilterResult')"
      >
        <el-table-column :label="$t('message.pages.marketing.live.liveGoods.productId')" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.product_id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.live.liveGoods.productName')" min-width="120">
          <template slot-scope="scope">
            <div class="product_box">
              <div v-viewer>
                <img :src="scope.row.product.image" alt="" />
              </div>
              <div class="txt">{{ scope.row.name }}</div>
            </div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.live.liveGoods.livePrice')" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.price }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.live.liveGoods.originalPrice')" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.cost_price }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.live.liveGoods.stock')" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.product.stock }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.live.liveGoods.auditStatusCol')" min-width="80">
          <template slot-scope="scope">
            <div>{{ scope.row.audit_status | liveStatusFilter }}</div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.live.liveGoods.isShow')" min-width="80">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.is_show"
              :value="scope.row.is_show"
              @change="onchangeIsShow(scope.row)"
              size="large"
              :disabled="scope.row.audit_status != 2"
              :active-text="$t('message.pages.marketing.live.liveGoods.show')"
              :inactive-text="$t('message.pages.marketing.live.liveGoods.hide')"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.common.action')" fixed="right" width="170">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row, $t('message.pages.marketing.common.edit'))">{{ $t('message.pages.marketing.live.liveGoods.detail') }}</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, $t('message.pages.marketing.live.liveGoods.delConfirm'), scope.$index)">{{ $t('message.pages.marketing.live.liveGoods.del') }}</a>
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
    <!--详情-->
    <el-dialog :visible.sync="modals" :title="$t('message.pages.marketing.live.liveGoods.productDetail')" class="paymentFooter" scrollable width="720px">
      <goodsFrom ref="goodsDetail" :FormData="FormData" />
    </el-dialog>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { liveGoods, liveSyncGoods, liveGoodsDetail, liveGoodsShow } from '@/api/live';
import goodsFrom from './components/goods_detail';
export default {
  name: 'live',
  components: {
    goodsFrom,
  },
  data() {
    return {
      grid: {
        xl: 7,
        lg: 7,
        md: 12,
        sm: 24,
        xs: 24,
      },
      formValidate: {
        status: '',
        kerword: '',
        page: 1,
        limit: 20,
      },
      treeData: {
        withdrawal: [
          {
            title: '全部',
            value: '',
          },
          {
            title: '待审核',
            value: 0,
          },
          {
            title: '已通过',
            value: 1,
          },
          {
            title: '未通过',
            value: -1,
          },
        ],
      },
      columns1: [
        { key: 'product_id', title: '商品ID', minWidth: 35 },
        { slot: 'name', minWidth: 35, title: '商品名称' },
        { key: 'price', minWidth: 35, title: '直播价' },
        { slot: 'cost_price', minWidth: 35, title: '原价' },
        { slot: 'stock', minWidth: 35, title: '库存' },
        { slot: 'status', minWidth: 35, title: '审核状态' },
        { slot: 'is_mer_show', title: '是否显示', minWidth: 80 },
        // {"key": "sort", "title": "排序", "minWidth": 35},
        { slot: 'action', fixed: 'right', title: '操作', minWidth: 120 },
      ],
      tabList: [],
      loading: false,
      modals: false,
      total: 0,
      FormData: {},
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
  mounted() {
    this.getList();
  },
  methods: {
    // 直播间显示隐藏
    onchangeIsShow({ id, is_show }) {
      liveGoodsShow(id, is_show)
        .then((res) => {
          this.$message.success(res.msg);
        })
        .catch((error) => {
          this.$message.error(error.msg);
        });
    },
    // 列表数据
    getList() {
      this.loading = true;
      liveGoods(this.formValidate)
        .then((res) => {
          this.total = res.data.count;
          this.tabList = res.data.list;
          this.loading = false;
        })
        .catch((error) => {
          this.$message.error(error.msg);
          this.loading = false;
        });
    },
    // 选择
    selChange() {
      this.formValidate.page = 1;
      this.getList();
    },
    // 添加商品
    menusAdd() {
      this.$router.push({
        path: this.$routeProStr + '/marketing/live/add_live_goods',
      });
    },
    // 同步商品
    syncGoods() {
      liveSyncGoods()
        .then((res) => {
          this.$message.success(res.msg);
          this.getList();
        })
        .catch((error) => {
          this.$message.error(error.msg);
        });
    },
    edit(row) {
      liveGoodsDetail(row.id)
        .then((res) => {
          this.FormData = res.data;
          this.modals = true;
        })
        .catch((error) => {
          this.$message.error(error.msg);
        });
    },
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `live/goods/del/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.tabList.splice(num, 1);

          this.getList();
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.product_box {
  display: flex;
  align-items: center;
  img {
    width: 36px;
    height: 36px;
  }
  .txt {
    margin-left: 10px;
    color: #000;
    font-size: 12px;
  }
}
</style>
