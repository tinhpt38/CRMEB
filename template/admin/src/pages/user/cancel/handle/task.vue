<template>
  <el-dialog :visible.sync="modals" title="Nhiệm vụ cấp độ" :close-on-click-modal="false" width="1000px" @closed="handleReset">
    <el-form
      ref="levelFrom"
      :model="levelFrom"
      :label-width="labelWidth"
      :label-position="labelPosition"
      @submit.native.prevent
    >
      <el-row :gutter="24">
        <el-col v-bind="grid">
          <el-form-item label="Trạng thái cấp độ：">
            <el-select v-model="levelFrom.is_show" placeholder="Có hiển thị hay không" clearable @change="userSearchs">
              <el-option value="1" label="trình diễn"></el-option>
              <el-option value="0" label="Không hiển thị"></el-option>
            </el-select>
          </el-form-item>
        </el-col>
        <el-col v-bind="grid">
          <el-form-item label="Tên cấp độ：" prop="status2" label-for="status2">
            <el-input
              search
              enter-button
              v-model="levelFrom.name"
              placeholder="Vui lòng nhập tên cấp độ"
              @on-search="userSearchs"
              style="width: 100%"
            />
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
    <el-divider direction="vertical" dashed />
    <el-row>
      <el-col v-bind="grid" class="mb15">
        <el-button type="primary" v-db-click @click="add">Thêm nhiệm vụ cấp độ</el-button>
      </el-col>
      <el-col :span="24" class="userAlert">
        <el-alert show-icon closable>
          <template slot="title">
            Thêm nhiệm vụ cấp độ,trong loại nhiệm vụ{$num}Nó sẽ được tự động thay thế bằng số lượng giới hạn + tên tác vụ tạo Đơn vị được hệ thống đặt trước
          </template>
        </el-alert>
      </el-col>
    </el-row>
    <el-divider direction="vertical" dashed />
    <el-table
      :data="levelLists"
      ref="table"
      v-loading="loading"
      no-userFrom-text="Chưa có dữ liệu"
      no-filtered-userFrom-text="Chưa có kết quả lọc nào"
    >
      <el-table-column label="ID" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Tên cấp độ" min-width="130">
        <template slot-scope="scope">
          <span>{{ scope.row.level_name }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Tên nhiệm vụ" min-width="130">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Có hiển thị hay không" min-width="130">
        <template slot-scope="scope">
          <el-switch
            class="defineSwitch"
            :active-value="1"
            :inactive-value="0"
            v-model="scope.row.is_show"
            :value="scope.row.is_show"
            size="large"
            @change="onchangeIsShow(scope.row)"
            active-text="trình diễn"
            inactive-text="trốn"
          >
          </el-switch>
        </template>
      </el-table-column>
      <el-table-column label="Phải đạt được" min-width="130">
        <template slot-scope="scope">
          <el-switch
            class="defineSwitch"
            :active-value="1"
            :inactive-value="0"
            v-model="scope.row.is_must"
            :value="scope.row.is_must"
            :true-value="1"
            :false-value="0"
            size="large"
            @change="onchangeIsMust(scope.row)"
            active-text="Tất cả"
            inactive-text="một"
          >
          </el-switch>
        </template>
      </el-table-column>
      <el-table-column label="Tuyên bố sứ mệnh" min-width="130">
        <template slot-scope="scope">
          <span>{{ scope.row.illustrate }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Thao tác" fixed="right" width="170">
        <template slot-scope="scope">
          <a v-db-click @click="edit(scope.row)">Biên tập | </a>
          <a v-db-click @click="del(scope.row, 'Xóa nhiệm vụ cấp độ', index)"> Xóa</a>
        </template>
      </el-table-column>
    </el-table>
    <div class="acea-row row-right page">
      <pagination
        v-if="total"
        :total="total"
        :page.sync="levelFrom.page"
        :limit.sync="levelFrom.limit"
        @pagination="getList"
      />
    </div>
    <!-- Tạo biểu mẫu chỉnh sửa mới-->
    <edit-from ref="edits" :FromData="FromData" @submitFail="submitFail" :titleType="titleType"></edit-from>
  </el-dialog>
</template>

<script>
import { mapState, mapMutations } from 'vuex';
import { taskListApi, setTaskShowApi, setTaskMustApi, createTaskApi } from '@/api/user';
import editFrom from '@/components/from/from';
export default {
  name: 'task',
  components: { editFrom },
  data() {
    return {
      grid: {
        xl: 10,
        lg: 10,
        md: 12,
        sm: 24,
        xs: 24,
      },
      modals: false,
      levelFrom: {
        is_show: '',
        name: '',
        page: 1,
        limit: 20,
      },
      total: 0,
      levelLists: [],
      loading: false,
      FromData: null,
      ids: 0,
      titleType: 'task',
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    ...mapState('userLevel', ['levelId']),
    labelWidth() {
      return this.isMobile ? undefined : '80px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  methods: {
    ...mapMutations('userLevel', ['getTaskId', 'getlevelId']),
    // Thêm vào
    add() {
      this.ids = '';
      this.getFrom();
    },
    // Tạo biểu mẫu chỉnh sửa mới
    getFrom() {
      let data = {
        id: this.ids,
        level_id: this.levelId,
      };
      this.$modalForm(createTaskApi(data)).then(() => this.getList());
    },
    // biên tập
    edit(row) {
      this.ids = row.id;
      this.getFrom();
    },
    // Đóng hộp phương thức
    handleReset() {
      this.modals = false;
    },
    // tìm kiếm bảng
    userSearchs() {
      this.getList();
    },
    // danh sách nhiệm vụ
    getList() {
      this.loading = true;
      this.levelFrom.is_show = this.levelFrom.is_show || '';
      taskListApi(this.levelId, this.levelFrom)
        .then(async (res) => {
          let data = res.data;
          this.levelLists = data.list;
          this.total = res.data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // Sửa đổi hiển thị ẩn
    onchangeIsShow(row) {
      let data = {
        id: row.id,
        is_show: row.is_show,
      };
      setTaskShowApi(data)
        .then(async (res) => {
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // Đặt xem nhiệm vụ có được hoàn thành hay không
    onchangeIsMust(row) {
      let data = {
        id: row.id,
        is_must: row.is_must,
      };
      setTaskMustApi(data)
        .then(async (res) => {
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // Trình soạn thảo mới đã được gửi thành công
    submitFail() {
      this.getList();
    },
    // Xóa nhiệm vụ
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `user/user_level/delete_task/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.levelLists.splice(num, 1);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
  },
};
</script>

<style scoped></style>
