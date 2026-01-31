<template>
  <div>
    <el-dialog
      :visible.sync="modals"
      :title="listTitle === 'man' ? $t('message.pages.agent.promotersList.titleMan') : $t('message.pages.agent.promotersList.titleOrder')"
      :close-on-click-modal="false"
      width="1000px"
      @closed="onCancel"
    >
      <div class="table_box">
        <el-form
          ref="formValidate"
          :model="formValidate"
          :label-width="labelWidth"
          :label-position="labelPosition"
          @submit.native.prevent
          inline
        >
          <el-form-item :label="$t('message.pages.agent.promotersList.timeSelect')">
            <el-date-picker
              clearable
              :editable="false"
              @change="onchangeTime"
              v-model="timeVal"
              value-format="yyyy/MM/dd"
              type="daterange"
              :start-placeholder="$t('message.pages.agent.promotersList.startDate')"
              :end-placeholder="$t('message.pages.agent.promotersList.endDate')"
              style="width: 250px"
            ></el-date-picker>
          </el-form-item>
          <el-form-item :label="$t('message.pages.agent.promotersList.userType')">
            <el-select v-model="formValidate.type" clearable class="form_content_width">
              <el-option
                v-for="(item, i) in listTitle === 'man' ? fromList.fromTxt2 : fromList.fromTxt3"
                :key="i"
                :value="item.val"
                :label="$t('message.pages.agent.promotersList.' + item.textKey)"
              ></el-option>
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('message.pages.agent.promotersList.search')" v-if="listTitle === 'man'">
            <el-input
              clearable
              :placeholder="$t('message.pages.agent.promotersList.placeholderNamePhoneUid')"
              v-model="formValidate.nickname"
              class="form_content_width"
            ></el-input>
          </el-form-item>
          <el-form-item :label="$t('message.pages.agent.promotersList.orderNo')" v-if="listTitle === 'order'">
            <el-input
              clearable
              :placeholder="$t('message.pages.agent.promotersList.placeholderOrderNo')"
              v-model="formValidate.order_id"
              class="form_content_width"
            ></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="userSearchs">{{ $t('message.pages.agent.promotersList.query') }}</el-button>
          </el-form-item>
        </el-form>
      </div>
      <el-table
        ref="selection"
        :data="tabList"
        v-loading="loading"
        :empty-text="$t('message.pages.agent.promotersList.noData')"
        highlight-current-row
        max-height="400"
      >
        <template v-if="listTitle === 'man'">
          <el-table-column :label="$t('message.pages.agent.promotersList.uid')" width="80">
            <template slot-scope="scope">
              <span>{{ scope.row.uid }}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('message.pages.agent.promotersList.avatar')" min-width="90">
            <template slot-scope="scope">
              <div class="tabBox_img" v-viewer>
                <img v-lazy="scope.row.avatar ? scope.row.avatar : require('../../../assets/images/moren.jpg')" />
              </div>
            </template>
          </el-table-column>
          <el-table-column :label="$t('message.pages.agent.promotersList.userInfo')" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.nickname }}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('message.pages.agent.promotersList.isPromoter')" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.promoter_name }}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('message.pages.agent.promotersList.spreadCount')" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.spread_count }}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('message.pages.agent.promotersList.orderCount')" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.order_count }}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('message.pages.agent.promotersList.bindTime')" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.spread_time | formatDate }}</span>
            </template>
          </el-table-column>
        </template>
        <template v-else>
          <el-table-column :label="$t('message.pages.agent.promotersList.orderId')" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.order_id }}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('message.pages.agent.promotersList.userInfo')" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.user_info }}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('message.pages.agent.promotersList.time')" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row._add_time }}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('message.pages.agent.promotersList.brokerageAmount')" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.brokerage_price || 0 }}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('message.pages.agent.promotersList.divisionBrokerage')" min-width="130" v-if="rowsList.division_type == 1">
            <template slot-scope="scope">
              <span>{{ scope.row.division_brokerage || 0 }}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('message.pages.agent.promotersList.agentBrokerage')" min-width="130" v-if="rowsList.division_type == 2">
            <template slot-scope="scope">
              <span>{{ scope.row.agent_brokerage || 0 }}</span>
            </template>
          </el-table-column>
          <el-table-column :label="$t('message.pages.agent.promotersList.staffBrokerage')" min-width="130" v-if="rowsList.division_type == 3">
            <template slot-scope="scope">
              <span>{{ scope.row.staff_brokerage || 0 }}</span>
            </template>
          </el-table-column>
        </template>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="formValidate.page"
          :limit.sync="formValidate.limit"
          @pagination="pageChange"
        />
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { stairListApi } from '@/api/agent';
import { formatDate } from '@/utils/validate';
export default {
  name: 'promotersList',
  // props: {
  //     listTitle: {
  //         type: String,
  //         default: ''
  //     }
  // },
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
      modals: false,
      fromList: {
        title: '选择时间',
        custom: true,
        fromTxt: [
          { textKey: 'all', val: '' },
          { textKey: 'today', val: 'today' },
          { textKey: 'yesterday', val: 'yesterday' },
          { textKey: 'lately7', val: 'lately7' },
          { textKey: 'lately30', val: 'lately30' },
          { textKey: 'month', val: 'month' },
          { textKey: 'year', val: 'year' },
        ],
        fromTxt2: [
          { textKey: 'all', val: '' },
          { textKey: 'level1Promoter', val: 1 },
          { textKey: 'level2Promoter', val: 2 },
        ],
        fromTxt3: [
          { textKey: 'all', val: '' },
          { textKey: 'level1Order', val: 1 },
          { textKey: 'level2Order', val: 2 },
          { textKey: 'divisionOrder', val: 3 },
          { textKey: 'agentOrder', val: 4 },
        ],
      },
      formValidate: {
        limit: 15,
        page: 1,
        nickname: '',
        data: '',
        type: '',
        order_id: '',
        uid: 0,
      },
      loading: false,
      tabList: [],
      total: 0,
      timeVal: [],
      columns4: [],
      listTitle: '',
      rowsList: {
        division_type: 0,
      },
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
  methods: {
    onCancel() {
      this.formValidate = {
        limit: 15,
        page: 1,
        nickname: '',
        data: '',
        type: '',
        order_id: '',
        uid: 0,
      };
      this.timeVal = [];
      this.rowsList = { division_type: 0 };
    },
    // 具体日期
    onchangeTime(e) {
      this.timeVal = e;
      this.formValidate.data = this.timeVal ? this.timeVal.join('-') : '';
      this.getList(this.rowsList, this.listTitle);
    },
    // 选择时间
    selectChange(tab) {
      this.formValidate.data = tab;
      this.timeVal = [];
      this.getList(this.rowsList, this.listTitle);
    },
    // 列表
    getList(row, tit) {
      this.listTitle = tit;
      this.rowsList = row;
      this.loading = true;
      let url = '';
      if (this.listTitle === 'man') {
        url = 'agent/stair';
      } else {
        url = 'agent/stair/order';
      }
      this.formValidate.uid = row.uid;
      stairListApi(url, this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tabList = data.list;
          this.total = data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.tabList = [];
          this.$message.error(res.msg);
        });
    },
    pageChange() {
      this.getList(this.rowsList, this.listTitle);
    },
    // 搜索
    userSearchs() {
      this.formValidate.page = 1;
      this.getList(this.rowsList, this.listTitle);
    },
  },
};
</script>

<style scoped></style>
