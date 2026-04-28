<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mb-16" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="levelFrom"
          :model="formValidate"
          :label-width="labelWidth"
          :label-position="labelPosition"
          @submit.native.prevent
          inline
        >
          <el-form-item label="Kiểu trả lời：" prop="type" label-for="type">
            <el-select
              v-model="formValidate.type"
              placeholder="Vui lòng chọn"
              clearable
              @change="userSearchs"
              class="form_content_width"
            >
              <el-option value="text" label="tin nhắn văn bản"></el-option>
              <el-option value="image" label="tin nhắn hình ảnh"></el-option>
              <el-option value="news" label="Tin nhắn đồ họa"></el-option>
              <el-option value="voice" label="tin nhắn thoại"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Từ khóa：" prop="key" label-for="key">
            <el-input clearable v-model="formValidate.key" placeholder="Vui lòng nhập từ khóa" class="form_content_width" />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="userSearchs">Tìm kiếm</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-button type="primary" v-db-click @click="add">Thêm từ khóa</el-button>
      <el-table
        :data="tabList"
        ref="table"
        class="mt14"
        v-loading="loading"
        highlight-current-row
        no-userFrom-text="Chưa có dữ liệu"
        no-filtered-userFrom-text="Chưa có kết quả lọc nào"
      >
        <el-table-column label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Từ khóa" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.key }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Kiểu trả lời" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.typeName }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Có hiển thị hay không" min-width="130">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.status"
              :value="scope.row.status"
              @change="onchangeIsShow(scope.row)"
              size="large"
              active-text="trình diễn"
              inactive-text="trốn"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column label="Thao tác" fixed="right" width="170">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row)">Chỉnh sửa</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, 'Trả lời từ khóa', scope.$index)">Xóa</a>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="formValidate.page"
          :limit.sync="formValidate.limit"
          @pagination="getList"
        />
      </div>
    </el-card>
    <el-dialog :visible.sync="modal" title="Mã QR">
      <div class="acea-row row-around">
        <div class="acea-row row-column-around row-between-wrapper">
          <div v-viewer class="QRpic">
            <img v-lazy="qrcode" />
          </div>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { keywordListApi, keywordsetStatusApi, downloadReplyCode } from '@/api/app';
import { mapState } from 'vuex';
export default {
  name: 'keyword',
  data() {
    return {
      grid: {
        xl: 7,
        lg: 7,
        md: 12,
        sm: 24,
        xs: 24,
      },
      loading: false,
      formValidate: {
        key: '',
        type: '',
        page: 1,
        limit: 20,
      },
      tabList: [],
      total: 0,
      columns1: [
        {
          title: 'ID',
          key: 'id',
          width: 80,
        },
        {
          title: 'Từ khóa',
          key: 'key',
          minWidth: 120,
        },
        {
          title: 'Kiểu trả lời',
          key: 'type',
          minWidth: 150,
        },
        {
          title: 'Có hiển thị hay không',
          slot: 'status',
          minWidth: 120,
        },
        {
          title: 'Thao tác',
          slot: 'action',
          fixed: 'right',
          minWidth: 120,
        },
      ],
      modal: false,
      qrcode: '',
    };
  },
  created() {
    this.getList();
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
    // danh sách
    getList() {
      this.loading = true;
      keywordListApi(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tabList = data.list;
          this.total = res.data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // Sửa đổi xem có hiển thị hay không
    onchangeIsShow(row) {
      let data = {
        id: row.id,
        status: row.status,
      };
      keywordsetStatusApi(data)
        .then(async (res) => {
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // tìm kiếm bảng
    userSearchs() {
      this.formValidate.page = 1;
      this.getList();
    },
    // Thêm vào
    add() {
      this.$router.push({ path: this.$routeProStr + '/app/wechat/reply/keyword/save/0' });
    },
    // biên tập
    edit(row) {
      this.$router.push({ path: this.$routeProStr + '/app/wechat/reply/keyword/save/' + row.id });
    },
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `app/wechat/keyword/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.tabList.splice(num, 1);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // Tải xuống mã QR
    download(row) {
      downloadReplyCode(row.id)
        .then((res) => {
          this.modal = true;
          this.qrcode = res.data.url;
        })
        .catch((err) => {
          this.$message.error(err.msg);
        });
    },
  },
};
</script>

<style scoped>
.QRpic {
  width: 180px;
  height: 180px;
}

.QRpic img {
  width: 100%;
  height: 100%;
}
</style>
