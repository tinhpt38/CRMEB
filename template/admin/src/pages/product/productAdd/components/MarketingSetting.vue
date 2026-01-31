<template>
  <!-- 营销设置 -->
  <el-row>
    <el-col :span="24">
      <el-form-item :label="$t('message.pages.product.add.buyGiveIntegral')" prop="give_integral">
        <el-input-number
          :controls="false"
          v-model="formValidate.give_integral"
          :min="0"
          :max="9999999999"
          :placeholder="$t('message.pages.product.add.inputIntegral')"
          class="input_width input-number-unit-class"
          :class-unit="$t('message.pages.product.add.integral')"
        />
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.pages.product.add.buyGiveCoupon')">
        <div v-if="couponName.length" class="mb10">
          <el-tag class="mr10" closable v-for="(item, index) in couponName" :key="index" @close="handleClose(item)">{{
            item.title
          }}</el-tag>
        </div>
        <el-button type="primary" v-db-click @click="addCoupon">{{ $t('message.pages.product.add.selectCoupon') }}</el-button>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.pages.product.add.linkUserLabel')" prop="label_id">
        <div style="display: flex">
          <div class="labelInput acea-row row-between-wrapper" v-db-click @click="openLabel">
            <div style="width: 90%">
              <div v-if="dataLabel.length">
                <el-tag closable v-for="(item, index) in dataLabel" @close="closeLabel(item)" :key="index">{{
                  item.label_name
                }}</el-tag>
              </div>
              <span class="span" v-else>{{ $t('message.pages.product.add.selectUserLabelSpan') }}</span>
            </div>
            <div class="iconfont iconxiayi"></div>
          </div>
          <span class="addfont" v-db-click @click="addLabel">{{ $t('message.pages.product.add.newLabel') }}</span>
        </div>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <div class="line"></div>
    </el-col>
    <el-col v-if="formValidate.virtual_type == 0" :span="24">
      <el-form-item :label="$t('message.pages.product.add.minQty')">
        <el-input-number
          :controls="false"
          :min="1"
          :max="9999999999"
          :precision="0"
          v-model="formValidate.min_qty"
          :placeholder="$t('message.pages.product.add.inputMinQty')"
          class="input_width input-number-unit-class"
          :class-unit="formValidate.unit_name || $t('message.pages.product.add.pcs')"
        />
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.pages.product.add.isLimit')">
        <el-switch
          v-model="formValidate.is_limit"
          class="defineSwitch"
          :active-text="$t('message.pages.product.add.open')"
          :inactive-text="$t('message.pages.product.add.close')"
          :active-value="1"
          :inactive-value="0"
          size="large"
        >
        </el-switch>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.pages.product.add.limitType')" v-if="formValidate.is_limit">
        <el-radio-group v-model="formValidate.limit_type">
          <el-radio :label="1">{{ $t('message.pages.product.add.limitOnce') }}</el-radio>
          <el-radio :label="2">{{ $t('message.pages.product.add.limitUser') }}</el-radio>
        </el-radio-group>
        <div class="tips-info">{{ $t('message.pages.product.add.limitTip') }}</div>
      </el-form-item>
    </el-col>
    <el-col :span="24" v-if="formValidate.is_limit">
      <el-form-item :label="$t('message.pages.product.add.limitNum')" prop="limit_num">
        <div class="acea-row row-middle">
          <el-input-number
            :controls="false"
            :placeholder="$t('message.pages.product.add.inputLimitNum')"
            :precision="0"
            :min="1"
            v-model="formValidate.limit_num"
            class="input_width input-number-unit-class"
            :class-unit="formValidate.unit_name || $t('message.pages.product.add.pcs')"
          />
        </div>
      </el-form-item>
    </el-col>
    <el-col  v-if="formValidate.is_limit" :span="24">
      <div class="line"></div>
    </el-col>
    <el-col :span="24" v-if="formValidate.virtual_type == 0 || formValidate.virtual_type == 3">
      <el-form-item :label="$t('message.pages.product.add.presaleProduct')">
        <el-switch
          v-model="formValidate.presale"
          class="defineSwitch"
          :active-text="$t('message.pages.product.add.open')"
          :inactive-text="$t('message.pages.product.add.close')"
          :active-value="1"
          :inactive-value="0"
          size="large"
        >
        </el-switch>
      </el-form-item>
    </el-col>
    <el-col :span="24" v-if="formValidate.presale">
      <el-form-item :label="$t('message.pages.product.add.presaleTime')" prop="presale_time">
        <div class="acea-row row-middle">
          <el-date-picker
            clearable
            :editable="false"
            type="datetimerange"
            format="yyyy-MM-dd HH:mm"
            value-format="yyyy-MM-dd HH:mm"
            range-separator="-"
            :start-placeholder="$t('message.pages.product.add.startDate')"
            :end-placeholder="$t('message.pages.product.add.endDate')"
            @change="onchangeTime"
            v-model="formValidate.presale_time"
          ></el-date-picker>
        </div>
        <div class="tips-info">{{ $t('message.pages.product.add.presaleTimeTip') }}</div>
      </el-form-item>
    </el-col>
    <el-col :span="24" v-if="formValidate.presale">
      <el-form-item :label="$t('message.pages.product.add.deliveryTime')" prop="presale_day">
        <div class="acea-row row-middle">
          <span class="mr10">{{ $t('message.pages.product.add.afterPresaleEnd') }}</span>
          <el-input-number
            class="w-80 input-number-unit-class"
            :controls="false"
            :placeholder="$t('message.pages.product.add.inputDeliveryTime')"
            :precision="0"
            :min="1"
            :class-unit="$t('message.pages.product.add.day')"
            v-model="formValidate.presale_day"
          />
          <span class="ml10"> {{ $t('message.pages.product.add.days') }} </span>
        </div>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <div class="line"></div>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.pages.product.add.productRecommend')">
        <el-checkbox-group v-model="formValidate.recommend">
          <el-checkbox label="is_hot">{{ $t('message.pages.product.add.hotProduct') }}</el-checkbox>
          <el-checkbox label="is_best">{{ $t('message.pages.product.add.bestRecommend') }}</el-checkbox>
          <el-checkbox label="is_new">{{ $t('message.pages.product.add.newProduct') }}</el-checkbox>
          <el-checkbox label="is_good">{{ $t('message.pages.product.add.goodRecommend') }}</el-checkbox>
        </el-checkbox-group>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.pages.product.add.activityPriority')">
        <div class="color-list acea-row row-middle">
          <div
            class="color-item"
            :class="activity[color]"
            v-for="color in formValidate.activity"
            v-dragging="{
              item: color,
              list: formValidate.activity,
              group: 'color',
            }"
            :key="color"
          >
            {{ color }}
          </div>
        </div>
        <div class="tips-info">{{ $t('message.pages.product.add.activityPriorityTip') }}</div>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.pages.product.add.goodRecommendProduct')">
        <div class="picBox">
          <div class="pictrue" v-for="(item, index) in formValidate.recommend_list" :key="index">
            <img v-lazy="item.image" />
            <i class="el-icon-error btndel" v-db-click @click="handleRemoveRecommend(index)"></i>
          </div>
          <div class="upLoad acea-row row-center-wrapper" v-db-click @click="changeGoods">
            <i class="el-icon-picture-outline" style="font-size: 24px"></i>
          </div>
        </div>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <div class="line"></div>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.pages.product.add.soldCount')">
        <el-input-number
          :controls="false"
          :min="0"
          :max="9999999999"
          v-model="formValidate.ficti"
          :placeholder="$t('message.pages.product.add.inputVirtualSales')"
          class="input_width input-number-unit-class"
          :class-unit="formValidate.unit_name || $t('message.pages.product.add.pcs')"
        />
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.pages.product.add.sort')">
        <el-input-number
          :controls="false"
          :min="0"
          :max="9999999999"
          v-model="formValidate.sort"
          :placeholder="$t('message.pages.product.add.sortPlaceholder')"
          class="input_width"
        />
      </el-form-item>
    </el-col>
  </el-row>
</template>

<script>
export default {
  name: 'MarketingSetting',
  props: {
    formValidate: {
      type: Object,
      required: true,
    },
    couponName: {
      type: Array,
      default: () => [],
    },
    dataLabel: {
      type: Array,
      default: () => [],
    },
    activity: {
      type: Object,
      default: () => ({}),
    },
  },
  methods: {
    handleClose(tag) {
      this.$emit('handleClose', tag);
    },
    addCoupon() {
      this.$emit('addCoupon');
    },
    openLabel() {
      this.$emit('openLabel');
    },
    closeLabel() {
      this.$emit('closeLabel');
    },
    addLabel() {
      this.$emit('addLabel');
    },
    onchangeTime(val) {
      this.$emit('onchangeTime', val);
    },
    handleRemoveRecommend(index) {
      this.$emit('handleRemoveRecommend', index);
    },
    changeGoods(val) {
      this.$emit('changeGoods', val);
    },
  },
};
</script>
<style lang="scss" scoped>
@use '../productAdd.scss' as *;
</style>
