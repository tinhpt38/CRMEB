<template>
  <div class="order_detail" v-if="orderDetail.userInfo" v-loading="spinShow">
    <div class="msg-box">
      <div class="box-title">{{ $t('message.pages.finance.orderDetall.shippingInfo') }}</div>
      <div class="msg-wrapper">
        <div class="msg-item">
          <div class="item"><span>{{ $t('message.pages.finance.orderDetall.userNickname') }}</span>{{ orderDetail.userInfo.nickname }}</div>
          <div class="item"><span>{{ $t('message.pages.finance.orderDetall.receiver') }}</span>{{ orderDetail.orderInfo.real_name }}</div>
        </div>
        <div class="msg-item">
          <div class="item"><span>{{ $t('message.pages.finance.orderDetall.contactPhone') }}</span>{{ orderDetail.orderInfo.user_phone }}</div>
          <div class="item"><span>{{ $t('message.pages.finance.orderDetall.shippingAddress') }}</span>{{ orderDetail.orderInfo.user_address }}</div>
        </div>
      </div>
    </div>
    <div class="msg-box" style="border: none">
      <div class="box-title">{{ $t('message.pages.finance.orderDetall.orderInfo') }}</div>
      <div class="msg-wrapper">
        <div class="msg-item">
          <div class="item"><span>{{ $t('message.pages.finance.orderDetall.orderId') }}</span>{{ orderDetail.orderInfo.order_id }}</div>
          <div class="item" style="color: red">
            <span style="color: red">{{ $t('message.pages.finance.orderDetall.orderStatus') }}</span>{{ orderDetail.orderInfo._status._title }}
          </div>
        </div>
        <div class="msg-item">
          <div class="item"><span>{{ $t('message.pages.finance.orderDetall.totalNum') }}</span>{{ orderDetail.orderInfo.total_num }}</div>
          <div class="item">
            <span>{{ $t('message.pages.finance.orderDetall.totalPrice') }}</span
            >{{ parseFloat(orderDetail.orderInfo.total_price) + parseFloat(orderDetail.orderInfo.vip_true_price || 0) }}
          </div>
        </div>
        <div class="msg-item">
          <div class="item"><span>{{ $t('message.pages.finance.orderDetall.postage') }}</span>{{ orderDetail.orderInfo.pay_postage }}</div>
          <div class="item"><span>{{ $t('message.pages.finance.orderDetall.couponAmount') }}</span>{{ orderDetail.orderInfo.coupon_price }}</div>
        </div>
        <div class="msg-item">
          <div class="item"><span>{{ $t('message.pages.finance.orderDetall.memberDiscount') }}</span>{{ orderDetail.orderInfo.vip_true_price || 0.0 }}</div>
          <div class="item"><span>{{ $t('message.pages.finance.orderDetall.integralDeduct') }}</span>{{ orderDetail.orderInfo.deduction_price || 0.0 }}</div>
        </div>
        <div class="msg-item">
          <div class="item"><span>{{ $t('message.pages.finance.orderDetall.actualPay') }}</span>{{ orderDetail.orderInfo.pay_price }}</div>
          <div class="item"><span>{{ $t('message.pages.finance.orderDetall.createTime') }}</span>{{ orderDetail.orderInfo.add_time }}</div>
        </div>
        <div class="msg-item">
          <div class="item"><span>{{ $t('message.pages.finance.orderDetall.payType') }}</span>{{ orderDetail.orderInfo._status._payType }}</div>
          <div class="item"><span>{{ $t('message.pages.finance.orderDetall.promoter') }}</span>{{ orderDetail.userInfo.spread_name }}</div>
        </div>
        <div class="msg-item">
          <div class="item"><span>{{ $t('message.pages.finance.orderDetall.merchantRemark') }}</span>{{ orderDetail.orderInfo.mark }}</div>
        </div>
      </div>
    </div>
    <div class="goods-box">
      <el-table :data="orderList">
        <el-table-column :label="$t('message.pages.finance.orderDetall.productId')" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.productInfo.id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.finance.orderDetall.productName')" min-width="160">
          <template slot-scope="scope">
            <div class="product_info">
              <img :src="scope.row.productInfo.image" alt="" />
              <p>{{ scope.row.productInfo.store_name }}</p>
            </div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.finance.orderDetall.productCategory')" min-width="160">
          <template slot-scope="scope">
            <div>{{ scope.row.class_name }}</div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.finance.orderDetall.productPrice')" min-width="160">
          <template slot-scope="scope">
            <div>{{ scope.row.productInfo.price }}</div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.finance.orderDetall.productNum')" min-width="160">
          <template slot-scope="scope">
            <div>{{ scope.row.cart_num }}</div>
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
import { orderInvoiceInfo } from '@/api/order';
export default {
  name: 'order_detail',
  props: {
    orderId: {
      type: String | Number,
      default: '',
    },
  },
  data() {
    return {
      orderDetail: {},
      orderList: [],
      spinShow: false,
    };
  },
  mounted() {
    this.getOrderInfo();
  },
  methods: {
    getOrderInfo() {
      this.spinShow = true;
      orderInvoiceInfo(this.orderId)
        .then((res) => {
          this.spinShow = false;
          this.orderDetail = res.data;
          this.orderList = res.data.orderInfo.cartInfo;
        })
        .catch((err) => {
          this.spinShow = false;
          this.$message.error(err.msg);
          this.$emit('detall', false);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.order_detail {
  .msg-box {
    border-bottom: 1px solid #e8eaed;

    .box-title {
      padding-top: 20px;
      font-size: 16px;
      color: #333;
    }

    .msg-wrapper {
      margin-top: 15px;
      padding-bottom: 10px;

      .msg-item {
        display: flex;

        .item {
          flex: 1;
          margin-bottom: 15px;
          color: #606266;
          font-size: 13px;
          span {
            font-size: 13px;
            font-weight: 400;
            color: #909399;
          }
        }
      }
    }

    &:first-child .box-title {
      padding-top: 0;
    }
  }

  .product_info {
    display: flex;
    align-items: center;

    img {
      width: 36px;
      height: 36px;
      border-radius: 4px;
      margin-right: 10px;
    }
  }
}
</style>
