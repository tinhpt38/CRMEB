<template>
  <div>
    <el-dialog :visible.sync="modals" width="720px" :title="titleFrom" :close-on-click-modal="false">
      <el-form
        ref="formValidate"
        :model="formValidate"
        label-width="100px"
        :rules="ruleValidate"
        @submit.native.prevent
      >
        <el-row :gutter="24">
          <el-col :span="24">
            <el-form-item :label="$t('message.pages.system.groupForm.groupNameLabel')" prop="name">
              <el-input v-model="formValidate.name" :placeholder="$t('message.pages.system.groupForm.groupNamePlaceholder')" style="width: 90%"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item :label="$t('message.pages.system.groupForm.dataFieldLabel')" prop="config_name">
              <el-input v-model="formValidate.config_name" :placeholder="$t('message.pages.system.groupForm.dataFieldPlaceholder')" style="width: 90%"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item :label="$t('message.pages.system.groupForm.dataIntroLabel')" prop="info">
              <el-input v-model="formValidate.info" :placeholder="$t('message.pages.system.groupForm.dataIntroPlaceholder')" style="width: 90%"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item :label="$t('message.pages.system.groupForm.dataTypeLabel')" prop="cate_id">
              <el-radio-group v-model="formValidate.cate_id">
                <el-radio :label="0">{{ $t('message.pages.system.groupForm.default') }}</el-radio>
                <el-radio :label="1">{{ $t('message.pages.system.groupForm.data') }}</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="24" v-for="(item, index) in formValidate.typelist" :key="index">
            <el-col v-bind="grid">
              <el-form-item
                :label="$t('message.pages.system.groupForm.fieldN') + (index + 1) + '：'"
                label-width="90px"
                :prop="'typelist.' + index + '.name.value'"
                :rules="fieldNameRules"
              >
                <el-input v-model="item.name.value" :placeholder="$t('message.pages.system.groupForm.fieldNamePlaceholder')"></el-input>
              </el-form-item>
            </el-col>
            <el-col v-bind="grid" class="goupBox">
              <el-form-item
                label-width="0"
                :prop="'typelist.' + index + '.title.value'"
                :rules="fieldConfigRules"
              >
                <el-input v-model="item.title.value" :placeholder="$t('message.pages.system.groupForm.fieldConfigPlaceholder')"></el-input>
              </el-form-item>
            </el-col>
            <el-col v-bind="grid" prop="type" class="goupBox">
              <el-form-item
                :prop="'typelist.' + index + '.type.value'"
                :rules="fieldTypeRules"
                label-width="0"
              >
                <el-select :placeholder="$t('message.pages.system.groupForm.fieldTypePlaceholder')" v-model="item.type.value">
                  <el-option value="input" :label="$t('message.pages.system.groupForm.input')"></el-option>
                  <el-option value="textarea" :label="$t('message.pages.system.groupForm.textarea')"></el-option>
                  <el-option value="radio" :label="$t('message.pages.system.groupForm.radio')"></el-option>
                  <el-option value="checkbox" :label="$t('message.pages.system.groupForm.checkbox')"></el-option>
                  <el-option value="select" :label="$t('message.pages.system.groupForm.select')"></el-option>
                  <el-option value="upload" :label="$t('message.pages.system.groupForm.singleImage')"></el-option>
                  <el-option value="uploads" :label="$t('message.pages.system.groupForm.multiImage')"></el-option>
                </el-select>
              </el-form-item>
            </el-col>
            <el-button icon="el-icon-delete" v-db-click @click="delGroup(index)"></el-button>
            <!-- <el-col span="1"> </el-col> -->
            <el-col
              :span="24"
              v-if="item.type.value === 'radio' || item.type.value === 'checkbox' || item.type.value === 'select'"
            >
              <el-form-item
                :prop="'typelist.' + index + '.param.value'"
                :rules="paramRules"
              >
                <el-input
                  type="textarea"
                  :rows="4"
                  :placeholder="item.param.placeholder"
                  v-model="item.param.value"
                  style="width: 90%"
                ></el-input>
              </el-form-item>
            </el-col>
          </el-col>
          <el-col>
            <el-form-item>
              <el-button type="primary" v-db-click @click="addType">{{ $t('message.pages.system.groupForm.addField') }}</el-button>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button v-db-click @click="handleReset">{{ $t('message.pages.system.groupForm.cancel') }}</el-button>
        <el-button type="primary" v-db-click @click="handleSubmit('formValidate')" :disabled="valids">{{ $t('message.pages.system.groupForm.confirm') }}</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { groupAddApi, groupInfoApi } from '@/api/system';
export default {
  name: 'menusFrom',
  props: {
    groupId: {
      type: Number,
      default: 0,
    },
    titleFrom: {
      type: String,
      default: '',
    },
    addId: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      iconVal: '',
      grid: {
        xl: 7,
        lg: 7,
        md: 12,
        sm: 24,
        xs: 24,
      },
      modals: false,
      modal12: false,
      ruleValidate: {
        name: [{ required: true, message: '', trigger: 'blur' }],
        config_name: [{ required: true, message: '', trigger: 'blur' }],
        info: [{ required: true, message: '', trigger: 'blur' }],
        names: [{ required: true, message: '', trigger: 'blur' }],
      },
      FromData: [],
      valids: false,
      list2: [],
      formValidate: {
        name: '',
        config_name: '',
        info: '',
        typelist: [],
        cate_id: 0,
      },
    };
  },
  computed: {
    fieldNameRules() {
      return [{ required: true, message: this.$t('message.pages.system.groupForm.inputFieldName'), trigger: 'blur' }];
    },
    fieldConfigRules() {
      return [{ required: true, message: this.$t('message.pages.system.groupForm.inputFieldConfigName'), trigger: 'blur' }];
    },
    fieldTypeRules() {
      return [{ required: true, message: this.$t('message.pages.system.groupForm.selectFieldType'), trigger: 'change' }];
    },
    paramRules() {
      return [{ required: true, message: this.$t('message.pages.system.groupForm.inputParam'), trigger: 'blur' }];
    },
  },
  watch: {
    addId(n) {
      if (n === 'addId') {
        this.formValidate.typelist = [];
      }
    },
  },
  mounted() {
    this.ruleValidate.name[0].message = this.$t('message.pages.system.groupForm.groupNamePlaceholder');
    this.ruleValidate.config_name[0].message = this.$t('message.pages.system.groupForm.dataFieldPlaceholder');
    this.ruleValidate.info[0].message = this.$t('message.pages.system.groupForm.dataIntroPlaceholder');
    this.ruleValidate.names[0].message = this.$t('message.pages.system.groupForm.inputFieldName');
  },
  methods: {
    // 点击添加字段
    addType() {
      this.formValidate.typelist.push({
        name: {
          value: '',
        },
        title: {
          value: '',
        },
        type: {
          value: '',
        },
        param: {
          placeholder: this.$t('message.pages.system.groupForm.inputParam'),
          value: '',
        },
      });
    },
    // 删除字段
    delGroup(index) {
      this.formValidate.typelist.splice(index, 1);
    },
    // 详情
    fromData(id) {
      groupInfoApi(id)
        .then(async (res) => {
          this.formValidate = res.data.info;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 提交
    handleSubmit(name) {
      let data = {
        url: this.groupId ? `/setting/group/${this.groupId}` : 'setting/group',
        method: this.groupId ? 'put' : 'post',
        datas: this.formValidate,
      };
      this.$refs[name].validate((valid) => {
        if (valid) {
          if (this.formValidate.typelist.length === 0) return this.$message.error(this.$t('message.pages.system.groupForm.inputFieldName'));
          groupAddApi(data)
            .then(async (res) => {
              this.$message.success(res.msg);
              this.modals = false;
              this.$refs[name].resetFields();
              this.formValidate.typelist = [];
              this.$emit('getList');
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
        } else {
          if (!this.formValidate.name) return this.$message.error(this.$t('message.pages.system.groupForm.groupNamePlaceholder'));
          if (!this.formValidate.config_name) return this.$message.error(this.$t('message.pages.system.groupForm.dataFieldPlaceholder'));
          if (!this.formValidate.info) return this.$message.error(this.$t('message.pages.system.groupForm.dataIntroPlaceholder'));
        }
      });
    },
    handleReset() {
      this.modals = false;
      this.$refs['formValidate'].resetFields();
      this.$emit('clearFrom');
    },
  },
};
</script>

<style lang="scss" scoped>
.cur {
  cursor: pointer;
}
.goupBox ::v-deep .ivu-form-item-content {
  margin-left: 43px !important;
}
</style>
