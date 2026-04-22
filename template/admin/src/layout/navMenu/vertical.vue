<template>
  <div>
    <el-menu
      router
      :class="setColumnsAsideStyle"
      background-color="transparent"
      :default-active="activePath || defaultActive"
      :collapse="setIsCollapse"
      :unique-opened="getThemeConfig.isUniqueOpened"
      :collapse-transition="true"
    >
      <template v-for="val in menuList">
        <el-submenu :index="val.path" v-if="val.is_show && val.children && val.children.length > 0" :key="val.path">
          <template slot="title">
            <i class="ivu-icon" :class="val.icon ? 'el-icon-' + val.icon : ''"></i>
            <span>{{ resolveMenuTitle(val.title) }}</span>
          </template>
          <SubItem :chil="val.children" />
        </el-submenu>
        <template v-else-if="val.is_show">
          <el-menu-item :index="val.path" :key="val.path">
            <i class="ivu-icon" :class="val.icon ? 'el-icon-' + val.icon : ''"></i>
            <template slot="title" v-if="!val.isLink || (val.isLink && val.isIframe)">
              <span>{{ resolveMenuTitle(val.title) }}</span>
            </template>
            <template slot="title" v-else>
              <a :href="val.isLink" target="_blank">{{ resolveMenuTitle(val.title) }}</a>
            </template>
          </el-menu-item>
        </template>
      </template>
    </el-menu>
  </div>
</template>

<script>
import SubItem from '@/layout/navMenu/subItem.vue';
import { mapState } from 'vuex';

export default {
  name: 'navMenuVertical',
  components: { SubItem },
  props: {
    menuList: {
      type: Array,
      default() {
        return [];
      },
    },
  },
  data() {
    return {
      defaultActive: this.$route.path,
      onRoutes: '',
    };
  },
  computed: {
    ...mapState('menu', ['activePath']),
    // 设置分栏高亮风格
    setColumnsAsideStyle() {
      return this.$store.state.themeConfig.themeConfig.columnsAsideStyle;
    },
    // 获取布局配置信息
    getThemeConfig() {
      return this.$store.state.themeConfig.themeConfig;
    },
    // 设置左侧菜单是否展开/收起
    setIsCollapse() {
      return document.body.clientWidth < 1000 ? false : this.$store.state.themeConfig.themeConfig.isCollapse;
    },
  },
  watch: {
    // 监听路由的变化
    $route: {
      handler(to) {
        this.defaultActive = to.path;
        const clientWidth = document.body.clientWidth;
        if (clientWidth < 1000) this.$store.state.themeConfig.themeConfig.isCollapse = false;
      },
      deep: true,
    },
  },
  created() {},
  methods: {
    decodeRawI18nKey(value) {
      if (!value || typeof value !== 'string' || !value.startsWith('k_')) return '';
      try {
        const raw = value.slice(2);
        const base64 = raw.replace(/-/g, '+').replace(/_/g, '/').padEnd(Math.ceil(raw.length / 4) * 4, '=');
        return decodeURIComponent(escape(atob(base64)));
      } catch (e) {
        return '';
      }
    },
    resolveMenuTitle(title) {
      if (!title) return '';
      const key = String(title);
      if (this.$te(key)) return this.$t(key);

      const routerKey = `message.router.${key}`;
      if (this.$te(routerKey)) return this.$t(routerKey);

      const menuDbKey = `message.systemMenusDb.${key}`;
      if (this.$te(menuDbKey)) return this.$t(menuDbKey);

      const routeDbKey = `message.systemRouteDb.${key}`;
      if (this.$te(routeDbKey)) return this.$t(routeDbKey);

      return this.decodeRawI18nKey(key) || key;
    },
  },
};
</script>
<style lang="scss" scoped>
::v-deep .center {
  text-align: center;
  margin-right: 0 !important;
  margin-left: 5px;
}
// ::v-deep .el-submenu__title {
//   display: flex;
//   justify-content: center;
//   align-items: center;
// }
</style>
