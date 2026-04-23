<template>
  <div>
    <el-card :bordered="false" shadow="never">
      <el-tabs v-model="isChecked" @tab-click="onChangeType">
        <el-tab-pane label="Tin nhắn ngắn" name="1"></el-tab-pane>
        <el-tab-pane label="Bộ sưu tập sản phẩm" name="4"></el-tab-pane>
        <el-tab-pane label="Điều tra hậu cần" name="3"></el-tab-pane>
        <el-tab-pane label="In biểu mẫu điện tử" name="2"></el-tab-pane>
      </el-tabs>
      <!--danh sách tin nhắn SMS-->
      <div class="note" v-if="isChecked === '1' && sms.open === 1">
        <div class="acea-row row-between-wrapper">
          <div>
            <span>trạng thái tin nhắn：</span>
            <el-radio-group type="button" v-model="tableFrom.type" @input="selectChange(tableFrom.type)">
              <el-radio-button label="">tất cả</el-radio-button>
              <el-radio-button label="1">thành công</el-radio-button>
              <el-radio-button label="2">thất bại</el-radio-button>
              <el-radio-button label="0">Đang gửi</el-radio-button>
            </el-radio-group>
          </div>
          <div>
            <el-button type="primary" v-db-click @click="shortMes">mẫu tin nhắn</el-button>
            <el-button style="margin-left: 20px" v-db-click @click="editSign">Sửa đổi chữ ký</el-button>
          </div>
        </div>
        <el-table
          :data="tableList"
          v-loading="loading"
          highlight-current-row
          no-userFrom-text="Chưa có dữ liệu"
          no-filtered-userFrom-text="Chưa có kết quả lọc nào"
          class="mt14"
        >
          <el-table-column label="Số điện thoại" width="100">
            <template slot-scope="scope">
              <span>{{ scope.row.phone }}</span>
            </template>
          </el-table-column>
          <el-table-column label="Nội dung mẫu" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.content }}</span>
            </template>
          </el-table-column>
          <el-table-column label="Số lượng mặt hàng(Mọi67/+1)" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.num }}</span>
            </template>
          </el-table-column>
          <el-table-column label="Gửi thời gian" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.add_time }}</span>
            </template>
          </el-table-column>
          <el-table-column label="mã trạng thái" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row._resultcode }}</span>
            </template>
          </el-table-column>
        </el-table>
        <div class="acea-row row-right page">
          <pagination
            v-if="total"
            :total="total"
            :page.sync="tableFrom.page"
            :limit.sync="tableFrom.limit"
            @pagination="getList"
          />
        </div>
      </div>
      <!--Thu thập sản phẩm, hậu cần, danh sách đơn hàng điện tử-->
      <div
        v-else-if="
          (isChecked === '3' && query.open === 1) ||
          (isChecked === '4' && copy.open === 1) ||
          (isChecked === '2' && dump.open === 1)
        "
      >
        <el-table
          :data="tableList"
          v-loading="loading"
          highlight-current-row
          no-userFrom-text="Chưa có dữ liệu"
          no-filtered-userFrom-text="Chưa có kết quả lọc nào"
          class="mt14"
        >
          <el-table-column
            :label="item.title"
            :min-width="item.minWidth"
            v-for="(item, index) in columns2"
            :key="index"
          >
            <template slot-scope="scope">
              <template v-if="item.key">
                <div>
                  <span>{{ scope.row[item.key] }}</span>
                </div>
              </template>
              <template v-else-if="item.slot === 'num' && isChecked === '3' && query.open === 1">
                <div>{{ scope.row.content.num }}</div>
              </template>
            </template>
          </el-table-column>
        </el-table>
        <div class="acea-row row-right page">
          <pagination
            v-if="total"
            :total="total"
            :page.sync="tableFrom.page"
            :limit.sync="tableFrom.limit"
            @pagination="getRecordList"
          />
        </div>
      </div>
      <!--Không kích hoạt-->
      <div v-else>
        <!--Nút kích hoạt-->
        <div
          v-if="
            (isChecked === '1' && !isSms) ||
            (isChecked === '2' && !isDump) ||
            (isChecked === '3' && !isLogistics) ||
            (isChecked === '4' && !isCopy)
          "
          class="wuBox acea-row row-column-around row-middle"
        >
          <div class="wuTu"><img src="../../../assets/images/wutu.png" /></div>
          <span v-if="isChecked === '1'">
            <span class="wuSp1">Dịch vụ SMS chưa được kích hoạt</span>
            <span class="wuSp2">Nhấn nút Kích hoạt ngay để sử dụng dịch vụ SMS～～～</span>
          </span>
          <span v-if="isChecked === '4'">
            <span class="wuSp1">Dịch vụ thu thập sản phẩm chưa được kích hoạt.</span>
            <span class="wuSp2">Nhấn nút Kích hoạt ngay để sử dụng dịch vụ nhận sản phẩm～～～</span>
          </span>
          <span v-if="isChecked === '3'">
            <span class="wuSp1">Truy vấn hậu cần không được kích hoạt</span>
            <span class="wuSp2">Bấm vào nút Kích hoạt ngay để sử dụng dịch vụ tra cứu hậu cần～～～</span>
          </span>
          <span v-if="isChecked === '2'">
            <span class="wuSp1">In biểu mẫu điện tử không được kích hoạt.</span>
            <span class="wuSp2">Bấm vào nút Kích hoạt ngay để sử dụng dịch vụ in biểu mẫu điện tử.～～～</span>
          </span>
          <el-button size="default" type="primary" v-db-click @click="onOpen">Kích hoạt ngay bây giờ</el-button>
        </div>
        <!--Kích hoạt SMS ngay bây giờ-->
        <div class="smsBox" v-if="isSms && isChecked === '1'">
          <div class="index_from page-account-container">
            <div class="page-account-top">
              <span class="page-account-top-tit">Kích hoạt dịch vụ SMS</span>
            </div>
            <el-form
              ref="formInline"
              :model="formInline"
              :rules="ruleInline"
              @submit.native.prevent
              @keyup.enter="handleSubmit('formInline')"
            >
              <el-form-item prop="sign" class="maxInpt">
                <el-input
                  type="text"
                  v-model="formInline.sign"
                  prefix="ios-contact-outline"
                  placeholder="Vui lòng nhập chữ ký SMS"
                />
              </el-form-item>
              <el-form-item class="maxInpt">
                <el-button type="primary" long size="default" v-db-click @click="handleSubmit('formInline')" class="btn"
                  >Đăng nhập</el-button
                >
              </el-form-item>
            </el-form>
          </div>
        </div>
        <!--Hiện đã có hóa đơn điện tử-->
        <div class="smsBox" v-if="isDump && isChecked === '2'">
          <div class="index_from page-account-container">
            <div class="page-account-top">
              <span class="page-account-top-tit" v-if="isChecked === '2'">Kích hoạt dịch vụ hóa đơn điện tử</span>
              <span class="page-account-top-tit" v-if="isChecked === '3'">Dịch vụ điều tra hậu cần mở</span>
            </div>
            <el-form
              ref="formInlineDump"
              :model="formInlineDump"
              :rules="ruleInlineDump"
              @submit.native.prevent
              @keyup.enter="handleSubmitDump('formInlineDump')"
            >
              <el-form-item prop="com" class="maxInpt">
                <el-select
                  v-model="formInlineDump.com"
                  placeholder="Hãy chọn công ty chuyển phát nhanh"
                  @change="onChangeExport"
                  style="text-align: left"
                >
                  <el-option
                    v-for="(item, index) in exportList"
                    :value="item.code"
                    :key="index"
                    :label="item.name"
                  ></el-option>
                </el-select>
              </el-form-item>
              <el-form-item prop="temp_id" class="tempId maxInpt">
                <div class="acea-row">
                  <el-select
                    v-model="formInlineDump.temp_id"
                    placeholder="Vui lòng chọn mẫu biểu mẫu điện tử"
                    style="text-align: left"
                    :class="[formInlineDump.temp_id ? 'width9' : 'width10']"
                    @change="onChangeImg"
                  >
                    <el-option
                      v-for="(item, index) in exportTempList"
                      :value="item.temp_id"
                      :key="index"
                      :label="item.title"
                    ></el-option>
                  </el-select>
                  <div v-if="formInlineDump.temp_id">
                    <span class="tempImg">Xem trước</span>
                    <div class="tabBox_img" v-viewer>
                      <img v-lazy="tempImg" />
                    </div>
                  </div>
                </div>
              </el-form-item>
              <el-form-item prop="to_name" class="maxInpt">
                <el-input
                  type="text"
                  v-model="formInlineDump.to_name"
                  prefix="ios-contact-outline"
                  placeholder="Vui lòng điền tên người gửi"
                />
              </el-form-item>
              <el-form-item prop="to_tel" class="maxInpt">
                <el-input
                  type="text"
                  v-model="formInlineDump.to_tel"
                  prefix="ios-contact-outline"
                  placeholder="Vui lòng điền số điện thoại người gửi"
                />
              </el-form-item>
              <el-form-item prop="to_address" class="maxInpt">
                <el-input
                  type="text"
                  v-model="formInlineDump.to_address"
                  prefix="ios-contact-outline"
                  placeholder="Vui lòng điền địa chỉ chi tiết của người gửi"
                />
              </el-form-item>
              <el-form-item prop="siid" class="maxInpt">
                <el-input
                  type="text"
                  v-model="formInlineDump.siid"
                  prefix="ios-contact-outline"
                  placeholder="Vui lòng điền số in trên đám mây"
                />
              </el-form-item>
              <el-form-item class="maxInpt">
                <el-button
                  type="primary"
                  long
                  size="default"
                  v-db-click
                  @click="handleSubmitDump('formInlineDump')"
                  class="btn"
                  >Kích hoạt ngay bây giờ</el-button
                >
              </el-form-item>
            </el-form>
          </div>
        </div>
      </div>
    </el-card>
    <el-dialog
      :visible.sync="modals"
      title="Sửa đổi chữ ký tài khoản SMS"
      width="540px"
      class="order_box"
      @closed="cancel('formInline')"
    >
      <el-form ref="formInline" :model="formInline" :rules="ruleInline" label-width="100px" @submit.native.prevent>
        <el-form-item>
          <el-input
            v-model="accountInfo.account"
            disabled
            prefix="ios-person-outline"
            size="large"
            style="width: 87%"
          ></el-input>
        </el-form-item>
        <el-form-item prop="sign">
          <el-input
            v-model="formInline.sign"
            prefix="ios-document-outline"
            placeholder="Vui lòng nhập chữ ký SMS của bạn, ví dụ：CRMEB"
            size="large"
            style="width: 87%"
          ></el-input>
        </el-form-item>
        <el-form-item prop="phone">
          <el-input
            v-model="formInline.phone"
            prefix="ios-call-outline"
            placeholder="Vui lòng nhập số điện thoại di động của bạn"
            size="large"
            style="width: 87%"
          ></el-input>
        </el-form-item>
        <el-form-item prop="code">
          <div class="code acea-row row-middle" style="width: 87%">
            <el-input
              type="text"
              v-model="formInline.code"
              prefix="ios-keypad-outline"
              placeholder="Mã xác minh"
              size="large"
              style="width: 75%"
            />
            <el-button :disabled="!this.canClick" v-db-click @click="cutDown" size="large">{{ cutNUm }}</el-button>
          </div>
        </el-form-item>
        <el-form-item>
          <el-button
            type="primary"
            long
            size="large"
            v-db-click
            @click="editSubmit('formInline')"
            class="btn"
            style="width: 87%"
            >Xác nhận thay đổi</el-button
          >
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import {
  smsRecordApi,
  serveInfoApi,
  serveSmsOpenApi,
  serveOpnExpressApi,
  serveOpnOtherApi,
  serveRecordListApi,
  exportTempApi,
  exportAllApi,
  serveSign,
  captchaApi,
  serveOpen,
} from '@/api/setting';
export default {
  name: 'tableList',
  props: {
    copy: {
      type: Object,
      default: null,
    },
    dump: {
      type: Object,
      default: null,
    },
    query: {
      type: Object,
      default: null,
    },
    sms: {
      type: Object,
      default: null,
    },
    accountInfo: {
      type: Object,
      default: null,
    },
  },
  data() {
    const validatePhone = (rule, value, callback) => {
      if (!value) {
        return callback(new Error('Vui lòng điền số điện thoại di động của bạn'));
      } else if (!/^1[3456789]\d{9}$/.test(value)) {
        callback(new Error('Định dạng số điện thoại di động không chính xác!'));
      } else {
        callback();
      }
    };
    return {
      cutNUm: 'Nhận mã xác minh',
      canClick: true,
      spinShow: true,
      formInline: {
        sign: '',
        phone: '',
        code: '',
      },
      ruleInline: {
        sign: [{ required: true, message: 'Vui lòng nhập chữ ký SMS', trigger: 'blur' }],
        phone: [{ required: true, validator: validatePhone, trigger: 'blur' }],
        code: [{ required: true, message: 'Vui lòng nhập mã xác minh', trigger: 'blur' }],
      },
      isChecked: '1',
      columns2: [],
      tableFrom: {
        page: 1,
        limit: 20,
        type: '',
      },
      total: 0,
      loading: false,
      tableList: [],
      formInlineDump: {
        temp_id: '',
        com: '',
        to_name: '',
        to_tel: '',
        siid: '',
        to_address: '',
      },
      ruleInlineDump: {
        com: [{ required: true, message: 'Hãy chọn công ty chuyển phát nhanh', trigger: 'change' }],
        temp_id: [{ required: true, message: 'Vui lòng chọn mẫu in', trigger: 'change' }],
        to_name: [{ required: true, message: 'Vui lòng nhập tên người gửi', trigger: 'blur' }],
        to_tel: [{ required: true, validator: validatePhone, trigger: 'blur' }],
        siid: [{ required: true, message: 'Vui lòng nhập số máy in trên đám mây', trigger: 'blur' }],
        to_address: [{ required: true, message: 'Vui lòng nhập địa chỉ người gửi', trigger: 'blur' }],
      },
      tempImg: '', // hình ảnh
      exportTempList: [], // Mẫu biểu mẫu điện tử
      exportList: [], // Danh sách công ty chuyển phát nhanh
      isSms: false, // Có bật SMS hay không
      isDump: false, // Có kích hoạt biểu mẫu điện tử hay không
      isCopy: false, // Có bật bộ sưu tập sản phẩm hay không
      modals: false,
      isLogistics: false, //Có bật truy vấn hậu cần hay không
    };
  },
  watch: {
    sms(n) {
      if (n.open === 1) this.getList();
    },
  },
  created() {
    if (this.isChecked === '1' && this.sms.open === 1) this.getList();
  },
  // mounted() {
  //     serveDumpOpen().then(res=>{
  //         this.isLogistics = res.data.isOpen
  //     })
  // },
  methods: {
    //Trang mẫu SMS
    shortMes() {
      this.$router.push({
        path: this.$routeProStr + '/setting/sms/sms_template_apply/index',
      });
    },
    // Mã xác minh SMS
    cutDown() {
      if (this.formInline.phone) {
        if (!this.canClick) return;
        this.canClick = false;
        this.cutNUm = 60;
        let data = {
          phone: this.formInline.phone,
        };
        captchaApi(data)
          .then(async (res) => {
            this.$message.success(res.msg);
          })
          .catch((res) => {
            this.$message.error(res.msg);
          });
        let time = setInterval(() => {
          this.cutNUm--;
          if (this.cutNUm === 0) {
            this.cutNUm = 'Nhận mã xác minh';
            this.canClick = true;
            clearInterval(time);
          }
        }, 1000);
      } else {
        this.$message.warning('Vui lòng điền số điện thoại di động của bạn!');
      }
    },
    editSign() {
      this.formInline.sign = this.accountInfo.sms.sign;
      this.modals = true;
    },
    cancel(name) {
      this.modals = false;
      this.$refs[name].resetFields();
    },
    // nộp
    editSubmit(name) {
      this.$refs[name].validate((valid) => {
        if (valid) {
          serveSign(this.formInline)
            .then((res) => {
              this.modals = false;
              this.$message.success(res.msg);
              this.$refs[name].resetFields();
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
        }
      });
    },
    onChangeImg(item) {
      this.exportTempList.map((i) => {
        if (i.temp_id === item) this.tempImg = i.pic;
      });
    },
    // Công ty hậu cần
    exportTempAllList() {
      exportAllApi()
        .then(async (res) => {
          this.exportList = res.data;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // Lựa chọn công ty chuyển phát nhanh
    onChangeExport(val) {
      this.formInlineDump.temp_id = '';
      this.exportTemp(val);
    },
    // Mẫu biểu mẫu điện tử
    exportTemp(val) {
      exportTempApi({ com: val })
        .then(async (res) => {
          this.exportTempList = res.data.data;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    onChangeType() {
      if (this.isChecked === '1' && this.sms.open === 1) {
        this.tableFrom.type = '';
        this.getList();
      } else {
        // if ((this.isChecked === '2' && this.query.open === 0) || (this.dump.open === 0 && this.isChecked === '3')) this.isDump = false
        if (this.isChecked === '2' && this.query.open === 0) this.isDump = false;
        if (this.isChecked === '3' && this.query.open === 0) this.isLogistics = false;
        if (this.dump.open === 1 || this.query.open === 1 || this.copy.open === 1) this.getRecordList();
      }
    },
    // Danh sách khác
    getRecordList() {
      this.loading = true;
      this.tableFrom.type = this.isChecked;
      serveRecordListApi(this.tableFrom)
        .then(async (res) => {
          let data = res.data;
          this.tableList = data.data;
          this.total = res.data.count;
          switch (this.isChecked) {
            case '2':
              this.columns2 = [
                {
                  title: 'Số đơn hàng',
                  key: 'order_id',
                  minWidth: 150,
                },
                {
                  title: 'người gửi hàng',
                  key: 'from_name',
                  minWidth: 120,
                },
                {
                  title: 'người nhận hàng',
                  key: 'to_name',
                  minWidth: 120,
                },
                {
                  title: 'Số theo dõi nhanh',
                  key: 'num',
                  minWidth: 120,
                },
                {
                  title: 'Mã công ty chuyển phát nhanh',
                  key: 'code',
                  minWidth: 120,
                },
                {
                  title: 'tình trạng',
                  key: '_resultcode',
                  minWidth: 100,
                },
                {
                  title: 'Thời gian in',
                  key: 'add_time',
                  minWidth: 150,
                },
              ];
              break;
            case '3':
              this.columns2 = [
                {
                  title: 'Số theo dõi nhanh',
                  slot: 'num',
                  minWidth: 120,
                },
                {
                  title: 'Mã công ty chuyển phát nhanh',
                  key: 'code',
                  minWidth: 120,
                },
                {
                  title: 'tình trạng',
                  key: '_resultcode',
                  minWidth: 120,
                },
                {
                  title: 'Thêm thời gian',
                  key: 'add_time',
                  minWidth: 150,
                },
              ];
              break;
            default:
              this.columns2 = [
                {
                  title: 'sao chépURL',
                  key: 'url',
                  minWidth: 400,
                },
                {
                  title: 'Trạng thái yêu cầu',
                  key: '_resultcode',
                  minWidth: 120,
                },
                {
                  title: 'Thêm thời gian',
                  key: 'add_time',
                  minWidth: 150,
                },
              ];
              break;
          }
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // Bật gửi SMS
    handleSubmit(name) {
      this.$refs[name].validate((valid) => {
        if (valid) {
          serveSmsOpenApi(this.formInline)
            .then(async (res) => {
              this.$message.success('Kích hoạt thành công!');
              this.getList();
              this.$emit('openService', 'sms');
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
        } else {
          return false;
        }
      });
    },
    // Vào trang chủ để kích hoạt
    onOpenIndex(val) {
      switch (val) {
        case 'sms':
          this.isChecked = '1';
          this.isSms = true;
          break;
        case 'copy':
          this.isChecked = '4';
          this.openOther();
          break;
        case 'query':
          this.isChecked = '3';
          this.onDumpOpen();
          break;
        default:
          this.isChecked = '2';
          this.openDump();
          break;
      }
    },
    // Nút kích hoạt
    onOpen() {
      if (this.isChecked === '1') this.isSms = true;
      if (this.isChecked === '2') this.openDump();
      if (this.isChecked === '3') this.onDumpOpen();
      if (this.isChecked === '4') this.openOther();
    },
    // Hậu cần mở
    onDumpOpen() {
      this.$msgbox({
        title: 'Mở cuộc điều tra hậu cần?',
        message: 'Bạn có chắc chắn muốn kích hoạt yêu cầu hậu cần không?？',
        showCancelButton: true,
        cancelButtonText: 'Hủy bỏ',
        confirmButtonText: 'Chắc chắn',
        iconClass: 'el-icon-warning',
        confirmButtonClass: 'btn-custom-cancel',
      })
        .then(() => {
          serveOpen().then((res) => {
            this.getRecordList();
            this.isLogistics = true;
            this.$message.info(res.msg);
            this.$emit('openService', 'query');
          });
        })
        .catch(() => {});
    },
    // Mở cái khác
    openOther() {
      this.$msgbox({
        title: 'Bộ sưu tập sản phẩm có được bật không?',
        message: 'Bạn có chắc chắn muốn bật bộ sưu tập sản phẩm không?？',
        showCancelButton: true,
        cancelButtonText: 'Hủy bỏ',
        confirmButtonText: 'Chắc chắn',
        iconClass: 'el-icon-warning',
        confirmButtonClass: 'btn-custom-cancel',
      })
        .then(() => {
          setTimeout(() => {
            serveOpnOtherApi({ type: 1 })
              .then(async (res) => {
                this.getRecordList();
                this.$emit('openService', 'copy');
              })
              .catch((res) => {
                this.$message.error(res.msg);
              });
          }, 300);
        })
        .catch(() => {});
    },
    // Mở biểu mẫu điện tử
    openDump() {
      this.exportTempAllList();
      this.isDump = true;
    },
    // chọn
    selectChange(tab) {
      this.tableFrom.type = tab;
      this.tableFrom.page = 1;
      this.getList();
    },
    // danh sách
    getList() {
      this.loading = true;
      smsRecordApi(this.tableFrom)
        .then(async (res) => {
          let data = res.data;
          this.tableList = data.data;
          this.total = res.data.count;
          this.spinShow = false;
          this.loading = false;
        })
        .catch((res) => {
          this.spinShow = false;
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // tìm kiếm bảng
    userSearchs() {
      this.getList();
    },
    handleSubmitDump(name) {
      this.$refs[name].validate((valid) => {
        if (valid) {
          serveOpnExpressApi(this.formInlineDump)
            .then(async (res) => {
              this.$message.success('Kích hoạt thành công!');
              this.getRecordList();
              this.$emit('openService', 'dump');
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
        } else {
          return false;
        }
      });
    },
  },
};
</script>
<style lang="scss" scoped>
.order_box ::v-deep .ivu-form-item-content {
  margin-left: 50px !important;
}
.maxInpt {
  max-width: 400px;
  margin-left: auto;
  margin-right: auto;
}
.smsBox .page-account-top {
  text-align: center;
  margin: 70px 0 30px 0;
}
.note {
  margin-top: 15px;
}
.tempImg {
  cursor: pointer;
  margin-left: 11px;
  color: var(--prev-color-primary);
}
.tabBox_img {
  opacity: 0;
  width: 38px;
  height: 30px;
  margin-top: -30px;
  cursor: pointer;
  img {
    width: 100%;
    height: 100%;
  }
}
.width9 {
  width: 90%;
}
.width10 {
  width: 100%;
}
.wuBox {
  width: 100%;
}
.wuSp1 {
  display: block;
  text-align: center;
  color: #000000;
  font-size: 21px;
  font-weight: 500;
  line-height: 32px;
  margin-top: 23px;
  margin-bottom: 5px;
}
.wuSp2 {
  opacity: 45%;
  font-weight: 400;
  color: #000000;
  line-height: 22px;
  margin-bottom: 30px;
}
.page-account-top-tit {
  font-size: 21px;
  color: var(--prev-color-primary);
}
.wuTu {
  width: 295px;
  height: 164px;
  margin-top: 54px;
  img {
    width: 100%;
    height: 100%;
  }

  + span {
    margin-bottom: 20px;
  }
}
.tempId {
  cursor: pointer;
  margin-left: 11px;
  color: var(--prev-color-primary);
  ::v-deep .ivu-form-item-content {
    text-align: left !important;
  }
}
.tabBox_img {
  opacity: 0;
  width: 38px;
  height: 30px;
  margin-top: -30px;
  cursor: pointer;
  img {
    width: 100%;
    height: 100%;
  }
}
.width9 {
  width: 90%;
}
.width10 {
  width: 100%;
}
.wuBox {
  width: 100%;
}
.wuSp1 {
  display: block;
  text-align: center;
  color: #000000;
  font-size: 21px;
  font-weight: 500;
  line-height: 32px;
  margin-top: 23px;
  margin-bottom: 5px;
}
.wuSp2 {
  opacity: 45%;
  font-weight: 400;
  color: #000000;
  line-height: 22px;
  margin-bottom: 30px;
}
.page-account-top-tit {
  font-size: 21px;
  color: var(--prev-color-primary);
}
.wuTu {
  width: 295px;
  height: 164px;
  margin-top: 54px;
  img {
    width: 100%;
    height: 100%;
  }

  + span {
    margin-bottom: 20px;
  }
}
.tempId {
  cursor: pointer;
  margin-left: 11px;
  color: var(--prev-color-primary);
  ::v-deep .ivu-form-item-content {
    text-align: left !important;
  }
}
.tabBox_img {
  opacity: 0;
  width: 38px;
  height: 30px;
  margin-top: -30px;
  cursor: pointer;
  img {
    width: 100%;
    height: 100%;
  }
}
.width9 {
  width: 90%;
}
.width10 {
  width: 100%;
}
.wuBox {
  width: 100%;
}
.wuSp1 {
  display: block;
  text-align: center;
  color: #000000;
  font-size: 21px;
  font-weight: 500;
  line-height: 32px;
  margin-top: 23px;
  margin-bottom: 5px;
}
.wuSp2 {
  opacity: 45%;
  font-weight: 400;
  color: #000000;
  line-height: 22px;
  margin-bottom: 30px;
}
.page-account-top-tit {
  font-size: 21px;
  color: var(--prev-color-primary);
}
.wuTu {
  width: 295px;
  height: 164px;
  margin-top: 54px;
  img {
    width: 100%;
    height: 100%;
  }

  + span {
    margin-bottom: 20px;
  }
}
.tempId {
  ::v-deep .ivu-form-item-content {
    text-align: left !important;
  }
}
</style>
