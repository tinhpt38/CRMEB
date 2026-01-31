<template>
  <div>
    <el-dialog :visible.sync="visible" :title="$t('message.pages.product.components.brokerageTitle')" width="700">
      <el-form :model="formData" label-width="80px">
        <el-form-item :label="$t('message.pages.product.components.brokerageSetting')">
          <el-radio-group v-model="formData.is_sub" @input="changeSubType">
            <el-radio :label="0">{{ $t('message.pages.product.components.defaultRatio') }}</el-radio>
            <el-radio :label="1">{{ $t('message.pages.product.components.customBrokerage') }}</el-radio>
          </el-radio-group>
          <div class="fs-12 tips-info" v-show="formData.is_sub">
            {{ $t('message.pages.product.components.switchToDefaultTip') }}
          </div>
        </el-form-item>
        <el-form-item label-width="0">
          <el-table size="small" border max-height="460" :data="attrData" style="width: 100%">
            <el-table-column prop="pic" :label="$t('message.pages.product.components.specPic')" min-width="90" align="center">
              <template slot-scope="scope">
                <div class="tabBox_img m-auto" v-viewer>
                  <img v-lazy="scope.row.pic" />
                </div>
              </template>
            </el-table-column>
            <el-table-column prop="suk" :label="$t('message.pages.product.components.productSpecCol')" min-width="120" align="center"></el-table-column>
            <el-table-column prop="price" :label="$t('message.pages.product.components.price')" min-width="120" align="center"></el-table-column>
            <el-table-column min-width="120" align="center">
              <template slot="header" slot-scope="scope">
                <span>{{ $t('message.pages.product.components.oneLevel') }}</span>
                <el-popover
                  ref="popoverRef_one"
                  placement="top"
                  width="280"
                  trigger="click"
                  v-if="formData.is_sub == 1"
                >
                  <div class="pop-title">{{ $t('message.pages.product.components.batchSetOne') }}</div>
                  <div class="mt-14">
                    <el-radio-group v-model="brokerageSetType">
                      <el-radio :label="0">{{ $t('message.pages.product.components.specifyPrice') }}</el-radio>
                      <el-radio :label="1">{{ $t('message.pages.product.components.ratio') }}</el-radio>
                    </el-radio-group>
                  </div>
                  <div class="mt10 mb10 acea-row">
                    <el-input type="number" class="popover-input" v-model="brokerage" @input="brokerageReplace">
                      <template slot="suffix">
                        <span>{{ brokerageSetType ? $t('message.pages.product.components.percent') : $t('message.pages.product.components.yuan') }}</span>
                      </template>
                    </el-input>
                    <div class="acea-row row-right row-middle ml14">
                      <el-button size="small" @click="closePop">{{ $t('message.pages.product.components.cancelBtn') }}</el-button>
                      <el-button size="small" type="primary" @click="brokerageOneSetUp">{{ $t('message.pages.product.components.confirmBtn') }}</el-button>
                    </div>
                  </div>
                  <span class="iconfont iconbianji1" slot="reference"></span>
                </el-popover>
              </template>
              <template slot-scope="scope">
                <el-input
                  v-model="scope.row.brokerage"
                  @change="brokerageRowReplace(scope.row)"
                  :disabled="formData.is_sub == 0"
                >
                  <template slot="suffix">
                    <span>{{ formData.is_sub ? $t('message.pages.product.components.yuan') : $t('message.pages.product.components.percent') }}</span>
                  </template>
                </el-input>
                <div
                  class="flex-x-center red"
                  v-show="formData.is_sub == 1 && Number(scope.row.brokerage) > Number(scope.row.price)"
                >
                  {{ $t('message.pages.product.components.brokerageNotGreaterThanPrice') }}
                </div>
              </template>
            </el-table-column>
            <el-table-column min-width="120" align="center">
              <template slot="header" slot-scope="scope">
                <span>{{ $t('message.pages.product.components.twoLevel') }}</span>
                <el-popover
                  ref="popoverRef_two"
                  placement="top"
                  width="280"
                  trigger="click"
                  v-if="formData.is_sub == 1"
                >
                  <div class="pop-title">{{ $t('message.pages.product.components.batchSetTwo') }}</div>
                  <div class="mt-14">
                    <el-radio-group v-model="brokerageSetType">
                      <el-radio :label="0">{{ $t('message.pages.product.components.specifyPrice') }}</el-radio>
                      <el-radio :label="1">{{ $t('message.pages.product.components.ratio') }}</el-radio>
                    </el-radio-group>
                  </div>
                  <div class="mt10 mb10 acea-row">
                    <el-input type="number" class="popover-input" v-model="brokerage_two">
                      <template slot="suffix">
                        <span>{{ brokerageSetType ? $t('message.pages.product.components.percent') : $t('message.pages.product.components.yuan') }}</span>
                      </template>
                    </el-input>
                    <div class="acea-row row-right row-middle ml14">
                      <el-button size="small" @click="closePop">{{ $t('message.pages.product.components.cancelBtn') }}</el-button>
                      <el-button size="small" type="primary" @click="brokerageTwoSetUp">{{ $t('message.pages.product.components.confirmBtn') }}</el-button>
                    </div>
                  </div>

                  <span class="iconfont iconbianji1" slot="reference"></span>
                </el-popover>
              </template>
              <template slot-scope="scope">
                <el-input
                  type="number"
                  v-model="scope.row.brokerage_two"
                  @change="brokerageTwoRowReplace(scope.row)"
                  :disabled="formData.is_sub == 0"
                >
                  <template slot="suffix">
                    <span>{{ formData.is_sub ? $t('message.pages.product.components.yuan') : $t('message.pages.product.components.percent') }}</span>
                  </template>
                </el-input>
                <div
                  class="flex-x-center red"
                  v-show="formData.is_sub == 1 && Number(scope.row.brokerage_two) > Number(scope.row.price)"
                >
                  {{ $t('message.pages.product.components.brokerageNotGreaterThanPrice') }}
                </div>
              </template>
            </el-table-column>
          </el-table>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="onCancel">{{ $t('message.pages.product.components.cancelBtn') }}</el-button>
        <el-button type="primary" @click="submitForm" :disabled="disabled" class="ml-14">{{ $t('message.pages.product.components.confirmBtn') }}</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import { productBrokerage, productBrokerageUpdate } from '@/api/product';
export default {
  name: 'brokerageSet',
  data() {
    return {
      formData: {
        is_sub: 0,
      },
      brokerage: '',
      brokerage_two: '',
      loading: false,
      attrData: [],
      disabled: false,
      brokerageSetType: 0,
      store_brokerage_ratio: '',
      store_brokerage_two: '',
      storeInfo: {},
      visible: false,
      defaultAttrData: [],
    };
  },
  props: {
    productId: {
      type: Number,
      default: 0,
    },
  },
  watch: {
    visible(val) {
      if (val) {
        this.getData();
      }
    },
  },
  created() {},
  methods: {
    brokerageReplace(value) {
      this.brokerage = value.replace(/^-|\D/g, '');
    },
    submitForm() {
      let isSuccess = true,
        step = true;
      if (this.formData.is_sub) {
        this.attrData.forEach((item) => {
          if (item.brokerage == 0 || item.brokerage_two == 0) {
            isSuccess = true;
          }
          if (Number(item.brokerage) > Number(item.price) || Number(item.brokerage_two) > Number(item.price)) {
            step = false;
          }
        });
      }
      if (!isSuccess) return this.$message.error('佣金不可为0');
      if (!step) return this.$message.error('佣金不可为大于售价');
      this.disabled = true;
      let data = {
        ...this.formData,
        attr_value: this.attrData,
      };
      productBrokerageUpdate(this.productId, 1, data)
        .then((res) => {
          this.disabled = false;
          this.$message.success(res.msg);
          this.visible = false;
        })
        .catch((err) => {
          this.$message.error(err.msg);
          this.disabled = false;
        });
    },
    brokerageOneSetUp() {
      if (this.brokerage == 0) return this.$message.error(this.$t('message.pages.product.components.priceAndDiscountNotZero'));
      if (this.brokerageSetType == 1 && this.brokerage > 100) return this.$message.error(this.$t('message.pages.product.components.discountOver100'));
      this.attrData.map((item) => {
        if (this.brokerageSetType == 0) {
          item.brokerage = this.brokerage;
        } else {
          item.brokerage = ((this.brokerage / 100) * item.price).toFixed(2);
        }
      });
      this.closePop();
    },
    brokerageTwoSetUp() {
      if (this.brokerage_two == 0) return this.$message.error(this.$t('message.pages.product.components.priceAndDiscountNotZero'));
      if (this.brokerageSetType == 1 && this.brokerage_two > 100) return this.$message.error(this.$t('message.pages.product.components.discountOver100'));
      this.attrData.map((item) => {
        if (this.brokerageSetType == 0) {
          item.brokerage_two = this.brokerage_two;
        } else {
          item.brokerage_two = ((this.brokerage_two / 100) * item.price).toFixed(2);
        }
      });
      this.closePop();
    },
    brokerageRowReplace(item) {
      item.brokerage = item.brokerage.replace(/^-|\D/g, '');
    },
    brokerageTwoRowReplace(item) {
      item.brokerage_two = item.brokerage_two.replace(/^-|\D/g, '');
    },
    closePop() {
      this.$refs.popoverRef_one.doClose();
      this.$refs.popoverRef_two.doClose();
      this.brokerage_two = '';
      this.brokerage = '';
    },
    changeSubType(val) {
      this.attrData.map((item, i) => {
        item.brokerage = val == 0 ? this.storeInfo.store_brokerage_ratio : this.defaultAttrData[i].brokerage;
        item.brokerage_two = val == 0 ? this.storeInfo.store_brokerage_two : this.defaultAttrData[i].brokerage_two;
      });
    },
    onCancel() {
      this.visible = false;
    },
    getData() {
      //获取产品属性
      productBrokerage(this.productId, 1).then((res) => {
        let arr = JSON.parse(JSON.stringify(res.data.attrValue));
        this.defaultAttrData = Object.values(arr);
        console.log(this.defaultAttrData, 'this.defaultAttrData');
        this.attrData = Object.values(res.data.attrValue);
        this.formData.is_sub = res.data.storeInfo.is_sub;
        this.storeInfo = res.data.storeInfo;
        if (this.formData.is_sub == 0) {
          this.attrData.map((e) => {
            e.brokerage = this.storeInfo.store_brokerage_ratio;
            e.brokerage_two = this.storeInfo.store_brokerage_two;
          });
        }
      });
    },
  },
};
</script>
<style scoped>
.iconbianji1 {
  font-size: 12px;
  padding-left: 4px;
  cursor: pointer;
}
.popover-input {
  width: 100px;
}
.w-250 {
  width: 250px;
}
.pop-title {
  font-size: 14px;
  font-weight: bold;
  margin-bottom: 14px;
}
.red {
  color: #e93323;
}
</style>
