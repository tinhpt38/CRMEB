<template>
  <div class="cropper-content">
    <div class="cropper-box">
      <div class="cropper">
        <vue-cropper
          ref="cropper"
          :img="option.img"
          :outputSize="option.outputSize"
          :outputType="option.outputType"
          :info="option.info"
          :canScale="option.canScale"
          :autoCrop="option.autoCrop"
          :autoCropWidth="option.autoCropWidth"
          :autoCropHeight="option.autoCropHeight"
          :fixed="option.fixed"
          :fixedNumber="option.fixedNumber"
          :full="option.full"
          :fixedBox="option.fixedBox"
          :canMove="option.canMove"
          :canMoveBox="option.canMoveBox"
          :original="option.original"
          :centerBox="option.centerBox"
          :height="option.height"
          :infoTrue="option.infoTrue"
          :maxImgSize="option.maxImgSize"
          :enlarge="option.enlarge"
          :mode="option.mode"
          @realTime="realTime"
          @imgLoad="imgLoad"
        >
        </vue-cropper>
      </div>
      <!--Các nút công cụ vận hành phía dưới-->
      <div class="footer-btn">
        <div class="scope-btn">
          <input
            type="file"
            id="uploads"
            style="position: absolute; clip: rect(0 0 0 0)"
            accept="image/png, image/jpeg, image/gif, image/jpg"
            @change="selectImg($event)"
          />
          <el-button size="mini" type="danger" plain icon="el-icon-zoom-in" v-db-click @click="changeScale(1)"
            >phóng to</el-button
          >
          <el-button size="mini" type="danger" plain icon="el-icon-zoom-out" v-db-click @click="changeScale(-1)"
            >thu nhỏ</el-button
          >
          <el-button size="mini" type="danger" plain v-db-click @click="rotateLeft">↺ Xoay trái</el-button>
          <el-button size="mini" type="danger" plain v-db-click @click="rotateRight">↻ Xoay phải</el-button>
        </div>
      </div>
    </div>
    <!--Xem trước kết xuất-->
    <div class="show-preview">
      <div class="preview">
        <img :src="previews.url" :style="previews.img" />
      </div>
      <div class="upload-btn">
        <label class="btn" for="uploads">Chọn ảnh</label>
        <el-button size="mini" type="success" v-db-click @click="uploadImg()">Xác nhận tải lên</el-button>
      </div>
    </div>
  </div>
</template>

<script>
import { VueCropper } from 'vue-cropper';
// import { updateAvatar } from ''; //Tại đây thay giao diện upload file bằng file của chính bạn.
import { fileUpload } from '@/api/setting';
export default {
  name: 'CropperImage',
  components: {
    VueCropper,
  },
  data() {
    return {
      name: '',
      resImg: '',
      previews: {},
      option: {
        img: '', //Địa chỉ của hình ảnh đã cắt
        outputSize: 1, //Cắt chất lượng hình ảnh(Không bắt buộc0.1 - 1)
        outputType: 'png', //Cắt để tạo định dạng hình ảnh（jpeg || png || webp）
        info: true, //Thông tin kích thước hình ảnh
        canScale: true, //Hình ảnh có cho phép thu phóng bằng bánh xe cuộn hay không
        autoCrop: true, //Có tạo hộp ảnh chụp màn hình theo mặc định hay không
        autoCropWidth: 200, //Chiều rộng khung ảnh chụp màn hình được tạo mặc định
        autoCropHeight: 200, //Chiều cao khung ảnh chụp màn hình được tạo mặc định
        fixed: true, //Có nên bật tỷ lệ cố định chiều rộng và chiều cao khung ảnh chụp màn hình hay không
        fixedNumber: [1, 1], //Tỷ lệ chiều rộng và chiều cao của khung ảnh chụp màn hình
        full: false, //falseCắt ảnh theo tỷ lệ gốc mà không bị biến dạng
        fixedBox: false, //Kích thước của khung ảnh chụp màn hình là cố định và không thể thay đổi.
        canMove: true, //Hình ảnh đã tải lên có thể được di chuyển?
        canMoveBox: true, //Khung ảnh chụp màn hình có thể kéo được không?
        original: false, //Hình ảnh tải lên được hiển thị theo tỷ lệ ban đầu của chúng
        centerBox: true, //Khung ảnh chụp màn hình có bị giới hạn ở hình ảnh hay không
        height: false, //Có xuất hình ảnh tỷ lệ theo dpr của thiết bị hay không
        infoTrue: false, //trueĐể hiển thị chiều rộng và chiều cao thực của hình ảnh đầu ra, false hiển thị chiều rộng và chiều cao của khung ảnh chụp màn hình mà bạn nhìn thấy.
        maxImgSize: 3000, //Giới hạn chiều rộng và chiều cao tối đa của hình ảnh
        enlarge: 1, //Hình ảnh xuất ra bội số tỷ lệ theo hộp ảnh chụp màn hình
        mode: '300px 300px', //Phương thức hiển thị mặc định của hình ảnh
      },
    };
  },
  methods: {
    //hàm khởi tạo
    imgLoad(msg) {
      console.log('Chức năng khởi tạo công cụ=====' + msg);
    },
    //Thu phóng hình ảnh
    changeScale(num) {
      num = num || 1;
      this.$refs.cropper.changeScale(num);
    },
    //Xoay trái
    rotateLeft() {
      this.$refs.cropper.rotateLeft();
    },
    //Xoay phải
    rotateRight() {
      this.$refs.cropper.rotateRight();
    },
    // //Chức năng xem trước trực tiếp
    realTime(data) {
      let that = this;
      that.previews = data;
      this.$refs.cropper.getCropBlob((data) => {
        this.blobToDataURI(data, function (res) {
          that.previewImg = res;
        });
      });
    },
    blobToDataURI(blob, callback) {
      var reader = new FileReader();
      reader.readAsDataURL(blob);
      reader.onload = function (e) {
        callback(e.target.result);
      };
    },
    //Chọn ảnh
    selectImg(e) {
      let file = e.target.files[0];
      if (!/\.(jpg|jpeg|png|JPG|PNG)$/.test(e.target.value)) {
        this.$message({
          message: 'Yêu cầu về loại hình ảnh：jpeg、jpg、png',
          type: 'error',
        });
        return false;
      }
      //chuyển đổi thànhblob
      let reader = new FileReader();
      reader.onload = (e) => {
        let data;
        if (typeof e.target.result === 'object') {
          data = window.URL.createObjectURL(new Blob([e.target.result]));
        } else {
          data = e.target.result;
        }
        this.option.img = data;
      };
      //chuyển đổi thànhbase64
      reader.readAsDataURL(file);
    },

    base64ImgtoFile(dataurl, filename = 'file') {
      //Chia định dạng base64：['data:image/png;base64','XXXX']
      const arr = dataurl.split(',');
      // .*？ Cho biết khớp bất kỳ ký tự nào với ký tự tiếp theo đáp ứng các điều kiện, khớp chính xác：
      // image/png
      const mime = arr[0].match(/:(.*?);/)[1]; //image/png
      //[image,png] Nhận hậu tố loại hình ảnh
      const suffix = mime.split('/')[1]; //png
      const bstr = atob(arr[1]); //atob() Phương pháp giải mã chuỗi được mã hóa bằng base-64
      let n = bstr.length;
      const u8arr = new Uint8Array(n);
      while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
      }
      return new File([u8arr], `${filename}.${suffix}`, {
        type: mime,
      });
    },

    uploadFile(file) {
      const formData = new FormData();
      formData.append('file', file);
      fileUpload(formData).then((res) => {
        if (res.status == 200) {
          this.$emit('uploadImgSuccess', res.data);
        } else {
          this.$message({
            message: 'Tải lên không thành công',
            type: 'error',
            duration: 1000,
          });
        }
      });
    },
    //Tải ảnh lên
    uploadImg() {
      this.$refs.cropper.getCropData((data) => {
        this.resImg = this.base64ImgtoFile(data);
        this.uploadFile(this.resImg);
      });
    },
  },
};
</script>

<style scoped lang="scss">
.btn {
  outline: none;
  display: inline-block;
  line-height: 1;
  white-space: nowrap;
  cursor: pointer;
  -webkit-appearance: none;
  text-align: center;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  outline: 0;
  -webkit-transition: 0.1s;
  transition: 0.1s;
  font-weight: 500;
  padding: 8px 15px;
  font-size: 12px;
  border-radius: 3px;
  color: #fff;
  background-color: #409eff;
  border-color: #409eff;
  margin-right: 10px;
}
.cropper-content {
  display: flex;
  display: -webkit-flex;
  justify-content: flex-end;
  .cropper-box {
    flex: 1;
    width: 100%;
    .cropper {
      width: auto;
      height: 300px;
    }
  }

  .show-preview {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    .preview {
      overflow: hidden;
      height: 200px;
      width: 200px;
      background: #cccccc;
      transform: scale(0.8);
      border-radius: 50%;
    }
  }
}
.footer-btn {
  margin-top: 30px;
  display: flex;
  display: -webkit-flex;
  justify-content: space-around;
  .scope-btn {
    display: flex;
    display: -webkit-flex;
    justify-content: space-between;
    padding-right: 10px;
  }
  .upload-btn {
    flex: 1;
    -webkit-flex: 1;
    display: flex;
    display: -webkit-flex;
    justify-content: center;
  }
}
</style>
