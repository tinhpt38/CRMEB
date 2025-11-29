<template>
  <!-- 其他设置 -->
  <el-row>
    <el-col :span="24">
      <el-form-item :label="$t('message.productList.productKeyword2')">
        <el-input
          class="content_width"
          v-model.trim="formValidate.keyword"
          :placeholder="$t('message.productList.pleaseInputProductKeyword2')"
          maxlength="100"
          show-word-limit
        />
        <div class="tips-info">{{ $t('message.productList.seoOptimizationTip') }}</div>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productList.productBrief2')">
        <el-input
          class="content_width"
          v-model.trim="formValidate.store_info"
          type="textarea"
          :rows="3"
          :placeholder="$t('message.productList.pleaseInputProductBrief2')"
          maxlength="100"
          show-word-limit
        />
        <div class="tips-info">{{ $t('message.productList.productBriefTip') }}</div>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productList.productCommand')">
        <el-input
          v-model.trim="formValidate.command_word"
          :placeholder="$t('message.productList.pleaseInputProductCommand')"
          type="textarea"
          :rows="3"
          class="content_width"
        />
        <div class="tips-info">{{ $t('message.productList.productCommandTip') }}</div>
      </el-form-item>
    </el-col>

    <el-col :span="24">
      <el-form-item :label="$t('message.productList.productRecommendImage')">
        <div class="pictrueBox" v-db-click @click="modalPicTap('dan', 'recommend_image')">
          <div class="pictrue" v-if="formValidate.recommend_image">
            <img v-lazy="formValidate.recommend_image" />
            <el-input v-model.trim="formValidate.recommend_image" style="display: none"></el-input>
          </div>
          <div class="upLoad acea-row row-center-wrapper" v-else>
            <el-input v-model.trim="formValidate.recommend_image" style="display: none"></el-input>
            <i class="el-icon-picture-outline" style="font-size: 24px"></i>
          </div>
          <div class="tips-info">{{ $t('message.productList.recommendImageTip') }}</div>
        </div>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productList.productParams')">
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
            <el-table-column :label="$t('message.productList.paramName')" min-width="80">
              <template slot-scope="scope">
                <el-input v-model="scope.row.name"></el-input>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.productList.paramValue')" min-width="80">
              <template slot-scope="scope">
                <el-input v-model="scope.row.value"></el-input>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.productList.operation2')" fixed="right" width="80">
              <template slot-scope="scope">
                <a class="submission mr15" v-db-click @click="deleteRow(scope.$index)">{{ $t('message.productList.delete4') }}</a>
              </template>
            </el-table-column>
          </el-table>
          <el-button
            v-if="formValidate.params_list.length < 8 && paramsType"
            type="primary"
            class="submission mr15 mt20"
            v-db-click
            @click="handleAddParams"
            >{{ $t('message.productList.addParam') }}</el-button
          >
        </div>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productList.serviceGuarantee')">
        <el-checkbox-group v-model="formValidate.protection_list" v-if="protectionList.length">
          <el-checkbox v-for="(item, index) in protectionList" :key="index" :label="item.id">{{
            item.title
          }}</el-checkbox>
        </el-checkbox-group>
        <el-button v-else type="primary" v-db-click @click="addProtection">{{ $t('message.productList.addGuarantee') }}</el-button>
        <div class="tips-info">{{ $t('message.productList.serviceGuaranteeTip') }}</div>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productList.customForm')">
        <el-switch :active-value="1" :inactive-value="0" v-model="innerCustomBtn" size="large">
          <span slot="open">{{ $t('message.productList.open2') }}</span>
          <span slot="close">{{ $t('message.productList.close2') }}</span>
        </el-switch>
        <div class="addCustom_content" v-if="customBtn">
          <div v-for="(item, index) in formValidate.custom_form" :key="index" class="custom_box">
            <el-input
              v-model.trim="item.title"
              :placeholder="$t('message.productList.formTitle') + (index + 1)"
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
            <el-checkbox v-model="item.status">{{ $t('message.productList.required') }}</el-checkbox>
            <div class="addfont" v-db-click @click="delcustom(index)">{{ $t('message.productList.delete5') }}</div>
          </div>
        </div>
        <div class="addCustomBox" v-show="customBtn">
          <div class="btn" v-db-click @click="addcustom">{{ $t('message.productList.addForm') }}</div>
          <div class="tips-info">{{ $t('message.productList.customFormTip') }}</div>
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
