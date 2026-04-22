<template>
  <div v-if="FromData">
    <el-dialog :visible.sync="modals" :title="localizedDialogTitle" width="720px" @closed="cancel">
      <template>
        <div class="radio acea-row row-middle" v-if="FromData.action === '/marketing/coupon/save.html'">
          <div class="name ivu-form-item-content">{{ $t('dialogCommon.couponType') }}</div>
          <el-radio-group v-model="type" @input="couponsType">
            <el-radio :label="0">{{ $t('dialogCommon.generalCoupon') }}</el-radio>
            <el-radio :label="1">{{ $t('dialogCommon.categoryCoupon') }}</el-radio>
            <el-radio :label="2">{{ $t('dialogCommon.productCoupon') }}</el-radio>
          </el-radio-group>
        </div>
      </template>
      <form-create
        :option="config"
        :rule="localizedRules"
        v-model="fapi"
        @submit="onSubmit"
        class="formBox"
        ref="fc"
        handleIcon="false"
      ></form-create>
      <span slot="footer" class="dialog-footer">
        <el-button v-db-click @click="modals = false">{{ $t('dialogCommon.cancel') }}</el-button>
        <el-button type="primary" v-db-click @click="formSubmit">{{ $t('dialogCommon.confirm') }}</el-button>
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
    normalizeText(text) {
      return String(text || '').replace(/：/g, '').trim();
    },
    convertDynamicText(text) {
      const normalize = this.normalizeText;
      const mapText = {
        // user group
        添加分组: this.$t('userGroup.addGroup'),
        编辑分组: this.$t('dialogCommon.editGroup'),
        分组名称: this.$t('userGroup.group'),
        // user label
        添加标签: this.$t('userLabel.addLabel'),
        编辑标签: this.$t('dialogCommon.editLabel'),
        标签名称: this.$t('userLabel.labelName'),
        分类名称: this.$t('userLabel.categoryName'),
        // user level
        添加用户等级: this.$t('userLevel.addUserLevel'),
        编辑用户等级: this.$t('dialogCommon.editUserLevel'),
        添加等级: this.$t('userLevel.addUserLevel'),
        编辑等级: this.$t('dialogCommon.editUserLevel'),
        等级名称: this.$t('userLevel.levelName'),
        等级图标: this.$t('userLevel.levelIcon'),
        等级背景图: this.$t('userLevel.levelBackground'),
        经验值要求: this.$t('userLevel.expRequirement'),
        享受折扣: this.$t('userLevel.discount'),
        是否显示: this.$t('userLevel.isShow'),
        排序: this.$t('message.systemMenus.sort'),
      };
      const key = normalize(text);
      if (mapText[key]) return mapText[key];
      // Fallback by keyword for backend variants like extra spaces/prefixes
      if (key.includes('添加分组')) return this.$t('userGroup.addGroup');
      if (key.includes('编辑分组')) return this.$t('dialogCommon.editGroup');
      if (key.includes('分组名称')) return this.$t('userGroup.group');
      if (key.includes('添加标签')) return this.$t('userLabel.addLabel');
      if (key.includes('编辑标签')) return this.$t('dialogCommon.editLabel');
      if (key.includes('标签名称')) return this.$t('userLabel.labelName');
      if (key.includes('添加用户等级') || key.includes('添加等级')) return this.$t('userLevel.addUserLevel');
      if (key.includes('编辑用户等级') || key.includes('编辑等级')) return this.$t('dialogCommon.editUserLevel');
      if (key.includes('等级名称')) return this.$t('userLevel.levelName');
      return text;
    },
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
  computed: {
    localizedDialogTitle() {
      if (!this.FromData) return '';
      return this.convertDynamicText(this.FromData.title);
    },
    localizedRules() {
      if (!this.FromData) return [];
      const rules = Array.isArray(this.FromData.rules) ? this.FromData.rules : Array.from(this.FromData.rules || []);
      return rules.map((rule) => {
        const item = { ...rule };
        if (item.title) {
          item.title = this.convertDynamicText(item.title).replace(/：/g, '') + '：';
        }
        return item;
      });
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
