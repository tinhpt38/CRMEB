<template>
  <common_wrapper :config="configObj">
    <div class="mobile-page">
      <div
        class="title"
        :style="{
          background: `linear-gradient(90deg,${titleColorLeft} 0%,${titleColorRight} 100%)`,
          borderRadius: fillet
            ? valList[0].val + 'px ' + valList[1].val + 'px ' + valList[3].val + 'px ' + valList[2].val + 'px'
            : filletVal + 'px',
        }"
      >
        <div
          class="title-box"
          :class="buttonConfig ? 'on' : ''"
          :style="{
            color: themeColor,
            fontSize: fontSize + 'px',
            fontStyle: txtStyle != 'bold' ? txtStyle : '',
            fontWeight: txtStyle == 'bold' ? txtStyle : '',
            textAlign: txtPosition,
          }"
        >
          {{ titleTxt }}
        </div>
        <div
          v-if="!buttonConfig"
          :style="{
            color: buttonColor,
            fontSize: buttonSize + 'px',
          }"
        >
          {{ buttonTitle }}<span class="iconfont iconjinru"></span>
        </div>
      </div>
    </div>
  </common_wrapper>
</template>

<script>
import { mapState } from 'vuex';
export default {
  name: 'home_title',
  cname: 'tiêu đề văn bản',
  icon: '#iconzujian-biaoti',
  configName: 'c_home_title',
  type: 2, // 0 Thành phần cơ bản 1 Thành phần tiếp thị 2 Thành phần công cụ
  defaultName: 'titles', // tên trận đấu bên ngoài
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
      configObj: null,
      defaultConfig: {
        cname: 'tiêu đề văn bản',
        name: 'titles',
        timestamp: this.num,
        isHide: false,
        setUp: {
          tabVal: 0,
        },
        titleLeft: 'Cài đặt tiêu đề',
        titleRight: 'Cài đặt văn bản',
        titleCurrency: 'Phong cách phổ quát',
        zIndexConfig: {
          title: 'Thành phần nổi',
          val: 0,
          min: 0,
        },
        titleConfig: {
          title: 'tên chức danh',
          value: 'tiêu đề',
          place: 'Vui lòng nhập tiêu đề',
          max: 10,
        },
        titleConfigRight: {
          title: 'Văn bản bên phải',
          value: 'Hơn',
          place: 'Vui lòng nhập văn bản bên phải',
          max: 5,
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
        linkConfig: {
          title: 'liên kết',
          value: '',
          place: 'Vui lòng nhập địa chỉ liên kết',
          max: 100,
          type: 'link',
        },
        themeColor: {
          title: 'màu tiêu đề',
          name: 'themeColor',
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
        buttonColor: {
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
        buttonText: {
          title: 'văn bản nút',
          val: 12,
          min: 6,
        },
        textPosition: {
          title: 'chức danh',
          tabVal: 0,
          tabList: [
            {
              name: 'căn trái',
              style: 'left',
              icon: 'icondoc_left',
            },
            {
              name: 'căn giữa',
              style: 'center',
              icon: 'icondoc_center',
            },
            {
              name: 'Căn phải',
              style: 'right',
              icon: 'icondoc_right',
            },
          ],
        },
        textStyle: {
          title: 'Kiểu tiêu đề',
          tabVal: 0,
          tabList: [
            {
              name: 'Bình thường',
              style: 'normal',
              icon: 'icondoc_general',
            },
            {
              name: 'nghiêng',
              style: 'italic',
              icon: 'icondoc_skew',
            },
            {
              name: 'In đậm',
              style: 'bold',
              icon: 'icondoc_bold',
            },
          ],
        },
        fontSize: {
          title: 'văn bản tiêu đề',
          val: 16,
          min: 8,
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
        componentBgConfig: {
          title: 'Cài đặt nền',
          tabVal: 0,
          tabList: [{ name: 'màu sắc' }, { name: 'hình ảnh' }],
          colorConfig: {
            title: 'màu nền',
            default: [{ item: '#F5F5F5' }, { item: '#F5F5F5' }],
            color: [{ item: '#F5F5F5' }, { item: '#F5F5F5' }],
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
        paddingConfig: {
          title: 'phần đệm',
          val: 0,
          min: 0,
          isAll: false,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        },
        marginConfig: {
          title: 'lề',
          val: 0,
          min: 0,
          isAll: false,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
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
      },
      titleTxt: '',
      link: '',
      txtPosition: '',
      txtStyle: '',
      fontSize: 0,
      titleColorLeft: '',
      titleColorRight: '',
      themeColor: '',
      paddingConfig: {
        title: 'phần đệm',
        val: 0,
        max: 100,
        isAll: false,
        valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
      },
      marginConfig: {
        title: 'lề',
        val: 0,
        max: 100,
        isAll: false,
        valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
      },

      pageData: {},
      bottomBgColor: '',
      buttonConfig: 0,
      buttonTitle: '',
      buttonColor: '',
      buttonSize: 0,
      fillet: 0,
      filletVal: 0,
      valList: [],
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
          this.$set(data, key, this.defaultConfig[key]);
        }
      }
      if (data.id) {
        this.titleTxt = data.titleConfig.value;
        this.link = data.linkConfig.value;
        this.txtPosition = data.textPosition.tabList[data.textPosition.tabVal].style;
        this.txtStyle = data.textStyle.tabList[data.textStyle.tabVal].style;
        this.themeColor = data.themeColor.color[0].item;
        this.fontSize = data.fontSize.val;
        this.titleColorLeft = data.moduleColor.color[0].item;
        this.titleColorRight = data.moduleColor.color[1].item;
        this.bottomBgColor = data.bottomBgColor.color[0].item;
        this.buttonConfig = data.buttonConfig.tabVal;
        this.buttonTitle = data.titleConfigRight.value;
        this.buttonColor = data.buttonColor.color[0].item;
        this.buttonSize = data.buttonText.val;
        this.fillet = data.fillet.type;
        this.filletVal = data.fillet.val;
        this.valList = data.fillet.valList;
        this.marginConfig = data.marginConfig || {
          val: 0,
          isAll: false,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        };

        if (!data.marginConfig) {
          if (data.mbConfig) this.marginConfig.valList[0].val = data.mbConfig.val;
        }
      }
    },
  },
};
</script>

<style scoped lang="scss">
.title {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.titleOn {
  border-radius: 10px !important;
}
.title {
  padding: 13px 12px;
  .title-box {
    &.on {
      width: 100%;
    }
  }
}
.iconfont {
  font-size: 14px;
}
</style>
