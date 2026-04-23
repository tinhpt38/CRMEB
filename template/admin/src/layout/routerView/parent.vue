<template>
  <div :class="isTagHistory ? 'h100' : 'h101'">
    <transition :name="setTransitionName" mode="out-in">
      <keep-alive :include="keepAliveNameList">
        <router-view :key="refreshRouterViewKey" />
      </keep-alive>
    </transition>
  </div>
</template>

<script>
export default {
  name: 'parent',
  data() {
    return {
      refreshRouterViewKey: null,
      keepAliveNameList: [],
      keepAliveNameNewList: [],
    };
  },
  computed: {
    // Đặt hình động chuyển đổi giao diện chính
    setTransitionName() {
      return this.$store.state.themeConfig.themeConfig.animation;
    },
    isTagHistory() {
      return this.$store.state.themeConfig.themeConfig.isTagsview;
    },
  },
  created() {
    /**
     * Nhận danh sách tên thành phần cần được giữ nguyên
     */
    this.keepAliveNameList = this.getKeepAliveNames();
    // Nghe sự kiện xem lộ trình làm mới chế độ xem tab
    this.bus.$on('onTagsViewRefreshRouterView', (path) => {
      // Nếu đường dẫn định tuyến hiện tại không bằng đường dẫn đến, hãy quay lại trực tiếpfalse
      if (this.$route.path !== path) return false;
      // Lọc tên thành phần tương ứng với tuyến đường hiện tại và đặt lại nókeepAliveNameList
      this.keepAliveNameList = this.getKeepAliveNames().filter((name) => this.$route.name !== name);
      // Làm mới chế độ xem định tuyếnkey
      this.refreshRouterViewKey = this.$route.path;
      // đặt lại ở tích tắc tiếp theokeepAliveNameList
      this.$nextTick(() => {
        this.refreshRouterViewKey = null;
        /**
         * Nhận danh sách tên thành phần cần được giữ nguyên
         */
        this.keepAliveNameList = this.getKeepAliveNames();
      });
    });
  },

  methods: {
    // Lấy danh sách bộ đệm tuyến đường (tên), tất cả các tuyến mặc định đều được lưu trữ
    getKeepAliveNames() {
      return this.$store.state.keepAliveNames.keepAliveNames;
    },
  },
};
</script>
