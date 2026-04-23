<template>
  <el-container class="layout-container">
    <Asides />
    <el-container class="flex-center layout-backtop">
      <Headers v-if="isFixedHeader" />
      <el-scrollbar ref="layoutDefaultsScrollbarRef">
        <Headers v-if="!isFixedHeader" />
        <Mains />
      </el-scrollbar>
    </el-container>
    <el-backtop target=".layout-backtop .el-scrollbar__wrap"></el-backtop>
  </el-container>
</template>

<script>
import Asides from '@/layout/component/aside.vue';
import Headers from '@/layout/component/header.vue';
import Mains from '@/layout/component/main.vue';
export default {
  name: 'layoutDefaults',
  components: { Asides, Headers, Mains },
  data() {
    return {};
  },
  computed: {
    // Có bật tính năng ghim hay không header
    isFixedHeader() {
      return this.$store.state.themeConfig.themeConfig.isFixedHeader;
    },
  },
  watch: {
    // Giám sát các thay đổi định tuyến
    $route: {
      handler() {
        this.$refs.layoutDefaultsScrollbarRef.wrap.scrollTop = 0;
      },
      deep: true,
    },
  },
};
</script>
