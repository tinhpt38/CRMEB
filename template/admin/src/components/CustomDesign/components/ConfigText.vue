<template>
  <div>
    <div class="section-title">{{ $t('message.customDesign.textSetting') }}</div>
    <el-form-item :label="$t('message.customDesign.textStyle')">
      <el-checkbox-group v-model="textShape" size="small">
        <el-checkbox label="bold">{{ $t('message.customDesign.bold') }}</el-checkbox>
        <el-checkbox label="italic">{{ $t('message.customDesign.italic') }}</el-checkbox>
      </el-checkbox-group>
    </el-form-item>
    <el-form-item :label="$t('message.customDesign.textDecoration')">
      <el-radio-group v-model="curComponent.propValue.textDecoration" size="small" @change="onChange">
        <el-radio label="none">{{ $t('message.customDesign.none') }}</el-radio>
        <el-radio label="underline">{{ $t('message.customDesign.underline') }}</el-radio>
        <el-radio label="line-through">{{ $t('message.customDesign.lineThrough') }}</el-radio>
      </el-radio-group>
    </el-form-item>
    <el-form-item :label="$t('message.customDesign.textAlign')">
      <el-radio-group v-model="curComponent.propValue.textAlign" size="small" @change="onChange">
        <el-radio-button label="start"><span class="iconfont iconzuoduiqi"></span></el-radio-button>
        <el-radio-button label="center"><span class="iconfont iconjuzhongduiqi"></span></el-radio-button>
        <el-radio-button label="end"><span class="iconfont iconyouduiqi"></span></el-radio-button>
      </el-radio-group>
    </el-form-item>
    <el-form-item :label="$t('message.customDesign.textColor')">
      <div class="row">
        <el-color-picker
          class="m-r-10"
          v-model="curComponent.propValue.color"
          @change="onChange"
          show-alpha
        ></el-color-picker>
        <el-input v-model="curComponent.propValue.color" />
        <span
          class="reset-color"
          @click="
            curComponent.propValue.color = '#000000';
            onChange();
          "
          >{{ $t('message.customDesign.reset') }}</span
        >
      </div>
    </el-form-item>
    <el-form-item :label="$t('message.customDesign.fontSize')">
      <div class="row">
        <el-slider
          v-model="curComponent.propValue.fontSize"
          :max="40"
          :min="8"
          @change="onChange"
          style="flex: 1; margin-right: 10px"
        ></el-slider>
        <el-input-number
          v-model="curComponent.propValue.fontSize"
          :max="40"
          :min="8"
          @change="onChange"
          style="width: 90px"
        ></el-input-number>
      </div>
    </el-form-item>
    <el-form-item :label="$t('message.customDesign.lineHeight')">
      <div class="row">
        <el-slider
          v-model="curComponent.propValue.lineHeight"
          :min="1"
          :max="5"
          :step="0.1"
          @change="onChange"
          style="flex: 1; margin-right: 10px"
        ></el-slider>
        <el-input-number
          v-model="curComponent.propValue.lineHeight"
          :min="1"
          :max="5"
          :step="0.1"
          @change="onChange"
          style="width: 90px"
        ></el-input-number>
      </div>
    </el-form-item>
    <el-form-item :label="$t('message.customDesign.ellipsis')">
      <el-radio-group v-model="curComponent.propValue.ellipsis" @change="onChange">
        <el-radio :label="0">{{ $t('message.customDesign.noLimit') }}</el-radio>
        <el-radio :label="1">{{ $t('message.customDesign.oneLine') }}</el-radio>
        <el-radio :label="2">{{ $t('message.customDesign.twoLine') }}</el-radio>
        <el-radio :label="3">{{ $t('message.customDesign.threeLine') }}</el-radio>
      </el-radio-group>
    </el-form-item>
    <el-form-item :label="$t('message.customDesign.textShadow')">
      <el-radio-group v-model="curComponent.propValue.showTextShadow" @change="onChange">
        <el-radio :label="false">{{ $t('message.customDesign.hidden') }}</el-radio>
        <el-radio :label="true">{{ $t('message.customDesign.show') }}</el-radio>
      </el-radio-group>
    </el-form-item>
    <template v-if="curComponent.propValue.showTextShadow">
      <el-form-item :label="$t('message.customDesign.shadowOffsetX')">
        <div class="row">
          <el-slider
            v-model="curComponent.propValue.shadowX"
            :min="-50"
            :max="50"
            :step="1"
            @change="onChange"
            style="flex: 1; margin-right: 10px"
          />
          <el-input-number
            v-model="curComponent.propValue.shadowX"
            :min="-50"
            :max="50"
            :step="1"
            @change="onChange"
            style="width: 90px"
          />
        </div>
      </el-form-item>
      <el-form-item :label="$t('message.customDesign.shadowOffsetY')">
        <div class="row">
          <el-slider
            v-model="curComponent.propValue.shadowY"
            :min="-50"
            :max="50"
            :step="1"
            @change="onChange"
            style="flex: 1; margin-right: 10px"
          />
          <el-input-number
            v-model="curComponent.propValue.shadowY"
            :min="-50"
            :max="50"
            :step="1"
            @change="onChange"
            style="width: 90px"
          />
        </div>
      </el-form-item>
      <el-form-item :label="$t('message.customDesign.blurRadius')">
        <div class="row">
          <el-slider
            v-model="curComponent.propValue.shadowBlur"
            :min="0"
            :max="50"
            :step="1"
            @change="onChange"
            style="flex: 1; margin-right: 10px"
          />
          <el-input-number
            v-model="curComponent.propValue.shadowBlur"
            :min="0"
            :max="50"
            :step="1"
            @change="onChange"
            style="width: 90px"
          />
        </div>
      </el-form-item>
      <el-form-item :label="$t('message.customDesign.shadowColor')">
        <div class="row">
          <el-color-picker class="m-r-10" v-model="curComponent.propValue.shadowColor" @change="onChange" show-alpha />
          <el-input v-model="curComponent.propValue.shadowColor" @change="onChange" />
          <span
            class="reset-color"
            @click="
              curComponent.propValue.shadowColor = 'rgba(0,0,0,0.5)';
              onChange();
            "
            >{{ $t('message.customDesign.reset') }}</span
          >
        </div>
      </el-form-item>
    </template>
  </div>
</template>

<script>
export default {
  name: 'ConfigText',
  props: {
    curComponent: {
      type: Object,
      required: true,
    },
  },
  watch: {
    'curComponent.id': {
      handler(val) {
        if (val && this.curComponent && this.curComponent.propValue) {
          const pv = this.curComponent.propValue;
          if (pv.showTextShadow === undefined) this.$set(pv, 'showTextShadow', false);
          if (pv.shadowX === undefined) this.$set(pv, 'shadowX', 0);
          if (pv.shadowY === undefined) this.$set(pv, 'shadowY', 0);
          if (pv.shadowBlur === undefined) this.$set(pv, 'shadowBlur', 0);
          if (pv.shadowColor === undefined) this.$set(pv, 'shadowColor', 'rgba(0,0,0,0.5)');
        }
      },
      immediate: true,
    },
  },
  computed: {
    textShape: {
      get() {
        const shapes = [];
        if (this.curComponent.propValue.fontWeight === 'bold') shapes.push('bold');
        if (this.curComponent.propValue.fontStyle === 'italic') shapes.push('italic');
        return shapes;
      },
      set(val) {
        this.curComponent.propValue.fontWeight = val.includes('bold') ? 'bold' : 'normal';
        this.curComponent.propValue.fontStyle = val.includes('italic') ? 'italic' : 'normal';
        this.onChange();
      },
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
.section-title {
  font-size: 14px;
  color: #333;
  margin-bottom: 18px;
  margin-top: 10px;
  width: calc(100% + 30px);
  margin-left: -15px;
  padding-left: 15px;
  padding-top: 20px;
  border-top: 6px solid #f0f2f5;
}
::v-deep .el-radio-button--small .el-radio-button__inner {
  padding: 7px 15px;
}
</style>
