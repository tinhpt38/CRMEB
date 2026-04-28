<template>
  <common_wrapper :config="configObj">
    <div class="home_product">
      <!-- Header Section -->
      <div class="header-box" :style="headerBoxStyle" v-if="headerText || headerImg">
        <div class="title-text" v-if="headerType === 0" :style="titleTextStyle">
          {{ headerText }}
        </div>
        <div class="title-img" v-else :style="titleImgBoxStyle">
          <img :src="headerImg" :style="titleImgStyle" alt="" v-if="headerImg" />
          <div class="empty-img" v-else>Chưa có hình ảnh nào</div>
        </div>
      </div>

      <!-- Single Column -->
      <template v-if="styleConfig == 0">
        <div class="list-wrapper itemA">
          <div
            class="item"
            v-for="(item, index) in list"
            :key="index"
            :style="{
              borderRadius: bgRadius,
            }"
          >
            <div class="img-box">
              <img
                class="img"
                v-if="item.image"
                :src="item.image"
                alt=""
                :style="{
                  borderRadius: imgRadius,
                }"
              />
              <div
                v-else
                class="empty-box"
                :style="{
                  borderRadius: imgRadius,
                }"
              >
                <img src="../../assets/images/shan.png" />
              </div>
            </div>
            <div class="info">
              <div class="hd">
                <div
                  class="title line2"
                  v-if="checkboxInfo.indexOf(0) != -1"
                  :style="{
                    fontWeight: goodsName,
                    color: toneConfig ? goodsNameColor : '#333',
                  }"
                >
                  {{ item.store_name || 'Huawei Honor được hưởng dịch vụ thay màn hình máy tính bảng, thay màn hình và sửa chữa bo mạch chủ màn hình ngoài' }}
                </div>
                <img v-if="checkboxInfo.indexOf(1) != -1" src="../../assets/images/goods01.png" />
              </div>
              <div
                class="price acea-row row-middle"
                :class="checkboxInfo.indexOf(3) == -1 && checkboxInfo.indexOf(4) == -1 ? 'on' : ''"
              >
                <div
                  class="num"
                  v-if="checkboxInfo.indexOf(2) != -1"
                  :style="{
                    color: toneConfig ? goodsPriceColor : colorStyle.theme,
                  }"
                >
                  <span>￥</span>{{ item.price ? $HandlePrice(item.price, 0) : 33
                  }}<span>{{ item.price ? $HandlePrice(item.price, 1) : '' }}</span>
                </div>
                <img class="img" v-if="checkboxInfo.indexOf(5) != -1" src="../../assets/images/goods02.png" />
              </div>
              <div class="bottom">
                <span
                  class="mr8"
                  v-if="checkboxInfo.indexOf(3) != -1"
                  :style="{
                    color: toneConfig ? soldNumColor : '#999999',
                  }"
                  >Đã bán{{ item.sales || 0 }}miếng</span
                >
                <span
                  v-if="checkboxInfo.indexOf(4) != -1"
                  :style="{
                    color: toneConfig ? scoreColor : '#999999',
                  }"
                  >Điểm {{ item.star || 0 }}</span
                >
              </div>
            </div>
            <div v-if="!cartConfig">
              <div
                class="bnt"
                v-if="bntStyleConfig == 0"
                :style="{
                  background: toneCartConfig ? bntBgColor : themeColor,
                }"
              >
                Mua
              </div>
              <div
                class="jia"
                v-else
                :style="{
                  background: toneCartConfig ? bntBgColor : themeColor,
                }"
              >
                <div class="jiaCon">
                  <span class="iconfont iconjiahao1" v-if="bntStyleConfig == 1"></span>
                  <span class="iconfont icongouwuche1" v-else></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>

      <!-- Two Columns -->
      <template v-else-if="styleConfig == 1">
        <div class="list-wrapper itemC">
          <div class="item" v-for="(item, index) in list" :key="index">
            <div class="img-box">
              <img
                class="img"
                v-if="item.image"
                :src="item.image"
                alt=""
                :style="{
                  borderRadius: imgRadius2,
                }"
              />
              <div
                v-else
                class="empty-box"
                :style="{
                  borderRadius: imgRadius2,
                }"
              >
                <img src="../../assets/images/shan.png" />
              </div>
            </div>
            <div
              class="info"
              :class="
                checkboxInfo.length == 1 && checkboxInfo.indexOf(0) != -1 && !cartConfig
                  ? 'on'
                  : ((checkboxInfo.length == 1 && checkboxInfo.indexOf(4) != -1) || !checkboxInfo.length) && !cartConfig
                  ? 'on2'
                  : ''
              "
              :style="{
                borderRadius: bgRadius2,
              }"
            >
              <div class="hd">
                <div
                  class="title line2"
                  v-if="checkboxInfo.indexOf(0) != -1"
                  :style="{
                    fontWeight: goodsName,
                    color: toneConfig ? goodsNameColor : '#333',
                  }"
                >
                  {{ item.store_name || 'Đây là khu vực hiển thị tên sản phẩm,Khu vực hiển thị tên sản phẩm,Khu vực hiển thị tên sản phẩm' }}
                </div>
                <img v-if="checkboxInfo.indexOf(1) != -1" src="../../assets/images/goods01.png" />
              </div>
              <div class="price acea-row row-middle">
                <div
                  class="num mb-10"
                  v-if="checkboxInfo.indexOf(2) != -1"
                  :style="{
                    color: toneConfig ? goodsPriceColor : colorStyle.theme,
                  }"
                >
                  <span>￥</span>{{ item.price ? $HandlePrice(item.price, 0) : 77
                  }}<span>{{ item.price ? $HandlePrice(item.price, 1) : '' }}</span>
                </div>
                <img class="img" v-if="checkboxInfo.indexOf(5) != -1" src="../../assets/images/goods02.png" />
              </div>
              <div
                class="bottom"
                v-if="checkboxInfo.indexOf(3) != -1"
                :style="{
                  color: toneConfig ? soldNumColor : '#999999',
                }"
              >
                <span>Đã bán{{ item.sales || 0 }}miếng</span>
              </div>
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
      </template>

      <!-- Three Columns / Sliding -->
      <template v-else>
        <div
          class="list-wrapper itemB"
          :class="styleConfig == 3 ? 'itemD' : ''"
          :style="{
            borderRadius: bgRadius,
          }"
        >
          <div class="list">
            <div class="item" v-for="(item, index) in list" :key="index">
              <div class="img-box">
                <img
                  class="img"
                  v-if="item.image"
                  :src="item.image"
                  alt=""
                  :style="{
                    borderRadius: imgRadius,
                  }"
                />
                <div
                  v-else
                  class="empty-box"
                  :style="{
                    borderRadius: imgRadius,
                  }"
                >
                  <img src="../../assets/images/shan.png" />
                </div>
              </div>
              <div
                class="info"
                :class="
                  checkboxInfo.indexOf(2) == -1 && checkboxInfo.indexOf(0) != -1 && !cartConfig
                    ? 'on'
                    : checkboxInfo.indexOf(2) == -1 && checkboxInfo.indexOf(0) == -1 && !cartConfig
                    ? 'on2'
                    : ''
                "
              >
                <div class="hd" v-if="checkboxInfo.indexOf(0) != -1">
                  <div
                    class="title line2"
                    :style="{
                      fontWeight: goodsName,
                      color: toneConfig ? goodsNameColor : '#333',
                    }"
                  >
                    {{ item.store_name || 'Tên sản phẩm Tên người bán sản phẩm Người bán người bán…' }}
                  </div>
                </div>
                <div class="price" v-if="checkboxInfo.indexOf(2) != -1">
                  <div
                    class="num"
                    :style="{
                      color: toneConfig ? goodsPriceColor : colorStyle.theme,
                    }"
                  >
                    <span>￥</span>{{ item.price ? $HandlePrice(item.price, 0) : 77
                    }}<span>{{ item.price ? $HandlePrice(item.price, 1) : '' }}</span>
                  </div>
                </div>
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
      </template>
    </div>
  </common_wrapper>
</template>

<script>
import { mapState } from 'vuex';
export default {
  name: 'home_good_recommend',
  cname: 'Sản phẩm được đề xuất',
  configName: 'c_good_recommend',
  icon: '#iconzujian-youpintuijian', // Placeholder icon
  type: 3,
  defaultName: 'goodRecommend',
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
    headerBoxStyle() {
      return {
        marginBottom: '10px',
        padding: '0 10px',
        textAlign: this.headerAlign,
      };
    },
    titleTextStyle() {
      return {
        color: this.headerColor,
        fontSize: this.headerFontSize + 'px',
        fontWeight: this.headerFontWeight,
        fontStyle: this.headerFontStyle,
        textAlign: this.headerAlign,
      };
    },
    titleImgBoxStyle() {
      return {
        textAlign: this.headerAlign,
      };
    },
    titleImgStyle() {
      return {
        maxWidth: '100%',
        height: 'auto',
      };
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
      configObj: null,
      defaultConfig: {
        cname: 'Sản phẩm được đề xuất',
        name: 'goodRecommend',
        timestamp: this.num,
        isHide: false,
        setUp: {
          tabVal: 0,
        },
        // Header Config
        headerTitle: 'Cài đặt đầu',
        headerType: {
          title: 'Loại tiêu đề',
          tabVal: 0,
          tabList: [{ name: 'Từ' }, { name: 'hình ảnh' }],
        },
        headerText: {
          title: 'văn bản tiêu đề',
          value: 'Sản phẩm được đề xuất',
        },
        headerImg: {
          url: '',
          type: 'code',
          delType: 1,
          name: 'Tải ảnh lên',
        },
        // Content Config
        titleGoods: 'Cài đặt sản phẩm',
        goodsList: {
          max: 20,
          list: [],
        },
        productList: {
          list: [],
        },
        typeConfig: {
          title: 'Chọn phương pháp',
          activeValue: 1,
          list: [
            { activeValue: 1, title: 'sản phẩm được chỉ định' },
            { activeValue: 3, title: 'Chỉ định danh mục' },
            { activeValue: 4, title: 'Nhãn sản phẩm' },
          ],
        },
        goodsSort: {
          title: 'Danh mục sản phẩm',
          tabVal: 1,
          tabList: [{ name: 'toàn diện' }, { name: 'Doanh số bán hàng' }, { name: 'giá' }],
        },
        numberConfig: {
          title: 'số lượng sản phẩm',
          val: 3,
          min: 1,
        },
        classList: {
          title: 'Danh mục sản phẩm',
          classVal: [],
        },
        goodsLabel: {
          title: 'Nhãn sản phẩm',
          activeValue: [],
          list: [],
        },
        checkboxInfo: {
          title: 'hiển thị thông tin',
          name: 'checkboxInfo',
          type: [0, 2, 5], // Name, Price, Member Price (5)
          list: [
            { id: 0, name: 'Tên sản phẩm' },
            { id: 2, name: 'Giá sản phẩm' },
            { id: 5, name: 'Giá thành viên' },
          ],
        },
        cartConfig: {
          title: 'nút giỏ hàng',
          tabVal: 0, // 0: Show, 1: Hide
          tabList: [{ name: 'trình diễn' }, { name: 'trốn' }],
        },
        bntStyleConfig: {
          title: 'kiểu nút',
          tabVal: 0,
          tabList: [
            { name: 'phong cách1', icon: 'icon-circle' },
            { name: 'phong cách2', icon: 'icon-plus' },
            { name: 'phong cách3', icon: 'icon-cart' },
          ],
        },
        bntConfig: {
          title: 'Hiệu ứng nút',
          tabVal: 1,
          tabList: [{ name: 'Nhập trang chi tiết sản phẩm' }, { name: 'thêm vào giỏ hàng' }],
        },
        // Style Config
        titleRight: 'phong cách danh sách', // List Style
        styleConfig: {
          title: 'phong cách danh sách',
          tabVal: 0,
          tabList: [{ name: 'Hiển thị cột đơn' }, { name: 'Hai cột theo chiều dọc' }, { name: 'hiển thị ba cột' }, { name: 'Trượt sang trái hoặc phải' }],
        },
        headerStyleTitle: 'Kiểu đầu',
        headerTextConfig: {
          title: 'văn bản tiêu đề',
          tabVal: 1, // 0: Bold, 1: Normal, 2: Italic
          tabList: [
            { name: 'In đậm', style: 'bold' },
            { name: 'Bình thường', style: 'normal' },
            { name: 'nghiêng', style: 'italic' },
          ],
        },
        headerColor: {
          title: 'màu tiêu đề',
          default: [{ item: '#333333' }],
          color: [{ item: '#333333' }],
        },
        headerAlign: {
          title: 'chức danh',
          tabVal: 1, // 0: Left, 1: Center, 2: Right
          tabList: [
            { name: 'căn trái', style: 'left' },
            { name: 'căn giữa', style: 'center' },
            { name: 'Căn phải', style: 'right' },
          ],
        },
        headerFontSize: {
          title: 'Cỡ chữ tiêu đề',
          val: 16,
          min: 12,
          max: 30,
        },
        cartStyleTitle: 'nút giỏ hàng',
        goodsStyleTitle: 'Phong cách hình ảnh sản phẩm',
        toneCartConfig: {
          title: 'giai điệu',
          tabVal: 0,
          tabList: [{ name: 'Theo dõi chủ đề' }, { name: 'Tùy chỉnh' }],
        },
        bntBgColor: {
          title: 'màu nút',
          default: [{ item: '#E93323' }, { item: '#FF7931' }],
          color: [{ item: '#E93323' }, { item: '#FF7931' }],
        },
        generalStyleTitle: 'Phong cách phổ quát',
        componentBgConfig: {
          title: 'Cài đặt nền',
          tabVal: 0,
          tabList: [{ name: 'màu sắc' }, { name: 'hình ảnh' }],
          colorConfig: {
            title: 'màu nền',
            default: [{ item: '#F5F5F5' }],
            color: [{ item: '#F5F5F5' }],
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
        bottomBgColor: {
          title: 'nền dưới cùng',
          default: [{ item: '#F5F5F5' }],
          color: [{ item: '#F5F5F5' }],
        },
        paddingConfig: {
          title: 'phần đệm',
          isAll: false,
          val: 10,
          min: 0,
          max: 100,
          valList: [{ val: 0 }, { val: 10 }, { val: 0 }, { val: 10 }],
        },
        marginConfig: {
          title: 'lề',
          isAll: false,
          val: 0,
          min: 0,
          max: 100,
          valList: [{ val: 10 }, { val: 0 }, { val: 0 }, { val: 0 }],
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
          val: 10,
          min: 0,
        },
        mbConfig: {
          title: 'khoảng cách nội dung', // Page Spacing in prompt, using Content Spacing name
          val: 10,
          min: 0,
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
        filletImg: {
          title: 'Giá trị phi lê', // Image Radius
          type: 0,
          val: 8,
          valList: [{ val: 8 }, { val: 8 }, { val: 8 }, { val: 8 }],
        },
        goodsName: {
          title: 'Tên sản phẩm',
          tabVal: 0,
          tabList: [
            { name: 'In đậm', style: 'bold' },
            { name: 'Bình thường', style: 'normal' },
          ],
        },
        toneConfig: {
          title: 'giai điệu',
          tabVal: 0,
          tabList: [{ name: 'Theo dõi chủ đề' }, { name: 'Tùy chỉnh' }],
        },
        goodsNameColor: {
          title: 'Tên sản phẩm',
          default: [{ item: '#333333' }],
          color: [{ item: '#333333' }],
        },
        goodsPriceColor: {
          title: 'Giá sản phẩm',
          default: [{ item: '#E93323' }],
          color: [{ item: '#E93323' }],
        },
        soldNumColor: {
          title: 'Số lượng bán',
          default: [{ item: '#999999' }],
          color: [{ item: '#999999' }],
        },
        scoreColor: {
          title: 'điểm',
          default: [{ item: '#999999' }],
          color: [{ item: '#999999' }],
        },
      },
      list: [],
      pageData: {},
      styleConfig: 0,
      checkboxInfo: [],
      cartConfig: 0,
      bntStyleConfig: 0,
      imgRadius: 0,
      imgRadius2: 0,
      goodsName: '',
      toneConfig: 0,
      goodsNameColor: '',
      goodsPriceColor: '',
      soldNumColor: '',
      scoreColor: '',
      toneCartConfig: 0,
      bntBgColor: '',
      bntBgColorLeft: '',
      bgColor: '',
      bottomBgColor: '',
      paddingConfig: {
        title: 'phần đệm',
        isAll: false,
        val: 10,
        min: 0,
        max: 100,
        valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
      },
      marginConfig: {
        title: 'lề',
        isAll: false,
        val: 0,
        min: 0,
        max: 100,
        valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
      },
      bgRadius: 0,
      bgRadius2: 0,
      themeColor: '',
      // Header Data
      headerType: 0, // 0: Text, 1: Image (In tabList index) -> Wait, tabList[0] is Image, tabList[1] is Text in my config above?
      // In default config above: tabList: [{name: 'hình ảnh'}, {name: 'Từ'}]. So 0 is Image, 1 is Text.
      // Let's stick to this.
      headerText: '',
      headerImg: '',
      headerFontWeight: 'normal',
      headerFontStyle: 'normal',
      headerColor: '',
      headerAlign: 'center',
      headerFontSize: 16,
      showHeader: true,
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
      let configObj = JSON.parse(JSON.stringify(data));
      this.configObj = configObj;

      this.paddingConfig = configObj.paddingConfig || {
        title: 'phần đệm',
        val: 10,
        min: 0,
        max: 100,
        isAll: false,
        valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
      };
      this.marginConfig = configObj.marginConfig || {
        title: 'lề',
        val: 0,
        min: 0,
        max: 100,
        isAll: false,
        valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
      };

      if (!configObj.paddingConfig) {
        if (configObj.topConfig) this.paddingConfig.valList[0].val = configObj.topConfig.val;
        if (configObj.bottomConfig) this.paddingConfig.valList[2].val = configObj.bottomConfig.val;
        if (configObj.prConfig) {
          this.paddingConfig.valList[1].val = configObj.prConfig.val;
          this.paddingConfig.valList[3].val = configObj.prConfig.val;
        }
        configObj.paddingConfig = this.paddingConfig;
      }
      if (!configObj.marginConfig) {
        if (configObj.mbConfig) this.marginConfig.valList[0].val = configObj.mbConfig.val;
        configObj.marginConfig = this.marginConfig;
      }

      // Ensure new configs exist
      if (!configObj.zIndexConfig) this.$set(configObj, 'zIndexConfig', this.defaultConfig.zIndexConfig);
      if (!configObj.borderConfig) this.$set(configObj, 'borderConfig', this.defaultConfig.borderConfig);
      if (!configObj.shadowConfig) this.$set(configObj, 'shadowConfig', this.defaultConfig.shadowConfig);
      if (!configObj.componentBgConfig) this.$set(configObj, 'componentBgConfig', this.defaultConfig.componentBgConfig);

      // Header Config Defaults
      if (!configObj.headerTitle) this.$set(configObj, 'headerTitle', this.defaultConfig.headerTitle);
      if (!configObj.headerType) this.$set(configObj, 'headerType', this.defaultConfig.headerType);
      if (!configObj.headerText) this.$set(configObj, 'headerText', this.defaultConfig.headerText);
      if (!configObj.headerImg) this.$set(configObj, 'headerImg', this.defaultConfig.headerImg);
      if (!configObj.headerStyleTitle) this.$set(configObj, 'headerStyleTitle', this.defaultConfig.headerStyleTitle);
      if (!configObj.headerTextConfig) this.$set(configObj, 'headerTextConfig', this.defaultConfig.headerTextConfig);
      if (!configObj.headerColor) this.$set(configObj, 'headerColor', this.defaultConfig.headerColor);
      if (!configObj.headerAlign) this.$set(configObj, 'headerAlign', this.defaultConfig.headerAlign);
      if (!configObj.headerFontSize) this.$set(configObj, 'headerFontSize', this.defaultConfig.headerFontSize);

      // Header Config Assignment
      this.headerType = configObj.headerType.tabVal;
      this.headerText = configObj.headerText.value;
      this.headerImg = configObj.headerImg.url;

      let headerTextConfig = configObj.headerTextConfig.tabVal;
      this.headerFontWeight = headerTextConfig == 0 ? 'bold' : 'normal';
      this.headerFontStyle = headerTextConfig == 2 ? 'italic' : 'normal';

      this.headerColor =
        configObj.headerColor.color && configObj.headerColor.color[0] ? configObj.headerColor.color[0].item : '#333';

      const alignList = configObj.headerAlign.tabList;
      const alignVal = configObj.headerAlign.tabVal;
      this.headerAlign = alignList && alignList[alignVal] ? alignList[alignVal].style : 'left';

      this.headerFontSize = configObj.headerFontSize.val;

      if (configObj.mbConfig) {
        this.styleConfig = configObj.styleConfig.tabVal;
        this.checkboxInfo = configObj.checkboxInfo.type;
        this.cartConfig = configObj.cartConfig.tabVal;
        this.bntStyleConfig = configObj.bntStyleConfig.tabVal;

        let filletImg = configObj.filletImg.type;
        let filletValImg = configObj.filletImg.val;
        let valListImg = configObj.filletImg.valList;
        this.imgRadius = filletImg
          ? valListImg[0].val + 'px ' + valListImg[1].val + 'px ' + valListImg[3].val + 'px ' + valListImg[2].val + 'px'
          : filletValImg + 'px';
        this.imgRadius2 = filletImg
          ? valListImg[0].val + 'px ' + valListImg[1].val + 'px 0 0'
          : filletValImg + 'px ' + filletValImg + 'px 0 0';

        let goodsTabVal = configObj.goodsName.tabVal;
        this.goodsName = configObj.goodsName.tabList[goodsTabVal].style;

        this.toneConfig = configObj.toneConfig.tabVal;
        this.goodsNameColor = configObj.goodsNameColor.color[0].item;
        this.goodsPriceColor = configObj.goodsPriceColor.color[0].item;
        this.soldNumColor = configObj.soldNumColor.color[0].item;
        this.scoreColor = configObj.scoreColor.color[0].item;

        this.toneCartConfig = configObj.toneCartConfig.tabVal;
        let bntBgColorLeft = configObj.bntBgColor.color[0].item;
        let bntBgColorRight = configObj.bntBgColor.color[1].item;
        this.bntBgColorLeft = bntBgColorLeft;
        this.bntBgColor = `linear-gradient(90deg,${bntBgColorLeft} 0%,${bntBgColorRight} 100%)`;

        this.bottomBgColor = configObj.bottomBgColor.color[0].item;
        this.themeColor = `linear-gradient(90deg,${this.colorStyle.theme} 0%,${this.colorStyle.gradient} 100%)`;

        let fillet = configObj.fillet.type;
        let filletVal = configObj.fillet.val;
        let valList = configObj.fillet.valList;
        this.bgRadius = fillet
          ? valList[0].val + 'px ' + valList[1].val + 'px ' + valList[3].val + 'px ' + valList[2].val + 'px'
          : filletVal + 'px';
        this.bgRadius2 = fillet
          ? '0 0 ' + valList[3].val + 'px ' + valList[2].val + 'px'
          : '0 0 ' + filletVal + 'px ' + filletVal + 'px';

        if (configObj.typeConfig.activeValue == 1) {
          this.list = configObj.goodsList.list.length ? configObj.goodsList.list : 4;
        } else {
          this.list = configObj.productList.list.length ? configObj.productList.list : 4;
        }
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
.itemOn {
  border-radius: 0 !important;
  img,
  .empty-box {
    border-radius: 0 !important;
  }
  .img-box {
    .label {
      border-radius: 0 0 8px 0 !important;
    }
  }
}
.pageOn {
  border-radius: 8px !important;
}
.list-wrapper {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  .item {
    width: 48.5%;
    margin-bottom: 10px;
    background-color: #fff;
    position: relative;
    .bnt {
      width: 48px;
      height: 28px;
      background: linear-gradient(90deg, #e93323 0%, #ff7931 100%);
      border-radius: 25px;
      color: #fff;
      text-align: center;
      line-height: 28px;
      font-size: 12px;
      position: absolute;
      right: 10px;
      bottom: 10px;
    }
    .jia {
      width: 22px;
      height: 22px;
      background-color: #e93323;
      border-radius: 50%;
      position: absolute;
      right: 10px;
      bottom: 10px;
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
      position: relative;
      width: 100%;
      height: 173px;
      .img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
      img,
      .box {
        width: 65px;
        height: 50px;
      }
      .empty-box {
        background: #f3f9ff;
      }
    }
    .info {
      padding: 7px 10px;
      .title {
        font-size: 14px;
        color: #333;
      }
      img {
        height: 14px;
        display: block;
        margin-top: 4px;
      }
      .bottom {
        color: #999999;
        font-size: 11px;
      }
      .price {
        display: flex;
        align-items: center;
        img {
          width: 70px;
          height: 15px;
          display: block;
        }
        .num {
          font-size: 20px;
          margin-right: 4px;
          font-family: SemiBold;
          span {
            font-size: 12px;
          }
        }
      }
    }
  }
}
.itemA {
  .item {
    display: flex;
    width: 100%;
    padding: 10px;
    position: relative;
    .img-box {
      position: relative;
      width: 112px;
      height: 112px;
    }
    .info {
      display: flex;
      justify-content: space-between;
      flex-direction: column;
      flex: 1;
      margin-left: 10px;
      padding: 0;
      .hd {
        height: 63px;
      }
      .price {
        margin-top: 2px;
        &.on {
          margin-top: 20px;
        }
      }
    }
  }
}
.itemB {
  justify-content: inherit;
  background-color: #fff;
  padding: 16px 10px 0 10px;
  width: 100%;
  box-sizing: border-box;
  .list {
    display: flex;
    flex-wrap: wrap;
    width: 100%;
  }
  .item {
    width: 31.3%;
    margin-right: 10px;
    background: unset;
    .jia {
      right: 2px;
      bottom: 0;
    }
    .info {
      padding: 0;
      &.on {
        height: 70px;
      }
      &.on2 {
        height: 30px;
      }
      .hd {
        margin-top: 7px;
        height: 42px;
      }
      .price {
        margin-top: 7px;
        line-height: 1.2;
      }
    }
    &:nth-child(3n) {
      margin-right: 0;
    }
    .img-box {
      position: relative;
      width: 100%;
      height: 110px;
      img,
      .box,
      .empty-box {
        border-radius: 10px 10px 0 0;
      }
    }
  }
}
.itemD {
  flex-wrap: nowrap;
  display: flex;
  overflow-x: auto;
  padding-bottom: 10px; // Space for scrollbar or visual
  &::-webkit-scrollbar {
    display: none;
  }
  .list {
    flex-wrap: nowrap;
    // justify-content: center; // Don't center, let it flow
    // align-items: center;
  }
  .item {
    width: 110px; // Fixed width for sliding items
    flex-shrink: 0;
    margin-right: 10px;
    background: unset;
    // &:nth-child(3n) {
    //   margin-right: 10px; // Reset 3n margin removal for sliding
    // }
    &:last-child {
      margin-right: 0;
    }
    .img-box {
      height: 110px;
    }
  }
}
.itemC {
  .item {
    background-color: transparent;
    .info {
      background-color: #fff;
    }
  }
  .item .info.on {
    height: 67px;
  }
  .item .info.on2 {
    height: 40px;
  }
  .item .info .price {
    margin-top: 6px;
    margin-bottom: 8px;
  }
  .item .info .bottom {
    margin-top: 3px;
  }
}
.title-img {
  display: inline-block;
}
</style>
