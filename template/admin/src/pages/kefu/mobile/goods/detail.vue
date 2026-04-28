<template>
  <div class="scroll-box">
    <div class="title-box">Chi tiết sản phẩm</div>
    <swiper :options="swiperOption" class="swiper-box">
      <swiper-slide class="swiper-slide" v-for="(item, index) in goodsInfo.slider_image" :key="index">
        <img :src="item" />
      </swiper-slide>
      <!-- Trình phân trang -->
    </swiper>
    <div class="goods_info">
      <div class="number-wrapper">
        <div class="price"><span>¥</span>{{ goodsInfo.price }}</div>
        <div class="old-price">
          ¥{{ goodsInfo.vip_price }}<img src="@/assets/images/goods_vip.png" alt="" width="28" />
        </div>
      </div>
      <div class="name">{{ goodsInfo.store_name }}</div>
      <div class="msg">
        <div class="item">Giá gốc:￥{{ goodsInfo.ot_price }}</div>
        <div class="item">Doanh số bán hàng:{{ goodsInfo.sales }}</div>
        <div class="item">Trong kho:{{ goodsInfo.stock }}</div>
      </div>
    </div>
    <div class="con-box">
      <div class="title-box">Giới thiệu sản phẩm</div>
      <div class="content" v-html="goodsInfo.description"></div>
    </div>
  </div>
</template>

<script>
import { productInfo } from '@/api/kefu';
export default {
  name: 'goods_detail',
  props: {
    goodsId: {
      type: String | Number,
      default: '',
    },
  },
  data() {
    return {
      value2: 0,
      goodsInfo: {},
      goodsId: '',
      swiperOption: {
        //hiển thị phân trang
        pagination: {
          el: '.swiper-pagination',
        },
        //Đặt mũi tên nhấp chuột
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        //băng chuyền tự động
        autoplay: {
          delay: 2000,
          //Băng chuyền tự động tiếp tục khi người dùng trượt hình ảnh
          disableOnInteraction: false,
        },
        //Bật chế độ vòng lặp
        loop: true,
      },
    };
  },
  created() {
    this.goodsId = this.$route.query.goodsId;
  },
  mounted() {
    this.getInfo();
  },
  methods: {
    getInfo() {
      productInfo(this.goodsId).then((res) => {
        this.goodsInfo = res.data;
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.scroll-box {
  height: 100%;
  overflow-y: scroll;
  -webkit-overflow-scrolling: touch;
}
.title-box {
  height: 0.8rem;
  line-height: 0.8rem;
  background: #fff;
  text-align: center;
  color: #333;
  font-size: 16px;
}
.swiper-box {
  width: 100%;
  height: auto;
  img {
    width: 100%;
    height: 100%;
    display: block;
  }
}
.goods_info {
  padding: 15px;
  background: #fff;
  .number-wrapper {
    display: flex;
    align-items: center;
    .price {
      color: #ff3838;
      font-size: 0.3rem;
      span {
        font-size: 0.26rem;
      }
    }
    .old-price {
      font-size: 0.3rem;
      margin-left: 0.2rem;
      color: #333333;
    }
  }
  .name {
    font-size: 0.32rem;
    color: #333;
  }
  .msg {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 0.2rem;
    .item {
      color: #999999;
      font-size: 0.28rem;
    }
  }
}
.con-box {
  margin-top: 10px;
  padding-bottom: 20px;
  img {
    display: block;
  }
}
</style>
