<template>
  <div>
    <el-form-item :label="$t('message.customDesign.shadowSetting')">
      <el-radio-group v-model="curComponent.propValue.showShadow" @change="onChange">
        <el-radio :label="false">{{ $t('message.customDesign.hidden') }}</el-radio>
        <el-radio :label="true">{{ $t('message.customDesign.show') }}</el-radio>
      </el-radio-group>
    </el-form-item>
    <template v-if="curComponent.propValue.showShadow">
      <el-form-item :label="$t('message.customDesign.shadowColor')">
        <div class="row">
          <el-color-picker v-model="curComponent.propValue.shadowColor" @change="onChange" show-alpha></el-color-picker>
          <el-input
            v-model="curComponent.propValue.shadowColor"
            :placeholder="$t('message.customDesign.enterColor')"
            @change="onChange"
            style="margin-left: 10px; flex: 1"
          ></el-input>
          <span
            class="reset-color"
            @click="
              curComponent.propValue.shadowColor = '#000000';
              onChange();
            "
            >{{ $t('message.customDesign.reset') }}</span
          >
        </div>
      </el-form-item>
      <el-form-item :label="$t('message.customDesign.shadowOffsetX')">
        <div class="row">
          <el-slider
            v-model="curComponent.propValue.shadowX"
            :max="50"
            :min="-50"
            @change="onChange"
            style="flex: 1; margin-right: 10px"
          ></el-slider>
          <el-input-number
            v-model="curComponent.propValue.shadowX"
            :max="50"
            :min="-50"
            @change="onChange"
            style="width: 90px"
          ></el-input-number>
        </div>
      </el-form-item>
      <el-form-item :label="$t('message.customDesign.shadowOffsetY')">
        <div class="row">
          <el-slider
            v-model="curComponent.propValue.shadowY"
            :max="50"
            :min="-50"
            @change="onChange"
            style="flex: 1; margin-right: 10px"
          ></el-slider>
          <el-input-number
            v-model="curComponent.propValue.shadowY"
            :max="50"
            :min="-50"
            @change="onChange"
            style="width: 90px"
          ></el-input-number>
        </div>
      </el-form-item>
      <el-form-item :label="$t('message.customDesign.blurRadius')">
        <div class="row">
          <el-slider
            v-model="curComponent.propValue.shadowBlur"
            :max="50"
            :min="0"
            @change="onChange"
            style="flex: 1; margin-right: 10px"
          ></el-slider>
          <el-input-number
            v-model="curComponent.propValue.shadowBlur"
            :max="50"
            :min="0"
            @change="onChange"
            style="width: 90px"
          ></el-input-number>
        </div>
      </el-form-item>
      <el-form-item :label="$t('message.customDesign.spreadRadius')">
        <div class="row">
          <el-slider
            v-model="curComponent.propValue.shadowSpread"
            :max="50"
            :min="-50"
            @change="onChange"
            style="flex: 1; margin-right: 10px"
          ></el-slider>
          <el-input-number
            v-model="curComponent.propValue.shadowSpread"
            :max="50"
            :min="-50"
            @change="onChange"
            style="width: 90px"
          ></el-input-number>
        </div>
      </el-form-item>
    </template>
  </div>
</template>

<script>
export default {
  name: 'ConfigShadow',
  props: {
    curComponent: {
      type: Object,
      required: true,
    },
  },
  methods: {
    onChange() {
      this.$emit('change');
    },
  },
};
</script>

<style scoped lang="scss">
.row {
  display: flex;
  align-items: center;
}
</style>
