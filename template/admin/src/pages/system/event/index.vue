<template>
  <div>
    <el-card :bordered="false" shadow="never">
      <el-alert type="warning" :closable="false">
        <template slot="title">
          Mô tả sự kiện tùy chỉnh：<br />
          1、Sự kiện mới sẽ được kích hoạt trong quá trình liên quan đến loại sự kiện tương ứng. Ví dụ: nếu chọn đăng nhập người dùng, mã sẽ được thực thi khi người dùng đăng nhập.。<br />
          2、Bạn có thể sử dụng các tham số tương ứng trong loại sự kiện tương ứng, ví dụ：$data['nickname']、$data['phone']Chờ đợi。<br />
          3、Hãy viết đường dẫn đầy đủ khi gọi lớp, ví dụ：\think\facade\Db、\app\services\other\CacheServices::classChờ đợi。<br />
        </template>
      </el-alert>
      <el-button type="primary" v-db-click @click="addTask" class="mt14">Thêm sự kiện hệ thống</el-button>
      <el-table :data="tableData" v-loading="loading" class="ivu-mt">
        <el-table-column label="Số seri" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Tên sự kiện" min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Loại sự kiện" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.mark_name }}</span>
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
              inactive-text="Ngưng hoạt động"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column label="Thời gian tạo" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Thao tác" width="100">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row.id)">Chỉnh sửa</a>
            <el-divider direction="vertical"></el-divider>
            <a v-permission="'seckill'" v-db-click @click="handleDelete(scope.row, 'Xóa sự kiện tùy chỉnh', scope.$index)"
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
import { eventIndex, eventShowTimer } from '@/api/system';
import creatTask from './createModal.vue';
import setting from '@/setting';
export default {
  name: 'system_event',
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
    this.apiBaseURL = setting.apiBaseURL;
    this.getList();
  },
  methods: {
    // danh sách
    getList() {
      this.loading = true;
      eventIndex({
        page: this.page,
        limit: this.limit,
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
      this.$refs.addTask.eventInfo();
    },
    edit(id) {
      this.$refs.addTask.eventInfo(id);
    },
    // xóa bỏ
    handleDelete(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `system/event/del/${row.id}`,
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
      eventShowTimer(id, is_open)
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
