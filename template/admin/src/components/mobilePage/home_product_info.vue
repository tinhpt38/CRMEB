<template>
  <common_wrapper :config="configObj">
    <div class="product-info-box" :class="'style-' + specStyle">
      <div class="image-wrap">
        <img src="@/assets/images/product-diy.png" />
        <div class="indicators" :class="'pos-' + indicatorPosition" v-if="indicatorConfig">
          <!-- Line Style -->
          <div v-if="indicatorConfig.tabVal === 0" class="indicator-line">
            <div class="line-item active" :style="{ backgroundColor: selectColor }"></div>
            <div class="line-item" v-for="i in 4" :key="i" :style="{ backgroundColor: defaultColor }"></div>
          </div>
          <!-- Dot Style -->
          <div v-if="indicatorConfig.tabVal === 1" class="indicator-dot">
            <div class="dot-item active" :style="{ backgroundColor: selectColor }"></div>
            <div class="dot-item" :style="{ backgroundColor: defaultColor }"></div>
            <div class="dot-item" :style="{ backgroundColor: defaultColor }"></div>
          </div>
          <!-- Number Style -->
          <div v-if="indicatorConfig.tabVal === 2" class="indicator-number">
            <div class="num-box"><span class="current">1</span>/<span class="total">3</span></div>
          </div>
        </div>
        <!-- Spec Style 4: Below indicator, above card (inside image wrap) -->
        <div v-if="specStyle === 3" class="spec-style-4">
          <div class="spec-list">
            <div
              class="spec-item"
              v-for="(item, index) in mockSpecList"
              :key="index"
              :style="{
                borderColor: index === 0 ? specSelectedBorderColor : 'transparent',
                background: index === 0 ? specSelectedBgColor : '#777777',
              }"
            >
              <img :src="item.image" />
              <div
                class="spec-info"
                :style="{
                  color: index === 0 ? specSelectedTextColor : specUnselectedTextColor,
                }"
              >
                <div class="name">Ren mây xanh</div>
              </div>
            </div>
            <div class="total-count" :style="{ color: specTextColor }">
              6sự chi trả<br />Không bắt buộc<span class="iconfont iconyou"></span>
            </div>
          </div>
        </div>
      </div>
      <div class="info-box">
        <!-- Spec Style 1 & 2: Top of info box -->
        <div v-if="specStyle === 0 || specStyle === 1" class="spec-top-section" :class="'style-' + specStyle">
          <div class="spec-list" v-if="specStyle === 0">
            <!-- Style 1: Small images -->
            <div class="spec-item" v-for="(item, index) in mockSpecList" :key="index">
              <img :src="item.image" :style="{ borderColor: index === 0 ? specSelectedBorderColor : '#eee' }" />
            </div>
            <div class="total-count" style="margin-left: auto" :style="{ color: specTextColor }">
              6sự chi trả<br />Không bắt buộc<span class="iconfont iconyou"></span>
            </div>
          </div>
          <div class="spec-list-text" v-if="specStyle === 1">
            <!-- Style 2: Text/Image horizontal -->
            <div
              class="spec-item active"
              :style="{ background: specSelectedBgColor, borderColor: specSelectedBorderColor }"
            >
              <img :src="mockSpecList[0].image" />
              <span class="name" :style="{ color: specSelectedTextColor }">Khối xây dựng từ tính màu thời gian dành cho gia đình-32P</span>
            </div>
            <div class="spec-item">
              <img :src="mockSpecList[1].image" />
              <span class="name">Thời gian dành cho gia đình-màu 64P</span>
            </div>
            <div class="total-count" style="margin-left: auto" :style="{ color: specTextColor }">
              Tổng cộng có 6 phong cách<span class="iconfont iconyou"></span>
            </div>
          </div>
        </div>

        <div class="info-item" v-for="(item, index) in sortList" :key="index">
          <!-- Price Section -->
          <div v-if="item.name === 'price' && item.show" class="price-section">
            <div class="price-row">
              <div v-if="item.checkList.includes(0)" class="main-price-wrap" :style="{ color: finalPriceColor }">
                <span class="label">Giá nhận được</span>
                <span class="price" :style="{ fontSize: priceFontSize + 'px' }">¥199.00</span>
              </div>
              <div v-if="item.checkList.includes(1)" class="ot-price-wrap" :style="{ color: sellingPriceColor }">
                <span class="label">Giá bán</span>
                <span class="price">¥299.00</span>
              </div>
              <div v-if="item.checkList.includes(2)" class="vip-price-wrap">
                <span class="badge">SVIP</span>
                <span class="price">¥26.00</span>
              </div>
            </div>
          </div>

          <!-- Name Section -->
          <div v-if="item.name === 'name' && item.show" class="name-section">
            <div class="title" :style="{ color: titleColor, fontSize: titleFontSize + 'px' }">
              Ấm đun nước điện Midea ấm đun nước gia đình công suất nhỏ 0 lớp phủ thực phẩm thép không gỉ 304 hai lớp chống bỏng Tất cả thép liền mạch
            </div>
          </div>

          <!-- Data Section -->
          <div v-if="item.name === 'data' && item.show" class="data-section">
            <span v-if="item.checkList.includes(0)" :style="{ color: originalPriceColor }">Giá gốc: ¥299</span>
            <span v-if="item.checkList.includes(1)" :style="{ color: stockColor }">Trong kho: 1000</span>
            <span v-if="item.checkList.includes(2)" :style="{ color: salesColor }">Doanh số bán hàng: 1000+</span>
          </div>

          <!-- Tags Section -->
          <div v-if="item.name === 'tags' && item.show" class="tags-section">
            <span class="tag">Thẻ hoạt động</span>
            <span class="tag">Nhãn sản phẩm</span>
          </div>
        </div>

        <!-- Spec Style 3: Bottom of info box -->
        <div v-if="specStyle === 2" class="spec-bottom-section">
          <div class="spec-list">
            <div class="spec-item selected" :style="{ borderColor: specSelectedBorderColor }">
              <img :src="mockSpecList[0].image" />
              <div class="name" :style="{ color: specSelectedTextColor, background: specSelectedBgColor }">
                Mây xanh
              </div>
            </div>
            <div class="spec-item" v-for="(item, index) in mockSpecList.slice(1)" :key="index">
              <img :src="item.image" />
              <div class="name" :style="{ color: specUnselectedTextColor }">Vịt vàng</div>
            </div>
            <div class="total-count" style="margin-left: auto">6sự chi trả<br />Không bắt buộc<span class="iconfont iconyou"></span></div>
          </div>
        </div>
      </div>
    </div>
  </common_wrapper>
</template>

<script>
import { mapState } from 'vuex';

export default {
  name: 'home_product_info',
  cname: 'Thông tin sản phẩm',
  desc: 'Thành phần thông tin sản phẩm',
  configName: 'c_product_info',
  icon: '#iconzujian-shangpinxinxi',
  type: 3, // Product Component
  defaultName: 'productInfo',
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
    specStyle() {
      return this.configObj.specStyle ? this.configObj.specStyle.tabVal : 0;
    },
    specSettings() {
      return this.configObj.specSettings || {};
    },
    isCustomSpecTone() {
      return this.specSettings.colorTone && this.specSettings.colorTone.tabVal === 1;
    },
    specTextColor() {
      if (this.isCustomSpecTone) {
        return this.specSettings.textColor && this.specSettings.textColor.color[0].item
          ? this.specSettings.textColor.color[0].item
          : '#E93323';
      }
      return this.colorStyle.theme || '#E93323';
    },
    specSelectedBorderColor() {
      if (this.isCustomSpecTone) {
        return this.specSettings.selectedBorderColor && this.specSettings.selectedBorderColor.color[0].item
          ? this.specSettings.selectedBorderColor.color[0].item
          : '#E93323';
      }
      return '#E93323';
    },
    specSelectedTextColor() {
      if (this.isCustomSpecTone) {
        return this.specSettings.selectedTextColor && this.specSettings.selectedTextColor.color[0].item
          ? this.specSettings.selectedTextColor.color[0].item
          : this.specTextColor;
      }
      return this.specTextColor;
    },
    specSelectedBgColor() {
      if (this.isCustomSpecTone) {
        return this.specSettings.selectedBgColor && this.specSettings.selectedBgColor.color[0].item
          ? this.specSettings.selectedBgColor.color[0].item
          : this.hexToRgba(this.specSelectedBorderColor, 0.1);
      }
      return this.specStyle === 3 ? '#777777' : this.hexToRgba(this.specSelectedBorderColor, 0.1);
    },
    specUnselectedTextColor() {
      if (this.isCustomSpecTone) {
        return this.specSettings.unselectedTextColor && this.specSettings.unselectedTextColor.color[0].item
          ? this.specSettings.unselectedTextColor.color[0].item
          : this.specStyle === 3
          ? '#ffffff'
          : '#333333';
      }
      return this.specStyle === 3 ? '#ffffff' : '#333333';
    },
    titleColor() {
      const config = this.configObj.titleConfig;
      if (!config) return '#333333';
      if (config.tabVal === 0) {
        return this.colorStyle.theme || '#333333';
      }
      return config.color && config.color.color[0].item ? config.color.color[0].item : '#333333';
    },
    titleFontSize() {
      return this.configObj.titleConfig && this.configObj.titleConfig.fontSize
        ? this.configObj.titleConfig.fontSize.val
        : 16;
    },
    sortList() {
      return this.configObj.sortList ? this.configObj.sortList.list : [];
    },
    indicatorConfig() {
      return this.configObj.indicatorConfig || {};
    },
    selectColor() {
      return this.indicatorConfig.selectColor ? this.indicatorConfig.selectColor.color[0].item : '#E93323';
    },
    defaultColor() {
      return this.indicatorConfig.defaultColor ? this.indicatorConfig.defaultColor.color[0].item : '#CCCCCC';
    },
    indicatorPosition() {
      if (this.indicatorConfig.tabVal === 0) return 'center'; // Line style always centered
      const pos = this.indicatorConfig.positionVal;
      return pos === 0 ? 'left' : pos === 2 ? 'right' : 'center';
    },
    priceSettings() {
      return this.configObj.priceSettings || {};
    },
    dataSettings() {
      return this.configObj.dataSettings || {};
    },
    isCustomPriceTone() {
      return this.priceSettings.colorTone && this.priceSettings.colorTone.tabVal === 1;
    },
    finalPriceColor() {
      if (this.isCustomPriceTone) {
        return this.priceSettings.finalPriceColor && this.priceSettings.finalPriceColor.color[0].item
          ? this.priceSettings.finalPriceColor.color[0].item
          : '#E93323';
      }
      return this.colorStyle.theme || '#E93323';
    },
    sellingPriceColor() {
      if (this.isCustomPriceTone) {
        return this.priceSettings.sellingPriceColor && this.priceSettings.sellingPriceColor.color[0].item
          ? this.priceSettings.sellingPriceColor.color[0].item
          : '#333333';
      }
      return '#333333';
    },
    priceFontSize() {
      return this.priceSettings.priceFontSize ? this.priceSettings.priceFontSize.val : 24;
    },
    originalPriceColor() {
      return this.dataSettings.originalPriceColor && this.dataSettings.originalPriceColor.color[0].item
        ? this.dataSettings.originalPriceColor.color[0].item
        : '#999999';
    },
    stockColor() {
      return this.dataSettings.stockColor && this.dataSettings.stockColor.color[0].item
        ? this.dataSettings.stockColor.color[0].item
        : '#999999';
    },
    salesColor() {
      return this.dataSettings.salesColor && this.dataSettings.salesColor.color[0].item
        ? this.dataSettings.salesColor.color[0].item
        : '#999999';
    },
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
      defaultConfig: {
        cname: 'Thông tin sản phẩm',
        desc: 'Thành phần thông tin sản phẩm',
        name: 'productInfo',
        titleCurrency: 'Phong cách phổ quát',
        timestamp: this.num,
        setUp: {
          tabVal: 0,
        },
        indicatorConfig: {
          title: 'Cài đặt chỉ báo',
          tabVal: 1, // 0: Line, 1: Dot-Line, 2: Number
          tabList: [{ name: 'Kiểu đường' }, { name: 'Kiểu đường chấm' }, { name: 'Kiểu số' }],
          positionVal: 1, // 0: Left, 1: Center, 2: Right
          positionList: [{ name: 'căn trái' }, { name: 'căn giữa' }, { name: 'Căn phải' }],
          selectColor: {
            title: 'phong cách đã chọn',
            name: 'selectColor',
            default: [{ item: '#E93323' }],
            color: [{ item: '#E93323' }],
          },
          defaultColor: {
            title: 'Kiểu mặc định',
            name: 'defaultColor',
            default: [{ item: '#CCCCCC' }],
            color: [{ item: '#CCCCCC' }],
          },
        },
        titleConfig: {
          title: 'Cài đặt tiêu đề',
          tabVal: 0,
          tabList: [
            { name: 'Theo dõi chủ đề', val: 0 },
            { name: 'Tùy chỉnh', val: 1 },
          ],
          color: {
            title: 'màu tiêu đề',
            default: [{ item: '#333333' }],
            color: [{ item: '#333333' }],
          },
          fontSize: {
            title: 'cỡ chữ',
            val: 16,
            min: 12,
          },
        },
        specStyle: {
          title: 'phong cách đặc điểm kỹ thuật',
          tabVal: 0,
          tabList: [{ name: 'phong cách một' }, { name: 'Phong cách 2' }, { name: 'phong cách ba' }, { name: 'phong cách bốn' }],
        },
        specSettings: {
          title: 'Thông số kỹ thuật',
          colorTone: {
            title: 'giai điệu',
            tabVal: 0, // 0: Follow Theme, 1: Custom
            tabList: [
              { name: 'Theo dõi chủ đề', val: 0 },
              { name: 'Tùy chỉnh', val: 1 },
            ],
          },
          textColor: {
            title: 'màu nút',
            name: 'textColor',
            default: [{ item: '#666' }],
            color: [{ item: '#666' }],
          },
          selectedBorderColor: {
            title: 'Chọn đường viền',
            name: 'selectedBorderColor',
            default: [{ item: '#E93323' }],
            color: [{ item: '#E93323' }],
          },
          selectedTextColor: {
            title: 'Chọn văn bản',
            name: 'selectedTextColor',
            default: [{ item: '#E93323' }],
            color: [{ item: '#E93323' }],
          },
          selectedBgColor: {
            title: 'Chọn nền',
            name: 'selectedBgColor',
            default: [{ item: '#FDEBEB' }],
            color: [{ item: '#FDEBEB' }],
          },
          unselectedTextColor: {
            title: 'Không có văn bản nào được chọn',
            name: 'unselectedTextColor',
            default: [{ item: '#333333' }],
            color: [{ item: '#333333' }],
          },
        },
        sortList: {
          title: 'Cài đặt thông tin',
          tips: 'Kéo chuột để điều chỉnh thứ tự hiển thị thông tin.',
          list: [
            {
              name: 'price',
              cname: 'Giá sản phẩm',
              type: 'radio',
              show: true,
              checkList: [0, 1, 2],
              checkBoxList: [
                { name: 'giá bán', value: 0 },
                { name: 'giá chéo', value: 1 },
                { name: 'Giá thành viên', value: 2 },
              ],
            },
            {
              name: 'name',
              cname: 'Tên sản phẩm',
              type: 'radio',
              show: true,
            },
            {
              name: 'data',
              cname: 'Dữ liệu sản phẩm',
              type: 'radio',
              show: true,
              checkList: [0, 1, 2],
              checkBoxList: [
                { name: 'giá gốc', value: 0 },
                { name: 'Trong kho', value: 1 },
                { name: 'Doanh số bán hàng', value: 2 },
              ],
            },
            {
              name: 'tags',
              cname: 'Nhãn sản phẩm',
              type: 'radio',
              show: true,
            },
          ],
        },
        bottomBgColor: {
          title: 'nền dưới cùng',
          default: [{ item: '#fff' }],
          color: [{ item: '#fff' }],
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
          val: 8,
          min: 0,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        },
      },
      configObj: {},
      pageData: {},
      mockSpecList: [
        { image: require('@/assets/images/product-diy.png'), name: 'Đặc điểm kỹ thuật1' },
        { image: require('@/assets/images/product-diy.png'), name: 'Đặc điểm kỹ thuật2' },
        { image: require('@/assets/images/product-diy.png'), name: 'Đặc điểm kỹ thuật3' },
        { image: require('@/assets/images/product-diy.png'), name: 'Đặc điểm kỹ thuật4' },
        { image: require('@/assets/images/product-diy.png'), name: 'Đặc điểm kỹ thuật5' },
        { image: require('@/assets/images/product-diy.png'), name: 'Đặc điểm kỹ thuật6' },
      ],
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
      if (data) {
        for (let key in this.defaultConfig) {
          if (data[key] === undefined) {
            this.$set(data, key, this.defaultConfig[key]);
          }
        }
        this.configObj = data;
      }
    },
    hexToRgba(hex, opacity) {
      if (!hex) return '';
      let c;
      if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)) {
        c = hex.substring(1).split('');
        if (c.length == 3) {
          c = [c[0], c[0], c[1], c[1], c[2], c[2]];
        }
        c = '0x' + c.join('');
        return 'rgba(' + [(c >> 16) & 255, (c >> 8) & 255, c & 255].join(',') + ',' + opacity + ')';
      }
      return hex;
    },
  },
};
</script>

<style scoped lang="scss">
.product-info-box {
  &.style-3 {
    .image-wrap .indicators {
      bottom: 85px;
    }
  }
  .image-wrap {
    position: relative;
    img {
      width: 100%;
      display: block;
    }
    .indicators {
      position: absolute;
      bottom: 30px;
      width: 100%;
      display: flex;
      padding: 0 10px;
      box-sizing: border-box;

      &.pos-left {
        justify-content: flex-start;
      }
      &.pos-center {
        justify-content: center;
      }
      &.pos-right {
        justify-content: flex-end;
      }

      .indicator-line {
        display: flex;
        align-items: center;
        width: 100%;
        .line-item {
          width: 20%;
          height: 2px;
          margin: 0 3px;
          border-radius: 2px;
        }
      }

      .indicator-dot {
        display: flex;
        align-items: center;
        .dot-item {
          width: 6px;
          height: 6px;
          border-radius: 50%;
          margin: 0 3px;
          transition: all 0.3s;
          &.active {
            width: 12px;
            border-radius: 3px;
          }
        }
      }

      .indicator-number {
        .num-box {
          background: rgba(0, 0, 0, 0.3);
          color: #fff;
          font-size: 12px;
          padding: 2px 8px;
          border-radius: 10px;
          .current {
            font-size: 14px;
          }
        }
      }
    }
  }

  .info-box {
    position: sticky;
    padding: 16px 16px 8px 16px;
    border-radius: 16px 16px 0 0;
    background: linear-gradient(180deg, #ffffff 0%, #ffffff 54%, rgba(255, 255, 255, 0) 100%);
    margin-top: -16px;
    z-index: 1;

    .spec-top-section {
      margin-bottom: 15px;
      &.style-0 {
        .spec-list {
          display: flex;
          align-items: center;
          overflow-x: auto;
          &::-webkit-scrollbar {
            display: none;
          }
          scrollbar-width: none;
          .spec-item {
            margin-right: 10px;
            flex-shrink: 0;
            img {
              width: 40px;
              height: 40px;
              border-radius: 4px;
              object-fit: cover;
              border: 1px solid #eee;
            }
            &:first-child img {
              border: 1px solid #e93323;
            }
          }
          .total-count {
            font-size: 12px;
            color: #999;
            line-height: 1.2;
            display: flex;
            align-items: center;
            flex-shrink: 0;
            position: sticky;
            right: 0;
            background-color: #fff;
            padding-left: 10px;
            z-index: 10;
            .iconfont {
              font-size: 12px;
            }
          }
        }
      }
      &.style-1 {
        .spec-list-text {
          display: flex;
          align-items: center;
          overflow-x: auto;
          &::-webkit-scrollbar {
            display: none;
          }
          scrollbar-width: none;
          .spec-item {
            display: flex;
            align-items: center;
            background: #f5f5f5;
            padding: 4px 8px;
            border-radius: 4px;
            margin-right: 10px;
            white-space: nowrap;
            flex-shrink: 0;
            img {
              width: 20px;
              height: 20px;
              margin-right: 5px;
              border-radius: 2px;
            }
            .name {
              font-size: 12px;
              color: #333;
            }
            &.active {
              background: #fdebeb;
              border: 1px solid #e93323;
              .name {
                color: #e93323;
              }
            }
          }
          .total-count {
            font-size: 12px;
            color: #999;
            height: 28px;
            display: flex;
            align-items: center;
            white-space: nowrap;
            flex-shrink: 0;
            position: sticky;
            right: 0;
            background-color: #fff;
            padding-left: 10px;
            z-index: 10;
            .iconfont {
              font-size: 12px;
            }
          }
        }
      }
    }

    .spec-bottom-section {
      margin-top: 15px;
      .spec-list {
        display: flex;
        overflow-x: auto;
        &::-webkit-scrollbar {
          display: none;
        }
        scrollbar-width: none;
        .spec-item {
          margin-right: 10px;
          text-align: center;
          flex-shrink: 0;
          img {
            width: 60px;
            height: 60px;
            border-radius: 6px;
            object-fit: cover;
          }
          .name {
            font-size: 12px;
            color: #333;
            margin-top: 5px;
          }
          &.selected {
            border: 1px solid #e93323;
            border-radius: 6px;
            overflow: hidden;

            .name {
              color: #e93323;
              background: #fdebeb;
              border-radius: 2px;
            }
          }
        }
        .total-count {
          display: flex;
          align-items: center;
          font-size: 12px;
          color: #999;
          flex-shrink: 0;
          position: sticky;
          right: 0;
          background-color: #fff;
          padding-left: 10px;
          z-index: 10;
          .iconfont {
            font-size: 12px;
          }
        }
      }
    }
  }

  .spec-style-4 {
    position: absolute;
    bottom: 5px;
    width: 100%;
    background: rgba(153, 153, 153, 0.6);
    padding: 10px 0px 15px 10px;
    box-sizing: border-box;
    overflow-x: hidden;
    .spec-list {
      display: flex;
      align-items: center;
      width: max-content;
      .spec-item {
        margin-right: 10px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #777777;
        padding: 2px;
        border-radius: 8px;
        img {
          width: 40px;
          height: 40px;
          border-radius: 4px;
        }
        .spec-info {
          display: flex;
          color: #fff;
          width: 60px;
          font-size: 12px;
          padding-left: 5px;
        }
        &:first-child {
          border: 1px solid #e93323;
          padding: 2px;
        }
      }
      .total-count {
        margin-left: auto;
        color: #fff;
        height: 44px;
        background: #777777;
        padding: 2px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: sticky;
        right: 0;
        padding-left: 10px;
        width: 60px;
        z-index: 10;
        border-radius: 4px 0 0 4px;
        .iconfont {
          font-size: 12px;
        }
      }
    }
  }

  .info-item {
    margin-bottom: 8px;
  }

  .price-section {
    .price-row {
      display: flex;
      align-items: flex-end;
      flex-wrap: wrap;

      .main-price-wrap {
        display: flex;
        align-items: flex-end;
        margin-right: 10px;
        color: #e93323;
        .label {
          font-size: 11px;
          margin-right: 2px;
        }
        .price {
          font-size: 24px;
          font-weight: bold;
          line-height: 1;
        }
      }

      .ot-price-wrap {
        display: flex;
        align-items: flex-end;
        margin-right: 10px;
        color: #333;
        .label {
          font-size: 11px;
        }
        .price {
          font-size: 11px;
        }
      }

      .vip-price-wrap {
        display: flex;
        align-items: center;
        height: 18px;
        .badge {
          background: #333;
          color: #f8dcae;
          font-size: 10px;
          padding: 0 4px;
          border-radius: 9px 0 0 9px;
          height: 100%;
          display: flex;
          align-items: center;
          font-weight: bold;
        }
        .price {
          background: #fff0d8;
          color: #333;
          font-size: 11px;
          padding: 0 5px 0 2px;
          border-radius: 0 9px 9px 0;
          height: 100%;
          display: flex;
          align-items: center;
          font-weight: bold;
        }
      }
    }
  }

  .name-section {
    .title {
      font-size: 16px;
      color: #333;
      font-weight: bold;
      line-height: 1.4;
    }
  }

  .data-section {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: #999;
  }

  .tags-section {
    display: flex;
    flex-wrap: wrap;
    .tag {
      font-size: 10px;
      color: #e93323;
      border: 1px solid #e93323;
      padding: 0 4px;
      border-radius: 2px;
      margin-right: 5px;
    }
  }
}
</style>
