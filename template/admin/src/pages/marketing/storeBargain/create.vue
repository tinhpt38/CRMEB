<template>
  <div>
    <pages-header
      ref="pageHeader"
      :title="$route.params.id ? 'Chỉnh sửa Sản phẩm trả giá' : 'Thêm Sản phẩm trả giá'"
      :backUrl="$routeProStr + '/marketing/store_bargain/index'"
    ></pages-header>
    <el-card :bordered="false" shadow="never" class="mt16">
      <el-row class="mt30 acea-row row-middle row-center">
        <el-col :span="20">
          <steps :stepList="stepList" :isActive="current"></steps>
        </el-col>
        <el-col :span="23" v-loading="spinShow">
          <el-form
            class="form mt30"
            ref="formValidate"
            :rules="ruleValidate"
            :model="formValidate"
            @on-validate="validate"
            :label-width="labelWidth"
            :label-position="labelPosition"
            @submit.native.prevent
          >
            <el-form-item label="Chọn sản phẩm：" prop="image_input" v-show="current === 0">
              <div class="picBox" v-db-click @click="changeGoods">
                <div class="pictrue" v-if="formValidate.image">
                  <img v-lazy="formValidate.image" />
                </div>
                <div class="upLoad acea-row row-center-wrapper" v-else>
                  <i class="el-icon-goods" style="font-size: 24px"></i>
                </div>
              </div>
            </el-form-item>
            <el-row v-show="current === 1">
              <el-col :span="24">
                <el-form-item label="Hình ảnh chính của sản phẩm：" prop="image">
                  <div class="picBox" v-db-click @click="modalPicTap('dan', 'danFrom')">
                    <div class="pictrue" v-if="formValidate.image">
                      <img v-lazy="formValidate.image" />
                    </div>
                    <div class="upLoad acea-row row-center-wrapper" v-else>
                      <i class="el-icon-picture-outline" style="font-size: 24px"></i>
                    </div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Ảnh slider sản phẩm：" prop="images">
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
                  <el-form-item label="Tên hoạt động mặc cả：" prop="title" label-for="title">
                    <el-input
                      placeholder="Vui lòng nhập tên hoạt động thương lượng"
                      v-model="formValidate.title"
                      class="content_width"
                      maxlength="30"
                      show-word-limit
                    />
                  </el-form-item>
                </el-col>
              </el-col>
              <el-col :span="24">
                <el-col v-bind="grid">
                  <el-form-item label="Giới thiệu về hoạt động thương lượng：" prop="info" label-for="info">
                    <el-input
                      placeholder="Vui lòng nhập mô tả hoạt động thương lượng"
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
                <el-form-item label="Thời gian hoạt động：" prop="section_time">
                  <div>
                    <el-date-picker
                      clearable
                      :editable="false"
                      type="datetimerange"
                      format="yyyy-MM-dd HH:mm"
                      value-format="yyyy-MM-dd HH:mm"
                      range-separator="-"
                      start-placeholder="ngày bắt đầu"
                      end-placeholder="ngày kết thúc"
                      @change="onchangeTime"
                      style="width: 460px"
                      v-model="formValidate.section_time"
                    ></el-date-picker>
                    <div class="grey">Đặt thời gian bắt đầu và kết thúc của sự kiện. Người dùng có thể bắt đầu thương lượng giá trong thời gian đã định.</div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24" v-if="formValidate.virtual_type == 0">
                <el-form-item label="Phương pháp hậu cần：" prop="logistics">
                  <el-checkbox-group v-model="formValidate.logistics">
                    <el-checkbox label="1">Chuyển phát nhanh</el-checkbox>
                    <el-checkbox label="2">Đến cửa hàng</el-checkbox>
                  </el-checkbox-group>
                </el-form-item>
              </el-col>
              <el-col :span="24" v-if="formValidate.virtual_type == 0 && formValidate.logistics.includes('1')">
                <el-form-item label="Cài đặt phí vận chuyển：" :prop="formValidate.freight != 1 ? 'freight' : ''">
                  <el-radio-group v-model="formValidate.freight">
                    <el-radio :label="2">Bưu phí cố định</el-radio>
                    <el-radio :label="3">Mẫu vận chuyển sản phẩm</el-radio>
                  </el-radio-group>
                </el-form-item>
              </el-col>
              <el-col
                :span="24"
                v-if="formValidate.freight != 3 && formValidate.freight != 1 && formValidate.virtual_type == 0 && formValidate.logistics.includes('1')"
              >
                <el-form-item label="">
                  <div class="acea-row">
                    <el-input-number
                      :controls="false"
                      :min="0"
                      :max="9999999999"
                      v-model="formValidate.postage"
                      placeholder="Vui lòng nhập số tiền"
                      class="content_width"
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
                      placeholder="Vui lòng chọn mẫu vận chuyển sản phẩm"
                      class="content_width"
                    >
                      <el-option
                        v-for="(item, index) in templateList"
                        :value="item.id"
                        :key="index"
                        :label="item.name"
                      ></el-option>
                    </el-select>
                    <span class="addfont" v-db-click @click="freight">Đã thêm mẫu vận chuyển sản phẩm</span>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Số người thương lượng：" prop="people_num" label-for="people_num">
                  <div>
                    <el-input-number
                      :controls="false"
                      placeholder="Vui lòng nhập số lượng người thương lượng"
                      element-id="people_num"
                      :min="2"
                      :max="10000"
                      :precision="0"
                      v-model="formValidate.people_num"
                      class="content_width input-number-unit-class"
                      class-unit="mọi người"
                    />
                    <div class="grey">Cần bao nhiêu người để mặc cả thành công?</div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Số lần cắt：" prop="bargain_num" label-for="bargain_num">
                  <div>
                    <el-input-number
                      :controls="false"
                      placeholder="Vui lòng nhập số lần giúp đỡ"
                      :min="1"
                      :max="10000"
                      :precision="0"
                      v-model="formValidate.bargain_num"
                      class="content_width input-number-unit-class"
                      class-unit="hạng hai"
                    />
                    <div class="grey">
                      Số lần người dùng có thể giúp mặc cả cho một sản phẩm. Ví dụ: số lần đặt là 1. A và B gửi liên kết thương lượng sản phẩm A đến C cùng một lúc. C chỉ có thể giúp một trong hai người A hoặc B thương lượng.
                    </div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Đơn vị：" prop="unit_name" label-for="unit_name">
                  <el-input
                    placeholder="Vui lòng nhập Đơn vị"
                    element-id="unit_name"
                    v-model="formValidate.unit_name"
                    class="content_width"
                  />
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Giới hạn số lượng mua hàng：" prop="num">
                  <div>
                    <el-input-number
                      :controls="false"
                      placeholder="Giới hạn số lượng mua hàng"
                      :min="1"
                      :max="10000"
                      :precision="0"
                      v-model="formValidate.num"
                      class="content_width input-number-unit-class"
                      :class-unit="formValidate.unit_name || 'miếng'"
                    />
                    <div class="grey">Giới hạn số lần mặc cả về giá do mỗi người dùng thực hiện trong một hoạt động</div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Loại：">
                  <el-input-number
                    :controls="false"
                    placeholder="Vui lòng nhập sắp xếp"
                    element-id="sort"
                    :min="0"
                    :max="10000"
                    :precision="0"
                    v-model="formValidate.sort"
                    class="content_width"
                  />
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Có tham gia phân phối khi thương lượng không：" props="is_commission" label-for="is_commission">
                  <div>
                    <el-switch
                      class="defineSwitch"
                      :active-value="1"
                      :inactive-value="0"
                      v-model="formValidate.is_commission"
                      size="large"
                      active-text="Hoạt động"
                      inactive-text="đóng cửa"
                    >
                    </el-switch>
                    <div class="grey">Liệu sản phẩm có tham gia giảm giá phân phối tại trung tâm mua sắm hay không</div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Trạng thái hoạt động：" props="status" label-for="status">
                  <el-switch
                    class="defineSwitch"
                    :active-value="1"
                    :inactive-value="0"
                    v-model="formValidate.status"
                    size="large"
                    active-text="Hoạt động"
                    inactive-text="đóng cửa"
                  >
                  </el-switch>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Lựa chọn thông số kỹ thuật：">
                  <el-table :data="specsData" border>
                    <el-table-column width="50">
                      <template slot-scope="scope">
                        <el-radio type="index" v-model="templateRadio" :label="scope.$index" @input="getTemplateRow"
                          >&nbsp;</el-radio
                        >
                      </template>
                    </el-table-column>
                    <el-table-column
                      :label="Item.title"
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
                        <template v-else-if="item.slot === 'min_price'">
                          <el-input-number
                            :controls="false"
                            v-model="scope.row.min_price"
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
            </el-row>
            <div v-if="current === 2">
              <el-form-item label="Nội dung：">
                <WangEditor
                  style="width: 90%"
                  :content="formValidate.description"
                  @editorContent="getEditorContent"
                ></WangEditor>
              </el-form-item>
            </div>
            <div v-if="current === 3">
              <el-form-item label="Luật lệ：">
                <WangEditor
                  style="width: 90%"
                  :content="formValidate.rule"
                  @editorContent="getEditorContent2"
                ></WangEditor>
              </el-form-item>
            </div>
            <el-form-item>
              <el-button
                v-if="current !== 0"
                class="submission"
                v-db-click
                @click="step"
                :disabled="($route.params.id && $route.params.id !== '0' && current === 1) || current === 0"
                >Bước trước</el-button
              >
              <el-button
                type="primary"
                :disabled="submitOpen && current === 3"
                class="submission"
                v-db-click
                @click="next('formValidate')"
                >{{ current === 3 ? 'nộp' : 'Bước tiếp theo' }}</el-button
              >
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
    </el-card>
    <!-- Chọn sản phẩm-->
    <el-dialog :visible.sync="modals" title="Danh sách sản phẩm" class="paymentFooter" width="1000px">
      <goods-list ref="goodslist" @getProductId="getProductId"></goods-list>
    </el-dialog>

    <!-- Tải ảnh lên-->
    <el-dialog :visible.sync="modalPic" width="950px" title="Tải lên hình ảnh sản phẩm" :close-on-click-modal="false">
      <uploadPictures
        :isChoice="isChoice"
        @getPic="getPic"
        @getPicD="getPicD"
        :gridBtn="gridBtn"
        :gridPic="gridPic"
        v-if="modalPic"
      ></uploadPictures>
    </el-dialog>
    <!-- Mẫu vận chuyển hàng hóa-->
    <freight-template ref="template" @addSuccess="productGetTemplate"></freight-template>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import goodsList from '@/components/goodsList/index';
import uploadPictures from '@/components/uploadPictures';
import { bargainInfoApi, bargainCreatApi, productAttrsApi } from '@/api/marketing';
import { productGetTemplateApi } from '@/api/product';
import freightTemplate from '@/components/freightTemplate/index';
import WangEditor from '@/components/wangEditor/index.vue';
import steps from '@/components/steps/index';

export default {
  name: 'storeBargainCreate',
  components: {
    goodsList,
    uploadPictures,
    freightTemplate,
    WangEditor,
    steps,
  },
  data() {
    return {
      templateRadio: 0,
      submitOpen: false,
      spinShow: false,
      myConfig: {
        autoHeightEnabled: false, // Trình chỉnh sửa không được tự động nâng lên bởi nội dung
        initialFrameHeight: 500, // chiều cao container ban đầu
        initialFrameWidth: '100%', // chiều rộng container ban đầu
        UEDITOR_HOME_URL: '/UEditor/',
        serverUrl: '',
      },
      stepList: ['Chọn đồ giá hời', 'Điền thông tin cơ bản', 'Sửa đổi chi tiết sản phẩm', 'Sửa đổi quy tắc sản phẩm'],
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
        lg: 8,
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
      modals: false,
      modal_loading: false,
      images: [],
      templateList: [],
      columns: [],
      specsData: [],
      formValidate: {
        images: [],
        info: '',
        title: '',
        store_name: '',
        image: '',
        unit_name: '',
        price: 0,
        min_price: 0,
        bargain_max_price: 10,
        bargain_min_price: 0.01,
        cost: 0,
        bargain_num: 1,
        people_num: 2,
        stock: 1,
        sales: 0,
        sort: 0,
        num: 1,
        give_integral: 0,
        is_postage: 0,
        is_hot: 0,
        status: 0,
        section_time: [],
        description: '',
        rule: '',
        id: 0,
        product_id: 0,
        temp_id: '',
        attrs: [],
        items: [],
        logistics: [], //Chọn phương thức hậu cần
        freight: 2, //Cài đặt phí vận chuyển
        postage: 1, //Đặt số tiền vận chuyển
        is_commission: 0,
      },
      description: '',
      rule: '',
      ruleValidate: {
        image: [{ required: true, message: 'Vui lòng chọn hình ảnh chính', trigger: 'change' }],
        images: [
          {
            required: true,
            type: 'array',
            message: 'Vui lòng chọn hình ảnh chính',
            trigger: 'change',
          },
          {
            type: 'array',
            min: 1,
            message: 'Choose two hobbies at best',
            trigger: 'change',
          },
        ],
        title: [{ required: true, message: 'Vui lòng nhập tên hoạt động thương lượng', trigger: 'blur' }],
        info: [{ required: true, message: 'Vui lòng nhập mô tả hoạt động thương lượng', trigger: 'blur' }],
        store_name: [{ required: true, message: 'Vui lòng nhập tên sản phẩm giảm giá', trigger: 'blur' }],
        section_time: [
          {
            required: true,
            type: 'array',
            message: 'Vui lòng chọn thời gian diễn ra sự kiện',
            trigger: 'change',
          },
        ],
        unit_name: [{ required: true, message: 'Vui lòng nhập Đơn vị', trigger: 'blur' }],
        price: [
          {
            required: true,
            type: 'number',
            message: 'Vui lòng nhập giá gốc',
            trigger: 'blur',
          },
        ],
        min_price: [
          {
            required: true,
            type: 'number',
            message: 'Vui lòng nhập giá mua tối thiểu',
            trigger: 'blur',
          },
        ],
        // bargain_max_price: [
        //     { required: true, type: 'number', message: 'Vui lòng nhập số tiền tối đa cho một lần thương lượng', trigger: 'blur' }
        // ],
        // bargain_min_price: [
        //     { required: true, type: 'number', message: 'Số tiền tối thiểu cho một lần thương lượng', trigger: 'blur' }
        // ],
        cost: [
          {
            required: true,
            type: 'number',
            message: 'Vui lòng nhập giá thành',
            trigger: 'blur',
          },
        ],
        bargain_num: [
          {
            required: true,
            type: 'number',
            message: 'Vui lòng nhập số lần giúp đỡ',
            trigger: 'blur',
          },
        ],
        people_num: [
          {
            required: true,
            type: 'number',
            message: 'Vui lòng nhập số lượng người thương lượng',
            trigger: 'blur',
          },
        ],
        stock: [
          {
            required: true,
            type: 'number',
            message: 'Vui lòng nhập hàng tồn kho',
            trigger: 'blur',
          },
        ],
        num: [
          {
            required: true,
            type: 'number',
            message: 'Vui lòng nhập số lượng được phép cho một lần mua',
            trigger: 'blur',
          },
        ],
        temp_id: [
          {
            required: true,
            message: 'Vui lòng chọn mẫu vận chuyển sản phẩm',
            trigger: 'change',
            type: 'number',
          },
        ],
      },
      currentid: 0,
      picTit: '',
      tableIndex: 0,
      copy: 0,
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '140px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  mounted() {
    if (this.$route.params.id !== '0' && this.$route.params.id) {
      this.copy = this.$route.params.copy;
      this.current = 1;
      this.getInfo();
    }
    this.productGetTemplate();
  },
  methods: {
    // Chi tiết
    getEditorContent(data) {
      this.description = data;
    },
    // Nội dung quy tắc
    getEditorContent2(data) {
      this.rule = data;
    },
    // Thêm mẫu vận chuyển hàng hóa
    freight() {
      this.$refs.template.id = 0;
      this.$refs.template.isTemplate = true;
    },
    // Thông số kỹ thuật mặc cả；
    productAttrs(row) {
      let that = this;
      productAttrsApi(row.id, 2)
        .then((res) => {
          let data = res.data.info;
          that.columns = data.header;
          // that.columns.unshift(radio);
          that.specsData = data.attrs;
          that.formValidate.items = data.items;
          that.$set(that.formValidate, 'attrs', [that.specsData[0]]);
        })
        .catch((res) => {
          that.$message.error(res.msg);
        });
    },
    getTemplateRow(index) {
      this.currentid = index;
      this.$set(this.formValidate, 'attrs', [this.specsData[index]]);
    },
    // Nhận mẫu vận chuyển；
    productGetTemplate() {
      productGetTemplateApi().then((res) => {
        this.templateList = res.data;
      });
    },
    // hàng hóaid
    getProductId(row) {
      this.modal_loading = false;
      this.modals = false;
      setTimeout(() => {
        this.formValidate = {
          // attrs: row.attrs,
          images: row.slider_image,
          info: row.store_info,
          title: row.store_name,
          store_name: row.store_name,
          image: row.image,
          unit_name: row.unit_name,
          price: 0, // Không lấy giá gốc của sản phẩm
          min_price: 0,
          bargain_max_price: 10,
          bargain_min_price: 0.01,
          cost: row.cost,
          bargain_num: 1,
          people_num: 2,
          stock: row.stock,
          sales: row.sales,
          sort: row.sort,
          num: 1,
          give_integral: row.give_integral,
          is_postage: row.is_postage,
          is_hot: row.is_hot,
          status: 0,
          section_time: [],
          description: '', // Không lấy từ sản phẩm
          rule: '',
          id: 0,
          product_id: row.id,
          temp_id: row.temp_id,
          logistics: row.temp_id ? row.temp_id : ['1'], //Chọn phương thức hậu cần
          freight: row.freight, //Cài đặt phí vận chuyển
          postage: row.postage, //Đặt số tiền vận chuyển
          custom_form: row.custom_form, //Dữ liệu biểu mẫu tùy chỉnh
          virtual_type: row.virtual_type, //Loại hàng hóa ảo
          is_commission: row.is_commission,
          description: row.description,
        };
        this.productAttrs(row);
      }, 500);
    },
    cancel() {
      this.modals = false;
    },
    // di chuyển
    handleDragStart(e, item) {
      this.dragging = item;
    },
    handleDragEnd(e, item) {
      this.dragging = null;
    },
    // Đầu tiên, biến div thành một phần tử có thể đặt được, tức là viết lại nódragenter/dragover
    handleDragOver(e) {
      e.dataTransfer.dropEffect = 'move';
    },
    handleDragEnter(e, item) {
      e.dataTransfer.effectAllowed = 'move';
      if (item === this.dragging) {
        return;
      }
      const newItems = [...this.formValidate.images];
      const src = newItems.indexOf(this.dragging);
      const dst = newItems.indexOf(item);
      newItems.splice(dst, 0, ...newItems.splice(src, 1));
      this.formValidate.images = newItems;
    },
    // ngày cụ thể
    onchangeTime(e) {
      this.formValidate.section_time = e;
    },
    // Chi tiết
    getInfo() {
      this.spinShow = true;
      bargainInfoApi(this.$route.params.id)
        .then(async (res) => {
          let that = this;
          let info = res.data.info;
          this.formValidate = info;
          this.formValidate.rule = info.rule === null ? '' : info.rule;
          this.$set(this.formValidate, 'items', info.attrs.items);
          this.description = this.formValidate.description;
          this.columns = info.attrs.header;
          this.specsData = info.attrs.value;
          let defaultAttrs = [];
          info.attrs.value.forEach(function (item, index) {
            if (item.opt) {
              defaultAttrs.push(item);
              that.$set(that, 'currentid', index);
              that.$set(that, 'templateRadio', index);
              that.$set(that.formValidate, 'attrs', defaultAttrs);
            }
          });
          this.spinShow = false;
        })
        .catch((res) => {
          this.spinShow = false;
          this.$message.error(res.msg);
        });
    },
    // Bước tiếp theo
    next(name) {
      if (this.current === 3) {
        this.formValidate.description = this.description;
        this.formValidate.rule = this.rule;
        this.$refs[name].validate((valid) => {
          if (valid) {
            if (this.copy == 1) this.formValidate.copy = 1;
            this.formValidate.id = this.$route.params.id || 0;
            this.submitOpen = true;
            bargainCreatApi(this.formValidate)
              .then(async (res) => {
                this.submitOpen = false;
                this.$message.success(res.msg);
                setTimeout(() => {
                  this.$router.push({
                    path: this.$routeProStr + '/marketing/store_bargain/index',
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
            if (this.currentid === '') {
              return this.$message.error('Vui lòng chọn thông số thuộc tính');
            } else {
              let val = this.specsData[this.currentid];
              // let formValidate = this.formValidate.attrs[0];
              // formValidate.price = val.price;
              // formValidate.min_price = val.min_price;
              // formValidate.quota = val.quota;
              if (this.formValidate.attrs[0].quota <= 0) {
                return this.$message.error('Giới hạn thương lượng phải lớn hơn0');
              }
              if (this.formValidate.attrs[0].quota > This.formValidate.attrs[0]['stock']) {
                return this.$message.error('Giới hạn thương lượng không thể vượt quá hàng tồn kho đặc điểm kỹ thuật');
              }
            }
            this.current += 1;
            // setTimeout((e) => {
            //   this.formValidate.description += ' ';
            // }, 0);
          } else {
            return this.$message.warning('Vui lòng điền đầy đủ thông tin của bạn');
          }
        });
      } else {
        if (this.formValidate.image) {
          this.current += 1;
          if (this.current == 3) {
            setTimeout((e) => {
              this.formValidate.rule += ' ';
            }, 0);
          }
        } else {
          this.$message.warning('Vui lòng chọn sản phẩm');
        }
      }
    },
    // Bước trước
    step() {
      this.current--;
    },
    // nội dung
    getContent(val) {
      this.formValidate.description = val;
    },
    // luật lệ
    getRole(val) {
      this.formValidate.rule = val;
    },
    // Bấm vào hình ảnh sản phẩm
    modalPicTap(tit, picTit, index) {
      this.modalPic = true;
      this.isChoice = tit === 'dan' ? 'Lựa chọn duy nhất' : 'Nhiều lựa chọn';
      this.picTit = picTit;
      this.tableIndex = index;
    },
    // Nhận thông tin về một hình ảnh
    getPic(pc) {
      switch (this.picTit) {
        case 'danFrom':
          this.formValidate.image = pc.att_dir;
          break;
        default:
          this.specsData[this.tableIndex].pic = pc.att_dir;
          this.formValidate.attrs[0].pic = pc.att_dir;
      }
      this.modalPic = false;
    },
    // Nhận nhiều thông tin hình ảnh
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
    // Chọn sản phẩm
    changeGoods() {
      this.modals = true;
      this.$nextTick((e) => {
        this.$refs.goodslist.formValidate.is_show = -1;
        this.$refs.goodslist.formValidate.type = 3;
        this.$refs.goodslist.getList();
        this.$refs.goodslist.goodsCategory();
      });
    },
    // xác nhận mẫu
    validate(prop, status, error) {
      if (status === false) {
        this.$message.error(error);
      }
    },
    // Thêm cửa sổ bật lên tùy chỉnh
    addCustomDialog(editorId) {
      window.UE.registerUI(
        'test-dialog',
        function (editor, uiName) {
          // tạo nên dialog
          let dialog = new window.UE.ui.Dialog({
            // Chỉ định đường dẫn của trang trong lớp bật lên. Chỉ có các trang được hỗ trợ ở đây. Vui lòng tham khảo Câu hỏi thường gặp để biết đường dẫn. 2
            iframeUrl: this.$routeProStr + '/widget.images/index.html?fodder=dialog',
            // Cần chỉ định phiên bản trình soạn thảo hiện tại
            editor: editor,
            // Chỉ định tên của hộp thoại
            name: uiName,
            // dialog tiêu đề
            title: 'Tải ảnh lên',
            // Chỉ định kiểu xung quanh của hộp thoại
            cssRules: 'width:960px;height:550px;padding:20px;',
          });
          this.dialog = dialog;
          var btn = new window.UE.ui.Button({
            name: 'dialog-button',
            title: 'Tải ảnh lên',
            cssRules: `background-image: url(../../../assets/images/icons.png);background-position: -726px -77px;`,
            onclick: function () {
              // kết xuấtdialog
              dialog.render();
              dialog.open();
            },
          });
          return btn;
        },
        37,
      );
    },
  },
};
</script>

<style lang="scss" scoped>
.content_width {
  width: 460px;
}
.grey {
  color: #999;
  font-size: 12px;
}
.maxW ::v-deep .ivu-select-dropdown {
  max-width: 600px;
}
.ivu-table-wrapper {
  border-left: 1px solid #dcdee2;
  border-top: 1px solid #dcdee2;
}
.tabBox_img {
  width: 50px;
  height: 50px;
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
