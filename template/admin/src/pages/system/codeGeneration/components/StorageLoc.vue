<template>
  <div class="main">
    <el-alert closable class="mb20">
      <template v-slot:title>crudTạo mô tả vị trí tệp</template>
      <template>
        <p>1、Vị trí của tệp được tạo không thể được sửa đổi nhiều nhất có thể. Bạn có thể tự di chuyển nó sau khi tạo.</p>
        <p>2、Vị trí tệp được tạo sẽ không chứa đường dẫn tuyệt đối của tệp nhưng sẽ mang đường dẫn tuyệt đối được đặt trước khi tạo tệp.</p>
        <p>3、Vị trí mặc định của tệp mẫu giao diện người dùng được định cấu hình trong admin_template_path của config/app.php</p>
        <p>4、Vị trí tạo mặc định của phần phụ trợ là: thư mục gốc của trang web</p>
        <p>5、Đảm bảo thư mục front-end/admin/src và thư mục ứng dụng back-end có quyền ghi và tạo tệp.</p>
      </template>
    </el-alert>
    <el-form ref="foundation" :model="storage" :rules="storageRules" label-width="140px">
      <el-form-item label="Tạo vị trí bộ điều khiển：">
        <el-input class="form-width" v-model="storage.controller" disabled placeholder="Vui lòng nhập"></el-input>
        <div class="tip">Tạo vị trí lưu trữ của tệp điều khiển phụ trợ</div>
      </el-form-item>
      <el-form-item label="Tạo vị trí dịch vụ：">
        <el-input class="form-width" v-model="storage.service" disabled placeholder="Vui lòng nhập"></el-input>
        <div class="tip">Tạo vị trí lưu trữ của tệp dịch vụ phụ trợ</div>
      </el-form-item>
      <el-form-item label="Tạo vị trí dao：">
        <el-input class="form-width" v-model="storage.dao" disabled placeholder="Vui lòng nhập"></el-input>
        <div class="tip">Tạo vị trí lưu trữ tệp dao phụ trợ</div>
      </el-form-item>
      <el-form-item label="Tạo vị trí mô hình：">
        <el-input class="form-width" v-model="storage.model" disabled placeholder="Vui lòng nhập"></el-input>
        <div class="tip">Tạo vị trí lưu trữ của tệp mô hình phụ trợ</div>
      </el-form-item>
      <el-form-item label="Tạo vị trí tuyến đường：">
        <el-input class="form-width" v-model="storage.route" disabled placeholder="Vui lòng nhập"></el-input>
        <div class="tip">Tạo vị trí lưu trữ tuyến đường phụ trợ</div>
      </el-form-item>
      <el-form-item label="Tạo vị trí xác thực：">
        <el-input class="form-width" v-model="storage.validate" disabled placeholder="Vui lòng nhập"></el-input>
        <div class="tip">Tạo vị trí lưu trữ trình xác thực phụ trợ</div>
      </el-form-item>
      <el-form-item label="Tạo vị trí trang：">
        <el-input class="form-width" v-model="storage.pages" disabled placeholder="Vui lòng nhập"></el-input>
        <div class="tip">Tạo vị trí lưu trữ tệp trang giao diện người dùng</div>
      </el-form-item>
      <el-form-item label="Tạo vị trí api：">
        <el-input class="form-width" v-model="storage.api" disabled placeholder="Vui lòng nhập"></el-input>
        <div class="tip">Tạo vị trí lưu trữ của tệp giao diện api giao diện người dùng</div>
      </el-form-item>
      <el-form-item label="Tạo vị trí bộ định tuyến：">
        <el-input class="form-width" v-model="storage.router" disabled placeholder="Vui lòng nhập"></el-input>
        <div class="tip">Tạo vị trí lưu trữ tuyến đường phía trước</div>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
export default {
  name: '',
  props: {
    storage: {
      type: Object,
      default: () => {
        return {};
      },
    },
  },
  data() {
    return {
      storageRules: {},
    };
  },
  created() {},
  mounted() {},
  methods: {},
};
</script>
<style lang="scss" scoped>
.form-width {
  width: 500px;
}
</style>
