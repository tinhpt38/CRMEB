<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-row class="mb20">
        <el-col :span="24">
          <el-button v-auth="['setting-delivery_service-add']" type="primary" v-db-click @click="add" class="mr10"
            >{{ $t('message.pages.setting.deliveryService.addDelivery') }}</el-button
          >
        </el-col>
      </el-row>
      <el-table
        :data="data1"
        v-loading="loading"
        highlight-current-row
        :no-data-text="$t('message.pages.setting.deliveryService.noData')"
        :no-filtered-data-text="$t('message.pages.setting.deliveryService.noFilterResult')"
      >
        <el-table-column :label="$t('message.pages.setting.deliveryService.id')" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.deliveryService.avatar')" min-width="90">
          <template slot-scope="scope">
            <div class="tabBox_img" v-viewer>
              <img v-lazy="scope.row.avatar" />
            </div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.deliveryService.name')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.nickname }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.deliveryService.phone')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.phone }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.deliveryService.isShow')" min-width="130">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.status"
              :value="scope.row.status"
              @change="onchangeIsShow(scope.row)"
              size="large"
              :active-text="$t('message.pages.setting.deliveryService.show')"
              :inactive-text="$t('message.pages.setting.deliveryService.hide')"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.deliveryService.addTime')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.deliveryService.action')" fixed="right" width="100">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row)">{{ $t('message.pages.setting.deliveryService.edit') }}</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, $t('message.pages.setting.deliveryService.delDeliveryTitle'), scope.$index)">{{ $t('message.pages.setting.deliveryService.del') }}</a>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="tableOptions.page"
          :limit.sync="tableOptions.limit"
          @pagination="getOrderDeliveryList"
        />
      </div>
    </el-card>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { deliveryList, orderDeliveryAdd, orderDeliveryEdit, orderDeliveryStatus } from '@/api/order';

export default {
  name: 'index',
  computed: {
    ...mapState('media', ['isMobile']),
  },
  data() {
    return {
      data1: [],
      total: 0,
      tableOptions: {
        page: 1,
        limit: 15,
      },
      loading: false,
    };
  },
  created() {
    this.getOrderDeliveryList();
  },
  methods: {
    // 配送员列表
    getOrderDeliveryList() {
      this.loading = true;
      deliveryList(this.tableOptions)
        .then((res) => {
          this.data1 = res.data.list;
          this.total = res.data.count;
          this.loading = false;
        })
        .catch((err) => {
          this.loading = false;
          this.$message.error(err.msg);
        });
    },
    // 添加配送员
    add() {
      this.$modalForm(orderDeliveryAdd()).then(() => this.getOrderDeliveryList());
    },
    // 编辑
    edit(row) {
      this.$modalForm(orderDeliveryEdit(row.id)).then(() => this.getOrderDeliveryList());
    },
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `/order/delivery/del/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.data1.splice(num, 1);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 是否显示
    onchangeIsShow(row) {
      orderDeliveryStatus(row)
        .then((res) => {
          this.$message.success(res.msg);
        })
        .catch((err) => {
          this.$message.error(err.msg);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
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
