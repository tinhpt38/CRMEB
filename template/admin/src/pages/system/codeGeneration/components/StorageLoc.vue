<template>
  <div class="main">
    <el-alert closable class="mb20">
      <template v-slot:title>{{ $t('message.systemCodeGen.crudFileLocationTitle') }}</template>
      <template>
        <p>{{ $t('message.systemCodeGen.crudFileLocationTip1') }}</p>
        <p>{{ $t('message.systemCodeGen.crudFileLocationTip2') }}</p>
        <p>{{ $t('message.systemCodeGen.crudFileLocationTip3') }}</p>
        <p>{{ $t('message.systemCodeGen.crudFileLocationTip4') }}</p>
        <p>{{ $t('message.systemCodeGen.crudFileLocationTip5') }}</p>
      </template>
    </el-alert>
    <el-form ref="foundation" :model="storage" :rules="storageRules" label-width="140px">
      <el-form-item :label="$t('message.systemCodeGen.controllerPathLabel')">
        <el-input class="form-width" v-model="storage.controller" disabled :placeholder="$t('systemCommon.inputRequired')"></el-input>
        <div class="tip">{{ $t('message.systemCodeGen.controllerPathTip') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.systemCodeGen.servicePathLabel')">
        <el-input class="form-width" v-model="storage.service" disabled :placeholder="$t('systemCommon.inputRequired')"></el-input>
        <div class="tip">{{ $t('message.systemCodeGen.servicePathTip') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.systemCodeGen.daoPathLabel')">
        <el-input class="form-width" v-model="storage.dao" disabled :placeholder="$t('systemCommon.inputRequired')"></el-input>
        <div class="tip">{{ $t('message.systemCodeGen.daoPathTip') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.systemCodeGen.modelPathLabel')">
        <el-input class="form-width" v-model="storage.model" disabled :placeholder="$t('systemCommon.inputRequired')"></el-input>
        <div class="tip">{{ $t('message.systemCodeGen.modelPathTip') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.systemCodeGen.routePathLabel')">
        <el-input class="form-width" v-model="storage.route" disabled :placeholder="$t('systemCommon.inputRequired')"></el-input>
        <div class="tip">{{ $t('message.systemCodeGen.routePathTip') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.systemCodeGen.validatePathLabel')">
        <el-input class="form-width" v-model="storage.validate" disabled :placeholder="$t('systemCommon.inputRequired')"></el-input>
        <div class="tip">{{ $t('message.systemCodeGen.validatePathTip') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.systemCodeGen.pagesPathLabel')">
        <el-input class="form-width" v-model="storage.pages" disabled :placeholder="$t('systemCommon.inputRequired')"></el-input>
        <div class="tip">{{ $t('message.systemCodeGen.pagesPathTip') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.systemCodeGen.apiPathLabel')">
        <el-input class="form-width" v-model="storage.api" disabled :placeholder="$t('systemCommon.inputRequired')"></el-input>
        <div class="tip">{{ $t('message.systemCodeGen.apiPathTip') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.systemCodeGen.routerPathLabel')">
        <el-input class="form-width" v-model="storage.router" disabled :placeholder="$t('systemCommon.inputRequired')"></el-input>
        <div class="tip">{{ $t('message.systemCodeGen.routerPathTip') }}</div>
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
