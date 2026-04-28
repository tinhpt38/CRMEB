<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mb-16" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="formValidate"
          :model="formValidate"
          :label-width="labelWidth"
          :label-position="labelPosition"
          @submit.native.prevent
          inline
        >
          <el-form-item label="Tên bìa：">
            <el-input
              clearable
              placeholder="Vui lòng nhập tên bảo vệ"
              v-model="formValidate.title"
              class="form_content_width"
              @change="userSearchs"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="userSearchs">Tìm kiếm</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never">
      <el-button v-auth="['cms-category-create']" type="primary" v-db-click @click="add">Thêm sự đảm bảo</el-button>

      <el-table
        :data="categoryList"
        ref="table"
        class="mt14"
        v-loading="loading"
        highlight-current-row
        row-key="id"
        :tree-props="{ children: 'children' }"
      >
        <el-table-column label="ID" prop="id" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Tên bìa" prop="title" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.title }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Hình ảnh đảm bảo" prop="image" min-width="130">
          <template slot-scope="scope">
            <div class="tabBox_img" v-viewer v-if="scope.row.image">
              <img v-lazy="scope.row.image" />
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Trạng thái" prop="status" min-width="120">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.status"
              :value="scope.row.status"
              @change="onchangeIsShow(scope.row)"
              size="large"
              active-text="Hoạt động"
              inactive-text="đóng cửa"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column label="Loại" prop="sort" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.sort }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Thao tác" width="120" fixed="right">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row)">Chỉnh sửa</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, 'Xóa bảo vệ')">Xóa</a>
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
  </div>
</template>
<script>
import { mapState, mapMutations } from 'vuex';
import {
  productProtectionFormApi,
  productProtectionInfoApi,
  productProtectionListApi,
  protectionStatusApi,
} from '@/api/product';
export default {
  name: 'articleCategory',
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
        page: 1,
        limit: 20,
        title: '',
      },
      status: '',
      total: 0,
      columns1: [
        {
          title: 'ID',
          key: 'id',
          width: 80,
        },
        {
          title: 'Tên bìa',
          key: 'title',
          minWidth: 130,
        },
        {
          title: 'Nội dung bảo vệ',
          key: 'content',
          minWidth: 130,
        },
        {
          title: 'biểu tượng',
          slot: 'images',
          minWidth: 130,
        },
        {
          title: 'Trạng thái',
          slot: 'statuss',
          minWidth: 130,
        },
        {
          title: 'loại',
          key: 'sort',
          minWidth: 130,
        },
        {
          title: 'Thao tác',
          slot: 'action',
          fixed: 'right',
          minWidth: 120,
        },
      ],
      FromData: null,
      modalTitleSs: '',
      categoryId: 0,
      categoryList: [],
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
  mounted() {
    this.getList();
  },
  methods: {
    ...mapMutations('userLevel', ['getCategoryId']),
    // Thêm vào
    add() {
      this.$modalForm(productProtectionFormApi(0)).then(() => this.getList());
    },
    // biên tập
    edit(row) {
      this.$modalForm(productProtectionFormApi(row.id)).then(() => this.getList());
    },
    // xóa bỏ
    del(row, tit) {
      let delfromData = {
        title: tit,
        num: 0,
        url: `product/protection/del/${row.id}`,
        method: 'DELETE',
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
    // danh sách
    getList() {
      this.loading = true;
      this.formValidate.status = this.status === 'all' ? '' : this.status;
      productProtectionListApi(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.categoryList = data.list;
          this.total = data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // tìm kiếm bảng
    userSearchs() {
      this.formValidate.page = 1;
      this.getList();
    },
    // Sửa đổi xem có hiển thị hay không
    onchangeIsShow(row) {
      let data = {
        id: row.id,
        status: row.status,
      };
      protectionStatusApi(data)
        .then(async (res) => {
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // Xem phạm vi bảo hiểm
    lookUp(row) {
      this.$router.push({
        path: this.$routeProStr + '/cms/article/index',
        query: {
          id: row.id,
        },
      });
      //xia mian chu cun mei yong;
      this.getCategoryId(row.id);
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
