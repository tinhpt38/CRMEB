<template>
  <common_wrapper :config="configObj" v-if="bgColor.length > 0">
    <div
      class="home-hot"
      :style="{
        background: boxColor,
      }"
    >
      <div class="hd">
        <p class="txt" :style="{ color: txtColor }">{{ titleTxt }}</p>
        <p class="color-txt" :style="`background: linear-gradient(90deg,${bgColor[0].item},${bgColor[1].item});`">
          {{ msgTxt }}
        </p>
      </div>
      <div class="bd">
        <div class="item" v-for="(item, index) in hotList" :key="index">
          <div class="left">
            <div class="title">{{ item.info[0].value }}</div>
            <div class="des">{{ item.info[1].value }}</div>
            <div class="link">GO！</div>
          </div>
          <div class="img-box">
            <img :src="item.img" alt="" v-if="item.img" />
            <div class="empty-box on" v-else><span class="iconfont icontupian"></span></div>
          </div>
        </div>
      </div>
    </div>
  </common_wrapper>
</template>

<script>
import { mapState } from 'vuex';
export default {
  name: 'home_hot',
  cname: 'Di chuyển khối Rubik',
  icon: 'iconhuodongmofang1',
  configName: 'c_home_hot',
  type: -1, // -1 doing 0 Thành phần cơ bản 1 Thành phần tiếp thị 2 Thành phần công cụ
  defaultName: 'activeParty', // tên trận đấu bên ngoài
  props: {
    index: {
      type: null,
    },
    num: {
      type: null,
    },
  },
  computed: {
    ...mapState('mobildConfig', ['defaultArray']),
  },
  watch: {
    pageData: {
      handler(nVal, oVal) {
        this.setConfig(nVal);
      },
      deep: true,
    },
    num: {
      handler(nVal, oVal) {
        let data = this.$store.state.mobildConfig.defaultArray[nVal];
        this.setConfig(data);
      },
      deep: true,
    },
    defaultArray: {
      handler(nVal, oVal) {
        let data = this.$store.state.mobildConfig.defaultArray[this.num];
        this.setConfig(data);
      },
      deep: true,
    },
  },
  data() {
    return {
      // Nghiêm cấm sửa đổi dữ liệu khởi tạo mặc định
      defaultConfig: {
        name: 'activeParty',
        timestamp: this.num,
        setUp: {
          tabVal: 0,
        },
        titleConfig: {
          title: 'danh hiệu khuyến mãi',
          value: 'Mẫu hot siêu giá trị',
          place: 'Vui lòng nhập tiêu đề',
          max: 10,
        },
        desConfig: {
          title: 'Giới thiệu khuyến mãi',
          value: 'Một cuộc sống tốt đẹp hơn bắt đầu từ đây',
          place: 'Vui lòng nhập phần giới thiệu',
          max: 8,
        },
        menuConfig: {
          title: 'Có thể thêm tối đa 4 phần và kích thước hình ảnh được đề xuất là 140 * 140px; thứ tự của các phần có thể được điều chỉnh bằng cách dùng chuột kéo dấu chấm bên trái.',
          maxList: 4,
          list: [
            {
              img: '',
              info: [
                {
                  title: 'tiêu đề',
                  value: 'Lời khuyên hôm nay',
                  tips: 'Tùy chọn, không quá 4 từ',
                  max: 4,
                },
                {
                  title: 'Giới thiệu',
                  value: 'Chủ sở hữu chân thành giới thiệu sản phẩm chất lượng',
                  tips: 'Tùy chọn, không quá 20 từ',
                  max: 20,
                },
                {
                  title: 'liên kết',
                  value: '',
                  tips: 'Vui lòng nhập liên kết',
                  max: 100,
                },
              ],
            },
            {
              img: '',
              info: [
                {
                  title: 'tiêu đề',
                  value: 'Danh sách phổ biến',
                  tips: 'Tùy chọn, không quá 4 từ',
                  max: 4,
                },
                {
                  title: 'Giới thiệu',
                  value: 'Chủ sở hữu chân thành giới thiệu sản phẩm chất lượng',
                  tips: 'Tùy chọn, không quá 20 từ',
                  max: 20,
                },
                {
                  title: 'liên kết',
                  value: '',
                  tips: 'Vui lòng nhập liên kết',
                  max: 100,
                },
              ],
            },
            {
              img: '',
              info: [
                {
                  title: 'tiêu đề',
                  value: 'Sản phẩm mới đầu tiên',
                  tips: 'Tùy chọn, không quá 4 từ',
                  max: 4,
                },
                {
                  title: 'Giới thiệu',
                  value: 'Sản phẩm mới đã lên kệ chờ bạn rinh về',
                  tips: 'Tùy chọn, không quá 20 từ',
                  max: 20,
                },
                {
                  title: 'liên kết',
                  value: '',
                  tips: 'Vui lòng nhập liên kết',
                  max: 100,
                },
              ],
            },
            {
              img: '',
              info: [
                {
                  title: 'tiêu đề',
                  value: 'Mặt hàng khuyến mại',
                  tips: 'Tùy chọn, không quá 4 từ',
                  max: 4,
                },
                {
                  title: 'Giới thiệu',
                  value: 'Lựa chọn toàn diện các sản phẩm tốt',
                  tips: 'Tùy chọn, không quá 20 từ',
                  max: 20,
                },
                {
                  title: 'liên kết',
                  value: '',
                  tips: 'Vui lòng nhập liên kết',
                  max: 100,
                },
              ],
            },
          ],
        },
        themeColor: {
          title: 'màu chủ đề',
          name: 'themeColor',
          default: [
            {
              item: '#fc3c3e',
            },
          ],
          color: [
            {
              item: '#fc3c3e',
            },
          ],
        },
        bgColor: {
          title: 'Màu nền nhãn',
          name: 'bgColor',
          default: [
            {
              item: '#F62C2C',
            },
            {
              item: '#F96E29',
            },
          ],
          color: [
            {
              item: '#F62C2C',
            },
            {
              item: '#F96E29',
            },
          ],
        },
        boxColor: {
          title: 'màu nền',
          name: 'boxColor',
          default: [
            {
              item: '#ffe5e3',
            },
          ],
          color: [
            {
              item: '#ffe5e3',
            },
          ],
        },
        titleCurrency: 'Phong cách phổ quát',
        bottomBgColor: {
          title: 'nền dưới cùng',
          default: [
            {
              item: '#F5F5F5',
            },
          ],
          color: [
            {
              item: '#F5F5F5',
            },
          ],
        },
        paddingConfig: {
          title: 'phần đệm',
          val: 0,
          min: 0,
          max: 100,
          isAll: false,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        },
        marginConfig: {
          title: 'lề',
          val: 0,
          min: 0,
          max: 100,
          isAll: false,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        },
        componentBgConfig: {
          title: 'Cài đặt nền',
          tabVal: 0,
          tabList: [{ name: 'màu sắc' }, { name: 'hình ảnh' }],
          colorConfig: {
            title: 'màu nền',
            default: [{ item: '#FFFFFF' }, { item: '#FFFFFF' }],
            color: [{ item: '#FFFFFF' }, { item: '#FFFFFF' }],
          },
          colorDirection: {
            title: 'Hướng dốc',
            tabVal: 0,
            tabList: [{ name: 'Nằm ngang' }, { name: 'chân dung' }, { name: 'xiên trái' }, { name: 'Nghiêng phải' }],
          },
          imageConfig: {
            header: 'hình nền',
            title: '',
            name: 'Tải ảnh lên',
            type: 'code',
            url: '',
            info: 'Kích thước đề xuất：750px * 400px',
          },
        },
        zIndexConfig: {
          title: 'Thành phần nổi',
          val: 0,
          min: 0,
        },
        borderConfig: {
          title: 'Cài đặt đường viền',
          tabVal: 0,
          tabList: [{ name: 'trốn' }, { name: 'trình diễn' }],
          val: 0, // 0: Hide, 1: Show
          styleConfig: {
            title: 'phong cách biên giới',
            tabVal: 0,
            tabList: [
              { name: 'đường liền nét', style: 'solid' },
              { name: 'đường chấm chấm', style: 'dashed' },
              { name: 'Say mê', style: 'dotted' },
            ],
          },
          widthConfig: {
            title: 'Độ dày viền',
            val: 1,
            min: 1,
          },
          colorConfig: {
            title: 'màu viền',
            default: [{ item: '#e5e5e5' }],
            color: [{ item: '#e5e5e5' }],
          },
        },
        shadowConfig: {
          title: 'Cài đặt bóng',
          tabVal: 0,
          tabList: [{ name: 'trốn' }, { name: 'trình diễn' }],
          val: 0, // 0: Off, 1: On
          colorConfig: {
            title: 'màu bóng',
            default: [{ item: 'rgba(0,0,0,0.1)' }],
            color: [{ item: 'rgba(0,0,0,0.1)' }],
          },
          xConfig: {
            title: 'Xđộ lệch trục',
            val: 0,
            min: -50,
          },
          yConfig: {
            title: 'Yđộ lệch trục',
            val: 0,
            min: -50,
          },
          blurConfig: {
            title: 'bán kính lờ mờ',
            val: 10,
            min: 0,
          },
          spreadConfig: {
            title: 'Bán kính mở rộng',
            val: 0,
            min: -50,
          },
        },
      },
      titleTxt: '',
      msgTxt: '',
      slider: '',
      hotList: [],
      txtColor: '',
      bgColor: [],
      pageData: {},
      configObj: {},
      boxColor: '',
    };
  },
  mounted() {
    this.$nextTick(() => {
      this.pageData = this.$store.state.mobildConfig.defaultArray[this.num];
      this.setConfig(this.pageData);
    });
  },
  methods: {
    setConfig(data) {
      if (!data) return;
      this.configObj = data;
      for (let key in this.defaultConfig) {
        if (data[key] == undefined) {
          this.$set(data, key, JSON.parse(JSON.stringify(this.defaultConfig[key])));
        }
      }
      this.titleTxt = data.titleConfig.value;
      this.msgTxt = data.desConfig.value;
      this.slider = data.mbConfig ? data.mbConfig.val : 0;
      this.hotList = data.menuConfig.list;
      this.txtColor = data.themeColor.color[0].item;
      this.bgColor = data.bgColor.color;
      this.boxColor = data.boxColor.color[0].item;
      // this.bottomBgColor = data.bottomBgColor ? data.bottomBgColor.color[0].item : '#F5F5F5';
      if (!this.configObj.paddingConfig) {
        this.$set(this.configObj, 'paddingConfig', {
          title: 'phần đệm',
          isAll: false,
          val: 0,
          min: 0,
          max: 100,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        });
      }
      if (!this.configObj.marginConfig) {
        this.$set(this.configObj, 'marginConfig', {
          title: 'lề',
          isAll: false,
          val: 0,
          min: 0,
          max: 100,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        });
        if (data.mbConfig) {
          this.configObj.marginConfig.valList[0].val = data.mbConfig.val;
        }
      }
      for (let key in this.defaultConfig) {
        if (this.configObj[key] === undefined) {
          this.$set(this.configObj, key, JSON.parse(JSON.stringify(this.defaultConfig[key])));
        }
      }
    },
  },
};
</script>

<style scoped lang="scss">
.paddingBox {
  padding: 0 10px !important;
}

.home-hot {
  padding: 15px 10px;
  background: #ffe5e3;
  border-radius: 12px;

  .hd {
    display: flex;
    align-items: center;

    .txt {
      margin-right: 10px;
      color: #fc3c3e;
      font-size: 16px;
      font-weight: bold;
    }

    .color-txt {
      width: 110px;
      height: 18px;
      border-radius: 13px 0 13px 0;
      color: #fff;
      text-align: center;
      font-size: 11px;
      box-shadow: 3px 1px 1px 1px rgba(255, 203, 199, 0.8);
    }
  }

  .bd {
    display: flex;
    flex-wrap: wrap;

    .item {
      display: flex;
      width: 158px;
      margin-top: 10px;
      margin-right: 13px;
      padding: 10px;
      background: #fff;
      border-radius: 8px;

      &:nth-child(2n) {
        margin-right: 0;
      }

      .left {
        width: 69px;

        .title {
          font-size: 14px;
        }

        .des {
          font-size: 12px;
          color: #999999;
        }

        .link {
          width: 56px;
          height: 18px;
          padding: 0 10px;
          margin-top: 3px;
          background: linear-gradient(90deg, #4bc4ff, #207eff 100%);
          border-radius: 9px;
          color: #fff;
          font-size: 13px;
        }
      }

      .img-box {
        flex: 1;

        img {
          width: 100%;
          height: 100%;
        }

        .box {
          width: 100%;
          height: 100%;
          background: #d8d8d8;
        }
      }

      &:nth-child(2) .left .link {
        background: linear-gradient(90deg, #ff9043, #ff531d 100%);
      }

      &:nth-child(3) .left .link {
        background: linear-gradient(90deg, #96e187, #48ce2c 100%);
      }

      &:nth-child(4) .left .link {
        background: linear-gradient(90deg, #ffc560, #ff9c00 100%);
      }
    }
  }
}
</style>
