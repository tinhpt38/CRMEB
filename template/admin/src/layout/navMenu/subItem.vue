<!--
 * @Author: From-wh from-wh@hotmail.com
 * @Date: 2023-03-09 15:45:51
 * @FilePath: /admin/src/layout/navMenu/subItem.vue
 * @Description:
-->
<template>
  <div>
    <template v-for="val in chil">
      <el-submenu :index="val.path" :key="val.path" v-if="val.is_show && val.children && val.children.length > 0">
        <template slot="title">
          <i class="ivu-icon" :class="'el-icon-' + val.icon"></i>
          <span>{{ resolveMenuTitle(val.title) }}</span>
        </template>
        <sub-item :chil="val.children" />
      </el-submenu>
      <template v-else-if="val.is_show">
        <el-menu-item :index="val.path" :key="val.path">
          <template v-if="!val.isLink || (val.isLink && val.isIframe)">
            <i class="ivu-icon" :class="val.icon ? 'el-icon-' + val.icon : ''"></i>
            <span>{{ resolveMenuTitle(val.title) }}</span>
          </template>
          <template v-else>
            <a :href="val.isLink" target="_blank">
              <i class="ivu-icon" :class="val.icon ? 'el-icon-' + val.icon : ''"></i>
              {{ resolveMenuTitle(val.title) }}
            </a>
          </template>
        </el-menu-item>
      </template>
    </template>
  </div>
</template>

<script>
export default {
  name: 'subItem',
  props: {
    chil: {
      type: Array,
      default() {
        return [];
      },
    },
  },
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
