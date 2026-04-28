<template>
  <el-dialog :visible.sync="modals" title="Vui lòng sửa đổi nội dung" width="470px" class="order_box" :show-close="true">
    <el-form ref="formValidate" :model="formValidate" :rules="ruleValidate" label-width="85px" @submit.native.prevent>
      <el-form-item label="Nhận xét：" prop="remark">
        <el-input
          v-model="formValidate.remark"
          :maxlength="200"
          :rows="8"
          show-word-limit
          type="textarea"
          placeholder="Ghi chú đơn hàng"
          style="width: 100%"
        />
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button v-db-click @click="cancel('formValidate')">Hủy bỏ</el-button>
      <el-button type="primary" v-db-click @click="putRemark('formValidate')">Nộp</el-button>
    </span>
  </el-dialog>
</template>

<script>
import { integralOrderPutRemarkData } from '@/api/marketing';
export default {
  name: 'orderMark',
  data() {
    return {
      formValidate: {
        remark: '',
      },
      modals: false,
      ruleValidate: {
        remark: [{ required: true, message: 'Vui lòng nhập thông tin nhận xét', trigger: 'blur' }],
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
        id: this.orderId,
        remark: this.formValidate,
      };
      this.$refs[name].validate((valid) => {
        if (valid) {
          integralOrderPutRemarkData(data)
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
          this.$message.warning('Hãy điền nhận xét');
        }
      });
    },
  },
};
</script>

<style scoped></style>
