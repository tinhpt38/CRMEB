<!-- Sửa đổi địa chỉ gửi -->
<template>
  <div class="order-address">
    <el-dialog
      title="Sửa đổi địa chỉ gửi"
      :visible.sync="modals"
      width="50%"
      :close-on-click-modal="false"
      :close-on-press-escape="false"
      :show-close="false"
      @open="handleOpen"
    >
      <el-form :model="form" :rules="rules" ref="form" label-width="140px" class="demo-ruleForm">
        <el-form-item label="Người nhận hàng" prop="real_name">
          <el-input v-model="form.real_name" autocomplete="off" />
        </el-form-item>
        <el-form-item label="Số điện thoại" prop="user_phone">
          <el-input v-model="form.user_phone" autocomplete="off" />
        </el-form-item>
        <el-form-item label="Tỉnh/thành phố" prop="province">
          <el-autocomplete
            v-model="form.province"
            class="address-autocomplete"
            :fetch-suggestions="queryProvince"
            placeholder="Chọn hoặc tìm tỉnh/thành phố"
            :trigger-on-focus="true"
            clearable
            @select="handleProvinceSelect"
            @clear="handleProvinceClear"
          />
        </el-form-item>
        <el-form-item label="Xã/phường" prop="ward">
          <el-autocomplete
            v-model="form.ward"
            class="address-autocomplete"
            :fetch-suggestions="queryWard"
            :placeholder="form.province ? 'Chọn hoặc tìm xã/phường' : 'Chọn tỉnh/thành phố trước'"
            :disabled="!form.province"
            :trigger-on-focus="true"
            clearable
          />
        </el-form-item>
        <el-form-item label="Địa chỉ chi tiết" prop="detail">
          <el-input
            v-model="form.detail"
            type="textarea"
            :rows="2"
            autocomplete="off"
            placeholder="Số nhà, tên đường"
          />
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button v-db-click @click="modals = false">Hủy bỏ</el-button>
        <el-button type="primary" v-db-click @click="submitForm('form')">Chắc chắn</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { cityList } from '@/api/app';
import {
  composeShippingAddress,
  createAddressSuggestions,
  parseShippingAddress,
} from '@/utils/shippingAddress';

const createEmptyForm = () => ({
  id: 0,
  real_name: '',
  user_phone: '',
  user_address: '',
  province: '',
  ward: '',
  detail: '',
});

export default {
  props: {
    addressData: {
      type: Object,
      default: () => ({}),
    },
  },
  data() {
    return {
      modals: false,
      cityTree: [],
      cityLoaded: false,
      form: createEmptyForm(),
    };
  },
  computed: {
    provinceOptions() {
      return this.cityTree.map((item) => item.label).filter(Boolean);
    },
    wardOptions() {
      const provinceNode = this.cityTree.find((item) => item.label === this.form.province);
      if (!provinceNode || !provinceNode.children) return [];
      return provinceNode.children.map((item) => item.label).filter(Boolean);
    },
    rules() {
      return {
        real_name: [{ required: true, message: 'Vui lòng nhập Người nhận hàng', trigger: 'blur' }],
        user_phone: [{ required: true, message: 'Vui lòng nhập số điện thoại di động', trigger: 'blur' }],
        province: [{ required: true, message: 'Vui lòng chọn tỉnh/thành phố', trigger: 'change' }],
        ward: [{ required: true, message: 'Vui lòng chọn xã/phường', trigger: 'change' }],
        detail: [{ required: true, message: 'Vui lòng nhập địa chỉ chi tiết', trigger: 'blur' }],
      };
    },
  },
  watch: {
    addressData: {
      handler(newVal) {
        if (!newVal || !Object.keys(newVal).length) return;
        this.applyAddressData(newVal);
      },
      deep: true,
    },
    'form.province'(value, oldValue) {
      if (!value || value === oldValue) return;
      if (this.wardOptions.length && this.form.ward && !this.wardOptions.includes(this.form.ward)) {
        this.form.ward = '';
      }
    },
  },
  methods: {
    handleOpen() {
      this.ensureCityList().then(() => {
        if (this.addressData && Object.keys(this.addressData).length) {
          this.applyAddressData(this.addressData);
        }
      });
    },
    ensureCityList() {
      if (this.cityLoaded) return Promise.resolve(this.cityTree);
      return cityList()
        .then((res) => {
          this.cityTree = res.data || [];
          this.cityLoaded = true;
          return this.cityTree;
        })
        .catch((res) => {
          this.$message.error(res.msg || 'Không tải được danh sách tỉnh/thành phố');
          return [];
        });
    },
    applyAddressData(data) {
      const parsed = parseShippingAddress(data.user_address, this.cityTree);
      this.form = {
        ...createEmptyForm(),
        ...data,
        province: parsed.province,
        ward: parsed.ward,
        detail: parsed.detail,
      };
    },
    queryProvince(queryString, cb) {
      cb(createAddressSuggestions(this.provinceOptions, queryString));
    },
    queryWard(queryString, cb) {
      cb(createAddressSuggestions(this.wardOptions, queryString));
    },
    handleProvinceSelect() {
      if (this.form.ward && !this.wardOptions.includes(this.form.ward)) {
        this.form.ward = '';
      }
    },
    handleProvinceClear() {
      this.form.ward = '';
    },
    submitForm(formName) {
      this.$refs[formName].validate((valid) => {
        if (!valid) return false;

        const payload = {
          ...this.form,
          user_address: composeShippingAddress({
            detail: this.form.detail,
            ward: this.form.ward,
            province: this.form.province,
          }),
        };

        Object.assign(this.addressData, payload);
        this.$emit('submitSuccess', payload);
        return true;
      });
    },
  },
};
</script>

<style scoped>
.address-autocomplete {
  width: 100%;
}
</style>
