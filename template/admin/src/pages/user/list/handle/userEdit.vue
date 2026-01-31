<template>
  <div class="pb-50">
    <el-form ref="formItem" :rules="ruleValidate" :model="formItem" label-width="100px" @submit.native.prevent>
      <el-form-item :label="$t('message.pages.user.list.editLabelUserId')" v-if="formItem.uid">
        <el-input
          class="form-sty"
          disabled
          v-model="formItem.uid"
          :placeholder="$t('message.pages.user.list.editPlaceholderId')"
          style="width: 80%"
        ></el-input>
      </el-form-item>
      <el-form-item :label="$t('message.pages.user.list.editLabelRealName')" prop="real_name">
        <el-input
          class="form-sty"
          v-model.trim="formItem.real_name"
          :placeholder="$t('message.pages.user.list.editPlaceholderRealName')"
          style="width: 80%"
        ></el-input>
      </el-form-item>
      <el-form-item :label="$t('message.pages.user.list.editLabelPhone')" prop="phone">
        <el-input class="form-sty" v-model="formItem.phone" :placeholder="$t('message.pages.user.list.editPlaceholderPhone')" style="width: 80%"></el-input>
      </el-form-item>
      <el-form-item :label="$t('message.pages.user.list.editLabelBirthday')">
        <el-date-picker
          clearable
          class="form-sty"
          type="date"
          v-model="formItem.birthday"
          :placeholder="$t('message.pages.user.list.editPlaceholderBirthday')"
          style="width: 80%"
          format="yyyy-MM-dd"
          value-format="yyyy-MM-dd"
        ></el-date-picker>
      </el-form-item>
      <el-form-item :label="$t('message.pages.user.list.editLabelCardId')">
        <el-input
          class="form-sty"
          v-model.trim="formItem.card_id"
          :placeholder="$t('message.pages.user.list.editPlaceholderCardId')"
          style="width: 80%"
        ></el-input>
      </el-form-item>
      <el-form-item :label="$t('message.pages.user.list.editLabelAddress')">
        <el-input class="form-sty" v-model="formItem.addres" :placeholder="$t('message.pages.user.list.editPlaceholderAddress')" style="width: 80%"></el-input>
      </el-form-item>
      <el-form-item :label="$t('message.pages.user.list.editLabelUserRemark')">
        <el-input class="form-sty" v-model="formItem.mark" :placeholder="$t('message.pages.user.list.editPlaceholderMark')" style="width: 80%"></el-input>
      </el-form-item>
      <el-form-item :label="$t('message.pages.user.list.editLabelLoginPwd')" prop="pwd">
        <el-input
          class="form-sty"
          type="password"
          v-model="formItem.pwd"
          :placeholder="$t('message.pages.user.list.editPlaceholderPwd')"
          style="width: 80%"
        ></el-input>
      </el-form-item>
      <el-form-item :label="$t('message.pages.user.list.editLabelConfirmPwd')" prop="true_pwd">
        <el-input
          class="form-sty"
          type="password"
          v-model="formItem.true_pwd"
          :placeholder="$t('message.pages.user.list.editPlaceholderPwdConfirm')"
          style="width: 80%"
        ></el-input>
      </el-form-item>

      <el-form-item :label="$t('message.pages.user.list.editLabelUserLevel')">
        <el-select v-model="formItem.level" class="form-sty" clearable>
          <el-option
            v-for="(item, index) in infoData.levelInfo"
            :key="index"
            :value="item.id"
            :label="item.name"
          ></el-option>
        </el-select>
      </el-form-item>
      <el-form-item :label="$t('message.pages.user.list.editLabelUserGroup')">
        <el-select v-model="formItem.group_id" class="form-sty" clearable>
          <el-option
            v-for="(item, index) in infoData.groupInfo"
            :key="index"
            :value="item.id"
            :label="item.group_name"
          ></el-option>
        </el-select>
      </el-form-item>
      <el-form-item :label="$t('message.pages.user.list.editLabelUserLabel')">
        <div style="display: flex">
          <div class="labelInput acea-row row-between-wrapper" v-db-click @click="openLabel">
            <div style="width: 90%">
              <div v-if="dataLabel.length">
                <el-tag
                  closable
                  v-for="(item, index) in dataLabel"
                  :key="index"
                  @close="closeLabel(item)"
                  class="mr10"
                  >{{ item.label_name }}</el-tag
                >
              </div>
              <span class="span" v-else>{{ $t('message.pages.user.list.editSelectLabelPlaceholder') }}</span>
            </div>
            <div class="ivu-icon ivu-icon-ios-arrow-down"></div>
          </div>
          <span class="addfont" v-db-click @click="addLabel">{{ $t('message.pages.user.list.addLabelBtn') }}</span>
        </div>
      </el-form-item>
      <el-form-item :label="$t('message.pages.user.list.editLabelDistributeDisable')">
        <el-radio-group v-model="formItem.spread_open" class="form-sty">
          <el-radio :label="0">{{ $t('message.pages.user.list.editYes') }}</el-radio>
          <el-radio :label="1">{{ $t('message.pages.user.list.editNo') }}</el-radio>
        </el-radio-group>
        <div class="tip">{{ $t('message.pages.user.list.editTipDistributeDisable') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.pages.user.list.editLabelDistributeRight')" v-if="formItem.spread_open == 1">
        <el-radio-group v-model="formItem.is_promoter" class="form-sty">
          <el-radio :label="1">{{ $t('message.pages.user.list.editOn') }}</el-radio>
          <el-radio :label="0">{{ $t('message.pages.user.list.editOff') }}</el-radio>
        </el-radio-group>
        <div class="tip">{{ $t('message.pages.user.list.editTipDistributeRight') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.pages.user.list.editLabelUserStatus')">
        <el-radio-group v-model="formItem.status" class="form-sty">
          <el-radio :label="1">{{ $t('message.pages.user.list.editOn') }}</el-radio>
          <el-radio :label="0">{{ $t('message.pages.user.list.editLock') }}</el-radio>
        </el-radio-group>
      </el-form-item>
    </el-form>

    <el-dialog
      :visible.sync="labelShow"
      scrollable
      :title="$t('message.pages.user.list.dialogSelectLabel')"
      :modal="false"
      :show-close="true"
      width="540px"
    >
      <userLabel
        v-if="labelShow"
        :only_get="true"
        :uid="formItem.uid"
        @close="labelClose"
        @activeData="activeData"
      ></userLabel>
    </el-dialog>
  </div>
</template>

<script>
import userLabel from '@/components/userLabel';

import { userLabelAddApi } from '@/api/user';
export default {
  name: 'userEdit',
  components: { userLabel },
  props: {
    // modals: {
    //   default: false,
    //   type: Boolean,
    // },
    userData: {
      type: Object,
      default: () => {},
    },
  },
  watch: {},
  data() {
    return {
      modals: false,
      labelShow: false,
      formItem: {
        uid: 0,
        real_name: '',
        phone: '',
        birthday: '',
        card_id: '',
        addres: '',
        mark: '',
        pwd: '',
        true_pwd: '',
        level: '',
        group_id: '',
        label_id: [],
        spread_open: 1,
        is_promoter: 0,
        status: 1,
      },
      groupInfo: [],
      labelInfo: [],
      levelInfo: [],
      infoData: {
        groupInfo: [],
        labelInfo: [],
        levelInfo: [],
      },
      ruleValidate: {
        real_name: [{ required: true, message: ' ', trigger: 'blur' }],
        phone: [{ required: true, message: ' ', trigger: 'blur' }],
        pwd: [{ required: true, message: ' ', trigger: 'blur' }],
        true_pwd: [{ required: true, message: ' ', trigger: 'blur' }],
      },
      dataLabel: [],
    };
  },
  mounted() {
    this.$set(this.infoData, 'groupInfo', this.userData.groupInfo);
    this.$set(this.infoData, 'levelInfo', this.userData.levelInfo);
    this.$set(this.infoData, 'labelInfo', this.userData.labelInfo);
    let arr = Object.keys(this.formItem);
    if (this.userData.userInfo) {
      arr.map((i) => {
        this.formItem[i] = this.userData.userInfo[i];
      });
      if (!this.formItem.birthday) this.formItem.birthday = '';
      if (this.formItem.label_id.length) {
        this.dataLabel = this.formItem.label_id;
      }
    } else {
      this.reset();
    }

    // this.formItem = this.userData.userInfo;
  },
  methods: {
    addLabel() {
      this.$modalForm(userLabelAddApi(0), { titleKey: 'message.pages.user.label.addLabel' }).then(() => {});
    },
    changeModal(status) {
      if (!status) {
        this.cancel();
        this.reset();
      }
    },
    openLabel(row) {
      this.labelShow = true;
      this.$refs.userLabel.userLabel(JSON.parse(JSON.stringify(this.infoData.labelInfo)));
    },
    cancel() {},
    activeData(dataLabel) {
      this.labelShow = false;
      this.dataLabel = dataLabel;
    },
    // 标签弹窗关闭
    labelClose() {
      this.labelShow = false;
    },
    closeLabel(label) {
      let index = this.dataLabel.indexOf(this.dataLabel.filter((d) => d.id == label.id)[0]);
      this.dataLabel.splice(index, 1);
    },
    reset() {
      this.formItem = {
        uid: '',
        real_name: '',
        phone: '',
        birthday: '',
        card_id: '',
        addres: '',
        mark: '',
        pwd: '',
        true_pwd: '',
        level: '',
        group_id: '',
        label_id: [],
        spread_open: 1,
        is_promoter: 0,
        status: 1,
      };
    },
  },
};
</script>

<style lang="scss" scoped>
.labelInput {
  border: 1px solid #dcdee2;
  width: 400px;
  padding: 0 15px;
  border-radius: 5px;
  min-height: 30px;
  cursor: pointer;
  font-size: 12px;
  .span {
    color: #c5c8ce;
  }
  .iconxiayi {
    font-size: 12px;
  }
}
.ivu-form-item {
  margin-bottom: 10px;
}
.form-sty {
  width: 400px !important;
}
.addfont {
  display: inline-block;
  font-size: 12px;
  font-weight: 400;
  color: var(--prev-color-primary);
  margin-left: 14px;
  cursor: pointer;
  margin-left: 10px;
}
.ivu-icon-ios-arrow-down {
  font-size: 14px;
}
.tip {
  color: #bbb;
  font-size: 12px;
  line-height: 12px;
}
.pb-50{
  padding-bottom: 50px;
}
</style>
