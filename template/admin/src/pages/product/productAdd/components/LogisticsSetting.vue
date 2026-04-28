<template>
  <!--  Cài đặt hậu cần -->
  <el-row>
    <el-col :span="24">
      <el-form-item label="Phương pháp hậu cần：" prop="logistics">
        <el-checkbox-group v-model="formValidate.logistics" @change="logisticsBtn">
          <el-checkbox label="1">Chuyển phát nhanh</el-checkbox>
          <el-checkbox label="2">Đến cửa hàng</el-checkbox>
        </el-checkbox-group>
      </el-form-item>
    </el-col>
    <el-col v-if="formValidate.logistics.includes('1')">
      <el-col :span="24">
        <el-form-item label="Cài đặt phí vận chuyển：">
          <el-radio-group v-model="formValidate.freight">
            <!-- <el-radio :label="1">Miễn phí vận chuyển</el-radio> -->
            <el-radio :label="2">Bưu phí cố định</el-radio>
            <el-radio :label="3">Mẫu vận chuyển sản phẩm</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-col>
      <el-col :span="24" v-if="formValidate.freight != 3 && formValidate.freight != 1">
        <el-form-item label="" :prop="formValidate.freight != 1 ? 'freight' : ''">
          <div class="acea-row">
            <el-input-number :controls="false" :min="0" v-model="formValidate.postage" placeholder="Vui lòng nhập số tiền"
              class="input_width maxW input-number-unit-class" class-unit="Nhân dân tệ" />
          </div>
        </el-form-item>
      </el-col>
      <el-col :span="24" v-if="formValidate.freight == 3">
        <el-form-item label="" prop="temp_id">
          <div class="acea-row">
            <el-select v-model="formValidate.temp_id" clearable placeholder="Vui lòng chọn mẫu vận chuyển sản phẩm" class="input_width maxW">
              <el-option v-for="(item, index) in templateList" :value="item.id" :key="index"
                :label="item.name"></el-option>
            </el-select>
            <span class="addfont" v-db-click @click="addTemp">Đã thêm mẫu vận chuyển sản phẩm</span>
          </div>
        </el-form-item>
      </el-col>
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
