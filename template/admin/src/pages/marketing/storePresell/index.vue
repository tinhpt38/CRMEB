<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-form
        ref="tableFrom"
        :model="tableFrom"
        :label-width="labelWidth"
        :label-position="labelPosition"
        @submit.native.prevent
      >
        <el-row :gutter="24">
          <el-col v-bind="grid">
            <el-form-item :label="$t('message.pages.marketing.storePresell.presellActivityStatus')">
              <el-select :placeholder="$t('message.pages.marketing.storePresell.pleaseSelectActivity')" v-model="tableFrom.time_type" clearable @change="userSearchs">
                <el-option value="0" :label="$t('message.pages.marketing.storePresell.all')"></el-option>
                <el-option value="1" :label="$t('message.pages.marketing.storePresell.statusNotStart')"></el-option>
                <el-option value="2" :label="$t('message.pages.marketing.storePresell.statusOngoing')"></el-option>
                <el-option value="3" :label="$t('message.pages.marketing.storePresell.statusEnded')"></el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col v-bind="grid">
            <el-form-item :label="$t('message.pages.marketing.storePresell.presellProductStatus')">
              <el-select :placeholder="$t('message.pages.marketing.storePresell.pleaseSelectProduct')" v-model="tableFrom.status" clearable @change="userSearchs">
                <el-option value="" :label="$t('message.pages.marketing.storePresell.all')"></el-option>
                <el-option value="1" :label="$t('message.pages.marketing.storePresell.onSale')"></el-option>
                <el-option value="0" :label="$t('message.pages.marketing.storePresell.offSale')"></el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col v-bind="grid">
            <el-form-item :label="$t('message.pages.marketing.storePresell.productSearch')" label-for="title">
              <el-input
                search
                enter-button
                :placeholder="$t('message.pages.marketing.storePresell.inputProductNameId')"
                v-model="tableFrom.title"
                @on-search="userSearchs"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row class="mb20">
          <el-col v-bind="grid">
            <el-button
              v-auth="['marketing-store_bargain-create']"
              type="primary"
              icon="md-add"
              v-db-click
              @click="add"
              class="mr10"
              >{{ $t('message.pages.marketing.storePresell.addPresellProduct') }}</el-button
            >
            <!-- <el-button
              v-auth="['export-storeBargain']"
              class="export"
              icon="ios-share-outline"
              v-db-click @click="exports"
              >导出</el-button
            > -->
          </el-col>
        </el-row>
      </el-form>
      <el-table
        :data="tableList"
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
        <el-table-column :label="$t('message.pages.marketing.storePresell.productImage')" min-width="90">
          <template slot-scope="scope">
            <div class="tabBox_img" v-viewer>
              <img v-lazy="scope.row.image" />
            </div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storePresell.presellName')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.title }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storePresell.presellPrice')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.price }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storePresell.soldCount')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.sales }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storePresell.limit')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.quota_show }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storePresell.limitRemain')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.quota }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storePresell.activityTime')" min-width="130">
          <template slot-scope="scope">
            <div>{{ $t('message.pages.marketing.storePresell.startLabel') }}{{ scope.row.start_time | formatDate }}</div>
            <div>{{ $t('message.pages.marketing.storePresell.endLabel') }}{{ scope.row.stop_time | formatDate }}</div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storePresell.presellStatus')" min-width="130">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.status"
              :value="scope.row.status"
              @change="onchangeIsShow(scope.row)"
              size="large"
              :active-text="$t('message.pages.marketing.storePresell.onSale')"
              :inactive-text="$t('message.pages.marketing.storePresell.offSale')"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.common.action')" fixed="right" width="170">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row)">{{ $t('message.pages.marketing.storePresell.edit') }}</a>
            <el-divider v-if="scope.row.stop_status === 0" direction="vertical" />
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, $t('message.pages.marketing.storePresell.delPresellConfirm'), scope.$index)">{{ $t('message.pages.marketing.common.del') }}</a>
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
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { presellListApi, advanceSetStatusApi, stroeBargainApi } from '@/api/marketing';
import { formatDate } from '@/utils/validate';
export default {
  name: 'storeBargain',
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
      loading: false,
      tableList: [],
      grid: {
        xl: 7,
        lg: 10,
        md: 12,
        sm: 24,
        xs: 24,
      },
      tableFrom: {
        status: '',
        time_type: 0,
        title: '',
        page: 1,
        limit: 15,
      },
      tableFrom2: {
        status: '',
        store_name: '',
        export: 1,
      },
      total: 0,
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '100px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'left';
    },
  },
  created() {
    this.getList();
  },
  methods: {
    // 添加
    add() {
      this.$router.push({ path: this.$routeProStr + '/marketing/presell/create/0' });
    },
    // 导出
    exports() {
      let formValidate = this.tableFrom;
      let data = {
        status: formValidate.status,
        store_name: formValidate.store_name,
      };
      stroeBargainApi(data)
        .then((res) => {
          location.href = res.data[0];
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 编辑
    edit(row) {
      this.$router.push({
        path: this.$routeProStr + '/marketing/presell/create/' + row.id + '/0',
      });
    },
    // 一键复制
    copy(row) {
      this.$router.push({
        path: this.$routeProStr + '/marketing/presell/create/' + row.id + '/1',
      });
    },
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `marketing/advance/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.tableList.splice(num, 1);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 列表
    getList() {
      this.loading = true;
      this.tableFrom.status = this.tableFrom.status || '';
      presellListApi(this.tableFrom)
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
    // 修改是否显示
    onchangeIsShow(row) {
      let data = {
        id: row.id,
        status: row.status,
      };
      advanceSetStatusApi(data)
        .then(async (res) => {
          this.getList();
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
