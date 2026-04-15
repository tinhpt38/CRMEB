<template>
  <!-- 营销设置 -->
  <el-row>
    <el-col :span="24">
      <el-form-item :label="$t('message.productAdd.buyGiftPoint') + '：'" prop="give_integral">
        <el-input-number
          :controls="false"
          v-model="formValidate.give_integral"
          :min="0"
          :max="9999999999"
          :placeholder="$t('message.productList.enterPoints')"
          class="input_width input-number-unit-class"
          :class-unit="$t('message.productList.pointsUnit')"
        />
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productAdd.buyGiftCoupon') + '：'">
        <div v-if="couponName.length" class="mb10">
          <el-tag class="mr10" closable v-for="(item, index) in couponName" :key="index" @close="handleClose(item)">{{
            item.title
          }}</el-tag>
        </div>
        <el-button type="primary" v-db-click @click="addCoupon">{{ $t('message.productAdd.selectCoupon') }}</el-button>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productAdd.relatedUserLabel') + '：'" prop="label_id">
        <div style="display: flex">
          <div class="labelInput acea-row row-between-wrapper" v-db-click @click="openLabel">
            <div style="width: 90%">
              <div v-if="dataLabel.length">
                <el-tag closable v-for="(item, index) in dataLabel" @close="closeLabel(item)" :key="index">{{
                  item.label_name
                }}</el-tag>
              </div>
              <span class="span" v-else>{{ $t('message.productList.selectRelatedUserTag') }}</span>
            </div>
            <div class="iconfont iconxiayi"></div>
          </div>
          <span class="addfont" v-db-click @click="addLabel">{{ $t('message.productAdd.addLabel') }}</span>
        </div>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <div class="line"></div>
    </el-col>
    <el-col v-if="formValidate.virtual_type == 0" :span="24">
      <el-form-item :label="$t('message.productAdd.minPurchaseQty') + '：'">
        <el-input-number
          :controls="false"
          :min="1"
          :max="9999999999"
          :precision="0"
          v-model="formValidate.min_qty"
          :placeholder="$t('message.productAdd.minPurchaseQtyPlaceholder')"
          class="input_width input-number-unit-class"
          :class-unit="formValidate.unit_name || $t('message.productAdd.itemUnit')"
        />
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productAdd.isLimit') + '：'">
        <el-switch
          v-model="formValidate.is_limit"
          class="defineSwitch"
          :active-text="$t('message.productCommon.enable')"
          :inactive-text="$t('message.productCommon.disable')"
          :active-value="1"
          :inactive-value="0"
          size="large"
        >
        </el-switch>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productAdd.limitType') + '：'" v-if="formValidate.is_limit">
        <el-radio-group v-model="formValidate.limit_type">
          <el-radio :label="1">{{ $t('message.productAdd.singleOrderLimit') }}</el-radio>
          <el-radio :label="2">{{ $t('message.productAdd.singleUserLimit') }}</el-radio>
        </el-radio-group>
        <div class="tips-info">{{ $t('message.productAdd.limitTypeTip') }}</div>
      </el-form-item>
    </el-col>
    <el-col :span="24" v-if="formValidate.is_limit">
      <el-form-item :label="$t('message.productAdd.limitQty') + '：'" prop="limit_num">
        <div class="acea-row row-middle">
          <el-input-number
            :controls="false"
            :placeholder="$t('message.productAdd.limitQtyPlaceholder')"
            :precision="0"
            :min="1"
            v-model="formValidate.limit_num"
            class="input_width input-number-unit-class"
            :class-unit="formValidate.unit_name || $t('message.productAdd.itemUnit')"
          />
        </div>
      </el-form-item>
    </el-col>
    <el-col  v-if="formValidate.is_limit" :span="24">
      <div class="line"></div>
    </el-col>
    <el-col :span="24" v-if="formValidate.virtual_type == 0 || formValidate.virtual_type == 3">
      <el-form-item :label="$t('message.productAdd.presaleProduct') + '：'">
        <el-switch
          v-model="formValidate.presale"
          class="defineSwitch"
          :active-text="$t('message.productCommon.enable')"
          :inactive-text="$t('message.productCommon.disable')"
          :active-value="1"
          :inactive-value="0"
          size="large"
        >
        </el-switch>
      </el-form-item>
    </el-col>
    <el-col :span="24" v-if="formValidate.presale">
      <el-form-item :label="$t('message.productAdd.presaleTime') + '：'" prop="presale_time">
        <div class="acea-row row-middle">
          <el-date-picker
            clearable
            :editable="false"
            type="datetimerange"
            format="yyyy-MM-dd HH:mm"
            value-format="yyyy-MM-dd HH:mm"
            range-separator="-"
            :start-placeholder="$t('message.productList.startDate')"
            :end-placeholder="$t('message.productList.endDate')"
            @change="onchangeTime"
            v-model="formValidate.presale_time"
          ></el-date-picker>
        </div>
        <div class="tips-info">{{ $t('message.productAdd.presaleTimeTip') }}</div>
      </el-form-item>
    </el-col>
    <el-col :span="24" v-if="formValidate.presale">
      <el-form-item :label="$t('message.productAdd.deliveryTime') + '：'" prop="presale_day">
        <div class="acea-row row-middle">
          <span class="mr10">{{ $t('message.productAdd.afterPresaleEnd') }}</span>
          <el-input-number
            class="w-80 input-number-unit-class"
            :controls="false"
            :placeholder="$t('message.productAdd.deliveryTimePlaceholder')"
            :precision="0"
            :min="1"
            :class-unit="$t('message.productAdd.dayUnit')"
            v-model="formValidate.presale_day"
          />
          <span class="ml10"> {{ $t('message.productAdd.within') }} </span>
        </div>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <div class="line"></div>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productAdd.productRecommend') + '：'">
        <el-checkbox-group v-model="formValidate.recommend">
          <el-checkbox label="is_hot">{{ $t('message.productList.hotSaleSingle') }}</el-checkbox>
          <el-checkbox label="is_best">{{ $t('message.productList.premiumRecommend') }}</el-checkbox>
          <el-checkbox label="is_new">{{ $t('message.productList.firstNew') }}</el-checkbox>
          <el-checkbox label="is_good">{{ $t('message.productList.goodProductRecommend') }}</el-checkbox>
        </el-checkbox-group>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productAdd.activityPriority') + '：'">
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
        <div class="tips-info">{{ $t('message.productAdd.activityPriorityTip') }}</div>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productAdd.goodRecommendProduct') + '：'">
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
      <el-form-item :label="$t('message.productAdd.soldCount') + '：'">
        <el-input-number
          :controls="false"
          :min="0"
          :max="9999999999"
          v-model="formValidate.ficti"
          :placeholder="$t('message.productAdd.virtualSalesPlaceholder')"
          class="input_width input-number-unit-class"
          :class-unit="formValidate.unit_name || $t('message.productAdd.itemUnit')"
        />
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productList.sort') + '：'">
        <el-input-number
          :controls="false"
          :min="0"
          :max="9999999999"
          v-model="formValidate.sort"
          :placeholder="$t('message.productAdd.sortPlaceholder')"
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
