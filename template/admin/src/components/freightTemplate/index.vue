<template>
  <div>
    <el-dialog
      :visible.sync="isTemplate"
      :title="$t('message.freightTemplate.templateTitle')"
      width="1000px"
      if="isTemplate"
      @on-cancel="cancel"
      @closed="close"
    >
      <div class="Modals">
        <el-form class="form" ref="formData" label-width="120px" label-position="right">
          <el-row :gutter="24">
            <el-col :xl="18" :lg="18" :md="18" :sm="24" :xs="24">
              <el-form-item :label="$t('message.freightTemplate.templateName')" prop="name">
                <el-input type="text" :placeholder="$t('message.freightTemplate.templateNamePlaceholder')" :maxlength="20" v-model="formData.name" />
              </el-form-item>
            </el-col>
          </el-row>
          <el-row :gutter="24">
            <el-col :xl="18" :lg="18" :md="18" :sm="24" :xs="24">
              <el-form-item :label="$t('message.freightTemplate.chargeType')" props="state" label-for="state">
                <el-radio-group class="radio" v-model="formData.type" @input="changeRadio" element-id="state">
                  <el-radio :label="1">{{ $t('message.freightTemplate.byCount') }}</el-radio>
                  <el-radio :label="2">{{ $t('message.freightTemplate.byWeight') }}</el-radio>
                  <el-radio :label="3">{{ $t('message.freightTemplate.byVolume') }}</el-radio>
                </el-radio-group>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row :gutter="24">
            <el-col :xl="24" :lg="24" :md="24" :sm="24" :xs="24">
              <el-form-item class="label" :label="$t('message.freightTemplate.regionAndFee')" props="state" label-for="state">
                <el-table ref="table" :data="templateList" class="ivu-mt" :empty-text="$t('message.freightTemplate.empty')" border>
                  <el-table-column :label="$t('message.freightTemplate.deliveryArea')" minWidth="100">
                    <template slot-scope="scope">
                      <el-input v-model="templateList[scope.$index].regionName" />
                    </template>
                  </el-table-column>
                  <el-table-column
                    :label="formData.type === 2 ? $t('message.freightTemplate.firstWeight') : formData.type === 3 ? $t('message.freightTemplate.firstVolume') : $t('message.freightTemplate.firstPiece')"
                    minWidth="100"
                  >
                    <template slot-scope="scope">
                      <el-input type="number" v-model="templateList[scope.$index].first" />
                    </template>
                  </el-table-column>
                  <el-table-column :label="$t('message.freightTemplate.freight')" minWidth="100">
                    <template slot-scope="scope">
                      <el-input type="number" v-model="templateList[scope.$index].price" />
                    </template>
                  </el-table-column>
                  <el-table-column
                    :label="formData.type === 2 ? $t('message.freightTemplate.continueWeight') : formData.type === 3 ? $t('message.freightTemplate.continueVolume') : $t('message.freightTemplate.continuePiece')"
                    minWidth="100"
                  >
                    <template slot-scope="scope">
                      <el-input type="number" v-model="templateList[scope.$index].continue" />
                    </template>
                  </el-table-column>
                  <el-table-column :label="$t('message.freightTemplate.continueFee')" minWidth="100">
                    <template slot-scope="scope">
                      <el-input type="number" v-model="templateList[scope.$index].continue_price" />
                    </template>
                  </el-table-column>
                  <el-table-column :label="$t('message.freightTemplate.operation')" fixed="right" width="100">
                    <template slot-scope="scope">
                      <a
                        v-if="scope.row.regionName !== $t('message.freightTemplate.defaultNationwide')"
                        v-db-click
                        @click="delCity(scope.row, $t('message.freightTemplate.regionAndFee'), scope.$index, 1)"
                        >{{ $t('message.freightTemplate.delete') }}</a
                      >
                    </template>
                  </el-table-column>
                </el-table>
                <el-row class="addTop">
                  <el-col>
                    <el-button type="primary" icon="md-add" v-db-click @click="addCity(1)">{{ $t('message.freightTemplate.addDeliveryArea') }}</el-button>
                  </el-col>
                </el-row>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row :gutter="24">
            <el-col :xl="24" :lg="24" :md="24" :sm="24" :xs="24">
              <el-form-item :label="$t('message.freightTemplate.appointFree')" prop="store_name" label-for="store_name">
                <el-radio-group class="radio" v-model="formData.appoint_check">
                  <el-radio :label="1">{{ $t('message.freightTemplate.open') }}</el-radio>
                  <el-radio :label="0">{{ $t('message.freightTemplate.close') }}</el-radio>
                </el-radio-group>
                <el-table
                  ref="table"
                  :data="appointList"
                  class="addTop mt10"
                  :empty-text="$t('message.freightTemplate.empty')"
                  border
                  v-if="formData.appoint_check === 1"
                >
                  <el-table-column :label="$t('message.freightTemplate.chooseArea')" minWidth="100">
                    <template slot-scope="scope">
                      <el-input v-model="appointList[scope.$index].placeName" />
                    </template>
                  </el-table-column>
                  <el-table-column
                    :label="formData.type === 2 ? $t('message.freightTemplate.freeWeight') : formData.type === 3 ? $t('message.freightTemplate.freeVolume') : $t('message.freightTemplate.freeCount')"
                    minWidth="100"
                  >
                    <template slot-scope="scope">
                      <el-input type="number" v-model="appointList[scope.$index].a_num" />
                    </template>
                  </el-table-column>
                  <el-table-column :label="$t('message.freightTemplate.freeAmount')" minWidth="100">
                    <template slot-scope="scope">
                      <el-input type="number" v-model="appointList[scope.$index].a_price" />
                    </template>
                  </el-table-column>
                  <el-table-column :label="$t('message.freightTemplate.operation')" fixed="right" width="100">
                    <template slot-scope="scope">
                      <a
                        v-if="scope.row.regionName !== $t('message.freightTemplate.defaultNationwide')"
                        v-db-click
                        @click="delCity(scope.row, $t('message.freightTemplate.regionAndFee'), scope.$index, 2)"
                        >{{ $t('message.freightTemplate.delete') }}</a
                      >
                    </template>
                  </el-table-column>
                </el-table>
                <div v-if="formData.appoint_check === 1" class="free_tips">
                  {{ $t('message.freightTemplate.freeTips') }}
                </div>
                <el-row class="addTop mt5" v-if="formData.appoint_check === 1">
                  <el-col>
                    <el-button type="primary" icon="md-add" v-db-click @click="addCity(2)">{{ $t('message.freightTemplate.addFreeArea') }}</el-button>
                  </el-col>
                </el-row>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row :gutter="24">
            <el-col :xl="24" :lg="24" :md="24" :sm="24" :xs="24">
              <el-form-item :label="$t('message.freightTemplate.noDelivery')" prop="store_name" label-for="store_name">
                <el-radio-group class="radio" v-model="formData.no_delivery_check">
                  <el-radio :label="1">{{ $t('message.freightTemplate.open') }}</el-radio>
                  <el-radio :label="0">{{ $t('message.freightTemplate.close') }}</el-radio>
                </el-radio-group>
                <el-table
                  ref="table"
                  :data="noDeliveryList"
                  class="addTop mt10"
                  :empty-text="$t('message.freightTemplate.empty')"
                  border
                  v-if="formData.no_delivery_check === 1"
                >
                  <el-table-column :label="$t('message.freightTemplate.chooseArea')" minWidth="100">
                    <template slot-scope="scope">
                      <el-input v-model="noDeliveryList[scope.$index].placeName" />
                    </template>
                  </el-table-column>
                  <el-table-column :label="$t('message.freightTemplate.operation')" fixed="right" width="100">
                    <template slot-scope="scope">
                      <a
                        v-if="scope.row.regionName !== $t('message.freightTemplate.defaultNationwide')"
                        v-db-click
                        @click="delCity(scope.row, $t('message.freightTemplate.regionAndFee'), scope.$index, 3)"
                        >{{ $t('message.freightTemplate.delete') }}</a
                      >
                    </template>
                  </el-table-column>
                </el-table>
                <el-row class="addTop" v-if="formData.no_delivery_check === 1">
                  <el-col>
                    <el-button type="primary" icon="md-add" v-db-click @click="addCity(3)">{{ $t('message.freightTemplate.addNoDeliveryArea') }}</el-button>
                  </el-col>
                </el-row>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row :gutter="24">
            <el-col :xl="18" :lg="18" :md="18" :sm="24" :xs="24">
              <el-form-item :label="$t('message.freightTemplate.sort')" prop="store_name" label-for="store_name">
                <el-input-number
                  :controls="false"
                  :min="0"
                  :placeholder="$t('message.freightTemplate.sortPlaceholder')"
                  v-model="formData.sort"
                ></el-input-number>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row :gutter="24">
            <el-col>
              <el-form-item prop="store_name" label-for="store_name">
                <el-button type="primary" v-db-click @click="handleSubmit">{{
                  id ? $t('message.freightTemplate.updateNow') : $t('message.freightTemplate.submitNow')
                }}</el-button>
              </el-form-item>
            </el-col>
          </el-row>
        </el-form>
      </div>
      <div slot="footer"></div>
    </el-dialog>
    <city ref="city" @selectCity="selectCity" :type="type" :selectArr="selectArr"></city>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import city from '@/components/freightTemplate/city';
import { templatesSaveApi, shipTemplatesApi } from '@/api/setting';
export default {
  name: 'freightTemplate',
  components: { city },
  props: {},
  data() {
    let that = this;
    return {
      isTemplate: false,
      templateList: [
        {
          region: [
            {
              name: this.$t('message.freightTemplate.defaultNationwide'),
              city_id: 0,
            },
          ],
          regionName: this.$t('message.freightTemplate.defaultNationwide'),
          first: 1,
          price: 0,
          continue: 1,
          continue_price: 0,
        },
      ],
      appointList: [],
      noDeliveryList: [],
      type: 1,
      formData: {
        type: 1,
        sort: 0,
        name: '',
        appoint_check: 0,
        no_delivery_check: 0,
      },
      id: 0,

      addressModal: false,
      indeterminate: true,
      checkAll: false,
      checkAllGroup: [],
      activeCity: -1,
      provinceAllGroup: [],
      index: -1,
      displayData: '',
      currentProvince: '',
      selectArr: [], // 传递选中的城市
      noShippingArr: [], // 不包邮选择的城市数据
      yesShippingArr: [], // 包邮选择的城市数据
      noDeliveryArr: [], // 不送达选择的城市数据
    };
  },
  computed: {},
  methods: {
    close() {
      this.$emit('close');
    },
    editFrom(id) {
      this.id = id;
      shipTemplatesApi(id).then((res) => {
        let formData = res.data.formData;
        this.templateList = res.data.templateList;
        this.appointList = res.data.appointList;
        this.noDeliveryList = res.data.noDeliveryList;
        this.formData = {
          type: formData.type,
          sort: formData.sort,
          name: formData.name,
          appoint_check: formData.appoint_check,
          no_delivery_check: formData.no_delivery_check,
        };
        // this.headerType();
      });
    },
    selectCity: function (data, type) {
      let cityName = data
        .map(function (item) {
          return item.name;
        })
        .join(';');
      switch (type) {
        case 1:
          this.templateList.push({
            region: data,
            regionName: cityName,
            first: 1,
            price: 0,
            continue: 1,
            continue_price: 0,
          });
          this.noShippingArr = this.noShippingArr.concat(data);
          break;
        case 2:
          this.appointList.push({
            place: data,
            placeName: cityName,
            a_num: 0,
            a_price: 0,
          });
          this.yesShippingArr = this.yesShippingArr.concat(data);
          break;
        case 3:
          this.noDeliveryList.push({
            place: data,
            placeName: cityName,
          });
          this.noDeliveryArr = this.noDeliveryArr.concat(data);
          break;
      }
    },
    // 单独添加配送区域
    addCity(type) {
      this.selectArr = type == 1 ? this.noShippingArr : type == 2 ? this.yesShippingArr : this.noDeliveryArr;
      this.type = type;
      this.$refs.city.getCityList();
      this.$refs.city.addressModal = true;
    },
    changeRadio() {},
    // 提交
    handleSubmit: function () {
      let that = this;
      if (!that.formData.name.trim().length) {
        return that.$message.error(this.$t('message.freightTemplate.nameRequired'));
      }
      for (let i = 0; i < that.templateList.length; i++) {
        if (that.templateList[i].first <= 0) {
          return that.$message.error(this.$t('message.freightTemplate.firstGt0'));
        }
        if (that.templateList[i].price < 0) {
          return that.$message.error(this.$t('message.freightTemplate.freightGte0'));
        }
        if (that.templateList[i].continue <= 0) {
          return that.$message.error(this.$t('message.freightTemplate.continueGt0'));
        }
        if (that.templateList[i].continue_price < 0) {
          return that.$message.error(this.$t('message.freightTemplate.continueFeeGte0'));
        }
      }
      if (that.formData.appoint_check === 1) {
        for (let i = 0; i < that.appointList.length; i++) {
          if (that.appointList[i].a_num <= 0) {
            return that.$message.error(this.$t('message.freightTemplate.freeCountGt0'));
          }
          if (that.appointList[i].a_price < 0) {
            return that.$message.error(this.$t('message.freightTemplate.freeAmountGte0'));
          }
        }
      }
      let data = {
        appoint_info: that.appointList,
        region_info: that.templateList,
        no_delivery_info: that.noDeliveryList,
        sort: that.formData.sort,
        type: that.formData.type,
        name: that.formData.name,
        appoint: that.formData.appoint_check,
        no_delivery: that.formData.no_delivery_check,
      };
      templatesSaveApi(that.id, data).then((res) => {
        this.isTemplate = false;
        // this.$parent.getList();
        this.formData = {
          type: 1,
          sort: 0,
          name: '',
          appoint_check: 0,
          no_delivery_check: 0,
        };
        this.appointList = [];
        this.noDeliveryList = [];
        this.addressModal = false;
        this.templateList = [
          {
            region: [
              {
                name: this.$t('message.freightTemplate.defaultNationwide'),
                city_id: 0,
              },
            ],
            regionName: this.$t('message.freightTemplate.defaultNationwide'),
            first: 1,
            price: 0,
            continue: 1,
            continue_price: 0,
          },
        ];
        this.$emit('addSuccess');
        this.$message.success(res.msg);
      });
    },
    // 删除
    delCity(row, tit, num, type) {
      if (type === 1) {
        this.templateList.splice(num, 1);
      } else if (type == 2) {
        this.appointList.splice(num, 1);
      } else {
        this.noDeliveryList.splice(num, 1);
      }
      //   let delfromData = {
      //     title: tit,
      //     num: num,
      //     url: `setting/shipping_templates/del/${row.id}`,
      //     method: "DELETE",
      //     ids: "",
      //   };
      //   this.$modalSure(delfromData)
      //     .then((res) => {
      //       this.$message.success(res.msg);
      //     })
      //     .catch((res) => {
      //       this.$message.error(res.msg);
      //     });
    },
    // 关闭
    cancel() {
      this.noShippingArr = [];
      this.noDeliveryArr = [];
      this.yesShippingArr = [];
      this.selectArr = [];
      this.formData = {
        type: 1,
        sort: 0,
        name: '',
        appoint_check: 0,
        no_delivery_check: 0,
      };
      this.appointList = [];
      this.noDeliveryList = [];
      this.addressModal = false;
      this.templateList = [
        {
          region: [
            {
              name: this.$t('message.freightTemplate.defaultNationwide'),
              city_id: 0,
            },
          ],
          regionName: this.$t('message.freightTemplate.defaultNationwide'),
          first: 0,
          price: 0,
          continue: 0,
          continue_price: 0,
        },
      ];
    },

    address() {
      this.addressModal = true;
    },
    enter(index) {
      this.activeCity = index;
    },
    leave() {
      this.activeCity = null;
    },
  },
  mounted() {},
};
</script>
<style lang="scss" scoped>
.ivu-table-wrapper {
  border-left: 1px solid #dcdee2;
  border-top: 1px solid #dcdee2;
}
.ivu-table-border th,
.ivu-table-border td {
  padding: 0 10px !important;
}
.addTop {
  margin-top: 15px;
}
.radio {
  padding: 5px 0;
}
.ivu-input-number {
  width: 100%;
}
.free_tips {
  font-size: 12px;
  color: #ccc;
}
</style>
