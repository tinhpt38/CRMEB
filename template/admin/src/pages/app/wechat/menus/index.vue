<template>
  <div class="article-manager">
    <!-- <div class="i-layout-page-header header-title">
      <span class="ivu-page-header-title">{{ $route.meta.title }}</span>
    </div> -->
    <pages-header ref="pageHeader" :title="$route.meta.title"></pages-header>
    <el-card :bordered="false" shadow="never" class="ivu-mt mt16">
      <!-- Cài đặt tài khoản chính thức -->
      <el-row :gutter="24">
        <el-col :span="24" class="ml40">
          <!-- Chức năng xem trước -->
          <el-col :span="24">
            <el-col :xl="7" :lg="7" :md="22" :sm="22" :xs="22" class="left mb15">
              <img class="top" src="../../../../assets/images/mobilehead.png" />
              <img class="bottom" src="@/assets/images/mobilefoot.png" />
              <div style="background: #f4f5f9; min-height: 438px; position: absolute; top: 63px; width: 320px"></div>
              <div class="textbot">
                <div class="li" v-for="(item, indx) in list" :key="indx" :class="{ active: item === formValidate }">
                  <div>
                    <div class="add" v-db-click @click="add(item, indx)">
                      <i class="el-icon-plus"></i>
                      <div class="arrow"></div>
                    </div>
                    <div class="tianjia">
                      <div
                        class="addadd"
                        v-for="(j, index) in item.sub_button"
                        :key="index"
                        :class="{ active: j === formValidate }"
                        v-db-click
                        @click="gettem(j, index, indx)"
                      >
                        {{ j.name || 'Menu phụ' }}
                      </div>
                    </div>
                  </div>
                  <div class="text" v-db-click @click="gettem(item, indx, null)">{{ item.name || 'Thực đơn cấp độ đầu tiên' }}</div>
                </div>
                <div class="li" v-show="list.length < 3">
                  <div class="text" v-db-click @click="addtext"><i class="el-icon-plus"></i></div>
                </div>
              </div>
            </el-col>
            <el-col :xl="11" :lg="12" :md="22" :sm="22" :xs="22">
              <el-tabs value="name1" v-if="checkedMenuId !== null">
                <el-tab-pane label="Thông tin thực đơn" name="name1">
                  <el-col :span="24" class="userAlert">
                    <div class="box-card right">
                      <el-alert type="info" show-icon closable title="Menu con đã được thêm vào, chỉ có thể đặt tên menu"></el-alert>
                      <el-form
                        ref="formValidate"
                        :model="formValidate"
                        :rules="ruleValidate"
                        label-width="100px"
                        class="mt20"
                      >
                        <el-form-item label="Tên thực đơn" prop="name">
                          <el-input v-model="formValidate.name" placeholder="Vui lòng điền tên thực đơn" class="spwidth"></el-input>
                        </el-form-item>
                        <el-form-item label="Trạng thái quy tắc" prop="type">
                          <el-select v-model="formValidate.type" placeholder="Vui lòng chọn trạng thái quy tắc" class="spwidth">
                            <el-option value="click" label="Từ khóa"></el-option>
                            <el-option value="view" label="Chuyển đến trang web"></el-option>
                            <el-option value="miniprogram" label="Chương trình nhỏ"></el-option>
                          </el-select>
                        </el-form-item>
                        <div v-if="formValidate.type === 'click'">
                          <el-form-item label="Từ khóa" prop="key">
                            <el-input v-model="formValidate.key" placeholder="Hãy điền từ khóa" class="spwidth"></el-input>
                          </el-form-item>
                        </div>
                        <div v-if="formValidate.type === 'miniprogram'">
                          <el-form-item label="Appid" prop="appid">
                            <el-input v-model="formValidate.appid" placeholder="Vui lòng điền vàoappid" class="spwidth"></el-input>
                          </el-form-item>
                          <el-form-item label="Đường dẫn chương trình nhỏ" prop="pagepath">
                            <el-input
                              v-model="formValidate.pagepath"
                              placeholder="Hãy điền vào đường dẫn chương trình mini"
                              class="spwidth"
                            ></el-input>
                          </el-form-item>
                          <el-form-item label="Trang web thay thế" prop="url">
                            <el-input
                              v-model="formValidate.url"
                              placeholder="Vui lòng điền vào trang dự phòng"
                              class="spwidth"
                            ></el-input>
                          </el-form-item>
                        </div>
                        <div v-if="formValidate.type === 'view'">
                          <el-form-item label="Chuyển địa chỉ" prop="url">
                            <el-input
                              v-model="formValidate.url"
                              placeholder="Vui lòng điền địa chỉ nhảy"
                              class="spwidth"
                            ></el-input>
                          </el-form-item>
                        </div>
                      </el-form>
                    </div>
                  </el-col>
                </el-tab-pane>
              </el-tabs>
              <el-col :span="24" v-if="isTrue">
                <el-button size="small" type="danger" v-db-click @click="deltMenus">Xóa</el-button>
                <el-button type="primary" v-db-click @click="submenus('formValidate')">Lưu và xuất bản</el-button>
              </el-col>
            </el-col>
          </el-col>
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import { wechatMenuApi, MenuApi } from '@/api/app';
export default {
  name: 'wechatMenus',
  data() {
    return {
      modal2: false,
      formValidate: {
        name: '',
        type: 'click',
        appid: '',
        url: '',
        key: '',
        pagepath: '',
        id: 0,
      },
      ruleValidate: {
        name: [
          { required: true, message: 'Vui lòng điền tên thực đơn', trigger: 'blur' },
          { min: 1, max: 14, message: 'Độ dài từ 1 đến 14 ký tự', trigger: 'blur' },
        ],
        key: [{ required: true, message: 'Hãy điền từ khóa', trigger: 'blur' }],
        appid: [{ required: true, message: 'Vui lòng điền vàoappid', trigger: 'blur' }],
        pagepath: [{ required: true, message: 'Vui lòng điền vào trang dự phòng', trigger: 'blur' }],
        url: [{ required: true, message: 'Vui lòng điền địa chỉ nhảy', trigger: 'blur' }],
        type: [{ required: true, message: 'Vui lòng chọn trạng thái quy tắc', trigger: 'change' }],
      },
      parentMenuId: null,
      list: [],
      checkedMenuId: null,
      isTrue: false,
    };
  },
  mounted() {
    this.getMenus();
    if (this.list.length) {
      this.formValidate = this.list[this.activeClass];
    } else {
      return this.formValidate;
    }
  },
  methods: {
    // Thêm chức năng trường cấp một
    defaultMenusData() {
      return {
        type: 'click',
        name: '',
        sub_button: [],
      };
    },
    // Thêm chức năng trường phụ
    defaultChildData() {
      return {
        type: 'click',
        name: '',
      };
    },
    // Nhận thực đơn
    getMenus() {
      wechatMenuApi()
        .then(async (res) => {
          let data = res.data;
          this.list = data.menus;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // Nhấn Lưu để gửi
    submenus(name) {
      if (this.isTrue && !this.checkedMenuId && this.checkedMenuId !== 0) {
        this.putData();
      } else {
        this.$refs[name].validate((valid) => {
          if (valid) {
            this.putData();
          } else {
            if (!this.check()) return false;
          }
        });
      }
    },
    // Mớidata
    putData() {
      let data = {
        button: this.list,
      };
      MenuApi(data)
        .then(async (res) => {
          this.$message.success(res.msg);
          this.checkedMenuId = null;
          this.formValidate = {};
          this.isTrue = false;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // phần tử nhấp chuột
    gettem(item, index, pid) {
      this.checkedMenuId = index;
      this.formValidate = item;
      this.parentMenuId = pid;
      this.isTrue = true;
    },
    // Thêm cấp độ thứ hai
    add(item, index) {
      if (!this.check()) return false;
      if (item.sub_button.length < 5) {
        let data = this.defaultChildData();
        let id = item.sub_button.length;
        item.sub_button.push(data);
        this.formValidate = data;
        this.checkedMenuId = id;
        this.parentMenuId = index;
        this.isTrue = true;
      } else {
        this.$message.warning('Chỉ có thể thêm tối đa 5 menu phụ!');
        return false;
      }
    },
    // Thêm một cấp độ
    addtext() {
      if (!this.check()) return false;
      let data = this.defaultMenusData();
      let id = this.list.length;
      this.list.push(data);
      this.formValidate = data;
      this.checkedMenuId = id;
      this.parentMenuId = null;
      this.isTrue = true;
    },
    // Chức năng phán đoán
    check: function () {
      let reg = /[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?/;
      if (this.checkedMenuId === null) return true;
      if (!this.isTrue) return true;
      if (!this.formValidate.name) {
        this.$message.warning('Vui lòng nhập tên nút!');
        return false;
      }
      if (this.formValidate.type === 'click' && !this.formValidate.key) {
        this.$message.warning('Vui lòng nhập từ khóa!');
        return false;
      }
      if (this.formValidate.type === 'view' && !reg.test(this.formValidate.url)) {
        this.$message.warning('Vui lòng nhập đúng địa chỉ nhảy!');
        return false;
      }
      if (
        this.formValidate.type === 'miniprogram' &&
        (!this.formValidate.appid || !this.formValidate.pagepath || !this.formValidate.url)
      ) {
        this.$message.warning('Vui lòng điền cấu hình chương trình mini hoàn chỉnh!');
        return false;
      }
      return true;
    },
    // xóa bỏ
    deltMenus() {
      if (this.isTrue) {
        this.$confirm('Bạn có chắc chắn xóa menu này không??', 'gợi ý', {
          confirmButtonText: 'Chắc chắn',
          cancelButtonText: 'Hủy bỏ',
          type: 'warning',
          beforeClose(action, instance, done) {
            if (action == 'confirm') {
              instance.$refs.confirm.$el.onclick = a();
              function a(e) {
                e = e || window.event;
                if (e.detail != 0) {
                  done();
                }
              }
            } else {
              done();
            }
          },
        })
          .then(() => {
            this.parentMenuId === null
              ? this.list.splice(this.checkedMenuId, 1)
              : this.list[this.parentMenuId].sub_button.splice(this.checkedMenuId, 1);
            this.parentMenuId = null;
            this.formValidate = {
              name: '',
              type: 'click',
              appid: '',
              url: '',
              key: '',
              pagepath: '',
              id: 0,
            };
            this.isTrue = true;
            this.modal2 = false;
            this.checkedMenuId = null;
            this.$refs['formValidate'].resetFields();
          })
          .catch(() => {});
      } else {
        this.$message.warning('Vui lòng chọn một thực đơn!');
      }
    },
    // Xác nhận xóa
    del() {
      this.parentMenuId === null
        ? this.list.splice(this.checkedMenuId, 1)
        : this.list[this.parentMenuId].sub_button.splice(this.checkedMenuId, 1);
      this.parentMenuId = null;
      this.formValidate = {
        name: '',
        type: 'click',
        appid: '',
        url: '',
        key: '',
        pagepath: '',
        id: 0,
      };
      this.isTrue = true;
      this.modal2 = false;
      this.checkedMenuId = null;
      this.$refs['formValidate'].resetFields();
    },
  },
};
</script>
<style scoped lang="scss">
* {
  -moz-user-select: none; /*Firefox*/
  -webkit-user-select: none; /*webkitTrình duyệt*/
  -ms-user-select: none; /*IE10*/
  -khtml-user-select: none; /*trình duyệt sớm*/
  user-select: none;
}

.left {
  min-width: 390px;
  min-height: 550px;
  position: relative;
  padding-left: 40px;
}

.top {
  position: absolute;
  top: 0px;
}

.bottom {
  position: absolute;
  bottom: 0px;
}

.textbot {
  position: absolute;
  bottom: 0px;
  left: 55px;
  width: 100%;
}
.active {
  border: 1px solid var(--prev-color-primary) !important;
  color: var(--prev-color-primary) !important;
}
.li {
  float: left;
  width: 92px;
  height: 48px;
  line-height: 48px;
  border-left: 1px solid #e7e7eb;
  background: #fafafa;
  text-align: center;
  cursor: pointer;
  color: #999;
  position: relative;
}
.text {
  height: 50px;
  white-space: nowrap;
  width: 100%;
  overflow: hidden;
  text-overflow: ellipsis;
  padding: 0 5px;
}
.text:hover {
  color: #000;
}

.add {
  position: absolute;
  bottom: 65px;
  width: 100%;
  line-height: 40px;
  // border: 1px solid #e7e7eb;
  background: #fafafa;
}
.arrow {
  position: absolute;
  bottom: -16px;
  left: 36px;
  /* Vị trí của các góc tròn cần được sửa lỗi cẩn thận. */
  width: 0;
  height: 0;
  font-size: 0;
  border: solid 8px;
  border-color: #fafafa #f4f5f9 #f4f5f9 #f4f5f9;
}
.tianjia {
  position: absolute;
  bottom: 107px;
  width: 100%;
  line-height: 48px;
  background: #fafafa;
  :first-child {
    border: none;
  }
}
.addadd {
  width: 100%;
  line-height: 40px;
  border-top: 1px solid #f0f0f0;
  background: #fafafa;
  height: 40px;
}
.right {
  background: #fff;
  min-height: 400px;
}
.spwidth {
  width: 100%;
}
.userAlert {
  margin-top: 16px !important;
}
</style>
