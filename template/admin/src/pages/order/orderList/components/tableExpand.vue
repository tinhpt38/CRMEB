<template>
  <div class="order-expand">
    <div class="order-expand__highlights">
      <div class="order-expand__highlight">
        <span class="order-expand__highlight-label">Trạng thái</span>
        <span class="order-expand__highlight-value" v-html="statusText"></span>
      </div>
      <div class="order-expand__highlight">
        <span class="order-expand__highlight-label">Thanh toán</span>
        <span class="order-expand__highlight-value">{{ paymentSummary }}</span>
      </div>
      <div class="order-expand__highlight">
        <span class="order-expand__highlight-label">Thanh toán thực tế</span>
        <span class="order-expand__highlight-value is-money">{{ paidAmountText }}</span>
      </div>
      <div class="order-expand__highlight">
        <span class="order-expand__highlight-label">Hình thức nhận hàng</span>
        <span class="order-expand__highlight-value">{{ shippingTypeText }}</span>
      </div>
    </div>

    <div class="order-expand__section">
      <div class="order-expand__section-title">Quy trình duyệt đơn</div>
      <OrderWorkflowSteps :row="row" />
    </div>

    <div class="order-expand__section">
      <div class="order-expand__section-title">Thông tin chính</div>
      <el-row :gutter="16" class="order-expand__grid">
        <el-col :span="8">
          <div class="order-expand__field">
            <span class="order-expand__key">Người nhận</span>
            <span class="order-expand__value">{{ row.real_name || '--' }}</span>
          </div>
        </el-col>
        <el-col :span="8">
          <div class="order-expand__field">
            <span class="order-expand__key">Số điện thoại</span>
            <span class="order-expand__value">{{ row.user_phone || '--' }}</span>
          </div>
        </el-col>
        <el-col :span="8">
          <div class="order-expand__field">
            <span class="order-expand__key">Tổng giá sản phẩm</span>
            <span class="order-expand__value is-money">{{ formatVnd(row.total_price) }}</span>
          </div>
        </el-col>
        <el-col :span="8">
          <div class="order-expand__field">
            <span class="order-expand__key">Thời gian đặt hàng</span>
            <span class="order-expand__value">{{ formatDateTime(row.add_time) }}</span>
          </div>
        </el-col>
        <el-col :span="8">
          <div class="order-expand__field">
            <span class="order-expand__key">Thời gian thanh toán</span>
            <span class="order-expand__value">{{ formatDateTime(row._pay_time) }}</span>
          </div>
        </el-col>
        <el-col :span="8">
          <div class="order-expand__field">
            <span class="order-expand__key">Người giới thiệu</span>
            <span class="order-expand__value">{{ row.spread_nickname || 'Không có' }}</span>
          </div>
        </el-col>
        <el-col :span="24" v-if="addressLines.length">
          <div class="order-expand__field">
            <span class="order-expand__key">Địa chỉ giao hàng</span>
            <div class="order-expand__address">
              <div
                v-for="(line, index) in addressLines"
                :key="`${line.label}-${index}`"
                class="order-expand__address-line"
              >
                <span class="order-expand__address-label">{{ line.label }}:</span>
                <span class="order-expand__address-value">{{ line.value }}</span>
              </div>
            </div>
          </div>
        </el-col>
        <el-col :span="8" v-if="row.shipping_type == 2">
          <div class="order-expand__field">
            <span class="order-expand__key">Điểm nhận hàng</span>
            <span class="order-expand__value">{{ row.verify_code ? row.store_name : 'Không có' }}</span>
          </div>
        </el-col>
        <el-col :span="8" v-if="row.shipping_type == 2">
          <div class="order-expand__field">
            <span class="order-expand__key">Mã xác nhận</span>
            <span class="order-expand__value">{{ row.verify_code || 'Không có' }}</span>
          </div>
        </el-col>
        <el-col :span="8">
          <div class="order-expand__field">
            <span class="order-expand__key">Đơn vị kinh doanh</span>
            <span class="order-expand__value">{{ row.division_name || 'Không có' }}</span>
          </div>
        </el-col>
        <el-col :span="12" v-if="orderMarkSection.visible">
          <div class="order-expand__field">
            <span class="order-expand__key">{{ orderMarkSection.title }}</span>
            <span class="order-expand__value">{{ orderMarkSection.value }}</span>
          </div>
        </el-col>
        <el-col :span="12">
          <div class="order-expand__field">
            <span class="order-expand__key">Ghi chú người bán</span>
            <span class="order-expand__value">{{ row.remark || 'Không có' }}</span>
          </div>
        </el-col>
      </el-row>
    </div>
  </div>
</template>

<script>
import OrderWorkflowSteps from './OrderWorkflowSteps.vue';
import { formatDateTime, formatVnd } from '@/utils/format';
import { getOrderMarkSection } from '@/utils/orderMark';
import { buildShippingAddressLines } from '@/utils/shippingAddress';

export default {
  name: 'table-expand',
  components: {
    OrderWorkflowSteps,
  },
  props: {
    row: {
      type: Object,
      default: () => ({}),
    },
  },
  computed: {
    statusText() {
      return this.row.status_name && this.row.status_name.status_name ? this.row.status_name.status_name : '--';
    },
    paymentSummary() {
      if (Number(this.row.paid) === 1) {
        return this.row.pay_type_name || 'Đã thanh toán';
      }
      return this.row.pay_type_name ? `${this.row.pay_type_name} (chưa thanh toán)` : 'Chưa thanh toán';
    },
    paidAmountText() {
      return Number(this.row.paid) === 1 ? formatVnd(this.row.pay_price) : 'Chưa thanh toán';
    },
    shippingTypeText() {
      return Number(this.row.shipping_type) === 2 ? 'Nhận tại cửa hàng' : 'Giao tận nơi';
    },
    orderMarkSection() {
      return getOrderMarkSection(this.row.mark);
    },
    addressLines() {
      return buildShippingAddressLines(this.row.user_address).filter((line) => line.value && line.value !== '-');
    },
  },
  methods: {
    formatVnd,
    formatDateTime,
  },
};
</script>

<style scoped lang="scss">
.order-expand {
  padding: 8px 12px 4px;
}

.order-expand__highlights {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 12px;
  margin-bottom: 16px;
}

.order-expand__highlight {
  padding: 12px 14px;
  border-radius: 8px;
  background: linear-gradient(180deg, #f5f9ff 0%, #eef4ff 100%);
  border: 1px solid #d9e8ff;
}

.order-expand__highlight-label {
  display: block;
  margin-bottom: 6px;
  font-size: 12px;
  color: #909399;
}

.order-expand__highlight-value {
  display: block;
  font-size: 14px;
  line-height: 20px;
  color: #303133;
  font-weight: 600;
  word-break: break-word;
}

.order-expand__highlight-value.is-money {
  color: #e6a23c;
}

.order-expand__section + .order-expand__section {
  margin-top: 16px;
}

.order-expand__section-title {
  margin-bottom: 10px;
  padding-left: 10px;
  border-left: 3px solid #409eff;
  font-size: 14px;
  font-weight: 600;
  color: #303133;
}

.order-expand__grid {
  margin-top: 4px;
}

.order-expand__field {
  margin-bottom: 12px;
}

.order-expand__key {
  display: block;
  margin-bottom: 4px;
  font-size: 12px;
  color: #909399;
}

.order-expand__value {
  display: block;
  font-size: 13px;
  line-height: 20px;
  color: #303133;
  word-break: break-word;
}

.order-expand__value.is-money {
  color: #e6a23c;
  font-weight: 600;
}

.order-expand__address-line + .order-expand__address-line {
  margin-top: 4px;
}

.order-expand__address-label {
  color: #909399;
  margin-right: 6px;
}

.order-expand__address-value {
  color: #303133;
}

@media (max-width: 1400px) {
  .order-expand__highlights {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
</style>
