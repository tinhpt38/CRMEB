<template>
  <div class="order_detail" v-if="orderDetail.userInfo">
    <div class="msg-box">
      <div class="box-title">Tiếp nhận thông tin</div>
      <div class="msg-wrapper">
        <div class="msg-item">
          <div class="item"><span>Biệt hiệu của người dùng：</span>{{ orderDetail.userInfo.nickname }}</div>
          <div class="item"><span>người nhận hàng：</span>{{ orderDetail.orderInfo.real_name }}</div>
        </div>
        <div class="msg-item">
          <div class="item"><span>Số liên lạc：</span>{{ orderDetail.orderInfo.user_phone }}</div>
          <div class="item"><span>Địa chỉ giao hàng：</span>{{ orderDetail.orderInfo.user_address }}</div>
        </div>
      </div>
    </div>
    <div class="msg-box" style="border: none">
      <div class="box-title">Thông tin đặt hàng</div>
      <div class="msg-wrapper">
        <div class="msg-item">
          <div class="item"><span>Đặt hàngID：</span>{{ orderDetail.orderInfo.order_id }}</div>
          <div class="item" style="color: red">
            <span style="color: red">Trạng thái đơn hàng：</span>{{ orderDetail.orderInfo._status._title }}
          </div>
        </div>
        <div class="msg-item">
          <div class="item"><span>Tổng số mặt hàng：</span>{{ orderDetail.orderInfo.total_num }}</div>
          <div class="item">
            <span>Tổng giá sản phẩm：</span
            >{{ parseFloat(orderDetail.orderInfo.total_price) + parseFloat(orderDetail.orderInfo.vip_true_price || 0) }}
          </div>
        </div>
        <div class="msg-item">
          <div class="item"><span>Bưu phí giao hàng：</span>{{ orderDetail.orderInfo.pay_postage }}</div>
          <div class="item"><span>Số tiền phiếu giảm giá：</span>{{ orderDetail.orderInfo.coupon_price }}</div>
        </div>
        <div class="msg-item">
          <div class="item"><span>thanh toán thực tế：</span>{{ orderDetail.orderInfo.pay_price }}</div>
          <div class="item"><span>thời gian sáng tạo：</span>{{ orderDetail.orderInfo.add_time }}</div>
        </div>
        <div class="msg-item">
          <div class="item"><span>Phương thức thanh toán：</span>{{ orderDetail.orderInfo._status._payType }}</div>
          <div class="item"><span>người quảng bá：</span>{{ orderDetail.userInfo.spread_name }}</div>
        </div>
        <div class="msg-item">
          <div class="item"><span>Nhận xét của người bán：</span>{{ orderDetail.orderInfo.remark }}</div>
        </div>
      </div>
    </div>
    <div class="goods-box">
      <el-table :data="orderList">
        <el-table-column label="hàng hóaID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.productInfo.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Tên sản phẩm" min-width="130">
          <template slot-scope="scope">
            <div class="product_info">
              <img :src="scope.row.productInfo.image" alt="" />
              <p>{{ scope.row.productInfo.store_name }}</p>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Phân loại sản phẩm" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.class_name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Giá bán sản phẩm" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.productInfo.attrInfo.price }}</span>
          </template>
        </el-table-column>
        <el-table-column label="số lượng sản phẩm" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.cart_num }}</span>
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
import { orderInfo } from '@/api/kefu';
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
    };
  },
  mounted() {
    this.getOrderInfo();
  },
  methods: {
    getOrderInfo() {
      orderInfo(this.orderId).then((res) => {
        res.data.orderInfo.add_time = this.$moment(parseInt(res.data.orderInfo.add_time) * 1000).format('YYYY-MM-DD');
        this.orderDetail = res.data;
        this.orderList = res.data.orderInfo.cartInfo;
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
