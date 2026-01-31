<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form ref="formValidate" :model="formValidate" inline label-width="80px" @submit.native.prevent>
          <el-form-item :label="$t('message.pages.setting.verifyOrder.verifyDate')">
            <el-date-picker
              clearable
              v-model="timeVal"
              type="daterange"
              :editable="false"
              @change="onchangeTime"
              format="yyyy/MM/dd"
              value-format="yyyy/MM/dd"
              :start-placeholder="$t('message.pages.setting.verifyOrder.startDate')"
              :end-placeholder="$t('message.pages.setting.verifyOrder.endDate')"
              :picker-options="pickerOptions"
              style="width: 250px"
              class="mr20"
            ></el-date-picker>
          </el-form-item>
          <el-form-item :label="$t('message.pages.setting.verifyOrder.filterCondition')">
            <el-input
              enter-button
              :placeholder="$t('message.pages.setting.verifyOrder.inputSearch')"
              v-model="formValidate.real_name"
              class="form_content_width"
            >
              <el-select v-model="field_key" slot="prepend" style="width: 100px">
                <el-option value="all" :label="$t('message.pages.setting.verifyOrder.all')"></el-option>
                <el-option value="order_id" :label="$t('message.pages.setting.verifyOrder.orderId')"></el-option>
                <el-option value="uid" :label="$t('message.pages.setting.verifyOrder.uid')"></el-option>
                <el-option value="real_name" :label="$t('message.pages.setting.verifyOrder.userName')"></el-option>
                <el-option value="user_phone" :label="$t('message.pages.setting.verifyOrder.userPhone')"></el-option>
                <el-option value="title" :label="$t('message.pages.setting.verifyOrder.productNameFuzzy')"></el-option>
              </el-select>
            </el-input>
          </el-form-item>
          <el-form-item :label="$t('message.pages.setting.verifyOrder.selectStore')">
            <el-select
              v-model="formValidate.store_id"
              element-id="store_id"
              clearable
              @change="userSearchs"
              class="form_content_width"
            >
              <el-option v-for="item in storeSelectList" :value="item.id" :key="item.id" :label="item.name"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="">
            <el-button type="primary" class="mr15" v-db-click @click="userSearchs">{{ $t('message.pages.setting.verifyOrder.search') }}</el-button>
            <!--            <el-button class="mr15" v-db-click @click="refresh">刷新</el-button>-->
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt mt16">
      <el-table
        :data="orderList"
        ref="table"
        v-loading="loading"
        highlight-current-row
        :empty-text="$t('message.pages.setting.verifyOrder.noData')"
        class="orderData"
      >
        <el-table-column :label="$t('message.pages.setting.verifyOrder.orderIdCol')" min-width="180">
          <template slot-scope="scope">
            <span>{{ scope.row.order_id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.verifyOrder.userInfo')" min-width="120">
          <template slot-scope="scope">
            <span>{{ scope.row.nickname }}/{{ scope.row.uid }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.verifyOrder.goodsInfo')" min-width="250">
          <template slot-scope="scope">
            <div class="tab" v-for="(item, i) in scope.row._info" :key="i">
              <img
                v-lazy="
                  item.cart_info.productInfo.attrInfo
                    ? item.cart_info.productInfo.attrInfo.image
                    : item.cart_info.productInfo.image
                "
              />
              <el-tooltip placement="top" :open-delay="300">
                <div slot="content">
                  <div>
                    <span>{{ $t('message.pages.setting.verifyOrder.productName') }}</span>
                    <span>{{ item.cart_info.productInfo.store_name || '--' }}</span>
                  </div>
                  <div>
                    <span>{{ $t('message.pages.setting.verifyOrder.specName') }}</span>
                    <span>{{
                      item.cart_info.productInfo.attrInfo ? item.cart_info.productInfo.attrInfo.suk : '---'
                    }}</span>
                  </div>
                  <div>
                    <span>{{ $t('message.pages.setting.verifyOrder.price') }}</span>
                    <span>¥{{ item.cart_info.truePrice || '--' }}</span>
                  </div>
                  <div>
                    <span>{{ $t('message.pages.setting.verifyOrder.num') }}</span>
                    <span>{{ item.cart_info.cart_num || '--' }}</span>
                  </div>
                </div>
                <span class="line2 w-250">{{ item.cart_info.productInfo.store_name }}</span>
              </el-tooltip>
            </div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.verifyOrder.actualPay')" min-width="90">
          <template slot-scope="scope">
            <span>{{ scope.row.pay_price }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.verifyOrder.verifyClerk')" min-width="90">
          <template slot-scope="scope">
            <span>{{ scope.row.clerk_name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.verifyOrder.verifyStore')" min-width="120">
          <template slot-scope="scope">
            <span>{{ scope.row.store_name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.verifyOrder.payStatus')" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.pay_type_name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.verifyOrder.orderStatus')" min-width="80">
          <template slot-scope="scope">
            <span> {{ scope.row.status_name.status_name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.verifyOrder.orderTime')" min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
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
    <referrer-info ref="info"></referrer-info>
  </div>
</template>

<script>
import { verifyOrderApi, merchantStoreListApi } from '@/api/setting';
import cardsData from '@/components/cards/cards';
import referrerInfo from '@/components/referrerInfo/index';
export default {
  name: 'setting_order',
  components: { cardsData, referrerInfo },
  data() {
    return {
      formValidate: {
        page: 1,
        limit: 15,
        data: '',
        real_name: '',
        store_id: '',
        field_key: '',
      },
      field_key: '',
      timeVal: [],
      storeSelectList: [],
      pickerOptions: this.$timeOptions,
      orderList: [],
      loading: false,
      total: 0,
    };
  },
  mounted() {
    this.getList();
    this.storeList();
  },
  methods: {
    getList() {
      let that = this;
      that.loading = true;
      that.formValidate.field_key = this.field_key === 'all' ? '' : this.field_key;
      verifyOrderApi(that.formValidate)
        .then((res) => {
          that.loading = false;
          that.orderList = res.data.data;
          that.total = res.data.count;
        })
        .catch((res) => {
          that.$message.error(res.msg);
        });
    },
    userSearchs() {
      this.formValidate.page = 1;
      this.getList();
    },
    // 具体日期
    onchangeTime(e) {
      this.timeVal = e;
      this.formValidate.data = this.timeVal ? this.timeVal.join('-') : '';
      this.getList();
    },
    // 选择时间
    selectChange(tab) {
      this.formValidate.page = 1;
      this.formValidate.data = tab;
      this.timeVal = [];
      this.getList();
    },
    storeList() {
      let that = this;
      merchantStoreListApi()
        .then((res) => {
          that.storeSelectList = res.data;
        })
        .catch((res) => {
          that.$message.error(res.msg);
        });
    },
    referenceInfo(uid) {
      this.$refs.info.isTemplate = true;
      this.$refs.info.verifySpreadInfo(uid);
    },
    refresh() {
      this.formValidate = {
        page: 1,
        limit: 15,
        data: '',
        real_name: '',
        store_id: '',
        field_key: '',
      };
      this.field_key = '';
      this.getList();
    },
  },
};
</script>

<style lang="scss" scoped>
img {
  height: 36px;
  display: block;
}
.tabBox {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  .tabBox_img {
    width: 36px;
    height: 36px;
    img {
      width: 100%;
      height: 100%;
    }
  }
  .tabBox_tit {
    width: 60%;
    font-size: 12px !important;
    margin: 0 2px 0 10px;
    letter-spacing: 1px;
    padding: 5px 0;
    box-sizing: border-box;
  }
}
.orderData ::v-deep .ivu-table-cell {
  padding-left: 0 !important;
}
.vertical-center-modal {
  display: flex;
  align-items: center;
  justify-content: center;
}
.ivu-mt {
  margin-bottom: 12px;
}
.ivu-mt a {
  color: #515a6e;
}
.ivu-mt a:hover {
  color: #2d8cf0;
}
.ivu-mt ::v-deep .ivu-form-item {
  padding: 7px 0;
  margin-bottom: 0;
}
.tab {
  display: flex;
  align-items: center;

  img {
    width: 36px;
    height: 36px;
    margin-right: 10px;
  }
}
.w-250 {
  max-width: 250px;
}
.w-120 {
  width: 120px;
}
</style>
