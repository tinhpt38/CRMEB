<template>
  <common_wrapper :config="configObj">
    <div class="service-box" :class="positions ? '' : 'on'">
      <div class="img-box">
        <img :src="imgUrl" alt="" v-if="imgUrl" />
        <div class="empty-box on" v-else>
          <img src="../../assets/images/shan.png" />
        </div>
      </div>
    </div>
  </common_wrapper>
</template>

<script>
import { mapState } from 'vuex';

export default {
  name: 'home_service',
  cname: 'nút nổi',
  configName: 'c_home_service',
  icon: '#iconzujian-xuanfuanniu',
  type: 2, // 0 Thành phần cơ bản 1 Thành phần tiếp thị 2 Thành phần công cụ
  defaultName: 'customerService', // tên trận đấu bên ngoài
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
  data() {
    return {
      defaultConfig: {
        cname: 'nút nổi',
        name: 'customerService',
        timestamp: this.num,
        isHide: false,
        setUp: {
          tabVal: 0,
        },
        titleLeft: 'Cài đặt nút',
        titleRight: 'cài đặt vị trí',
        buttonConfig: {
          title: 'nút nhảy',
          tabVal: 0,
          tabList: [
            {
              name: 'Liên kết trang',
            },
            {
              name: 'Lối vào dịch vụ khách hàng',
            },
          ],
        },
        locationConfig: {
          title: 'vị trí',
          tabVal: 1,
          tabList: [
            {
              name: 'Bên trái',
            },
            {
              name: 'Phải',
            },
          ],
        },
        logoConfig: {
          title: 'Gợi ý: hiển thị tải lên100*100px；',
          url: '',
          link: '',
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
          val: 0, // 0: Hide, 1: Show
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
          tabList: [{ name: 'trốn' }, { name: 'trình diễn' }],
          val: 0, // 0: Hide, 1: Show
          colorConfig: {
            title: 'màu nền',
            default: [{ item: '#fff' }],
            color: [{ item: '#fff' }],
          },
        },
        // khoảng cách trang
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
      },
      imgUrl: '',
      pageData: {},
      mTop: 0,
      positions: 1, //vị trí
      configObj: null,
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
        if (this.configObj[key] === undefined) {
          this.$set(this.configObj, key, this.defaultConfig[key]);
        }
      }
      this.imgUrl = data.logoConfig.url;
      this.positions = data.locationConfig.tabVal;
    },
  },
};
</script>

<style scoped lang="scss">
.service-box {
  width: 100%;
  display: flex;
  justify-content: flex-end;
  padding-right: 10px;
  &.on {
    justify-content: flex-start;
    padding-left: 10px;
  }
  .img-box {
    width: 43px;
    height: 43px;
    img {
      width: 100%;
      height: 100%;
      border-radius: 50%;
    }
    .empty-box {
      border-radius: 50%;
      background: #f3f9ff;
      img {
        width: 26px;
        height: 20px;
      }
      .iconfont {
        font-size: 20px;
      }
    }
  }
}
</style>
