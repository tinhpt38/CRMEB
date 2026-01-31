<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form ref="tableFrom" :model="tableFrom" :label-width="labelWidth" label-position="right"
          @submit.native.prevent inline>
          <el-form-item :label="$t('message.pages.marketing.storeSeckill.productSearch')" label-for="store_name">
            <el-input :placeholder="$t('message.pages.marketing.storeSeckill.inputProductName')" v-model="tableFrom.store_name" class="form_content_width" />
          </el-form-item>
          <el-form-item :label="$t('message.pages.marketing.storeSeckill.activitySearch')" label-for="store_name">
            <el-input :placeholder="$t('message.pages.marketing.storeSeckill.inputActivityName')" v-model="tableFrom.activity_name" class="form_content_width" />
          </el-form-item>
          <el-form-item :label="$t('message.pages.marketing.storeSeckill.activityStatus')">
            <el-select :placeholder="$t('message.pages.marketing.common.pleaseSelect')" clearable v-model="tableFrom.status" @change="userSearchs"
              class="form_content_width">
              <el-option value="1" :label="$t('message.pages.marketing.common.open')"></el-option>
              <el-option value="0" :label="$t('message.pages.marketing.common.close')"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('message.pages.marketing.storeSeckill.activityTime')">
            <el-date-picker clearable v-model="timeVal" type="daterange" :editable="false" @change="onchangeTime"
              format="yyyy/MM/dd" value-format="yyyy/MM/dd" :start-placeholder="$t('message.pages.marketing.common.startDate')" :end-placeholder="$t('message.pages.marketing.common.endDate')"
              style="width: 250px"></el-date-picker>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="userSearchs">{{ $t('message.pages.marketing.common.query') }}</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt mt16">
      <el-button v-auth="['export-storeSeckill']" class="export" v-db-click @click="exports">{{ $t('message.pages.marketing.common.export') }}</el-button>
      <el-table :data="tableList" v-loading="loading" highlight-current-row :no-data-text="$t('message.pages.marketing.common.noData')"
        :empty-text="$t('message.pages.marketing.common.noData')" class="mt14">
        <el-table-column :label="$t('message.pages.marketing.common.id')" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeSeckill.productImage')" min-width="90">
          <template slot-scope="scope">
            <div class="tabBox_img" v-viewer>
              <img v-lazy="scope.row.image" />
            </div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeSeckill.productTitle')" min-width="130">
          <template slot-scope="scope">
            <el-tooltip placement="top" :open-delay="600">
              <div slot="content">{{ scope.row.title }}</div>
              <span class="line2">{{ scope.row.title }}</span>
            </el-tooltip>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeSeckill.productIntro')" min-width="100">
          <template slot-scope="scope">
            <el-tooltip placement="top" :open-delay="600">
              <div slot="content">{{ scope.row.info }}</div>
              <span class="line2">{{ scope.row.info }}</span>
            </el-tooltip>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeSeckill.activityName')" min-width="100">
          <template slot-scope="scope">
            <el-tooltip placement="top" :open-delay="600">
              <div slot="content">{{ scope.row.activity_name }}</div>
              <span class="line2">{{ scope.row.activity_name }}</span>
            </el-tooltip>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeSeckill.price')" min-width="90">
          <template slot-scope="scope">
            <span>{{ scope.row.product_price }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeSeckill.seckillPrice')" min-width="90">
          <template slot-scope="scope">
            <span>{{ scope.row.price }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeSeckill.limit')" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.quota_show }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeSeckill.limitRemain')" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.quota }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeSeckill.seckillStatus')" min-width="90">
          <template slot-scope="scope">
            <span>{{ scope.row.start_name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.storeSeckill.activityTimeCol')" min-width="190">
          <template slot-scope="scope">
            <p>{{ $t('message.pages.marketing.storeSeckill.start') }}{{ scope.row.start_time}}</p>
            <p>{{ $t('message.pages.marketing.storeSeckill.end') }}{{ scope.row.stop_time}}</p>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.common.status')" min-width="100">
          <template slot-scope="scope">
            <el-switch class="defineSwitch" :active-value="1" :inactive-value="0" v-model="scope.row.status"
              :value="scope.row.status" @change="onchangeIsShow(scope.row)" size="large" :active-text="$t('message.pages.marketing.common.open')"
              :inactive-text="$t('message.pages.marketing.common.close')">
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.common.action')" fixed="right" width="100">
          <template slot-scope="scope">
            <a v-db-click @click="del(scope.row, $t('message.pages.marketing.storeSeckill.delSeckill'), scope.$index)">{{ $t('message.pages.marketing.common.del') }}</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="viewInfo(scope.row)">{{ $t('message.pages.marketing.storeSeckill.statistics') }}</a>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination v-if="total" :total="total" :page.sync="tableFrom.page" :limit.sync="tableFrom.limit"
          @pagination="getList" />
      </div>
    </el-card>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { seckillListApi, seckillStatusApi, storeSeckillApi, seckillTimeListApi } from '@/api/marketing';
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
        store_name: '',
        page: 1,
        limit: 15,
        time_ids: [],
        time: '',
        activity_name: '',
      },
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
      this.$router.push({ path: this.$routeProStr + '/marketing/store_seckill/create' });
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
        path: this.$routeProStr + '/marketing/store_seckill/create/' + row.id + '/0',
      });
    },
    // 一键复制
    copy(row) {
      this.$router.push({
        path: this.$routeProStr + '/marketing/store_seckill/create/' + row.id + '/1',
      });
    },
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `marketing/seckill/${row.id}`,
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
      this.tableFrom.product_id = this.$route.params.product_id || '';
      seckillListApi(this.tableFrom)
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
      seckillStatusApi(data)
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
