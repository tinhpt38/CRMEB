<template>
  <div v-loading="spinShow">
    <pages-header
      ref="pageHeader"
      :title="$route.params.id ? $t('message.pages.marketing.storeSeckill.create.editSeckill') : $t('message.pages.marketing.storeSeckill.create.addSeckill')"
      :backUrl="$routeProStr + '/marketing/store_seckill/index'"
    ></pages-header>
    <el-card :bordered="false" shadow="never" class="mt16">
      <el-row class="mt30 acea-row row-middle row-center">
        <el-col :span="20">
          <steps :stepList="stepList" :isActive="current"></steps>
        </el-col>
        <el-col :span="23">
          <el-form
            class="form mt30"
            ref="formValidate"
            :model="formValidate"
            :rules="ruleValidate"
            @on-validate="validate"
            :label-width="labelWidth"
            :label-position="labelPosition"
            @submit.native.prevent
          >
            <el-form-item :label="$t('message.pages.marketing.storeSeckill.create.selectProduct')" prop="image_input" v-if="current === 0">
              <div class="picBox" v-db-click @click="changeGoods">
                <div class="pictrue" v-if="formValidate.image">
                  <img v-lazy="formValidate.image" />
                </div>
                <div class="upLoad acea-row row-center-wrapper" v-else>
                  <i class="el-icon-goods" style="font-size: 24px"></i>
                </div>
              </div>
            </el-form-item>
            <el-col v-show="current === 1">
              <el-col :span="24">
                <el-form-item :label="$t('message.pages.marketing.storeSeckill.create.productCarousel')" prop="images">
                  <div class="acea-row">
                    <div
                      class="pictrue"
                      v-for="(item, index) in formValidate.images"
                      :key="index"
                      draggable="true"
                      @dragstart="handleDragStart($event, item)"
                      @dragover.prevent="handleDragOver($event, item)"
                      @dragenter="handleDragEnter($event, item)"
                      @dragend="handleDragEnd($event, item)"
                    >
                      <img v-lazy="item" />
                      <i class="el-icon-circle-close btndel" v-db-click @click="handleRemove(index)"></i>
                    </div>
                    <div
                      v-if="formValidate.images.length < 10"
                      class="upLoad acea-row row-center-wrapper"
                      v-db-click
                      @click="modalPicTap('duo')"
                    >
                      <i class="el-icon-picture-outline" style="font-size: 24px"></i>
                    </div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-col v-bind="grid">
                  <el-form-item :label="$t('message.pages.marketing.storeSeckill.create.productTitle')" prop="title" label-for="title">
                    <el-input
                      clearable
                      :placeholder="$t('message.pages.marketing.storeSeckill.create.inputProductTitle')"
                      v-model="formValidate.title"
                      class="content_width"
                      maxlength="80"
                      show-word-limit
                    />
                  </el-form-item>
                </el-col>
              </el-col>
              <el-col :span="24">
                <el-col v-bind="grid">
                  <el-form-item :label="$t('message.pages.marketing.storeSeckill.create.seckillActivityIntro')" prop="info" label-for="info">
                    <el-input
                      :placeholder="$t('message.pages.marketing.storeSeckill.create.inputSeckillActivityIntro')"
                      type="textarea"
                      :rows="4"
                      v-model="formValidate.info"
                      class="content_width"
                      maxlength="100"
                      show-word-limit
                    />
                  </el-form-item>
                </el-col>
              </el-col>
              <el-col :span="24">
                <el-form-item :label="$t('message.pages.marketing.storeSeckill.create.activityTimeLabel')" prop="section_time">
                  <div>
                    <el-date-picker
                      clearable
                      :editable="false"
                      type="daterange"
                      format="yyyy-MM-dd"
                      value-format="yyyy-MM-dd"
                      range-separator="-"
                      :start-placeholder="$t('message.pages.marketing.storeSeckill.create.startDate')"
                      :end-placeholder="$t('message.pages.marketing.storeSeckill.create.endDate')"
                      @change="onchangeTime"
                      class="content_width"
                      v-model="formValidate.section_time"
                    ></el-date-picker>
                    <div class="grey">{{ $t('message.pages.marketing.storeSeckill.create.activityTimeHint') }}</div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24" v-if="formValidate.virtual_type == 0">
                <el-form-item :label="$t('message.pages.marketing.storeSeckill.create.logistics')" prop="logistics">
                  <el-checkbox-group v-model="formValidate.logistics">
                    <el-checkbox label="1">{{ $t('message.pages.marketing.storeSeckill.create.express') }}</el-checkbox>
                    <el-checkbox label="2">{{ $t('message.pages.marketing.storeSeckill.create.inStore') }}</el-checkbox>
                  </el-checkbox-group>
                </el-form-item>
              </el-col>
              <el-col :span="24" v-if="formValidate.virtual_type == 0">
                <el-form-item :label="$t('message.pages.marketing.storeSeckill.create.freightSetting')" :prop="formValidate.freight != 1 ? 'freight' : ''">
                  <el-radio-group v-model="formValidate.freight">
                    <el-radio :label="2">{{ $t('message.pages.marketing.storeSeckill.create.fixedPostage') }}</el-radio>
                    <el-radio :label="3">{{ $t('message.pages.marketing.storeSeckill.create.freightTemplate') }}</el-radio>
                  </el-radio-group>
                </el-form-item>
              </el-col>
              <el-col
                :span="24"
                v-if="formValidate.freight != 3 && formValidate.freight != 1 && formValidate.virtual_type == 0"
              >
                <el-form-item label="">
                  <div class="acea-row">
                    <el-input-number
                      :controls="false"
                      :min="0"
                      :max="9999999999"
                      v-model="formValidate.postage"
                      :placeholder="$t('message.pages.marketing.storeSeckill.create.inputAmount')"
                      class="content_width input-number-unit-class"
                      :class-unit="$t('message.pages.marketing.storeSeckill.create.yuan')"
                    />
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24" v-if="formValidate.freight == 3 && formValidate.virtual_type == 0">
                <el-form-item label="" prop="temp_id">
                  <div class="acea-row">
                    <el-select
                      v-model="formValidate.temp_id"
                      clearable
                      :placeholder="$t('message.pages.marketing.storeSeckill.create.selectFreightTemplate')"
                      class="content_width"
                    >
                      <el-option
                        v-for="(item, index) in templateList"
                        :value="item.id"
                        :key="index"
                        :label="item.name"
                      ></el-option>
                    </el-select>
                    <span class="addfont" v-db-click @click="freight">{{ $t('message.pages.marketing.storeSeckill.create.newFreightTemplate') }}</span>
                  </div>
                </el-form-item>
              </el-col>

              <el-col :span="24">
                <el-form-item :label="$t('message.pages.marketing.storeSeckill.create.startTimeLabel')" prop="time_id">
                  <div>
                    <el-select v-model="formValidate.time_id" multiple class="content_width">
                      <el-option
                        v-for="item in timeList"
                        :value="item.id"
                        :key="item.id"
                        :label="item.time_name"
                      ></el-option>
                    </el-select>
                    <div class="grey">
                      {{ $t('message.pages.marketing.storeSeckill.create.startTimeHint') }}
                    </div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item :label="$t('message.pages.marketing.storeSeckill.create.totalPurchaseLimit')" prop="num">
                  <div>
                    <el-input-number
                      :controls="false"
                      :min="1"
                      :placeholder="$t('message.pages.marketing.storeSeckill.create.inputQuantityLimit')"
                      element-id="num"
                      :precision="0"
                      :max="10000"
                      v-model="formValidate.num"
                      class="content_width"
                    />
                    <div class="grey">
                      {{ $t('message.pages.marketing.storeSeckill.create.totalPurchaseLimitHint') }}
                    </div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item :label="$t('message.pages.marketing.storeSeckill.create.oncePurchaseLimit')" prop="once_num">
                  <div>
                    <el-input-number
                      :controls="false"
                      :min="1"
                      :placeholder="$t('message.pages.marketing.storeSeckill.create.inputOncePurchaseLimit')"
                      element-id="once_num"
                      :precision="0"
                      :max="10000"
                      v-model="formValidate.once_num"
                      class="content_width"
                    />
                    <div class="grey">
                      {{ $t('message.pages.marketing.storeSeckill.create.oncePurchaseLimitHint') }}
                    </div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item :label="$t('message.pages.marketing.storeSeckill.create.unit')" prop="unit_name" label-for="unit_name">
                  <el-input
                    :placeholder="$t('message.pages.marketing.storeSeckill.create.inputUnit')"
                    element-id="unit_name"
                    v-model="formValidate.unit_name"
                    class="content_width"
                  />
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item :label="$t('message.pages.marketing.storeSeckill.create.sortLabel')">
                  <el-input-number
                    :controls="false"
                    :placeholder="$t('message.pages.marketing.storeSeckill.create.inputSort')"
                    element-id="sort"
                    :precision="0"
                    :max="10000"
                    :min="0"
                    v-model="formValidate.sort"
                    class="content_width"
                  />
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item :label="$t('message.pages.marketing.storeSeckill.create.isCommission')" props="is_commission" label-for="is_commission">
                  <div>
                    <el-switch
                      class="defineSwitch"
                      :active-value="1"
                      :inactive-value="0"
                      v-model="formValidate.is_commission"
                      size="large"
                      :active-text="$t('message.pages.marketing.storeSeckill.create.open')"
                      :inactive-text="$t('message.pages.marketing.storeSeckill.create.close')"
                    >
                    </el-switch>
                    <div class="grey">{{ $t('message.pages.marketing.storeSeckill.create.isCommissionHint') }}</div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item :label="$t('message.pages.marketing.storeSeckill.create.activityStatusLabel')" props="status" label-for="status">
                  <el-switch
                    class="defineSwitch"
                    :active-value="1"
                    :inactive-value="0"
                    v-model="formValidate.status"
                    size="large"
                    :active-text="$t('message.pages.marketing.storeSeckill.create.open')"
                    :inactive-text="$t('message.pages.marketing.storeSeckill.create.close')"
                  >
                  </el-switch>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item :label="$t('message.pages.marketing.storeSeckill.create.specSelect')">
                  <el-table
                    ref="multipleTable"
                    :row-key="getRowKeys"
                    :data="specsData"
                    border
                    @selection-change="changeCheckbox"
                  >
                    <el-table-column type="selection" :reserve-selection="true" width="55"> </el-table-column>
                    <el-table-column
                      :label="item.title"
                      :min-width="item.minWidth"
                      v-for="(item, index) in columns"
                      :key="index"
                    >
                      <template slot-scope="scope">
                        <template v-if="item.key">
                          <div>
                            <span>{{ scope.row[item.key] }}</span>
                          </div>
                        </template>
                        <template v-else-if="item.slot === 'pic'">
                          <div
                            class="acea-row row-middle row-center-wrapper"
                            v-db-click
                            @click="modalPicTap('dan', 'danTable', scope.$index)"
                          >
                            <div class="pictrue pictrueTab" v-if="scope.row.pic">
                              <img v-lazy="scope.row.pic" />
                            </div>
                            <div class="upLoad pictrueTab acea-row row-center-wrapper" v-else>
                              <i class="el-icon-picture-outline" style="font-size: 24px"></i>
                            </div>
                          </div>
                        </template>
                        <template v-else-if="item.slot === 'price'">
                          <el-input-number
                            :controls="false"
                            v-model="scope.row.price"
                            :min="0"
                            :precision="2"
                            class="priceBox"
                            :active-change="false"
                          ></el-input-number>
                        </template>
                        <template v-else-if="item.slot === 'quota'">
                          <el-input-number
                            :controls="false"
                            v-model="scope.row.quota"
                            :min="1"
                            active-change
                            class="priceBox"
                          ></el-input-number>
                        </template>
                      </template>
                    </el-table-column>
                  </el-table>
                </el-form-item>
              </el-col>
            </el-col>
            <el-row v-show="current === 2">
              <el-col :span="24">
                <el-form-item :label="$t('message.pages.marketing.storeSeckill.create.content')">
                  <WangEditor style="width: 90%" :content="formValidate.description" @editorContent="getEditorContent">
                  </WangEditor>
                </el-form-item>
              </el-col>
            </el-row>
            <el-col :span="24">
              <el-form-item>
                <el-button
                  class="submission"
                  v-db-click
                  @click="step"
                  :disabled="($route.params.id && current === 1) || current === 0"
                  >{{ $t('message.pages.marketing.storeSeckill.create.prevStep') }}
                </el-button>
                <el-button
                  :disabled="submitOpen && current === 2"
                  type="primary"
                  class="submission"
                  v-db-click
                  @click="next('formValidate')"
                  >{{ current === 2 ? $t('message.pages.marketing.storeSeckill.create.submit') : $t('message.pages.marketing.storeSeckill.create.nextStep') }}</el-button
                >
              </el-form-item>
            </el-col>
          </el-form>
        </el-col>
      </el-row>
    </el-card>
    <!-- 选择商品-->
    <el-dialog :visible.sync="modals" :title="$t('message.pages.marketing.storeSeckill.create.productList')" class="paymentFooter" width="1000px">
      <goods-list ref="goodslist" @getProductId="getProductId"></goods-list>
    </el-dialog>
    <!-- 上传图片-->
    <el-dialog :visible.sync="modalPic" width="950px" :title="$t('message.pages.marketing.storeSeckill.create.uploadProductImage')" :close-on-click-modal="false">
      <uploadPictures
        :isChoice="isChoice"
        @getPic="getPic"
        @getPicD="getPicD"
        :gridBtn="gridBtn"
        :gridPic="gridPic"
        v-if="modalPic"
      ></uploadPictures>
    </el-dialog>
    <!-- 运费模板-->
    <freight-template ref="template" @addSuccess="productGetTemplate"></freight-template>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import goodsList from '@/components/goodsList/index';
import WangEditor from '@/components/wangEditor/index.vue';
import uploadPictures from '@/components/uploadPictures';
import { seckillInfoApi, seckillAddApi, seckillTimeListApi, productAttrsApi } from '@/api/marketing';
import { productGetTemplateApi } from '@/api/product';
import freightTemplate from '@/components/freightTemplate/index';
import steps from '@/components/steps/index';

export default {
  name: 'storeSeckillCreate',
  components: {
    goodsList,
    uploadPictures,
    WangEditor,
    freightTemplate,
    steps,
  },
  data() {
    return {
      submitOpen: false,
      spinShow: false,
      isChoice: '',
      current: 0,
      modalPic: false,
      grid: {
        xl: 12,
        lg: 20,
        md: 24,
        sm: 24,
        xs: 24,
      },
      grid2: {
        xl: 8,
        lg: 12,
        md: 12,
        sm: 24,
        xs: 24,
      },
      gridPic: {
        xl: 6,
        lg: 8,
        md: 12,
        sm: 12,
        xs: 12,
      },
      gridBtn: {
        xl: 4,
        lg: 8,
        md: 8,
        sm: 8,
        xs: 8,
      },
      myConfig: {
        autoHeightEnabled: false, // 编辑器不自动被内容撑高
        initialFrameHeight: 500, // 初始容器高度
        initialFrameWidth: '100%', // 初始容器宽度
        UEDITOR_HOME_URL: '/UEditor/',
        serverUrl: '',
      },
      modals: false,
      modal_loading: false,
      images: [],
      formValidate: {
        images: [],
        info: '',
        title: '',
        image: '',
        unit_name: '',
        price: 0,
        logistics: ['1'], //选择物流方式
        freight: 2, //运费设置
        postage: 1, //设置运费金额
        ot_price: 0,
        cost: 0,
        sales: 0,
        stock: 0,
        sort: 0,
        num: 1,
        once_num: 1,
        give_integral: 0,
        postage: 0,
        section_time: [],
        is_postage: 0,
        is_hot: 0,
        status: 0,
        description: '',
        id: 0,
        product_id: 0,
        temp_id: '',
        time_id: [],
        attrs: [],
        items: [],
        is_commission: 0,
      },
      description: '',
      templateList: [],
      timeList: [],
      columns: [],
      specsData: [],
      picTit: '',
      tableIndex: 0,
      ruleValidate: {},
      copy: 0,
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '135px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
    stepList() {
      return [
        this.$t('message.pages.marketing.storeSeckill.create.step1'),
        this.$t('message.pages.marketing.storeSeckill.create.step2'),
        this.$t('message.pages.marketing.storeSeckill.create.step3'),
      ];
    },
  },
  mounted() {
    if (this.$route.params.id) {
      this.copy = this.$route.params.copy;
      this.current = 1;
      this.getInfo();
    }
    this.productGetTemplate();
    this.seckillTimeList();
    this.setRuleValidate();
  },
  methods: {
    setRuleValidate() {
      const t = (key) => this.$t(key);
      this.ruleValidate = {
        image: [{ required: true, message: t('message.pages.marketing.storeSeckill.create.msgSelectMainImage'), trigger: 'change' }],
        images: [
          { required: true, type: 'array', message: t('message.pages.marketing.storeSeckill.create.msgSelectMainImage'), trigger: 'change' },
          { type: 'array', min: 1, message: t('message.pages.marketing.storeSeckill.create.msgSelectMainImage'), trigger: 'change' },
        ],
        title: [{ required: true, message: t('message.pages.marketing.storeSeckill.create.msgInputProductTitle'), trigger: 'blur' }],
        info: [{ required: true, message: t('message.pages.marketing.storeSeckill.create.msgInputSeckillActivityIntro'), trigger: 'blur' }],
        section_time: [{ required: true, type: 'array', message: t('message.pages.marketing.storeSeckill.create.msgSelectActivityTime'), trigger: 'change' }],
        unit_name: [{ required: true, message: t('message.pages.marketing.storeSeckill.create.msgInputUnit'), trigger: 'blur' }],
        price: [{ required: true, type: 'number', message: t('message.pages.marketing.storeSeckill.create.msgInputSeckillPrice'), trigger: 'blur' }],
        ot_price: [{ required: true, type: 'number', message: t('message.pages.marketing.storeSeckill.create.msgInputOriginalPrice'), trigger: 'blur' }],
        cost: [{ required: true, type: 'number', message: t('message.pages.marketing.storeSeckill.create.msgInputCostPrice'), trigger: 'blur' }],
        stock: [{ required: true, type: 'number', message: t('message.pages.marketing.storeSeckill.create.msgInputStock'), trigger: 'blur' }],
        num: [{ required: true, type: 'number', message: t('message.pages.marketing.storeSeckill.create.msgInputPurchaseLimit'), trigger: 'blur' }],
        once_num: [{ required: true, type: 'number', message: t('message.pages.marketing.storeSeckill.create.msgInputOncePurchaseLimit'), trigger: 'blur' }],
        temp_id: [{ required: true, message: t('message.pages.marketing.storeSeckill.create.msgSelectFreightTemplate'), trigger: 'change', type: 'number' }],
        time_id: [{ required: true, message: t('message.pages.marketing.storeSeckill.create.msgSelectStartTime'), trigger: 'change', type: 'array' }],
      };
    },
    getEditorContent(data) {
      this.description = data;
    },
    // 添加运费模板
    freight() {
      this.$refs.template.id = 0;
      this.$refs.template.isTemplate = true;
    },
    // 秒杀规格；
    productAttrs(rows) {
      let that = this;
      productAttrsApi(rows.id, 1)
        .then((res) => {
          let data = res.data.info;
          let selection = {
            type: 'selection',
            width: 60,
            align: 'center',
          };
          that.specsData = data.attrs;
          that.specsData.forEach(function (item, index) {
            that.$set(that.specsData[index], 'id', index);
          });
          that.formValidate.items = data.items;
          that.columns = data.header;
        })
        .catch((res) => {
          that.$message.error(res.msg);
        });
    },

    // 多选
    changeCheckbox(selection) {
      this.formValidate.attrs = selection;
    },
    seckillTimeList() {
      let that = this;
      seckillTimeListApi()
        .then((res) => {
          that.timeList = res.data.list.data;
        })
        .catch((res) => {
          that.$message.error(res.msg);
        });
    },
    // 获取运费模板；
    productGetTemplate() {
      productGetTemplateApi().then((res) => {
        this.templateList = res.data;
      });
    },
    // 表单验证
    validate(prop, status, error) {
      if (status === false) {
        this.$message.error(error);
      }
    },
    // 商品id
    getProductId(row) {
      this.modal_loading = false;
      this.modals = false;
      setTimeout(() => {
        this.formValidate = {
          images: row.slider_image,
          info: row.store_info,
          title: row.store_name,
          image: row.image,
          unit_name: row.unit_name,
          price: 0, // 不取商品中的原价
          ot_price: row.ot_price,
          cost: row.cost,
          sales: row.sales,
          stock: row.stock,
          sort: row.sort,
          num: 1,
          once_num: 1,
          give_integral: row.give_integral,
          postage: row.postage,
          section_time: [],
          is_postage: row.is_postage,
          is_hot: row.is_hot,
          status: 0,
          description: '',
          id: 0,
          product_id: row.id,
          temp_id: row.temp_id,
          logistics: row.logistics, //选择物流方式
          freight: row.freight, //运费设置
          postage: row.postage, //设置运费金额
          custom_form: row.custom_form, //自定义表单数据
          virtual_type: row.virtual_type, //虚拟商品类型
          is_commission: row.is_commission,
        };
        this.productAttrs(row);
        this.$refs.goodslist.productRow = null;
      }, 500);
    },

    cancel() {
      this.modals = false;
    },
    // 具体日期
    onchangeTime(e) {
      this.formValidate.section_time = e;
    },
    // 详情
    getInfo() {
      this.spinShow = true;
      seckillInfoApi(this.$route.params.id)
        .then(async (res) => {
          let that = this;
          let info = res.data.info;
          let selection = {
            type: 'selection',
            width: 60,
            align: 'center',
          };
          this.formValidate = info;
          this.$set(this.formValidate, 'items', info.attrs.items);
          this.columns = info.attrs.header;
          // this.columns.unshift(selection);
          that.specsData = info.attrs.value;
          that.specsData.forEach(function (item, index) {
            that.$set(that.specsData[index], 'id', index);
          });
          let data = info.attrs;
          let attr = [];
          for (let index in info.attrs.value) {
            if (info.attrs.value[index]._checked) {
              attr.push(info.attrs.value[index]);
            }
          }
          that.formValidate.attrs = attr;
          attr.forEach((row) => {
            that.$refs.multipleTable.toggleRowSelection(row, true);
          });
          this.spinShow = false;
        })
        .catch((res) => {
          this.spinShow = false;
          this.$message.error(res.msg);
        });
    },
    getRowKeys(row) {
      return row.id;
    },
    changePrice(e, index) {
      this.$set(this.specsData[index], 'price', e);
    },
    // 下一步
    next(name) {
      let that = this;
      if (this.current === 2) {
        this.formValidate.description = this.description;
        this.$refs[name].validate((valid) => {
          if (valid) {
            if (this.copy == 1) this.formValidate.copy = 1;
            this.formValidate.id = Number(this.$route.params.id) || 0;
            this.submitOpen = true;
            seckillAddApi(this.formValidate)
              .then(async (res) => {
                this.submitOpen = false;
                this.$message.success(res.msg);
                setTimeout(() => {
                  this.$router.push({
                    path: this.$routeProStr + '/marketing/store_seckill/index',
                  });
                }, 500);
              })
              .catch((res) => {
                this.submitOpen = false;
                this.$message.error(res.msg);
              });
          } else {
            return false;
          }
        });
      } else if (this.current === 1) {
        this.$refs[name].validate((valid) => {
          if (valid) {
            if (!that.formValidate.attrs) {
              return that.$message.error(that.$t('message.pages.marketing.storeSeckill.create.msgSelectSpec'));
            } else {
              for (let index in that.formValidate.attrs) {
                if (that.formValidate.attrs[index].price > this.formValidate.attrs[index]['ot_price']) {
                  return that.$message.error(that.$t('message.pages.marketing.storeSeckill.create.msgSeckillPriceNotExceedOriginal'));
                }
                if (that.formValidate.attrs[index].quota <= 0) {
                  return that.$message.error(that.$t('message.pages.marketing.storeSeckill.create.msgLimitGtZero'));
                }
                if (this.formValidate.attrs[index].quota > this.formValidate.attrs[index]['stock']) {
                  return this.$message.error(this.$t('message.pages.marketing.storeSeckill.create.msgLimitNotExceedStock'));
                }
              }
            }
            this.current += 1;
          }
        });
      } else {
        if (this.formValidate.images) {
          this.current += 1;
        } else {
          this.$message.warning(this.$t('message.pages.marketing.storeSeckill.create.msgSelectProduct'));
        }
      }
    },
    // 上一步
    step() {
      this.current--;
    },
    // 内容
    getContent(val) {
      this.formValidate.description = val;
    },
    // 点击商品图
    modalPicTap(tit, picTit, index) {
      this.modalPic = true;
      this.isChoice = tit === 'dan' ? this.$t('message.pages.marketing.storeSeckill.create.singleSelect') : this.$t('message.pages.marketing.storeSeckill.create.multiSelect');
      this.picTit = picTit;
      this.tableIndex = index;
    },
    // 获取单张图片信息
    getPic(pc) {
      switch (this.picTit) {
        case 'danFrom':
          this.formValidate.image = pc.att_dir;
          break;
        // case 'danTable':
        //     this.specsData[this.tableIndex].pic = pc.att_dir;
        //     break;
        default:
          if (!!this.formValidate.attrs && this.formValidate.attrs.length) {
            this.$set(this.specsData[this.tableIndex], '_checked', true);
          }
          this.specsData[this.tableIndex].pic = pc.att_dir;
      }
      this.modalPic = false;
    },
    // 获取多张图信息
    getPicD(pc) {
      this.images = pc;
      this.images.map((item) => {
        this.formValidate.images.push(item.att_dir);
        this.formValidate.images = this.formValidate.images.splice(0, 10);
      });
      this.modalPic = false;
    },
    handleRemove(i) {
      this.images.splice(i, 1);
      this.formValidate.images.splice(i, 1);
    },
    // 选择商品
    changeGoods() {
      this.modals = true;
      this.$nextTick((e) => {
        this.$refs.goodslist.formValidate.is_show = -1;
        this.$refs.goodslist.formValidate.type = 3;
        this.$refs.goodslist.getList();
        this.$refs.goodslist.goodsCategory();
      });
    }, // 移动
    handleDragStart(e, item) {
      this.dragging = item;
    },
    handleDragEnd(e, item) {
      this.dragging = null;
    },
    // 首先把div变成可以放置的元素，即重写dragenter/dragover
    handleDragOver(e) {
      e.dataTransfer.dropEffect = 'move'; // e.dataTransfer.dropEffect="move";//在dragenter中针对放置目标来设置!
    },
    handleDragEnter(e, item) {
      e.dataTransfer.effectAllowed = 'move'; // 为需要移动的元素设置dragstart事件
      if (item === this.dragging) {
        return;
      }
      const newItems = [...this.formValidate.images];
      const src = newItems.indexOf(this.dragging);
      const dst = newItems.indexOf(item);
      newItems.splice(dst, 0, ...newItems.splice(src, 1));
      this.formValidate.images = newItems;
    },
  },
};
</script>

<style lang="scss" scoped>
.content_width {
  width: 460px;
}
.maxW ::v-deep .ivu-select-dropdown {
  max-width: 600px;
}
.grey {
  color: #999;
  font-size: 12px;
}
.tabBox_img {
  width: 50px;
  height: 50px;
  margin: 0 auto;
}
.tabBox_img img {
  width: 100%;
  height: 100%;
}
.priceBox {
  width: 100%;
}
.form {
  .picBox {
    display: inline-block;
    cursor: pointer;
  }
  .pictrue {
    width: 60px;
    height: 60px;
    border: 1px dotted rgba(0, 0, 0, 0.1);
    margin-right: 15px;
    display: inline-block;
    position: relative;
    cursor: pointer;

    img {
      width: 100%;
      height: 100%;
    }
    .btndel {
      position: absolute;
      z-index: 9;
      width: 20px !important;
      height: 20px !important;
      left: 46px;
      top: -4px;
    }
  }
  .upLoad {
    width: 58px;
    height: 58px;
    line-height: 58px;
    border: 1px dotted rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    background: rgba(0, 0, 0, 0.02);
    cursor: pointer;
  }
  .col {
    color: #2d8cf0;
    cursor: pointer;
  }
}
.addfont {
  font-size: 12px;
  color: var(--prev-color-primary);
  margin-left: 14px;
  cursor: pointer;
  margin-left: 10px;
  cursor: pointer;
}
</style>
