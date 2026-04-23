<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="artFrom"
          :model="artFrom"
          label-width="80px"
          label-position="right"
          class="tabform"
          @submit.native.prevent
          inline
        >
          <el-form-item label="Tìm kiếm thông số kỹ thuật：">
            <el-input
              clearable
              v-model="artFrom.rule_name"
              placeholder="Vui lòng nhập tên thông số kỹ thuật"
              class="form_content_width"
            ></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="userSearchs">Truy vấn</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt mt16">
      <el-button v-auth="['product-rule-save']" type="primary" v-db-click @click="addAttr">Thêm thông số kỹ thuật sản phẩm</el-button>
      <el-button v-auth="['product-product-rule-delete']" v-db-click @click="del(null, 'Xóa thông số kỹ thuật theo lô')"
        >Xóa hàng loạt</el-button
      >
      <el-table
        ref="table"
        :data="tableList"
        v-loading="loading"
        highlight-current-row
        :row-key="getRowKey"
        @selection-change="handleSelectRow"
        empty-text="Chưa có dữ liệu"
        class="mt14"
      >
        <el-table-column type="selection" width="60" :reserve-selection="true"> </el-table-column>
        <el-table-column label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Tên mẫu" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.rule_name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Tên đặc điểm kỹ thuật" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.attr_name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Thuộc tính sản phẩm" min-width="130">
          <template slot-scope="scope">
            <span
              v-for="(item, index) in scope.row.attr_value"
              :key="index"
              v-text="item"
              style="display: block"
            ></span>
          </template>
        </el-table-column>
        <el-table-column label="vận hành" fixed="right" width="120">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row)">biên tập</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, 'Xóa thông số kỹ thuật', scope.$index)">xóa bỏ</a>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="artFrom.page"
          :limit.sync="artFrom.limit"
          @pagination="getDataList"
        />
      </div>
    </el-card>
    <add-attr ref="addattr" @getList="userSearchs"></add-attr>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import addAttr from './addAttr';
import { ruleListApi } from '@/api/product';
export default {
  name: 'productAttr',
  components: { addAttr },
  data() {
    return {
      loading: false,
      artFrom: {
        page: 1,
        limit: 20,
        rule_name: '',
      },
      tableList: [],
      total: 0,
      selectedIds: new Set(), //Các mục đã hợp nhất đã chọnid
      ids: [],
      multipleSelection: [],
    };
  },
  computed: {
    ...mapState('admin/order', ['orderChartType']),
  },
  created() {
    this.getDataList();
  },
  methods: {
    getRowKey(row) {
      return row.id;
    },
    //Được kích hoạt khi chọn tất cả và bỏ chọn tất cả
    handleSelectAll(selection) {
      if (selection.length === 0) {
        //Lấy dữ liệu bảng；
        let data = this.$refs.table.data;
        data.forEach((item) => {
          if (this.selectedIds.has(item.id)) {
            this.selectedIds.delete(item.id);
          }
        });
      } else {
        selection.forEach((item) => {
          this.selectedIds.add(item.id);
        });
      }
      this.$nextTick(() => {
        //Hãy chắc chắn rằng dom đã được tải
        this.setChecked();
      });
    },
    //  Chọn một hàng
    handleSelectRow(selection) {
      const uniqueArr = [];
      const ids = [];
      for (let i = 0; i < selection.length; i++) {
        const item = selection[i];
        if (!ids.includes(item.id)) {
          uniqueArr.push(item);
          ids.push(item.id);
        }
      }
      this.selectedIds = ids;
      this.multipleSelection = uniqueArr;
      this.$nextTick((e) => {
        this.setChecked();
      });
    },
    setChecked() {
      //Sẽnew Set()Chuyển đổi thành mảng
      this.ids = [...this.selectedIds].join(',');
    },
    // xóa bỏ
    del(row, tit) {
      let data = {};
      if (tit === 'Xóa thông số kỹ thuật theo lô') {
        if (this.selectedIds.size === 0) return this.$message.warning('Vui lòng chọn thông số kỹ thuật cần xóa！');
        data = {
          ids: this.ids,
        };
      } else {
        data = {
          ids: row.id,
        };
      }
      let delfromData = {
        title: tit,
        num: 0,
        url: `product/product/rule/delete`,
        method: 'DELETE',
        ids: data,
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.getDataList();
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    addAttr() {
      this.$refs.addattr.modal = true;
    },
    // biên tập
    edit(row) {
      this.$refs.addattr.modal = true;
      this.$refs.addattr.getIofo(row);
    },
    // danh sách；
    getDataList() {
      this.loading = true;
      ruleListApi(this.artFrom)
        .then((res) => {
          let data = res.data;
          this.tableList = data.list;
          this.$nextTick(() => {
            //Hãy chắc chắn rằng dom đã được tải
            this.setChecked();
          });
          this.total = res.data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // tìm kiếm bảng
    userSearchs() {
      this.artFrom.page = 1;
      this.getDataList();
    },
  },
};
</script>

<style scoped></style>
