<template>
  <div>
    <div class="i-layout-page-header header-title">
      <div class="fl_header">
        <el-button
          class="btn-back"
          icon="el-icon-arrow-left"
          size="small"
          type="text"
          v-db-click
          @click="$router.go(-1)"
          >Trở lại</el-button
        >
        <el-divider direction="vertical"></el-divider>
        <span class="ivu-page-header-title">{{ $route.query.lottery_id ? 'Chỉnh sửa' : 'Mới' }}rút thăm trúng thưởng</span>
      </div>
    </div>
    <el-card :bordered="false" shadow="never" class="ivu-mt" :body-style="{ padding: '0 20px 20px' }">
      <el-row class="mt30 acea-row row-middle row-center">
        <el-col :span="24" v-loading="spinShow">
          <el-form
            class="form"
            ref="formValidate"
            :rules="ruleValidate"
            :model="formValidate"
            @on-validate="validate"
            :label-width="labelWidth"
            :label-position="labelPosition"
            @submit.native.prevent
          >
            <el-row>
              <el-col :span="24">
                <el-form-item label="Loại hoạt động：" prop="name" label-for="name">
                  <el-radio-group v-model="formValidate.factor" @input="onClickTab">
                    <el-radio v-for="(item, index) in tabs" :label="item.type" :disabled="!!lottery_id" :key="index">{{
                      item.name
                    }}</el-radio>
                  </el-radio-group>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Tên hoạt động：" prop="name" label-for="name">
                  <el-input
                    placeholder="Vui lòng nhập tên sự kiện"
                    v-model="formValidate.name"
                    class="content_width"
                    maxlength="80"
                    show-word-limit
                  />
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Thời gian hoạt động：">
                  <div class="acea-row row-middle">
                    <el-date-picker
                      v-model="formValidate.period"
                      :editable="false"
                      type="datetimerange"
                      format="yyyy-MM-dd"
                      value-format="yyyy-MM-dd"
                      range-separator="-"
                      start-placeholder="ngày bắt đầu"
                      end-placeholder="ngày kết thúc"
                      @change="onchangeTime"
                      style="width: 460px"
                    ></el-date-picker>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="Người dùng tham gia：" prop="attends_user" label-for="attends_user">
                  <el-radio-group element-id="attends_user" v-model="formValidate.attends_user" @input="changeUsers">
                    <el-radio :label="1" class="radio">Tất cả người dùng</el-radio>
                    <el-radio :label="2">Một số người dùng</el-radio>
                  </el-radio-group>
                </el-form-item>
              </el-col>
              <el-col :span="24" v-if="formValidate.attends_user == 2">
                <el-form-item label="" :prop="formValidate.attends_user == 2 ? 'user_level' : ''">
                  <div class="acea-row row-middle">
                    <el-select
                      multiple
                      v-model="formValidate.user_level"
                      class="content_width"
                      placeholder="Vui lòng chọn cấp độ người dùng"
                    >
                      <el-option
                        v-for="item in userLevelListApi"
                        :value="item.id"
                        :key="item.id"
                        :label="item.name"
                      ></el-option>
                    </el-select>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24" v-if="formValidate.attends_user == 2">
                <el-form-item label="" :prop="formValidate.attends_user == 2 ? 'is_svip' : ''">
                  <div class="acea-row row-middle">
                    <el-select
                      v-model="formValidate.is_svip"
                      clearable
                      placeholder="Vui lòng chọn xem bạn có phải là thành viên trả phí hay không"
                      class="content_width"
                    >
                      <el-option
                        v-for="item in templateList"
                        :value="item.id"
                        :key="item.id"
                        :label="item.name"
                      ></el-option>
                    </el-select>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24" v-if="formValidate.attends_user == 2">
                <el-form-item label="" :prop="formValidate.attends_user == 2 ? 'user_label' : ''">
                  <div class="acea-row row-middle">
                    <div class="labelInput acea-row row-between-wrapper" v-db-click @click="selectLabelShow = true">
                      <div class="">
                        <div v-if="selectDataLabel.length">
                          <el-tag
                            :closable="false"
                            v-for="(item, index) in selectDataLabel"
                            @close="closeLabel(item)"
                            :key="index"
                            class="mr10"
                            >{{ item.label_name }}</el-tag
                          >
                        </div>
                        <span class="span" v-else>Chọn nhãn người dùng</span>
                      </div>
                      <div class="ivu-icon ivu-icon-ios-arrow-down"></div>
                    </div>
                  </div>
                  <div class="tips-info ml100 grey">Sau khi đặt đủ ba điều kiện,Chỉ những người dùng đáp ứng các điều kiện này mới có thể tham gia xổ số</div>
                </el-form-item>
              </el-col>
              <el-col :span="24" v-if="formValidate.factor == 5">
                <el-form-item
                  label="Số lần rút thăm："
                  :prop="formValidate.factor == 5 ? 'lottery_num_term' : ''"
                  label-for="status"
                >
                  <el-radio-group element-id="lottery_num_term" v-model="formValidate.lottery_num_term">
                    <el-radio :label="1" class="radio">N lần một ngày</el-radio>
                    <el-radio :label="2">N lần mỗi người</el-radio>
                  </el-radio-group>
                </el-form-item>
              </el-col>
              <el-col :span="24" v-if="formValidate.factor == 5">
                <el-form-item
                  label="Mời người dùng mới nhận được tối đa 10 lần rút thăm may mắn"
                  :prop="formValidate.factor == 5 ? 'lottery_num' : ''"
                  label-for="lottery_num"
                >
                  <div class="acea-row row-middle">
                    <div class="mr10 grey"></div>
                    <el-input-number
                      :controls="false"
                      placeholder=""
                      element-id="lottery_num"
                      :min="1"
                      :precision="0"
                      v-model="formValidate.lottery_num"
                      class="content_width"
                    />
                    <div class="ml10 grey">Hạng hai</div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col :span="24" v-if="formValidate.factor == 5">
                <el-form-item
                  label="Mời người dùng mới theo dõi tài khoản chính thức để nhận rút thăm may mắn"
                  :prop="formValidate.factor == 5 ? 'spread_num' : ''"
                  label-for="spread_num"
                >
                  <div class="acea-row row-middle">
                    <div class="mr10 grey"></div>
                    <el-input-number
                      :controls="false"
                      placeholder=""
                      element-id="spread_num"
                      :min="1"
                      :precision="0"
                      v-model="formValidate.spread_num"
                      class="content_width"
                    />
                    <div class="ml10 grey">Hạng hai</div>
                  </div>
                </el-form-item>
              </el-col>
              <el-col
                :span="24"
                v-if="formValidate.factor == 1 || formValidate.factor == 3 || formValidate.factor == 4"
              >
                <el-form-item
                  :label="FormValidate.factor == 1 ? 'Xổ số tiêu tốn điểm：' : 'Số lần rút thăm：'"
                  :prop="
                    formValidate.factor == 1 || formValidate.factor == 3 || formValidate.factor == 4 ? 'factor_num' : ''
                  "
                  label-for="factor_num"
                >
                  <div class="acea-row row-middle">
                    <!-- <div class="mr10 grey"></div> -->
                    <el-input-number
                      :controls="false"
                      placeholder=""
                      element-id="factor_num"
                      :min="1"
                      :precision="0"
                      v-model="formValidate.factor_num"
                      class="content_width"
                    >
                    </el-input-number>
                    <!-- <div class="ml10 grey" v-if="formValidate.factor !== 1">Hạng hai</div> -->
                  </div>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="24">
                <el-form-item label="Lựa chọn thông số kỹ thuật：" prop="prize">
                  <el-table ref="selection" :data="specsData">
                    <el-table-column min-width="30">
                      <template slot-scope="scope">
                        <div class="drag" @on-drag-drop="onDragDrop">
                          <img class="handle" src="@/assets/images/drag-icon.png" alt="" />
                        </div>
                      </template>
                    </el-table-column>
                    <el-table-column label="Số seri" type="index" width="50"> </el-table-column>
                    <el-table-column label="Hình ảnh" min-width="80">
                      <template slot-scope="scope">
                        <div
                          class="acea-row scope.row-middle scope.row-center-wrapper"
                          v-db-click
                          @click="modalPicTap('dan', 'goods', scope.$index)"
                        >
                          <div class="pictrue pictrueTab" v-if="scope.row.image">
                            <img v-lazy="scope.row.image" />
                          </div>
                          <div class="upLoad pictrueTab acea-row row-center-wrapper" v-else>
                            <i class="el-icon-picture-outline" style="font-size: 24px"></i>
                          </div>
                        </div>
                      </template>
                    </el-table-column>
                    <el-table-column label="Tên" min-width="80">
                      <template slot-scope="scope">
                        <div>{{ scope.row.name }}</div>
                      </template>
                    </el-table-column>
                    <el-table-column label="Phần thưởng" min-width="80">
                      <template slot-scope="scope">
                        <div>{{ scope.row.type | typeName }}</div>
                      </template>
                    </el-table-column>
                    <el-table-column label="Nhắc nhở" min-width="80">
                      <template slot-scope="scope">
                        <div>{{ scope.row.prompt }}</div>
                      </template>
                    </el-table-column>
                    <el-table-column label="Số lượng" min-width="80">
                      <template slot-scope="scope">
                        <el-input-number
                          :controls="false"
                          v-model="scope.row.total"
                          :max="9999999999"
                          :min="0"
                          :precision="0"
                          class="priceBox"
                        ></el-input-number>
                      </template>
                    </el-table-column>
                    <el-table-column label="Xác suất trúng thưởng(%)" min-width="80">
                      <template slot-scope="scope">
                        <el-input-number
                          :controls="false"
                          v-model="scope.row.percent"
                          :max="100"
                          :min="0"
                          :precision="2"
                          class="priceBox"
                        ></el-input-number>
                      </template>
                    </el-table-column>
                    <el-table-column label="Thao tác" fixed="right" width="80">
                      <template slot-scope="scope">
                        <a class="submission mr15" v-db-click @click="editGoods(scope.$index)">Chỉnh sửa</a>
                      </template>
                    </el-table-column>
                  </el-table>
                  <el-button
                    v-if="specsData.length < 8"
                    type="primary"
                    class="submission mr15 mt20"
                    v-db-click
                    @click="addGoods"
                    >Thêm sản phẩm</el-button
                  >
                </el-form-item>
                <el-form-item>
                  <div class="pl60 grey">
                    Số lượng giải thưởng phải đặt là 8. Kéo thả trong danh sách để điều chỉnh vị trí các giải thưởng trong Cửu Cung.
                    <el-tooltip effect="light" placement="bottom" width="380">
                      <a>Xem bản đồ ví dụ về vị trí</a>
                      <div class="api" slot="content">
                        <img src="../../../assets/images/lotteryTest.png" alt="" />
                      </div>
                    </el-tooltip>
                  </div>
                </el-form-item>
              </el-col>
            </el-row>
            <div>
              <el-form-item
                v-if="formValidate.factor != 3 && formValidate.factor != 4"
                :prop="formValidate.factor != 3 && formValidate.factor != 4 ? 'image' : ''"
              >
                <div class="custom-label" slot="label">
                  <div>
                    <div>Hình nền sự kiện</div>
                    <div>(750*750)</div>
                  </div>
                  <div>：</div>
                </div>
                <div class="acea-row">
                  <div class="pictrue" v-if="formValidate.image">
                    <img v-lazy="formValidate.image" />
                    <i class="el-icon-circle-close btndel" v-db-click @click="handleRemove()"></i>
                  </div>
                  <div
                    v-else
                    class="upLoad acea-row row-center-wrapper"
                    v-db-click
                    @click="modalPicTap('dan', 'danFrom')"
                  >
                    <i class="el-icon-picture-outline" style="font-size: 24px"></i>
                  </div>
                </div>
              </el-form-item>
              <el-form-item
                v-if="formValidate.factor != 3 && formValidate.factor != 4"
                label="Danh sách người chiến thắng："
                :prop="formValidate.factor != 3 && formValidate.factor != 4 ? 'is_all_record' : ''"
                label-for="is_all_record"
              >
                <el-switch
                  class="defineSwitch"
                  :active-value="1"
                  :inactive-value="0"
                  v-model="formValidate.is_all_record"
                  size="large"
                  active-text="Hoạt động"
                  inactive-text="đóng cửa"
                >
                </el-switch>
              </el-form-item>
              <el-form-item
                v-if="formValidate.factor != 3 && formValidate.factor != 4"
                label="Kỷ lục chiến thắng cá nhân："
                :prop="formValidate.factor != 3 && formValidate.factor != 4 ? 'is_personal_record' : ''"
                label-for="is_personal_record"
              >
                <el-switch
                  class="defineSwitch"
                  :active-value="1"
                  :inactive-value="0"
                  v-model="formValidate.is_personal_record"
                  size="large"
                  active-text="Hoạt động"
                  inactive-text="đóng cửa"
                >
                </el-switch>
              </el-form-item>
              <el-form-item
                v-if="formValidate.factor != 3 && formValidate.factor != 4"
                label="Quy tắc hoạt động："
                prop="is_content"
                label-for="is_content"
              >
                <el-switch
                  class="defineSwitch"
                  :active-value="1"
                  :inactive-value="0"
                  v-model="formValidate.is_content"
                  size="large"
                  active-text="Hoạt động"
                  inactive-text="đóng cửa"
                >
                </el-switch>
              </el-form-item>
              <el-form-item
                label=""
                :prop="
                  formValidate.factor != 3 && formValidate.factor != 4 && formValidate.is_content == 1 ? 'content' : ''
                "
                v-show="formValidate.factor != 3 && formValidate.factor != 4 && formValidate.is_content == 1"
              >
                <WangEditor
                  style="width: 90%"
                  :content="formValidate.content"
                  @editorContent="getEditorContent"
                ></WangEditor>
              </el-form-item>
              <el-form-item label="Trạng thái hoạt động：" prop="status" label-for="status">
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
            </div>
            <el-form-item>
              <el-button type="primary" :loading="submitOpen" v-db-click @click="next('formValidate')">Nộp</el-button>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
    </el-card>

    <!-- Tải ảnh lên-->
    <el-dialog :visible.sync="modalPic" width="950px" title="Tải lên hình ảnh sản phẩm" :close-on-click-modal="false">
      <uploadPictures :isChoice="isChoice" @getPic="getPic" v-if="modalPic"></uploadPictures>
    </el-dialog>
    <!-- Tải ảnh lên-->
    <el-dialog :visible.sync="addGoodsModel" width="720px" :title="Title" :close-on-click-modal="false">
      <addGoods ref="addGoodsForm" v-if="addGoodsModel" @addGoodsData="addGoodsData" :editData="editData"></addGoods>
      <div class="acea-row row-right mt20">
        <el-button v-db-click @click="addGoodsModel = false">Hủy bỏ</el-button>
        <el-button type="primary" v-db-click @click="submitAddGoods">Nộp</el-button>
      </div>
    </el-dialog>
    <!-- Thẻ người dùng -->
    <el-dialog
      :visible.sync="selectLabelShow"
      scrollable
      title="Vui lòng chọn nhãn người dùng"
      :closable="false"
      width="540px"
      :footer-hide="true"
      :mask-closable="false"
    >
      <userLabel
        v-if="selectLabelShow"
        :uid="0"
        ref="userLabel"
        :only_get="true"
        :selectDataLabel="selectDataLabel"
        @activeData="activeSelectData"
        @close="labelClose"
      ></userLabel>
    </el-dialog>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import goodsList from '@/components/goodsList/index';
import uploadPictures from '@/components/uploadPictures';
import userLabel from '@/components/userLabel';
import addGoods from './addGoods';
import { lotteryNewDetailApi, lotteryDetailApi, lotteryCreateApi, lotteryEditApi } from '@/api/lottery'; //Chi tiết Tạo Chỉnh sửa
import { lotteryFrom } from './formRule/lotteryFrom';
import { labelListApi } from '@/api/product';
import { levelListApi } from '@/api/user';
import WangEditor from '@/components/wangEditor/index.vue';

import { formatDate } from '@/utils/validate';
import { formatRichText } from '@/utils/editorImg';
import Sortable from 'sortablejs';

export default {
  name: 'lotteryCreate',
  components: {
    goodsList,
    uploadPictures,
    addGoods,
    WangEditor,
    userLabel,
  },
  data() {
    return {
      selectDataLabel: [],
      selectLabelShow: false,
      content: '',
      tabs: [
        {
          name: 'Trích xuất điểm',
          type: '1',
        },
        {
          name: 'Thanh toán đơn hàng',
          type: '3',
        },
        {
          name: 'Đánh giá đơn hàng',
          type: '4',
        },
      ],
      title: 'Thêm sản phẩm',
      loading: false,
      userLabelList: [], //Danh sách thẻ người dùng
      userLevelListApi: [], //Danh sách cấp độ người dùng
      submitOpen: false,
      spinShow: false,
      addGoodsModel: false,
      editData: {},
      myConfig: {
        autoHeightEnabled: false, // Trình chỉnh sửa không được tự động nâng lên bởi nội dung
        initialFrameHeight: 500, // chiều cao container ban đầu
        initialFrameWidth: '100%', // chiều rộng container ban đầu
        UEDITOR_HOME_URL: '/UEditor/',
        serverUrl: '',
      },
      isChoice: 'Lựa chọn duy nhất',
      current: 0,
      modalPic: false,
      modal_loading: false,
      images: [],
      templateList: [
        { id: -1, name: 'Không hạn chế về loại thành viên' },
        { id: 0, name: 'Thành viên không trả tiền' },
        { id: 1, name: 'Gói thẻ VIP' },
      ],
      specsData: [
        {
          type: 1, //Loại 1: Không thắng Loại 2: Điểm  3:Số dư 4: Phong bì đỏ 5:Phiếu giảm giá 6: Sản phẩm của trang web
          name: '', //Tên hoạt động
          num: 10, //Số lượng giải thưởng
          image: '', //Hình ảnh giải thưởng
          chance: 1, //Thắng cân
          total: 0, //Số lượng giải thưởng
          percent: 0, //Xác suất chiến thắng
          min_try_num: 0, //Số lần rút thăm đã thử
          prompt: '', //nhắc nhở
        },
        {
          type: 1, //Loại 1: Không thắng Loại 2: Điểm  3:Số dư 4: Phong bì đỏ 5:Phiếu giảm giá 6: Sản phẩm của trang web
          name: '', //Tên hoạt động
          num: 10, //Số lượng giải thưởng
          image: '', //Hình ảnh giải thưởng
          chance: 1, //Thắng cân
          total: 0, //Số lượng giải thưởng
          percent: 0, //Xác suất chiến thắng
          min_try_num: 0, //Số lần rút thăm đã thử
          prompt: '', //nhắc nhở
        },
        {
          type: 1, //Loại 1: Không thắng Loại 2: Điểm  3:Số dư 4: Phong bì đỏ 5:Phiếu giảm giá 6: Sản phẩm của trang web
          name: '', //Tên hoạt động
          num: 10, //Số lượng giải thưởng
          image: '', //Hình ảnh giải thưởng
          chance: 1, //Thắng cân
          total: 0, //Số lượng giải thưởng
          percent: 0, //Xác suất chiến thắng
          min_try_num: 0, //Số lần rút thăm đã thử
          prompt: '', //nhắc nhở
        },
        {
          type: 1, //Loại 1: Không thắng Loại 2: Điểm  3:Số dư 4: Phong bì đỏ 5:Phiếu giảm giá 6: Sản phẩm của trang web
          name: '', //Tên hoạt động
          num: 10, //Số lượng giải thưởng
          image: '', //Hình ảnh giải thưởng
          chance: 1, //Thắng cân
          total: 0, //Số lượng giải thưởng
          percent: 0, //Xác suất chiến thắng
          min_try_num: 0, //Số lần rút thăm đã thử
          prompt: '', //nhắc nhở
        },
        {
          type: 1, //Loại 1: Không thắng Loại 2: Điểm  3:Số dư 4: Phong bì đỏ 5:Phiếu giảm giá 6: Sản phẩm của trang web
          name: '', //Tên hoạt động
          num: 10, //Số lượng giải thưởng
          image: '', //Hình ảnh giải thưởng
          chance: 1, //Thắng cân
          total: 0, //Số lượng giải thưởng
          percent: 0, //Xác suất chiến thắng
          min_try_num: 0, //Số lần rút thăm đã thử
          prompt: '', //nhắc nhở
        },
        {
          type: 1, //Loại 1: Không thắng Loại 2: Điểm  3:Số dư 4: Phong bì đỏ 5:Phiếu giảm giá 6: Sản phẩm của trang web
          name: '', //Tên hoạt động
          num: 10, //Số lượng giải thưởng
          image: '', //Hình ảnh giải thưởng
          chance: 1, //Thắng cân
          total: 0, //Số lượng giải thưởng
          percent: 0, //Xác suất chiến thắng
          min_try_num: 0, //Số lần rút thăm đã thử
          prompt: '', //nhắc nhở
        },
        {
          type: 1, //Loại 1: Không thắng Loại 2: Điểm  3:Số dư 4: Phong bì đỏ 5:Phiếu giảm giá 6: Sản phẩm của trang web
          name: '', //Tên hoạt động
          num: 10, //Số lượng giải thưởng
          image: '', //Hình ảnh giải thưởng
          chance: 1, //Thắng cân
          total: 0, //Số lượng giải thưởng
          percent: 0, //Xác suất chiến thắng
          min_try_num: 0, //Số lần rút thăm đã thử
          prompt: '', //nhắc nhở
        },
        {
          type: 1, //Loại 1: Không thắng Loại 2: Điểm  3:Số dư 4: Phong bì đỏ 5:Phiếu giảm giá 6: Sản phẩm của trang web
          name: '', //Tên hoạt động
          num: 10, //Số lượng giải thưởng
          image: '', //Hình ảnh giải thưởng
          chance: 1, //Thắng cân
          total: 0, //Số lượng giải thưởng
          percent: 0, //Xác suất chiến thắng
          min_try_num: 0, //Số lần rút thăm đã thử
          prompt: '', //nhắc nhở
        },
      ],
      formValidate: {
        images: [],
        name: '', //Tên hoạt động
        desc: '', //Mô tả hoạt động
        image: '', //Hình nền sự kiện
        factor: '1', //Loại xổ số：1:điểm thưởng 2:Số dư 3: Thanh toán đơn hàng thành công 4:Đánh giá đơn hàng',5:tập trung vào
        factor_num: 1, //Lấy số điều kiện để quay số
        attends_user: 1, //Người dùng tham gia 1: Tất cả 2: Một số
        user_level: [], //Cấp độ người dùng tham gia
        user_label: [], //Thẻ người dùng tham gia
        is_svip: '', //Liệu người dùng tham gia có phải là thành viên trả phí hay không
        prize_num: 0, //Số lượng giải thưởng
        period: [], //Thời gian hoạt động
        prize: [], //Mảng giải thưởng
        lottery_num_term: 1, //Giới hạn số lần rút thăm: 1: mỗi ngày 2: mỗi người
        lottery_num: 1, //Số lần rút thăm
        spread_num: 1, //Theo dõi khuyến mãi để nhận số lần rút thăm
        is_all_record: 0, //Hiển thị kỷ lục chiến thắng
        is_personal_record: 0, //Hiển thị hồ sơ chiến thắng cá nhân
        is_content: 0, //Liệu thông số sự kiện có được hiển thị hay không
        content: '', //Nội dung văn bản phong phú
        status: 0, //tình trạng
      },
      ruleValidate: lotteryFrom,
      currentid: '',
      picTit: '',
      tableIndex: 0,
      copy: 0,
      editIndex: null,
      id: '',
      copy: 0,
      lottery_id: 0,
    };
  },
  filters: {
    typeName(type) {
      if (type == 1) {
        return 'Không thắng';
      } else if (type == 2) {
        return 'điểm thưởng';
      } else if (type == 3) {
        return 'Số dư';
      } else if (type == 4) {
        return 'phong bì màu đỏ';
      } else if (type == 5) {
        return 'Mã giảm giá';
      } else if (type == 6) {
        return 'sản phẩm';
      }
    },
  },
  computed: {
    ...mapState('admin/layout', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '120px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  mounted() {
    this.labelListApi();
    this.levelListApi();
    if (this.$route.query.type) {
      this.formValidate.factor = this.$route.query.type;
    }
    if (this.$route.query.lottery_id) {
      this.lottery_id = this.$route.query.lottery_id;
      this.getInfo();
    }
    this.$nextTick((e) => {
      this.setSort();
    });
  },
  methods: {
    submitAddGoods() {
      this.$refs.addGoodsForm.handleSubmit('formValidate');
    },
    changeUsers(e) {
      if (e == 1) {
        this.formValidate.user_level = []; //Cấp độ người dùng tham gia
        this.formValidate.user_label = []; //Thẻ người dùng tham gia
        this.formValidate.is_svip = '-1'; //Liệu người dùng tham gia có phải là thành viên trả phí hay không
        this.selectDataLabel = []; //Liệu người dùng tham gia có phải là thành viên trả phí hay không
      }
    },
    // Cửa sổ bật lên nhãn đóng lại
    labelClose() {
      this.selectLabelShow = false;
    },
    activeSelectData(data) {
      this.selectLabelShow = false;
      this.selectDataLabel = data;
    },
    onClickTab(e) {
      if (this.lottery_id) this.getInfo();
    },
    getEditorContent(data) {
      this.content = data;
    },
    //Danh sách thẻ người dùng
    labelListApi() {
      labelListApi().then((res) => {
        this.userLabelList = res.data.list;
      });
    },
    //Danh sách cấp độ người dùng
    levelListApi() {
      levelListApi().then((res) => {
        this.userLevelListApi = res.data.list;
      });
    },
    // ngày cụ thể
    onchangeTime(e) {
      this.$set(this.formValidate, 'period', e);
    },
    // Chi tiết
    getInfo(e) {
      this.spinShow = true;
      lotteryDetailApi(this.lottery_id)
        .then((res) => {
          if (res.status == 200 && !Array.isArray(res.data)) {
            this.formValidate = res.data;
            this.formValidate.user_level = res.data.user_level || [];
            this.selectDataLabel = res.data.user_label || [];
            this.formValidate.is_svip = res.data.is_svip;
            this.content = res.data.is_content ? res.data.content : '';
            this.formValidate.factor = res.data.factor.toString();
            this.$set(this.formValidate, 'period', [
              this.formatDate(res.data.start_time) || '',
              this.formatDate(res.data.end_time) || '',
            ]);
            this.specsData = res.data.prize;
            this.getProbability();
          } else {
            this.formValidate = {
              images: [],
              name: '', //Tên hoạt động
              desc: '', //Mô tả hoạt động
              image: '', //Hình nền sự kiện
              factor: e.toString(), //Loại xổ số：1:điểm thưởng 2:Số dư 3: Thanh toán đơn hàng thành công 4:Đánh giá đơn hàng',5:tập trung vào
              factor_num: 1, //Lấy số điều kiện để quay số
              attends_user: 1, //Người dùng tham gia 1: Tất cả 2: Một số
              user_level: [], //Cấp độ người dùng tham gia
              user_label: [], //Thẻ người dùng tham gia
              is_svip: '-1', //Liệu người dùng tham gia có phải là thành viên trả phí hay không
              prize_num: 0, //Số lượng giải thưởng
              period: [], //Thời gian hoạt động
              prize: [], //Mảng giải thưởng
              lottery_num_term: 1, //Giới hạn số lần rút thăm: 1: mỗi ngày 2: mỗi người
              lottery_num: 1, //Số lần rút thăm
              spread_num: 1, //Theo dõi khuyến mãi để nhận số lần rút thăm
              is_all_record: 0, //Hiển thị kỷ lục chiến thắng
              is_personal_record: 0, //Hiển thị hồ sơ chiến thắng cá nhân
              is_content: 0, //Liệu thông số sự kiện có được hiển thị hay không
              content: '', //Nội dung văn bản phong phú
              status: 0, //tình trạng
            };
            this.specsData = [
              {
                type: 1, //Loại 1: Không thắng Loại 2: Điểm  3:Số dư 4: Phong bì đỏ 5:Phiếu giảm giá 6: Sản phẩm của trang web
                name: '', //Tên hoạt động
                num: 10, //Số lượng giải thưởng
                image: '', //Hình ảnh giải thưởng
                chance: 1, //Thắng cân
                total: 0, //Số lượng giải thưởng
                percent: 0, //Xác suất chiến thắng
                min_try_num: 0, //Số lần rút thăm đã thử
                prompt: '', //nhắc nhở
              },
              {
                type: 1, //Loại 1: Không thắng Loại 2: Điểm  3:Số dư 4: Phong bì đỏ 5:Phiếu giảm giá 6: Sản phẩm của trang web
                name: '', //Tên hoạt động
                num: 10, //Số lượng giải thưởng
                image: '', //Hình ảnh giải thưởng
                chance: 1, //Thắng cân
                total: 0, //Số lượng giải thưởng
                percent: 0, //Xác suất chiến thắng
                min_try_num: 0, //Số lần rút thăm đã thử
                prompt: '', //nhắc nhở
              },
              {
                type: 1, //Loại 1: Không thắng Loại 2: Điểm  3:Số dư 4: Phong bì đỏ 5:Phiếu giảm giá 6: Sản phẩm của trang web
                name: '', //Tên hoạt động
                num: 10, //Số lượng giải thưởng
                image: '', //Hình ảnh giải thưởng
                chance: 1, //Thắng cân
                total: 0, //Số lượng giải thưởng
                percent: 0, //Xác suất chiến thắng
                min_try_num: 0, //Số lần rút thăm đã thử
                prompt: '', //nhắc nhở
              },
              {
                type: 1, //Loại 1: Không thắng Loại 2: Điểm  3:Số dư 4: Phong bì đỏ 5:Phiếu giảm giá 6: Sản phẩm của trang web
                name: '', //Tên hoạt động
                num: 10, //Số lượng giải thưởng
                image: '', //Hình ảnh giải thưởng
                chance: 1, //Thắng cân
                total: 0, //Số lượng giải thưởng
                percent: 0, //Xác suất chiến thắng
                min_try_num: 0, //Số lần rút thăm đã thử
                prompt: '', //nhắc nhở
              },
              {
                type: 1, //Loại 1: Không thắng Loại 2: Điểm  3:Số dư 4: Phong bì đỏ 5:Phiếu giảm giá 6: Sản phẩm của trang web
                name: '', //Tên hoạt động
                num: 10, //Số lượng giải thưởng
                image: '', //Hình ảnh giải thưởng
                chance: 1, //Thắng cân
                total: 0, //Số lượng giải thưởng
                percent: 0, //Xác suất chiến thắng
                min_try_num: 0, //Số lần rút thăm đã thử
                prompt: '', //nhắc nhở
              },
              {
                type: 1, //Loại 1: Không thắng Loại 2: Điểm  3:Số dư 4: Phong bì đỏ 5:Phiếu giảm giá 6: Sản phẩm của trang web
                name: '', //Tên hoạt động
                num: 10, //Số lượng giải thưởng
                image: '', //Hình ảnh giải thưởng
                chance: 1, //Thắng cân
                total: 0, //Số lượng giải thưởng
                percent: 0, //Xác suất chiến thắng
                min_try_num: 0, //Số lần rút thăm đã thử
                prompt: '', //nhắc nhở
              },
              {
                type: 1, //Loại 1: Không thắng Loại 2: Điểm  3:Số dư 4: Phong bì đỏ 5:Phiếu giảm giá 6: Sản phẩm của trang web
                name: '', //Tên hoạt động
                num: 10, //Số lượng giải thưởng
                image: '', //Hình ảnh giải thưởng
                chance: 1, //Thắng cân
                total: 0, //Số lượng giải thưởng
                percent: 0, //Xác suất chiến thắng
                min_try_num: 0, //Số lần rút thăm đã thử
                prompt: '', //nhắc nhở
              },
              {
                type: 1, //Loại 1: Không thắng Loại 2: Điểm  3:Số dư 4: Phong bì đỏ 5:Phiếu giảm giá 6: Sản phẩm của trang web
                name: '', //Tên hoạt động
                num: 10, //Số lượng giải thưởng
                image: '', //Hình ảnh giải thưởng
                chance: 1, //Thắng cân
                total: 0, //Số lượng giải thưởng
                percent: 0, //Xác suất chiến thắng
                min_try_num: 0, //Số lần rút thăm đã thử
                prompt: '', //nhắc nhở
              },
            ];
          }
          this.$nextTick((e) => {
            this.spinShow = false;
          });
        })
        .catch((err) => {});
    },
    // Bước tiếp theo
    next(name) {
      this.formValidate.prize = this.specsData;
      if (this.formValidate.is_content) {
        this.formValidate.content = formatRichText(this.content);
      }
      if (this.formValidate.attends_user == 2) {
        if (this.selectDataLabel.length) {
          let activeIds = [];
          this.selectDataLabel.forEach((item) => {
            activeIds.push(item.id);
          });
          this.formValidate.user_label = activeIds;
        }
      }
      if (this.submitOpen) return false;
      this.$refs[name].validate((valid) => {
        if (valid) {
          this.submitOpen = true;
          if (this.formValidate.id && !this.copy) {
            lotteryEditApi(this.formValidate.id, this.formValidate)
              .then(async (res) => {
                this.$message.success(res.msg);
                this.submitOpen = false;
                setTimeout(() => {
                  this.$router.push({
                    path: '/admin/marketing/lottery/list',
                  });
                }, 500);
              })
              .catch((res) => {
                this.submitOpen = false;
                this.$message.error(res.msg);
              });
          } else {
            lotteryCreateApi(this.formValidate)
              .then(async (res) => {
                this.submitOpen = false;
                this.$message.success(res.msg);
                setTimeout(() => {
                  this.$router.push({
                    path: '/admin/marketing/lottery/list',
                  });
                }, 500);
              })
              .catch((res) => {
                this.submitOpen = false;
                this.$message.error(res.msg);
              });
          }
        } else {
          return false;
        }
      });
    },
    // Bước trước
    step() {
      this.current--;
    },
    // Bấm vào hình ảnh sản phẩm
    modalPicTap(tit, picTit, index) {
      this.modalPic = true;
      this.isChoice = tit === 'dan' ? 'Lựa chọn duy nhất' : 'Nhiều lựa chọn';
      this.picTit = picTit || '';
      this.tableIndex = index;
    },
    // Nhận thông tin về một hình ảnh
    getPic(pc) {
      switch (this.picTit) {
        case 'danFrom':
          this.formValidate.image = pc.att_dir;
          break;
        default:
          this.specsData[this.tableIndex].image = pc.att_dir;
      }
      this.modalPic = false;
    },
    handleRemove() {
      this.formValidate.image = '';
    },
    // xác nhận mẫu
    validate(prop, status, error) {
      if (status === false) {
        this.$message.error(error);
        return false;
      } else {
        return true;
      }
    },
    //Thêm sản phẩm mới
    addGoods() {
      this.addGoodsModel = true;
      this.title = 'Thêm sản phẩm';
      this.editData = {};
    },
    //Chỉnh sửa sản phẩm
    editGoods(index) {
      this.addGoodsModel = true;
      this.title = 'Thêm giải thưởng';
      this.editData = this.specsData[index];
      this.editIndex = index;
    },
    //Xóa sản phẩm
    deleteGoods(index) {
      this.specsData.splice(index, 1);
    },
    //Lấy tổng của một trường trong một mảng
    sumArr(arr, name) {
      let arrData = [];
      for (let i = 0; i < arr.length; i++) {
        arrData.push(arr[i][name]);
      }
      return eval(arrData.join('+'));
    },
    addGoodsData(data) {
      this.editIndex != null
        ? this.$set(this.specsData, [this.editIndex], data)
        : this.specsData.length < 8
        ? this.specsData.push(data)
        : this.$message.warning('Thêm tối đa 8 giải thưởng');
      this.getProbability();
      this.addGoodsModel = false;
      this.editIndex = null;
    },
    changeChance(e, index) {
      let value = e.target.value;
      this.$set(this.specsData[index], 'percent', value);
    },
    changeTotal(data, index) {
      this.$set(this.specsData[index], 'total', data);
    },
    //Nhận xác suất trúng được một sản phẩm
    getProbability() {
      let sum = 0;
      sum = this.sumArr(this.specsData, 'chance');
      for (let j = 0; j < this.specsData.length; j++) {
        if (sum == 0) {
          this.$set(this.specsData[j], 'probability', '0%');
        } else {
          this.$set(this.specsData[j], 'probability', ((this.specsData[j].chance / sum) * 100).toFixed(2) + '%');
        }
      }
    },
    //Sửa đổi sắp xếp
    onDragDrop(a, b) {
      this.specsData.splice(b, 1, ...this.specsData.splice(a, 1, this.specsData[b]));
    },
    setSort() {
      // refNó phải phù hợp với giới thiệu trên bảng
      const el = this.$refs.selection.$el.querySelectorAll('.el-table__body-wrapper > Table > tbody')[0];
      this.sortable = Sortable.create(el, {
        ghostClass: 'sortable-ghost',
        handle: '.handle',
        setData: function (dataTransfer) {
          dataTransfer.setData('Text', '');
        },
        // Được kích hoạt khi sự kiện kéo theo dõi kết thúc
        onEnd: (evt) => {
          this.elChangeExForArray(evt.oldIndex, evt.newIndex, this.specsData);
        },
      });
    },
    elChangeExForArray(index1, index2, array, init) {
      const arr = array;
      const temp = array[index1];
      const tempt = array[index2];
      if (init) {
        arr[index2] = tempt;
        arr[index1] = temp;
      } else {
        arr[index1] = tempt;
        arr[index2] = temp;
      }
      this.specsData = [];
      this.$nextTick((e) => {
        this.specsData = arr;
      });
    },
    //chuyển đổi định dạng thời gian
    formatDate(time) {
      if (time) {
        let date = new Date(time * 1000);
        return formatDate(date, 'yyyy-MM-dd hh:mm');
      } else {
        return '';
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.content_width {
  width: 460px;
}
::v-deep .el-tabs__item {
  height: 54px !important;
  line-height: 54px !important;
}
.custom-label {
  display: inline-flex;
  line-height: 1.5;
}
.grey {
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
    width: 58px;
    height: 58px;
    border: 1px dotted rgba(0, 0, 0, 0.1);
    margin-right: 0px;
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
      font-size: 20px;
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
}
.labelInput {
  border: 1px solid #dcdee2;
  padding: 0 15px;
  width: 460px;
  border-radius: 5px;
  min-height: 30px;
  cursor: pointer;
  .span {
    font-size: 12px;
    color: #c5c8ce;
  }
  .ivu-icon-ios-arrow-down {
    font-size: 14px;
    color: #808695;
  }
}
</style>
