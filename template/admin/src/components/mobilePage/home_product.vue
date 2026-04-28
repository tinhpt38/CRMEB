<template>
  <common_wrapper :config="configObj">
    <div class="home_product">
      <div class="hd_nav" v-if="styleConfig == 0">
        <div class="item" :class="index == tabCur ? 'active' : ''" v-for="(item, index) in navlist" :key="index">
          <p
            class="title"
            :style="{ color: index == tabCur ? (toneConfig ? textColor2 : colorStyle.theme) : '#282828' }"
          >
            {{ item.chiild[0].val || 'tiêu đề' }}
          </p>
          <span
            class="label"
            :style="{ background: index == tabCur ? (toneConfig ? decorateColor : themeColor) : '' }"
            v-if="item.chiild[1].val"
            >{{ item.chiild[1].val || 'Tiêu đề giới thiệu' }}</span
          >
        </div>
      </div>
      <div class="menus" :class="styleConfig == 2 ? 'on' : ''" v-else>
        <template v-if="styleConfig == 1">
          <div
            class="item"
            :class="index == tabCur ? 'on' : ''"
            v-for="(item, index) in navlist"
            :key="index"
            :style="{
              color: index == tabCur ? (toneConfig ? textColor : '#333') : '#282828',
            }"
          >
            {{ item.chiild[0].val || 'tiêu đề'
            }}<span
              :style="{
                background: toneConfig ? decorateColor : themeColor,
              }"
            ></span>
          </div>
        </template>
        <template v-if="styleConfig == 2">
          <div
            class="item"
            :class="index == tabCur ? 'on3' : ''"
            v-for="(item, index) in navlist"
            :key="index"
            :style="{
              color: index == tabCur ? (toneConfig ? textColor2 : colorStyle.theme) : '#282828',
            }"
          >
            {{ item.chiild[0].val || 'tiêu đề'
            }}<span
              :style="{
                borderColor: toneConfig ? decorateColor2 : colorStyle.theme,
              }"
            ></span>
          </div>
        </template>
        <template v-if="styleConfig == 3">
          <div
            class="item"
            :class="index == tabCur ? 'on2' : ''"
            v-for="(item, index) in navlist"
            :key="index"
            :style="{
              color: index == tabCur ? (toneConfig ? textColor3 : '#fff') : '#282828',
              background: index == tabCur ? (toneConfig ? decorateColor : themeColor) : '',
            }"
          >
            {{ item.chiild[0].val || 'tiêu đề' }}
          </div>
        </template>
        <template v-if="styleConfig == 4">
          <div class="item pic" v-for="(item, index) in navlist" :key="index">
            <div
              class="pictrue acea-row row-center-wrapper"
              :style="{
                borderColor: index == tabCur ? (toneConfig ? decorateColorLeft : colorStyle.theme) : '#EEEEEE',
              }"
            >
              <img class="img" :src="item.image" v-if="item.image" />
              <img src="../../assets/images/shan.png" v-else />
            </div>
            <div
              class="title"
              :style="{
                color: index == tabCur ? (toneConfig ? textColor3 : '#fff') : '#282828',
                background: index == tabCur ? (toneConfig ? decorateColor : themeColor) : '',
              }"
            >
              {{ item.chiild[0].val || 'tiêu đề' }}
            </div>
          </div>
        </template>
      </div>
      <div class="list-wrapper">
        <div class="item" v-for="(item, index) in list" :key="index">
          <div class="img-box">
            <img
              class="img"
              v-if="item.image"
              :src="item.image"
              alt=""
              :style="{
                borderRadius: bgRadius,
              }"
            />
            <div
              v-else
              class="empty-box"
              :style="{
                borderRadius: bgRadius,
              }"
            >
              <img src="../../assets/images/shan.png" />
            </div>
          </div>
          <div class="info" :style="{ borderRadius: bgRadius2 }">
            <div class="title line2">
              {{ item.store_name || 'Đây là khu vực hiển thị tên sản phẩm,Khu vực hiển thị tên sản phẩm,Khu vực hiển thị tên sản phẩm' }}
            </div>
            <div class="pictrue">
              <img src="../../assets/images/goods01.png" />
            </div>
            <div class="price">
              <div class="num" :style="{ color: goodsPriceColor }">
                <span>￥</span>{{ item.price ? $HandlePrice(item.price, 0) : 77
                }}<span>{{ item.price ? $HandlePrice(item.price, 1) : '' }}</span>
              </div>
              <img src="../../assets/images/goods02.png" />
            </div>
            <div class="sales">Đã bán{{ item.sales || 0 }}miếng</div>
          </div>
          <div
            class="jia"
            v-if="!cartConfig"
            :style="{
              background: toneCartConfig ? bntBgColor : themeColor,
            }"
          >
            <div class="jiaCon">
              <span class="iconfont iconjiahao1" v-if="bntStyleConfig == 0"></span>
              <span class="iconfont icongouwuche1" v-else></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </common_wrapper>
</template>

<script>
import { mapState } from 'vuex';
// import theme from "@/mixins/theme";
export default {
  name: 'home_product',
  cname: 'Tab sản phẩm',
  configName: 'c_home_product',
  desc: 'Tab sản phẩm',
  icon: '#iconzujian-shangpinxuanxiangka',
  type: 0, // 0 Thành phần cơ bản 1 Thành phần tiếp thị 2 Thành phần công cụ
  defaultName: 'promotionList', // tên trận đấu bên ngoài
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
        const data = this.$store.state.mobildConfig.defaultArray[nVal];
        this.setConfig(data);
      },
      deep: true,
    },
    defaultArray: {
      handler(nVal, oVal) {
        const data = this.$store.state.mobildConfig.defaultArray[this.num];
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
        cname: 'Tab sản phẩm',
        desc: 'Tab sản phẩm',
        name: 'promotionList',
        timestamp: this.num,
        isHide: false,
        setUp: {
          tabVal: 0,
        },
        titleLeft: 'Cài đặt hiển thị',
        titleTab: 'Cài đặt tab',
        titleRight: 'Kiểu tab',
        titleCurrency: 'Phong cách phổ quát',
        titleCart: 'nút giỏ hàng',
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
            {
              name: 'phong cách ba',
            },
            {
              name: 'phong cách bốn',
            },
            {
              name: 'phong cách năm',
            },
          ],
        },
        slideConfig: {
          title: 'Trượt lên trên cùng',
          tabVal: 1,
          tabList: [
            {
              name: 'cho phép',
            },
            {
              name: 'Chưa bật',
            },
          ],
        },
        tabConfig: {
          title: 'Nhấp vào tab bên dưới để chỉnh sửa; kéo phần bằng chuột để điều chỉnh thứ tự',
          max: '',
          tabCur: 0,
          classList: [],
          list: [
            {
              chiild: [
                {
                  title: 'tiêu đề',
                  val: 'Sản phẩm mới đầu tiên',
                  max: 4,
                  pla: 'Tùy chọn, không quá bốn từ',
                },
                {
                  title: 'Giới thiệu',
                  val: 'Phát hành mới nhất',
                  max: 4,
                  pla: 'Tùy chọn, không quá bốn từ',
                },
              ],
              image: '',
              tabVal: 1,
              brandConfig: {
                brandVal: [],
              },
              selectConfig: {
                activeValue: [],
              },
              goodsLabel: {
                activeValue: [],
                list: [],
              },
              goodsSort: 0,
              numConfig: {
                val: 6,
              },
              goodsList: {
                max: 20,
                list: [],
              },
              productList: {
                list: [],
              },
            },
          ],
        },
        cartConfig: {
          title: 'Có hiển thị hay không',
          tabVal: 0,
          tabList: [
            {
              name: 'trình diễn',
            },
            {
              name: 'trốn',
            },
          ],
        },
        bntConfig: {
          title: 'Hiệu ứng nút',
          tabVal: 1,
          tabList: [
            {
              name: 'Nhập trang chi tiết sản phẩm',
            },
            {
              name: 'Mua thêm sản phẩm',
            },
          ],
        },
        bntStyleConfig: {
          typeFrom: 'bnt',
          title: 'kiểu nút',
          tabVal: 0,
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
        goodsPriceColor: {
          title: 'Giá sản phẩm',
          name: 'goodsPriceColor',
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
        decorateColor: {
          title: 'yếu tố trang trí',
          default: [
            {
              item: '#E93323',
            },
            {
              item: '#FF7931',
            },
          ],
          color: [
            {
              item: '#E93323',
            },
            {
              item: '#FF7931',
            },
          ],
        },
        decorateColor2: {
          title: 'yếu tố trang trí',
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
        textColor: {
          title: 'Chọn văn bản',
          default: [
            {
              item: '#333333',
            },
          ],
          color: [
            {
              item: '#333333',
            },
          ],
        },
        textColor2: {
          title: 'Chọn văn bản',
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
        textColor3: {
          title: 'Chọn văn bản',
          default: [
            {
              item: '#FFFFFF',
            },
          ],
          color: [
            {
              item: '#FFFFFF',
            },
          ],
        },
        toneCartConfig: {
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
          title: 'màu nút',
          name: 'bntBgColor',
          default: [
            {
              item: '#E93323',
            },
            {
              item: '#FF7931',
            },
          ],
          color: [
            {
              item: '#E93323',
            },
            {
              item: '#FF7931',
            },
          ],
        },
        bottomBgColor: {
          title: 'nền dưới cùng',
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
          val: 0,
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
          val: 0,
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
        fillet: {
          title: 'Nền bo tròn các góc',
          type: 0,
          list: [
            { val: 'Tất cả', icon: 'iconcaozuo-zhengti' },
            { val: 'đơn', icon: 'iconcaozuo-bianjiao' },
          ],
          valName: 'Giá trị phi lê',
          val: 0,
          min: 0,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        },
      },
      configObj: null,
      navlist: [],
      imgStyle: '',
      tabCur: 0,
      list: [],
      pageData: {},
      styleConfig: 0,
      toneConfig: 0,
      textColor: '',
      textColor2: '',
      textColor3: '',
      decorateColor: '',
      decorateColor2: '',
      decorateColorLeft: '',
      // bgColor:'',
      bottomBgColor: '',
      topConfig: 0,
      bottomConfig: 0,
      prConfig: 0,
      bgRadius: 0,
      bgRadius2: 0,
      themeColor: '',
      cartConfig: 0,
      toneCartConfig: 0,
      bntBgColor: '',
      bntStyleConfig: 0,
      goodsPriceColor: '',
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
      for (let key in this.defaultConfig) {
        if (data[key] == undefined) {
          this.$set(data, key, JSON.parse(JSON.stringify(this.defaultConfig[key])));
        }
      }
      this.styleConfig = data.styleConfig.tabVal;
      this.cartConfig = data.cartConfig.tabVal;
      this.bntStyleConfig = data.bntStyleConfig.tabVal;
      this.toneCartConfig = data.toneCartConfig.tabVal;
      let bntBgColorLeft = data.bntBgColor.color[0].item;
      let bntBgColorRight = data.bntBgColor.color[1].item;
      this.bntBgColor = `linear-gradient(90deg,${bntBgColorLeft} 0%,${bntBgColorRight} 100%)`;
      this.toneConfig = data.toneConfig.tabVal;
      this.textColor = data.textColor.color[0].item;
      this.textColor2 = data.textColor2.color[0].item;
      this.textColor3 = data.textColor3.color[0].item;
      let decorateColorLeft = data.decorateColor.color[0].item;
      let decorateColorRight = data.decorateColor.color[1].item;
      this.decorateColorLeft = decorateColorLeft;
      this.goodsPriceColor = this.toneCartConfig ? data.goodsPriceColor.color[0].item : '#E93323';
      this.decorateColor = `linear-gradient(90deg,${decorateColorLeft} 0%,${decorateColorRight} 100%)`;
      this.decorateColor2 = data.decorateColor2.color[0].item;
      this.themeColor = `linear-gradient(90deg,${this.colorStyle.theme} 0%,${this.colorStyle.gradient} 100%)`;
      // let bgColorLeft =  data.moduleColor.color[0].item;
      // let bgColorRight =  data.moduleColor.color[1].item;
      // this.bgColor = `linear-gradient(90deg,${bgColorLeft} 0%,${bgColorRight} 100%)`;
      this.bottomBgColor = data.bottomBgColor.color[0].item;
      this.configObj = data;
      // Tương thích với dữ liệu cũ
      if (!data.paddingConfig) {
        let paddingConfig = {
          title: 'phần đệm',
          isAll: false,
          val: 0,
          min: 0,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        };
        if (data.topConfig) paddingConfig.valList[0].val = data.topConfig.val;
        if (data.prConfig) {
          paddingConfig.valList[1].val = data.prConfig.val;
          paddingConfig.valList[3].val = data.prConfig.val;
        }
        if (data.bottomConfig) paddingConfig.valList[2].val = data.bottomConfig.val;
        this.$set(this.configObj, 'paddingConfig', paddingConfig);
      }
      if (!data.marginConfig) {
        let marginConfig = {
          title: 'lề',
          isAll: false,
          val: 0,
          min: 0,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        };
        if (data.mbConfig) marginConfig.valList[0].val = data.mbConfig.val;
        this.$set(this.configObj, 'marginConfig', marginConfig);
      }
      let fillet = data.fillet.type;
      let filletVal = data.fillet.val;
      let valList = data.fillet.valList;
      this.bgRadius = fillet
        ? valList[0].val + 'px ' + valList[1].val + 'px 0 0'
        : filletVal + 'px ' + filletVal + 'px 0 0';
      this.bgRadius2 = fillet
        ? '0 0 ' + valList[3].val + 'px ' + valList[2].val + 'px'
        : '0 0 ' + filletVal + 'px ' + filletVal + 'px';
      this.navlist = data.tabConfig.list;
      this.tabCur = data.tabConfig.tabCur || 0;
      let goods = data.tabConfig.list[this.tabCur];
      if (goods.tabVal == 1 && goods.goodsList.list) {
        this.list = goods.goodsList.list.length ? goods.goodsList.list : 2;
      } else if (goods.goodsList.list) {
        this.list = goods.productList.list.length ? goods.productList.list : 2;
      } else {
        this.list = goods.productList.list.length ? goods.productList.list : 2;
      }
    },
  },
};
</script>

<style scoped lang="scss">
.mobile-page {
  display: inline-block;
  width: -webkit-fill-available;
}
.menus {
  display: flex;
  align-items: center;
  width: 10000%;
  cursor: pointer;
  padding-left: 12px;
  padding: 9px 0;

  &.on {
    padding-bottom: 14px;
  }

  .title {
    color: #333;
    font-size: 12px;
    margin-top: 4px;
    height: 20px;
    border-radius: 20px;
    padding: 0 5px;
    line-height: 20px;
    text-align: center;
    white-space: nowrap;
  }

  .item {
    position: relative;
    color: #333;
    font-size: 14px;
    margin-right: 28px;
    z-index: 9;

    &.pic {
      margin-right: 15px;
    }

    .pictrue {
      width: 46px;
      height: 46px;
      border: 1px solid #eeeeee;
      border-radius: 50%;
      background: #f3f9ff;
      margin: 0 auto;

      .img {
        width: 42px;
        height: 42px;
        border-radius: 50%;
      }

      img {
        width: 27px;
      }
    }

    &.on {
      font-size: 16px;
      font-weight: 600;

      span {
        display: block;
        position: absolute;
        left: 50%;
        bottom: 4px;
        width: 100%;
        height: 4px;
        border-radius: 100px;
        transform: translateX(-50%);
        background: #fff;
        background: linear-gradient(90deg, #e93323 0%, #ff7931 100%);
        z-index: -1;
      }
    }

    &.on2 {
      height: 24px;
      text-align: center;
      line-height: 24px;
      color: #fff;
      background: linear-gradient(90deg, #e93323 0%, #ff7931 100%);
      border-radius: 50px;
      padding: 0 6px;
    }

    &.on3 {
      font-size: 16px;
      font-weight: 600;
      color: #e93323;

      span {
        position: absolute;
        width: 30px;
        height: 30px;
        border: 3px solid #e93323;
        border-left: 3px solid transparent !important;
        border-top: 3px solid transparent !important;
        border-right: 3px solid transparent !important;
        border-radius: 50%;
        bottom: -4px;
        left: 50%;
        transform: translateX(-50%);
      }
    }
  }
}

.home_product {
  overflow: hidden;

  .hd_nav {
    display: flex;
    height: 65px;
    overflow: hidden;
    padding: 10px 0;

    .item {
      display: flex;
      flex-direction: column;
      justify-content: center;
      margin-right: 37px;

      .title {
        white-space: nowrap;
        font-size: 15px;
        color: #282828;
        text-align: center;
      }

      .label {
        width: 56px;
        height: 19px;
        line-height: 19px;
        text-align: center;
        background: transparent;
        border-radius: 10px;
        color: #999999;
        font-size: 11px;
      }

      &.active {
        .title {
          color: #ff4444;
        }

        .label {
          color: #fff;
          background: linear-gradient(270deg, rgba(255, 84, 0, 1) 0%, rgba(255, 0, 0, 1) 100%);
        }
      }
    }
  }

  .list-wrapper {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;

    .item {
      width: 48.5%;
      margin-bottom: 10px;
      position: relative;

      .jia {
        width: 22px;
        height: 22px;
        background-color: #e93323;
        border-radius: 50%;
        position: absolute;
        right: 10px;
        bottom: 8px;

        .jiaCon {
          width: 100%;
          height: 100%;
          text-align: center;
          line-height: 22px;

          .iconfont {
            color: #fff;
            font-size: 13px;
          }
        }
      }

      .img-box {
        width: 100%;
        height: 173px;

        .img {
          width: 100%;
          height: 100%;
        }

        img,
        .box {
          width: 65px;
          height: 50px;
        }

        .empty-box {
          background: #f3f9ff;
        }

        .box {
          background: #d8d8d8;
        }

        .label {
          position: absolute;
          left: 0;
          top: 0;
          width: 46px;
          height: 22px;
          border-radius: 10px 0px 10px 0px;
          color: #fff;
          font-size: 13px;
          text-align: center;
          line-height: 22px;
        }
      }

      .info {
        padding: 7px 10px;
        background: #fff;
        border-radius: 0px 0px 10px 10px;

        .pictrue {
          // width: 99px;
          height: 14px;
          margin-top: 4px;

          img {
            // width: 100%;
            height: 100%;
          }
        }

        .title {
          font-size: 14px;
          color: #282828;
        }

        .sales {
          color: #999;
          font-size: 11px;
        }

        .price {
          display: flex;
          align-items: center;
          margin-top: 6px;

          img {
            width: 70px;
            height: 15px;
          }

          .num {
            font-size: 20px;
            margin-right: 4px;
            font-family: SemiBold;
            color: #e93323;
            span {
              font-size: 12px;
            }
          }

          .label {
            width: 16px;
            height: 18px;
            margin-left: 5px;
            text-align: center;
            line-height: 18px;
            font-size: 11px;

            &.on {
              margin-left: 0;
            }
          }
        }
      }
    }
  }
}
</style>
