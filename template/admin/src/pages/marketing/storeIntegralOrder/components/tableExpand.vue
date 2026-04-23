<template>
  <div>
    <el-row class="expand-row">
      <el-col :span="8">
        <span class="expand-key">thời gian đặt hàng：</span>
        <span class="expand-value" v-text="row.add_time"></span>
      </el-col>
      <el-col :span="8">
        <span class="expand-key">Nhận xét của người dùng：</span>
        <span class="expand-value" v-text="row.mark ? row.mark : 'không có'"></span>
      </el-col>
      <el-col :span="8">
        <span class="expand-key">Nhận xét của người bán：</span>
        <span class="expand-value" v-text="row.remark ? row.remark : 'không có'"></span>
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
};
</script>

<style scoped>
.expand-row {
  margin-bottom: 16px;
}
</style>
