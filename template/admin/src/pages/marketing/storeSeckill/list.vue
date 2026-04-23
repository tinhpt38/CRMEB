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
          <el-form-item label="Tìm kiếm hoạt động：" label-for="title">
            <el-input placeholder="Vui lòng nhập tên sự kiện，ID" v-model="tableFrom.title" clearable class="form_content_width" />
          </el-form-item>
          <el-form-item label="trạng thái hoạt động：">
            <el-select
              placeholder="Vui lòng chọn"
              clearable
              v-model="tableFrom.status"
              @change="searchs"
              class="form_content_width"
            >
              <el-option value="1" label="bật lên"></el-option>
              <el-option value="0" label="đóng cửa"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Thời gian hoạt động：">
            <el-select v-model="tableFrom.time_ids" multiple class="form_content_width" @change="searchs">
              <el-option v-for="item in timeList" :value="item.id" :key="item.id" :label="item.time_name"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Thời gian hoạt động：">
            <el-date-picker
              clearable
              v-model="timeVal"
              type="daterange"
              :editable="false"
              @change="onchangeTime"
              format="yyyy/MM/dd"
              value-format="yyyy/MM/dd"
              start-placeholder="ngày bắt đầu"
              end-placeholder="ngày kết thúc"
              style="width: 250px"
            ></el-date-picker>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="searchs">Truy vấn</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt mt16">
      <el-button v-auth="['marketing-store_seckill-create']" type="primary" v-db-click @click="add"
        >Thêm hoạt động flash sale</el-button
      >
      <el-table
        :data="tableList"
        v-loading="loading"
        highlight-current-row
        no-userFrom-text="Chưa có dữ liệu"
        no-filtered-userFrom-text="Chưa có kết quả lọc nào"
        class="mt14"
      >
        <el-table-column label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Tiêu đề sự kiện" min-width="130">
          <template slot-scope="scope">
            <el-tooltip placement="top" :open-delay="600">
              <div slot="content">{{ scope.row.title }}</div>
              <span class="line2">{{ scope.row.title }}</span>
            </el-tooltip>
          </template>
        </el-table-column>
        <el-table-column label="Giới hạn mua một lần" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.once_num }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Tổng số lượng mua giới hạn" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.num }}</span>
          </template>
        </el-table-column>
        <el-table-column label="số lượng sản phẩm" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.product_count }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Thời gian hoạt động" min-width="200">
          <template slot-scope="scope">
            <el-tag class="m2" v-for="(item, index) in scope.row.times_list" :key="index" effect="plain">
              {{ item }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column label="Thời gian hoạt động" min-width="210">
          <template slot-scope="scope">
            <div>bắt đầu: {{ scope.row.start_day }}</div>
            <div>Hoàn thành: {{ scope.row.end_day }}</div>
          </template>
        </el-table-column>
        <el-table-column label="tình trạng" min-width="100">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.status"
              :value="scope.row.status"
              @change="onchangeIsShow(scope.row)"
              size="large"
              active-text="bật lên"
              inactive-text="đóng cửa"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column label="vận hành" fixed="right" width="100">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row)">biên tập</a>
            <el-divider direction="vertical" />
            <a v-db-click @click="del(scope.row, 'Xóa hoạt động flash sale', scope.$index)">xóa bỏ</a>
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
          title: 'Hình ảnh sản phẩm',
          slot: 'image',
          minWidth: 90,
        },
        {
          title: 'Tiêu đề sự kiện',
          key: 'title',
          minWidth: 130,
        },
        {
          title: 'Giới thiệu hoạt động',
          key: 'info',
          minWidth: 100,
        },
        {
          title: 'giá gốc',
          key: 'ot_price',
          minWidth: 100,
        },
        {
          title: 'giá bán chớp nhoáng',
          key: 'price',
          minWidth: 100,
        },
        {
          title: 'phiên bản giới hạn',
          key: 'quota_show',
          minWidth: 130,
        },
        {
          title: 'Số lượng còn lại có hạn',
          key: 'quota',
          minWidth: 130,
        },
        {
          title: 'tình trạng bán hàng chớp nhoáng',
          key: 'start_name',
          minWidth: 100,
        },
        {
          title: 'thời gian kết thúc',
          slot: 'stop_time',
          minWidth: 100,
        },
        {
          title: 'tình trạng',
          slot: 'status',
          minWidth: 100,
        },
        {
          title: 'vận hành',
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
    // ngày cụ thể
    onchangeTime(e) {
      this.timeVal = e;
      this.tableFrom.time = this.timeVal ? this.timeVal.join('-') : '';
      this.tableFrom.page = 1;
      if (!e[0]) {
        this.tableFrom.time = '';
      }
      this.getList();
    },
    // Thêm vào
    add() {
      this.$router.push({ path: this.$routeProStr + '/marketing/store_seckill/create_more' });
    },
    // Xuất khẩu
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

    // biên tập
    edit(row) {
      this.$router.push({
        path: this.$routeProStr + '/marketing/store_seckill/create_more/' + row.id,
      });
    },
    // xóa bỏ
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
    // danh sách
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
    // tìm kiếm bảng
    searchs() {
      this.tableFrom.page = 1;
      this.getList();
    },
    // Sửa đổi xem có hiển thị hay không
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
