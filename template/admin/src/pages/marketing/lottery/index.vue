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
        <el-scope.row :gutter="24">
          <el-col>
            <el-form-item :label="$t('message.pages.marketing.lottery.activityType')" clearable>
              <el-select
                style="width: 200px"
                v-model="tableFrom.factor"
                :placeholder="$t('message.pages.marketing.lottery.pleaseSelectType')"
                clearable
                @change="userSearchs"
              >
                <el-option value="1" :label="$t('message.pages.marketing.lottery.integralDraw')"></el-option>
                <el-option value="3" :label="$t('message.pages.marketing.lottery.orderPay')"></el-option>
                <el-option value="4" :label="$t('message.pages.marketing.lottery.orderReview')"></el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col>
            <el-form-item :label="$t('message.pages.marketing.lottery.activityStatus')" clearable>
              <el-select
                style="width: 200px"
                v-model="tableFrom.start_status"
                :placeholder="$t('message.pages.marketing.common.pleaseSelect')"
                clearable
                @change="userSearchs"
              >
                <el-option value="0" :label="$t('message.pages.marketing.lottery.statusNotStart')"></el-option>
                <el-option value="1" :label="$t('message.pages.marketing.lottery.statusOngoing')"></el-option>
                <el-option value="-1" :label="$t('message.pages.marketing.lottery.statusEnded')"></el-option>
              </el-select>
            </el-form-item>
          </el-col>

          <el-col>
            <el-form-item :label="$t('message.pages.marketing.lottery.onSaleStatus')">
              <el-select
                style="width: 200px"
                :placeholder="$t('message.pages.marketing.common.pleaseSelect')"
                v-model="tableFrom.status"
                clearable
                @change="userSearchs"
              >
                <el-option value="1" :label="$t('message.pages.marketing.storeCombination.onSale')"></el-option>
                <el-option value="0" :label="$t('message.pages.marketing.storeCombination.offSale')"></el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col>
            <el-form-item :label="$t('message.pages.marketing.lottery.lotterySearch')" label-for="store_name">
              <el-input
                search
                enter-button
                style="width: 200px"
                :placeholder="$t('message.pages.marketing.lottery.inputLotteryName')"
                v-model="tableFrom.store_name"
                @on-search="userSearchs"
              />
            </el-form-item>
          </el-col>
        </el-scope.row>
        <el-scope.row class="mb20">
          <el-button v-auth="['marketing-store_bargain-create']" type="primary" v-db-click @click="add" class="mr10"
            >{{ $t('message.pages.marketing.lottery.addLottery') }}</el-button
          >
        </el-scope.row>
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
        <el-table-column :label="$t('message.pages.marketing.lottery.activityName')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.lottery.activityTypeCol')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.lottery_type }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.lottery.participateCount')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.lottery_all }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.lottery.lotteryPeople')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.lottery_people }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.lottery.winCount')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.lottery_win }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.lottery.activityStatus')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.status_name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.lottery.onSaleStatus')" min-width="130">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.status"
              :value="scope.row.status"
              :disabled="scope.row.lottery_status == 2 ? true : false"
              @change="onchangeIsShow(scope.row)"
              size="large"
              :active-text="$t('message.pages.marketing.storeCombination.onSale')"
              :inactive-text="$t('message.pages.marketing.storeCombination.offSale')"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.lottery.activityTime')" min-width="130">
          <template slot-scope="scope">
            <div>{{ $t('message.pages.marketing.lottery.startLabel') }}{{ scope.row.start_time || '--' }}</div>
            <div>{{ $t('message.pages.marketing.lottery.endLabel') }}{{ scope.row.end_time || '--' }}</div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.common.action')" fixed="right" width="170">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row)">{{ $t('message.pages.marketing.lottery.edit') }}</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, $t('message.pages.marketing.lottery.delLotteryConfirm'), scope.$index)">{{ $t('message.pages.marketing.common.del') }}</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="copy(scope.row)">复制</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="getRecording(scope.row)">抽奖记录</a>
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
import { lotteryListApi, lotteryStatusApi } from '@/api/lottery';
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
      tableFrom: {
        start_status: '',
        status: '',
        store_name: '',
        export: 0,
        page: 1,
        factor: '',
        limit: 15,
      },
      total: 0,
    };
  },
  computed: {
    ...mapState('admin/layout', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '80px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  created() {
    this.getList();
  },
  methods: {
    // 添加
    add() {
      this.$router.push({ path: this.$routeProStr + '/marketing/lottery/create' });
    },
    // 编辑
    edit(row) {
      this.$router.push({
        name: 'marketing_create',
        query: {
          id: row.id,
        },
      });
    },
    // 一键复制
    copy(row) {
      this.$router.push({
        name: 'marketing_create',
        query: {
          id: row.id,
          copy: 1,
        },
      });
    },
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `marketing/lottery/del/${row.id}`,
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
    //查看抽奖记录
    getRecording(row) {
      this.$router.push({
        path: this.$routeProStr + `/marketing/lottery/recording_list`,
        query: {
          id: row.id,
        },
      });
    },
    // 列表
    getList() {
      this.loading = true;
      this.tableFrom.start_status = this.tableFrom.start_status || '';
      this.tableFrom.status = this.tableFrom.status || '';
      lotteryListApi(this.tableFrom)
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
      lotteryStatusApi(data)
        .then(async (res) => {
          this.$message.success(res.msg);
          this.getList();
        })
        .catch((res) => {
          this.$message.error(res.msg);
          this.getList();
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
