<template>
  <el-dialog :visible.sync="modals" :title="$t('message.pages.order.shipment.title')" class="order_box" :show-close="true" width="540px">
    <Form
      ref="formValidate"
      :model="formValidate"
      :rules="ruleValidate"
      label-width="80px"
      label-position="right"
      @submit.native.prevent
    >
      <FormItem :label="$t('message.pages.order.shipment.remark')" prop="msg">
        <el-input
          v-model="formValidate.msg"
          :maxlength="200"
          show-word-limit
          type="textarea"
          :placeholder="$t('message.pages.order.shipment.cancelShipRemark')"
          style="width: 414px"
        />
      </FormItem>
    </Form>
    <div class="acea-row row-right mt20">
      <el-button v-db-click @click="cancel('formValidate')">{{ $t('message.pages.order.shipment.cancel') }}</el-button>
      <el-button type="primary" v-db-click @click="putRemark('formValidate')">{{ $t('message.pages.order.shipment.submit') }}</el-button>
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
          { required: true, message: this.$t('message.pages.order.shipment.errRemarkRequired'), trigger: 'blur' },
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
          this.$message.warning(this.$t('message.pages.order.shipment.msgRemarkRequired'));
        }
      });
    },
  },
};
</script>

<style scoped></style>
