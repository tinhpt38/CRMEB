<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt" :body-style="{ padding: '0 20px' }">
      <div>
        <el-tabs v-model="currentTab" @tab-click="getList">
          <el-tab-pane
            :label="item.label"
            :name="item.value.toString()"
            v-for="(item, index) in headerList"
            :key="index"
          />
        </el-tabs>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never">
      <el-alert type="warning" :closable="false">
        <template slot="title">
          Hai cách để bắt đầu nhiệm vụ theo lịch trình：<br />
          1、Bắt đầu sử dụng lệnh: php think time start
          --d; Nếu bạn thay đổi chu kỳ thực hiện, cho dù tính năng chỉnh sửa được bật hay xóa tác vụ đã lên lịch, bạn cần khởi động lại tác vụ đã lên lịch để đảm bảo nó có hiệu lực.；<br />
          2、Sử dụng giao diện để kích hoạt các tác vụ theo lịch trình. Nên gọi nó mỗi phút một lần. Địa chỉ giao diện {{ apiBaseURL }}api/crontab/run <br />
        </template>
      </el-alert>
      <el-button v-if="currentTab === '1'" type="primary" v-db-click @click="addTask" class="mt14"
        >Thêm một nhiệm vụ theo lịch trình</el-button
      >
      <el-table :data="tableData" v-loading="loading" class="ivu-mt">
        <el-table-column label="Tiêu đề" min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Tuyên bố sứ mệnh" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.content }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Chu kỳ thực hiện" min-width="130">
          <template slot-scope="scope">
            <span>{{ taskTrip(scope.row) }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Có nên bật không" min-width="130">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.is_open"
              size="large"
              @change="handleChange(scope.row)"
              active-text="Hoạt động"
              inactive-text="đóng cửa"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column label="Thao tác" width="100">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row.id)">Chỉnh sửa</a>
            <el-divider direction="vertical" v-if="currentTab === '1'"></el-divider>
            <a
              v-if="currentTab === '1'"
              v-permission="'seckill'"
              v-db-click
              @click="handleDelete(scope.row, 'Xóa nhiệm vụ đã lên lịch', scope.$index)"
              >Xóa</a
            >
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination v-if="total" :total="total" :page.sync="page" :limit.sync="limit" @pagination="getList" />
      </div>
      <creatTask ref="addTask" :currentTab="currentTab" @submitAsk="getList"></creatTask>
    </el-card>
  </div>
</template>

<script>
import { timerIndex, showTimer } from '@/api/system';
import creatTask from './createModal.vue';
import setting from '@/setting';
export default {
  name: 'system_crontab',
  components: { creatTask },
  data() {
    return {
      loading: false,
      tableData: [],
      page: 1,
      limit: 15,
      total: 1,
      apiBaseURL: '',
      headerList: [
        { label: 'Nhiệm vụ hệ thống', value: '0' },
        { label: 'Nhiệm vụ tùy chỉnh', value: '1' },
      ],
      currentTab: '0',
    };
  },
  created() {
    this.apiBaseURL = setting.apiBaseURL.replace(/adminapi/, '');
    this.getList();
  },
  methods: {
    taskTrip(row) {
      switch (row.type) {
        case 1:
          return `mọi${row.second}Thực hiện một lần mỗi giây`;
        case 2:
          return `mọi${row.minute}Thực hiện mỗi phút một lần`;
        case 3:
          return `mọi${row.hour}Thực hiện mỗi giờ một lần`;
        case 4:
          return `mọi${row.day}Thực hiện mỗi ngày một lần`;
        case 5:
          return `mỗi ngày${row.hour}giờ${row.minute}điểm${row.second}Thực hiện một lần mỗi giây`;
        case 6:
          return `mỗi tuần${row.week}của${row.hour}giờ${row.minute}điểm${row.second}Thực hiện một lần mỗi giây`;
        case 7:
          return `mỗi tháng${row.day}tiếng Nhật${row.hour}giờ${row.minute}điểm${row.second}Thực hiện một lần mỗi giây`;
        case 8:
          return `mỗi năm${row.month}tháng${row.day}tiếng Nhật${row.hour}giờ${row.minute}điểm${row.second}Thực hiện một lần mỗi giây`;
      }
    },
    // danh sách
    getList() {
      this.loading = true;
      timerIndex({
        page: this.page,
        limit: this.limit,
        custom: this.currentTab === '1' ? 1 : 0,
      })
        .then((res) => {
          this.loading = false;
          let { count, list } = res.data;
          this.total = count;
          this.tableData = list;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    addTask() {
      this.$refs.addTask.timerInfo(0);
    },
    edit(id) {
      this.$refs.addTask.timerInfo(id);
    },
    // xóa bỏ
    handleDelete(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `system/crontab/del/${row.id}`,
        method: 'delete',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.getList();
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // Có nên bật không
    handleChange({ id, is_open }) {
      showTimer(id, is_open)
        .then((res) => {
          this.$message.success(res.msg);
          this.getList();
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.ivu-mt {
  padding-top: 10px;
}
</style>
