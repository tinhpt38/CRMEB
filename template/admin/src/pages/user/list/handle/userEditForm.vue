<template>
  <div class="user-info">
    <el-form ref="formItem" :rules="ruleValidate" :model="formItem" label-width="100px" @submit.native.prevent>
      <div class="section">
        <div class="section-hd">Thông tin cơ bản</div>
        <div class="section-bd">
          <div class="item">
            <el-form-item label="ID khách hàng：">
              <el-input class="form-sty" disabled v-model="formItem.uid" placeholder="Vui lòng nhập số"></el-input>
            </el-form-item>
          </div>
          <div class="item">
            <el-form-item label="Họ và tên：" prop="real_name">
              <el-input class="form-sty" v-model="formItem.real_name" placeholder="Nhập họ và tên"></el-input>
            </el-form-item>
          </div>
          <div class="item">
            <el-form-item label="Số điện thoại：" prop="phone">
              <el-input class="form-sty" v-model.trim="formItem.phone" placeholder="Nhập số điện thoại"></el-input>
            </el-form-item>
          </div>
          <div class="item">
            <el-form-item label="Sinh nhật：">
              <el-date-picker
                clearable
                class="form-sty"
                type="date"
                v-model="formItem.birthday"
                placeholder="Vui lòng chọn ngày sinh"
                format="dd/MM/yyyy"
                value-format="yyyy-MM-dd"
              ></el-date-picker>
            </el-form-item>
          </div>
          <div class="item">
            <el-form-item label="CMND/CCCD：" prop="card_id">
              <el-input class="form-sty" v-model.trim="formItem.card_id" placeholder="Nhập 9 hoặc 12 chữ số"></el-input>
            </el-form-item>
          </div>
          <div class="item">
            <el-form-item label="Địa chỉ：" prop="addres">
              <el-input class="form-sty" v-model="formItem.addres" placeholder="Nhập địa chỉ"></el-input>
            </el-form-item>
          </div>
        </div>
      </div>
      <div class="section">
        <div class="section-hd">Mật khẩu</div>
        <div class="section-bd">
          <div class="item">
            <el-form-item label="Mật khẩu đăng nhập：" prop="pwd">
              <el-input
                class="form-sty"
                type="password"
                v-model="formItem.pwd"
                placeholder="Nhập mật khẩu (để trống nếu không muốn thay đổi)"
              ></el-input>
            </el-form-item>
          </div>
          <div class="item">
            <el-form-item label="Xác nhận mật khẩu：" prop="true_pwd">
              <el-input
                class="form-sty"
                type="password"
                v-model="formItem.true_pwd"
                placeholder="Nhập lại mật khẩu để xác nhận"
              ></el-input>
            </el-form-item>
          </div>
        </div>
      </div>
      <div class="section">
        <div class="section-hd">Hồ sơ người dùng</div>
        <div class="section-bd">
          <div class="item">
            <el-form-item label="Hạng khách hàng：">
              <el-select v-model="formItem.level" class="form-sty" clearable>
                <el-option
                  v-for="(item, index) in infoData.levelInfo"
                  :key="index"
                  :value="item.id"
                  :label="item.name"
                ></el-option>
              </el-select>
            </el-form-item>
          </div>
          <div class="item">
            <el-form-item label="Nhóm khách hàng：">
              <el-select v-model="formItem.group_id" class="form-sty" clearable>
                <el-option
                  v-for="(item, index) in infoData.groupInfo"
                  :key="index"
                  :value="item.id"
                  :label="item.group_name"
                ></el-option>
              </el-select>
            </el-form-item>
          </div>
          <div class="item lang">
            <el-form-item label="Thẻ khách hàng：">
              <div style="display: flex">
                <div class="labelInput acea-row row-between-wrapper" v-db-click @click="openLabel">
                  <div style="width: 90%">
                    <div v-if="dataLabel.length">
                      <el-tag
                        closable
                        v-for="(item, index) in dataLabel"
                        :key="index"
                        @close="closeLabel(item)"
                        class="mr10"
                        >{{ item.label_name }}</el-tag
                      >
                    </div>
                    <span class="span" v-else>Chọn nhãn liên kết người dùng</span>
                  </div>
                  <div class="ivu-icon ivu-icon-ios-arrow-down"></div>
                </div>
                <span class="addfont" v-db-click @click="addLabel">Thêm thẻ mới</span>
              </div>
            </el-form-item>
          </div>
          <div class="item lang">
            <el-form-item label="Phân phối bị vô hiệu hóa：">
              <el-radio-group v-model="formItem.spread_open" class="form-sty">
                <el-radio :label="0">Đúng</el-radio>
                <el-radio :label="1">KHÔNG</el-radio>
              </el-radio-group>
              <div class="tip">Khi tắt, tài khoản sẽ không thể tham gia chương trình cộng tác viên.</div>
            </el-form-item>
          </div>
          <div class="item lang" v-if="formItem.spread_open == 1">
            <el-form-item label="Trạng thái cộng tác viên：">
              <el-radio-group v-model="formItem.is_promoter" class="form-sty">
                <el-radio :label="1">Bật</el-radio>
                <el-radio :label="0">Tắt</el-radio>
              </el-radio-group>
              <div class="tip">Bật hoặc tắt quyền cộng tác viên thủ công.</div>
            </el-form-item>
          </div>
          <div class="item lang">
            <el-form-item label="Trạng thái người dùng：">
              <el-radio-group v-model="formItem.status" class="form-sty">
                <el-radio :label="1">Hoạt động</el-radio>
                <el-radio :label="0">Khóa</el-radio>
              </el-radio-group>
            </el-form-item>
          </div>
        </div>
      </div>
      <div class="section">
        <div class="section-hd">Nhận xét của người dùng</div>
        <div class="section-bd">
          <div class="item">
            <el-form-item label="Ghi chú：" prop="mark">
              <el-input
                class="form-sty"
                type="textarea"
                :rows="5"
                v-model="formItem.mark"
                placeholder="Nhập ghi chú (nếu có)"
              ></el-input>
            </el-form-item>
          </div>
        </div>
      </div>
    </el-form>
    <el-dialog :visible.sync="labelShow" append-to-body title="Vui lòng chọn nhãn người dùng" :show-close="true" width="540px">
      <userLabel v-if="labelShow" :only_get="true" :uid="formItem.uid" @close="labelClose" @activeData="activeData">
      </userLabel>
    </el-dialog>
  </div>
</template>

<script>
import userLabel from '@/components/userLabel';

import { userLabelAddApi, getUserInfo, editUser, setUser } from '@/api/user';
import dayjs from 'dayjs';

export default {
  name: 'userInfo',
  components: { userLabel },
  props: {
    userId: {
      type: Number,
      default: 0,
    },
  },
  filters: {
    timeFormat(value) {
      if (!value) {
        return '-';
      }
      return dayjs(value * 1000).format('YYYY-MM-DD HH:mm:ss');
    },
    gender(value) {
      if (value == 1) {
        return 'Nam';
      } else if (value == 2) {
        return 'nữ giới';
      } else {
        return 'không rõ';
      }
    },
  },
  data() {
    return {
      labelShow: false,
      formItem: {
        uid: 0,
        real_name: '',
        phone: '',
        birthday: '',
        card_id: '',
        addres: '',
        mark: '',
        pwd: '',
        true_pwd: '',
        level: '',
        group_id: '',
        label_id: [],
        spread_open: 0,
        is_promoter: 0,
        status: 1,
      },
      groupInfo: [],
      labelInfo: [],
      levelInfo: [],
      infoData: {
        groupInfo: [],
        labelInfo: [],
        levelInfo: [],
      },
      ruleValidate: {
        real_name: [
          { required: true, message: 'Vui lòng nhập họ và tên', trigger: 'blur' },
          { min: 2, max: 50, message: 'Họ và tên phải từ 2-50 ký tự', trigger: 'blur' },
        ],
        phone: [{ required: true, validator: this.validatePhone, trigger: 'blur' }],
        card_id: [{ validator: this.validateCardId, trigger: 'blur' }],
        addres: [{ max: 255, message: 'Địa chỉ tối đa 255 ký tự', trigger: 'blur' }],
        mark: [{ max: 255, message: 'Ghi chú tối đa 255 ký tự', trigger: 'blur' }],
        pwd: [{ validator: this.validatePassword, trigger: 'blur' }],
        true_pwd: [{ validator: this.validateConfirmPassword, trigger: 'blur' }],
      },
      dataLabel: [],
    };
  },
  computed: {
    hasExtendInfo() {
      //   return this.psInfo.extend_info.some((item) => item.value);
    },
  },
  created() {
    this.getUserFrom(this.userId);

    // this.formItem = this.userData.userInfo;
  },
  methods: {
    validatePhone(rule, value, callback) {
      if (!value) return callback(new Error('Vui lòng nhập số điện thoại'));
      const phone = String(value).trim();
      if (!/^(0|\+84)(3|5|7|8|9)\d{8}$/.test(phone)) {
        return callback(new Error('Số điện thoại không đúng định dạng Việt Nam'));
      }
      callback();
    },
    validateCardId(rule, value, callback) {
      if (!value) return callback();
      if (!/^\d{9}$|^\d{12}$/.test(String(value).trim())) {
        return callback(new Error('CMND/CCCD phải gồm 9 hoặc 12 chữ số'));
      }
      callback();
    },
    validatePassword(rule, value, callback) {
      if (!value) return callback();
      if (value.length < 6 || value.length > 32) {
        return callback(new Error('Mật khẩu phải từ 6-32 ký tự'));
      }
      callback();
    },
    validateConfirmPassword(rule, value, callback) {
      if (this.formItem.pwd && !value) return callback(new Error('Vui lòng nhập lại mật khẩu'));
      if (value && value !== this.formItem.pwd) return callback(new Error('Mật khẩu xác nhận không khớp'));
      callback();
    },
    setUser() {
      let data = this.formItem;
      let ids = [];
      this.dataLabel.map((i) => {
        ids.push(i.id);
      });
      data.label_id = ids;
      // if (!data.real_name) return this.$message.warning("Vui lòng nhập tên thật của bạn");
      // if (!data.phone) return this.$message.warning("Vui lòng nhập số điện thoại di động");
      // if (!data.pwd) return this.$message.warning("Vui lòng nhập mật khẩu");
      // if (!data.true_pwd) return this.$message.warning("Vui lòng nhập mật khẩu xác nhận");
      if (data.uid) {
        editUser(data)
          .then((res) => {
            this.$message.success(res.msg);
            this.$emit('success');
          })
          .catch((err) => {
            this.$message.error(err.msg);
          });
      } else {
        setUser(data)
          .then((res) => {
            this.$emit('success');
            this.$message.success(res.msg);
          })
          .catch((err) => {
            this.$message.error('err.msg');
          });
      }
    },
    addLabel() {
      this.$modalForm(userLabelAddApi(0)).then(() => {});
    },
    openLabel(row) {
      this.labelShow = true;
      this.$refs.userLabel.userLabel(JSON.parse(JSON.stringify(this.infoData.labelInfo)));
    },
    closeLabel(label) {
      let index = this.dataLabel.indexOf(this.dataLabel.filter((d) => d.id == label.id)[0]);
      this.dataLabel.splice(index, 1);
    },
    getUserFrom(id) {
      getUserInfo(id)
        .then(async (res) => {
          this.userData = res.data;
          this.$set(this.infoData, 'groupInfo', this.userData.groupInfo);
          this.$set(this.infoData, 'levelInfo', this.userData.levelInfo);
          this.$set(this.infoData, 'labelInfo', this.userData.labelInfo);
          let arr = Object.keys(this.formItem);
          if (this.userData.userInfo) {
            arr.map((i) => {
              this.formItem[i] = this.userData.userInfo[i];
            });
            if (!this.formItem.birthday) this.formItem.birthday = '';
            if (this.formItem.label_id.length) {
              this.dataLabel = this.formItem.label_id;
            }
          } else {
            this.reset();
          }
        })
        .catch((res) => {
          this.$message.error('res.msg');
        });
    },
    // Cửa sổ bật lên nhãn đóng lại
    labelClose() {
      this.labelShow = false;
    },
    activeData(dataLabel) {
      this.labelShow = false;
      this.dataLabel = dataLabel;
    },
    reset() {
      this.formItem = {
        uid: '',
        real_name: '',
        phone: '',
        birthday: '',
        card_id: '',
        addres: '',
        mark: '',
        pwd: '',
        true_pwd: '',
        level: '',
        group_id: '',
        label_id: [],
        spread_open: 0,
        is_promoter: 0,
        status: 1,
      };
    },
  },
};
</script>

<style lang="scss" scoped>
.labelInput {
  border: 1px solid #dcdee2;
  width: 300px;
  padding: 0 15px;
  border-radius: 5px;
  min-height: 30px;
  cursor: pointer;
  font-size: 12px;

  .span {
    color: #c5c8ce;
  }

  .iconxiayi {
    font-size: 12px;
  }
}

.width-add {
  width: 40px;
}

.mr30 {
  margin-right: 30px;
}

.user-info {
  .section {
    padding: 25px 0 0;
    border-bottom: 1px dashed #eeeeee;

    &-hd {
      margin-bottom: 18px;
      padding-left: 10px;
      border-left: 3px solid var(--prev-color-primary);
      font-weight: 500;
      font-size: 14px;
      line-height: 16px;
      color: #303133;
    }

    &-bd {
      display: flex;
      flex-wrap: wrap;
    }

    .item {
      width: 50%;
      display: flex;
      font-size: 13px;
      color: #666;

      .form-sty {
        width: 300px;
      }

      .ivu-form-item {
        margin: 3px 0;
      }

      .addfont {
        display: inline-block;
        font-size: 12px;
        font-weight: 400;
        color: var(--prev-color-primary);
        margin-left: 14px;
        cursor: pointer;
        margin-left: 10px;
      }
    }

    .item.lang {
      width: 100%;
    }

    .value {
      flex: 1;
    }

    .avatar {
      width: 60px;
      height: 60px;
      overflow: hidden;

      img {
        width: 100%;
        height: 100%;
      }
    }
  }
}
</style>
