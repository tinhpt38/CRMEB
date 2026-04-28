<template>
  <div>
    <el-form ref="formValidate" :model="formValidate" :rules="ruleInline" inline>
      <el-form-item label="Nhận xét：" prop="con" class="form-item" label-position="right" label-width="60px">
        <el-input
          v-model="formValidate.con"
          placeholder="Vui lòng nhập nhận xét"
          style="width: 360px"
          maxlength="200"
          type="textarea"
          :rows="5"
          show-word-limit
        ></el-input>
      </el-form-item>
      <div class="mask-footer">
        <el-button v-db-click @click="close">Hủy bỏ</el-button>
        <el-button type="primary" v-db-click @click="handleSubmit('formValidate')">Nộp</el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
import { orderRemark } from '@/api/kefu';
export default {
  name: 'remarks',
  props: {
    remarkId: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      formValidate: {
        con: '',
      },
      ruleInline: {
        con: [{ required: true, message: 'Vui lòng nhập thông tin nhận xét', trigger: 'change' }],
      },
      formValidate: {
        con: '',
      },
    };
  },
  methods: {
    handleSubmit(name) {
      this.$refs[name].validate((valid) => {
        if (valid) {
          orderRemark({
            order_id: this.remarkId,
            remark: this.formValidate.con,
          })
            .then((res) => {
              this.$message.success(res.msg);
              this.$emit('remarkSuccess');
            })
            .catch((error) => {
              this.$message.error(error.msg);
            });
        } else {
        }
      });
    },
    close() {
      this.$emit('close');
    },
  },
};
</script>

<style scoped>
.form-item {
  width: 100%;
}
</style>
