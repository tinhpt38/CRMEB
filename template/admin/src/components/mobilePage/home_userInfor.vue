<template>
  <common_wrapper :config="configObj">
    <div
      v-if="styleConfig"
      class="userInfor acea-row row-between-wrapper"
      :style="{
        background: `linear-gradient(90deg,${bgColorLeft} 0%,${bgColorRight} 100%)`,
        borderRadius: bgRadius,
      }"
    >
      <div class="left acea-row row-middle">
        <div class="pictrue acea-row row-center-wrapper">
          <img :src="logoConfig" v-if="logoConfig" />
          <div class="empty-box" v-else>
            <img src="../../assets/images/shan.png" />
          </div>
        </div>
        <div class="text">
          <div class="name acea-row row-middle">Tên người dùng<img src="../../assets/images/vip-diy.png" /></div>
          <div class="acea-row row-middle">
            <div
              class="progress"
              :style="{
                background: toneConfig ? progressBgColor : '#eee',
              }"
            >
              <div
                class="bgReds"
                :style="{
                  background: toneConfig
                    ? `linear-gradient(90deg,${progressLeft} 0%,${progressRight} 100%)`
                    : themeColor,
                }"
              ></div>
            </div>
            <div class="percent">3000/1000000</div>
          </div>
          <!--<div class="phone"><span class="iconfont iconshouji"></span>13000000000</div>-->
        </div>
      </div>
      <div class="right acea-row row-bottom">
        <div class="item" v-if="checkType.slice(0, 3).indexOf(1) != -1">
          <div class="num">20</div>
          <div>tích phân</div>
        </div>
        <div class="item" v-if="checkType.slice(0, 3).indexOf(2) != -1">
          <div class="num">200</div>
          <div>Sự cân bằng</div>
        </div>
        <div class="item" v-if="checkType.slice(0, 3).indexOf(0) != -1">
          <div class="num">2</div>
          <div>Phiếu giảm giá</div>
        </div>
        <div class="item" v-if="checkType.slice(0, 3).indexOf(4) != -1">
          <div class="num">80</div>
          <div>sưu tầm</div>
        </div>
        <div class="item" v-if="checkType.slice(0, 3).indexOf(5) != -1">
          <div class="num">80</div>
          <div>Duyệt qua</div>
        </div>
      </div>
    </div>
    <div
      v-else
      class="userInfor"
      :style="{
        background: `linear-gradient(90deg,${bgColorLeft} 0%,${bgColorRight} 100%)`,
        borderRadius: bgRadius,
      }"
    >
      <div class="acea-row row-between-wrapper">
        <div class="left acea-row row-middle">
          <div class="pictrue acea-row row-center-wrapper">
            <img :src="logoConfig" v-if="logoConfig" />
            <div class="empty-box" v-else>
              <img src="../../assets/images/shan.png" />
            </div>
          </div>
          <div class="text">
            <div class="name acea-row row-middle">Tên người dùng<img src="../../assets/images/vip-diy.png" /></div>
            <div class="acea-row row-middle">
              <div
                class="progress"
                :style="{
                  background: toneConfig ? progressBgColor : '#eee',
                }"
              >
                <div
                  class="bgReds"
                  :style="{
                    background: toneConfig
                      ? `linear-gradient(90deg,${progressLeft} 0%,${progressRight} 100%)`
                      : themeColor,
                  }"
                ></div>
              </div>
              <div class="percent">3000/1000000</div>
            </div>
            <!--<div class="phone"><span class="iconfont iconshouji"></span>13000000000</div>-->
          </div>
        </div>
      </div>
      <div class="list acea-row row-around">
        <div class="item" v-if="checkType.indexOf(1) != -1">
          <div>tích phân<span class="num">20000</span></div>
        </div>
        <div class="item" v-if="checkType.indexOf(2) != -1">
          <div>Sự cân bằng<span class="num">200</span></div>
        </div>
        <div class="item" v-if="checkType.indexOf(0) != -1">
          <div>Phiếu giảm giá<span class="num">2</span></div>
        </div>
        <div class="item" v-if="checkType.indexOf(4) != -1">
          <div>sưu tầm<span class="num">80</span></div>
        </div>
        <div class="item" v-if="checkType.indexOf(5) != -1">
          <div>Duyệt qua<span class="num">80</span></div>
        </div>
      </div>
    </div>
  </common_wrapper>
</template>

<script>
import { mapState, mapMutations } from 'vuex';
// import theme from "@/mixins/theme";
export default {
  name: 'home_userInfor',
  cname: 'Thông tin người dùng',
  configName: 'c_userInfor',
  icon: '#iconzujian-yonghuxinxi',
  type: 0, // 0 Thành phần cơ bản 1 Thành phần tiếp thị 2 Thành phần công cụ
  defaultName: 'userInfor', // tên trận đấu bên ngoài
  props: {
    index: {
      type: null,
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
      configObj: null,
      // Nghiêm cấm sửa đổi dữ liệu khởi tạo mặc định
      defaultConfig: {
        cname: 'Thông tin người dùng',
        name: 'userInfor',
        timestamp: this.num,
        isHide: false,
        setUp: {
          tabVal: 0,
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
        titleLeft: 'Cài đặt hiển thị',
        titleImg: 'Hình đại diện mặc định',
        titleRight: 'thanh tiến trình',
        titleCurrency: 'Phong cách phổ quát',
        styleConfig: {
          title: 'Chọn phong cách',
          tabVal: 1,
          tabList: [
            {
              name: 'phong cách một',
            },
            {
              name: 'Phong cách 2',
            },
          ],
        },
        checkboxInfo: {
          title: 'Có hiển thị hay không',
          name: 'checkboxInfo',
          userType: 1,
          type: [1, 2],
          list: [
            {
              id: 1,
              name: 'tích phân',
            },
            {
              id: 2,
              name: 'Sự cân bằng',
            },
            {
              id: 4,
              name: 'sưu tầm',
            },
            {
              id: 0,
              name: 'Phiếu giảm giá',
            },
            {
              id: 5,
              name: 'Duyệt qua',
            },
          ],
        },
        logoConfig: {
          info: 'Gợi ý: Kích thước hình ảnh90px * 90px',
          url: '',
          type: 'code',
          delType: 1,
          name: 'Tải ảnh lên',
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
        progressColor: {
          title: 'thanh tiến trình',
          default: [
            {
              item: '#e93323',
            },
            {
              item: '#ff8933',
            },
          ],
          color: [
            {
              item: '#e93323',
            },
            {
              item: '#ff8933',
            },
          ],
        },
        progressBgColor: {
          title: 'nền thanh tiến trình',
          default: [
            {
              item: '#EEEEEE',
            },
          ],
          color: [
            {
              item: '#EEEEEE',
            },
          ],
        },
        moduleColor: {
          title: 'Nền thành phần',
          default: [
            {
              item: '#fff',
            },
            {
              item: '#fff',
            },
          ],
          color: [
            {
              item: '#fff',
            },
            {
              item: '#fff',
            },
          ],
        },
        bottomBgColor: {
          title: 'nền dưới cùng',
          default: [
            {
              item: '#fff',
            },
          ],
          color: [
            {
              item: '#fff',
            },
          ],
        },
        topConfig: {
          title: 'lề trên',
          val: 0,
          min: 0,
        },
        bottomConfig: {
          title: 'lề dưới',
          val: 0,
          min: 0,
        },
        prConfig: {
          title: 'lề trái và lề phải',
          val: 0,
          min: 0,
        },
        mbConfig: {
          title: 'khoảng cách trang',
          val: 0,
          min: 0,
        },
        fillet: {
          title: 'Nền bo tròn các góc',
          type: 0,
          list: [
            {
              val: 'tất cả',
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
      },
      pageData: {},
      styleConfig: 0,
      checkType: [],
      logoConfig: '',
      progressLeft: '',
      progressRight: '',
      progressBgColor: '',
      bgColorLeft: '',
      bgColorRight: '',
      bottomBgColor: '',
      topConfig: 0,
      bottomConfig: 0,
      prConfig: 0,
      mTop: '',
      bgRadius: 0,
      toneConfig: 0,
      themeColor: '',
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
        if (data[key] === undefined) {
          this.$set(data, key, JSON.parse(JSON.stringify(this.defaultConfig[key])));
        }
      }
      this.paddingConfig = data.paddingConfig || {
        title: 'phần đệm',
        isAll: false,
        val: 0,
        min: 0,
        valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
      };
      this.marginConfig = data.marginConfig || {
        title: 'lề',
        isAll: false,
        val: 0,
        min: 0,
        valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
      };
      if (!data.paddingConfig) {
        if (data.topConfig) this.paddingConfig.valList[0].val = data.topConfig.val;
        if (data.bottomConfig) this.paddingConfig.valList[2].val = data.bottomConfig.val;
        if (data.prConfig) {
          this.paddingConfig.valList[1].val = data.prConfig.val;
          this.paddingConfig.valList[3].val = data.prConfig.val;
        }
      }
      if (!data.marginConfig) {
        if (data.mbConfig) this.marginConfig.valList[0].val = data.mbConfig.val;
      }
      this.styleConfig = data.styleConfig.tabVal;
      this.checkType = data.checkboxInfo.type;
      this.logoConfig = data.logoConfig.url;
      this.toneConfig = data.toneConfig.tabVal;
      this.progressLeft = data.progressColor.color[0].item;
      this.progressRight = data.progressColor.color[1].item;
      this.progressBgColor = data.progressBgColor.color[0].item;
      this.bgColorLeft = data.moduleColor.color[0].item;
      this.bgColorRight = data.moduleColor.color[1].item;
      this.themeColor = `linear-gradient(90deg,${this.colorStyle.theme} 0%,${this.colorStyle.gradient} 100%)`;
      // this.bottomBgColor = data.bottomBgColor.color[0].item;
      // this.topConfig = data.topConfig.val;
      // this.bottomConfig = data.bottomConfig.val;
      // this.prConfig = data.prConfig.val;
      // this.mTop = data.mbConfig.val;
      let fillet = data.fillet.type;
      let filletVal = data.fillet.val;
      let valList = data.fillet.valList;
      this.bgRadius = fillet
        ? valList[0].val + 'px ' + valList[1].val + 'px ' + valList[3].val + 'px ' + valList[2].val + 'px'
        : filletVal + 'px';
    },
  },
};
</script>

<style scoped lang="scss">
.userInfor {
  padding: 14px 10px;
  .list {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #eee;
    .item {
      font-size: 13px;
      color: #999;
      .num {
        font-size: 14px;
        font-weight: 600;
        color: #333333;
        margin-left: 4px;
      }
    }
  }
  .right {
    &.on {
      .item {
        color: #666666;
      }
    }
    .item {
      text-align: center;
      font-weight: 400;
      color: #999;
      font-size: 11px;
      margin-left: 16px;
      .num {
        font-size: 16px;
        margin-bottom: 2px;
        font-weight: 600;
        color: #333333;
      }
    }
    .iconfont {
      font-weight: 400;
      //color: #333333;
      font-size: 15px;
      margin-bottom: 2px;
    }
  }
  .left {
    .pictrue {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      margin-right: 10px;
      img {
        border-radius: 50%;
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
      .empty-box {
        border-radius: 50%;
        background-color: #f3f9ff;
        border: 1px solid #eeeeee;
        .iconfont {
          font-size: 20px;
        }
        img {
          width: 26px;
          height: 20px;
        }
      }
    }
    .text {
      font-weight: 400;
      color: #333333;
      font-size: 16px;
      .name {
        img {
          width: 38px;
          height: 16px;
          margin-left: 5px;
        }
      }
      .progress {
        overflow: hidden;
        background-color: #eeeeee;
        width: 60px;
        height: 7px;
        border-radius: 3px;
        position: relative;
        .bgReds {
          width: 80%;
          height: 100%;
          transition: width 0.6s ease;
          border-radius: 3px;
        }
      }
      .percent {
        font-size: 10px;
        margin-left: 4px;
      }
      .phone {
        font-weight: 400;
        //color: #333;
        font-size: 10px;
        margin-top: 3px;
        .iconshouji {
          margin-right: 2px;
          font-size: 12px;
        }
      }
    }
  }
}
</style>
