<template>
  <div>
    <el-row class="expand-row">
      <el-col :span="6" class="mr20">
        <span class="expand-key">{{ $t('message.pages.product.expand.productCategory') }}</span>
        <span class="expand-value">{{ row.cate_name }}</span>
      </el-col>
      <el-col :span="6">
        <span class="expand-key">{{ $t('message.pages.product.expand.marketPrice') }}</span>
        <span class="expand-value">{{ row.ot_price }}</span>
      </el-col>
      <el-col :span="6">
        <span class="expand-key">{{ $t('message.pages.product.expand.costPrice') }}</span>
        <span class="expand-value">{{ row.cost }}</span>
      </el-col>
    </el-row>
    <el-row class="expand-row">
      <el-col :span="6" class="mr20">
        <span class="expand-key">{{ $t('message.pages.product.expand.collect') }}</span>
        <span class="expand-value">{{ row.collect }}</span>
      </el-col>
      <el-col :span="6">
        <span class="expand-key">{{ $t('message.pages.product.expand.virtualSales') }}</span>
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
