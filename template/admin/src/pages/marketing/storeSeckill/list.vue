<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="tableFrom"
          :model="tableFrom"
          :label-width="labelWidth"
          label-position="right"
          @submit.native.prevent
          inline
        >
          <el-form-item :label="$t('message.pages.marketing.storeSeckill.list.activitySearch')" label-for="title">
            <el-input :placeholder="$t('message.pages.marketing.storeSeckill.list.inputActivityNameId')" v-model="tableFrom.title" class="form_content_width" />
          </el-form-item>
          <el-form-item :label="$t('message.pages.marketing.storeSeckill.list.activityStatus')">
            <el-select
              :placeholder="$t('message.pages.marketing.storeSeckill.list.pleaseSelect')"
              clearable
              v-model="tableFrom.status"
              @change="searchs"
              class="form_content_width"
            >
              <el-option value="1" :label="$t('message.pages.marketing.common.open')"></el-option>
              <el-option value="0" :label="$t('message.pages.marketing.common.close')"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('message.pages.marketing.storeSeckill.list.activityPeriod')">
            <el-select v-model="tableFrom.time_ids" multiple class="form_content_width" @change="searchs">
              <el-option v-for="item in timeList" :value="item.id" :key="item.id" :label="item.time_name"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('message.pages.marketing.storeSeckill.list.activityTime')">
            <el-date-picker
              clearable
              v-model="timeVal"
              type="daterange"
              :editable="false"
              @change="onchangeTime"
              format="yyyy/MM/dd"
              value-format="yyyy/MM/dd"
              :start-placeholder="$t('message.pages.marketing.common.startDate')"
              :end-placeholder="$t('message.pages.marketing.common.endDate')"
              style="width: 250px"
            ></el-date-picker>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="searchs">{{ $t('message.pages.marketing.storeSeckill.list.query') }}</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt mt16">
      <el-button v-auth="['marketing-store_seckill-create']" type="primary" v-db-click @click="add"
        >{{ $t('message.pages.marketing.storeSeckill.list.addSeckill') }}</el-button
      >
      <el-table
        :data="tableList"
        v-loading="loading"
        highlight-current-row
        :no-data-text="$t('message.pages.marketing.common.noData')"
        :no-filtered-data-text="$t('message.pages.marketing.common.noFilterResult')"
        class="mt14"
      >
        <el-table-column :label="$t('message.pages.marketing.common.id')" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeSeckill.list.activityTitle')" min-width="130">
          <template slot-scope="scope">
            <el-tooltip placement="top" :open-delay="600">
              <div slot="content">{{ scope.row.title }}</div>
              <span class="line2">{{ scope.row.title }}</span>
            </el-tooltip>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeSeckill.list.onceLimit')" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.once_num }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeSeckill.list.totalLimit')" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.num }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeSeckill.list.productCount')" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.product_count }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeSeckill.list.activityPeriodCol')" min-width="200">
          <template slot-scope="scope">
            <el-tag class="m2" v-for="(item, index) in scope.row.times_list" :key="index" effect="plain">
              {{ item }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeSeckill.list.activityTimeCol')" min-width="210">
          <template slot-scope="scope">
            <div>{{ $t('message.pages.marketing.storeSeckill.list.startLabel') }} {{ scope.row.start_day }}</div>
            <div>{{ $t('message.pages.marketing.storeSeckill.list.endLabel') }} {{ scope.row.end_day }}</div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeSeckill.list.statusCol')" min-width="100">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.status"
              :value="scope.row.status"
              @change="onchangeIsShow(scope.row)"
              size="large"
              :active-text="$t('message.pages.marketing.common.open')"
              :inactive-text="$t('message.pages.marketing.common.close')"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.common.action')" fixed="right" width="100">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row)">{{ $t('message.pages.marketing.common.edit') }}</a>
            <el-divider direction="vertical" />
            <a v-db-click @click="del(scope.row, $t('message.pages.marketing.storeSeckill.list.delSeckillConfirm'), scope.$index)">{{ $t('message.pages.marketing.common.del') }}</a>
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
import { seckillActivityListApi, seckillActivityStatusApi, storeSeckillApi, seckillTimeListApi } from '@/api/marketing';
import { formatDate } from '@/utils/validate';
import { exportSeckillList } from '@/api/export.js';

export default {
  name: 'marketing_storeSeckill',
  filters: {
    formatDate(time) {
      if (time !== 0) {
        let date = new Date(time * 1000);
        return formatDate(date, 'yyyy-MM-dd');
      }
    },
  },
  data() {
    return {
      loading: false,
      columns1: [
        {
          title: 'ID',
          key: 'id',
          width: 80,
        },
        {
          title: '商品图片',
          slot: 'image',
          minWidth: 90,
        },
        {
          title: '活动标题',
          key: 'title',
          minWidth: 130,
        },
        {
          title: '活动简介',
          key: 'info',
          minWidth: 100,
        },
        {
          title: '原价',
          key: 'ot_price',
          minWidth: 100,
        },
        {
          title: '秒杀价',
          key: 'price',
          minWidth: 100,
        },
        {
          title: '限量',
          key: 'quota_show',
          minWidth: 130,
        },
        {
          title: '限量剩余',
          key: 'quota',
          minWidth: 130,
        },
        {
          title: '秒杀状态',
          key: 'start_name',
          minWidth: 100,
        },
        {
          title: '结束时间',
          slot: 'stop_time',
          minWidth: 100,
        },
        {
          title: '状态',
          slot: 'status',
          minWidth: 100,
        },
        {
          title: '操作',
          slot: 'action',
          fixed: 'right',
          minWidth: 130,
        },
      ],
      tableList: [],
      timeList: [],
      grid: {
        xl: 7,
        lg: 10,
        md: 12,
        sm: 24,
        xs: 24,
      },
      tableFrom: {
        status: '',
        title: '',
        page: 1,
        limit: 15,
        time_ids: [],
        time: '',
      },
      total: 0,
      timeVal: [],
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
  activated() {
    this.getList();
    this.seckillTimeList();
  },
  methods: {
    seckillTimeList() {
      seckillTimeListApi()
        .then((res) => {
          this.timeList = res.data.list.data;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 具体日期
    onchangeTime(e) {
      this.timeVal = e;
      this.tableFrom.time = this.timeVal ? this.timeVal.join('-') : '';
      this.tableFrom.page = 1;
      if (!e[0]) {
        this.tableFrom.time = '';
      }
      this.getList();
    },
    // 添加
    add() {
      this.$router.push({ path: this.$routeProStr + '/marketing/store_seckill/create_more' });
    },
    // 导出
    async exports() {
      let [th, filekey, data, fileName] = [[], [], [], ''];
      let excelData = JSON.parse(JSON.stringify(this.tableFrom));
      excelData.page = 1;
      excelData.limit = 200;
      for (let i = 0; i < excelData.page + 1; i++) {
        let lebData = await this.getExcelData(excelData);
        if (!fileName) fileName = lebData.filename;
        if (!filekey.length) {
          filekey = lebData.fileKey;
        }
        if (!th.length) th = lebData.header;
        if (lebData.export.length) {
          data = data.concat(lebData.export);
          excelData.page++;
        } else {
          this.$exportExcel(th, filekey, fileName, data);
          return;
        }
      }
    },
    getExcelData(excelData) {
      return new Promise((resolve, reject) => {
        exportSeckillList(excelData).then((res) => {
          resolve(res.data);
        });
      });
    },

    // 编辑
    edit(row) {
      this.$router.push({
        path: this.$routeProStr + '/marketing/store_seckill/create_more/' + row.id,
      });
    },
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `marketing/seckill_activity/del/${row.id}`,
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
    viewInfo(row) {
      this.$router.push({
        path: this.$routeProStr + '/marketing/store_seckill/statistics/' + row.id,
      });
    },
    // 列表
    getList() {
      this.loading = true;
      this.tableFrom.status = this.tableFrom.status || '';
      seckillActivityListApi(this.tableFrom)
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
    searchs() {
      this.tableFrom.page = 1;
      this.getList();
    },
    // 修改是否显示
    onchangeIsShow(row) {
      let data = {
        id: row.id,
        status: row.status,
      };
      seckillActivityStatusApi(data)
        .then(async (res) => {
          this.getList();

          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
          row.status = !row.status;
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
