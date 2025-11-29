<template>
  <!-- 营销设置 -->
  <el-row>
    <el-col :span="24">
      <el-form-item :label="$t('message.productList.buySendPoints2')" prop="give_integral">
        <el-input-number
          :controls="false"
          v-model="formValidate.give_integral"
          :min="0"
          :max="9999999999"
          :placeholder="$t('message.productList.pleaseInputPoints2')"
          class="input_width input-number-unit-class"
          class-unit="积分"
        />
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productList.buySendCoupon2')">
        <div v-if="couponName.length" class="mb10">
          <el-tag class="mr10" closable v-for="(item, index) in couponName" :key="index" @close="handleClose(item)">{{
            item.title
          }}</el-tag>
        </div>
        <el-button type="primary" v-db-click @click="addCoupon">{{ $t('message.productList.selectCoupon4') }}</el-button>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productList.linkUserLabel2')" prop="label_id">
        <div style="display: flex">
          <div class="labelInput acea-row row-between-wrapper" v-db-click @click="openLabel">
            <div style="width: 90%">
              <div v-if="dataLabel.length">
                <el-tag closable v-for="(item, index) in dataLabel" @close="closeLabel(item)" :key="index">{{
                  item.label_name
                }}</el-tag>
              </div>
              <span class="span" v-else>{{ $t('message.productList.selectUserLinkLabel') }}</span>
            </div>
            <div class="iconfont iconxiayi"></div>
          </div>
          <span class="addfont" v-db-click @click="addLabel">{{ $t('message.productList.addLabel2') }}</span>
        </div>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <div class="line"></div>
    </el-col>
    <el-col v-if="formValidate.virtual_type == 0" :span="24">
      <el-form-item :label="$t('message.productList.minPurchaseQty')">
        <el-input-number
          :controls="false"
          :min="1"
          :max="9999999999"
          :precision="0"
          v-model="formValidate.min_qty"
          :placeholder="$t('message.productList.pleaseInputMinPurchaseQty')"
          class="input_width input-number-unit-class"
          :class-unit="formValidate.unit_name || $t('message.productList.piece')"
        />
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productList.isLimitPurchase')">
        <el-switch
          v-model="formValidate.is_limit"
          class="defineSwitch"
          :active-text="$t('message.productList.open3')"
          :inactive-text="$t('message.productList.close3')"
          :active-value="1"
          :inactive-value="0"
          size="large"
        >
        </el-switch>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productList.limitPurchaseType')" v-if="formValidate.is_limit">
        <el-radio-group v-model="formValidate.limit_type">
          <el-radio :label="1">{{ $t('message.productList.singleLimitPurchase') }}</el-radio>
          <el-radio :label="2">{{ $t('message.productList.singleUserLimitPurchase') }}</el-radio>
        </el-radio-group>
        <div class="tips-info">{{ $t('message.productList.limitPurchaseTip') }}</div>
      </el-form-item>
    </el-col>
    <el-col :span="24" v-if="formValidate.is_limit">
      <el-form-item :label="$t('message.productList.limitPurchaseQty')" prop="limit_num">
        <div class="acea-row row-middle">
          <el-input-number
            :controls="false"
            :placeholder="$t('message.productList.pleaseInputLimitPurchaseQty')"
            :precision="0"
            :min="1"
            v-model="formValidate.limit_num"
            class="input_width input-number-unit-class"
            :class-unit="formValidate.unit_name || $t('message.productList.piece')"
          />
        </div>
      </el-form-item>
    </el-col>
    <el-col  v-if="formValidate.is_limit" :span="24">
      <div class="line"></div>
    </el-col>
    <el-col :span="24" v-if="formValidate.virtual_type == 0 || formValidate.virtual_type == 3">
      <el-form-item :label="$t('message.productList.presaleProduct')">
        <el-switch
          v-model="formValidate.presale"
          class="defineSwitch"
          :active-text="$t('message.productList.open3')"
          :inactive-text="$t('message.productList.close3')"
          :active-value="1"
          :inactive-value="0"
          size="large"
        >
        </el-switch>
      </el-form-item>
    </el-col>
    <el-col :span="24" v-if="formValidate.presale">
      <el-form-item :label="$t('message.productList.presaleActivityTime')" prop="presale_time">
        <div class="acea-row row-middle">
          <el-date-picker
            clearable
            :editable="false"
            type="datetimerange"
            format="yyyy-MM-dd HH:mm"
            value-format="yyyy-MM-dd HH:mm"
            range-separator="-"
            :start-placeholder="$t('message.productList.startDate2')"
            :end-placeholder="$t('message.productList.endDate2')"
            @change="onchangeTime"
            v-model="formValidate.presale_time"
          ></el-date-picker>
        </div>
        <div class="tips-info">{{ $t('message.productList.presaleTip') }}</div>
      </el-form-item>
    </el-col>
    <el-col :span="24" v-if="formValidate.presale">
      <el-form-item :label="$t('message.productList.deliveryTime')" prop="presale_day">
        <div class="acea-row row-middle">
          <span class="mr10">{{ $t('message.productList.afterPresaleActivity') }}</span>
          <el-input-number
            class="w-80 input-number-unit-class"
            :controls="false"
            :placeholder="$t('message.productList.pleaseInputDeliveryTime')"
            :precision="0"
            :min="1"
            :class-unit="$t('message.productList.day')"
            v-model="formValidate.presale_day"
          />
          <span class="ml10"> {{ $t('message.productList.within') }} </span>
        </div>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <div class="line"></div>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productList.productRecommend')">
        <el-checkbox-group v-model="formValidate.recommend">
          <el-checkbox label="is_hot">{{ $t('message.productList.hotSale') }}</el-checkbox>
          <el-checkbox label="is_best">{{ $t('message.productList.bestRecommend') }}</el-checkbox>
          <el-checkbox label="is_new">{{ $t('message.productList.newProduct') }}</el-checkbox>
          <el-checkbox label="is_good">{{ $t('message.productList.goodRecommend') }}</el-checkbox>
        </el-checkbox-group>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productList.activityPriority')">
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
        <div class="tips-info">{{ $t('message.productList.dragToAdjustPriority') }}</div>
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productList.goodRecommendProducts')">
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
      <el-form-item :label="$t('message.productList.soldQuantity')">
        <el-input-number
          :controls="false"
          :min="0"
          :max="9999999999"
          v-model="formValidate.ficti"
          :placeholder="$t('message.productList.pleaseInputVirtualSales2')"
          class="input_width input-number-unit-class"
          :class-unit="formValidate.unit_name || $t('message.productList.piece')"
        />
      </el-form-item>
    </el-col>
    <el-col :span="24">
      <el-form-item :label="$t('message.productList.sort2')">
        <el-input-number
          :controls="false"
          :min="0"
          :max="9999999999"
          v-model="formValidate.sort"
          :placeholder="$t('message.productList.pleaseInputSortNumber')"
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
