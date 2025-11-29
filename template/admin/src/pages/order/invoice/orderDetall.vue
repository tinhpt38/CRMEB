<template>
  <div class="order_detail" v-loading="spinShow" v-if="orderDetail.userInfo">
    <div class="msg-box">
      <div class="box-title">{{ $t('message.orderList.receiveInfo2') }}</div>
      <div class="msg-wrapper">
        <div class="msg-item">
          <div class="item"><span>{{ $t('message.orderList.userNickname') }}</span>{{ orderDetail.userInfo.nickname }}</div>
          <div class="item"><span>{{ $t('message.orderList.receiver') }}</span>{{ orderDetail.orderInfo.real_name }}</div>
        </div>
        <div class="msg-item">
          <div class="item"><span>{{ $t('message.orderList.contactPhone2') }}</span>{{ orderDetail.orderInfo.user_phone }}</div>
          <div class="item"><span>{{ $t('message.orderList.receiveAddress') }}</span>{{ orderDetail.orderInfo.user_address }}</div>
        </div>
      </div>
    </div>
    <div class="msg-box" style="border: none">
      <div class="box-title">{{ $t('message.orderList.orderInfo') }}</div>
      <div class="msg-wrapper">
        <div class="msg-item">
          <div class="item"><span>{{ $t('message.orderList.orderId2') }}</span>{{ orderDetail.orderInfo.order_id }}</div>
          <div class="item" style="color: red">
            <span style="color: red">{{ $t('message.orderList.orderStatus2') }}</span>{{ orderDetail.orderInfo._status._title }}
          </div>
        </div>
        <div class="msg-item">
          <div class="item"><span>{{ $t('message.orderList.totalProducts2') }}</span>{{ orderDetail.orderInfo.total_num }}</div>
          <div class="item">
            <span>{{ $t('message.orderList.totalPrice2') }}</span
            >{{ parseFloat(orderDetail.orderInfo.total_price) + parseFloat(orderDetail.orderInfo.vip_true_price || 0) }}
          </div>
        </div>
        <div class="msg-item">
          <div class="item"><span>{{ $t('message.orderList.deliveryFee2') }}</span>{{ orderDetail.orderInfo.pay_postage }}</div>
          <div class="item"><span>{{ $t('message.orderList.couponAmount2') }}</span>{{ orderDetail.orderInfo.coupon_price }}</div>
        </div>
        <div class="msg-item">
          <div class="item"><span>{{ $t('message.orderList.memberProductDiscount') }}</span>{{ orderDetail.orderInfo.vip_true_price || 0.0 }}</div>
          <div class="item"><span>{{ $t('message.orderList.pointsDeduction2') }}</span>{{ orderDetail.orderInfo.deduction_price || 0.0 }}</div>
        </div>
        <div class="msg-item">
          <div class="item"><span>{{ $t('message.orderList.actualPay2') }}</span>{{ orderDetail.orderInfo.pay_price }}</div>
          <div class="item"><span>{{ $t('message.orderList.createTime2') }}</span>{{ orderDetail.orderInfo.add_time }}</div>
        </div>
        <div class="msg-item">
          <div class="item"><span>{{ $t('message.orderList.payMethod2') }}</span>{{ orderDetail.orderInfo._status._payType }}</div>
          <div class="item"><span>{{ $t('message.orderList.promoter2') }}</span>{{ orderDetail.userInfo.spread_name }}</div>
        </div>
        <div class="msg-item">
          <div class="item"><span>{{ $t('message.orderList.merchantRemark') }}</span>{{ orderDetail.orderInfo.mark }}</div>
        </div>
      </div>
    </div>
    <div class="goods-box">
      <el-table :data="orderList">
        <el-table-column :label="$t('message.orderList.productId')" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.productInfo.id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.orderList.productName2')" min-width="160">
          <template slot-scope="scope">
            <div class="product_info">
              <img :src="scope.row.productInfo.image" alt="" />
              <p>{{ scope.row.productInfo.store_name }}</p>
            </div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.orderList.productCategory')" min-width="160">
          <template slot-scope="scope">
            <div>{{ scope.row.class_name }}</div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.orderList.productPrice')" min-width="160">
          <template slot-scope="scope">
            <div>{{ scope.row.productInfo.price }}</div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.orderList.productQuantity')" min-width="160">
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
      font-size: 15px;
      font-weight: 500;
      color: #303133;
      line-height: 15px;
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
