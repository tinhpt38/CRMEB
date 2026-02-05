<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-alert type="warning" :closable="false" class="alert-info">
        <template slot="title">
          {{ $t('message.pages.setting.systemOutAccount.tokenTip') }}<br />
          {{ $t('message.pages.setting.systemOutAccount.tokenTipUrl') }}<br />
          {{ $t('message.pages.setting.systemOutAccount.useTokenTip') }}<br />
          {{ $t('message.pages.setting.systemOutAccount.authTip') }}
        </template>
      </el-alert>
      <el-form
        ref="formValidate"
        :model="formValidate"
        :label-width="labelWidth"
        :label-position="labelPosition"
        @submit.native.prevent
      >
        <el-row>
          <el-col v-bind="grid">
            <el-button v-auth="['setting-system_admin-add']" type="primary" v-db-click @click="add">{{ $t('message.pages.setting.systemOutAccount.addAccount') }}</el-button>
          </el-col>
        </el-row>
      </el-form>
      <el-table
        :data="list"
        class="mt14"
        :no-data-text="$t('message.pages.setting.systemOutAccount.noData')"
        :no-filtered-data-text="$t('message.pages.setting.systemOutAccount.noFilterResult')"
        v-loading="loading"
        highlight-current-row
      >
        <el-table-column :label="$t('message.pages.setting.systemOutAccount.id')" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.systemOutAccount.account')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.appid }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.systemOutAccount.desc')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.title }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.systemOutAccount.addTime')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.systemOutAccount.lastLoginTime')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.last_time }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.systemOutAccount.lastLoginIp')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.ip }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.systemOutAccount.status')" min-width="130">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.status"
              :value="scope.row.status"
              @change="onchangeIsShow(scope.row)"
              size="large"
              :active-text="$t('message.pages.setting.systemOutAccount.on')"
              :inactive-text="$t('message.pages.setting.systemOutAccount.off')"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.systemOutAccount.action')" fixed="right" width="140">
          <template slot-scope="scope">
            <a v-db-click @click="setUp(scope.row)">{{ $t('message.pages.setting.systemOutAccount.setup') }}</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="edit(scope.row)">{{ $t('message.pages.setting.systemOutAccount.edit') }}</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, $t('message.pages.setting.systemOutAccount.delAccountTitle'), scope.$index)">{{ $t('message.pages.setting.systemOutAccount.del') }}</a>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="formValidate.page"
          :limit.sync="formValidate.limit"
          @pagination="getList"
        />
      </div>
    </el-card>
    <el-dialog
      :visible.sync="modals"
      :title="type == 0 ? $t('message.pages.setting.systemOutAccount.addAccountTitle') : $t('message.pages.setting.systemOutAccount.editAccountTitle')"
      :close-on-click-modal="false"
      :show-close="true"
      width="720px"
    >
      <el-form
        ref="modalsdate"
        :model="modalsdate"
        :rules="type == 0 ? ruleValidate : editValidate"
        label-width="80px"
        label-position="right"
      >
        <el-form-item :label="$t('message.pages.setting.systemOutAccount.accountLabel')" prop="appid">
          <div style="display: flex">
            <el-input type="text" v-model="modalsdate.appid" :disabled="type != 0"></el-input>
          </div>
        </el-form-item>
        <el-form-item :label="$t('message.pages.setting.systemOutAccount.passwordLabel')" prop="appsecret">
          <div style="display: flex">
            <el-input type="text" v-model="modalsdate.appsecret" class="input"></el-input>
            <el-button type="primary" v-db-click @click="reset" class="reset">{{ $t('message.pages.setting.systemOutAccount.random') }}</el-button>
          </div>
        </el-form-item>
        <el-form-item :label="$t('message.pages.setting.systemOutAccount.descLabel')" prop="title">
          <div style="display: flex">
            <el-input type="textarea" v-model="modalsdate.title"></el-input>
          </div>
        </el-form-item>
        <el-form-item :label="$t('message.pages.setting.systemOutAccount.apiPermission')" prop="title">
          <!-- <el-checkbox-group v-model="modalsdate.rules">
            <el-checkbox
              :disabled="[2, 3].includes(item.id)"
              style="width: 30%"
              v-for="item in intList"
              :key="item.id"
              :label="item.id"
              >{{ item.name }}</el-checkbox
            >
          </el-checkbox-group> -->
          <el-tree
            :data="intList"
            :props="props"
            multiple
            show-checkbox
            ref="tree"
            node-key="id"
            :default-checked-keys="selectIds"
            @check-change="selectTree"
          ></el-tree>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button v-db-click @click="cancel">{{ $t('message.pages.setting.systemOutAccount.cancel') }}</el-button>
        <el-button type="primary" v-db-click @click="ok('modalsdate')">{{ $t('message.pages.setting.systemOutAccount.confirm') }}</el-button>
      </span>
    </el-dialog>
    <el-dialog
      :visible.sync="settingModals"
      scrollable
      :title="$t('message.pages.setting.systemOutAccount.pushSettingTitle')"
      width="1000px"
      :close-on-click-modal="false"
      :show-close="true"
    >
      <el-form
        class="setting-style"
        ref="settingData"
        :model="settingData"
        :rules="type == 0 ? ruleValidate : editValidate"
        label-width="155px"
        label-position="right"
      >
        <el-form-item :label="$t('message.pages.setting.systemOutAccount.pushSwitch')" prop="switch">
          <el-switch v-model="settingData.push_open" :active-value="1" :inactive-value="0" />
        </el-form-item>
        <el-form-item :label="$t('message.pages.setting.systemOutAccount.pushAccount')" prop="push_account">
          <div class="form-content">
            <el-input type="text" v-model="settingData.push_account" :placeholder="$t('message.pages.setting.systemOutAccount.pushAccountPlaceholder')"></el-input>
            <span class="tips-info">{{ $t('message.pages.setting.systemOutAccount.pushAccountTip') }}</span>
          </div>
        </el-form-item>
        <el-form-item :label="$t('message.pages.setting.systemOutAccount.pushPassword')" prop="push_password">
          <div class="form-content">
            <el-input type="text" v-model="settingData.push_password" :placeholder="$t('message.pages.setting.systemOutAccount.pushPasswordPlaceholder')"></el-input>
            <span class="tips-info">{{ $t('message.pages.setting.systemOutAccount.pushPasswordTip') }}</span>
          </div>
        </el-form-item>
        <el-form-item :label="$t('message.pages.setting.systemOutAccount.getTokenApi')" prop="push_token_url">
          <div class="form-content">
            <div class="input-button">
              <el-input type="text" v-model="settingData.push_token_url" :placeholder="$t('message.pages.setting.systemOutAccount.getTokenPlaceholder')"></el-input>
              <el-button class="ml10" type="primary" v-db-click @click="textOutUrl(settingData.id)">{{ $t('message.pages.setting.systemOutAccount.testLink') }}</el-button>
            </div>
            <span class="tips-info">{{ $t('message.pages.setting.systemOutAccount.getTokenTip') }}</span>
          </div>
        </el-form-item>
        <el-form-item :label="$t('message.pages.setting.systemOutAccount.userUpdatePush')" prop="user_update_push">
          <div class="form-content">
            <el-input
              type="text"
              v-model="settingData.user_update_push"
              :placeholder="$t('message.pages.setting.systemOutAccount.userUpdatePushPlaceholder')"
            ></el-input>
            <span class="tips-info">{{ $t('message.pages.setting.systemOutAccount.userUpdatePushTip') }}</span>
          </div>
        </el-form-item>
        <el-form-item :label="$t('message.pages.setting.systemOutAccount.orderCreatePush')" prop="order_create_push">
          <div class="form-content">
            <el-input
              type="text"
              v-model="settingData.order_create_push"
              :placeholder="$t('message.pages.setting.systemOutAccount.orderCreatePushPlaceholder')"
            ></el-input>
            <span class="tips-info">{{ $t('message.pages.setting.systemOutAccount.orderCreatePushTip') }}</span>
          </div>
        </el-form-item>
        <el-form-item :label="$t('message.pages.setting.systemOutAccount.orderPayPush')" prop="order_pay_push">
          <div class="form-content">
            <el-input type="text" v-model="settingData.order_pay_push" :placeholder="$t('message.pages.setting.systemOutAccount.orderPayPushPlaceholder')"></el-input>
            <span class="tips-info">{{ $t('message.pages.setting.systemOutAccount.orderPayPushTip') }}</span>
          </div>
        </el-form-item>
        <el-form-item :label="$t('message.pages.setting.systemOutAccount.refundCreatePush')" prop="refund_create_push">
          <div class="form-content">
            <el-input
              type="text"
              v-model="settingData.refund_create_push"
              :placeholder="$t('message.pages.setting.systemOutAccount.refundCreatePushPlaceholder')"
            ></el-input>
            <span class="tips-info">{{ $t('message.pages.setting.systemOutAccount.refundCreatePushTip') }}</span>
          </div>
        </el-form-item>
        <el-form-item :label="$t('message.pages.setting.systemOutAccount.refundCancelPush')" prop="refund_cancel_push">
          <div class="form-content">
            <el-input
              type="text"
              v-model="settingData.refund_cancel_push"
              :placeholder="$t('message.pages.setting.systemOutAccount.refundCancelPushPlaceholder')"
            ></el-input>
            <span class="tips-info">{{ $t('message.pages.setting.systemOutAccount.refundCancelPushTip') }}</span>
          </div>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button type="primary" v-db-click @click="submit('settingData')">{{ $t('message.pages.setting.systemOutAccount.confirmBtn') }}</el-button>
        <el-button v-db-click @click="settingModals = false">{{ $t('message.pages.setting.systemOutAccount.cancelBtn') }}</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import {
  accountListApi,
  outSaveApi,
  outSavesApi,
  setShowApi,
  outSetUp,
  interfaceList,
  setUpPush,
  textOutUrl,
} from '@/api/systemOutAccount';
export default {
  name: 'systemOut',
  data() {
    return {
      grid: {
        xl: 7,
        lg: 7,
        md: 12,
        sm: 24,
        xs: 24,
      },
      total: 0,
      loading: false,
      roleData: {
        status1: '',
      },
      formValidate: {
        roles: '',
        status: '',
        name: '',
        page: 1, // 当前页
        limit: 20, // 每页显示条数
      },
      status: '',
      list: [],
      intList: [],
      FromData: null,
      modalTitleSs: '',
      ids: Number,
      modals: false,
      modalsid: '',
      type: 0,
      modalsdate: {
        appid: '',
        appsecret: '',
        title: '',
        rules: [],
      },
      settingModals: false,
      settingData: {
        switch: 1,
        name: '',
      },
      ruleValidate: {
        appid: [{ required: true, message: '', trigger: 'blur', min: 4, max: 30 }],
        appsecret: [{ required: true, message: '', trigger: 'blur', min: 6, max: 32 }],
        title: [{ message: '', trigger: 'blur', max: 200 }],
      },
      editValidate: {
        appsecret: [{ required: false, message: '', trigger: 'blur', min: 6, max: 32 }],
      },
      props: {
        label: 'title',
        disabled: 'disableCheckbox',
      },
      selectIds: [],
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '50px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  created() {
    this.ruleValidate.appid[0].message = this.$t('message.pages.setting.systemOutAccount.accountRequired');
    this.ruleValidate.appsecret[0].message = this.$t('message.pages.setting.systemOutAccount.passwordRequired');
    this.ruleValidate.title[0].message = this.$t('message.pages.setting.systemOutAccount.descMax');
    this.editValidate.appsecret[0].message = this.$t('message.pages.setting.systemOutAccount.passwordRequired');
    this.getList();
  },
  methods: {
    // 开启状态
    onchangeIsShow(row) {
      let data = {
        id: row.id,
        status: row.status,
      };
      setShowApi(data)
        .then(async (res) => {
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 请求列表
    submitFail() {
      this.getList();
    },
    // 列表
    getList() {
      this.loading = true;
      this.formValidate.roles = this.formValidate.roles || '';
      accountListApi(this.formValidate)
        .then(async (res) => {
          this.total = res.data.count;
          this.list = res.data.list;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // 添加
    add() {
      this.modals = true;
      this.type = 0;
      this.modalsdate = {
        appid: '',
        appsecret: '',
        title: '',
        rules: [],
      };
      this.getIntList();
    },
    selectTree(e, i) {},
    getIntList(type, list) {
      let arr = [];
      interfaceList().then((res) => {
        this.intList = res.data;
        if (!type) {
          this.intList.map((item) => {
            if (item.id === 1) {
              item.checked = true;
              item.disableCheckbox = true;
              arr.push(item.id);
              if (item.children.length) {
                item.children.map((v) => {
                  v.checked = true;
                  v.disableCheckbox = true;
                  arr.push(v.id);
                });
              }
            }
          });
          this.$nextTick((e) => {
            this.selectIds = arr;
          });
        } else {
          list.map((item) => {
            this.intList.map((e) => {
              if (e.id === 1) {
                e.checked = true;
                e.disableCheckbox = true;
                if (e.children.length) {
                  e.children.map((v) => {
                    v.checked = true;
                    v.disableCheckbox = true;
                  });
                }
              }
              listData(e.children || [], item);
            });
          });
          this.selectIds = list;
        }
        function listData(list, id) {
          if (list.length) {
            list.map((v) => {
              if (v.id == id) {
                v.checked = true;
              }
              if (v.children) {
                listData(v.children);
              }
            });
          }
        }
      });
    },
    // 编辑
    edit(row) {
      this.modals = true;
      this.modalsdate.appid = row.appid;
      this.modalsdate.title = row.title;
      this.modalsdate.appsecret = row.apppwd;
      this.modalsdate.rules = row.rules.map((e) => {
        return Number(e);
      });
      this.modalsid = row.id;
      this.type = 1;
      this.getIntList('edit', this.modalsdate.rules);
    },
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `setting/system_out_account/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.list.splice(num, 1);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 编辑
    setUp(row) {
      this.settingModals = true;
      this.settingData = row;
    },
    // 搜索
    userSearchs() {
      this.formValidate.status = this.status === 'all' ? '' : this.status;
      this.formValidate.page = 1;
      this.list = [];
      this.getList();
    },
    submit(name) {
      setUpPush(this.settingData).then((res) => {
        this.$message.success(res.msg);
        this.settingModals = false;
        this.getList();
      });
    },
    textOutUrl() {
      textOutUrl(this.settingData)
        .then((res) => {
          this.$message.success(res.msg);
        })
        .catch((err) => {
          this.$message.error(err.msg);
        });
    },
    ok(name) {
      let fuc = this.modalsid ? outSavesApi : outSaveApi;
      this.$refs[name].validate((valid) => {
        if (valid) {
          this.modalsdate.rules = [];
          this.$refs.tree.getCheckedNodes().map((node) => {
            this.modalsdate.rules.push(node.id);
          });
          if (this.modalsid) this.modalsdate.id = this.modalsid;
          fuc(this.modalsdate)
            .then((res) => {
              this.modalsdate = {
                appid: '',
                appsecret: '',
                title: '',
                rules: [],
              };
              (this.modals = false), this.$message.success(res.msg);
              this.modalsid = '';
              this.getList();
            })
            .catch((err) => {
              this.$message.error(err.msg);
            });
        } else {
          this.$message.warning(this.$t('message.pages.setting.systemOutAccount.completeData'));
        }
      });
    },
    cancel() {
      this.modalsid = '';
      this.modalsdate = {
        appid: '',
        appsecret: '',
        title: '',
      };
      this.modals = false;
    },
    reset() {
      let len = 16;
      let chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';
      let maxPos = chars.length;
      let pwd = '';
      for (let i = 0; i < len; i++) {
        pwd += chars.charAt(Math.floor(Math.random() * maxPos));
      }
      this.modalsdate.appsecret = pwd;
    },
  },
};
</script>

<style scoped>
.reset {
  margin-left: 10px;
}
.form-content {
  display: flex;
  flex-direction: column;
}
.input-button {
  display: flex;
}
.setting-style ::v-deep .ivu-form-item {
  margin-bottom: 14px;
}
.alert-info {
  margin-bottom: 14px;
}
</style>
