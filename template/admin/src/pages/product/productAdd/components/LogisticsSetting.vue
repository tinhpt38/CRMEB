<template>
  <!--  物流设置 -->
  <el-row>
    <el-col :span="24">
      <el-form-item :label="$t('message.pages.product.add.logisticsType')" prop="logistics">
        <el-checkbox-group v-model="formValidate.logistics" @change="logisticsBtn">
          <el-checkbox label="1">{{ $t('message.pages.product.add.express') }}</el-checkbox>
          <el-checkbox label="2">{{ $t('message.pages.product.add.store') }}</el-checkbox>
        </el-checkbox-group>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.pages.product.add.freightSetting')">
        <el-radio-group v-model="formValidate.freight">
          <el-radio :label="2">{{ $t('message.pages.product.add.fixedPostage') }}</el-radio>
          <el-radio :label="3">{{ $t('message.pages.product.add.freightTemplate') }}</el-radio>
        </el-radio-group>
      </el-form-item>
    </el-col>
    <el-col :span="24" v-if="formValidate.freight != 3 && formValidate.freight != 1">
      <el-form-item label="" :prop="formValidate.freight != 1 ? 'freight' : ''">
        <div class="acea-row">
          <el-input-number
            :controls="false"
            :min="0"
            v-model="formValidate.postage"
            :placeholder="$t('message.pages.product.add.inputAmount')"
            class="input_width maxW input-number-unit-class"
            :class-unit="$t('message.pages.product.add.yuan')"
          />
        </div>
      </el-form-item>
    </el-col>
    <el-col :span="24" v-if="formValidate.freight == 3">
      <el-form-item label="" prop="temp_id">
        <div class="acea-row">
          <el-select v-model="formValidate.temp_id" clearable :placeholder="$t('message.pages.product.add.selectFreightTemplate')" class="input_width maxW">
            <el-option
              v-for="(item, index) in templateList"
              :value="item.id"
              :key="index"
              :label="item.name"
            ></el-option>
          </el-select>
          <span class="addfont" v-db-click @click="addTemp">{{ $t('message.pages.product.add.newFreightTemplate') }}</span>
        </div>
      </el-form-item>
    </el-col>
  </el-row>
</template>

<script>
export default {
  name: 'LogisticsSetting',
  props: {
    formValidate: {
      type: Object,
      required: true,
    },
    templateList: {
      type: Array,
      required: true,
    },
  },
  methods: {
    logisticsBtn(val) {
      this.$emit('logisticsBtn', val);
    },
    addTemp() {
      this.$emit('addTemp');
    },
  },
};
</script>
<style lang="scss" scoped>
@use '../productAdd.scss' as *;
</style>
