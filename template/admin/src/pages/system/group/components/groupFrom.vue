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
            <el-form-item :label="$t('message.systemMenus.dataGroupName') + '：'" prop="name">
              <el-input v-model="formValidate.name" :placeholder="$t('message.systemMenus.pleaseInputDataGroupName')" style="width: 90%"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item :label="$t('message.systemMenus.dataField') + '：'" prop="config_name">
              <el-input v-model="formValidate.config_name" :placeholder="$t('message.systemMenus.pleaseInputDataField')" style="width: 90%"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item :label="$t('message.systemMenus.dataIntro') + '：'" prop="info">
              <el-input v-model="formValidate.info" :placeholder="$t('message.systemMenus.pleaseInputDataIntro')" style="width: 90%"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item :label="$t('message.systemMenus.dataType') + '：'" prop="cate_id">
              <el-radio-group v-model="formValidate.cate_id">
                <el-radio :label="0">{{ $t('message.systemMenus.default') }}</el-radio>
                <el-radio :label="1">{{ $t('message.systemMenus.data') }}</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="24" v-for="(item, index) in formValidate.typelist" :key="index">
            <el-col v-bind="grid">
              <el-form-item
                :label="$t('message.systemMenus.field') + (index + 1) + '：'"
                label-width="90px"
                :prop="'typelist.' + index + '.name.value'"
                :rules="{ required: true, message: $t('message.systemMenus.pleaseInputFieldNameExample'), trigger: 'blur' }"
              >
                <el-input v-model="item.name.value" :placeholder="$t('message.systemMenus.fieldNameExample')"></el-input>
              </el-form-item>
            </el-col>
            <el-col v-bind="grid" class="goupBox">
              <el-form-item
                label-width="0"
                :prop="'typelist.' + index + '.title.value'"
                :rules="{ required: true, message: $t('message.systemMenus.pleaseInputFieldConfigName'), trigger: 'blur' }"
              >
                <el-input v-model="item.title.value" :placeholder="$t('message.systemMenus.fieldConfigNameExample')"></el-input>
              </el-form-item>
            </el-col>
            <el-col v-bind="grid" prop="type" class="goupBox">
              <el-form-item
                :prop="'typelist.' + index + '.type.value'"
                :rules="{ required: true, message: $t('message.systemMenus.pleaseSelectFieldType'), trigger: 'change' }"
                label-width="0"
              >
                <el-select :placeholder="$t('message.systemMenus.fieldType')" v-model="item.type.value">
                  <el-option value="input">{{ $t('message.systemMenus.textBox') }}</el-option>
                  <el-option value="textarea">{{ $t('message.systemMenus.multiLineTextBox') }}</el-option>
                  <el-option value="radio">{{ $t('message.systemMenus.radioButton') }}</el-option>
                  <el-option value="checkbox">{{ $t('message.systemMenus.checkbox') }}</el-option>
                  <el-option value="select">{{ $t('message.systemMenus.dropdownSelect') }}</el-option>
                  <el-option value="upload">{{ $t('message.systemMenus.singleImage') }}</el-option>
                  <el-option value="uploads">{{ $t('message.systemMenus.multipleImages') }}</el-option>
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
                :rules="{ required: true, message: $t('message.systemMenus.pleaseInputParameterMethod'), trigger: 'blur' }"
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
              <el-button type="primary" v-db-click @click="addType">{{ $t('message.systemMenus.addField') }}</el-button>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button v-db-click @click="handleReset">{{ $t('message.systemMenus.cancel') }}</el-button>
        <el-button type="primary" v-db-click @click="handleSubmit('formValidate')" :disabled="valids">{{ $t('message.systemMenus.confirm') }}</el-button>
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
      ruleValidate: {},
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
  watch: {
    addId(n) {
      if (n === 'addId') {
        this.formValidate.typelist = [];
      }
    },
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
          placeholder: this.$t('message.systemMenus.parameterMethodExample'),
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
          if (this.formValidate.typelist.length === 0) return this.$message.error(this.$t('message.systemMenus.pleaseAddFieldNameExample'));
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
          if (!this.formValidate.name) return this.$message.error(this.$t('message.systemMenus.pleaseAddDataGroupName'));
          if (!this.formValidate.config_name) return this.$message.error(this.$t('message.systemMenus.pleaseAddDataField'));
          if (!this.formValidate.info) return this.$message.error(this.$t('message.systemMenus.pleaseAddDataIntro'));
        }
      });
    },
    handleReset() {
      this.modals = false;
      this.$refs['formValidate'].resetFields();
      this.$emit('clearFrom');
    },
  },
  created() {
    // Initialize ruleValidate with i18n
    this.ruleValidate = {
      name: [{ required: true, message: this.$t('message.systemMenus.pleaseInputDataGroupName'), trigger: 'blur' }],
      config_name: [{ required: true, message: this.$t('message.systemMenus.pleaseInputDataField'), trigger: 'blur' }],
      info: [{ required: true, message: this.$t('message.systemMenus.pleaseInputDataIntro'), trigger: 'blur' }],
      names: [{ required: true, message: this.$t('message.systemMenus.pleaseInputFieldName'), trigger: 'blur' }],
    };
  },
  mounted() {},
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
