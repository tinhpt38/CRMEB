<template>
  <common_wrapper :config="configObj">
    <div
      class="box"
      :style="{
        borderBottomColor: lineColor,
        borderBottomStyle: style,
        borderBottomWidth: `${configObj && configObj.heightConfig.val}px`,
      }"
    ></div>
  </common_wrapper>
</template>

<script>
import { mapState, mapMutations } from 'vuex';
export default {
  name: 'z_auxiliary_line',
  cname: 'đường phụ trợ',
  configName: 'c_auxiliary_line',
  icon: '#iconzujian-fuzhuxian',
  type: 2, // 0 Thành phần cơ bản 1 Thành phần tiếp thị 2 Thành phần công cụ
  defaultName: 'guide', // tên trận đấu bên ngoài
  props: {
    index: {
      type: null,
      default: -1,
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
        cname: 'đường phụ trợ',
        name: 'guide',
        timestamp: this.num,
        isHide: false,
        setUp: {
          tabVal: 0,
        },
        titleLeft: 'Cài đặt hiển thị',
        titleRight: 'kiểu đường',
        titleCurrent: 'Phong cách phổ quát',
        zIndexConfig: {
          title: 'Thành phần nổi',
          val: 0,
          min: 0,
        },
        lineColor: {
          title: 'màu đường',
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
        lineBgColor: {
          title: 'nền dưới cùng',
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
        lineStyle: {
          title: 'Chọn kiểu',
          tabVal: 1,
          tabList: [
            {
              name: 'đường chấm chấm',
              style: 'dashed',
            },
            {
              name: 'đường liền nét',
              style: 'solid',
            },
            {
              name: 'đường chấm chấm',
              style: 'dotted',
            },
          ],
        },
        paddingConfig: {
          title: 'phần đệm',
          val: 0,
          min: 0,
          max: 100,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
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
        heightConfig: {
          title: 'chiều cao dòng',
          val: 10,
          min: 1,
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
        marginConfig: {
          title: 'lề',
          val: 0,
          min: 0,
          max: 100,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        },
      },
      configObj: null,
      bgColor: '',
      confObj: {},
      pageData: {},
      edge: '',
      udEdge: '',
      topConfig: '',
      bottomConfig: '',
      style: '',
      lineColor: '',
      paddingConfig: {
        title: 'phần đệm',
        val: 0,
        min: 0,
        max: 100,
        valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
      },
      marginConfig: {
        title: 'lề',
        val: 0,
        min: 0,
        max: 100,
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
      this.$set(this.configObj, 'bottomBgColor', data.lineBgColor);
      let styleType = data.lineStyle.tabVal;
      this.bgColor = data.lineBgColor.color[0].item;
      this.lineColor = data.lineColor.color[0].item;
      this.style = data.lineStyle.tabList[styleType].style;

      for (let key in this.defaultConfig) {
        if (this.configObj[key] === undefined) {
          this.$set(this.configObj, key, this.defaultConfig[key]);
        }
      }

      if (!data.paddingConfig) {
        data.paddingConfig = {
          isAll: false,
          valList: [
            { val: data.topConfig ? data.topConfig.val : 0 },
            { val: data.lrEdge ? data.lrEdge.val : 0 },
            { val: data.bottomConfig ? data.bottomConfig.val : 0 },
            { val: data.lrEdge ? data.lrEdge.val : 0 },
          ],
        };
      }

      if (!data.marginConfig) {
        data.marginConfig = {
          isAll: false,
          valList: [{ val: 0 }, { val: 0 }, { val: data.mbConfig ? data.mbConfig.val : 0 }, { val: 0 }],
        };
      }
    },
  },
};
</script>

<style scoped lang="scss">
.mobile-page {
  padding: 7px 0;
  display: inline-block;
  width: -webkit-fill-available;
}
</style>
