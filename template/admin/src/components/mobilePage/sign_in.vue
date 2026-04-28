<template>
  <common_wrapper :config="configObj">
    <div
      class="signIn"
      :style="{
        background: ``,
        borderRadius: bgRadius,
      }"
    >
      <div class="signInBg acea-row row-middle row-around" v-if="styleConfig == 0">
        <div class="item">
          <img src="../../assets/images/gift4.png" />
          <div>Hôm nay</div>
        </div>
        <div class="item">
          <img src="../../assets/images/points.png" />
          <div>Thứ ba</div>
        </div>
        <div class="item">
          <img src="../../assets/images/points.png" />
          <div>Thứ Tư</div>
        </div>
        <div class="item">
          <img src="../../assets/images/gift3.png" />
          <div>Thứ năm</div>
        </div>
        <div class="item">
          <img src="../../assets/images/gift2.png" />
          <div>Thứ sáu</div>
        </div>
        <div class="item">
          <img src="../../assets/images/points.png" />
          <div>Thứ bảy</div>
        </div>
        <div class="item gift">
          <img src="../../assets/images/gift.png" />
          <div>Chủ nhật</div>
        </div>
        <div
          class="bnt"
          :style="{
            color: toneConfig ? bntTxtColor : '#fff',
            background: toneConfig ? `linear-gradient(90deg,${bntBgColorRight} 0%,${bntBgColorLeft} 100%)` : themeColor,
          }"
        >
          Đăng nhập
        </div>
      </div>
      <div class="signInBg on acea-row row-between-wrapper" v-else>
        <div class="acea-row row-middle">
          <div class="pictrue">
            <img src="../../assets/images/signInGift.png" />
          </div>
          <div>
            <div class="acea-row row-middle">
              <span class="name">Đăng nhập và nhận ngay</span>
              <div
                class="points acea-row row-center-wrapper"
                :style="{
                  color: toneConfig ? labelTxtColor : colorStyle.theme,
                  background: toneConfig ? labelBgColor : colorStyle.theme,
                }"
              >
                <div
                  class="pointsBg acea-row row-center-wrapper"
                  :style="{
                    background: toneConfig ? '' : 'rgba(255,255,255,0.9)',
                  }"
                >
                  <img src="../../assets/images/points.png" />
                  <span>+20</span>
                </div>
              </div>
            </div>
            <div class="tips">Đăng nhập 3 ngày liên tiếp và nhận 15 điểm cho các hoạt động bổ sung</div>
          </div>
        </div>
        <div
          class="bnt"
          :style="{
            color: toneConfig ? bntTxtColor : '#fff',
            background: toneConfig ? `linear-gradient(90deg,${bntBgColorRight} 0%,${bntBgColorLeft} 100%)` : themeColor,
          }"
        >
          Đăng nhập ngay bây giờ
        </div>
      </div>
    </div>
  </common_wrapper>
</template>

<script>
import { mapState, mapMutations } from 'vuex';
// import theme from "@/mixins/theme";
export default {
  name: 'sign_in',
  cname: 'Đăng nhập',
  configName: 'c_sign_in',
  icon: '#iconzujian-qiandao',
  type: 1, // 0 Thành phần cơ bản 1 Thành phần tiếp thị 2 Thành phần công cụ
  defaultName: 'signIn', // tên trận đấu bên ngoài
  props: {
    index: {
      type: null,
      default: -1,
    },
    num: {
      type: null,
    },
    colorStyle: {
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
  // mixins: [theme],
  data() {
    return {
      // Nghiêm cấm sửa đổi dữ liệu khởi tạo mặc định
      defaultConfig: {
        cname: 'Đăng nhập',
        name: 'signIn',
        timestamp: this.num,
        isHide: false,
        setUp: {
          tabVal: 0,
        },
        titleLeft: 'Cài đặt hiển thị',
        titleRight: 'Phong cách đăng nhập',
        titleCurrency: 'Phong cách phổ quát',
        styleConfig: {
          title: 'Chọn phong cách',
          tabVal: 0,
          type: 'signIn',
        },
        toneConfig: {
          title: 'giai điệu',
          tabVal: 0,
          tabList: [
            {
              name: 'Theo dõi chủ đề',
            },
            {
              name: 'Tùy chỉnh',
            },
          ],
        },
        bntBgColor: {
          title: 'nút nền',
          name: 'bntBgColor',
          default: [
            {
              item: '#FF7931',
            },
            {
              item: '#E93323',
            },
          ],
          color: [
            {
              item: '#FF7931',
            },
            {
              item: '#E93323',
            },
          ],
        },
        bntTxtColor: {
          title: 'văn bản nút',
          name: 'bntTxtColor',
          default: [
            {
              item: '#FFF',
            },
          ],
          color: [
            {
              item: '#FFF',
            },
          ],
        },
        labelBgColor: {
          title: 'nền nhãn',
          name: 'labelBgColor',
          default: [
            {
              item: '#FCEAE9',
            },
          ],
          color: [
            {
              item: '#FCEAE9',
            },
          ],
        },
        labelTxtColor: {
          title: 'văn bản nhãn',
          name: 'labelBgColor',
          default: [
            {
              item: '#E93323',
            },
          ],
          color: [
            {
              item: '#E93323',
            },
          ],
        },
        bottomBgColor: {
          title: 'nền dưới cùng',
          name: 'bgColor',
          default: [
            {
              item: '#f5f5f5',
            },
          ],
          color: [
            {
              item: '#f5f5f5',
            },
          ],
        },
        paddingConfig: {
          title: 'phần đệm',
          val: 12,
          min: 0,
          isAll: false,
          max: 100,
          valList: [{ val: 12 }, { val: 12 }, { val: 12 }, { val: 12 }],
        },
        marginConfig: {
          title: 'lề',
          val: 0,
          min: 0,
          isAll: false,
          max: 100,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        },
        fillet: {
          title: 'Nền bo tròn các góc',
          type: 0,
          list: [
            {
              val: 'Tất cả',
              icon: 'iconcaozuo-zhengti',
            },
            {
              val: 'đơn',
              icon: 'iconcaozuo-bianjiao',
            },
          ],
          valName: 'Giá trị phi lê',
          val: 0,
          min: 0,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        },
        zIndexConfig: {
          title: 'Thành phần nổi',
          val: 0,
          min: 0,
        },
        componentBgConfig: {
          title: 'Cài đặt nền',
          tabVal: 0,
          tabList: [{ name: 'màu sắc' }, { name: 'hình ảnh' }],
          colorConfig: {
            title: 'màu nền',
            default: [{ item: '#fff' }, { item: '#fff' }],
            color: [{ item: '#fff' }, { item: '#fff' }],
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
      pageData: {},
      configObj: null,
      bottomConfig: '',
      styleConfig: 0,
      toneConfig: 0,
      bntBgColorLeft: '',
      bntBgColorRight: '',
      bntTxtColor: '',
      labelBgColor: '',
      labelTxtColor: '',
      bgColorLeft: '',
      bgColorRight: '',
      bgColorLeft2: '',
      bgColorRight2: '',
      mbConfig: 0,
      bgRadius: 0,
      themeColor: '',
      zIndexConfig: null,
      componentBgConfig: null,
      borderConfig: null,
      shadowConfig: null,
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
      let dataClone = JSON.parse(JSON.stringify(data));
      for (let key in this.defaultConfig) {
        if (dataClone[key] == undefined) {
          this.$set(dataClone, key, JSON.parse(JSON.stringify(this.defaultConfig[key])));
        }
      }

      if (!dataClone.paddingConfig) {
        dataClone.paddingConfig = {
          title: 'phần đệm',
          val: 0,
          min: 0,
          max: 100,
          isAll: false,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        };
        if (dataClone.topConfig) dataClone.paddingConfig.valList[0].val = dataClone.topConfig.val;
        if (dataClone.prConfig) {
          dataClone.paddingConfig.valList[1].val = dataClone.prConfig.val;
          dataClone.paddingConfig.valList[3].val = dataClone.prConfig.val;
        }
        if (dataClone.bottomConfig) dataClone.paddingConfig.valList[2].val = dataClone.bottomConfig.val;
      }

      if (!dataClone.marginConfig) {
        dataClone.marginConfig = {
          title: 'lề',
          val: 0,
          min: 0,
          max: 100,
          isAll: false,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        };
        if (dataClone.mbConfig) dataClone.marginConfig.valList[0].val = dataClone.mbConfig.val;
      }

      this.configObj = dataClone;
      this.zIndexConfig = dataClone.zIndexConfig.val;
      this.componentBgConfig = dataClone.componentBgConfig;
      this.borderConfig = dataClone.borderConfig;
      this.shadowConfig = dataClone.shadowConfig;

      this.styleConfig = dataClone.styleConfig.tabVal;
      this.toneConfig = dataClone.toneConfig.tabVal;
      this.bntBgColorLeft = dataClone.bntBgColor.color[0].item;
      this.bntBgColorRight = dataClone.bntBgColor.color[1].item;
      this.bntTxtColor = dataClone.bntTxtColor.color[0].item;
      this.labelBgColor = dataClone.labelBgColor.color[0].item;
      this.labelTxtColor = dataClone.labelTxtColor.color[0].item;
      this.themeColor = `linear-gradient(90deg,${this.colorStyle.theme} 0%,${this.colorStyle.gradient} 100%)`;
      // this.bottomBgColor = data.bottomBgColor.color[0].item;
      let fillet = dataClone.fillet.type;
      let filletVal = dataClone.fillet.val;
      let valList = dataClone.fillet.valList;
      this.bgRadius = fillet
        ? valList[0].val + 'px ' + valList[1].val + 'px ' + valList[3].val + 'px ' + valList[2].val + 'px'
        : filletVal + 'px';
    },
  },
};
</script>

<style scoped lang="scss">
.signIn {
  width: 100%;
  // background: linear-gradient(90deg, #e93323 0%, #ff7931 100%);

  .signInBg {
    width: 100%;
    height: 70px;
    border-radius: 8px;
    // background: linear-gradient(90deg, #ffe8f5 0%, #f1fbfd 100%);
    padding: 0 3px;
    .item {
      text-align: center;
      font-size: 11px;
      color: #999999;
      padding-top: 5px;
      &.gift {
        padding-bottom: 3px;
        img {
          width: 32px;
          height: 32px;
          margin-bottom: 2px;
        }
      }
      img {
        width: 24px;
        height: 24px;
        display: block;
        margin-bottom: 6px;
      }
    }
    .bnt {
      width: 44px;
      height: 24px;
      background: linear-gradient(90deg, #e93323 0%, #ff7931 100%);
      border-radius: 12px;
      text-align: center;
      line-height: 24px;
      font-size: 12px;
      color: #fff;
    }
    &.on {
      background: #fff;
      padding: 0 10px;
      .bnt {
        width: 70px;
        height: 26px;
        background: linear-gradient(90deg, #e93323 0%, #ff7931 100%);
        border-radius: 13px;
        font-size: 12px;
        color: #ffffff;
        text-align: center;
        line-height: 26px;
      }
      .pictrue {
        width: 44px;
        height: 44px;
        margin-right: 10px;
        img {
          width: 100%;
          height: 100%;
        }
      }
      .name {
        color: #282828;
        font-size: 15px;
        font-weight: 600;
        line-height: 1;
      }
      .points {
        width: 40px;
        height: 16px;
        background: #fceae9;
        border-radius: 12px;
        font-size: 10px;
        color: #e93323;
        margin-left: 2px;

        .pointsBg {
          width: 100%;
          height: 100%;
        }

        img {
          width: 14px;
          height: 14px;
          display: block;
        }
      }
      .tips {
        color: #999999;
        font-size: 12px;
        margin-top: 6px;
      }
    }
  }
}
</style>
