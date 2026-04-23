<template>
  <common_wrapper :config="configObj" v-if="!isHide">
    <div class="product-service">
      <!-- Hoạt động -->
      <div class="item" v-if="checkList.includes(0)">
        <div class="label" :style="{ color: titleColor }">Hoạt động</div>
        <div class="content">
          <div class="tags">
            <span class="tag" :style="tagStyle"
              ><span class="mb-iconfont icon-ic_user1"></span>Hai người cùng chơi<span
                class="iconfont iconyou"
                :style="{ color: activityColor }"
              ></span
            ></span>
            <span class="tag" :style="tagStyle"
              ><span class="mb-iconfont icon-miaosha1"></span>Khuyến mại chớp nhoáng trong thời gian có hạn<span
                class="iconfont iconyou"
                :style="{ color: activityColor }"
              ></span
            ></span>
            <span class="tag" :style="tagStyle"
              ><span class="mb-iconfont icon-ic_sale"></span>Tham gia thương lượng<span
                class="iconfont iconyou"
                :style="{ color: activityColor }"
              ></span
            ></span>
          </div>
          <span class="iconfont iconyou" :style="{ color: contentColor }"></span>
        </div>
      </div>
      <!-- chọn -->
      <div class="item" v-if="checkList.includes(1)">
        <div class="label" :style="{ color: titleColor }">chọn</div>
        <div class="content">
          <span :style="{ color: contentColor }">đen,80ml</span>
          <span class="iconfont iconyou" :style="{ color: contentColor }"></span>
        </div>
      </div>
      <!-- tham số -->
      <div class="item" v-if="checkList.includes(2)">
        <div class="label" :style="{ color: titleColor }">tham số</div>
        <div class="content">
          <span :style="{ color: contentColor }">Giảm 85% · Vải polyester</span>
          <span class="iconfont iconyou" :style="{ color: contentColor }"></span>
        </div>
      </div>
      <!-- Phục vụ -->
      <div class="item" v-if="checkList.includes(3)">
        <div class="label" :style="{ color: titleColor }">Phục vụ</div>
        <div class="content">
          <span :style="{ color: contentColor }">Đảm bảo sản phẩm đích thực · Đổi trả trong vòng 7 ngày không cần lý do · Bảo hiểm vận chuyển hàng trả lại...</span>
          <span class="iconfont iconyou" :style="{ color: contentColor }"></span>
        </div>
      </div>
    </div>
  </common_wrapper>
</template>

<script>
import { mapState } from 'vuex';
export default {
  name: 'home_product_service',
  cname: 'Hàng hóa và Dịch vụ',
  configName: 'c_product_service',
  icon: '#iconzujian-shangpinfuwu', // Need a suitable icon, using placeholder
  type: 3, // 0 Thành phần cơ bản 1 Thành phần tiếp thị 2 Thành phần công cụ 3 Thành phần sản phẩm 4 Thành phần người dùng
  defaultName: 'productService',
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
    isHide() {
      return this.configObj ? this.configObj.isHide : true;
    },
    checkList() {
      return this.configObj && this.configObj.checkBoxConfig ? this.configObj.checkBoxConfig.type : [];
    },
    titleColor() {
      return this.configObj && this.configObj.titleColor ? this.configObj.titleColor.color[0].item : '#999999';
    },
    contentColor() {
      return this.configObj && this.configObj.contentColor ? this.configObj.contentColor.color[0].item : '#333333';
    },
    isCustomTone() {
      return this.configObj && this.configObj.toneConfig && this.configObj.toneConfig.tabVal === 1;
    },
    tagStyle() {
      if (this.isCustomTone) {
        return {
          color: this.configObj.activityColor ? this.configObj.activityColor.color[0].item : '#E93323',
          background: this.configObj.activityBgColor ? this.configObj.activityBgColor.color[0].item : '#FDEBE9',
        };
      }
      // Follow theme - assuming standard theme colors or hardcoded for now if theme var not available easily
      return {
        color: '#E93323',
        background: '#FDEBE9',
      };
    },
    activityColor() {
      return this.configObj && this.configObj.activityColor ? this.configObj.activityColor.color[0].item : '#E93323';
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
  mounted() {
    this.$nextTick(() => {
      this.pageData = this.$store.state.mobildConfig.defaultArray[this.num];
      this.setConfig(this.pageData);
    });
  },
  data() {
    return {
      defaultConfig: {
        cname: 'Hàng hóa và Dịch vụ',
        name: 'productService',
        timestamp: this.num,
        openService: 'Bắt đầu dịch vụ',
        isHide: false,
        setUp: {
          tabVal: 0,
        },
        checkBoxConfig: {
          title: 'hiển thị thông tin',
          type: [0, 1, 2, 3],
          list: [
            { id: 0, name: 'Hoạt động' },
            { id: 1, name: 'chọn' },
            { id: 2, name: 'tham số' },
            { id: 3, name: 'Phục vụ' },
          ],
        },
        serviceStyleTitle: 'phong cách phục vụ',
        generalStyleTitle: 'Phong cách phổ quát',
        titleColor: {
          title: 'văn bản tiêu đề',
          default: [{ item: '#999999' }],
          color: [{ item: '#999999' }],
        },
        contentColor: {
          title: 'văn bản nội dung',
          default: [{ item: '#333333' }],
          color: [{ item: '#333333' }],
        },
        toneConfig: {
          title: 'giai điệu',
          tabVal: 0,
          tabList: [{ name: 'Theo dõi chủ đề' }, { name: 'Tùy chỉnh' }],
        },
        activityColor: {
          title: 'Nội dung hoạt động',
          default: [{ item: '#E93323' }],
          color: [{ item: '#E93323' }],
        },
        activityBgColor: {
          title: 'Nền sự kiện',
          default: [{ item: '#FDEBE9' }],
          color: [{ item: '#FDEBE9' }],
        },
        zIndexConfig: {
          title: 'Thành phần nổi',
          val: 0,
          min: 0,
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
        bottomBgColor: {
          title: 'nền dưới cùng',
          default: [{ item: '#F5F5F5' }],
          color: [{ item: '#F5F5F5' }],
        },
        paddingConfig: {
          title: 'phần đệm',
          val: 10,
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
          val: 8,
          min: 0,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
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
      },
      configObj: null,
      pageData: {},
    };
  },
  methods: {
    setConfig(data) {
      if (!data) return;
      let dataClone = JSON.parse(JSON.stringify(data));

      for (let key in this.defaultConfig) {
        if (dataClone[key] == undefined) {
          this.$set(dataClone, key, JSON.parse(JSON.stringify(this.defaultConfig[key])));
        }
      }

      // Khả năng tương thích với dữ liệu cũ: nền thành phần
      if (!data.componentBgConfig && data.componentBgColor) {
        dataClone.componentBgConfig.colorConfig.color[0].item = data.componentBgColor.color[0].item;
        if (data.componentBgColor.color[1]) {
          dataClone.componentBgConfig.colorConfig.color[1].item = data.componentBgColor.color[1].item;
        }
      }
      this.configObj = dataClone;
    },
  },
};
</script>

<style scoped lang="scss">
.product-service {
  overflow: hidden;
  .item {
    display: flex;
    justify-content: space-between;
    align-items: center; // Align items vertically center
    padding: 12px 0;
    &:last-child {
      border-bottom: none;
    }
    .label {
      width: 40px;
      font-size: 14px;
      color: #999;
      margin-right: 10px;
      flex-shrink: 0; // Prevent label from shrinking
    }
    .content {
      flex: 1;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 14px;
      color: #333;
      overflow: hidden; // Prevent overflow

      // Make sure text truncates if too long
      > span:first-child {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex: 1;
      }

      .tags {
        display: flex;
        flex-wrap: wrap;
        .tag {
          font-size: 10px;
          padding: 2px 5px;
          border-radius: 10px;
          margin-right: 5px;
          margin-bottom: 0;
          display: flex;
          align-items: center;
          justify-content: center;
          .iconfont {
            font-size: 8px;
            margin-left: 2px;
            line-height: 12px;
          }
          .mb-iconfont {
            font-size: 12px;
            margin-right: 2px;
            line-height: 12px;
          }
        }
      }
      .iconfont {
        font-size: 12px;
        color: #333;
        margin-left: 5px;
      }
    }
  }
}
</style>
