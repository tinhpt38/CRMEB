<template>
  <common_wrapper :config="configObj">
    <div class="product-desc-box">
      <div
        class="title"
        v-if="titleShow"
        :style="{
          color: titleColor,
          fontSize: titleSize + 'px',
          textAlign: titleAlign,
        }"
      >
        Giới thiệu sản phẩm
      </div>
      <div class="desc">Mô-đun này hiện không có cài đặt nội dung và chỉ hỗ trợ cài đặt kiểu chung.</div>
    </div>
  </common_wrapper>
</template>

<script>
import { mapState } from 'vuex';
export default {
  name: 'home_product_desc',
  cname: 'Giới thiệu sản phẩm',
  configName: 'c_product_desc',
  icon: '#iconzujian-wenzhangliebiao',
  type: 3,
  defaultName: 'productDesc',
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
    titleShow() {
      return this.configObj?.isShow?.tabVal == 0;
    },
    titleColor() {
      return this.configObj?.textColor?.color?.[0]?.item || '#333';
    },
    titleSize() {
      return this.configObj?.fontSize?.val || 16;
    },
    titleAlign() {
      return this.configObj?.textPosition.val || 'left';
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
        cname: 'Giới thiệu sản phẩm',
        name: 'productDesc',
        timestamp: this.num,
        contentTitle: 'Cài đặt nội dung',
        titleStyle: 'Kiểu tiêu đề',
        setUp: {
          tabVal: 0,
        },
        isShow: {
          title: 'hiển thị tiêu đề',
          tabVal: 0,
          tabList: [{ name: 'trình diễn' }, { name: 'trốn' }],
        },

        textPosition: {
          title: 'Căn chỉnh',
          val: 'center',
        },
        textColor: {
          title: 'màu văn bản',
          default: [{ item: '#333' }],
          color: [{ item: '#333' }],
        },
        fontSize: {
          title: 'cỡ chữ',
          val: 16,
          min: 12,
          max: 40,
        },
        titleCurrency: 'Phong cách phổ quát',
        moduleColor: {
          title: 'màu nền',
          name: 'moduleColor',
          default: [{ item: '#fff' }],
          color: [{ item: '#fff' }],
        },
        bottomBgColor: {
          title: 'nền dưới cùng',
          name: 'bottomBgColor',
          default: [{ item: '#fff' }],
          color: [{ item: '#fff' }],
        },
        marginConfig: {
          title: 'lề',
          val: 0,
          min: 0,
          max: 100,
          isAll: false,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        },
        paddingConfig: {
          title: 'phần đệm',
          val: 10,
          min: 0,
          max: 100,
          isAll: false,
          valList: [{ val: 10 }, { val: 10 }, { val: 10 }, { val: 10 }],
        },
        componentBgConfig: {
          title: 'Nền thành phần',
          tabVal: 0,
          colorConfig: {
            title: 'Cài đặt màu',
            default: [{ item: '#fff' }],
            color: [{ item: '#fff' }],
          },
          imageConfig: {
            title: 'Cài đặt hình ảnh',
            url: '',
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
          tabList: [
            {
              name: 'trốn',
            },
            {
              name: 'trình diễn',
            },
          ],
          val: 0,
          colorConfig: {
            title: 'màu bóng',
            default: [
              {
                item: 'rgba(0,0,0,0.1)',
              },
            ],
            color: [
              {
                item: 'rgba(0,0,0,0.1)',
              },
            ],
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
        borderRadius: '0',
        zIndexConfig: {
          title: 'Hệ thống phân cấp',
          val: 0,
          min: 0,
        },
      },
    };
  },
  methods: {
    setConfig(data) {
      if (!data) return;
      if (data) {
        this.configObj = data;
      }
    },
  },
};
</script>

<style scoped>
.product-desc-box {
  text-align: center;
}
.title {
  font-size: 16px;
  font-weight: bold;
  color: #333;
}
.desc {
  font-size: 12px;
  color: #999;
  margin-top: 10px;
}
</style>
