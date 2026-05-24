<template>
  <div>
    <pages-header
      ref="pageHeader"
      :title="$route.meta.title"
      :backUrl="$routeProStr + '/marketing/live/live_room'"
    ></pages-header>
    <el-card :bordered="false" shadow="never" class="mt16">
      <el-form
        ref="formValidate"
        :model="formValidate"
        :label-width="labelWidth"
        :label-position="labelPosition"
        class="tabform"
        :rules="ruleValidate"
        @submit.native.prevent
      >
        <el-row :gutter="24">
          <el-col :span="24">
            <el-alert class="mb10" type="warning" show-icon :closable="false">
              <span slot="title"
                >Bạn phải truy cập phần phụ trợ chính thức của chương trình mini WeChat để kích hoạt quyền phát sóng trực tiếp và làm theo<span
                  style="color: red; cursor: pointer"
                  v-db-click
                  @click="codeImg"
                  >【Chương trình nhỏ phát sóng trực tiếp】</span
                >Những điều cần biết về trạng thái phát trực tiếp</span
              >
            </el-alert>
          </el-col>
          <el-col :span="24">
            <el-form-item label="Chọn mỏ neo：" prop="anchor_wechat">
              <el-select
                v-model="formValidate.anchor_wechat"
                filterable
                clearable
                class="content_width"
                @change="anchorName"
              >
                <el-option
                  v-for="(item, index) in liveList"
                  :value="item.wechat"
                  :key="index"
                  :label="item.wechat"
                ></el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item label="Tên phòng phát sóng trực tiếp：" prop="name">
              <el-input
                enter-button
                placeholder="Vui lòng nhập tên phòng phát sóng trực tiếp"
                element-id="name"
                v-model="formValidate.name"
                class="content_width"
                maxlength="80"
                show-word-limit
              />
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <div style="display: flex">
              <el-form-item label="Hình nền：" prop="name">
                <div v-db-click @click="modalPicTap(0)" class="box">
                  <img :src="formValidate.cover_img" alt="" v-if="formValidate.cover_img" />
                  <div class="upload-box acea-row row-center-wrapper" v-else>
                    <i class="el-icon-picture-outline" style="font-size: 24px"></i>
                  </div>
                </div>
                <div class="desc">Kích cỡ：1080*1920px</div>
              </el-form-item>
            </div>
          </el-col>
          <el-col :span="24">
            <div style="display: flex">
              <el-form-item label="Chia sẻ hình ảnh：" prop="name">
                <div v-db-click @click="modalPicTap(1)" class="box">
                  <img :src="formValidate.share_img" alt="" v-if="formValidate.share_img" />
                  <div class="upload-box acea-row row-center-wrapper" v-else>
                    <i class="el-icon-picture-outline" style="font-size: 24px"></i>
                  </div>
                </div>
                <div class="desc">Kích cỡ：800*640px</div>
              </el-form-item>
            </div>
          </el-col>
          <!--<el-col :span="24">-->
          <!--<el-form-item label="Biệt hiệu neo：">-->
          <!--<el-input enter-button  placeholder="Vui lòng nhập biệt danh của người dẫn chương trình" element-id="anchor_name" v-model="formValidate.anchor_name" style="width: 60%;"/>-->
          <!--</el-form-item>-->
          <!--</el-col>-->
          <el-col :span="24">
            <el-form-item label="Số liên lạc：">
              <el-input
                placeholder="Vui lòng nhập số liên lạc của chủ nhà"
                v-model="formValidate.phone"
                class="content_width"
                maxlength="11"
                show-word-limit
              />
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item label="Thời gian phát sóng trực tiếp：" prop="name">
              <el-date-picker
                clearable
                type="datetimerange"
                format="dd/MM/yyyy HH:mm"
                placeholder="Vui lòng chọn thời gian phát sóng trực tiếp"
                class="content_width"
                v-model="timeVal"
                @change="selectDate"
                value-format="yyyy-MM-dd HH:mm"
                range-separator="-"
                start-placeholder="ngày bắt đầu"
                end-placeholder="ngày kết thúc"
              ></el-date-picker>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item label="Loại：">
              <el-input type="number" placeholder="0" v-model="formValidate.sort" class="content_width" />
            </el-form-item>
          </el-col>
          <!-- <el-col :span="24">
            <el-form-item label="Phong cách hiển thị：">
              <el-radio-group v-model="formValidate.screen_type">
                <el-radio :label="item.label" v-for="(item, index) in screen_type" :key="index">
                  <span>{{ item.value }}</span>
                </el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col> -->
          <el-col :span="24">
            <el-form-item label="Loại phòng phát sóng trực tiếp：">
              <el-radio-group v-model="formValidate.type">
                <el-radio :label="item.label" v-for="(item, index) in type" :key="index">
                  <span>{{ item.value }}</span>
                </el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item label="Giống như phòng phát sóng trực tiếp：">
              <el-switch
                class="defineSwitch"
                :active-value="1"
                :inactive-value="0"
                v-model="formValidate.close_like"
                size="large"
                active-text="Hoạt động"
                inactive-text="Ngưng hoạt động"
              >
              </el-switch>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item label="Bán hàng trực tiếp：">
              <el-switch
                class="defineSwitch"
                :active-value="1"
                :inactive-value="0"
                v-model="formValidate.close_goods"
                size="large"
                active-text="Hoạt động"
                inactive-text="Ngưng hoạt động"
              >
              </el-switch>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item label="Nhận xét phòng trực tiếp：">
              <el-switch
                class="defineSwitch"
                :active-value="1"
                :inactive-value="0"
                v-model="formValidate.close_comment"
                size="large"
                active-text="Hoạt động"
                inactive-text="Ngưng hoạt động"
              >
              </el-switch>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="24">
          <el-col v-bind="grid" :span="24">
            <el-button
              :loading="loading"
              type="primary"
              style="margin-left: 120px"
              v-db-click
              @click="handleSubmit('formItem')"
            >Nộp</el-button>
            <!-- <el-button
              type="primary"
              v-db-click @click="handleSubmit('formItem')"
              style="width: 19%; margin-left: 99px"
              >Nộp</el-button
            > -->
          </el-col>
        </el-row>
      </el-form>
    </el-card>
    <div>
      <el-dialog :visible.sync="modalPic" width="950px" title="Tải lên hình ảnh sản phẩm" :close-on-click-modal="false" :z-index="888">
        <uploadPictures
          :isChoice="isChoice"
          @getPic="getPic"
          :gridBtn="gridBtn"
          :gridPic="gridPic"
          v-if="modalPic"
        ></uploadPictures>
      </el-dialog>
    </div>
    <el-dialog :visible.sync="modal3" title="Mã QR">
      <div class="acea-row row-around">
        <div v-viewer class="QRpic">
          <img src="https://res.wx.qq.com/op_res/9rSix1dhHfK4rR049JL0PHJ7TpOvkuZ3mE0z7Ou_Etvjf-w1J_jVX0rZqeStLfwh" />
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import uploadPictures from '@/components/uploadPictures';
import { liveAdd, liveAuchorList } from '@/api/live';
export default {
  name: 'creat_live',
  components: {
    uploadPictures,
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '120px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  data() {
    return {
      gridBtn: {
        xl: 4,
        lg: 8,
        md: 8,
        sm: 8,
        xs: 8,
      },
      gridPic: {
        xl: 6,
        lg: 8,
        md: 12,
        sm: 12,
        xs: 12,
      },
      grid: {
        xl: 10,
        lg: 16,
        md: 18,
        sm: 24,
        xs: 24,
      },
      loading: false,
      formValidate: {
        name: '',
        anchor_name: '',
        anchor_wechat: '',
        phone: '',
        screen_type: 0,
        close_like: 1,
        close_goods: 1,
        close_comment: 1,
        cover_img: '',
        share_img: '',
        sort: 0,
        type: 0,
        start_time: '',
      },
      screen_type: [
        {
          value: 'Màn hình dọc',
          label: 0,
        },
        {
          value: 'Màn hình ngang',
          label: 1,
        },
      ],
      type: [
        // {
        //     value:'Đẩy phát trực tuyến',
        //     label:1
        // },
        {
          value: 'Truyền hình trực tiếp trên thiết bị di động',
          label: 0,
        },
      ],
      close_like: [
        {
          value: 'Hoạt động',
          label: 1,
        },
        {
          value: 'Ngưng hoạt động',
          label: 0,
        },
      ],
      close_goods: [
        {
          value: 'Hoạt động',
          label: 1,
        },
        {
          value: 'Ngưng hoạt động',
          label: 0,
        },
      ],
      close_comment: [
        {
          value: 'Hoạt động',
          label: 1,
        },
        {
          value: 'Ngưng hoạt động',
          label: 0,
        },
      ],
      timeVal: '',
      modalPic: false,
      isChoice: 'Lựa chọn duy nhất',
      activeIndex: 0,
      liveList: [],
      modal3: false,
      ruleValidate: {
        anchor_wechat: [{ required: true, message: 'Please select the city', trigger: 'change' }],
        name: [{ required: true, message: 'The name cannot be empty', trigger: 'blur' }],
      },
    };
  },
  mounted() {
    this.getLive();
  },
  methods: {
    cancel() {
      this.modal3 = false;
    },
    codeImg() {
      this.modal3 = true;
    },
    anchorName(e) {
      this.liveList.filter((el, index) => {
        if (el.wechat === e) {
          this.formValidate.anchor_name = el.name;
        }
      });
    },
    //Danh sách neo；
    getLive() {
      let formValidate = {
        kerword: '',
        page: '',
        limit: '',
      };
      liveAuchorList(formValidate)
        .then((res) => {
          this.liveList = res.data.list;
        })
        .catch((error) => {
          this.$message.error(error.msg);
        });
    },
    // Bấm vào ảnh và bìa văn bản
    modalPicTap(type) {
      this.activeIndex = type;
      this.modalPic = true;
    },
    // Chọn ngày
    selectDate(e) {
      this.formValidate.start_time = e;
    },
    // Lấy thông tin hình ảnh
    getPic(pc) {
      this.$nextTick(() => {
        if (this.activeIndex == 0) {
          this.formValidate.cover_img = pc.att_dir;
        } else {
          this.formValidate.share_img = pc.att_dir;
        }
        this.modalPic = false;
      });
    },
    // Lưu
    handleSubmit(name) {
      this.loading = true;
      liveAdd(this.formValidate)
        .then((res) => {
          this.$message.success('Đã thêm thành công');
          setTimeout(() => {
            this.loading = false;
            this.$router.push({ path: this.$routeProStr + '/marketing/live/live_room' });
          }, 500);
        })
        .catch((error) => {
          setTimeout(() => {
            this.loading = false;
          }, 1000);
          this.$message.error(error.msg);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.content_width {
  width: 460px;
}
.QRpic {
  width: 180px;
  height: 180px;

  img {
    width: 100%;
    height: 100%;
  }
}
.desc {
  font-size: 12px;
  color: #999;
}
.upload-box {
  width: 58px;
  height: 58px;
  line-height: 58px;
  border: 1px dotted rgba(0, 0, 0, 0.1);
  border-radius: 4px;
  background: rgba(0, 0, 0, 0.02);
  cursor: pointer;
}
.box {
  width: 60px;
  height: 60px;
  border-radius: 4px;
  cursor: pointer;

  img {
    width: 100%;
    height: 100%;
  }
}
</style>
