<template>
  <div class="main">
    <div class="i-layout-page-header header-title">
      <span class="ivu-page-header-title">{{ $route.meta.title }}</span>
    </div>
    <el-card :bordered="false" shadow="never">
      <el-form
        ref="formItem"
        :model="formItem"
        label-width="110px"
        label-position="right"
        :rules="ruleValidate"
        @submit.native.prevent
      >
        <el-row :gutter="24">
          <el-col :span="24">
            <el-col v-bind="grid">
              <el-form-item :label="$t('message.pages.notify.elecInvoice.statusLabel')" prop="name" label-for="name">
                <el-radio-group v-model="formItem.elec_invoice">
                  <el-radio :label="1">{{ $t('message.pages.notify.elecInvoice.open') }}</el-radio>
                  <el-radio :label="0">{{ $t('message.pages.notify.elecInvoice.close') }}</el-radio>
                </el-radio-group>
                <div class="tips-info">{{ $t('message.pages.notify.elecInvoice.tipEnable') }}</div>
              </el-form-item>
            </el-col>
          </el-col>
          <template v-if="formItem.elec_invoice === 1">
            <el-col :span="24">
              <el-col v-bind="grid">
                <el-form-item :label="$t('message.pages.notify.elecInvoice.autoInvoiceLabel')" prop="name" label-for="name">
                  <div>
                    <el-radio-group v-model="formItem.auto_invoice">
                      <el-radio :label="1">{{ $t('message.pages.notify.elecInvoice.open') }}</el-radio>
                      <el-radio :label="0">{{ $t('message.pages.notify.elecInvoice.close') }}</el-radio>
                    </el-radio-group>
                    <div class="tips-info">{{ $t('message.pages.notify.elecInvoice.tipAutoInvoice') }}</div>
                  </div>
                </el-form-item>
              </el-col>
            </el-col>
            <template v-if="formItem.auto_invoice === 1 && formItem.elec_invoice === 1">
              <el-col :span="24">
                <el-col v-bind="grid">
                  <el-form-item :label="$t('message.pages.notify.elecInvoice.categoryLabel')">
                    <el-select
                      class="input-width"
                      v-model="formItem.elec_invoice_cate"
                      filterable
                      remote
                      reserve-keyword
                      :remote-method="remoteMethod"
                      :loading="loading"
                      :placeholder="$t('message.pages.notify.elecInvoice.categoryPlaceholder')"
                      @change="selectChange"
                    >
                      <el-option v-for="item in options" :key="item.id" :label="item.name" :value="item.id">
                      </el-option>
                    </el-select>
                    <div class="tips-info">{{ $t('message.pages.notify.elecInvoice.tipCategory') }}</div>
                  </el-form-item>
                </el-col>
              </el-col>
              <el-col :span="24">
                <el-col v-bind="grid">
                  <el-form-item :label="$t('message.pages.notify.elecInvoice.taxRateLabel')">
                    <el-input
                      type="number"
                      class="input-width"
                      v-model="formItem.elec_invoice_tax_rate"
                      :placeholder="$t('message.pages.notify.elecInvoice.taxRatePlaceholder')"
                    />
                    <div class="tips-info">
                      {{ $t('message.pages.notify.elecInvoice.tipTaxRate') }}
                    </div>
                  </el-form-item>
                </el-col>
              </el-col>
            </template>
          </template>
          <el-col :span="24">
            <el-col v-bind="grid">
              <el-form-item>
                <el-button type="primary" long v-db-click @click="handleSubmit('formItem')">{{ $t('message.pages.notify.elecInvoice.save') }}</el-button>
              </el-form-item>
            </el-col>
          </el-col>
        </el-row>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { invoiceCategory, saveBasics, invoiceConfig } from '@/api/order';
export default {
  name: '',
  data() {
    return {
      ruleValidate: {},
      formItem: {
        elec_invoice: 0,
        auto_invoice: 0,
        elec_invoice_cate: '',
        elec_invoice_tax_rate: null,
      },
      optionsConfig: {
        label: 'name',
        value: 'id',
      },
      grid: {
        xl: 8,
        lg: 12,
        md: 18,
        sm: 16,
        xs: 24,
      },
      loading: false,
      options: [],
    };
  },
  created() {
    this.getInvoiceConfig();
  },
  mounted() {},
  methods: {
    getInvoiceConfig() {
      invoiceConfig().then((res) => {
        this.formItem = res.data;
        this.formItem.elec_invoice_cate = res.data.elec_invoice_cate || '';
        let { elec_invoice_cate, elec_invoice_cate_name } = res.data;
        if (elec_invoice_cate) {
          this.options = [{ id: elec_invoice_cate, name: elec_invoice_cate_name }];
        }
      });
    },
    selectChange(e) {
      let obj = {};
      obj = this.options.find((item) => {
        return item.id === e;
      });
      this.formItem.elec_invoice_cate_name = obj.name;
      this.formItem.elec_invoice_tax_rate = obj.tax_rate_num;
    },
    handleSubmit(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          saveBasics(this.formItem).then(() => {
            this.$message.success(this.$t('message.pages.notify.elecInvoice.saveSuccess'));
          });
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    remoteMethod(query) {
      if (query !== '') {
        this.loading = true;
        invoiceCategory({ name: query }).then((res) => {
          this.loading = false;
          this.options = res.data.list.filter((item) => {
            return item.name.toLowerCase().indexOf(query.toLowerCase()) > -1;
          });
        });
      } else {
        this.options = [];
      }
    },
  },
};
</script>
<style lang="scss" scoped>
.input-width {
  width: 100%;
}
</style>
