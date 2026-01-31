<template>
  <div class="expand">
    <el-row class="expand-row">
      <el-col :span="6">
        <span class="expand-key">{{ $t('message.pages.order.expand.totalPrice') }}</span>
        <span class="expand-value" v-text="row.total_price"></span>
      </el-col>
      <el-col :span="6">
        <span class="expand-key">{{ $t('message.pages.order.expand.orderTime') }}</span>
        <span class="expand-value" v-text="row.add_time"></span>
      </el-col>
      <el-col :span="6">
        <span class="expand-key">{{ $t('message.pages.order.expand.promoter') }}</span>
        <span class="expand-value" v-text="row.spread_nickname ? row.spread_nickname : $t('message.pages.order.expand.none')"></span>
      </el-col>
      <el-col :span="6">
        <span class="expand-key">{{ $t('message.pages.order.expand.division') }}</span>
        <span class="expand-value" v-text="row.division_name ? row.division_name : $t('message.pages.order.expand.none')"></span>
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="6">
        <span class="expand-key">{{ $t('message.pages.order.expand.userRemark') }}</span>
        <span class="expand-value" v-text="row.mark ? row.mark : $t('message.pages.order.expand.none')"></span>
      </el-col>
      <el-col :span="6">
        <span class="expand-key">{{ $t('message.pages.order.expand.merchantRemark') }}</span>
        <span class="expand-value" v-text="row.remark ? row.remark : $t('message.pages.order.expand.none')"></span>
      </el-col>
      <el-col :span="6" v-if="row.shipping_type == 2">
        <span class="expand-key">{{ $t('message.pages.order.expand.verifyStore') }}</span>
        <span class="expand-value" v-text="row.verify_code ? row.store_name : $t('message.pages.order.expand.none')"></span>
      </el-col>
      <el-col :span="6" v-if="row.shipping_type == 2">
        <span class="expand-key">{{ $t('message.pages.order.expand.verifyCode') }}</span>
        <span class="expand-value" v-text="row.verify_code ? row.verify_code : $t('message.pages.order.expand.none')"></span>
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
.expand {
  /* padding-left: 50px; */
}
.expand-row {
  margin-bottom: 16px;
}
</style>
