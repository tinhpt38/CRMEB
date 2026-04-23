<!-- Nhập khẩu sản phẩm -->
<template>
  <div class="goods-import">
    <!-- Tải xuống mẫu -->
    <div class="download acea-row row-middle">
      <span>Vui lòng chỉnh sửa nội dung theo định dạng trong mẫu Excel trước khi upload</span>
      <img src="@/assets/images/excel-icon.png" alt="" />
      <a href="/product_migration.xlsx" download class="download-text cup">Tải xuống mẫu Excel</a>
    </div>

    <div class="goods-upload mt20">
      <el-upload
        v-show="!fileUrl && !importStatus"
        ref="upload"
        class="upload-demo"
        :drag="!fileUrl"
        :show-file-list="false"
        :action="uploadUrl"
        :headers="header"
        :before-upload="fileChange"
        :on-success="handleSuccess"
        accept=".xls, .xlsx"
      >
        <template>
          <img class="el-upload-dragger__icon mb20" src="@/assets/images/upload-icon.png" alt="" />
          <div class="el-upload__text">Kéo tệp vào đây hoặc<em>Bấm để thêm</em></div>
          <div class="el-upload__trip">Hỗ trợ .xls, .xlsx, giới hạn ở 10M</div>
        </template>
      </el-upload>
      <div v-show="fileUrl && !importStatus" class="file-info">
        <img class="el-upload-dragger__icon mb20" src="@/assets/images/upload-icon.png" alt="" />
        <div class="el-upload__text">{{ fileName }}</div>
        <div class="flex mt12" v-if="fileUrl && !importLoading">
          <div class="active-btn" @click="selectFile">Tải lên lại</div>
          <div class="active-btn" @click="fileUrl = ''">xóa bỏ</div>
        </div>
        <div class="el-upload__trip" v-if="importLoading">
          Khi nhập, bạn có thể đóng cửa sổ bật lên hiện tại và xem kết quả nhập trong danh sách sau.
          <i class="el-icon-loading"></i>
        </div>
        <el-button v-else class="btn-import" type="primary" size="small" @click="importGoods">Nhập ngay</el-button>
      </div>
      <div v-show="fileUrl && importStatus" class="file-info">
        <img class="el-upload-dragger__icon mb20" :src="statusImage" alt="" />
        <div class="el-upload__text">
          Tổng nhập khẩu {{ resultData.all }} một, thành công {{ resultData.success }} một, thất bại {{ resultData.fail }} nhảy qua
          {{ resultData.jump }} cá nhân
        </div>
        <div class="el-upload__trip" v-if="resultData.fail > 0">
          Bạn có thể tải xuống dữ liệu bị lỗi, sửa đổi và sau đó nhập lại <span class="active-btn" @click="downloadFailData">Tải xuống dữ liệu không thành công</span>
        </div>
        <div>
          <el-button class="btn-import" size="small" @click="selectFile">Nhập lại</el-button>
          <el-button type="primary" class="btn-import" @click="close">Hoàn thành</el-button>
        </div>
      </div>
    </div>
    <!-- Quy tắc nhập khẩu -->
    <div class="import-rule mt20">
      <div class="rule-title">Quy tắc nhập khẩu</div>
      <!-- 1. Trước tiên, vui lòng tải xuống mẫu, điền vào các trường trong mẫu, sau đó tải tệp lên.
2. Không đóng trang trước khi quá trình nhập hoàn tất, nếu không dữ liệu có thể không chính xác.
3. Kích thước tệp không vượt quá 10MB.
4. Có giới hạn 10.000 hàng bản ghi để nhập. Vui lòng nhập các bản ghi dư thừa nhiều lần.。 -->
      <div class="rule-text">1. Vui lòng tải mẫu xuống trước, điền vào các trường trong mẫu rồi tải tệp lên。</div>
      <div class="rule-text">2. Vui lòng không đóng trang trước khi quá trình nhập hoàn tất, nếu không dữ liệu có thể không chính xác.。</div>
      <div class="rule-text">3. Kích thước tập tin không vượt quá10MB。</div>
      <div class="rule-text">4. Có giới hạn 10.000 hàng bản ghi được nhập. Vui lòng nhập các bản ghi dư thừa thành nhiều đợt.。</div>
    </div>
  </div>
</template>

<script>
import { importProductImport } from '@/api/export';
import Setting from '@/setting';
import { getCookies } from '@/libs/util';
import { isXlsUpload } from '@/utils/index';

export default {
  name: 'goodsImport',
  data() {
    return {
      uploadUrl: Setting.apiBaseURL + '/file/upload/1',
      header: {
        'Authori-zation': 'Bearer ' + getCookies('token'),
      },
      fileName: '',
      fileUrl: '',
      importStatus: false,
      importLoading: false,
      resultData: {
        all: 0,
        success: 0,
        fail: 0,
        jump: 0,
      },
      statusImage: require('@/assets/images/file-success.png'),
    };
  },
  watch: {
    resultData: {
      handler(newValue) {
        if (newValue.fail > 0) {
          this.statusImage = require('@/assets/images/file-fail.png');
        } else {
          this.statusImage = require('@/assets/images/file-success.png');
        }
      },
      deep: true, // Giá trị mặc định là sai, cho biết có giám sát sâu hay không
    },
  },
  mounted() {},
  methods: {
    fileChange(file, fileList) {
      if (isXlsUpload(file)) {
        // giới hạn10M
        if (file.size >= 10485760) {
          this.$message.error('Kích thước tệp không thể vượt quá10MB');
          return false;
        } else {
          this.fileName = file.name;
        }
      } else {
        return false;
      }
    },
    selectFile() {
      this.importStatus = false;
      this.importLoading = false;
      // Gọi tập tin đã chọn
      this.$refs['upload'].$refs['upload-inner'].handleClick();
    },
    handleSuccess(res, file, fileList) {
      if (res.status === 200) {
        this.fileUrl = res.data.src;
      }
    },
    importGoods() {
      this.importLoading = true;
      this.importStatus = false;
      importProductImport({
        file: this.fileUrl,
      })
        .then((res) => {
          // Trả về kết quả nhập
          this.importStatus = true;
          this.resultData = res.data;
        })
        .catch((err) => {
          this.importLoading = false;
          this.importStatus = false;
          this.$message.error(err.msg);
        });
    },
    close() {
      this.fileUrl = '';
      this.fileName = '';
      this.importStatus = false;
      this.$emit('close');
    },
    downloadFailData() {
      // Tải xuống dữ liệu không thành công
    },
  },
};
</script>

<style lang="scss" scoped>
.download {
  background-color: var(--prev-color-primary-light-9);
  padding: 12px;
  border-radius: 4px;
  color: #303133;
  font-size: 12px;
  img {
    width: 19px;
    height: 19px;
    margin: 0 4px 0 8px;
  }
  .download-text {
    color: var(--prev-color-primary);
  }
}
.goods-upload {
  width: 100%;
  ::v-deep .el-upload {
    width: 100%;
    .el-upload-dragger {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      padding: 170px 0;
      .el-upload-dragger__icon {
        width: 42px;
        height: 57px;
      }
      .el-upload__trip {
        font-weight: 400;
        font-size: 12px;
        color: #999999;
        margin-top: 6px;
      }
    }
  }
  .file-info {
    height: 342px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    border: 1px dashed #d9d9d9;
    border-radius: 4px;
    margin-top: 10px;
    .active-btn {
      color: var(--prev-color-primary);
      font-size: 12px;
      font-weight: 400;
      margin: 0 6px;
      cursor: pointer;
    }
    .btn-import {
      margin-top: 26px;
    }
    .el-upload-dragger__icon {
      width: 42px;
      height: 57px;
    }
    .el-upload__trip {
      display: flex;
      align-items: center;
      font-weight: 400;
      font-size: 12px;
      color: #999;
      margin-top: 6px;
    }
  }
}
.import-rule {
  .rule-title {
    font-weight: 500;
    font-size: 14px;
    color: #303133;
    margin-bottom: 6px;
  }
  .rule-text {
    font-size: 12px;
    color: #303133;
  }
}
</style>
