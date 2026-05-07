<template>
  <div>
    <pages-header
      ref="pageHeader"
      :title="$route.params.id && !$route.params.copy ? 'Sửa sản phẩm nhóm' : 'Thêm sản phẩm nhóm'"
      :backUrl="$routeProStr + '/marketing/store_combination/index'"
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
            :model="formValidate"
            :rules="ruleValidate"
            @on-validate="validate"
            :label-width="labelWidth"
            :label-position="labelPosition"
            @submit.native.prevent
          >
            <el-form-item label="Chọn sản phẩm：" prop="image_input" v-if="current === 0">
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
                  <el-form-item label="Tên nhóm：" prop="title" label-for="title">
                    <el-input
                      elearable
                      placeholder="Vui lòng nhập tên nhóm"
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
                  <el-form-item label="Giới thiệu về chia sẻ nhóm：" prop="info" label-for="info">
                    <el-input
                      placeholder="Vui lòng nhập thông tin nhóm"
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
                <el-form-item label="Giờ nhóm：" prop="section_time">
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
                      class="content_width"
                      v-model="formValidate.section_time"
                    ></el-date-picker>
                    <div class="grey">Đặt thời gian bắt đầu và kết thúc của sự kiện. Người dùng có thể bắt đầu tham gia nhóm trong thời gian đã đặt.</div>
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
                v-if="
                  formValidate.freight != 3 &&
                  formValidate.freight != 1 &&
                  formValidate.virtual_type == 0 &&
                  formValidate.logistics.includes('1')
                "
              >
                <el-form-item label="">
                  <div class="acea-row">
                    <el-input-number
                      :controls="false"
                      :min="0"
                      :max="9999999999"
                      v-model="formValidate.postage"
                      placeholder="Vui lòng nhập số tiền"
                      class="content_width input-number-unit-class"
                      class-unit="đ"
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
                <el-form-item label="Giới hạn thời gian nhóm nhóm：" prop="effective_time">
                  <div>
                    <el-input-number
                      :controls="false"
                      placeholder="Vui lòng nhập giới hạn thời gian đặt vé theo nhóm"
                      class="content_width input-number-unit-class"
                      class-unit="Giờ"
                      v-model="formValidate.effective_time"
                    />
                    <div class="grey">
                      Bộ đếm thời gian bắt đầu sau khi người dùng bắt đầu mua hàng theo nhóm. Số lượng bạn bè được chỉ định phải được mời tham gia nhóm trong thời gian đã định. Nếu vượt quá thời hạn, hệ thống sẽ xác định rằng giao dịch mua theo nhóm không thành công và tự động bắt đầu hoàn tiền.
                    </div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Số người trong nhóm：" prop="people">
                  <div>
                    <el-input-number
                      :controls="false"
                      :min="2"
                      :max="10000"
                      placeholder="Vui lòng nhập số lượng người trong nhóm"
                      :precision="0"
                      v-model="formValidate.people"
                      class="content_width input-number-unit-class"
                      class-unit="mọi người"
                    />
                    <div class="grey">Số lượng người dùng cần thiết để tham gia vào một cuộc chiến nhóm</div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Điền số lượng người trong một nhóm ảo：" prop="virtualPeople">
                  <div>
                    <el-input-number
                      :controls="false"
                      placeholder="Đặt số lượng người để hoàn thành nhóm ảo"
                      :precision="0"
                      :max="10000"
                      :min="0"
                      v-model="formValidate.virtualPeople"
                      class="content_width input-number-unit-class"
                      class-unit="mọi người"
                    />
                    <div class="grey">
                      Đặt số lượng người để hoàn thành nhóm ảo. Ví dụ: nếu một nhóm 5 người được đặt thành 2 người, khi số thành viên trong nhóm nhiều hơn hoặc bằng 3 người thì tối đa 2 vị trí còn lại sẽ tự động được điền vào cuối nhóm. Nếu bạn không bật tính năng nhóm ảo, vui lòng đặt nó thành0
                    </div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Đơn vị：" prop="unit_name" label-for="unit_name">
                  <el-input clearable placeholder="Vui lòng nhập Đơn vị" v-model="formValidate.unit_name" class="content_width" />
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Tổng số lượng mua giới hạn：" prop="num">
                  <div>
                    <el-input-number
                      :controls="false"
                      :min="1"
                      placeholder="Vui lòng nhập tổng số lượng giới hạn"
                      :precision="0"
                      :max="10000"
                      v-model="formValidate.num"
                      class="content_width input-number-unit-class"
                      :class-unit="formValidate.unit_name || 'miếng'"
                    />
                    <div class="grey">
                      Số lượng tối đa mà người dùng có thể mua trong thời gian hoạt động của sản phẩm này. Ví dụ: đặt thành 4, nghĩa là mỗi người dùng có thể mua tối đa 4 mặt hàng trong thời gian hiệu lực của sự kiện này.
                    </div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Giới hạn số lượng mua một lần：" prop="once_num">
                  <div>
                    <el-input-number
                      :controls="false"
                      :min="1"
                      placeholder="Vui lòng nhập giới hạn số lượng mua một lần"
                      :precision="0"
                      :max="10000"
                      v-model="formValidate.once_num"
                      class="content_width input-number-unit-class"
                      :class-unit="formValidate.unit_name || 'miếng'"
                    />
                    <div class="grey">
                      Khi người dùng tham gia mua nhóm, sẽ có giới hạn về số lượng mua tối đa cùng một lúc. Ví dụ: đặt thành 2, nghĩa là mỗi lần người dùng tham gia mua hàng theo nhóm, người dùng có thể chọn tối đa 2 mặt hàng cho một lần mua.
                    </div>
                  </div>
                </el-form-item>
              </el-col>

              <el-col :span="24">
                <el-form-item label="Tỷ lệ hoàn trả hoa hồng trưởng nhóm：" prop="head_commission">
                  <div>
                    <el-input-number
                      :controls="false"
                      :min="0"
                      :max="100"
                      placeholder="Tỷ lệ hoàn trả hoa hồng trưởng nhóm"
                      :precision="0"
                      v-model="formValidate.head_commission"
                      class="content_width input-number-unit-class"
                      class-unit="%"
                    />
                    <div class="grey">
                      Sau khi nhóm được tập hợp thành công, nếu trưởng nhóm là nhà phân phối thì một khoản hoa hồng nhất định sẽ được trả lại cho trưởng nhóm khi đơn hàng được xác nhận và nhận. Tỷ lệ hoa hồng dựa trên số tiền Thanh toán thực tế.0-100%
                    </div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Việc chia sẻ nhóm có liên quan đến việc phân phối hay không：" props="is_commission" label-for="is_commission">
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
                    <div class="grey">Liệu các sản phẩm mua theo nhóm có tham gia giảm giá phân phối tại trung tâm thương mại hay không</div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Loại：">
                  <el-input-number
                    :controls="false"
                    placeholder="Vui lòng nhập sắp xếp"
                    :precision="0"
                    :max="10000"
                    :min="0"
                    v-model="formValidate.sort"
                    class="content_width"
                  />
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Khuyến nghị phổ biến：" props="is_hot" label-for="is_hot">
                  <el-switch
                    class="defineSwitch"
                    :active-value="1"
                    :inactive-value="0"
                    v-model="formValidate.is_host"
                    size="large"
                    active-text="Hoạt động"
                    inactive-text="đóng cửa"
                  >
                  </el-switch>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Trạng thái hoạt động：" props="is_show" label-for="is_show">
                  <el-switch
                    class="defineSwitch"
                    :active-value="1"
                    :inactive-value="0"
                    v-model="formValidate.is_show"
                    size="large"
                    active-text="Hoạt động"
                    inactive-text="đóng cửa"
                  >
                  </el-switch>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Lựa chọn thông số kỹ thuật：">
                  <el-table
                    ref="multipleTable"
                    :data="specsData"
                    :row-key="getRowKeys"
                    border
                    @selection-change="changeCheckbox"
                  >
                    <el-table-column type="selection" :reserve-selection="true" width="55"> </el-table-column>
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
                        <template v-if="item.slot === 'quota'">
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
            <el-row v-show="current === 2">
              <el-col :span="24">
                <el-form-item label="Nội dung：">
                  <WangEditor
                    style="width: 90%"
                    :content="formValidate.description"
                    @editorContent="getEditorContent"
                  ></WangEditor>
                </el-form-item>
              </el-col>
            </el-row>
            <el-form-item>
              <el-button
                class="submission"
                v-db-click
                @click="step"
                :disabled="($route.params.id && current === 1) || current === 0"
                >Bước trước</el-button
              >
              <el-button
                type="primary"
                :disabled="submitOpen && current === 2"
                class="submission"
                v-db-click
                @click="next('formValidate')"
                >{{ current === 2 ? 'nộp' : 'Bước tiếp theo' }}</el-button
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
import WangEditor from '@/components/wangEditor/index.vue';
import uploadPictures from '@/components/uploadPictures';
import { combinationInfoApi, combinationCreatApi, productAttrsApi } from '@/api/marketing';
import { productGetTemplateApi } from '@/api/product';
import freightTemplate from '@/components/freightTemplate/index';
import steps from '@/components/steps/index';

export default {
  name: 'storeCombinationCreate',
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
      stepList: ['Chọn sản phẩm nhóm', 'Điền thông tin cơ bản', 'Sửa đổi chi tiết sản phẩm'],
      myConfig: {
        autoHeightEnabled: false, // Trình chỉnh sửa không được tự động nâng lên bởi nội dung
        initialFrameHeight: 500, // chiều cao container ban đầu
        initialFrameWidth: '100%', // chiều rộng container ban đầu
        UEDITOR_HOME_URL: '/UEditor/',
        serverUrl: '',
      },
      modals: false,
      modal_loading: false,
      images: [],
      templateList: [],
      columns: [],
      specsData: [],
      picTit: '',
      tableIndex: 0,
      formValidate: {
        images: [],
        info: '',
        title: '',
        image: '',
        unit_name: '',
        price: 0,
        effective_time: 24,
        stock: 1,
        sales: 0,
        sort: 0,
        is_postage: 0,
        is_commission: 0,
        is_host: 0,
        is_show: 0,
        section_time: [],
        description: '',
        id: 0,
        product_id: 0,
        people: 2,
        once_num: 1,
        num: 1,
        temp_id: '',
        attrs: [],
        items: [],
        virtual: 100,
        virtualPeople: 0,
        head_commission: 0,
        logistics: ['1'], //Chọn phương thức hậu cần
        freight: 2, //Cài đặt phí vận chuyển
        postage: 1, //Đặt số tiền vận chuyển
      },
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
        title: [{ required: true, message: 'Vui lòng nhập tên nhóm', trigger: 'blur' }],
        info: [{ required: true, message: 'Vui lòng nhập thông tin nhóm', trigger: 'blur' }],
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
            message: 'Vui lòng nhập giá nhóm',
            trigger: 'blur',
          },
        ],
        cost: [
          {
            required: true,
            type: 'number',
            message: 'Vui lòng nhập giá thành',
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
        give_integral: [
          {
            required: true,
            type: 'number',
            message: 'Vui lòng nhập điểm thưởng',
            trigger: 'blur',
          },
        ],
        effective_time: [
          {
            required: true,
            type: 'number',
            message: 'Vui lòng nhập giới hạn thời gian đặt vé theo nhóm(Đơn vị giờ)',
            trigger: 'blur',
          },
        ],
        people: [
          {
            required: true,
            type: 'number',
            message: 'Vui lòng nhập số lượng người trong nhóm',
            trigger: 'blur',
          },
        ],
        num: [
          {
            required: true,
            type: 'number',
            message: 'Vui lòng nhập giới hạn số lượng mua hàng',
            trigger: 'blur',
          },
        ],
        once_num: [
          {
            required: true,
            type: 'number',
            message: 'Vui lòng nhập giới hạn số lượng mua một lần',
            trigger: 'blur',
          },
        ],
        virtualPeople: [
          {
            required: true,
            type: 'number',
            message: 'Vui lòng nhập số lượng người để hoàn thành nhóm ảo',
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
      copy: 0,
      description: '',
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '155px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  mounted() {
    if (this.$route.params.id) {
      this.copy = this.$route.params.copy;
      this.current = 1;
      this.getInfo();
    }
    this.productGetTemplate();
  },
  methods: {
    changePrice(e, index) {
      this.$set(this.specsData[index], 'price', e);
    },
    getEditorContent(data) {
      this.description = data;
    },
    // Thêm mẫu vận chuyển hàng hóa
    freight() {
      this.$refs.template.id = 0;
      this.$refs.template.isTemplate = true;
    },
    // Thông số nhóm nhóm；
    productAttrs(row) {
      let that = this;
      productAttrsApi(row.id, 3)
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
          // that.columns.unshift(selection);
        })
        .catch((res) => {
          that.$message.error(res.msg);
        });
    },
    // Nhiều lựa chọn
    changeCheckbox(selection) {
      this.formValidate.attrs = selection;
    },
    // Nhận mẫu vận chuyển；
    productGetTemplate() {
      productGetTemplateApi().then((res) => {
        this.templateList = res.data;
      });
    },
    // xác nhận mẫu
    validate(prop, status, error) {
      if (status === false) {
        this.$message.error(error);
      }
    },
    // hàng hóaid
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
          price: 0, // Không lấy giá gốc của sản phẩm
          effective_time: 24,
          stock: row.stock,
          sales: row.sales,
          sort: row.sort,
          is_postage: row.is_postage,
          is_commission: 0,
          is_host: row.is_hot,
          is_show: 0,
          section_time: [],
          description: '', // Không lấy từ sản phẩm
          id: 0,
          people: 2,
          num: 1,
          once_num: 1,
          product_id: row.id,
          temp_id: row.temp_id,
          virtual: 100,
          virtualPeople: 0,
          logistics: row.logistics, //Chọn phương thức hậu cần
          freight: row.freight, //Cài đặt phí vận chuyển
          postage: row.postage, //Đặt số tiền vận chuyển
          custom_form: row.custom_form, //Dữ liệu biểu mẫu tùy chỉnh
          virtual_type: row.virtual_type, //Loại hàng hóa ảo
          head_commission: 0,
          description: row.description,
        };
        this.productAttrs(row);
      }, 500);
    },
    cancel() {
      this.modals = false;
    },
    // ngày cụ thể
    onchangeTime(e) {
      this.formValidate.section_time = e;
    },
    // Chi tiết
    getInfo() {
      this.spinShow = true;
      combinationInfoApi(this.$route.params.id)
        .then(async (res) => {
          let that = this;
          let info = res.data.info;
          let selection = {
            type: 'selection',
            width: 60,
            align: 'center',
          };
          this.formValidate = info;
          this.formValidate.virtualPeople = parseInt(
            this.formValidate.people - this.formValidate.people * (this.formValidate.virtual / 100),
          );
          this.$set(this.formValidate, 'items', info.attrs.items);
          this.columns = info.attrs.header;
          // this.columns.unshift(selection);
          this.specsData = info.attrs.value;
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
    // Bước tiếp theo
    next(name) {
      let that = this;
      if (this.current === 2) {
        this.formValidate.description = this.description;
        this.$refs[name].validate((valid) => {
          if (valid) {
            if (this.copy == 1) this.formValidate.copy = 1;
            this.formValidate.id = Number(this.$route.params.id) || 0;
            this.submitOpen = true;
            this.formValidate.virtual = parseInt(
              ((this.formValidate.people - this.formValidate.virtualPeople) / this.formValidate.people) * 100,
            );
            combinationCreatApi(this.formValidate)
              .then(async (res) => {
                this.submitOpen = false;
                this.$message.success(res.msg);
                setTimeout(() => {
                  this.$router.push({
                    path: this.$routeProStr + '/marketing/store_combination/index',
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
            if (that.formValidate.people < 2) {
              return that.$message.error('Số lượng người tham gia nhóm phải lớn hơn2');
            }
            if (that.formValidate.num < 0) {
              return that.$message.error('Giới hạn số lượng mua phải lớn hơn0');
            }
            if (that.formValidate.once_num < 0) {
              return that.$message.error('Giới hạn số lượng mua một lần phải lớn hơn0');
            }
            if (!that.formValidate.attrs) {
              return that.$message.error('Vui lòng chọn thông số thuộc tính');
            } else {
              for (let index in that.formValidate.attrs) {
                if (that.formValidate.attrs[index].quota <= 0) {
                  return that.$message.error('Giới hạn nhóm phải lớn hơn0');
                }
                if (this.formValidate.attrs[index].quota > This.formValidate.attrs[index]['stock']) {
                  return this.$message.error('Giới hạn nhóm không thể vượt quá khoảng không quảng cáo được chỉ định');
                }
              }
            }
            this.current += 1;
          } else {
            return this.$message.warning('Vui lòng điền đầy đủ thông tin của bạn');
          }
        });
      } else {
        if (this.formValidate.image) {
          this.current += 1;
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
          if (!!this.formValidate.attrs && this.formValidate.attrs.length) {
            this.$set(this.specsData[this.tableIndex], '_checked', true);
          }
          this.specsData[this.tableIndex].pic = pc.att_dir;
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
  },
};
</script>

<style lang="scss" scoped>
.content_width {
  width: 460px;
}
.grey {
  font-size: 12px;
  color: #999;
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
