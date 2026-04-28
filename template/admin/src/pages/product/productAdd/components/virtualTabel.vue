<template>
  <div>
    <slot></slot>
  </div>
</template>

<script>
import throttle from 'lodash/throttle';

export default {
  name: 'el-table-virtual-scroll',
  props: {
    data: {
      type: Array,
      required: true,
    },
    height: {
      type: Number,
      default: 60,
    },
    buffer: {
      type: Number,
      default: 500,
    },
    keyProp: {
      type: String,
      default: 'id',
    },
    throttleTime: {
      type: Number,
      default: 100,
    },
  },
  data() {
    return {
      sizes: {}, // Ánh xạ kích thước (phụ thuộc vào khả năng đáp ứng）
    };
  },
  computed: {
    // Tính khoảng cách từ mỗi mục (giá trị khóa) đến đầu vùng chứa cuộn
    offsetMap({ keyProp, height, sizes, data }) {
      const res = {};
      let total = 0;
      for (let i = 0; i < data.length; i++) {
        const key = data[i][keyProp];
        res[key] = total;

        const curSize = sizes[key];
        const size = typeof curSize === 'number' ? curSize : height;
        total += size;
      }
      return res;
    },
  },
  methods: {
    // dữ liệu khởi tạo
    initData() {
      // Hiển thị dữ liệu trong phạm vi hình ảnh
      this.renderData = [];
      // Phần trên cùng và dưới cùng của phạm vi hiển thị của trang
      this.top = undefined;
      this.bottom = undefined;
      // Ghi lại chỉ mục bắt đầu và kết thúc của dữ liệu được hiển thị trong phạm vi hiển thị của trang
      this.start = 0;
      this.end = undefined;

      this.scroller = this.$el.querySelector('.el-table__body-wrapper');

      // lần thực hiện đầu tiên
      setTimeout(() => {
        this.handleScroll();
      }, 100);

      // Lắng nghe sự kiện
      this.onScroll = throttle(this.handleScroll, this.throttleTime);
      this.scroller.addEventListener('scroll', this.handleScroll);
      window.addEventListener('resize', this.onScroll);
    },

    // Cập nhật kích thước (chiều cao）
    updateSizes() {
      const rows = this.$el.querySelectorAll('.el-table__body > Tbody > .el-table__row');

      Array.from(rows).forEach((row, index) => {
        const item = this.renderData[index];
        if (!item) return;

        const key = item[this.keyProp];
        const offsetHeight = row.offsetHeight;

        if (this.sizes[key] !== offsetHeight) {
          this.$set(this.sizes, key, offsetHeight);
        }
      });
    },

    // Xử lý các sự kiện cuộn
    handleScroll(shouldUpdate = true) {
      // Cập nhật kích thước hiện tại (chiều cao）
      this.updateSizes();
      // tính toánrenderData
      this.calcRenderData();
      // Tính toán vị trí
      this.calcPosition();
      shouldUpdate && this.updatePosition();
      // sự kiện kích hoạt
      this.$emit('change', this.renderData, this.start, this.end);
    },

    // Lấy một phần dữ liệuoffsetTop
    getOffsetTop(index) {
      const item = this.data[index];
      if (item) {
        return this.offsetMap[item[this.keyProp]] || 0;
      }
      return 0;
    },

    // Lấy kích thước của một phần dữ liệu
    getSize(index) {
      const item = this.data[index];
      if (item) {
        const key = item[this.keyProp];
        return this.sizes[key] || this.height;
      }
      return this.height;
    },

    // Tính toán dữ liệu chỉ được hiển thị trên dạng xem
    calcRenderData() {
      const { scroller, data, buffer } = this;
      // Tính toán phần trên và phần dưới của phạm vi nhìn thấy được
      const top = scroller.scrollTop - buffer;
      const bottom = scroller.scrollTop + scroller.offsetHeight + buffer;

      // Phương pháp chia đôi tính toán nội dung đầu tiên ở đầu phạm vi hiển thị
      let l = 0;
      let r = data.length - 1;
      let mid = 0;
      while (l <= r) {
        mid = Math.floor((l + r) / 2);
        const midVal = this.getOffsetTop(mid);
        if (midVal < top) {
          const midNextVal = this.getOffsetTop(mid + 1);
          if (midNextVal > Top) break;
          l = mid + 1;
        } else {
          r = mid - 1;
        }
      }

      // Tính chỉ số bắt đầu và kết thúc của nội dung được hiển thị
      let start = mid;
      let end = data.length - 1;
      for (let i = start + 1; i < data.length; i++) {
        const offsetTop = this.getOffsetTop(i);
        if (offsetTop >= bottom) {
          end = i;
          break;
        }
      }

      // Chỉ số bắt đầu luôn là số chẵn. Nếu là số lẻ thì cộng thêm 1 để thành số chẵn. [Đảm bảo rằng số chẵn của các hàng trong bảng nhất quán và sẽ không làm cho mẫu ngựa vằn hiển thị không theo thứ tự.】
      if (start % 2) {
        start = start - 1;
      }
      // console.log(start, end, 'start end')

      this.top = top;
      this.bottom = bottom;
      this.start = start;
      this.end = end;
      this.renderData = data.slice(start, end + 1);
    },

    // Tính toán vị trí
    calcPosition() {
      const last = this.data.length - 1;
      // Tính tổng chiều cao nội dung
      const wrapHeight = this.getOffsetTop(last) + this.getSize(last);
      // Tính chiều cao cần đỡ ở vị trí cuộn hiện tại
      const offsetTop = this.getOffsetTop(this.start);

      // Đặt vị trí dom
      const classNames = [
        '.el-table__body-wrapper',
        '.el-table__fixed-right .el-table__fixed-body-wrapper',
        '.el-table__fixed .el-table__fixed-body-wrapper',
      ];
      classNames.forEach((className) => {
        const el = this.$el.querySelector(className);
        if (!el) return;

        // tạo nênwrapEl、innerEl
        if (!el.wrapEl) {
          const wrapEl = document.createElement('div');
          const innerEl = document.createElement('div');
          wrapEl.appendChild(innerEl);
          innerEl.appendChild(el.children[0]);
          el.insertBefore(wrapEl, el.firstChild);
          el.wrapEl = wrapEl;
          el.innerEl = innerEl;
        }

        if (el.wrapEl) {
          // Đặt chiều cao
          el.wrapEl.style.height = wrapHeight + 'px';
          // Đặt chiều cao hỗ trợ chuyển đổi
          el.innerEl.style.transform = `translateY(${offsetTop}px)`;
          // Đặt chiều cao hỗ trợ phần đệmTop
          // el.innerEl.style.paddingTop = `${offsetTop}px`
        }
      });
    },

    // Cập nhật vị trí khi không hoạt động
    updatePosition() {
      this.timer && clearTimeout(this.timer);
      this.timer = setTimeout(() => {
        this.timer && clearTimeout(this.timer);
        // Chuyển sai để tránh gọi vòng lặp
        this.handleScroll(false);
      }, this.throttleTime + 10);
    },

    // 【Cuộc gọi bên ngoài】Cập nhật
    update() {
      this.handleScroll();
    },

    // 【Cuộc gọi bên ngoài】Cuộn đến dòng nào
    scrollTo(index, stop = false) {
      const item = this.data[index];
      if (item && this.scroller) {
        this.updateSizes();
        this.calcRenderData();

        this.$nextTick(() => {
          const offsetTop = this.getOffsetTop(index);
          this.scroller.scrollTop = offsetTop;

          // Gọi rollTo hai lần. Khi cuộn lần đầu tiên, nếu chiều cao hiển thị ban đầu của hàng trong bảng thay đổi, vị trí cuộn sẽ bị lệch. Lúc này, bạn cần thực hiện cuộn lần thứ 2 để đảm bảo vị trí cuộn chính xác.
          if (!stop) {
            setTimeout(() => {
              this.scrollTo(index, true);
            }, 50);
          }
        });
      }
    },

    // 【Cuộc gọi bên ngoài】Đặt lại
    reset() {
      this.sizes = {};
      this.scrollTo(0, false);
    },
  },
  watch: {
    data() {
      this.update();
    },
  },
  created() {
    this.$nextTick(() => {
      this.initData();
    });
  },
  beforeDestroy() {
    if (this.scroller) {
      this.scroller.removeEventListener('scroll', this.onScroll);
      window.removeEventListener('resize', this.onScroll);
    }
  },
};
</script>

<style lang="less" scoped></style>
