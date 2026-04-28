<template>
  <div>
    <el-dialog title="Chỉnh sửa điểm phát sóng" :visible.sync="dialogVisible" @opened="openModal" fullscreen>
      <div class="operationFloor">
        <div class="imgBox" @mouseup.left.stop="changeStop()">
          <div ref="container" id="img-box-container" class="container">
            <img
              ref="backgroundImg"
              :src="imgs"
              ondragstart="return false;"
              oncontextmenu="return false;"
              onselect="document.selection.empty();"
              alt="img"
              @mousedown.left.stop="mouseDown($event)"
            />
            <!--draw hotpot-->
            <div
              v-show="caseShow"
              :style="{
                width: areaWidth + 'px',
                height: areaHeight + 'px',
                left: starX + 'px',
                top: starY + 'px',
              }"
              class="area"
            />
            <!--be hotpot-->
            <AreaBox
              v-for="(item, index) in areaData"
              :area-data-index="index"
              :key="'area' + index"
              :link="item.link"
              :title="Item.title"
              :type="parseInt(item.type)"
              :area-init.sync="item"
              :parent-width="parentWidth"
              :parent-height="parentHeight"
              @delAreaBox="delAreaBox"
              @addURL="addURL"
            />
          </div>
        </div>
        <!-- Cấu hình liên kết vùng nóng -->
        <div class="form">
          <h2 class="mb20">Hình ảnh vùng nóng</h2>
          <el-alert type="warning" :closable="false" show-icon>Đóng khung phạm vi vùng nóng và nhấp đúp để đặt thông tin vùng nóng.</el-alert>

          <div v-for="(item, index) in areaData" :key="index" class="form-row">
            <div class="form-item">
              <span class="num">Vùng nóng{{ item.number }}</span>
            </div>
            <div class="form-item label">
              <div>
                <el-input
                  icon="ios-arrow-forward"
                  v-model="item.link"
                  :style="linkInputStyle"
                  placeholder="Chọn liên kết nhảy"
                >
                  <i class="el-icon-link" slot="suffix" @click="getLink(index)" />
                </el-input>
              </div>
            </div>
            <i class="el-icon-delete" @click="delAreaBox(index)" />
          </div>
        </div>
      </div>
      <div slot="footer">
        <el-button class="mr20" type="primary" @click="saveAreaData">Hoàn thành</el-button>
      </div>
    </el-dialog>
    <linkaddress ref="linkaddres" @linkUrl="linkUrl"></linkaddress>
  </div>
</template>

<script>
import AreaBox from './AreaBox';
import linkaddress from '@/components/linkaddress';

export default {
  name: 'OperationFloor',
  components: {
    AreaBox,
    linkaddress,
  },
  props: {
    /**
     * @description Đối tượng dữ liệu hình ảnh
     * @type {ImgData}
     */
    imgs: {
      type: String, // Loại hình ảnh
      default: () => '', // Giá trị mặc định là chuỗi trống
    },
    /**
     * @description Đây có phải là món súp phổ biến không?
     * @type {boolean}
     */
    isHotPot: {
      type: Boolean, // kiểu Boolean
      default: () => false, // Giá trị mặc định làfalse
    },
    /**
     * @description Đối tượng dữ liệu vùng hình ảnh
     * @type {AreaData[]}
     */
    imgAreaData: {
      type: Array, // kiểu mảng
      default: () => [], // Giá trị mặc định là một mảng trống
    },
    /**
     * @description Đối tượng kiểu hộp nhập liên kết
     * @type {LinkInputStyle}
     */
    linkInputStyle: {
      type: Object, // Loại đối tượng
      default: () => ({
        // Giá trị mặc định là một đối tượng chứa thuộc tính chiều rộng
        width: '300px',
      }),
    },
  },
  data() {
    return {
      /**
       * @description Hộp thoại có hiển thị không?
       * @type {boolean}
       */
      dialogVisible: false,
      /**
       * @description tọa độ bắt đầu x
       * @type {number}
       */
      starX: 0,
      /**
       * @description tọa độ bắt đầu y
       * @type {number}
       */
      starY: 0,
      /**
       * @description chiều rộng diện tích
       * @type {number}
       */
      areaWidth: 0,
      /**
       * @description chiều cao khu vực
       * @type {number}
       */
      areaHeight: 0,
      /**
       * @description Chỉ mục hình ảnh hiện đang hiển thị
       * @type {boolean}
       */
      caseShow: false,
      /**
       * @description Chiều rộng của hình ảnh hiện tại
       * @type {null}
       */
      nowImgWidth: null,
      /**
       * @description Dữ liệu khu vực
       * @type {Array}
       */
      areaData: [],
      /**
       * @description Số hình ảnh hiện đang hiển thị
       * @type {number}
       */
      imgNum: 1,
      /**
       * @description Chiều rộng phần tử cha
       * @type {number}
       */
      parentWidth: 0,
      /**
       * @description Chiều cao phần tử cha
       * @type {number}
       */
      parentHeight: 0,
      /**
       * @description chiều rộng mặc định
       * @type {number}
       */
      defaultWidth: 750,
      /**
       * @description Chỉ mục hình ảnh hiện đang hiển thị
       * @type {number}
       */
      itemIndex: 0,
    };
  },
  computed: {},
  watch: {
    imgAreaData(val) {
      this.areaData = [...val];
    },
  },
  mounted() {
    this.areaData = [...this.imgAreaData];
  },
  methods: {
    openModal() {
      this.$nextTick(() => {
        const parentDiv = document.querySelector('#img-box-container');
        //Lấy chiều rộng và chiều cao của phần tử
        this.parentWidth = this.defaultWidth;
        // this.parentWidth = parentDiv.clientWidth;
        this.parentHeight = parentDiv.clientHeight;
        // console.log("this.parentWidth", this.parentWidth, this.parentHeight)
      });
    },
    closeModal() {
      this.$confirm('Nội dung không được lưu. Bạn có muốn từ bỏ việc tiết kiệm trước khi rời đi?？', 'Tin nhắn nhắc nhở', {
        confirmButtonText: 'Chắc chắn',
        cancelButtonText: 'Hủy bỏ',
        type: 'warning',
      })
        .then(() => {
          this.dialogVisible = false;
        })
        .catch(() => {
          this.$message({
            type: 'info',
            message: 'Đã hủy',
          });
        });
    },
    // Sơn vùng nóng bắt đầu
    mouseDown(e) {
      e.preventDefault();
      this.caseShow = true;
      // Ghi lại giá trị trượt ban đầu
      this.starX = e.layerX -5;
      this.starY = e.layerY -5;
      // Quá trình trượt chuột
      if (!document.onmousemove) {
        let maxWidth = this.defaultWidth - e.layerX;
        document.onmousemove = (ev) => {
          if (ev.layerX - this.starX < maxWidth) {
            this.areaWidth = ev.layerX - this.starX - 5;
          } else {
            this.areaWidth = maxWidth;
          }
          this.areaHeight = ev.layerY - this.starY -5;
        };
      }
    },
    // Tranh kết thúc vùng nóng
    changeStop() {
      document.onmousemove = null;
      this.imgNum = this.areaData.length + 1;
      if (this.caseShow && this.areaWidth > 10 && this.areaHeight > 10) {
        const data = {
          number: this.imgNum,
          starX: this.starX,
          starY: this.starY,
          areaWidth: this.areaWidth,
          areaHeight: this.areaHeight,
          nowImgWidth: this.defaultWidth,
          link: '',
        };
        this.areaData.push(data);
      }
      // Khởi tạo bản vẽ
      this.caseShow = false;
      this.starX = 0;
      this.starY = 0;
      this.areaWidth = 0;
      this.areaHeight = 0;
    },
    // Xóa điểm phát sóng được chỉ định
    delAreaBox(index) {
      /* Xóa điểm phát sóng */
      this.areaData.splice(index, 1);
      this.$emit('delAreaData', this.areaData);
      /* Sau khi xóa, mỗi vùng nóng được đánh số lại theo thứ tự. */
      if (this.areaData) {
        const arr = this.areaData.filter((i) => I.number > index);
        if (!arr) return;
        arr.forEach((i) => i.number--);
        if (this.areaData[this.areaData.length - 1]) {
          this.imgNum = this.areaData[this.areaData.length - 1].number + 1;
        } else {
          this.imgNum = 1;
        }
      }
    },
    // Thêm URL
    addURL(index, url) {
      let obj = {
        ...this.areaData[index],
        link: url,
      };
      this.$set(this.areaData, index, obj);
    },
    // Lưu thông tin vùng nóng
    saveAreaData() {
      if ((this.areaData && !this.areaData.length) || !this.checkData(this.areaData)) {
        this.$message.error('Vùng nóng có được định cấu hình bằng liên kết hay không và có thêm ít nhất một vùng nóng hay không?');
        return;
      }
      this.$emit('saveAreaData', this.areaData);
      this.dialogVisible = false;
      this.$message.success('Đã chỉnh sửa thành công!');
    },
    /**
     * Kiểm tra xem mỗi phần tử trong danh sách có thuộc tính liên kết không
     * @param {Array} list - Danh sách cần kiểm tra
     * @returns {Boolean} - Liệu tất cả các phần tử có thuộc tính liên kết hay không
     */
    checkData(list) {
      let isCheck = true;
      list.some((val) => {
        if (!val.link) {
          isCheck = false;
        }
      });
      return isCheck;
    },
    /**
     * @description Lấy địa chỉ liên kết và mở hộp phương thức để thêm liên kết
     * @param {number} index - Giá trị chỉ mục của mục hiện tại
     */
    getLink(index) {
      // Đặt giá trị chỉ mục của mục hiện tại
      this.itemIndex = index;
      // Mở hộp phương thức để thêm liên kết
      this.$refs.linkaddres.modals = true;
    },
    /**
     * @description Xử lý sự kiện đầu vào của địa chỉ liên kết
     * @param {string} e - Địa chỉ liên kết
     */
    linkUrl(e) {
      // Lưu địa chỉ liên kết vào mục dữ liệu tương ứng
      this.areaData[this.itemIndex].link = e;
    },
  },
};
</script>

<style lang="scss" scoped>
::v-deep .el-dialog {
  border-radius: 0px !important;
  .el-alert__icon.is-big {
    font-size: 14px;
    width: 16px;
  }
  .el-alert .el-alert__description {
    margin: 0;
  }
}
.btn {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 16px 0;
}
.dialog-footer {
  text-align: right;
  margin-top: 20px;
  margin-right: 20px;
}
.operationFloor {
  display: flex;
  position: relative;
  max-height: 80vh;
  .header {
    .titleBox {
      display: flex;
      justify-content: space-between;
      align-items: center;
      height: 100px;
      .name {
        font-size: 13px;
        font-weight: bold;
      }
    }
    .textBox {
      font-size: 12px;
      color: #777;
      margin-bottom: 10px;
    }
  }
  .imgBox::-webkit-scrollbar {
    display: none; /* Chrome Safari */
  }
  .imgBox {
    display: flex;
    justify-content: center;
    width: 65%;
    overflow-y: scroll;
    max-height: 800px;
    .container {
      position: relative;
      border: 1px solid #f5f5f5;
    }

    img {
      cursor: crosshair;
      display: block;
      width: 750px;
    }
    .area {
      position: absolute;
      width: 200px;
      height: 200px;
      left: 200px;
      top: 300px;
      background: rgba(#2980b9, 0.3);
      border: 1px dashed #34495e;
    }
  }
}
.form {
  font-size: 12px;
  width: 30%;
  max-height: 800px;
  overflow-y: scroll;
  .form-row {
    display: flex;
    margin: 12px 0;
    align-items: center;
    .form-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      white-space: nowrap;
      margin: 0 5px;
      font-size: 12px;
      .num {
        width: 69px;
        color: #999;
        font-size: 12px;
      }
      .label {
        color: #c7c7c7;
      }
    }
  }
  .el-icon-delete {
    font-size: 16px;
    cursor: pointer;
  }
}
</style>
