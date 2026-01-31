<template>
  <el-dialog :visible.sync="modals" :title="$t('message.components.remark.title')" class="order_box" width="470px" :show-close="true">
    <el-form ref="formValidate" :model="formValidate" :rules="ruleValidate" label-width="85px" @submit.native.prevent>
      <el-form-item :label="$t('message.components.remark.remarkLabel')" prop="remark">
        <el-input
          v-model="formValidate.remark"
          :maxlength="200"
          show-word-limit
          type="textarea"
          :placeholder="$t('message.components.remark.placeholder')"
          style="width: 100%"
        />
      </el-form-item>
    </el-form>
    <div slot="footer">
      <el-button type="primary" v-db-click @click="putRemark('formValidate')">{{ $t('message.components.remark.submit') }}</el-button>
      <el-button v-db-click @click="cancel('formValidate')">{{ $t('message.components.remark.cancel') }}</el-button>
    </div>
  </el-dialog>
</template>

<script>
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
          { required: true, message: '', trigger: 'blur' },
        ],
      },
    };
  },
  props: {
    remark: {
      default: '',
      type: String,
    },
  },
  mounted() {
    this.ruleValidate.remark[0].message = this.$t('message.components.remark.requiredMessage');
  },
  methods: {
    cancel(name) {
      this.modals = false;
      this.$refs[name].resetFields();
    },
    putRemark(name) {
      this.$refs[name].validate((valid) => {
        if (valid) {
          this.$emit('submitFail', this.formValidate.remark);
        } else {
          this.$message.warning(this.$t('message.components.remark.requiredMessage'));
        }
      });
    },
  },
};
</script>

<style scoped></style>
