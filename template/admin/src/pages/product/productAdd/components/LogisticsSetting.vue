<template>
  <!--  物流设置 -->
  <el-row>
    <el-col :span="24">
      <el-form-item :label="$t('message.productList.logisticsMethod')" prop="logistics">
        <el-checkbox-group v-model="formValidate.logistics" @change="logisticsBtn">
          <el-checkbox label="1">{{ $t('message.productList.express') }}</el-checkbox>
          <el-checkbox label="2">{{ $t('message.productList.storePickup2') }}</el-checkbox>
        </el-checkbox-group>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productList.freightSetting')">
        <el-radio-group v-model="formValidate.freight">
          <!-- <el-radio :label="1">包邮</el-radio> -->
          <el-radio :label="2">{{ $t('message.productList.fixedFreight') }}</el-radio>
          <el-radio :label="3">{{ $t('message.productList.freightTemplate') }}</el-radio>
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
            :placeholder="$t('message.productList.pleaseInputAmount')"
            class="input_width maxW input-number-unit-class"
            :class-unit="$t('message.productList.yuan2')"
          />
        </div>
      </el-form-item>
    </el-col>
    <el-col :span="24" v-if="formValidate.freight == 3">
      <el-form-item label="" prop="temp_id">
        <div class="acea-row">
          <el-select v-model="formValidate.temp_id" clearable :placeholder="$t('message.productList.pleaseSelectFreightTemplate')" class="input_width maxW">
            <el-option
              v-for="(item, index) in templateList"
              :value="item.id"
              :key="index"
              :label="item.name"
            ></el-option>
          </el-select>
          <span class="addfont" v-db-click @click="addTemp">{{ $t('message.productList.addFreightTemplate') }}</span>
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
