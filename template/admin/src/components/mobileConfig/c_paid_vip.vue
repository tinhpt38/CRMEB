<template>
  <div class="mobile-config pro">
    <div v-for="(item, key) in rCom" :key="key">
      <component
        :is="item.components.name"
        :configObj="configObj"
        ref="childData"
        :configNme="item.configNme"
        :key="key"
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
import { mapState, mapMutations, mapActions } from 'vuex';

export default {
  name: 'c_paid_vip',
  cname: 'Thành viên trả phí',
  componentsName: 'home_paid_vip',
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
      setUp: 0,
      rCom: [
        {
          components: toolCom.c_set_up,
          configNme: 'setUp',
        },
      ],
      // Các mục cấu hình cài đặt nội dung
      contentConfig: [
        {
          components: toolCom.c_title,
          configNme: 'titleContent',
        },
        {
          components: toolCom.c_upload_img,
          configNme: 'imgConfig',
        },
        {
          components: toolCom.c_input_item,
          configNme: 'rightBntConfig',
        },
      ],
    };
  },
  watch: {
    num(nVal) {
      let value = JSON.parse(JSON.stringify(this.$store.state.mobildConfig.defaultArray[nVal]));
      this.configObj = this.patchConfig(value);
    },
    configObj: {
      handler(nVal, oVal) {
        this.$store.commit('mobildConfig/UPDATEARR', { num: this.num, val: nVal });
      },
      deep: true,
    },
    'configObj.setUp.tabVal': {
      handler(nVal, oVal) {
        this.setUp = nVal;
        this.updateRCom();
      },
      deep: true,
    },
    'configObj.toneConfig.tabVal': {
      handler(nVal, oVal) {
        if (this.setUp === 1) {
          // Only update if currently in style tab
          this.updateRCom();
        }
      },
      deep: true,
    },
  },
  mounted() {
    this.$nextTick(() => {
      let value = JSON.parse(JSON.stringify(this.$store.state.mobildConfig.defaultArray[this.num]));
      this.configObj = this.patchConfig(value);
    });
  },
  methods: {
    patchConfig(data) {
      if (!data) return data;
      if (!data.paddingConfig) {
        this.$set(data, 'paddingConfig', {
          title: 'phần đệm',
          isAll: false,
          val: 0,
          min: 0,
          max: 100,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        });
        if (data.topConfig) data.paddingConfig.valList[0].val = data.topConfig.val;
        if (data.prConfig) {
          data.paddingConfig.valList[1].val = data.prConfig.val;
          data.paddingConfig.valList[3].val = data.prConfig.val;
        }
        if (data.bottomConfig) data.paddingConfig.valList[2].val = data.bottomConfig.val;
      }
      if (!data.marginConfig) {
        this.$set(data, 'marginConfig', {
          title: 'lề',
          val: 0,
          min: 0,
          max: 100,
          isAll: false,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        });
        if (data.mbConfig) data.marginConfig.valList[0].val = data.mbConfig.val;
      }
      if (!data.borderConfig) {
        this.$set(data, 'borderConfig', {
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
        });
      }
      if (!data.shadowConfig) {
        this.$set(data, 'shadowConfig', {
          title: 'Cài đặt bóng',
          tabVal: 0,
          tabList: [{ name: 'trốn' }, { name: 'trình diễn' }],
          val: 0, // 0: Hide, 1: Show
          colorConfig: {
            title: 'màu bóng',
            default: [{ item: '#e5e5e5' }],
            color: [{ item: '#e5e5e5' }],
          },
        });
      }
      if (!data.componentBgConfig) {
        this.$set(data, 'componentBgConfig', {
          title: 'Nền thành phần',
          tabVal: 0,
          tabList: [{ name: 'màu sắc' }, { name: 'hình ảnh' }],
          colorConfig: {
            title: 'màu nền',
            default: [{ item: '#fff' }],
            color: [{ item: '#fff' }],
          },
          imageConfig: {
            url: '',
            type: 'code',
            name: 'hình nền',
          },
        });
      }
      if (!data.fillet) {
        this.$set(data, 'fillet', {
          title: 'Nền bo tròn các góc',
          type: 0,
          list: [
            { val: 'tất cả', icon: 'iconcaozuo-zhengti' },
            { val: 'đơn', icon: 'iconcaozuo-bianjiao' },
          ],
          valName: 'Giá trị phi lê',
          val: 8,
          min: 0,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        });
      }
      if (!data.imgConfig) {
        this.$set(data, 'imgConfig', {
          info: 'gợi ý：36px * 36px',
          url: require('@/assets/images/goods_vip.png'),
          type: 'code',
          delType: 0,
          name: 'Hình ảnh thành viên',
        });
      }
      if (!data.rightBntConfig) {
        this.$set(data, 'rightBntConfig', {
          title: 'nút bên phải',
          value: 'Kích hoạt ngay bây giờ',
          place: 'Vui lòng nhập văn bản nút',
          max: 6,
        });
      }
      if (!data.c_common_style) {
        this.$set(data, 'c_common_style', {
          color: {
            title: 'màu nền',
            val: '',
            name: 'bgColor',
          },
          color2: {
            title: 'màu đường',
            val: '',
            name: 'lineColor',
          },
          lr: {
            title: 'lề trái và lề phải',
            val: 0,
            min: 0,
            max: 100,
          },
          type: 0,
        });
      }
      return data;
    },
    // Cập nhật danh sách thành phần cấu hình
    updateRCom() {
      var arr = [this.rCom[0]]; // Giữ thành phần setUp đầu tiên

      if (this.setUp == 0) {
        // Cài đặt nội dung
        this.rCom = arr.concat(this.contentConfig);
      } else {
        // Cài đặt kiểu
        let styleArr = [
          {
            components: toolCom.c_title,
            configNme: 'titleStyle',
          },
          {
            components: toolCom.c_radio,
            configNme: 'toneConfig',
          },
        ];

        // Show color settings if Custom Tone (tabVal == 1)
        if (this.configObj.toneConfig && this.configObj.toneConfig.tabVal == 1) {
          styleArr = styleArr.concat([
            {
              components: toolCom.c_bg_color,
              configNme: 'tipsColor',
            },
            {
              components: toolCom.c_bg_color,
              configNme: 'moneyColor',
            },
            {
              components: toolCom.c_bg_color,
              configNme: 'btnColor',
            },
            {
              components: toolCom.c_slider,
              configNme: 'btnConfig',
            },
          ]);
        }

        // Common Styles
        styleArr = styleArr.concat([
          {
            components: toolCom.c_common_style,
            configNme: 'c_common_style',
          },
        ]);

        this.rCom = arr.concat(styleArr);
      }
    },
    handleSubmit(name) {
      let obj = {};
      obj.activeIndex = this.activeIndex;
      obj.data = this.configObj;
      this.add(obj);
    },
    ...mapMutations({
      add: 'mobildConfig/UPDATEARR',
    }),
  },
};
</script>

<style scoped></style>
