<template>
  <el-row>
    <el-col :span="24">
      <div class="index_from page-account-container">
        <div class="page-account-top">
          <span class="page-account-top-tit">{{ $t('message.pages.notify.register.title') }}</span>
        </div>
        <el-form ref="formInline" :model="formInline" :rules="ruleInline" @submit.native.prevent>
          <!--<el-form-item prop="account">-->
          <!--<el-input type="text" v-model="formInline.account" prefix="ios-contact-outline"-->
          <!--placeholder="请输入短信平台账号" />-->
          <!--</el-form-item>-->
          <el-form-item prop="phone" class="maxInpt">
            <el-input
              type="number"
              v-model="formInline.phone"
              prefix="ios-contact-outline"
              :placeholder="$t('message.pages.notify.register.phonePlaceholder')"
            />
          </el-form-item>
          <el-form-item prop="password" class="maxInpt">
            <el-input
              type="password"
              v-model="formInline.password"
              prefix="ios-lock-outline"
              :placeholder="$t('message.pages.notify.register.passwordPlaceholder')"
            />
          </el-form-item>
          <!--<el-form-item prop="password">-->
          <!--<el-input type="password" v-model="formInline.password" prefix="ios-lock-outline"-->
          <!--placeholder="请确认短信平台密码/token" />-->
          <!--</el-form-item>-->
          <!-- <el-form-item prop="url" class="maxInpt">
            <el-input type="text" v-model="formInline.url" prefix="ios-contact-outline" placeholder="请输入网址域名" />
          </el-form-item> -->
          <!--<el-form-item prop="sign">-->
          <!--<el-input type="text" v-model="formInline.sign" prefix="ios-contact-outline"-->
          <!--placeholder="请输入短信签名，例如：CRMEB" />-->
          <!--</el-form-item>-->
          <el-form-item prop="verify_code" class="maxInpt">
            <div class="code">
              <el-input
                type="text"
                v-model="formInline.verify_code"
                prefix="ios-keypad-outline"
                :placeholder="$t('message.pages.notify.register.codePlaceholder')"
              />
              <el-button :disabled="!canClick" v-db-click @click="cutDown">{{ cutNUm }}</el-button>
            </div>
          </el-form-item>
          <el-form-item class="maxInpt">
            <el-button type="primary" long size="large" v-db-click @click="handleSubmit('formInline')" class="btn"
              >{{ $t('message.pages.notify.register.register') }}</el-button
            >
          </el-form-item>
        </el-form>
        <div class="page-account-other">
          <span v-db-click @click="changelogo">{{ $t('message.pages.notify.register.loginNow') }}</span>
        </div>
      </div>
    </el-col>
  </el-row>
</template>

<script>
import { captchaApi, registerApi } from '@/api/setting';
export default {
  name: 'register',
  data() {
    const self = this;
    return {
      cutNUm: '',
      canClick: true,
      formInline: {
        url: '',
        password: '',
        verify_code: '',
        phone: '',
      },
      ruleInline: {
        account: [{ required: true, message: '', trigger: 'blur' }],
        password: [{ required: true, message: '', trigger: 'blur' }],
        phone: [
          {
            required: true,
            validator: (rule, value, callback) => {
              if (!value) return callback(new Error(self.$t('message.pages.notify.register.fillPhone')));
              if (!/^1[3456789]\d{9}$/.test(value)) return callback(new Error(self.$t('message.pages.notify.register.phoneFormatError')));
              callback();
            },
            trigger: 'blur',
          },
        ],
        sign: [{ required: true, message: '', trigger: 'blur' }],
        verify_code: [{ required: true, message: '', trigger: 'blur' }],
      },
    };
  },
  created() {
    this.cutNUm = this.$t('message.pages.notify.register.getCode');
    this.ruleInline.account[0].message = this.$t('message.pages.notify.register.inputSmsAccount');
    this.ruleInline.password[0].message = this.$t('message.pages.notify.register.inputPassword');
    this.ruleInline.sign[0].message = this.$t('message.pages.notify.register.inputSign');
    this.ruleInline.verify_code[0].message = this.$t('message.pages.notify.register.inputCode');
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
            this.cutNUm = this.$t('message.pages.notify.register.getCode');
            this.canClick = true;
            clearInterval(time);
          }
        }, 1000);
      } else {
        this.$message.warning(this.$t('message.pages.notify.register.fillPhone') + '!');
      }
    },
    // 注册
    handleSubmit(name) {
      this.$refs[name].validate((valid) => {
        if (valid) {
          registerApi(this.formInline)
            .then(async (res) => {
              this.$message.success(res.msg);
              setTimeout(() => {
                this.changelogo();
              }, 1000);
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
        } else {
          return false;
        }
      });
    },
    // 立即登录
    changelogo() {
      this.$emit('on-change');
    },
  },
};
</script>

<style lang="scss" scoped>
.maxInpt {
  max-width: 400px;
  margin-left: auto;
  margin-right: auto;
}
.page-account-container {
  text-align: center;
  padding: 50px 0;
}
.page-account-top {
  margin-bottom: 20px;
}
.page-account-top-tit {
  font-size: 21px;
  color: var(--prev-color-primary);
}
.page-account-other {
  text-align: center;
  color: var(--prev-color-primary);
  font-size: 12px;
  span {
    cursor: pointer;
  }
}
.code {
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
