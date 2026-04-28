<template>
  <div class="mobile-config">
    <div v-for="(item, key) in rCom" :key="key">
      <component
        :is="item.components.name"
        :configObj="configObj"
        ref="childData"
        :configNme="item.configNme"
        :key="key"
        @getConfig="getConfig"
        :index="activeIndex"
        :num="item.num"
      ></component>
    </div>

    <rightBtn :activeIndex="activeIndex" :configObj="configObj"></rightBtn>
  </div>
</template>

<script>
import toolCom from '@/components/mobileConfigRight/index.js';
import rightBtn from '@/components/rightBtn/index.vue';

export default {
  name: 'c_product_info',
  componentsName: 'home_product_info',
  components: {
    ...toolCom,
    rightBtn,
  },
  props: {
    activeIndex: {
      type: null,
    },
    num: {
      type: null,
    },
    index: {
      type: null,
    },
  },
  data() {
    return {
      configObj: {},
      defaultConfig: {
        cname: 'Thông tin sản phẩm',
        desc: 'Thành phần thông tin sản phẩm',
        name: 'productInfo',
        timestamp: this.num,
        setUp: {
          tabVal: 0,
        },
        indicatorConfig: {
          title: 'Cài đặt chỉ báo',
          tabVal: 1,
          tabList: [{ name: 'Kiểu đường' }, { name: 'Kiểu đường chấm' }, { name: 'Kiểu số' }],
          positionVal: 1,
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
            tabVal: 0,
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
        priceSettings: {
          title: 'thiết lập giá',
          colorTone: {
            title: 'giai điệu',
            tabVal: 0,
            tabList: [
              { name: 'Theo dõi chủ đề', val: 0 },
              { name: 'Tùy chỉnh', val: 1 },
            ],
          },
          finalPriceColor: {
            title: 'Màu giá',
            name: 'finalPriceColor',
            default: [{ item: '#E93323' }],
            color: [{ item: '#E93323' }],
          },
          sellingPriceColor: {
            title: 'Màu giá bán',
            name: 'sellingPriceColor',
            default: [{ item: '#333333' }],
            color: [{ item: '#333333' }],
          },
          priceFontSize: {
            title: 'Cỡ chữ giá',
            val: 24,
            min: 12,
            max: 50,
          },
        },
        dataSettings: {
          title: 'Cài đặt dữ liệu',
          originalPriceColor: {
            title: 'Màu giá gốc',
            name: 'originalPriceColor',
            default: [{ item: '#999999' }],
            color: [{ item: '#999999' }],
          },
          stockColor: {
            title: 'Màu sắc chứng khoán',
            name: 'stockColor',
            default: [{ item: '#999999' }],
            color: [{ item: '#999999' }],
          },
          salesColor: {
            title: 'màu bán hàng',
            name: 'salesColor',
            default: [{ item: '#999999' }],
            color: [{ item: '#999999' }],
          },
        },
      },
      rCom: [
        {
          components: toolCom.c_card_select,
          configNme: 'specStyle',
        },
        {
          components: toolCom.c_set_up,
          configNme: 'setUp',
        },
      ],
    };
  },
  watch: {
    num(nVal) {
      let value = JSON.parse(JSON.stringify(this.$store.state.mobildConfig.defaultArray[nVal]));
      this.setConfig(value);
    },
    configObj: {
      handler(nVal, oVal) {
        this.$store.commit('mobildConfig/UPDATEARR', { num: this.num, val: nVal });
      },
      deep: true,
    },
    'configObj.setUp.tabVal': {
      handler(nVal) {
        this.setUpChange(nVal);
      },
    },
  },
  mounted() {
    this.$nextTick(() => {
      let value = JSON.parse(JSON.stringify(this.$store.state.mobildConfig.defaultArray[this.num]));
      this.setConfig(value);
      this.setUpChange(value.setUp.tabVal);
    });
  },
  methods: {
    setConfig(data) {
      if (!data) return;
      this.patchConfig(data);
      for (let key in this.defaultConfig) {
        if (data[key] === undefined) {
          this.$set(data, key, this.defaultConfig[key]);
        }
      }
      this.configObj = data;
    },
    patchConfig(data) {
      if (!data.componentBgConfig) {
        this.$set(data, 'componentBgConfig', {
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
        });
      }
      if (!data.paddingConfig) {
        this.$set(data, 'paddingConfig', {
          title: 'phần đệm',
          val: 0,
          min: 0,
          max: 100,
          isAll: false,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        });
      }
      if (!data.marginConfig) {
        this.$set(data, 'marginConfig', {
          title: 'lề',
          isAll: false,
          val: 0,
          min: 0,
          max: 100,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        });
      }
      if (!data.zIndexConfig) {
        this.$set(data, 'zIndexConfig', {
          title: 'Thành phần nổi',
          val: 0,
          min: 0,
        });
      }
      if (!data.borderConfig) {
        this.$set(data, 'borderConfig', {
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
        });
      }
      if (!data.shadowConfig) {
        this.$set(data, 'shadowConfig', {
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
        });
      }
      if (data.specSettings) {
        if (!data.specSettings.selectedBorderColor) {
          this.$set(data.specSettings, 'selectedBorderColor', {
            title: 'Chọn đường viền',
            name: 'selectedBorderColor',
            default: [{ item: '#E93323' }],
            color: [{ item: '#E93323' }],
          });
        }
        if (!data.specSettings.selectedTextColor) {
          this.$set(data.specSettings, 'selectedTextColor', {
            title: 'Chọn văn bản',
            name: 'selectedTextColor',
            default: [{ item: '#E93323' }],
            color: [{ item: '#E93323' }],
          });
        }
        if (!data.specSettings.selectedBgColor) {
          this.$set(data.specSettings, 'selectedBgColor', {
            title: 'Chọn nền',
            name: 'selectedBgColor',
            default: [{ item: '#FDEBEB' }],
            color: [{ item: '#FDEBEB' }],
          });
        }
        if (!data.specSettings.unselectedTextColor) {
          this.$set(data.specSettings, 'unselectedTextColor', {
            title: 'Không có văn bản nào được chọn',
            name: 'unselectedTextColor',
            default: [{ item: '#333333' }],
            color: [{ item: '#333333' }],
          });
        }
      }
    },
    setUpChange(val) {
      this.rCom = [
        {
          components: toolCom.c_card_select,
          configNme: 'specStyle',
        },
        {
          components: toolCom.c_set_up,
          configNme: 'setUp',
        },
      ];
      if (val == 0) {
        // Content Settings
        // Spec Style at the top

        // Product Info List (Draggable Sections)
        this.rCom.push({
          components: toolCom.c_product_info_list,
          configNme: 'sortList',
        });
      } else {
        // Style Settings
        this.rCom.push({
          components: toolCom.c_indicator_settings,
          configNme: 'indicatorConfig',
        });
        this.rCom.push({
          components: toolCom.c_title_settings,
          configNme: 'titleConfig',
        });
        this.rCom.push({
          components: toolCom.c_spec_settings,
          configNme: 'specSettings',
        });
        this.rCom.push({
          components: toolCom.c_price_settings,
          configNme: 'priceSettings',
        });
        this.rCom.push({
          components: toolCom.c_data_settings,
          configNme: 'dataSettings',
        });
        this.rCom.push({
          components: toolCom.c_common_style,
          configNme: 'c_common_style',
        });
      }
    },
    getConfig(data) {
      // Handle updates from child components if needed
    },
  },
};
</script>

<style scoped lang="scss"></style>
