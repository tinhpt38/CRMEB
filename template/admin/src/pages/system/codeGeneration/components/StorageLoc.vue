<template>
  <div class="main">
    <el-alert closable class="mb20">
      <template v-slot:title>{{ $t('message.systemMenus.crudFileLocationDescription') }}</template>
      <template>
        <p>{{ $t('message.systemMenus.fileLocationTip1') }}</p>
        <p>{{ $t('message.systemMenus.fileLocationTip2') }}</p>
        <p>{{ $t('message.systemMenus.fileLocationTip3') }}</p>
        <p>{{ $t('message.systemMenus.fileLocationTip4') }}</p>
        <p>{{ $t('message.systemMenus.fileLocationTip5') }}</p>
      </template>
    </el-alert>
    <el-form ref="foundation" :model="storage" :rules="storageRules" label-width="140px">
      <el-form-item :label="$t('message.systemMenus.generateControllerLocation') + '：'">
        <el-input class="form-width" v-model="storage.controller" disabled :placeholder="$t('message.common.pleaseInput')"></el-input>
        <div class="tip">{{ $t('message.systemMenus.controllerLocationTip') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.systemMenus.generateServiceLocation') + '：'">
        <el-input class="form-width" v-model="storage.service" disabled :placeholder="$t('message.common.pleaseInput')"></el-input>
        <div class="tip">{{ $t('message.systemMenus.serviceLocationTip') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.systemMenus.generateDaoLocation') + '：'">
        <el-input class="form-width" v-model="storage.dao" disabled :placeholder="$t('message.common.pleaseInput')"></el-input>
        <div class="tip">{{ $t('message.systemMenus.daoLocationTip') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.systemMenus.generateModelLocation') + '：'">
        <el-input class="form-width" v-model="storage.model" disabled :placeholder="$t('message.common.pleaseInput')"></el-input>
        <div class="tip">{{ $t('message.systemMenus.modelLocationTip') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.systemMenus.generateRouteLocation') + '：'">
        <el-input class="form-width" v-model="storage.route" disabled :placeholder="$t('message.common.pleaseInput')"></el-input>
        <div class="tip">{{ $t('message.systemMenus.routeLocationTip') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.systemMenus.generateValidateLocation') + '：'">
        <el-input class="form-width" v-model="storage.validate" disabled :placeholder="$t('message.common.pleaseInput')"></el-input>
        <div class="tip">{{ $t('message.systemMenus.validateLocationTip') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.systemMenus.generatePagesLocation') + '：'">
        <el-input class="form-width" v-model="storage.pages" disabled :placeholder="$t('message.common.pleaseInput')"></el-input>
        <div class="tip">{{ $t('message.systemMenus.pagesLocationTip') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.systemMenus.generateApiLocation') + '：'">
        <el-input class="form-width" v-model="storage.api" disabled :placeholder="$t('message.common.pleaseInput')"></el-input>
        <div class="tip">{{ $t('message.systemMenus.apiLocationTip') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.systemMenus.generateRouterLocation') + '：'">
        <el-input class="form-width" v-model="storage.router" disabled :placeholder="$t('message.common.pleaseInput')"></el-input>
        <div class="tip">{{ $t('message.systemMenus.routerLocationTip') }}</div>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
export default {
  name: '',
  props: {
    storage: {
      type: Object,
      default: () => {
        return {};
      },
    },
  },
  data() {
    return {
      storageRules: {},
    };
  },
  created() {},
  mounted() {},
  methods: {},
};
</script>
<style lang="scss" scoped>
.form-width {
  width: 500px;
}
</style>
