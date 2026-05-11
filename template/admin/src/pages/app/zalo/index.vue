<template>
  <div class="zalo-config-page">
    <!-- Header -->
    <div class="i-layout-page-header header-title">
      <span class="ivu-page-header-title">Cấu hình Zalo Mini App</span>
      <div class="header-actions">
        <el-button
          size="small"
          :loading="testLoading"
          @click="handleTestConnection"
          icon="el-icon-connection"
        >Kiểm tra kết nối</el-button>
        <el-button
          type="primary"
          size="small"
          :loading="saveLoading"
          @click="handleSave"
          icon="el-icon-check"
        >Lưu cấu hình</el-button>
      </div>
    </div>

    <div class="page-body" v-loading="pageLoading">
      <el-row :gutter="24">

        <!-- Cột trái: Form cấu hình -->
        <el-col :xl="14" :lg="14" :md="24" :sm="24">
          <el-card shadow="never" class="config-card">
            <div slot="header" class="card-header">
              <i class="el-icon-chat-dot-round platform-icon"></i>
              <span class="card-title">Thông tin xác thực</span>
              <el-tag :type="form.zalo_login_open ? 'success' : 'info'" size="small" class="status-tag">
                {{ form.zalo_login_open ? 'Đang bật' : 'Đang tắt' }}
              </el-tag>
            </div>

            <el-form
              ref="configForm"
              :model="form"
              :rules="rules"
              label-width="180px"
              label-position="right"
              @submit.native.prevent
            >
              <!-- Bật/tắt đăng nhập Zalo -->
              <el-form-item label="Đăng nhập Zalo" prop="zalo_login_open">
                <el-switch
                  v-model="form.zalo_login_open"
                  :active-value="1"
                  :inactive-value="0"
                  active-text="Bật"
                  inactive-text="Tắt"
                />
                <div class="form-tip">
                  Cho phép người dùng Zalo Mini App đăng nhập vào hệ thống
                </div>
              </el-form-item>

              <el-divider />

              <!-- App ID -->
              <el-form-item label="App ID" prop="zalo_app_id">
                <el-input
                  v-model.trim="form.zalo_app_id"
                  placeholder="Nhập App ID từ Zalo Developers"
                  clearable
                  :disabled="!form.zalo_login_open"
                >
                  <template slot="prepend">
                    <i class="el-icon-key" />
                  </template>
                </el-input>
                <div class="form-tip">
                  Tìm App ID tại
                  <el-link
                    type="primary"
                    href="https://developers.zalo.me/app"
                    target="_blank"
                    :underline="false"
                  >developers.zalo.me/app</el-link>
                  → Chọn app → Mục "App Info"
                </div>
              </el-form-item>

              <!-- App Secret -->
              <el-form-item label="App Secret" prop="zalo_app_secret">
                <el-input
                  v-model.trim="form.zalo_app_secret"
                  :type="showSecret ? 'text' : 'password'"
                  placeholder="Nhập App Secret (để trống để giữ nguyên)"
                  :disabled="!form.zalo_login_open"
                  autocomplete="new-password"
                >
                  <template slot="prepend">
                    <i class="el-icon-lock" />
                  </template>
                  <template slot="append">
                    <i
                      :class="showSecret ? 'el-icon-view' : 'el-icon-hide'"
                      class="secret-toggle"
                      @click="showSecret = !showSecret"
                    />
                  </template>
                </el-input>
                <div class="form-tip">
                  App Secret dùng để xác thực phía server. Không chia sẻ công khai.
                </div>
              </el-form-item>

              <!-- Callback Domain -->
              <el-form-item label="Domain callback" prop="zalo_callback_domain">
                <el-input
                  v-model.trim="form.zalo_callback_domain"
                  placeholder="VD: https://shop.yourdomain.com"
                  clearable
                  :disabled="!form.zalo_login_open"
                >
                  <template slot="prepend">
                    <i class="el-icon-link" />
                  </template>
                </el-input>
                <div class="form-tip">
                  Domain này phải được thêm vào danh sách trắng trong Zalo Developers → App Settings → Valid Domains
                </div>
              </el-form-item>

              <!-- Bắt buộc gắn SĐT -->
              <el-form-item label="Bắt buộc xác minh SĐT" prop="zalo_bind_phone">
                <el-switch
                  v-model="form.zalo_bind_phone"
                  :active-value="1"
                  :inactive-value="0"
                  :disabled="!form.zalo_login_open"
                  active-text="Có"
                  inactive-text="Không"
                />
                <div class="form-tip">
                  Nếu bật, người dùng Zalo phải xác minh số điện thoại trước khi sử dụng đầy đủ tính năng
                </div>
              </el-form-item>

              <el-divider content-position="left">Mini App — Trang landing web</el-divider>

              <el-form-item label="Deeplink Mini App" prop="zalo_mini_app_deeplink">
                <el-input
                  v-model.trim="form.zalo_mini_app_deeplink"
                  placeholder="VD: https://zalo.me/s/xxxx hoặc link mở app từ Zalo Developers"
                  clearable
                >
                  <template slot="prepend">
                    <i class="el-icon-link" />
                  </template>
                </el-input>
                <div class="form-tip">
                  Link người dùng bấm để mở Mini App trên trang landing (PC). Thường là link chia sẻ công khai từ Zalo Mini App.
                </div>
              </el-form-item>

              <el-form-item label="Ảnh mã QR" prop="zalo_mini_app_qr_image">
                <div class="acea-row row-middle" style="flex-wrap: wrap; gap: 10px;">
                  <el-input
                    v-model="form.zalo_mini_app_qr_image"
                    readonly
                    placeholder="Chưa chọn ảnh — dùng thư viện ảnh CRMEB"
                    style="width: 260px; max-width: 100%;"
                  />
                  <el-button
                    size="small"
                    type="primary"
                    icon="el-icon-picture-outline"
                    @click="openQrPictureModal"
                  >Chọn từ thư viện ảnh</el-button>
                  <el-button
                    v-if="form.zalo_mini_app_qr_image"
                    size="small"
                    icon="el-icon-delete"
                    @click="clearQrImage"
                  >Xóa ảnh</el-button>
                </div>
                <div v-if="displayQrUrl" class="qr-preview">
                  <span class="form-tip" style="display:block;margin:8px 0 4px">Xem trước:</span>
                  <img :src="displayQrUrl" alt="QR Mini App">
                </div>
                <div class="form-tip">
                  Chọn ảnh mã QR trong <strong>Thư viện ảnh</strong> (Quản lý hình ảnh CRMEB). Ảnh hiển thị trên trang landing; deeplink dùng cho nút &quot;Mở Mini App&quot;.
                </div>
              </el-form-item>
            </el-form>
          </el-card>

          <!-- API Endpoints tham khảo -->
          <el-card shadow="never" class="config-card endpoint-card">
            <div slot="header" class="card-header">
              <span class="card-title">API Endpoint tích hợp</span>
            </div>
            <el-table :data="endpoints" size="small" border>
              <el-table-column label="Phương thức" width="90">
                <template slot-scope="{ row }">
                  <el-tag :type="row.method === 'POST' ? 'warning' : 'success'" size="mini">{{ row.method }}</el-tag>
                </template>
              </el-table-column>
              <el-table-column label="Endpoint" prop="path" />
              <el-table-column label="Mô tả" prop="desc" min-width="180" />
            </el-table>
          </el-card>
        </el-col>

        <!-- Cột phải: Hướng dẫn -->
        <el-col :xl="10" :lg="10" :md="24" :sm="24">
          <el-card shadow="never" class="guide-card">
            <div slot="header" class="card-header">
              <i class="el-icon-question" style="color:#0068ff; margin-right:6px" />
              <span class="card-title">Hướng dẫn tích hợp</span>
            </div>

            <el-steps direction="vertical" :active="5" finish-status="success">
              <el-step title="Tạo Zalo App">
                <div slot="description" class="step-desc">
                  Truy cập
                  <el-link type="primary" href="https://developers.zalo.me" target="_blank">developers.zalo.me</el-link>
                  → Tạo ứng dụng mới → Chọn loại <strong>Mini App</strong>
                </div>
              </el-step>

              <el-step title="Lấy App ID & App Secret">
                <div slot="description" class="step-desc">
                  Vào <strong>App Settings</strong> → Tab <strong>App Info</strong> → Copy <code>App ID</code> và <code>App Secret</code> điền vào form bên cạnh
                </div>
              </el-step>

              <el-step title="Cấu hình Valid Domains">
                <div slot="description" class="step-desc">
                  Trong Zalo Developers → <strong>App Settings</strong> → <strong>Valid Domains</strong> → Thêm domain của CRMEB
                  <el-tag size="mini" type="warning" style="margin-top:4px; display:block">
                    {{ callbackDomain || 'https://your-crmeb-domain.com' }}
                  </el-tag>
                </div>
              </el-step>

              <el-step title="Thêm Redirect URI">
                <div slot="description" class="step-desc">
                  <strong>App Settings</strong> → <strong>Redirect URI</strong> → Thêm:
                  <el-tag size="mini" type="info" style="margin-top:4px; display:block; word-break:break-all">
                    {{ callbackDomain }}/api/zalo/auth
                  </el-tag>
                </div>
              </el-step>

              <el-step title="Cấu hình trong CRMEB">
                <div slot="description" class="step-desc">
                  Điền App ID, App Secret, Domain callback → Bấm <strong>Lưu cấu hình</strong> → <strong>Kiểm tra kết nối</strong>
                </div>
              </el-step>
            </el-steps>
          </el-card>

          <!-- Lưu ý bảo mật -->
          <el-card shadow="never" class="warning-card">
            <div slot="header" class="card-header">
              <i class="el-icon-warning" style="color:#E6A23C; margin-right:6px" />
              <span class="card-title">Lưu ý bảo mật</span>
            </div>
            <ul class="warning-list">
              <li>
                <i class="el-icon-close-notification" />
                App Secret phải được giữ bí mật — không nhúng vào code frontend
              </li>
              <li>
                <i class="el-icon-close-notification" />
                Chỉ thêm domain tin cậy vào Valid Domains trên Zalo Developer Console
              </li>
              <li>
                <i class="el-icon-close-notification" />
                Token Zalo có thời hạn, hệ thống tự xử lý refresh — không lưu lâu dài
              </li>
              <li>
                <i class="el-icon-close-notification" />
                Định kỳ rotate App Secret (tối thiểu 6 tháng/lần)
              </li>
            </ul>
          </el-card>
        </el-col>
      </el-row>
    </div>

    <!-- Dialog kết quả test -->
    <el-dialog
      title="Kết quả kiểm tra kết nối Zalo"
      :visible.sync="testDialogVisible"
      width="480px"
      :close-on-click-modal="false"
    >
      <div class="test-result" :class="testResult.status ? 'success' : 'fail'">
        <i :class="testResult.status ? 'el-icon-circle-check' : 'el-icon-circle-close'" class="result-icon" />
        <div class="result-content">
          <p class="result-message">{{ testResult.message }}</p>
          <template v-if="testResult.status && testResult.details">
            <el-descriptions :column="1" size="small" border class="result-details">
              <el-descriptions-item label="App ID">{{ testResult.details.app_id }}</el-descriptions-item>
              <el-descriptions-item label="API URL">{{ testResult.details.api_url }}</el-descriptions-item>
              <el-descriptions-item label="Trạng thái">
                <el-tag type="success" size="mini">Đã xác thực</el-tag>
              </el-descriptions-item>
            </el-descriptions>
          </template>
        </div>
      </div>
      <span slot="footer">
        <el-button size="small" @click="testDialogVisible = false">Đóng</el-button>
      </span>
    </el-dialog>

    <!-- Thư viện ảnh CRMEB — chọn ảnh QR Mini App -->
    <el-dialog
      title="Chọn ảnh mã QR"
      :visible.sync="qrPictureModal"
      width="950px"
      append-to-body
      :close-on-click-modal="false"
    >
      <uploadPictures
        v-if="qrPictureModal"
        :isChoice="qrPictureChoice"
        :gridBtn="gridBtn"
        :gridPic="gridPic"
        @getPic="onQrPicturePicked"
      />
    </el-dialog>
  </div>
</template>

<script>
import { getZaloConfig, saveZaloConfig, testZaloConnection } from '@/api/app';
import Setting from '@/setting';
import uploadPictures from '@/components/uploadPictures';

export default {
  name: 'app_zalo_config',

  components: {
    uploadPictures,
  },

  data() {
    return {
      pageLoading: false,
      saveLoading: false,
      testLoading: false,
      showSecret: false,
      qrPictureModal: false,
      qrPictureChoice: 'Lựa chọn duy nhất',
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

      form: {
        zalo_login_open:           0,
        zalo_app_id:               '',
        zalo_app_secret:           '',
        zalo_callback_domain:      '',
        zalo_bind_phone:           0,
        zalo_mini_app_deeplink:    '',
        zalo_mini_app_qr_image:    '',
      },

      rules: {
        zalo_app_id: [
          {
            validator: (rule, value, cb) => {
              if (this.form.zalo_login_open && !value) {
                cb(new Error('App ID không được để trống khi bật đăng nhập Zalo'));
              } else {
                cb();
              }
            },
            trigger: 'blur',
          },
        ],
        zalo_callback_domain: [
          {
            validator: (rule, value, cb) => {
              if (value && !/^https?:\/\/.+/.test(value)) {
                cb(new Error('Domain phải bắt đầu bằng http:// hoặc https://'));
              } else {
                cb();
              }
            },
            trigger: 'blur',
          },
        ],
        zalo_mini_app_deeplink: [
          {
            validator: (rule, value, cb) => {
              const v = (value || '').trim();
              if (v && !/^https?:\/\/.+/.test(v)) {
                cb(new Error('Deeplink nên bắt đầu bằng http:// hoặc https://'));
              } else {
                cb();
              }
            },
            trigger: 'blur',
          },
        ],
      },

      // Dialog test
      testDialogVisible: false,
      testResult: { status: false, message: '', details: null },

      // Bảng endpoint
      endpoints: [
        {
          method: 'POST',
          path:   '/api/zalo/auth',
          desc:   'Đăng nhập bằng Zalo access_token, nhận JWT CRMEB',
        },
        {
          method: 'POST',
          path:   '/api/zalo/bind_phone',
          desc:   'Gắn số điện thoại sau khi đăng nhập (yêu cầu Bearer token)',
        },
        {
          method: 'POST',
          path:   '/api/register/verify',
          desc:   'Gửi OTP về số điện thoại (dùng chung với H5)',
        },
      ],
    };
  },

  computed: {
    callbackDomain() {
      return this.form.zalo_callback_domain || (window.location.origin || '');
    },
    displayQrUrl() {
      const u = (this.form.zalo_mini_app_qr_image || '').trim();
      if (!u) return '';
      if (/^https?:\/\//.test(u) || u.startsWith('//')) return u;
      const search = '/adminapi/';
      const idx = Setting.apiBaseURL.indexOf(search);
      const host = idx >= 0 ? Setting.apiBaseURL.substring(0, idx) : '';
      return host ? host + u : u;
    },
  },

  created() {
    this.loadConfig();
  },

  methods: {
    openQrPictureModal() {
      this.qrPictureModal = true;
    },
    onQrPicturePicked(pc) {
      const path = pc && (pc.att_dir || pc.satt_dir || '');
      if (!path) {
        this.$message.warning('Không lấy được đường dẫn ảnh');
        return;
      }
      this.form.zalo_mini_app_qr_image = path;
      this.qrPictureModal = false;
      this.$message.success('Đã chọn ảnh từ thư viện');
    },
    clearQrImage() {
      this.form.zalo_mini_app_qr_image = '';
    },

    // ─── Load config ────────────────────────────────────────────────────────

    loadConfig() {
      this.pageLoading = true;
      getZaloConfig()
        .then((res) => {
          const { _meta, ...config } = res.data;
          this.form = { ...this.form, ...config };
        })
        .catch((err) => {
          this.$message.error(err.msg || 'Không thể tải cấu hình');
        })
        .finally(() => {
          this.pageLoading = false;
        });
    },

    // ─── Lưu config ─────────────────────────────────────────────────────────

    handleSave() {
      this.$refs.configForm.validate((valid) => {
        if (!valid) return;

        this.saveLoading = true;
        saveZaloConfig(this.form)
          .then((res) => {
            this.$message.success(res.msg || 'Lưu cấu hình thành công');
            // Reload để lấy secret đã mask
            this.loadConfig();
          })
          .catch((err) => {
            this.$message.error(err.msg || 'Lưu thất bại');
          })
          .finally(() => {
            this.saveLoading = false;
          });
      });
    },

    // ─── Test connection ─────────────────────────────────────────────────────

    handleTestConnection() {
      const appId     = this.form.zalo_app_id;
      const appSecret = this.form.zalo_app_secret;

      if (!appId) {
        this.$message.warning('Vui lòng nhập App ID trước khi kiểm tra');
        return;
      }
      if (!appSecret) {
        this.$message.warning('Vui lòng nhập App Secret (hoặc lưu cấu hình trước)');
        return;
      }

      this.testLoading = true;
      testZaloConnection()
        .then((res) => {
          this.testResult = {
            status:  true,
            message: res.msg || 'Kết nối Zalo API thành công',
            details: res.data || null,
          };
          this.testDialogVisible = true;
        })
        .catch((err) => {
          this.testResult = {
            status:  false,
            message: err.msg || err || 'Kết nối thất bại',
            details: null,
          };
          this.testDialogVisible = true;
        })
        .finally(() => {
          this.testLoading = false;
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.zalo-config-page {
  .header-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 20px;
    background: #fff;
    border-bottom: 1px solid #eee;

    .header-actions {
      display: flex;
      gap: 8px;
    }
  }

  .page-body {
    padding: 20px;
  }

  .config-card,
  .guide-card,
  .warning-card {
    margin-bottom: 20px;
    border-radius: 6px;

    .card-header {
      display: flex;
      align-items: center;
      gap: 8px;

      .platform-icon {
        font-size: 22px;
        color: #0068ff;
      }

      .card-title {
        font-weight: 600;
        font-size: 14px;
        flex: 1;
      }

      .status-tag {
        font-size: 11px;
      }
    }
  }

  .form-tip {
    margin-top: 4px;
    font-size: 12px;
    color: #999;
    line-height: 1.5;
  }

  .qr-preview img {
    max-width: 200px;
    max-height: 200px;
    border-radius: 6px;
    border: 1px solid #ebeef5;
    display: block;
  }

  .secret-toggle {
    cursor: pointer;
    color: #606266;
    &:hover { color: #409EFF; }
  }

  // Endpoint table
  .endpoint-card {
    ::v-deep .el-table {
      font-size: 12px;
    }
  }

  // Hướng dẫn steps
  .guide-card {
    ::v-deep .el-steps {
      .el-step__title {
        font-size: 13px;
        font-weight: 600;
      }
    }

    .step-desc {
      font-size: 12px;
      color: #666;
      line-height: 1.6;
      padding-top: 4px;

      code {
        background: #f4f4f5;
        padding: 1px 5px;
        border-radius: 3px;
        font-size: 11px;
        color: #e6375a;
      }
    }
  }

  // Lưu ý bảo mật
  .warning-card {
    .warning-list {
      list-style: none;
      padding: 0;
      margin: 0;

      li {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        padding: 6px 0;
        font-size: 13px;
        color: #606266;
        border-bottom: 1px dashed #f0f0f0;

        &:last-child { border-bottom: none; }

        i {
          color: #E6A23C;
          margin-top: 2px;
          flex-shrink: 0;
        }
      }
    }
  }

  // Dialog test result
  .test-result {
    display: flex;
    gap: 16px;
    padding: 16px;
    border-radius: 6px;

    &.success {
      background: #f0f9eb;
      border: 1px solid #e1f3d8;
    }
    &.fail {
      background: #fef0f0;
      border: 1px solid #fde2e2;
    }

    .result-icon {
      font-size: 36px;
      flex-shrink: 0;

      .success & { color: #67C23A; }
      .fail &    { color: #F56C6C; }
    }

    .result-content {
      flex: 1;

      .result-message {
        font-size: 14px;
        font-weight: 600;
        margin: 0 0 12px;
      }

      .result-details {
        font-size: 12px;
      }
    }
  }
}
</style>
