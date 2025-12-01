<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="formValidate"
          :model="formValidate"
          :label-width="labelWidth"
          :label-position="labelPosition"
          class="tabform"
          @submit.native.prevent
          inline
        >
          <el-form-item :label="$t('message.setting.messageTime') + '：'">
            <el-date-picker
              clearable
              v-model="timeVal"
              type="daterange"
              :editable="false"
              @change="onchangeTime"
              format="yyyy/MM/dd"
              value-format="yyyy/MM/dd"
              :start-placeholder="$t('message.setting.startDate')"
              :end-placeholder="$t('message.setting.endDate')"
              :picker-options="pickerOptions"
              style="width: 250px"
              class="mr20"
            ></el-date-picker>
          </el-form-item>
          <el-form-item :label="$t('message.setting.messageInfo') + '：'">
            <el-input
              clearable
              :placeholder="$t('message.setting.pleaseInputUserNicknamePhoneOrMessageContent')"
              v-model="formValidate.title"
              class="form_content_width"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="selChange">{{ $t('message.setting.query') }}</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt mt16">
      <el-table :data="list" v-loading="loading" :empty-text="$t('message.common.noData')">
        <el-table-column :label="$t('message.common.id')" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.setting.nickname')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.rela_name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.setting.phone')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.phone }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.setting.content')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.content }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.setting.status')" min-width="130">
          <template slot-scope="scope">
            <div>{{ scope.row.status === 1 ? $t('message.setting.processed') : $t('message.setting.unprocessed') }}</div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.setting.time')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.common.operation')" fixed="right" width="170">
          <template slot-scope="scope">
            <a v-db-click @click="remarks(scope.row.id)">{{ scope.row.status === 1 ? $t('message.setting.remark') : $t('message.setting.process') }}</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, $t('message.setting.deleteFeedback'), scope.$index)">{{ $t('message.setting.delete') }}</a>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination v-if="total" :total="total" :page.sync="page" :limit.sync="limit" @pagination="getList" />
      </div>
    </el-card>
  </div>
</template>

<script>
import { kefuFeedBack, kefuFeedBackEdit } from '@/api/setting';
import { mapState } from 'vuex';
export default {
  name: 'feedback',
  data() {
    return {
      loading: false,
      list: [],
      page: 1,
      limit: 15,
      formValidate: {
        time: '',
        title: '',
      },
      pickerOptions: this.$timeOptions,
      timeVal: [],
      total: 0,
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
  created() {
    this.getList();
  },
  methods: {
    //备注；
    remarks(id) {
      this.$modalForm(kefuFeedBackEdit(id)).then(() => this.getList());
    },
    // 选择
    selChange() {
      this.page = 1;
      this.getList();
    },
    // 选择时间
    selectChange(tab) {
      this.formValidate.time = tab;
      this.timeVal = [];
      this.page = 1;
      this.getList();
    },
    // 具体日期
    onchangeTime(e) {
      this.timeVal = e;
      this.formValidate.time = this.timeVal ? this.timeVal.join('-') : '';
      this.page = 1;
      this.getList();
    },
    getList() {
      kefuFeedBack({
        page: this.page,
        limit: this.limit,
        time: this.formValidate.time,
        title: this.formValidate.title,
      }).then((res) => {
        this.list = res.data.data;
        this.total = res.data.count;
      });
    },
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `/app/feedback/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.list.splice(num, 1);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
  },
};
</script>

<style scoped></style>
