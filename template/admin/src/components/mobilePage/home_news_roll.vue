<template>
  <common_wrapper :config="configObj" v-if="list.length">
    <div class="news-box">
      <div
        class="item"
        :style="{
          background: `linear-gradient(90deg,${bgColorLeft} 0%,${bgColorRight} 100%)`,
          borderRadius: bgRadius,
        }"
        v-if="styleConfig == 0"
      >
        <div class="img-box" v-if="titleConfig == 0"><img :src="imgUrl" alt="" /></div>
        <div
          class="top"
          v-else
          :style="{
            color: toneConfig ? titleColor : '#fff',
            background: toneConfig
              ? `linear-gradient(90deg,${titleBgColorLeft} 0%,${titleBgColorRight} 100%)`
              : colorStyle.theme,
          }"
        >
          {{ titleTxtConfig || 'Tiêu đề trung tâm mua sắm' }}
        </div>
        <div
          class="right-box"
          :style="{
            color: newsColor,
          }"
        >
          {{ list[0].chiild[0].val }}
        </div>
        <span
          class="iconfont iconjinru"
          :style="{
            color: bntColor,
          }"
          v-if="!buttonConfig"
        ></span>
      </div>
      <div
        class="list"
        v-else
        :style="{
          background: `linear-gradient(90deg,${bgColorLeft} 0%,${bgColorRight} 100%)`,
          borderRadius: bgRadius,
        }"
      >
        <div class="title acea-row row-between-wrapper">
          <div class="pictrue" v-if="titleConfig == 0">
            <img :src="imgUrl" alt="" />
          </div>
          <div
            class="top"
            v-else
            :style="{
              color: !toneConfig ? titleColor : colorStyle.theme,
            }"
          >
            {{ titleTxtConfig || 'Tiêu đề trung tâm mua sắm' }}
          </div>
          <div
            v-if="!buttonConfig"
            :style="{
              color: bntColor,
            }"
          >
            {{ textConfig }}<span class="iconfont iconjinru"></span>
          </div>
        </div>
        <div
          class="text line1"
          v-for="(item, index) in list"
          :key="index"
          :style="{
            color: newsColor,
          }"
        >
          <span class="num" :class="index == 0 ? 'on' : index == 1 ? 'on2' : index == 2 ? 'on3' : ''">{{
            index + 1
          }}</span
          >{{ item.chiild[0].val }}
        </div>
      </div>
    </div>
  </common_wrapper>
</template>

<script>
import { mapState } from 'vuex';
// import theme from "@/mixins/theme";
export default {
  name: 'home_news_roll',
  cname: 'Thông báo tin tức',
  configName: 'c_news_roll',
  type: 0, // 0 Thành phần cơ bản 1 Thành phần tiếp thị 2 Thành phần công cụ
  defaultName: 'news', // tên trận đấu bên ngoài
  icon: '#iconzujian-xinwenbobao',
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
      // Nghiêm cấm sửa đổi dữ liệu khởi tạo mặc định
      defaultConfig: {
        cname: 'Thông báo tin tức',
        desc: 'Thông báo tin tức',
        name: 'news',
        timestamp: this.num,
        isHide: false,
        setUp: {
          tabVal: 0,
        },
        titleLeft: 'Cài đặt hiển thị',
        titleStyle: 'Phong cách thông báo',
        titleButton: 'Cài đặt nút',
        titleContent: 'Nội dung thông báo',
        titleRight: 'Kiểu tiêu đề',
        titleCurrency: 'Phong cách phổ quát',
        styleConfig: {
          title: 'Chọn phong cách',
          tabVal: 0,
          tabList: [
            {
              name: 'phong cách một',
            },
            {
              name: 'Phong cách 2',
            },
          ],
        },
        titleConfig: {
          title: 'Loại tiêu đề',
          tabVal: 0,
          tabList: [
            {
              name: 'hình ảnh',
            },
            {
              name: 'Từ',
            },
          ],
        },
        imgConfig: {
          url: require('@/assets/images/news2.png'),
          type: 'code',
          delType: 0,
          name: 'Tải ảnh lên',
        },
        titleTxtConfig: {
          title: 'văn bản tiêu đề',
          value: 'Tiêu đề trung tâm mua sắm',
          place: 'Vui lòng nhập văn bản tiêu đề',
          max: 4,
        },
        rollConfig: {
          title: 'chế độ cuộn',
          tabVal: 0,
          tabList: [
            {
              name: 'cuộn lên và xuống',
            },
            {
              name: 'Cuộn sang trái và phải',
            },
          ],
        },
        buttonConfig: {
          title: 'nút bên phải',
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
        textConfig: {
          title: 'Văn bản bên phải',
          value: 'Thêm',
          place: 'Vui lòng nhập văn bản bên phải',
          max: 4,
        },
        linkConfig: {
          title: 'liên kết',
          value: '',
          place: 'Chọn liên kết nhảy',
          max: 100,
          type: 'link',
        },
        listConfig: {
          max: 10,
          type: 1,
          list: [
            {
              chiild: [
                {
                  title: 'tiêu đề',
                  val: 'tiêu đề',
                  max: 20,
                  pla: 'Nhập tiêu đề',
                },
                {
                  title: 'liên kết',
                  val: '',
                  max: 200,
                  pla: 'kết nối đầu vào',
                },
              ],
              show: true,
            },
          ],
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
        titleBgColor: {
          title: 'nền tiêu đề',
          default: [
            {
              item: '#FCEAE9',
            },
            {
              item: '#FCEAE9',
            },
          ],
          color: [
            {
              item: '#FCEAE9',
            },
            {
              item: '#FCEAE9',
            },
          ],
        },
        titleColor: {
          title: 'văn bản tiêu đề',
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
        newsColor: {
          title: 'Tiêu đề tin tức',
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
        bntColor: {
          title: 'màu nút',
          default: [
            {
              item: '#999999',
            },
          ],
          color: [
            {
              item: '#999999',
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
          title: 'Nền thành phần',
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
          val: 0,
          min: 0,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        },
        // logoConfig: {
        //     header: 'bộ biểu tượng',
        //     title: 'Bạn có thể thêm tối đa 1 ảnh, chiều rộng khuyến nghị130 * 36px',
        //     url: require('@/assets/images/news.png')
        // }
      },
      tabVal: '',
      rollStyle: '',
      txtPosition: '',
      pageData: {},
      configObj: {},
      list: [],
      slider: 0,
      styleConfig: 0,
      imgUrl: '',
      buttonConfig: 0,
      textConfig: '',
      toneConfig: 0,
      newsColor: '',
      bntColor: '',
      bgColorLeft: '',
      bgColorRight: '',
      bgRadius: 0,
      titleConfig: 0,
      titleBgColorLeft: '',
      titleBgColorRight: '',
      titleColor: '',
      titleTxtConfig: '',
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
      if (!this.configObj.paddingConfig) {
        this.$set(this.configObj, 'paddingConfig', {
          title: 'phần đệm',
          isAll: false,
          val: 0,
          min: 0,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        });
        if (data.topConfig) this.configObj.paddingConfig.valList[0].val = data.topConfig.val;
        if (data.bottomConfig) this.configObj.paddingConfig.valList[2].val = data.bottomConfig.val;
        if (data.prConfig) {
          this.configObj.paddingConfig.valList[1].val = data.prConfig.val;
          this.configObj.paddingConfig.valList[3].val = data.prConfig.val;
        }
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
          this.$set(this.configObj, key, this.defaultConfig[key]);
        }
      }
      this.rollStyle = data.rollConfig.tabVal;
      this.txtPosition = data.textConfig.tabVal;
      // this.slider = this.marginConfig.valList[0].val;
      this.styleConfig = data.styleConfig.tabVal;
      this.imgUrl = data.imgConfig.url;
      this.buttonConfig = data.buttonConfig.tabVal;
      this.textConfig = data.textConfig.value;
      let lists = data.listConfig.list;
      let list = [];
      lists.forEach((item) => {
        if (item.show) {
          list.push(item);
        }
      });
      this.list = list;
      this.toneConfig = data.toneConfig.tabVal;
      this.newsColor = data.newsColor.color[0].item;
      this.bntColor = data.bntColor.color[0].item;
      this.bgColorLeft = data.moduleColor.color[0].item;
      this.bgColorRight = data.moduleColor.color[1].item;
      // this.prConfig = this.paddingConfig.valList[1].val;
      // this.topConfig = this.paddingConfig.valList[0].val;
      // this.bottomConfig = this.paddingConfig.valList[2].val;
      this.titleConfig = data.titleConfig.tabVal;
      this.titleBgColorLeft = data.titleBgColor.color[0].item;
      this.titleBgColorRight = data.titleBgColor.color[1].item;
      this.titleColor = data.titleColor.color[0].item;
      this.titleTxtConfig = data.titleTxtConfig.value;
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
.pageOn {
  border-radius: 6px !important;
}
.news-box {
  display: inline-block;
  width: -webkit-fill-available;
  .list {
    padding: 0 12px 16px 12px;
    .title {
      color: #999999;
      font-size: 12px;
      padding: 16px 0;

      .top {
        font-size: 16px;
      }

      .pictrue {
        height: 18px;
        img {
          height: 100%;
          width: 100%;
        }
      }
      .iconfont {
        font-size: 12px;
      }
    }
    .text {
      color: #282828;
      font-size: 14px;

      .num {
        font-size: 15px;
        margin-right: 7px;
        color: #999999;
        &.on {
          color: #e93323;
        }
        &.on2 {
          color: #ff7300;
        }
        &.on3 {
          color: #ffc300;
        }
      }

      & ~ .text {
        margin-top: 12px;
      }
    }
  }
  .item {
    display: flex;
    align-items: center;
    height: 44px;
    padding: 0 10px;
    font-size: 13px;
    color: #333;
    position: relative;
    .top {
      max-width: 64px;
      height: 20px;
      font-size: 13px;
      text-align: center;
      line-height: 20px;
      padding: 0 6px;
      border-radius: 4px;
      margin-right: 8px;
    }
    .iconfont {
      color: #999;
      font-size: 14px;
    }
    .img-box {
      height: 24px;
      img {
        height: 100%;
        margin-right: 8px;
      }
    }
    .right-box {
      flex: 1;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
  }
}
</style>
