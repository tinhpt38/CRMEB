<template>
  <div>
    <el-row class="expand-row">
      <el-col :span="6">
        <span class="expand-key">{{ $t('message.productTableExpand.firstVisit') }}：</span>
        <span class="expand-value"> {{ row.add_time | formatDate }}</span>
      </el-col>
      <el-col :span="6">
        <span class="expand-key">{{ $t('message.productTableExpand.lastVisit') }}：</span>
        <span class="expand-value">{{ row.last_time | formatDate }}</span>
      </el-col>
      <el-col :span="6">
        <span class="expand-key">{{ $t('message.productTableExpand.idCardNumber') }}：</span>
        <span class="expand-value">{{ row.card_id }}</span>
      </el-col>
      <el-col :span="6">
        <span class="expand-key">{{ $t('message.productTableExpand.realName') }}：</span>
        <span class="expand-value">{{ row.real_name }}</span>
      </el-col>
    </el-row>
    <el-row class="expand-row">
      <el-col :span="6">
        <span class="expand-key">{{ $t('message.productTableExpand.label') }}：</span>
        <span class="expand-value">{{ row.labels }}</span>
      </el-col>
      <el-col :span="6">
        <span class="expand-key">{{ $t('message.productTableExpand.birthday') }}：</span>
        <span class="expand-value">{{ row.birthday }}</span>
      </el-col>
      <el-col :span="6">
        <span class="expand-key">{{ $t('message.productTableExpand.referrer') }}：</span>
        <span class="expand-value">{{ row.spread_uid_nickname }}</span>
      </el-col>
      <el-col :span="6">
        <span class="expand-key">{{ $t('message.productTableExpand.address') }}：</span>
        <span class="expand-value">{{ row.addres }}</span>
      </el-col>
    </el-row>
    <el-row class="expand-row">
      <el-col :span="6">
        <span class="expand-key">{{ $t('message.productTableExpand.remark') }}：</span>
        <span class="expand-value">{{ row.mark }}</span>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import { formatDate } from '@/utils/validate';
export default {
  name: 'table-expand',
  filters: {
    formatDate(time) {
      if (time !== 0) {
        let date = new Date(time * 1000);
        return formatDate(date, 'yyyy-MM-dd hh:mm');
      }
    },
  },
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
