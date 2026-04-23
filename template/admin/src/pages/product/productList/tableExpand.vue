<template>
  <div>
    <el-row class="expand-row">
      <el-col :span="6" class="mr20">
        <span class="expand-key">Phân loại sản phẩm：</span>
        <span class="expand-value">{{ row.cate_name }}</span>
      </el-col>
      <el-col :span="6">
        <span class="expand-key">giá thị trường hàng hóa：</span>
        <span class="expand-value">{{ row.ot_price }}</span>
      </el-col>
      <el-col :span="6">
        <span class="expand-key">giá thành：</span>
        <span class="expand-value">{{ row.cost }}</span>
      </el-col>
    </el-row>
    <el-row class="expand-row">
      <el-col :span="6" class="mr20">
        <span class="expand-key">sưu tầm：</span>
        <span class="expand-value">{{ row.collect }}</span>
      </el-col>
      <el-col :span="6">
        <span class="expand-key">bán hàng ảo：</span>
        <span class="expand-value">{{ row.ficti }}</span>
      </el-col>
    </el-row>
  </div>
</template>
<style scoped>
.expand-row {
  margin-bottom: 16px;
}
</style>
<script>
export default {
  props: {
    row: Object,
  },
};
</script>
