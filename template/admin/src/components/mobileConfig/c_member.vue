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
import { mapState, mapMutations, mapActions } from 'vuex';
export default {
  name: 'c_member',
  componentsName: 'home_member',
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
      rCom: [
        {
          components: toolCom.c_card_select,
          configNme: 'styleConfig',
        },
        {
          components: toolCom.c_set_up,
          configNme: 'setUp',
        },
      ],
      setUp: 0,
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
    'configObj.styleConfig.tabVal': {
      handler(nVal, oVal) {
        if (nVal == 3 || nVal == 4) {
          if (this.configObj.nameColor.color[0].item == '#fff') this.configObj.nameColor.color[0].item = '#333';
          if (this.configObj.numColor.color[0].item == '#fff') this.configObj.numColor.color[0].item = '#333';
          // Màu nền cũng được thay đổi thành 333
          if (this.configObj.compo.img - box - girdnentBgConfig.colorConfig.color[0].item == '#E93323')
            this.configObj.componentBgConfig.colorConfig.color[0].item = '#333';
          if (this.configObj.componentBgConfig.colorConfig.color[1].item == '#E93323')
            this.configObj.componentBgConfig.colorConfig.color[1].item = '#333';
          if (this.configObj.dataNumColor.color[0].item == '#fff') this.configObj.dataNumColor.color[0].item = '#333';
          if (this.configObj.dataTitleColor.color[0].item == '#fff')
            this.configObj.dataTitleColor.color[0].item = '#333';
        } else {
          if (this.configObj.dataNumColor.color[0].item == '#333') this.configObj.dataNumColor.color[0].item = '#fff';
          if (this.configObj.dataTitleColor.color[0].item == '#333')
            this.configObj.dataTitleColor.color[0].item = '#fff';
          if (this.configObj.nameColor.color[0].item == '#333') this.configObj.nameColor.color[0].item = '#fff';
          if (this.configObj.numColor.color[0].item == '#333') this.configObj.numColor.color[0].item = '#fff';
          if (this.configObj.componentBgConfig.colorConfig.color[0].item == '#333')
            this.configObj.componentBgConfig.colorConfig.color[0].item = '#E93323';
          if (this.configObj.componentBgConfig.colorConfig.color[1].item == '#333')
            this.configObj.componentBgConfig.colorConfig.color[1].item = '#E93323';
        }
        this.updateRCom();
      },
      deep: true,
    },
    'configObj.memberStyleConfig.tabVal': {
      handler(nVal, oVal) {
        this.updateRCom();
      },
      deep: true,
    },
    'configObj.assetMode.tabVal': {
      handler(nVal, oVal) {
        this.updateRCom();
      },
      deep: true,
    },
    'configObj.menuConfig.listStyle': {
      handler(nVal, oVal) {
        this.updateRCom();
      },
      deep: true,
    },
    'configObj.shortcutConfig.listStyle': {
      handler(nVal, oVal) {
        this.updateRCom();
      },
      deep: true,
    },
    'configObj.assetMode.tabVal': {
      handler(nVal, oVal) {
        this.updateRCom();
      },
      deep: true,
    },
    'configObj.ms2TitleType.tabVal': {
      handler(nVal, oVal) {
        this.updateRCom();
      },
      deep: true,
    },
    'configObj.ms3BgMode.tabVal': {
      handler(nVal, oVal) {
        this.updateRCom();
      },
      deep: true,
    },
    'configObj.ms4BgMode.tabVal': {
      handler(nVal, oVal) {
        this.updateRCom();
      },
      deep: true,
    },
  },
  mounted() {
    this.$nextTick(() => {
      let value = JSON.parse(JSON.stringify(this.$store.state.mobildConfig.defaultArray[this.num]));
      this.configObj = value;
      this.configObj = this.patchConfig(this.configObj);
    });
  },
  methods: {
    updateRCom() {
      let arr = [
        {
          components: toolCom.c_card_select,
          configNme: 'styleConfig',
        },
        {
          components: toolCom.c_set_up,
          configNme: 'setUp',
        },
      ];
      if (this.setUp == 0) {
        let tempArr = [
          {
            components: toolCom.c_title,
            configNme: 'titleLeft',
          },
          {
            components: toolCom.c_radio,
            configNme: 'userInfoConfig',
          },
          {
            components: toolCom.c_radio,
            configNme: 'memberStyleConfig',
          },
        ];

        // Menu Config - Available for Style 1, 3, 5 (Index 0, 2, 4)
        if (
          this.configObj.styleConfig.tabVal == 0 ||
          this.configObj.styleConfig.tabVal == 2 ||
          this.configObj.styleConfig.tabVal == 4
        ) {
          tempArr.push({
            components: toolCom.c_menu_list,
            configNme: 'menuConfig',
          });
        }

        // Shortcut Config - Left Top
        // if (this.configObj.styleConfig.tabVal == 0 || this.configObj.styleConfig.tabVal == 2) {
        //   tempArr.push({
        //     components: toolCom.c_menu_list,
        //     configNme: 'shortcutConfig',
        //   });
        // }

        tempArr.push({
          components: toolCom.c_radio,
          configNme: 'assetMode',
        });
        if (this.configObj.assetMode.tabVal == 0) {
          tempArr.push({
            components: toolCom.c_radio,
            configNme: 'dataStyle',
          });
          tempArr.push({
            components: toolCom.c_checkbox,
            configNme: 'checkboxInfo',
          });
        } else {
          tempArr.push({
            components: toolCom.c_menu_list,
            configNme: 'assetConfig',
          });
        }
        // Member Style 1 (Index 0)
        if (this.configObj.memberStyleConfig.tabVal == 0) {
          tempArr.push({
            components: toolCom.c_menu_list,
            configNme: 'memberConfig',
          });
        }
        // Member Style 2 (Index 1)
        if (this.configObj.memberStyleConfig.tabVal == 1) {
          tempArr.push({ components: toolCom.c_title, configNme: 'infoStyleText' });
          tempArr.push({ components: toolCom.c_radio, configNme: 'ms2TitleType' });
          if (this.configObj.ms2TitleType.tabVal == 0) {
            tempArr.push({ components: toolCom.c_input_item, configNme: 'ms2TitleText' });
          } else {
            tempArr.push({ components: toolCom.c_upload_img, configNme: 'ms2TitleImage' });
          }
          tempArr.push({ components: toolCom.c_input_item, configNme: 'ms2IntroText' });
          tempArr.push({ components: toolCom.c_menu_list, configNme: 'ms2RightsList' });
          tempArr.push({ components: toolCom.c_upload_img, configNme: 'ms2ExplainIcons' });
          tempArr.push({ components: toolCom.c_input_item, configNme: 'ms2ExplainText' });
          // tempArr.push({ components: toolCom.c_bg_color, configNme: 'ms2ExplainColor' });
          tempArr.push({ components: toolCom.c_input_item, configNme: 'ms2ButtonText' });
          tempArr.push({ components: toolCom.c_input_item, configNme: 'ms2ButtonLink' });
          // tempArr.push({ components: toolCom.c_bg_color, configNme: 'ms2ButtonColor' });
          // tempArr.push({ components: toolCom.c_bg_color, configNme: 'ms2ButtonBgColor' });
        }
        // Member Style 3 (Index 2)
        if (this.configObj.memberStyleConfig.tabVal == 2) {
          tempArr.push(
            {
              components: toolCom.c_title,
              configNme: 'infoStyleText',
            },
            { components: toolCom.c_input_item, configNme: 'ms3TitleText' },
            { components: toolCom.c_input_item, configNme: 'ms3ButtonText' },
          );
        }
        // Info Style 4 (Index 3)
        if (this.configObj.styleConfig.tabVal == 3) {
          tempArr.push({
            components: toolCom.c_menu_list,
            configNme: 'rightEntryConfig',
          });
        }
        this.rCom = arr.concat(tempArr);
      } else {
        // Style Settings
        let styleArr = [
          {
            components: toolCom.c_title,
            configNme: 'infoStyleText',
          },
          {
            components: toolCom.c_bg_color,
            configNme: 'nameColor',
          },
          {
            components: toolCom.c_slider,
            configNme: 'nameSize',
          },
          {
            components: toolCom.c_bg_color,
            configNme: 'numColor',
          },
          {
            components: toolCom.c_slider,
            configNme: 'numSize',
          },

          {
            components: toolCom.c_title,
            configNme: 'iconStyleText',
          },
          ...(this.configObj.assetMode.tabVal === 0
            ? [
                {
                  components: toolCom.c_bg_color,
                  configNme: 'dataTitleColor',
                },
                {
                  components: toolCom.c_bg_color,
                  configNme: 'dataNumColor',
                },
              ]
            : [
                {
                  components: toolCom.c_bg_color,
                  configNme: 'assetIconColor',
                },
                {
                  components: toolCom.c_slider,
                  configNme: 'assetIconSize',
                },
                {
                  components: toolCom.c_bg_color,
                  configNme: 'assetTextColor',
                },
                {
                  components: toolCom.c_slider,
                  configNme: 'assetTextSize',
                },
              ]),

          {
            components: toolCom.c_title,
            configNme: 'memberStyleText',
          },
          {
            components: toolCom.c_common_style,
            configNme: 'c_common_style',
          },
        ];
        const memberStyleIndex = styleArr.findIndex((item) => item.configNme === 'memberStyleText');
        if (this.configObj.memberStyleConfig.tabVal !== 2 && this.configObj.memberStyleConfig.tabVal !== 3) {
          styleArr.splice(memberStyleIndex + 1, 0, {
            components: toolCom.c_bg_color,
            configNme: 'cardBgColor',
          });
        }
        // Style 4 Module Styles
        if (this.configObj.styleConfig.tabVal == 3) {
          let assetConfigIndex = styleArr.findIndex((item) => item.configNme === 'assetConfigText');
          if (assetConfigIndex !== -1) {
            styleArr.splice(assetConfigIndex, 0, {
              components: toolCom.c_title,
              configNme: 'moduleStyleText',
            });
            styleArr.splice(assetConfigIndex + 1, 0, {
              components: toolCom.c_bg_color,
              configNme: 'moduleBgColor',
            });
            styleArr.splice(assetConfigIndex + 2, 0, {
              components: toolCom.c_bg_color,
              configNme: 'moduleTextColor',
            });
            styleArr.splice(assetConfigIndex + 3, 0, {
              components: toolCom.c_fillet,
              configNme: 'moduleRadius',
            });
          }
          styleArr.push({
            components: toolCom.c_fillet,
            configNme: 'cardBgRadius',
          });
        }
        // Member Style 2 Styles
        if (this.configObj.memberStyleConfig.tabVal == 1) {
          let ms2StyleArr = [];
          if (this.configObj.ms2TitleType.tabVal == 0) {
            ms2StyleArr.push({
              components: toolCom.c_bg_color,
              configNme: 'ms2TitleColor',
            });
          }
          ms2StyleArr.push(
            {
              components: toolCom.c_bg_color,
              configNme: 'ms2IntroColor',
            },
            {
              components: toolCom.c_bg_color,
              configNme: 'ms2RightsColor',
            },
            {
              components: toolCom.c_bg_color,
              configNme: 'ms2ExplainColor',
            },
            {
              components: toolCom.c_bg_color,
              configNme: 'ms2ButtonBgColor',
            },
            {
              components: toolCom.c_bg_color,
              configNme: 'ms2ButtonColor',
            },
            {
              components: toolCom.c_fillet,
              configNme: 'cardBgRadius',
            },
          );
          let memberStyleIndex = styleArr.findIndex((item) => item.configNme === 'memberStyleText');
          if (memberStyleIndex !== -1) {
            styleArr.splice(memberStyleIndex + 1, 0, ...ms2StyleArr);
          }
        }
        // Member Style 3 Styles
        if (this.configObj.memberStyleConfig.tabVal == 2) {
          let memberStyleIndex = styleArr.findIndex((item) => item.configNme === 'memberStyleText');
          if (memberStyleIndex !== -1) {
            styleArr.splice(memberStyleIndex + 1, 0, {
              components: toolCom.c_radio,
              configNme: 'ms3BgMode',
            });
            if (this.configObj.ms3BgMode && this.configObj.ms3BgMode.tabVal === 0) {
              styleArr.splice(memberStyleIndex + 2, 0, {
                components: toolCom.c_bg_color,
                configNme: 'cardBgColor',
              });
            } else {
              styleArr.splice(memberStyleIndex + 2, 0, {
                components: toolCom.c_upload_img,
                configNme: 'ms3BackgroundImage',
              });
            }

            let ms3StyleArr = [
              {
                components: toolCom.c_bg_color,
                configNme: 'ms3TitleColor',
              },
              {
                components: toolCom.c_bg_color,
                configNme: 'ms3ButtonColor',
              },
              {
                components: toolCom.c_margin_style,
                configNme: 'ms3PaddingConfig',
              },
              {
                components: toolCom.c_fillet,
                configNme: 'cardBgRadius',
              },
            ];
            styleArr.splice(memberStyleIndex + 3, 0, ...ms3StyleArr);
          }
        }
        // Member Style 4 Styles
        if (this.configObj.memberStyleConfig.tabVal == 3) {
          let memberStyleIndex = styleArr.findIndex((item) => item.configNme === 'memberStyleText');
          if (memberStyleIndex !== -1) {
            styleArr.splice(memberStyleIndex + 1, 0, {
              components: toolCom.c_radio,
              configNme: 'ms4BgMode',
            });
            if (this.configObj.ms4BgMode && this.configObj.ms4BgMode.tabVal === 0) {
              styleArr.splice(memberStyleIndex + 2, 0, {
                components: toolCom.c_bg_color,
                configNme: 'cardBgColor',
              });
            } else {
              styleArr.splice(memberStyleIndex + 2, 0, {
                components: toolCom.c_upload_img,
                configNme: 'ms4BackgroundImage',
              });
            }
          }
        }
        if (
          this.configObj.styleConfig.tabVal == 0 ||
          this.configObj.styleConfig.tabVal == 2 ||
          this.configObj.styleConfig.tabVal == 4
        ) {
          let isMenuIcon = this.configObj.menuConfig && this.configObj.menuConfig.listStyle == 1;
          let isShortcutIcon = this.configObj.shortcutConfig && this.configObj.shortcutConfig.listStyle == 1;
          if (isMenuIcon || isShortcutIcon) {
            styleArr.splice(6, 0, {
              components: toolCom.c_icon_style,
              configNme: 'iconStyleConfig',
            });
          }
        }
        this.rCom = arr.concat(styleArr);
      }
    },
    patchConfig(data) {
      if (!data) return data;
      if (!data.assetIconColor) {
        this.$set(data, 'assetIconColor', {
          title: 'màu biểu tượng',
          default: [{ item: '#fff' }],
          color: [{ item: '#fff' }],
        });
      }
      if (!data.assetIconSize) {
        this.$set(data, 'assetIconSize', {
          title: 'kích thước biểu tượng',
          val: 24,
          min: 10,
          max: 32,
        });
      }
      if (!data.assetTextColor) {
        this.$set(data, 'assetTextColor', {
          title: 'màu văn bản',
          default: [{ item: '#fff' }],
          color: [{ item: '#fff' }],
        });
      }
      if (!data.assetTextSize) {
        this.$set(data, 'assetTextSize', {
          title: 'kích thước văn bản',
          val: 12,
          min: 10,
          max: 32,
        });
      }
      if (data.styleConfig && data.styleConfig.tabList.length < 5) {
        data.styleConfig.tabList.push({ name: 'phong cách năm' });
      }
      if (!data.nameColor) {
        this.$set(data, 'nameColor', {
          title: 'Màu biệt hiệu',
          default: [{ item: '#fff' }],
          color: [{ item: '#fff' }],
        });
      }
      if (!data.nameSize) {
        this.$set(data, 'nameSize', {
          title: 'Văn bản biệt hiệu',
          val: 16,
          min: 10,
          max: 30,
        });
      }
      if (!data.numColor) {
        this.$set(data, 'numColor', {
          title: 'ID/Số điện thoại',
          default: [{ item: '#fff' }],
          color: [{ item: '#fff' }],
        });
      }
      if (!data.numSize) {
        this.$set(data, 'numSize', {
          title: 'kích thước văn bản',
          val: 10,
          min: 10,
          max: 30,
        });
      }
      if (!data.userInfoConfig) {
        this.$set(data, 'userInfoConfig', {
          title: 'Thông tin người dùng',
          tabVal: 0,
          tabList: [{ name: 'Số điện thoại' }, { name: 'ID' }],
        });
      }
      if (!data.memberStyleConfig) {
        this.$set(data, 'memberStyleConfig', {
          title: 'phong cách thành viên',
          tabVal: 0,
          tabList: [{ name: 'phong cách một' }, { name: 'Phong cách 2' }, { name: 'phong cách ba' }, { name: 'phong cách bốn' }],
        });
      }
      if (!data.iconStyleConfig) {
        this.$set(data, 'iconStyleConfig', {
          title: 'phong cách biểu tượng',
          name: 'iconStyleConfig',
          type: 1,
          color: {
            title: 'màu sắc',
            default: [{ item: '#fff' }],
            color: [{ item: '#fff' }],
          },
          size: {
            title: 'kích cỡ',
            val: 20,
            min: 12,
            max: 100,
          },
          padding: {
            title: 'phần đệm',
            val: 0,
            min: 0,
            max: 100,
          },
          rotate: {
            title: 'quay',
            val: 0,
            min: 0,
            max: 360,
          },
        });
      }
      if (!data.dataTitleColor) {
        this.$set(data, 'dataTitleColor', {
          title: 'màu tiêu đề',
          default: [{ item: '#fff' }],
          color: [{ item: '#fff' }],
        });
      }
      if (!data.dataNumColor) {
        this.$set(data, 'dataNumColor', {
          title: 'màu kỹ thuật số',
          default: [{ item: '#fff' }],
          color: [{ item: '#fff' }],
        });
      }
      if (!data.zIndexConfig) {
        this.$set(data, 'zIndexConfig', {
          title: 'Thành phần nổi',
          val: 0,
          min: 0,
        });
      }
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
      if (data.borderConfig && data.borderConfig.styleConfig && !data.borderConfig.styleConfig.tabList) {
        this.$set(data.borderConfig, 'styleConfig', {
          title: 'phong cách biên giới',
          tabVal: 0,
          tabList: [
            { name: 'đường liền nét', style: 'solid' },
            { name: 'đường chấm chấm', style: 'dashed' },
            { name: 'Say mê', style: 'dotted' },
          ],
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
      if (!data.menuConfig) {
        this.$set(data, 'menuConfig', {
          title: 'Cài đặt nội dung hoạt động',
          listStyleName: 'phong cách hiển thị',
          bnt: 'Thêm mới',
          type: 1,
          listStyle: 0,
          maxList: 2,
          list: [
            {
              img: '',
              type: 0,
              show: true,
              icon: '',
              info: [
                {
                  title: 'tiêu đề',
                  value: 'tiêu đề',
                  tips: 'Tùy chọn, không quá 4 từ',
                  max: 4,
                },
                {
                  title: 'liên kết',
                  value: '',
                  tips: 'Vui lòng nhập liên kết',
                  max: 100,
                },
              ],
            },
            {
              img: '',
              type: 0,
              show: true,
              icon: '',
              info: [
                {
                  title: 'tiêu đề',
                  value: 'tiêu đề',
                  tips: 'Tùy chọn, không quá 4 từ',
                  max: 4,
                },
                {
                  title: 'liên kết',
                  value: '',
                  tips: 'Vui lòng nhập liên kết',
                  max: 100,
                },
              ],
            },
          ],
        });
      }

      if (!data.assetMode) {
        this.$set(data, 'assetMode', {
          title: 'chế độ hiển thị',
          tabVal: 0,
          tabList: [{ name: 'Hiển thị dữ liệu' }, { name: 'Hiển thị hình ảnh và văn bản' }],
        });
      }
      if (!data.dataStyle) {
        this.$set(data, 'dataStyle', {
          title: 'Bố cục dữ liệu',
          tabVal: 0,
          tabList: [{ name: 'Số-Văn bản(thẳng đứng)' }, { name: 'chữ-số(nằm ngang)' }, { name: 'chữ-số(thẳng đứng)' }],
        });
      }
      if (!data.checkboxInfo) {
        this.$set(data, 'checkboxInfo', {
          title: 'Lựa chọn dữ liệu',
          name: 'checkboxInfo',
          maxList: 5,
          type: [1, 2, 3],
          list: [
            { id: 1, name: 'Số dư' },
            { id: 2, name: 'điểm thưởng' },
            { id: 3, name: 'Mã giảm giá' },
            { id: 4, name: 'sưu tầm' },
            { id: 5, name: 'Lịch sử duyệt web' },
          ],
        });
      }
      if (!data.ms3BgMode) {
        this.$set(data, 'ms3BgMode', {
          title: 'Cài đặt nền',
          tabVal: 0,
          tabList: [
            { name: 'màu nền', val: 0 },
            { name: 'hình nền', val: 1 },
          ],
        });
      }
      if (!data.ms3BgColor) {
        this.$set(data, 'ms3BgColor', {
          title: 'màu nền',
          default: [{ item: '#fff' }, { item: '#fff' }],
          color: [{ item: '#fff' }, { item: '#fff' }],
        });
      }
      if (!data.ms3BackgroundImage) {
        this.$set(data, 'ms3BackgroundImage', {
          title: '',
          url: '',
        });
      }
      if (!data.ms4BgMode) {
        this.$set(data, 'ms4BgMode', {
          title: 'Cài đặt nền',
          tabVal: 0,
          tabList: [
            { name: 'màu nền', val: 0 },
            { name: 'hình nền', val: 1 },
          ],
        });
      }
      if (!data.ms4BgColor) {
        this.$set(data, 'ms4BgColor', {
          title: 'màu nền',
          default: [{ item: '#fff' }, { item: '#fff' }],
          color: [{ item: '#fff' }, { item: '#fff' }],
        });
      }
      if (!data.ms4BackgroundImage) {
        this.$set(data, 'ms4BackgroundImage', {
          title: '',
          url: '',
        });
      }

      if (!data.assetConfig) {
        this.$set(data, 'assetConfig', {
          title: 'Nhập nhanh',
          listStyle: 0,
          maxList: 5,
          bnt: 'Thêm mới',
          list: [
            {
              img: '',
              icon: 'icon-yue',
              info: [
                { title: 'tiêu đề', value: 'Số dư', tips: 'Tùy chọn, không quá 4 từ', max: 4 },
                { title: 'liên kết', value: '/pages/users/user_money/index', tips: 'Vui lòng nhập liên kết', max: 100 },
              ],
            },
            {
              img: '',
              icon: 'icon-jifen',
              info: [
                { title: 'tiêu đề', value: 'điểm thưởng', tips: 'Tùy chọn, không quá 4 từ', max: 4 },
                { title: 'liên kết', value: '/pages/users/user_integral/index', tips: 'Vui lòng nhập liên kết', max: 100 },
              ],
            },
            {
              img: '',
              icon: 'icon-youhuiquan',
              info: [
                { title: 'tiêu đề', value: 'Mã giảm giá', tips: 'Tùy chọn, không quá 4 từ', max: 4 },
                { title: 'liên kết', value: '/pages/users/user_coupon/index', tips: 'Vui lòng nhập liên kết', max: 100 },
              ],
            },
            {
              img: '',
              icon: 'icon-shoucang',
              info: [
                { title: 'tiêu đề', value: 'sưu tầm', tips: 'Tùy chọn, không quá 4 từ', max: 4 },
                { title: 'liên kết', value: '/pages/users/user_goods_collection/index', tips: 'Vui lòng nhập liên kết', max: 100 },
              ],
            },
            {
              img: '',
              icon: 'icon-zuji',
              info: [
                { title: 'tiêu đề', value: 'Lịch sử duyệt web', tips: 'Tùy chọn, không quá 4 từ', max: 4 },
                { title: 'liên kết', value: '/pages/users/user_visit/index', tips: 'Vui lòng nhập liên kết', max: 100 },
              ],
            },
          ],
        });
      }

      if (!data.paddingConfig) {
        this.$set(data, 'paddingConfig', {
          isAll: false,
          title: 'phần đệm',
          val: 15,
          min: 0,
          max: 100,
          valList: [{ val: 15 }, { val: 15 }, { val: 15 }, { val: 15 }],
        });
      }
      if (!data.marginConfig) {
        this.$set(data, 'marginConfig', {
          isAll: false,
          title: 'lề',
          val: 0,
          min: 0,
          max: 100,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
        });
      }
      if (!data.rightEntryConfig) {
        this.$set(data, 'rightEntryConfig', {
          title: 'Lối vào bên phải',
          listStyleName: 'phong cách hiển thị',
          bnt: 'Thêm mới',
          type: 1,
          listStyle: -1,
          maxList: 1,
          list: [
            {
              img: '',
              type: 0,
              show: true,
              icon: '',
              info: [
                {
                  title: 'tiêu đề',
                  value: 'Trung tâm mua sắm điểm',
                  tips: 'Tùy chọn, không quá 4 từ',
                  max: 4,
                },
                {
                  title: 'mô tả',
                  value: 'Điểm có thể đổi lấy đồ tốt',
                  tips: 'Tùy chọn, không quá 6 từ',
                  max: 6,
                },
                {
                  title: 'liên kết',
                  value: '',
                  tips: 'Vui lòng nhập liên kết',
                  max: 100,
                },
              ],
            },
          ],
        });
      }
      if (!data.leftMenuConfig) {
        this.$set(data, 'leftMenuConfig', {
          title: 'Nội dung bên trái',
          listStyleName: 'phong cách hiển thị',
          bnt: 'Thêm mới',
          type: 1,
          listStyle: 1,
          maxList: 3,
          list: [
            {
              img: '',
              type: 0,
              show: true,
              icon: 'icon-yue',
              info: [
                {
                  title: 'tiêu đề',
                  value: 'Số dư',
                  tips: 'Tùy chọn, không quá 4 từ',
                  max: 4,
                },
                {
                  title: 'liên kết',
                  value: '',
                  tips: 'Vui lòng nhập liên kết',
                  max: 100,
                },
              ],
            },
            {
              img: '',
              type: 0,
              show: true,
              icon: 'icon-jifen',
              info: [
                {
                  title: 'tiêu đề',
                  value: 'điểm thưởng',
                  tips: 'Tùy chọn, không quá 4 từ',
                  max: 4,
                },
                {
                  title: 'liên kết',
                  value: '',
                  tips: 'Vui lòng nhập liên kết',
                  max: 100,
                },
              ],
            },
            {
              img: '',
              type: 0,
              show: true,
              icon: 'icon-youhuiquan',
              info: [
                {
                  title: 'tiêu đề',
                  value: 'Mã giảm giá',
                  tips: 'Tùy chọn, không quá 4 từ',
                  max: 4,
                },
                {
                  title: 'liên kết',
                  value: '',
                  tips: 'Vui lòng nhập liên kết',
                  max: 100,
                },
              ],
            },
          ],
        });
      }
      if (!data.memberConfig) {
        this.$set(data, 'memberConfig', {
          listStyleName: 'phong cách hiển thị',
          bnt: 'Thêm mới',
          type: 1,
          listStyle: -1,
          maxList: 2,
          list: [
            {
              img: '',
              type: 0,
              show: true,
              icon: '',
              info: [
                {
                  title: 'tiêu đề',
                  value: 'Trung tâm thành viên',
                  tips: 'Tùy chọn, không quá 4 từ',
                  max: 4,
                },
                {
                  title: 'mô tả',
                  value: 'Xem lợi ích mới',
                  tips: 'Tùy chọn, không quá 6 từ',
                  max: 6,
                },
                {
                  title: 'liên kết',
                  value: '',
                  tips: 'Vui lòng nhập liên kết',
                  max: 100,
                },
              ],
            },
            {
              img: '',
              type: 0,
              show: true,
              icon: '',
              info: [
                {
                  title: 'tiêu đề',
                  value: 'Trung tâm mua sắm điểm',
                  tips: 'Tùy chọn, không quá 4 từ',
                  max: 4,
                },
                {
                  title: 'mô tả',
                  value: 'Mã giảm giá phiên bản giới hạn',
                  tips: 'Tùy chọn, không quá 6 từ',
                  max: 6,
                },
                {
                  title: 'liên kết',
                  value: '',
                  tips: 'Vui lòng nhập liên kết',
                  max: 100,
                },
              ],
            },
          ],
        });
      }

      // Member Style 2 Configs
      if (!data.ms2TitleType) {
        this.$set(data, 'ms2TitleType', {
          title: 'Loại tiêu đề',
          tabVal: 0,
          tabList: [{ name: 'Từ' }, { name: 'hình ảnh' }],
        });
      }
      if (!data.ms2TitleText) {
        this.$set(data, 'ms2TitleText', {
          title: 'văn bản tiêu đề',
          value: 'SVIP thành viên',
          max: 10,
        });
      }
      if (!data.ms2TitleColor) {
        this.$set(data, 'ms2TitleColor', {
          title: 'màu tiêu đề',
          default: [{ item: '#8B572A' }],
          color: [{ item: '#8B572A' }],
        });
      }
      if (!data.ms2TitleImage) {
        this.$set(data, 'ms2TitleImage', {
          header: '',
          title: '',
          name: 'hình ảnh tiêu đề',
          type: 'code',
          url: '',
          info: 'Kích thước đề xuất：162px * 36px',
        });
      }
      if (!data.ms2IntroText) {
        this.$set(data, 'ms2IntroText', {
          title: 'văn bản giới thiệu',
          value: 'Tận hưởng giảm giá 92% khi mua sắm tại trung tâm thương mại',
          max: 20,
        });
      }
      if (!data.ms2IntroColor) {
        this.$set(data, 'ms2IntroColor', {
          title: 'Giới thiệu về màu sắc',
          default: [{ item: '#8B572A' }],
          color: [{ item: '#8B572A' }],
        });
      }
      if (!data.ms2RightsList) {
        this.$set(data, 'ms2RightsList', {
          title: 'biểu tượng vốn chủ sở hữu',
          listStyleName: 'Đề xuất: 40px*40px; kéo phần bằng chuột để điều chỉnh thứ tự hình ảnh',
          bnt: 'Thêm mới',
          type: 1,
          listStyle: -1,
          maxList: 2,
          list: [
            {
              img: '',
              icon: 'icon-zk',
              info: [
                { title: 'tiêu đề', value: 'giảm giá mua sắm', tips: 'Tùy chọn, không quá 4 từ', max: 6 },
                { title: 'liên kết', value: '', tips: 'Vui lòng nhập liên kết', max: 100 },
              ],
            },
            {
              img: '',
              icon: 'icon-mz',
              info: [
                { title: 'tiêu đề', value: 'Huy hiệu độc quyền', tips: 'Tùy chọn, không quá 4 từ', max: 6 },
                { title: 'liên kết', value: '', tips: 'Vui lòng nhập liên kết', max: 100 },
              ],
            },
          ],
        });
      }
      if (!data.ms2ExplainIcons) {
        this.$set(data, 'ms2ExplainIcons', {
          header: '',
          title: '',
          name: 'Hình ảnh minh họa',
          type: 'code',
          url: '',
          delType: 1,
          info: 'gợi ý：94px * 32px',
        });
      }
      if (!data.ms2ExplainText) {
        this.$set(data, 'ms2ExplainText', {
          title: 'văn bản mô tả',
          value: 'Tìm hiểu thêm các kỹ thuật nâng cấp nhanh',
          max: 20,
        });
      }
      if (!data.ms2ExplainColor) {
        this.$set(data, 'ms2ExplainColor', {
          title: 'Màu mô tả',
          default: [{ item: '#8B572A' }],
          color: [{ item: '#8B572A' }],
        });
      }
      if (!data.ms2ButtonText) {
        this.$set(data, 'ms2ButtonText', {
          title: 'văn bản nút',
          value: 'để có được',
          max: 6,
        });
      }
      if (!data.ms2ButtonLink) {
        this.$set(data, 'ms2ButtonLink', {
          title: 'liên kết nút',
          value: '/pages/users/user_vip/index',
          max: 100,
          type: 'link',
        });
      }
      if (!data.ms2ButtonColor) {
        this.$set(data, 'ms2ButtonColor', {
          title: 'màu văn bản',
          default: [{ item: '#5A350C' }],
          color: [{ item: '#5A350C' }],
        });
      }
      if (!data.ms2ButtonBgColor) {
        this.$set(data, 'ms2ButtonBgColor', {
          title: 'màu nền',
          default: [{ item: '#F6D99D' }],
          color: [{ item: '#F6D99D' }],
        });
      }
      if (!data.ms2RightsColor) {
        this.$set(data, 'ms2RightsColor', {
          title: 'văn bản vốn chủ sở hữu',
          default: [{ item: '#8B572A' }],
          color: [{ item: '#8B572A' }],
        });
      }

      if (!data.moduleStyleText) {
        this.$set(data, 'moduleStyleText', 'phong cách mô-đun');
      }
      if (!data.moduleBgColor) {
        this.$set(data, 'moduleBgColor', {
          title: 'nền mô-đun',
          default: [{ item: '#fff' }, { item: '#fff' }],
          color: [{ item: '#fff' }, { item: '#fff' }],
        });
      }
      if (!data.moduleTextColor) {
        this.$set(data, 'moduleTextColor', {
          title: 'văn bản mô-đun',
          default: [{ item: '#333' }],
          color: [{ item: '#333' }],
        });
      }
      if (!data.moduleRadius) {
        this.$set(data, 'moduleRadius', {
          title: 'Mô-đun góc tròn',
          type: 0,
          list: [
            { val: 'Tất cả', icon: 'iconcaozuo-zhengti' },
            { val: 'đơn', icon: 'iconcaozuo-bianjiao' },
          ],
          valName: 'Giá trị phi lê',
          val: 8,
          min: 0,
          max: 100,
          valList: [{ val: 8 }, { val: 8 }, { val: 8 }, { val: 8 }],
        });
      }
      if (!data.cardBgColor) {
        this.$set(data, 'cardBgColor', {
          title: 'Màu nền thành viên',
          default: [{ item: '#fff' }, { item: '#fff' }],
          color: [{ item: '#fff' }, { item: '#fff' }],
        });
      }
      if (!data.cardBgRadius) {
        this.$set(data, 'cardBgRadius', {
          title: 'Nền thành viên được bo tròn góc',
          type: 0,
          list: [
            { val: 'Tất cả', icon: 'iconcaozuo-zhengti' },
            { val: 'đơn', icon: 'iconcaozuo-bianjiao' },
          ],
          valName: 'Giá trị phi lê',
          val: 10,
          min: 0,
          max: 100,
          valList: [{ val: 10 }, { val: 10 }, { val: 10 }, { val: 10 }],
        });
      }
      // Member Style 3 Configs
      if (!data.ms3TitleText) {
        this.$set(data, 'ms3TitleText', {
          title: 'văn bản mô tả',
          value: 'Trở thành thành viên và tận hưởng nhiều lợi ích hơn',
          max: 20,
        });
      }
      if (!data.ms3TitleColor) {
        this.$set(data, 'ms3TitleColor', {
          title: 'Màu mô tả',
          default: [{ item: '#333' }],
          color: [{ item: '#333' }],
        });
      }
      if (!data.ms3ButtonText) {
        this.$set(data, 'ms3ButtonText', {
          title: 'văn bản nút',
          value: 'Kích hoạt ngay bây giờ',
          max: 10,
        });
      }
      if (!data.ms3ButtonColor) {
        this.$set(data, 'ms3ButtonColor', {
          title: 'màu nút',
          default: [{ item: '#e93323' }],
          color: [{ item: '#e93323' }],
        });
      }
      if (!data.ms3PaddingConfig) {
        this.$set(data, 'ms3PaddingConfig', {
          title: 'phần đệm',
          val: 10,
          min: 0,
          max: 100,
          valList: [{ val: 10 }, { val: 10 }, { val: 10 }, { val: 10 }],
        });
      }
      if (!data.ms3BackgroundImage) {
        this.$set(data, 'ms3BackgroundImage', {
          header: '',
          title: '',
          name: 'Tải ảnh lên',
          type: 'code',
          url: '',
          info: 'Khuyến nghị: rộng662px*92px',
        });
      }
      if (!data.ms4BackgroundImage) {
        this.$set(data, 'ms4BackgroundImage', {
          header: '',
          title: '',
          name: 'Tải ảnh lên',
          type: 'code',
          url: '',
          info: 'gợi ý：750px * 200px',
        });
      }
      return data;
    },
    // Lấy tham số thành phần
    getConfig(data) {},
  },
};
</script>
