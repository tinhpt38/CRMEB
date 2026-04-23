<template>
  <common_wrapper :config="configObj">
    <div
      class="pictrue acea-row row-center-wrapper"
      :class="scaleConfig == 1 ? 'on' : scaleConfig == 2 ? 'on2' : ''"
      :style="'background-image:url(' + (imgUrl ? imgUrl : imgBgUrl) + ');border-radius:' + bgRadius"
    >
      <div class="image acea-row row-center-wrapper">
        <img src="../../assets/images/ic_right2.png" />
      </div>
    </div>
  </common_wrapper>
</template>

<script>
import { mapState, mapMutations } from 'vuex';
export default {
  name: 'home_video',
  cname: 'băng hình',
  configName: 'c_video',
  icon: '#iconzujian-shipin',
  type: 0, // 0 Thành phần cơ bản 1 Thành phần tiếp thị 2 Thành phần công cụ
  defaultName: 'videos', // tên trận đấu bên ngoài
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
        cname: 'băng hình',
        name: 'videos',
        timestamp: this.num,
        isHide: false,
        setUp: {
          tabVal: 0,
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
        titleLeft: 'Cài đặt nội dung',
        titleRight: 'Phong cách phổ quát',
        imgConfig: {
          url: '',
          type: 'code',
          delType: 1,
          name: 'Bìa video',
        },
        videoConfig: {
          url: '',
          type: 'code',
          video: 1,
          delType: 0,
          name: 'Tải video lên',
        },
        scaleConfig: {
          title: 'tỷ lệ video',
          tabVal: 0,
          tabList: [
            {
              name: '16:9',
            },
            {
              name: '4:3',
            },
            {
              name: '1:1',
            },
          ],
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
        bottomBgColor: {
          title: 'nền dưới cùng',
          name: 'bgColor',
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
          val: 0,
          min: 0,
        },
        mbConfig: {
          title: 'khoảng cách trên trang',
          val: 0,
          min: 0,
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
      },
      imgBgUrl: require('@/assets/images/videoBg.png'),
      confObj: {},
      pageData: {},
      topConfig: '',
      bottomConfig: '',
      prConfig: 0,
      bgRadius: 0,
      imgUrl: '',
      scaleConfig: 0,
      mTop: 0,
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
        if (data[key] == undefined) {
          this.$set(data, key, this.defaultConfig[key]);
        }
      }
      this.scaleConfig = data.scaleConfig.tabVal;
      this.imgUrl = data.imgConfig.url;
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
.pictrue {
  width: 100%;
  height: 213px;
  background-repeat: no-repeat;
  background-size: cover;
  background-position: 50%;
  &.on {
    height: 284px;
  }
  &.on2 {
    height: 375px;
  }
  .image {
    width: 44px;
    height: 44px;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 50%;
    img {
      width: 24px;
      height: 24px;
      display: block;
    }
  }
  .empty-box {
    width: 100%;
    height: 375px;
    border-radius: 0;
    background: #f3f9ff;

    img {
      width: 65px;
      height: 50px;
    }
  }
  img {
    width: 100%;
    height: 100%;
  }
}
</style>
