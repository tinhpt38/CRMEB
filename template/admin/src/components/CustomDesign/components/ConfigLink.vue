<template>
  <div>
    <el-form-item :label="$t('message.customDesign.jumpLink')">
      <div v-if="['article', 'goods'].includes(type) && showLinkType">
        <el-radio-group v-model="curComponent.propValue.linkType" @change="onChange" style="margin-bottom: 2px">
          <el-radio label="url">{{ $t('message.customDesign.websiteLink') }}</el-radio>
          <el-radio label="detail">{{
            type === 'article' ? $t('message.customDesign.articleDetail') : $t('message.customDesign.productDetail')
          }}</el-radio>
        </el-radio-group>
      </div>
      <el-input
        v-if="!curComponent.propValue.linkType || curComponent.propValue.linkType === 'url'"
        v-model="curComponent.propValue.link"
        :placeholder="$t('message.customDesign.enterLink')"
        @change="onChange"
      >
        <i class="el-icon-link" slot="suffix" @click="getLink" />
      </el-input>
    </el-form-item>
    <!-- 不是面板 -->
    <el-form-item :label="$t('message.customDesign.infoType')" v-if="curComponent.component !== 'Panel'">
      <el-select
        v-model="curComponent.propValue.fieldType"
        clearable
        :placeholder="$t('message.customDesign.chooseInfoType')"
        @change="handleFieldTypeChange"
        style="width: 100%"
      >
        <el-option
          v-for="(item, index) in currentFieldList"
          :key="index"
          :label="item.label"
          :value="item.value"
        ></el-option>
      </el-select>
    </el-form-item>
  </div>
</template>

<script>
export default {
  name: 'ConfigLink',
  props: {
    curComponent: {
      type: Object,
      required: true,
    },
    type: {
      type: String,
      default: 'user',
    },
    currentFieldList: {
      type: Array,
      default: () => [],
    },
    showLinkType: {
      type: Boolean,
      default: false,
    },
  },
  methods: {
    onChange() {
      this.$emit('change');
    },
    getLink() {
      this.$emit('get-link');
    },
    handleFieldTypeChange(val) {
      const field = this.currentFieldList.find((item) => item.value === val);
      if (field) {
        this.$set(this.curComponent.propValue, 'typeLabel', field.label);
      }
      this.onChange();
    },
  },
};
</script>
