<template>
  <div class="customer">
    <el-form ref="formValidate" :model="formValidate" label-width="80px" inline @submit.native.prevent>
      <el-form-item :label="$t('message.customerInfo.searchUser')">
        <el-input
          clearable
          :placeholder="$t('message.customerInfo.searchPlaceholder')"
          v-model="formValidate.nickname"
          class="form_content_width"
        ></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" v-db-click @click="userSearchs">{{ $t('message.customerInfo.search') }}</el-button>
      </el-form-item>
    </el-form>
    <el-table
      class="mt15"
      v-loading="loading2"
      highlight-current-row
      :no-userFrom-text="$t('message.customerInfo.empty')"
      :no-filtered-userFrom-text="$t('message.customerInfo.noResult')"
      ref="selection"
      :data="tableList2"
      height="450"
    >
      <el-table-column width="50">
        <template slot-scope="scope">
          <el-radio
            v-model="currentid"
            :disabled="!!scope.row.is_del"
            :label="scope.row.uid"
            @input="() => currentidRadio(scope.row)"
            >&nbsp;</el-radio
          >
        </template>
      </el-table-column>
      <el-table-column label="UID" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.uid }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.customerInfo.avatar')" min-width="90">
        <template slot-scope="scope">
          <div class="tabBox_img" v-viewer>
            <img v-lazy="scope.row.headimgurl" />
          </div>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.customerInfo.nickname')" min-width="180">
        <template slot-scope="scope">
          <div>{{ scope.row.nickname }}</div>
          <div style="color: red">{{ scope.row.is_del ? $t('message.customerInfo.userDeleted') : '' }}</div>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.customerInfo.phone')" min-width="180">
        <template slot-scope="scope">
          <div>{{ scope.row.phone }}</div>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.customerInfo.subscribeStatus')" min-width="130">
        <template slot-scope="scope">
          <span v-text="scope.row.subscribe === 1 ? $t('message.customerInfo.subscribed') : $t('message.customerInfo.unsubscribed')"></span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.customerInfo.registerTime')" min-width="180">
        <template slot-scope="scope">
          <div>{{ scope.row.add_time }}</div>
        </template>
      </el-table-column>
    </el-table>
    <div class="acea-row row-right page">
      <pagination
        v-if="total2"
        :total="total2"
        :page.sync="formValidate.page"
        :limit.sync="formValidate.limit"
        @pagination="getListService"
      />
    </div>
  </div>
</template>
<script>
import { kefucreateApi } from '@/api/setting';
export default {
  name: 'index',
  data() {
    return {
      formValidate: {
        page: 1,
        limit: 15,
        data: '',
        nickname: '',
      },
      tableList2: [],
      timeVal: [],
      fromList: {
        title: this.$t('message.customerInfo.selectTime'),
        custom: true,
        fromTxt: [
          { text: this.$t('message.customerInfo.all'), val: '' },
          { text: this.$t('message.customerInfo.today'), val: 'today' },
          { text: this.$t('message.customerInfo.yesterday'), val: 'yesterday' },
          { text: this.$t('message.customerInfo.lately7'), val: 'lately7' },
          { text: this.$t('message.customerInfo.lately30'), val: 'lately30' },
          { text: this.$t('message.customerInfo.month'), val: 'month' },
          { text: this.$t('message.customerInfo.year'), val: 'year' },
        ],
      },
      currentid: 0,
      productRow: {},
      loading2: false,
      total2: 0,
    };
  },
  created() {},
  mounted() {
    this.getListService();
  },
  methods: {
    // 具体日期
    onchangeTime(e) {
      this.timeVal = e;
      this.formValidate.data = this.timeVal ? this.timeVal.join('-') : '';
      this.getListService();
    },
    // 选择时间
    selectChange(tab) {
      this.formValidate.data = tab;
      this.timeVal = [];
      this.getListService();
    },
    // 客服列表
    getListService() {
      this.loading2 = true;
      kefucreateApi(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tableList2 = data.list;
          this.total2 = data.count;
          this.tableList2.map((item) => {
            item._isChecked = false;
          });
          this.loading2 = false;
        })
        .catch((res) => {
          this.loading2 = false;
          this.$message.error(res.msg);
        });
    },
    // 搜索
    userSearchs() {
      this.formValidate.page = 1;
      this.getListService();
    },
    currentidRadio(row) {
      self.currentid = row.uid;
      this.productRow = row;
      if (this.productRow.uid) {
        if (this.$route.query.fodder === 'image') {
          /* eslint-disable */
          let imageObject = {
            image: this.productRow.headimgurl,
            uid: this.productRow.uid,
          };
          form_create_helper.set('image', imageObject);
          form_create_helper.close('image');
        } else {
          this.$emit('imageObject', {
            image: this.productRow.headimgurl,
            uid: this.productRow.uid,
          });
        }
      } else {
        this.$message.warning(this.$t('message.customerInfo.chooseFirst'));
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.customer {
  height: 100%;
  background-color: #fff;
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
.modelBox {
  ::v-deep,
  .ivu-table-header {
    width: 100% !important;
  }
}
.trees-coadd {
  width: 100%;
  height: 385px;
  .scollhide {
    width: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: scroll;
  }
}
.scollhide::-webkit-scrollbar {
  display: none;
}
.footer {
  margin: 15px 0;
  padding-right: 20px;
}
::v-deep .el-form--inline .el-form-item {
  margin-bottom: 0;
}
</style>
