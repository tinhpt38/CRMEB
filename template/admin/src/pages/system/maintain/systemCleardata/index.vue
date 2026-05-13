<template>
  <div>
    <div class="i-layout-page-header header-title">
      <span class="ivu-page-header-title">{{ $route.meta.title }}</span>
      <span class="clear_tit">
        <i class="el-icon-info" style="color: #ed4014" />
        <span>Hãy cẩn thận khi xóa dữ liệu vì dữ liệu không thể được khôi phục sau khi xóa.！</span>
      </span>
    </div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-row :gutter="24">
        <el-col v-bind="grid" class="mb20" v-for="(item, index) in tabList" :key="index">
          <div class="clear_box">
            <span class="clear_box_sp1" v-text="item.title"></span>
            <span class="clear_box_sp2" v-text="item.tlt"></span>
            <el-button
              :type="item.typeName"
              v-text="item.typeName === 'primary' ? 'Thay thế ngay bây giờ' : 'Dọn dẹp ngay bây giờ'"
              v-db-click
              @click="onChange(item)"
            ></el-button>
          </div>
        </el-col>
      </el-row>
    </el-card>
    <!-- Thay đổi tên miền-->
    <el-dialog :visible.sync="modals" class="tableBox" title="Thay đổi tên miền" width="540px" :close-on-click-modal="false">
      <div class="acea-row row-column">
        <span>Hãy nhập tên miền cần thay thế theo dạng：http://tên miền。</span>
        <span>Quy tắc thay thế: hiện tại[cài đặt]bên trong[tên miền trang web]Thay thế nó bằng tên miền bạn hiện đang nhập.。</span>
        <span class="mb15">Thay thế sau khi thay thế thành công[tên miền trang web]。</span>
        <el-input v-model="value6" type="textarea" :rows="4" placeholder="Vui lòng nhập tên miền trang web..." />
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button v-db-click @click="modals = false">Hủy bỏ</el-button>
        <el-button type="primary" v-db-click @click="changeYU">Chắc chắn</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { replaceSiteUrlApi } from '@/api/system';
export default {
  name: 'systemCleardata',
  data() {
    return {
      value6: '',
      modals: false,
      grid: {
        xl: 6,
        lg: 8,
        md: 12,
        sm: 24,
        xs: 24,
      },
      tabList: [
        {
          title: 'Thay đổi tên miền',
          tlt: 'Thay thế Tất cả tên miền hình ảnh được tải lên cục bộ',
          typeName: 'primary',
          type: '11',
        },
        {
          title: 'Xóa các tệp đính kèm tạm thời do người dùng tạo',
          tlt: 'Xóa các tệp đính kèm tạm thời do người dùng tạo mà không ảnh hưởng đến hình ảnh sản phẩm',
          typeName: 'error',
          type: 'temp',
        },
        {
          title: 'Xóa các mục khỏi thùng rác',
          tlt: 'Xóa các mục khỏi thùng rác và tiến hành một cách thận trọng',
          typeName: 'error',
          type: 'recycle',
        },
        {
          title: 'Xóa dữ liệu người dùng',
          tlt: 'Tất cả các bảng liên quan đến người dùng sẽ bị xóa, hãy thận trọng khi tiến hành',
          typeName: 'error',
          type: 'user',
        },
        {
          title: 'Xóa dữ liệu cửa hàng',
          tlt: 'Xóa Tất cả dữ liệu trung tâm mua sắm và tiến hành thận trọng',
          typeName: 'error',
          type: 'store',
        },
        {
          title: 'Xóa danh mục sản phẩm',
          tlt: 'Tất cả các danh mục sản phẩm sẽ bị xóa, vui lòng Thao tác thận trọng',
          typeName: 'error',
          type: 'category',
        },
        {
          title: 'Xóa dữ liệu đơn hàng',
          tlt: 'Xóa Tất cả dữ liệu đặt hàng của người dùng, Thao tác thận trọng',
          typeName: 'error',
          type: 'order',
        },
        {
          title: 'Xóa dữ liệu dịch vụ khách hàng',
          tlt: 'Xóa dữ liệu dịch vụ khách hàng đã thêm và tiến hành một cách thận trọng',
          typeName: 'error',
          type: 'kefu',
        },
        {
          title: 'Xóa dữ liệu ứng dụng',
          tlt: 'Xóa menu ứng dụng, từ khóa trả lời không hợp lệ',
          typeName: 'error',
          type: 'wechat',
        },
        {
          title: 'Xóa danh mục nội dung',
          tlt: 'Xóa các bài viết và danh mục bài viết đã thêm,Tiến hành thận trọng',
          typeName: 'error',
          type: 'article',
        },
        {
          title: 'Xóa Tất cả tệp đính kèm',
          tlt: 'Xóa Tất cả các tệp đính kèm do người dùng tạo và tải lên trong nền,Tiến hành thận trọng',
          typeName: 'error',
          type: 'attachment',
        },
        {
          title: 'Xóa nhật ký hệ thống',
          tlt: 'Xóa nhật ký hệ thống,Tiến hành thận trọng',
          typeName: 'error',
          type: 'system',
        },
      ],
    };
  },
  methods: {
    // xóa dữ liệu
    onChange(item) {
      if (item.type === '11') {
        this.modals = true;
      } else {
        this.clearFroms(item);
      }
    },
    clearFroms(item) {
      let delfromData = {
        title: item.title,
        url: `system/clear/${item.type}`,
        method: 'get',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // Thay đổi tên miền
    changeYU() {
      replaceSiteUrlApi({ url: this.value6 })
        .then((res) => {
          this.modals = false;
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.clear_tit {
  align-items: center;
  margin: 15px;
  span {
    font-size: 14px;
    color: #ed4014;
  }
}
.clear_box {
  border: 1px solid #dadfe6;
  border-radius: 3px;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 30px 10px;
  box-sizing: border-box;
  .clear_box_sp1 {
    font-size: 16px;
    color: #000000;
    display: block;
  }
  .clear_box_sp2 {
    font-size: 14px;
    color: #808695;
    display: block;
    margin: 12px 0;
  }
}
.clear_box ::v-deep .ivu-btn-error {
  color: #fff;
  background-color: #ed4014;
  border-color: #ed4014;
}
.product_tabs ::v-deep .ivu-page-header-title {
  margin-bottom: 0 !important;
}
</style>
