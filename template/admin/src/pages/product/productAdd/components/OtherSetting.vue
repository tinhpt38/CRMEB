<template>
  <el-row>
    <el-col :span="24">
      <el-form-item :label="$t('message.pages.product.add.keyword')">
        <el-input
          class="content_width"
          v-model.trim="formValidate.keyword"
          :placeholder="$t('message.pages.product.add.inputKeyword')"
          maxlength="100"
          show-word-limit
        />
        <div class="tips-info">{{ $t('message.pages.product.add.keywordTip') }}</div>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.pages.product.add.productIntro')">
        <el-input
          class="content_width"
          v-model.trim="formValidate.store_info"
          type="textarea"
          :rows="3"
          :placeholder="$t('message.pages.product.add.inputProductIntro')"
          maxlength="100"
          show-word-limit
        />
        <div class="tips-info">{{ $t('message.pages.product.add.introTip') }}</div>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.pages.product.add.commandWord')">
        <el-input
          v-model.trim="formValidate.command_word"
          :placeholder="$t('message.pages.product.add.inputCommandWord')"
          type="textarea"
          :rows="3"
          class="content_width"
        />
        <div class="tips-info">{{ $t('message.pages.product.add.commandWordTip') }}</div>
      </el-form-item>
    </el-col>

    <el-col :span="24">
      <el-form-item :label="$t('message.pages.product.add.recommendImage')">
        <div class="pictrueBox" v-db-click @click="modalPicTap('dan', 'recommend_image')">
          <div class="pictrue" v-if="formValidate.recommend_image">
            <img v-lazy="formValidate.recommend_image" />
            <el-input v-model.trim="formValidate.recommend_image" style="display: none"></el-input>
          </div>
          <div class="upLoad acea-row row-center-wrapper" v-else>
            <el-input v-model.trim="formValidate.recommend_image" style="display: none"></el-input>
            <i class="el-icon-picture-outline" style="font-size: 24px"></i>
          </div>
          <div class="tips-info">{{ $t('message.pages.product.add.recommendImageTip') }}</div>
        </div>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.pages.product.add.productParams')">
        <el-select
          v-model="paramsType"
          clearable
          style="width: 200px; margin-left: 6px; margin-right: 10px"
          @change="changeParamsType"
        >
          <el-option v-for="items in paramsTypeList" :value="items.id" :key="items.id" :label="items.name"></el-option>
        </el-select>
        <div class="specifications">
          <el-table
            v-if="paramsType || formValidate.params_list.length"
            class="mt15"
            ref="selection"
            :data="formValidate.params_list"
          >
            <el-table-column :label="$t('message.pages.product.add.paramName')" min-width="80">
              <template slot-scope="scope">
                <el-input v-model="scope.row.name"></el-input>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.pages.product.add.paramValue')" min-width="80">
              <template slot-scope="scope">
                <el-input v-model="scope.row.value"></el-input>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.pages.product.add.action')" fixed="right" width="80">
              <template slot-scope="scope">
                <a class="submission mr15" v-db-click @click="deleteRow(scope.$index)">{{ $t('message.pages.product.add.del') }}</a>
              </template>
            </el-table-column>
          </el-table>
          <el-button
            v-if="formValidate.params_list.length < 8 && paramsType"
            type="primary"
            class="submission mr15 mt20"
            v-db-click
            @click="handleAddParams"
            >{{ $t('message.pages.product.add.addParam') }}</el-button
          >
        </div>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.pages.product.add.serviceGuarantee')">
        <el-checkbox-group v-model="formValidate.protection_list" v-if="protectionList.length">
          <el-checkbox v-for="(item, index) in protectionList" :key="index" :label="item.id">{{
            item.title
          }}</el-checkbox>
        </el-checkbox-group>
        <el-button v-else type="primary" v-db-click @click="addProtection">{{ $t('message.pages.product.add.addProtection') }}</el-button>
        <div class="tips-info">{{ $t('message.pages.product.add.protectionTip') }}</div>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.pages.product.add.customForm')">
        <el-switch :active-value="1" :inactive-value="0" v-model="innerCustomBtn" size="large">
          <span slot="open">{{ $t('message.pages.product.add.open') }}</span>
          <span slot="close">{{ $t('message.pages.product.add.close') }}</span>
        </el-switch>
        <div class="addCustom_content" v-if="customBtn">
          <div v-for="(item, index) in formValidate.custom_form" :key="index" class="custom_box">
            <el-input
              v-model.trim="item.title"
              :placeholder="$t('message.pages.product.add.formTitle') + (index + 1)"
              style="width: 150px; margin-right: 10px"
              maxlength="10"
              show-word-limit
            />
            <el-select v-model="item.label" style="width: 200px; margin-left: 6px; margin-right: 10px">
              <el-option
                v-for="items in CustomList"
                :value="items.value"
                :key="items.value"
                :label="items.label"
              ></el-option>
            </el-select>
            <el-checkbox v-model="item.status">{{ $t('message.pages.product.add.required') }}</el-checkbox>
            <div class="addfont" v-db-click @click="delcustom(index)">{{ $t('message.pages.product.add.delForm') }}</div>
          </div>
        </div>
        <div class="addCustomBox" v-show="customBtn">
          <div class="btn" v-db-click @click="addcustom">{{ $t('message.pages.product.add.addForm') }}</div>
          <div class="tips-info">{{ $t('message.pages.product.add.customFormTip') }}</div>
        </div>
      </el-form-item>
    </el-col>
  </el-row>
</template>

<script>
export default {
  name: 'OtherSetting',
  props: {
    formValidate: {
      type: Object,
      required: true,
    },
    customBtn: {
      type: Number,
      default: 0,
    },
    paramsType: {
      type: Number,
      default: 0,
    },
    paramsTypeList: {
      type: Array,
      default: () => [],
    },
    protectionList: {
      type: Array,
      default: () => [],
    },
    CustomList: {
      type: Array,
      default: () => [],
    },
  },
  computed: {
    innerCustomBtn: {
      get() {
        return this.customBtn;
      },
      set(val) {
        this.$emit('customMessBtn', val);
      },
    },
  },
  methods: {
    modalPicTap(tit, type) {
      this.$emit('modalPicTap', tit, type);
    },
    changeParamsType(val) {
      this.$emit('changeParamsType', val);
    },
    deleteRow(index) {
      this.$emit('deleteRow', index);
    },
    handleAddParams() {
      this.$emit('handleAddParams');
    },
    addProtection() {
      this.$emit('addProtection');
    },
    // customMessBtn(e) {
    //   console.log(e);
    //   this.$emit('customMessBtn', e);
    // },
    delcustom(index) {
      this.$emit('delcustom', index);
    },
    addcustom() {
      this.$emit('addcustom');
    },
  },
};
</script>
<style lang="scss" scoped>
@use '../productAdd.scss' as *;
</style>
