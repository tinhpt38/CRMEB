<template>
  <el-dialog :visible.sync="modals" :title="$t('message.orderList.cancelShipment')" class="order_box" :show-close="true" width="540px">
    <Form
      ref="formValidate"
      :model="formValidate"
      :rules="ruleValidate"
      label-width="80px"
      label-position="top"
      @submit.native.prevent
    >
      <FormItem :label="$t('message.orderList.remark')" prop="msg">
        <el-input
          v-model="formValidate.msg"
          :maxlength="200"
          show-word-limit
          type="textarea"
          :placeholder="$t('message.orderList.cancelShipmentRemark')"
          style="width: 414px"
        />
      </FormItem>
    </Form>
    <div class="acea-row row-right mt20">
      <el-button v-db-click @click="cancel('formValidate')">{{ $t('message.orderList.cancel') }}</el-button>
      <el-button type="primary" v-db-click @click="putRemark('formValidate')">{{ $t('message.orderList.submit') }}</el-button>
    </div>
  </el-dialog>
</template>

<script>
import { shipmentCancelOrder } from '@/api/order';
export default {
  name: 'orderMark',
  data() {
    return {
      formValidate: {
        msg: '',
      },
      modals: false,
      ruleValidate: {
        msg: [
          { required: true, message: this.$t('message.orderList.pleaseInputRemark'), trigger: 'blur' },
          // { type: 'string', min: 20, message: 'Introduce no less than 20 words', trigger: 'blur' }
        ],
      },
    };
  },
  props: {
    orderId: Number,
  },
  methods: {
    cancel(name) {
      this.modals = false;
      this.$refs[name].resetFields();
    },
    putRemark(name) {
      let data = {
        msg: this.formValidate.msg,
      };
      this.$refs[name].validate((valid) => {
        if (valid) {
          shipmentCancelOrder(this.orderId, data)
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
          this.$message.warning(this.$t('message.orderList.pleaseFillRemark'));
        }
      });
    },
  },
};
</script>

<style scoped></style>
