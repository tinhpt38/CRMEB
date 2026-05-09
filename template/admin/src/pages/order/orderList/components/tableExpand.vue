<template>
  <div class="expand">
    <el-row class="expand-row">
      <el-col :span="6">
        <span class="expand-key">Tổng giá sản phẩm:</span>
        <span class="expand-value" v-text="formatVnd(row.total_price)"></span>
      </el-col>
      <el-col :span="6">
        <span class="expand-key">Thời gian đặt hàng:</span>
        <span class="expand-value" v-text="row.add_time"></span>
      </el-col>
      <el-col :span="6">
        <span class="expand-key">Người giới thiệu:</span>
        <span class="expand-value" v-text="row.spread_nickname ? row.spread_nickname : 'không có'"></span>
      </el-col>
      <el-col :span="6">
        <span class="expand-key">Đơn vị kinh doanh:</span>
        <span class="expand-value" v-text="row.division_name ? row.division_name : 'không có'"></span>
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="6">
        <span class="expand-key">Lời nhắn người mua:</span>
        <span class="expand-value" v-text="row.mark ? row.mark : 'không có'"></span>
      </el-col>
      <el-col :span="6">
        <span class="expand-key">Ghi chú người bán:</span>
        <span class="expand-value" v-text="row.remark ? row.remark : 'không có'"></span>
      </el-col>
      <el-col :span="6" v-if="row.shipping_type == 2">
        <span class="expand-key">Điểm nhận hàng:</span>
        <span class="expand-value" v-text="row.verify_code ? row.store_name : 'không có'"></span>
      </el-col>
      <el-col :span="6" v-if="row.shipping_type == 2">
        <span class="expand-key">Mã xác nhận:</span>
        <span class="expand-value" v-text="row.verify_code ? row.verify_code : 'không có'"></span>
      </el-col>
    </el-row>
  </div>
</template>

<script>
export default {
  name: 'table-expand',
  props: {
    row: Object,
  },
  methods: {
    formatVnd(value) {
      const num = Number(value || 0);
      if (Number.isNaN(num)) return '--';
      return `${num.toLocaleString('vi-VN')} đ`;
    },
  },
};
</script>

<style scoped>
.expand {
  /* padding-left: 50px; */
}
.expand-row {
  margin-bottom: 16px;
}
</style>
