<template>
  <div v-if="FromData">
    <el-dialog :visible.sync="modals" :title="FromData.title" width="720px" @closed="cancel">
      <template>
        <div class="radio acea-row row-middle" v-if="FromData.action === '/marketing/coupon/save.html'">
          <div class="name ivu-form-item-content">Loại phiếu giảm giá</div>
          <el-radio-group v-model="type" @input="couponsType">
            <el-radio :label="0">Mã giảm giá phổ quát</el-radio>
            <el-radio :label="1">Mã giảm giá danh mục</el-radio>
            <el-radio :label="2">Mã giảm giá sản phẩm</el-radio>
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
        <el-button v-db-click @click="modals = false">Hủy bỏ</el-button>
        <el-button type="primary" v-db-click @click="formSubmit">Chắc chắn</el-button>
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
    FromData() {
      this.FromData.rules.forEach((e) => {
        e.title += '：';
      });
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
    // Gửi biểu mẫu group
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
    // nút đóng
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
