<template>
  <el-dialog :visible.sync="modals" :title="$t('message.pages.order.remark.title')" class="order_box" :show-close="true" width="540px">
    <el-form
      ref="formValidate"
      :model="formValidate"
      :rules="ruleValidate"
      label-width="80px"
      label-position="right"
      @submit.native.prevent
    >
      <el-form-item :label="$t('message.pages.order.remark.remark')" prop="remark">
        <el-input
          v-model="formValidate.remark"
          :maxlength="200"
          show-word-limit
          type="textarea"
          :placeholder="$t('message.pages.order.remark.orderRemarkPlaceholder')"
          style="width: 414px"
        />
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button v-db-click @click="cancel('formValidate')">{{ $t('message.pages.order.remark.cancel') }}</el-button>
      <el-button type="primary" v-db-click @click="putRemark('formValidate')">{{ $t('message.pages.order.remark.submit') }}</el-button>
    </span>
  </el-dialog>
</template>

<script>
import { putRemarkData, putRefundRemarkData } from '@/api/order';
export default {
  name: 'orderMark',
  data() {
    return {
      formValidate: {
        remark: '',
      },
      modals: false,
      ruleValidate: {
        remark: [
          { required: true, message: this.$t('message.pages.order.remark.errRemarkRequired'), trigger: 'blur' },
        ],
      },
    };
  },
  props: {
    orderId: Number,
    remarkType: {
      default: '',
      type: String,
    },
  },
  methods: {
    cancel(name) {
      this.modals = false;
      this.$refs[name].resetFields();
    },
    putRemark(name) {
      let data = {
        id: this.orderId,
        remark: this.formValidate,
      };
      this.$refs[name].validate((valid) => {
        if (valid) {
          (this.remarkType ? putRefundRemarkData : putRemarkData)(data)
            .then(async (res) => {
              this.$message.success(res.msg);
              this.modals = false;
              this.$refs[name].resetFields();
              this.$emit('submitFail');
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
        } else {
          this.$message.warning(this.$t('message.pages.order.remark.msgRemarkRequired'));
        }
      });
    },
  },
};
</script>

<style scoped></style>
