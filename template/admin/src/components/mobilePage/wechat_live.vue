<template>
  <common_wrapper :config="configObj">
    <div class="live-broadcast" :class="styleConfig == 2 ? 'on' : ''">
      <div class="live-wrapper-c" v-if="styleConfig == 0">
        <div
          class="live-item-a"
          :style="{
            marginBottom: liveConfig + 'px',
          }"
          v-for="(item, index) in live"
          :key="index"
        >
          <div class="img-box">
            <div
              class="empty-box"
              :style="{
                borderRadius: imgRadius,
              }"
            >
              <img src="../../assets/images/shan.png" />
            </div>
            <div class="label bgblue" v-if="item.type == 1">
              <span class="txt">Xem trước</span>
              <span class="msg">08tháng 08 20:00</span>
            </div>
            <div class="label bggary" v-if="item.type == 0">Phát lại</div>
            <div class="label bgred" v-if="item.type == 2"><span class="iconfont iconzhibozhong"></span>Phát sóng trực tiếp</div>
          </div>
          <div class="info">
            <div class="title" v-if="checkboxInfo.indexOf(0) != -1">Đây là tiêu đề trực tiếp. Đây là tiêu đề trực tiếp. Đây là tiêu đề trực tiếp....</div>
            <div class="people" v-if="checkboxInfo.indexOf(1) != -1">
              <img src="@/assets/images/ren.png" alt="" />
              <span>Người dẫn chương trình: Wang Xiaoya</span>
            </div>
          </div>
        </div>
      </div>
      <div class="live-wrapper-b" v-else-if="styleConfig == 2">
        <div
          class="live-item-b"
          :style="{
            marginRight: liveConfig + 'px',
          }"
          v-for="(item, index) in live"
          :key="index"
        >
          <div class="img-box">
            <div
              class="empty-box"
              :style="{
                borderRadius: imgRadius,
              }"
            >
              <img src="../../assets/images/shan.png" />
            </div>
            <div
              class="label bgblue"
              :style="{
                borderRadius: imgRadius2,
              }"
              v-if="item.type == 1"
            >
              <span
                class="txt"
                :style="{
                  borderRadius: imgRadius2,
                }"
                >Xem trước</span
              >
              <span class="msg">08tháng 08 20:00</span>
            </div>
            <div
              class="label bggary"
              :style="{
                borderRadius: imgRadius2,
              }"
              v-if="item.type == 0"
            >
              Phát lại
            </div>
            <div
              class="label bgred"
              :style="{
                borderRadius: imgRadius2,
              }"
              v-if="item.type == 2"
            >
              <span class="iconfont iconzhibozhong"></span>Phát sóng trực tiếp
            </div>
          </div>
          <div class="info">
            <div class="title line1" v-if="checkboxInfo.indexOf(0) != -1">Tiêu đề trực tiếp tiêu đề trực tiếp tiêu đề trực tiếp tiêu đề trực tiếp</div>
            <div class="people" v-if="checkboxInfo.indexOf(1) != -1">
              <img src="@/assets/images/ren.png" alt="" />
              <span>Người dẫn chương trình: Wang Xiaoya</span>
            </div>
          </div>
        </div>
      </div>
      <div class="live-wrapper-a" v-else-if="styleConfig == 1">
        <div
          class="live-item-a"
          :style="{
            marginBottom: liveConfig + 'px',
          }"
          v-for="(item, index) in live"
          :key="index"
        >
          <div class="img-box">
            <div
              class="empty-box"
              :style="{
                borderRadius: imgRadius,
              }"
            >
              <img src="../../assets/images/shan.png" />
            </div>
            <div class="label bgblue" v-if="item.type == 1">
              <span class="txt">Xem trước</span>
              <span class="msg">08tháng 08 20:00</span>
            </div>
            <div class="label bggary" v-if="item.type == 0">Phát lại</div>
            <div class="label bgred" v-if="item.type == 2"><span class="iconfont iconzhibozhong"></span>Phát sóng trực tiếp</div>
          </div>
          <div class="info">
            <div class="left">
              <div class="title line2" v-if="checkboxInfo.indexOf(0) != -1">Tiêu đề trực tiếp tiêu đề trực tiếp tiêu đề trực tiếp tiêu đề trực tiếp</div>
            </div>
            <div class="goods-wrapper">
              <template v-if="item.goods.length > 0">
                <div class="goods-item" v-for="(goods, index) in item.goods" :key="index">
                  <img src="../../assets/images/shan.png" alt="" />
                  <span v-if="index < 2">đ{{ goods.price }}</span>
                  <span class="num" v-else>+5</span>
                </div>
              </template>
            </div>
          </div>
        </div>
      </div>
      <div class="live-wrapper-d" v-else>
        <div v-for="(item, index) in live" :key="index">
          <div class="live-item-d" v-if="index == 0">
            <div
              class="img-box"
              :style="{
                borderRadius: imgRadius,
              }"
            >
              <div class="empty-box">
                <img src="../../assets/images/shan.png" />
              </div>
              <div class="label bgred" v-if="item.type == 2"><span class="iconfont iconzhibozhong"></span>Phát sóng trực tiếp</div>
              <div
                class="info"
                v-if="checkboxInfo.indexOf(0) != -1 || checkboxInfo.indexOf(1) != -1"
                :style="{
                  borderRadius: imgRadius3,
                }"
              >
                <div class="title line1" v-if="checkboxInfo.indexOf(0) != -1">
                  Đây là tiêu đề trực tiếp. Đây là tiêu đề trực tiếp. Đây là tiêu đề trực tiếp....
                </div>
                <div class="people" v-if="checkboxInfo.indexOf(1) != -1">
                  <img src="@/assets/images/ren.png" alt="" />
                  <span>Người dẫn chương trình: Wang Xiaoya</span>
                </div>
              </div>
            </div>
          </div>
          <div
            class="live-item-d on"
            v-else
            :style="{
              marginTop: liveConfig + 'px',
            }"
          >
            <div class="name line1">
              <div class="label" v-if="item.type == 1">Xem trước</div>
              <div class="label bggary" v-if="item.type == 0">Phát lại</div>
              <div v-if="checkboxInfo.indexOf(0) != -1">Đây là tiêu đề phát sóng trực tiếp ở đây...</div>
            </div>
            <div class="people acea-row row-middle" v-if="checkboxInfo.indexOf(1) != -1">
              <img src="@/assets/images/ren.png" alt="" />
              <div class="acea-row row-middle">
                <span>Người dẫn chương trình: Wang Xiaoya</span>
                <div class="line"></div>
                <span>08ngày 28 tháng 3 18:00</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </common_wrapper>
</template>

<script>
import { mapState, mapMutations } from 'vuex';
export default {
  name: 'wechat_live',
  cname: 'Chương trình nhỏ phát sóng trực tiếp',
  configName: 'c_wechat_live',
  type: 1, // 0 Thành phần cơ bản 1 Thành phần tiếp thị 2 Thành phần công cụ
  defaultName: 'liveBroadcast', // tên trận đấu bên ngoài
  icon: '#iconzujian-xiaochengxuzhibo',
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
        cname: 'Chương trình nhỏ phát sóng trực tiếp',
        name: 'liveBroadcast',
        timestamp: this.num,
        isHide: false,
        setUp: {
          tabVal: 0,
        },
        titleLeft: 'Cài đặt hiển thị',
        titleContent: 'Hiển thị nội dung',
        titleRight: 'Phong cách sống',
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
            {
              name: 'phong cách ba',
            },
            {
              name: 'phong cách bốn',
            },
          ],
        },
        numberConfig: {
          title: 'Số lượng phát sóng trực tiếp',
          val: 3,
          min: 1,
        },
        checkboxInfo: {
          title: 'hiển thị thông tin',
          name: 'checkboxInfo',
          type: [0, 1],
          list: [
            {
              id: 0,
              name: 'Tiêu đề trực tiếp',
            },
            {
              id: 1,
              name: 'Tên người dùng',
            },
          ],
        },
        liveConfig: {
          title: 'Khoảng cách phát sóng trực tiếp',
          val: 10,
          min: 0,
        },
        filletImg: {
          title: 'Hình ảnh được bo tròn các góc',
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
          val: 8,
          min: 0,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
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
          name: 'bgColor',
          default: [{ item: '#f5f5f5' }],
          color: [{ item: '#f5f5f5' }],
        },
        paddingConfig: {
          title: 'phần đệm',
          val: 10,
          min: 0,
          max: 100,
          valList: [{ val: 10 }, { val: 10 }, { val: 10 }, { val: 10 }],
        },
        marginConfig: {
          title: 'lề',
          val: 0,
          min: 0,
          max: 100,
          valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
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
      live: [
        {
          title: 'Phát sóng trực tiếp',
          name: 'playBg',
          type: 2,
          color: '',
          icon: 'iconzhibozhong',
          goods: [
            {
              img: '',
              price: '199',
            },
          ],
        },
        {
          title: 'Xem trước',
          name: 'notBg',
          type: 1,
          color: '',
          icon: 'iconweikaishi',
          goods: [
            {
              img: '',
              price: '199',
            },
            {
              img: '',
              price: '199',
            },
            {
              img: '',
              price: '199',
            },
          ],
        },
        {
          title: 'Phát lại',
          name: 'endBg',
          type: 0,
          color: '',
          icon: 'iconyijieshu',
          goods: [
            {
              img: '',
              price: '199',
            },
            {
              img: '',
              price: '199',
            },
          ],
        },
      ],
      confObj: {},
      pageData: {},
      styleConfig: 0,
      checkboxInfo: [],
      liveConfig: 0,
      imgRadius: 0,
      bgColor: '',
      bottomBgColor: '',
      paddingConfig: {
        valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
      },
      marginConfig: {
        valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
      },
      mTop: 0,
      topConfig: 0,
      bottomConfig: 0,
      prConfig: 0,
      bgRadius: '',
      imgRadius2: 0,
      imgRadius3: 0,
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
      let dataClone = JSON.parse(JSON.stringify(data));
      for (let key in this.defaultConfig) {
        if (dataClone[key] == undefined) {
          this.$set(dataClone, key, JSON.parse(JSON.stringify(this.defaultConfig[key])));
        }
      }

      if (!data.componentBgConfig && data.moduleColor) {
        dataClone.componentBgConfig.colorConfig.color[0].item = data.moduleColor.color[0].item;
        dataClone.componentBgConfig.colorConfig.color[1].item = data.moduleColor.color[1].item;
      }

      if (!data.paddingConfig) {
        if (dataClone.topConfig) dataClone.paddingConfig.valList[0].val = dataClone.topConfig.val;
        if (dataClone.bottomConfig) dataClone.paddingConfig.valList[2].val = dataClone.bottomConfig.val;
        if (dataClone.prConfig) {
          dataClone.paddingConfig.valList[1].val = dataClone.prConfig.val;
          dataClone.paddingConfig.valList[3].val = dataClone.prConfig.val;
        }
      }
      if (!data.marginConfig) {
        if (dataClone.mbConfig) dataClone.marginConfig.valList[0].val = dataClone.mbConfig.val;
      }

      this.configObj = dataClone;

      this.styleConfig = dataClone.styleConfig.tabVal;
      this.checkboxInfo = dataClone.checkboxInfo.type;
      this.liveConfig = dataClone.liveConfig.val;
      let filletImg = dataClone.filletImg.type;
      let filletValImg = dataClone.filletImg.val;
      let valListImg = dataClone.filletImg.valList;
      this.imgRadius = filletImg
        ? valListImg[0].val + 'px ' + valListImg[1].val + 'px ' + valListImg[3].val + 'px ' + valListImg[2].val + 'px'
        : filletValImg + 'px';
      this.imgRadius2 = filletImg ? valListImg[0].val + 'px 0 10px 0' : filletValImg + 'px 0 10px 0';
      this.imgRadius3 = filletImg
        ? '0 0 ' + valListImg[3].val + 'px ' + valListImg[2].val + 'px'
        : '0 0 ' + filletValImg + 'px ' + filletValImg + 'px';
    },
  },
};
</script>

<style scoped lang="scss">
.live-broadcast {
  width: 100%;
  &.on {
    padding-right: 0;
  }
}

.live-wrapper-d {
  width: 100%;

  .live-item-d {
    .img-box {
      width: 100%;
      height: 335px;
      position: relative;
      background: #f3f9ff;

      .empty-box {
        width: 100%;
        height: 265px;
        background-color: unset;

        img {
          width: 88px;
          height: 68px;
        }
      }

      .label {
        top: 12px;
        left: 0;
        border-radius: 0 50px 50px 0;
        height: 20px;
      }

      .info {
        position: absolute;
        width: 100%;
        background-color: rgba(0, 0, 0, 0.2);
        left: 0;
        bottom: 0;
        padding: 12px;

        .title {
          color: #ffffff;
          font-size: 14px;
        }

        .people {
          display: flex;
          align-items: center;
          margin-top: 6px;

          img {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 5px;
          }

          span {
            font-size: 12px;
            color: #ffffff;
          }
        }
      }
    }

    &.on {
      width: 100%;
      border-radius: 8px;
      border: 1px solid #eee;
      padding: 10px;
      margin-top: 10px;

      .name {
        color: #333333;
        font-size: 14px;
        display: flex;
        align-items: center;

        .label {
          position: unset;
          width: 32px;
          height: 16px;
          text-align: center;
          line-height: 16px;
          background: linear-gradient(90deg, #208ff2 0%, #3fa6ff 98%);
          border-radius: 3px;
          margin-right: 8px;

          &.bggary {
            background: linear-gradient(90deg, #6d80ac 0%, #889ebd 100%);
          }
        }
      }

      .people {
        font-size: 12px;
        color: #999999;
        display: flex;
        align-items: center;
        margin-top: 6px;

        img {
          width: 20px;
          height: 20px;
          border-radius: 50%;
          margin-right: 6px;
        }

        .line {
          width: 1px;
          height: 11px;
          background: #eee;
          margin: 0 6px;
        }
      }
    }
  }
}

.live-wrapper-c {
  width: 100%;

  .live-item-a {
    position: relative;

    &:nth-last-child(1) {
      margin-bottom: 0 !important;
    }

    .empty-box {
      width: 100%;
      height: 200px;
      background: #f3f9ff;

      img {
        width: 88px;
        height: 68px;
      }
    }

    .img-box {
      .label {
        height: 20px;
        line-height: 20px;
        border-radius: 0 50px 50px 0;
        left: 0;
        top: 12px;

        &.bgred {
          width: 65px;
          background: linear-gradient(90deg, #ff2851 0%, #ff2851 100%);
        }

        &.bgblue {
          .txt {
            border-radius: 0 50px 50px 0;
            line-height: 20px;
          }

          .msg {
            height: 100%;
          }
        }

        &.bggary {
          width: 38px;
        }
      }
    }

    .info {
      .title {
        color: #333333;
        font-size: 14px;
        margin-top: 12px;
      }

      .people {
        display: flex;
        align-items: center;
        font-size: 12px;
        color: #999999;
        margin-top: 6px;

        img {
          width: 20px;
          height: 20px;
          background: #f3f9ff;
          border-radius: 50%;
          margin-right: 6px;
        }
      }
    }
  }
}

.live-wrapper-a {
  .live-item-a {
    display: flex;
    align-items: center;
    position: relative;
    margin-bottom: 10px;
    overflow: hidden;

    &:nth-last-child(1) {
      margin-bottom: 0 !important;
    }

    .img-box {
      position: relative;
      width: 166px;
      height: 118px;
      overflow: hidden;

      .empty-box {
        background-color: #f3f9ff;

        img {
          width: 65px;
          height: 50px;
        }
      }

      .bggary.label {
        width: 34px;
        line-height: 18px;
      }
    }

    .info {
      flex: 1;
      overflow: hidden;
      margin-left: 10px;

      .title {
        color: #333333;
        font-size: 14px;
      }
    }

    .goods-wrapper {
      margin-top: 8px;
      display: flex;

      .goods-item {
        position: relative;
        width: 48px;
        height: 48px;
        border-radius: 4px;
        margin-right: 8px;
        background: #f3f9ff;
        display: flex;
        align-items: center;
        justify-content: center;

        &:nth-child(3n) {
          margin-right: 0;
        }

        img {
          width: 36px;
          height: 28px;
        }

        span {
          position: absolute;
          left: 0;
          bottom: 0;
          color: #fff;
          font-size: 12px;
          width: 100%;
          height: 13px;
          background: rgba(0, 0, 0, 0.3);
          border-radius: 0 0 4px 4px;
          font-size: 11px;
          text-align: center;
          line-height: 14px;

          &.num {
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.35);
            font-size: 14px;
            color: #fefefe;
            border-radius: 4px;
            line-height: 48px;
          }
        }
      }
    }
  }
}

.live-wrapper-b {
  display: flex;
  overflow: hidden;

  .live-item-b {
    width: 140px;
    margin-right: 10px;

    &:nth-last-child(1) {
      margin-bottom: 0;
    }

    .img-box {
      position: relative;
      height: 100px;
      width: 140px;

      .empty-box {
        background: #f3f9ff;

        img {
          width: 65px;
          height: 50px;
        }
      }
    }

    .label {
      top: 0;
      left: 0;

      &.bgred {
        width: 61px;
      }

      &.bgblue {
        width: 128px;

        .txt {
          border-radius: 0;
        }
      }

      &.bggary {
        width: 34px;
      }
    }

    .info {
      width: 100%;

      .title {
        font-size: 14px;
        color: #333;
        margin-top: 6px;
      }

      .people {
        display: flex;
        align-items: center;
        margin-top: 6px;
        font-size: 12px;
        color: #999;

        img {
          width: 20px;
          height: 20px;
          margin-right: 5px;
          border-radius: 50%;
        }
      }
    }
  }
}

.bggary {
  background: linear-gradient(90deg, #6d80ac 0%, #889ebd 100%);
}

.bgred {
  background: linear-gradient(90deg, #ff2851 0%, #ff2851 100%);
}

.label {
  display: flex;
  align-items: center;
  justify-content: center;
  position: absolute;
  left: 6px;
  top: 6px;
  width: 61px;
  height: 18px;
  line-height: 18px;
  border-radius: 50px;
  color: #fff;
  font-size: 11px;

  .iconfont {
    font-size: 12px;
    margin-right: 5px;
  }

  &.bgblue {
    justify-content: inherit;
    width: 128px;
    background: rgba(0, 0, 0, 0.2);
    overflow: hidden;

    .txt {
      width: 34px;
      height: 100%;
      text-align: center;
      margin-right: 7px;
      background: linear-gradient(90deg, #208ff2 0%, #3fa6ff 98%);
      border-radius: 50px;
      line-height: 18px;
    }
  }

  &.bggary {
    width: 54px;
  }
}
</style>
