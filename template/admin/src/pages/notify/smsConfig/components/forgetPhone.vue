<template>
  <el-row justify="center" align="middle">
    <el-col :span="20" style="margin-top: 70px" class="mb50">
      <steps :stepList="stepList" :isActive="current"></steps>
    </el-col>
    <el-col :span="24">
      <div class="index_from page-account-container">
        <el-form ref="formInline" :model="formInline" :rules="ruleInline" @submit.native.prevent>
          <template v-if="current === 0">
            <el-form-item prop="account" class="maxInpt">
              <el-input
                type="text"
                v-model="formInline.account"
                prefix="ios-contact-outline"
                :placeholder="$t('message.pages.notify.forgetPhone.currentPhonePlaceholder')"
                size="large"
              />
            </el-form-item>
            <el-form-item prop="password" class="maxInpt">
              <el-input
                type="password"
                v-model="formInline.password"
                prefix="ios-lock-outline"
                :placeholder="$t('message.pages.notify.forgetPhone.passwordPlaceholder')"
              />
            </el-form-item>
          </template>
          <template v-if="current === 1">
            <el-form-item prop="phone" class="maxInpt">
              <el-input
                type="text"
                v-model="formInline.phone"
                prefix="ios-lock-outline"
                :placeholder="$t('message.pages.notify.forgetPhone.newPhonePlaceholder')"
                size="large"
              />
            </el-form-item>
            <el-form-item prop="verify_code" class="maxInpt">
              <div class="code">
                <el-input
                  type="text"
                  v-model="formInline.verify_code"
                  prefix="ios-keypad-outline"
                  :placeholder="$t('message.pages.notify.forgetPhone.codePlaceholder')"
                  size="large"
                />
                <el-button :disabled="!this.canClick" v-db-click @click="cutDown" size="large">{{ cutNUm }}</el-button>
              </div>
            </el-form-item>
          </template>
          <template v-if="current === 2">
            <el-form-item prop="phone" class="maxInpt">
              <el-input
                type="text"
                v-model="formInline.phone"
                prefix="ios-contact-outline"
                :placeholder="$t('message.pages.notify.forgetPhone.phonePlaceholder')"
              />
            </el-form-item>
            <el-form-item prop="password" class="maxInpt">
              <el-input
                type="password"
                v-model="formInline.password"
                prefix="ios-lock-outline"
                :placeholder="$t('message.pages.notify.forgetPhone.passwordPlaceholder')"
              />
            </el-form-item>
          </template>
          <el-form-item class="maxInpt">
            <el-button
              v-if="current === 0"
              type="primary"
              long
              size="large"
              v-db-click
              @click="handleSubmit1('formInline', current)"
              class="mb20"
              >{{ $t('message.pages.notify.forgetPhone.nextStep') }}</el-button
            >
            <el-button
              v-if="current === 1"
              type="primary"
              long
              size="large"
              v-db-click
              @click="handleSubmit2('formInline', current)"
              class="mb20"
              >{{ $t('message.pages.notify.forgetPhone.submit') }}</el-button
            >
            <el-button
              v-if="current === 2"
              type="primary"
              long
              size="large"
              v-db-click
              @click="handleSubmit('formInline', current)"
              class="mb20"
              >{{ $t('message.pages.notify.forgetPhone.stepLogin') }}</el-button
            >
            <el-button long size="large" v-db-click @click="returns('formInline')" class="btn">{{ $t('message.pages.notify.forgetPhone.back') }} </el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-col>
  </el-row>
</template>

<script>
import { captchaApi, configApi, serveModifyApi, updateHoneApi } from '@/api/setting';
import steps from '@/components/steps/index';

export default {
  name: 'forgetPhone',
  components: { steps },
  props: {
    isIndex: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    const self = this;
    return {
      cutNUm: '',
      canClick: true,
      current: 0,
      formInline: {
        account: '',
        phone: '',
        verify_code: '',
        password: '',
      },
      ruleInline: {
        phone: [
          {
            required: true,
            validator: (rule, value, callback) => {
              if (!value) return callback(new Error(self.$t('message.pages.notify.forgetPhone.fillPhone')));
              if (!/^1[3456789]\d{9}$/.test(value)) return callback(new Error(self.$t('message.pages.notify.forgetPhone.phoneFormatError')));
              callback();
            },
            trigger: 'blur',
          },
        ],
        verify_code: [{ required: true, message: '', trigger: 'blur' }],
        password: [{ required: true, message: '', trigger: 'blur' }],
        account: [
          {
            required: true,
            validator: (rule, value, callback) => {
              if (!value) return callback(new Error(self.$t('message.pages.notify.forgetPhone.fillPhone')));
              if (!/^1[3456789]\d{9}$/.test(value)) return callback(new Error(self.$t('message.pages.notify.forgetPhone.phoneFormatError')));
              callback();
            },
            trigger: 'blur',
          },
        ],
      },
      stepList: [],
    };
  },
  created() {
    this.cutNUm = this.$t('message.pages.notify.forgetPhone.getCode');
    this.ruleInline.verify_code[0].message = this.$t('message.pages.notify.forgetPhone.inputCode');
    this.ruleInline.password[0].message = this.$t('message.pages.notify.forgetPhone.inputPassword');
    this.stepList = [
      this.$t('message.pages.notify.forgetPhone.stepVerify'),
      this.$t('message.pages.notify.forgetPhone.stepModifyPhone'),
      this.$t('message.pages.notify.forgetPhone.stepLogin'),
    ];
  },
  methods: {
    // 短信验证码
    cutDown() {
      if (this.formInline.phone) {
        if (!this.canClick) return;
        this.canClick = false;
        this.cutNUm = 60;
        let data = {
          phone: this.formInline.phone,
        };
        captchaApi(data)
          .then(async (res) => {
            this.$message.success(res.msg);
          })
          .catch((res) => {
            this.$message.error(res.msg);
          });
        let time = setInterval(() => {
          this.cutNUm--;
          if (this.cutNUm === 0) {
            this.cutNUm = this.$t('message.pages.notify.forgetPhone.getCode');
            this.canClick = true;
            clearInterval(time);
          }
        }, 1000);
      } else {
        this.$message.warning(this.$t('message.pages.notify.forgetPhone.fillPhoneWarn'));
      }
    },
    handleSubmit1(name) {
      this.$refs[name].validate((valid) => {
        if (valid) {
          this.handleSubmit(name, 1);
        } else {
          return false;
        }
      });
    },
    handleSubmit2(name) {
      this.$refs[name].validate((valid) => {
        if (valid) {
          updateHoneApi(this.formInline)
            .then(async (res) => {
              this.$message.success(res.msg);
              this.current = 2;
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
        } else {
          return false;
        }
      });
    },
    //登录
    handleSubmit(name, num) {
      this.$refs[name].validate((valid) => {
        if (valid) {
          configApi({
            account: this.formInline.account,
            password: this.formInline.password,
          })
            .then(async (res) => {
              num === 1 ? this.$message.success(this.$t('message.pages.notify.forgetPhone.oldPhoneCorrect')) : this.$message.success(this.$t('message.pages.notify.forgetPhone.loginSuccess'));
              num === 1 ? (this.current = 1) : this.$emit('on-Login');
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
        } else {
          return false;
        }
      });
    },
    returns() {
      this.current === 0 ? this.$emit('gobackPhone') : (this.current = 0);
    },
  },
};
</script>

<style scoped lang="scss">
.maxInpt {
  max-width: 400px;
  margin-left: auto;
  margin-right: auto;
}
.code {
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
