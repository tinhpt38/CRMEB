<template>
  <div v-if="FromData">
    <el-dialog :visible.sync="modals" :title="formDialogTitle" width="720px" @closed="cancel">
      <template>
        <div class="radio acea-row row-middle" v-if="FromData.action === '/marketing/coupon/save.html'">
          <div class="name ivu-form-item-content">{{ $t('message.components.formDialog.couponType') }}</div>
          <el-radio-group v-model="type" @input="couponsType">
            <el-radio :label="0">{{ $t('message.components.formDialog.universalCoupon') }}</el-radio>
            <el-radio :label="1">{{ $t('message.components.formDialog.categoryCoupon') }}</el-radio>
            <el-radio :label="2">{{ $t('message.components.formDialog.productCoupon') }}</el-radio>
          </el-radio-group>
        </div>
      </template>
      <form-create
        :option="config"
        :rule="Array.from(this.FromData.rules)"
        v-model="fapi"
        @submit="onSubmit"
        class="formBox"
        ref="fc"
        handleIcon="false"
      ></form-create>
      <span slot="footer" class="dialog-footer">
        <el-button v-db-click @click="modals = false">{{ $t('message.components.formDialog.cancel') }}</el-button>
        <el-button type="primary" v-db-click @click="formSubmit">{{ $t('message.components.formDialog.confirm') }}</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import formCreate from '@form-create/element-ui';
import request from '@/libs/request';
import { mapState } from 'vuex';
export default {
  name: 'edit',
  components: {
    formCreate: formCreate.$form(),
  },
  computed: {
    ...mapState('userLevel', ['taskId', 'levelId']),
    formDialogTitle() {
      if (!this.FromData || !this.FromData.action) return this.FromData ? this.FromData.title : '';
      const action = (this.FromData.action || '').toLowerCase();
      const isAdd = action.includes('create') || action.includes('/0');
      if (action.includes('category')) {
        return this.$t(isAdd ? 'message.pages.product.classify.form.addCategoryTitle' : 'message.pages.product.classify.form.editCategoryTitle');
      }
      if (action.includes('protection')) {
        return this.$t(isAdd ? 'message.pages.product.protectionList.form.addGuaranteeTitle' : 'message.pages.product.protectionList.form.editGuaranteeTitle');
      }
      return this.FromData.title || '';
    },
  },
  props: {
    FromData: {
      type: Object,
      default: null,
    },
    update: {
      type: Boolean,
      default: true,
    },
  },
  watch: {
    FromData: {
      handler(val) {
        if (!val || !val.rules) return;
        const action = (val.action || '').toLowerCase();
        const isCategory = action.includes('category');
        const isProtection = action.includes('protection');
        const categoryMap = { cate_name: 'categoryName', pid: 'parentCategory', pic: 'categoryIcon', sort: 'sort', is_show: 'status' };
        const protectionMap = { title: 'guaranteeName', content: 'guaranteeContent', image: 'image', status: 'status', sort: 'sort' };
        val.rules.forEach((e) => {
          let key = null;
          if (isCategory && categoryMap[e.field]) {
            key = 'message.pages.product.classify.form.' + categoryMap[e.field];
          } else if (isProtection && protectionMap[e.field]) {
            key = 'message.pages.product.protectionList.form.' + protectionMap[e.field];
          }
          if (key && this.$te(key)) {
            e.title = this.$t(key) + (e.title && e.title.endsWith('：') ? '' : '：');
          } else if (!e.title || !e.title.endsWith('：')) {
            e.title = (e.title || '') + '：';
          }
        });
      },
      immediate: true,
    },
  },
  data() {
    return {
      modals: false,
      type: 0,
      loading: false,
      fapi: null,
      config: {
        form: {
          labelWidth: '100px',
        },
        resetBtn: false,
        submitBtn: false,
        global: {
          upload: {
            props: {
              onSuccess(res, file) {
                if (res.status === 200) {
                  file.url = res.data.src;
                } else {
                  this.$message.error(res.msg);
                }
              },
            },
          },
        },
      },
    };
  },
  methods: {
    couponsType() {
      this.$parent.addType(this.type);
    },
    formSubmit() {
      this.fapi.submit();
    },
    // 提交表单 group
    onSubmit(formData) {
      let datas = {};
      datas = formData;
      if (this.loading) return;
      this.loading = true;
      request({
        url: this.FromData.action,
        method: this.FromData.method,
        data: datas,
      })
        .then((res) => {
          if (this.update) this.$parent.getList();
          this.$message.success(res.msg);
          this.modals = false;
          setTimeout(() => {
            this.$emit('submitFail');
            this.loading = false;
          }, 1000);
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // 关闭按钮
    cancel() {
      this.type = 0;
      // this.$emit('onCancel')
    },
  },
};
</script>

<style lang="scss" scoped>
.radio {
  margin-bottom: 14px;
}
.radio ::v-deep .name {
  width: 125px;
  text-align: right;
  padding-right: 12px;
}
</style>
