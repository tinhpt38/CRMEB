<template>
  <div>
    <el-drawer title="Chi tiết đơn hàng" :size="1000" :visible.sync="modals" wrapperClosable :before-close="handleClose">
      <div v-if="orderDatalist">
        
        <div class="head">
          <div class="full">
            <img class="order_icon" :src="orderImg" alt="" />
            <div class="text">
              <div class="title">Đơn hàng thông thường</div>
              <div>
                <span class="mr20">Số đơn hàng: {{ orderDatalist.orderInfo.order_id }}</span>
              </div>
            </div>
          </div>
          <ul class="list">
            <li class="item">
              <div class="title">Trạng thái đơn hàng</div>
              <div>
                {{ orderDatalist.orderInfo._status._title }}
                {{
                  orderDatalist.orderInfo.refund &&
                  orderDatalist.orderInfo.refund.length &&
                  orderDatalist.orderInfo.refund_status < 2
                    ? orderDatalist.orderInfo.is_all_refund
                      ? 'Đang hoàn tiền'
                      : 'Đang hoàn lại một phần'
                    : ''
                }}
              </div>
            </li>
            <li class="item">
              <div class="title">Thanh toán thực tế</div>
              <div>{{ formatVnd(orderDatalist.orderInfo.pay_price) }}</div>
            </li>
            <li class="item" v-if="orderDatalist.orderInfo.refund_status == 2">
              <div class="title">Hoàn lại tiền thực tế</div>
              <div>{{ formatVnd(orderDatalist.orderInfo.refunded_price) }}</div>
            </li>
            <li class="item">
              <div class="title">Phương thức thanh toán</div>
              <div>{{ orderPaymentLabel }}</div>
            </li>
            <li class="item">
              <div class="title">Thời gian thanh toán</div>
              <div>{{ formatDateTime(orderDatalist.orderInfo._pay_time) }}</div>
            </li>
          </ul>
        </div>
        <el-tabs type="border-card" v-model="activeName" @tab-click="tabClick">
          <el-tab-pane label="Thông tin đơn hàng" name="detail">
            <div class="action-toolbar action-toolbar--top" v-if="hasDetailActions">
              <div class="action-group" v-if="hasPaymentActions">
                <span class="action-group__label">Thanh toán</span>
                <div class="action-group__content">
                  <el-tag size="mini" type="warning" v-if="isCodUnpaid">COD chưa thu</el-tag>
                  <el-tag size="mini" type="warning" v-if="isBankUnpaid">CK chưa xác nhận</el-tag>
                  <el-tag size="mini" type="success" v-if="isOfflinePaid">Đã xác nhận TT</el-tag>
                  <el-button size="small" type="primary" plain v-if="isCodUnpaid" @click="$emit('detail-action', 'confirm_payment', orderDatalist.orderInfo)">Xác nhận đã thu COD</el-button>
                  <el-button size="small" type="primary" plain v-if="isBankUnpaid" @click="$emit('detail-action', 'confirm_payment', orderDatalist.orderInfo)">Xác nhận đã nhận CK</el-button>
                  <el-button size="small" type="primary" v-if="canConfirmPayment" @click="$emit('detail-action', 'confirm_payment', orderDatalist.orderInfo)">Xác nhận thanh toán</el-button>
                </div>
              </div>
              <div class="action-group" v-if="hasOrderManageActions">
                <span class="action-group__label">Quản lý</span>
                <div class="action-group__content">
                  <el-button size="small" v-if="canEditAddress" @click="$emit('detail-action', 'edit_address', orderDatalist.orderInfo)">Sửa địa chỉ</el-button>
                  <el-button size="small" v-if="canRemark" @click="$emit('detail-action', 'remark_order', orderDatalist.orderInfo)">Ghi chú</el-button>
                  <el-button size="small" type="warning" plain v-if="canRefund" @click="$emit('detail-action', 'refund_order', orderDatalist.orderInfo)">Hoàn tiền</el-button>
                  <el-button size="small" type="danger" plain v-if="canAdminCancelOrder" @click="openAdminCancel">Hủy đơn</el-button>
                </div>
              </div>
            </div>

            <div class="section">
              <div class="title">Thông tin người dùng</div>
              <ul class="list">
                <li class="item">
                  <div>Tên người dùng:</div>
                  <div class="value">{{ orderDatalist.userInfo.real_name }}</div>
                </li>
                <li class="item">
                  <div>Số điện thoại liên kết:</div>
                  <div class="value">{{ orderDatalist.orderInfo.user_phone || '' }}</div>
                </li>
              </ul>
            </div>
            <div class="section">
              <div class="title">Thông tin nhận hàng</div>
              <ul class="list">
                <!-- <li class="item">
                  <div>Tiếp nhận thông tin：</div>
                  <div class="value">{{ orderDatalist.orderInfo.user_address || '' }}</div>
                </li> -->
                <li class="item">
                  <div>Hình thức nhận hàng:</div>
                  <div class="value">{{ isPickupOrder ? 'Nhận tại cửa hàng' : 'Giao tận nơi' }}</div>
                </li>
                <li class="item">
                  <div>Người nhận hàng:</div>
                  <div class="value">
                    {{ orderDatalist.orderInfo.real_name ? orderDatalist.orderInfo.real_name : '-' }}
                  </div>
                </li>
                <li class="item">
                  <div>SĐT người nhận:</div>
                  <div class="value">
                    {{ orderDatalist.orderInfo.user_phone ? orderDatalist.orderInfo.user_phone : '-' }}
                  </div>
                </li>
                <template v-if="isPickupOrder">
                  <li class="item">
                    <div>Cửa hàng nhận:</div>
                    <div class="value">{{ pickupStoreName }}</div>
                  </li>
                  <li class="item" v-if="pickupVerifyCode">
                    <div>Mã nhận hàng:</div>
                    <div class="value">{{ pickupVerifyCode }}</div>
                  </li>
                </template>
                <li v-else class="item item-address">
                  <div class="address-lines">
                    <div class="address-line" v-for="(line, idx) in shippingAddressLines" :key="idx">
                      <span class="address-label">{{ line.label }}:</span>
                      <span class="address-value">{{ line.value }}</span>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
            <div class="section">
              <div class="title">Thông tin đơn hàng</div>
              <ul class="list">
                <li class="item">
                  <div>Thời gian tạo:</div>
                  <div class="value">{{ formatDateTime(orderDatalist.orderInfo._add_time) }}</div>
                </li>
                <li class="item">
                  <div>Tổng số mặt hàng:</div>
                  <div class="value">{{ orderDatalist.orderInfo.total_num }}</div>
                </li>
                <li class="item">
                  <div>Tổng giá sản phẩm:</div>
                  <div class="value">{{ orderDatalist.orderInfo.total_price }}</div>
                </li>
                <li class="item">
                  <div>Số tiền phiếu giảm giá:</div>
                  <div class="value">{{ orderDatalist.orderInfo.coupon_price }}</div>
                </li>
                <li class="item">
                  <div>Trừ điểm:</div>
                  <div class="value">{{ orderDatalist.orderInfo.deduction_price || '0.0' }}</div>
                </li>
                <li class="item">
                  <div>Phí vận chuyển:</div>
                  <div class="value">{{ orderDatalist.orderInfo.pay_postage }}</div>
                </li>
                <li class="item">
                  <div>Giảm giá cấp độ người dùng:</div>
                  <div class="value">{{ orderDatalist.orderInfo.levelPrice || '0.0' }}</div>
                </li>
                <li class="item">
                  <div>Ưu đãi thành viên trả phí:</div>
                  <div class="value">{{ orderDatalist.orderInfo.memberPrice || '0.0' }}</div>
                </li>
                <li class="item">
                  <div>Thanh toán thực tế:</div>
                  <div class="value">{{ orderDatalist.orderInfo.pay_price || '0.0' }}</div>
                </li>
              </ul>
            </div>
            <div class="section">
              <div class="title">Thông tin giảm giá</div>
              <ul class="list">
                <li class="item">
                  <div>Hoa hồng cấp 1:</div>
                  <div class="value">{{ orderDatalist.orderInfo.one_brokerage }} | {{ orderDatalist.orderInfo.spread_uid }}</div>
                </li>
                <li class="item">
                  <div>Hoa hồng cấp 2:</div>
                  <div class="value">{{ orderDatalist.orderInfo.two_brokerage }} | {{ orderDatalist.orderInfo.spread_two_uid }}</div>
                </li>
                <li class="item">
                  <div>Hoa hồng nhân viên:</div>
                  <div class="value">{{ orderDatalist.orderInfo.staff_brokerage }} | {{ orderDatalist.orderInfo.staff_id }}</div>
                </li>
                <li class="item">
                  <div>Hoa hồng đại lý:</div>
                  <div class="value">{{ orderDatalist.orderInfo.agent_brokerage }} | {{ orderDatalist.orderInfo.agent_id }}</div>
                </li>
                <li class="item">
                  <div>Hoa hồng đơn vị kinh doanh:</div>
                  <div class="value">{{ orderDatalist.orderInfo.division_brokerage }} | {{ orderDatalist.orderInfo.division_id }}</div>
                </li>
              </ul>
            </div>
            <div class="section" v-if="orderDatalist.orderInfo.delivery_name">
              <div class="title">
                {{ orderDatalist.orderInfo.delivery_type == 'express' ? 'Thông tin vận chuyển' : 'Thông tin người giao hàng' }}
              </div>
              <ul class="list">
                <li class="item">
                  <div>{{ orderDatalist.orderInfo.delivery_type == 'express' ? 'Đơn vị vận chuyển:' : 'Tên người giao hàng:' }}</div>
                  <div class="value">
                    {{ orderDatalist.orderInfo.delivery_name ? orderDatalist.orderInfo.delivery_name : '-' }}
                  </div>
                </li>
                <li class="item">
                  <div>{{ orderDatalist.orderInfo.delivery_type == 'express' ? 'Mã vận đơn:' : 'Số điện thoại người giao hàng:' }}</div>
                  <div class="value">
                    {{ orderDatalist.orderInfo.delivery_id }}
                    <a v-if="orderDatalist.orderInfo.delivery_type == 'express'" v-db-click @click="openLogistics"
                      >Theo dõi vận đơn</a
                    >
                  </div>
                </li>
              </ul>
            </div>
            <div class="section" v-if="orderDatalist.orderInfo.invoice">
              <div class="title">Thông tin hóa đơn</div>
              <ul class="list">
                <li class="item">
                  <div>Tiêu đề hóa đơn:</div>
                  <div class="value">
                    {{ orderDatalist.orderInfo.invoice.name }}
                  </div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 2 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>Mã số thuế doanh nghiệp:</div>
                  <div class="value">{{ orderDatalist.orderInfo.invoice.duty_number }}</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 2 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>Loại hóa đơn:</div>
                  <div class="value">Hóa đơn điện tử thông thường</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 2 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>Loại tiêu đề hóa đơn:</div>
                  <div class="value">Doanh nghiệp</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 1 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>Tên thật:</div>
                  <div class="value">{{ orderDatalist.orderInfo.invoice.name || '' }}</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 1 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>Số liên lạc:</div>
                  <div class="value">{{ orderDatalist.orderInfo.invoice.drawer_phone || '' }}</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 2 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>Số liên lạc:</div>
                  <div class="value">{{ orderDatalist.orderInfo.invoice.user_phone || '' }}</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 2 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>Email liên hệ:</div>
                  <div class="value">{{ orderDatalist.orderInfo.invoice.email || '' }}</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 2 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>Trạng thái lập hoá đơn:</div>
                  <div class="value">{{ orderDatalist.orderInfo.invoice.is_invoice ? 'Đã lập hoá đơn' : 'Không được lập hoá đơn' }}</div>
                </li>
              </ul>
            </div>
            <div class="section" v-if="orderMarkSection.visible">
              <div class="title">{{ orderMarkSection.title }}</div>
              <ul class="list">
                <li class="item">
                  <div>{{ orderMarkSection.value }}</div>
                </li>
              </ul>
            </div>
            <div class="section" v-if="orderDatalist.orderInfo.custom_form.length">
              <div class="title">Thông tin biểu mẫu</div>
              <ul class="list">
                <li
                  class="item"
                  :class="{ pic: item.label == 'img' }"
                  :span="item.label !== 'text' ? 12 : 24"
                  v-for="(item, index) in orderDatalist.orderInfo.custom_form"
                  :key="index"
                >
                  <template v-if="item.label !== 'img'">
                    <div>{{ item.title }}: {{ item.value }}</div>
                  </template>
                  <template v-else>
                    <div>{{ item.title }}:</div>
                    <div v-for="(img, i) in item.value" :key="i" class="img">
                      <img v-viewer :src="img" alt="" />
                    </div>
                  </template>
                </li>
              </ul>
            </div>
            <div class="section">
              <div class="title">Ghi chú đơn hàng</div>
              <ul class="list">
                <li class="item">
                  <div>{{ normalRemark || '-' }}</div>
                </li>
              </ul>
            </div>
            <div class="section" v-if="codReconcileLogs.length">
              <div class="title">Lịch sử đối soát COD</div>
              <ul class="list cod-list">
                <li class="item cod-item" v-for="(log, idx) in codReconcileLogs" :key="idx">
                  <div>{{ log }}</div>
                </li>
              </ul>
            </div>
          </el-tab-pane>
          <el-tab-pane label="Thông tin sản phẩm" name="goods">
            <div class="action-toolbar action-toolbar--top" v-if="hasGoodsActions">
              <div class="action-group">
                <span class="action-group__label">Sản phẩm</span>
                <div class="action-group__content">
                  <el-button size="small" type="primary" plain v-if="canEditCartQty" @click="openEditQty">Sửa số lượng</el-button>
                  <el-button size="small" v-if="canEditOrder" @click="$emit('detail-action', 'edit_order', orderDatalist.orderInfo)">Chỉnh sửa đơn hàng</el-button>
                </div>
              </div>
            </div>

            <el-table class="mt20" :data="orderDatalist.orderInfo.cartInfo">
              <el-table-column label="Thông tin sản phẩm" min-width="300">
                <template slot-scope="scope">
                  <div class="tab">
                    <div class="demo-image__preview">
                      <el-image
                        :src="
                          scope.row.productInfo.attrInfo
                            ? scope.row.productInfo.attrInfo.image
                            : scope.row.productInfo.image
                        "
                        :preview-src-list="[scope.row.productInfo.attrInfo.image]"
                      />
                    </div>
                    <div>
                      <div class="line">{{ scope.row.productInfo.store_name }}</div>
                      <div class="line1 gary">
                        Đặc điểm kỹ thuật: {{ scope.row.productInfo.attrInfo ? scope.row.productInfo.attrInfo.suk : 'Mặc định' }}
                      </div>
                    </div>
                  </div>
                </template>
              </el-table-column>
              <el-table-column label="Đơn giá" min-width="90">
                <template slot-scope="scope">
                  <div class="tab">
                    <div class="line1">
                      {{ formatVnd(scope.row.truePrice) }}
                    </div>
                  </div>
                </template>
              </el-table-column>
              <el-table-column label="Số lượng mua" min-width="90">
                <template slot-scope="scope">
                  <div class="tab">
                    <div class="line1">
                      {{ scope.row.cart_num }}
                    </div>
                  </div>
                </template>
              </el-table-column>
              <!-- <el-table-column label="Trong kho" min-width="70">
                <template slot-scope="scope">
                  <div class="tab">
                    <div class="line1">
                      {{ scope.row.productInfo.stock }}
                    </div>
                  </div>
                </template>
              </el-table-column> -->
            </el-table>

          </el-tab-pane>
          <el-tab-pane label="Vận chuyển & in ấn" name="shipping" v-if="hasShippingActions">
            <div class="action-toolbar action-toolbar--top" v-if="hasShippingActions">
              <div class="action-group" v-if="hasShippingDeliveryActions">
                <span class="action-group__label">Vận chuyển</span>
                <div class="action-group__content">
                  <el-button size="small" type="primary" v-if="canSendOrder" @click="$emit('detail-action', 'send_order', orderDatalist.orderInfo)">Gửi hàng / tách đơn giao</el-button>
                  <el-button size="small" v-if="canViewDelivery" @click="$emit('detail-action', 'delivery_info', orderDatalist.orderInfo)">Thông tin vận chuyển</el-button>
                  <el-button size="small" v-if="canTakeDelivery" @click="$emit('detail-action', 'take_delivery', orderDatalist.orderInfo)">Xác nhận đã nhận</el-button>
                  <el-button size="small" type="primary" plain v-if="canConfirmPickup" @click="$emit('detail-action', 'confirm_pickup', orderDatalist.orderInfo)">Xác nhận nhận tại quầy</el-button>
                </div>
              </div>
              <div class="action-group" v-if="hasPrintActions">
                <span class="action-group__label">In ấn</span>
                <div class="action-group__content">
                  <el-button size="small" v-if="canPrintOrder" @click="$emit('detail-action', 'print_order', orderDatalist.orderInfo)">In đơn hàng</el-button>
                  <el-button size="small" v-if="canPrintDelivery" @click="$emit('detail-action', 'print_delivery', orderDatalist.orderInfo)">In phiếu giao hàng</el-button>
                  <el-button size="small" v-if="canPrintExpress" @click="$emit('detail-action', 'print_express', orderDatalist.orderInfo)">In mã vận đơn</el-button>
                </div>
              </div>
            </div>

          </el-tab-pane>
          <el-tab-pane label="Lịch sử đơn hàng" name="orderList">
            <el-table class="mt20" :data="recordData" v-loading="loading" empty-text="Chưa có dữ liệu" highlight-current-row>
              <el-table-column label="Đặt hàngID" min-width="100">
                <template slot-scope="scope">
                  <span>{{ scope.row.oid }}</span>
                </template>
              </el-table-column>
              <el-table-column label="Lịch sử thao tác" min-width="100">
                <template slot-scope="scope">
                  <span>{{ scope.row.change_message }}</span>
                </template>
              </el-table-column>
              <el-table-column label="Thời gian hoạt động" min-width="100">
                <template slot-scope="scope">
                  <span>{{ scope.row.change_time }}</span>
                </template>
              </el-table-column>
            </el-table>
          </el-tab-pane>
        </el-tabs>
      </div>
    </el-drawer>

    <el-dialog title="Hủy đơn hàng" :visible.sync="cancelVisible" width="480px" append-to-body @closed="resetCancelForm">
      <el-form label-width="120px" size="small">
        <el-form-item label="Lý do hủy" required>
          <el-select v-model="cancelForm.reason_key" placeholder="Chọn lý do" style="width: 100%">
            <el-option v-for="r in cancelReasons" :key="r.key" :label="r.label" :value="r.key" />
          </el-select>
        </el-form-item>
        <el-form-item v-if="cancelForm.reason_key === 'other'" label="Nội dung hủy" required>
          <el-input v-model="cancelForm.custom_reason" type="textarea" :rows="3" maxlength="500" show-word-limit placeholder="Mô tả lý do hủy đơn" />
        </el-form-item>
      </el-form>
      <span slot="footer">
        <el-button @click="cancelVisible = false">Đóng</el-button>
        <el-button type="danger" :loading="cancelSubmitting" @click="submitAdminCancel">Xác nhận hủy đơn</el-button>
      </span>
    </el-dialog>

    <el-dialog title="Sửa số lượng sản phẩm" :visible.sync="qtyVisible" width="560px" append-to-body @closed="qtyRows = []">
      <p class="qty-tip">Chỉ áp dụng đơn chưa thanh toán, không áp dụng đơn flash sale / nhóm / mặc cả. Sau khi lưu, tổng tiền và tồn kho được cập nhật theo số lượng mới.</p>
      <el-table v-if="qtyRows.length" :data="qtyRows" size="small" border>
        <el-table-column label="Sản phẩm" min-width="200">
          <template slot-scope="scope">
            <div class="line1">{{ scope.row.storeName }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Số lượng" width="160">
          <template slot-scope="scope">
            <el-input-number v-model="scope.row.cart_num" :min="1" :max="999999" size="small" controls-position="right" />
          </template>
        </el-table-column>
      </el-table>
      <span slot="footer">
        <el-button @click="qtyVisible = false">Đóng</el-button>
        <el-button type="primary" :loading="qtySubmitting" @click="submitEditQty">Lưu</el-button>
      </span>
    </el-dialog>

    <el-drawer :visible.sync="modal2" scrollable title="Theo dõi vận đơn" width="350px" class="order_box2">
      <div class="logistics acea-row row-top" v-if="orderDatalist">
        <div class="logistics_img">
          <img src="../../../../assets/images/expressi.jpg" />
        </div>
        <div class="logistics_cent">
          <span>Đơn vị vận chuyển: {{ orderDatalist.orderInfo.delivery_name }}</span>
          <span>Mã vận đơn: {{ orderDatalist.orderInfo.delivery_id }}</span>
        </div>
      </div>
      <div class="acea-row row-column-around trees-coadd">
        <div class="scollhide">
          <el-timeline>
            <el-timeline-item v-for="(item, i) in result" :key="i">
              <p class="time" v-text="item.time"></p>
              <p class="content" v-text="item.status"></p>
            </el-timeline-item>
          </el-timeline>
        </div>
      </div>
    </el-drawer>
  </div>
</template>
<script>
import {
  getExpress,
  getOrderRecord,
  getOrderCancelReasons,
  adminCancelOrder,
  adminUpdateOrderCartNum,
} from '@/api/order';
import { buildShippingAddressLines } from '@/utils/shippingAddress';
import { getOrderMarkSection, parsePayMethodMark } from '@/utils/orderMark';
import { cityList } from '@/api/app';
import { formatDateTime, formatVnd } from '@/utils/format';
export default {
  name: 'orderDetails',
  data() {
    return {
      activeName: 'detail',
      modal2: false,
      modals: false,
      grid: {
        xl: 8,
        lg: 8,
        md: 12,
        sm: 24,
        xs: 24,
      },
      result: [],
      orderImg: require('@/assets/images/order_icon.png'),
      recordData: [],
      page: {
        page: 1, // Trang hiện tại
        limit: 15, // Số mục được hiển thị trên mỗi trang
      },
      loading: false,
      cancelVisible: false,
      cancelReasons: [],
      cancelForm: { reason_key: '', custom_reason: '' },
      cancelSubmitting: false,
      qtyVisible: false,
      qtyRows: [],
      qtySubmitting: false,
      cityTree: [],
      cityLoaded: false,
    };
  },
  props: {
    orderDatalist: {
      type: Object,
      default: () => {
        orderInfo: {
        }
      },
    },
    orderId: Number,
    is_refund: {
      type: Number,
      default: 0,
    },
  },
  watch: {
    modals(val) {
      if (val) {
        this.activeName = 'detail';
        this.ensureCityList();
      }
    },
  },
  created() {
    this.ensureCityList();
  },
  computed: {
    info() {
      return (this.orderDatalist && this.orderDatalist.orderInfo) || {};
    },
    paidNum() {
      const val = Number(this.info.paid);
      return Number.isNaN(val) ? 0 : val;
    },
    isDelNum() {
      const val = Number(this.info.is_del);
      return Number.isNaN(val) ? 0 : val;
    },
    isCancelNum() {
      const val = Number(this.info.is_cancel);
      return Number.isNaN(val) ? 0 : val;
    },
    statusNum() {
      const val = Number(this.info.status);
      return Number.isNaN(val) ? -1 : val;
    },
    shippingTypeNum() {
      const val = Number(this.info.shipping_type);
      return Number.isNaN(val) ? 0 : val;
    },
    isPickupOrder() {
      return this.shippingTypeNum === 2;
    },
    pickupStoreName() {
      const name = this.info._store_name || this.info.store_name;
      return name ? String(name).trim() : '-';
    },
    pickupVerifyCode() {
      const code = this.info.verify_code || this.info._verify_code;
      return code ? String(code).trim() : '';
    },
    refundStatusNum() {
      const val = Number(this.info.refund_status);
      return Number.isNaN(val) ? -1 : val;
    },
    statusTitle() {
      const title = this.info?._status?._title;
      return typeof title === 'string' ? title.trim() : '';
    },
    payTypeNormalized() {
      const raw = String(this.info.pay_type || '').toLowerCase();
      if (raw === 'cod' || raw === 'vn-cod') return 'vn_cod';
      if (raw === 'bank' || raw === 'vn-bank') return 'vn_bank';
      return raw;
    },
    orderStatusCode() {
      const status = this.info._status;
      if (typeof status === 'number') return status;
      if (status && typeof status === 'object') {
        const maybeCode = status._status ?? status._type ?? status.status ?? status.code ?? status.type;
        const parsed = Number(maybeCode);
        if (!Number.isNaN(parsed)) return parsed;
      }
      return this.statusNum;
    },
    /** Đơn chưa ghi nhận tiền — `_type` 0 (chờ TT online) hoặc 9 (COD / CK / offline chờ xác nhận) */
    isUnpaidStage() {
      if (this.paidNum !== 0 || this.isDelNum === 1 || this.isCancelNum === 1) return false;
      const code = Number(this.orderStatusCode);
      return code === 0 || code === 9;
    },
    canConfirmPayment() {
      return (
        this.isUnpaidStage &&
        ['offline', 'vn_cod', 'vn_bank'].includes(this.payTypeNormalized) &&
        this.isDelNum !== 1 &&
        this.isCancelNum !== 1
      );
    },
    isCodUnpaid() {
      return this.isUnpaidStage && this.payTypeNormalized === 'vn_cod';
    },
    isBankUnpaid() {
      return this.isUnpaidStage && this.payTypeNormalized === 'vn_bank';
    },
    isOfflinePaid() {
      return ['offline', 'vn_cod', 'vn_bank'].includes(this.payTypeNormalized) && this.paidNum === 1;
    },
    canEditOrder() {
      return this.paidNum === 0 && this.isDelNum !== 1 && this.isCancelNum !== 1;
    },
    canSendOrder() {
      return (
        (this.statusNum === 4 || this.orderStatusCode === 2 || this.orderStatusCode === 8) &&
        this.shippingTypeNum === 1 &&
        (this.info.pinkStatus === null || this.info.pinkStatus === 2) &&
        this.isDelNum !== 1 &&
        this.isCancelNum !== 1 &&
        !(this.info.refund || []).length
      );
    },
    canViewDelivery() {
      return this.orderStatusCode === 4 && !(this.info.split || []).length;
    },
    canTakeDelivery() {
      return this.orderStatusCode === 4;
    },
    canRefund() {
      return this.paidNum === 1 && this.refundStatusNum === 0 && !(this.info.refund || []).length;
    },
    canEditAddress() {
      return this.statusNum === 0;
    },
    canRemark() {
      return (
        this.orderStatusCode !== 1 ||
        (this.orderStatusCode === 3 &&
          this.info.use_integral > 0 &&
          this.info.use_integral >= this.info.back_integral)
      );
    },
    canPrintOrder() {
      return this.orderStatusCode >= 2;
    },
    canPrintDelivery() {
      return this.paidNum === 1;
    },
    canPrintExpress() {
      return !!this.info.kuaidi_label;
    },
    canConfirmPickup() {
      return (
        this.shippingTypeNum === 2 &&
        this.statusNum === 0 &&
        this.paidNum === 1 &&
        this.refundStatusNum === 0
      );
    },
    canAdminCancelOrder() {
      return this.canEditOrder && this.refundStatusNum === 0;
    },
    canEditCartQty() {
      if (!this.canEditOrder || this.refundStatusNum !== 0) return false;
      const o = this.info;
      if (!o) return false;
      if (Number(o.combination_id) > 0) return false;
      if (Number(o.seckill_id) > 0) return false;
      if (Number(o.bargain_id) > 0) return false;
      if (Number(o.pink_id) > 0) return false;
      return true;
    },
    codReconcileLogs() {
      const remark = String(this.info.remark || '');
      if (!remark) return [];
      return remark
        .split('\n')
        .map((line) => line.trim())
        .filter((line) => line.startsWith('[COD]'));
    },
    normalRemark() {
      const remark = String(this.info.remark || '');
      if (!remark) return '';
      const lines = remark
        .split('\n')
        .map((line) => line.trim())
        .filter((line) => line && !line.startsWith('[COD]'));
      return lines.join('\n');
    },
    hasDetailActions() {
      return this.hasPaymentActions || this.hasOrderManageActions;
    },
    hasPaymentActions() {
      return this.isCodUnpaid || this.isBankUnpaid || this.isOfflinePaid || this.canConfirmPayment;
    },
    hasOrderManageActions() {
      return this.canEditAddress || this.canRemark || this.canRefund || this.canAdminCancelOrder;
    },
    hasShippingDeliveryActions() {
      return this.canSendOrder || this.canViewDelivery || this.canTakeDelivery || this.canConfirmPickup;
    },
    hasPrintActions() {
      return this.canPrintOrder || this.canPrintDelivery || this.canPrintExpress;
    },
    hasGoodsActions() {
      return this.canEditCartQty || this.canEditOrder;
    },
    hasShippingActions() {
      return (
        this.canSendOrder ||
        this.canViewDelivery ||
        this.canTakeDelivery ||
        this.canConfirmPickup ||
        this.canPrintOrder ||
        this.canPrintDelivery ||
        this.canPrintExpress
      );
    },
    shippingAddressLines() {
      return buildShippingAddressLines(this.info.user_address, this.cityTree);
    },
    orderMarkSection() {
      return getOrderMarkSection(this.info.mark);
    },
    orderPaymentLabel() {
      const fromMark = parsePayMethodMark(this.info.mark);
      if (fromMark) return fromMark;
      if (this.info._status && this.info._status._payType) return this.info._status._payType;
      return this.payTypeLabel(this.info.pay_type);
    },
  },
  methods: {
    formatVnd,
    formatDateTime,
    ensureCityList() {
      if (this.cityLoaded) return Promise.resolve(this.cityTree);
      return cityList()
        .then((res) => {
          this.cityTree = res.data || [];
          this.cityLoaded = true;
          return this.cityTree;
        })
        .catch(() => {
          this.cityTree = [];
          return [];
        });
    },
    payTypeLabel(val) {
      let obj = {
        yue: 'Số dư',
        weixin: 'Thanh toán online',
        alipay: 'Thanh toán online',
        offline: 'Thanh toán ngoại tuyến',
        vn_cod: 'Thanh toán khi nhận hàng (COD)',
        cod: 'Thanh toán khi nhận hàng (COD)',
        vn_bank: 'Chuyển khoản ngân hàng / VietQR',
        bank: 'Chuyển khoản ngân hàng / VietQR',
      };
      return obj[String(val || '').toLowerCase()] ?? 'Phương thức khác';
    },
    openLogistics() {
      this.getOrderData();
    },
    // Nhận thông tin hậu cần đơn hàng
    getOrderData() {
      getExpress(this.orderId)
        .then(async (res) => {
          this.result = res.data.result;
          this.modal2 = true;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    tabClick(tab) {
      if (tab.name == 'orderList') {
        this.getRecordList();
      }
    },
    handleClose() {
      this.modals = false;
    },
    getRecordList() {
      let data = {
        id: this.is_refund ? this.orderDatalist.orderInfo.store_order_id : this.orderDatalist.orderInfo.id,
        datas: this.page,
      };
      this.loading = true;
      getOrderRecord(data)
        .then(async (res) => {
          this.recordData = res.data;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    async openAdminCancel() {
      this.cancelForm = { reason_key: '', custom_reason: '' };
      if (!this.cancelReasons.length) {
        try {
          const res = await getOrderCancelReasons();
          this.cancelReasons = res.data || [];
        } catch (e) {
          this.$message.error((e && e.msg) || 'Không tải được danh sách lý do');
          return;
        }
      }
      this.cancelVisible = true;
    },
    resetCancelForm() {
      this.cancelForm = { reason_key: '', custom_reason: '' };
      this.cancelSubmitting = false;
    },
    submitAdminCancel() {
      if (!this.cancelForm.reason_key) {
        this.$message.warning('Vui lòng chọn lý do hủy đơn');
        return;
      }
      if (this.cancelForm.reason_key === 'other' && !String(this.cancelForm.custom_reason || '').trim()) {
        this.$message.warning('Vui lòng nhập nội dung hủy đơn');
        return;
      }
      const id = this.orderDatalist && this.orderDatalist.orderInfo && this.orderDatalist.orderInfo.id;
      if (!id) return;
      this.cancelSubmitting = true;
      adminCancelOrder(id, {
        reason_key: this.cancelForm.reason_key,
        custom_reason: this.cancelForm.custom_reason,
      })
        .then((res) => {
          this.$message.success(res.msg || 'Đã hủy đơn');
          this.cancelVisible = false;
          this.$emit('reload-detail');
        })
        .catch((e) => {
          this.$message.error((e && e.msg) || 'Hủy đơn thất bại');
        })
        .finally(() => {
          this.cancelSubmitting = false;
        });
    },
    openEditQty() {
      const list = (this.orderDatalist && this.orderDatalist.orderInfo && this.orderDatalist.orderInfo.cartInfo) || [];
      this.qtyRows = list.map((row) => ({
        unique: row.unique,
        storeName: (row.productInfo && row.productInfo.store_name) || '—',
        cart_num: Number(row.cart_num) || 1,
        initialNum: Number(row.cart_num) || 1,
      }));
      this.qtyVisible = true;
    },
    submitEditQty() {
      const id = this.orderDatalist && this.orderDatalist.orderInfo && this.orderDatalist.orderInfo.id;
      if (!id || !this.qtyRows.length) return;
      for (const r of this.qtyRows) {
        if (!r.cart_num || r.cart_num < 1) {
          this.$message.warning('Số lượng mỗi dòng phải ≥ 1');
          return;
        }
      }
      const items = this.qtyRows.map((r) => ({ unique: r.unique, cart_num: r.cart_num }));
      this.qtySubmitting = true;
      adminUpdateOrderCartNum(id, { items })
        .then((res) => {
          this.$message.success(res.msg || 'Đã cập nhật số lượng');
          this.qtyVisible = false;
          this.$emit('reload-detail');
        })
        .catch((e) => {
          this.$message.error((e && e.msg) || 'Cập nhật thất bại');
        })
        .finally(() => {
          this.qtySubmitting = false;
        });
    },
  },
};
</script>
<style lang="scss" scoped>
::v-deep .el-tabs--border-card > .el-tabs__header .el-tabs__item.is-active {
  border-bottom: none;
}
::v-deep .el-tabs__item {
  height: 40px !important;
  line-height: 40px !important;
}
.head {
  padding: 0 30px 24px;

  .full {
    display: flex;
    align-items: center;
    .order_icon {
      width: 60px;
      height: 60px;
    }
    .iconfont {
      color: var(--prev-color-primary);
      &.sale-after {
        color: #90add5;
      }
    }
    .text {
      align-self: center;
      flex: 1;
      min-width: 0;
      padding-left: 12px;
      font-size: 13px;
      color: #606266;
      .title {
        margin-bottom: 10px;
        font-weight: 500;
        font-size: 16px;
        line-height: 16px;
        color: rgba(0, 0, 0, 0.85);
      }
      .order-num {
        padding-top: 10px;
        white-space: nowrap;
      }
    }
  }
  .list {
    display: flex;
    flex-wrap: wrap;
    margin-top: 20px;
    overflow: visible;
    list-style: none;
    padding: 0;
    .item {
      flex: none;
      width: 200px;
      font-size: 14px;
      line-height: 14px;
      color: rgba(0, 0, 0, 0.85);
      .title {
        margin-bottom: 12px;
        font-size: 13px;
        line-height: 13px;
        color: #666666;
      }
      .value1 {
        color: #f56022;
      }

      .value2 {
        color: #1bbe6b;
      }

      .value3 {
        color: var(--prev-color-primary);
      }

      .value4 {
        color: #6a7b9d;
      }

      .value5 {
        color: #f5222d;
      }
    }
  }
}

.detail-version-tip {
  margin: 0 30px 12px;
  padding: 8px 12px;
  border-radius: 4px;
  background: #ecf5ff;
  color: #409eff;
  font-size: 13px;
}

.section-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
}

.action-toolbar {
  display: flex;
  flex-wrap: wrap;
  align-items: flex-start;
  gap: 12px 20px;
  margin-bottom: 20px;
  padding: 12px 14px;
  border: 1px solid #ebeef5;
  border-radius: 6px;
  background: #fafafa;
}

.action-toolbar--top {
  margin-top: 0;
}

.action-group {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 10px;
  min-width: 0;
}

.action-group__label {
  flex: 0 0 auto;
  font-size: 13px;
  font-weight: 500;
  color: #606266;
}

.action-group__content {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 8px;
  min-width: 0;
}

.item-address {
  flex: 0 0 100% !important;
  display: block !important;
  padding-left: 0 !important;
  padding-right: 0 !important;
}

.address-lines {
  width: 100%;
}

.address-line {
  display: flex;
  align-items: baseline;
  gap: 8px;
  margin-top: 8px;
  font-size: 13px;
  line-height: 1.6;
}

.address-line:first-child {
  margin-top: 0;
}

.address-label {
  flex: none;
  color: #909399;
  white-space: nowrap;
}

.address-value {
  flex: 0 1 auto;
  color: #303133;
  word-break: break-word;
}

.qty-tip {
  margin: 0 0 12px;
  font-size: 13px;
  color: #909399;
  line-height: 1.5;
}

.section {
  padding: 25px 0;
  border-bottom: 1px dashed #eeeeee;
  .title {
    padding-left: 10px;
    border-left: 3px solid var(--prev-color-primary);
    font-size: 15px;
    line-height: 15px;
    color: #303133;
  }
  .list {
    display: flex;
    flex-wrap: wrap;
    list-style: none;
    padding: 0;
  }
  .item {
    flex: 0 0 calc(100% / 3);
    display: flex;
    align-items: baseline;
    gap: 8px;
    margin-top: 16px;
    font-size: 13px;
    color: #666666;
    > div:first-child {
      flex: none;
      color: #909399;
      white-space: nowrap;
    }
    &:nth-child(3n + 1) {
      padding-right: 20px;
    }

    &:nth-child(3n + 2) {
      padding-right: 10px;
      padding-left: 10px;
    }

    &:nth-child(3n + 3) {
      padding-left: 20px;
    }
  }
  .value {
    flex: 0 1 auto;
    color: #303133;
    word-break: break-word;
    image {
      display: inline-block;
      width: 40px;
      height: 40px;
      margin: 0 12px 12px 0;
      vertical-align: middle;
    }
  }
  .item.pic {
    display: flex;
    img {
      width: 80px;
      height: 80px;
    }
  }
}

.cod-list {
  display: block !important;
}

.cod-item {
  width: 100% !important;
  line-height: 1.6 !important;
  border-left: 3px solid #67c23a;
  padding-left: 10px;
  margin-bottom: 8px;
}
.tab {
  display: flex;
  align-items: center;
  .el-image {
    width: 36px;
    height: 36px;
    margin-right: 10px;
  }
}
::v-deep .el-drawer__body {
  // padding: 0;
  overflow: auto;
}
.gary {
  color: #aaa;
}
::v-deep .el-drawer__body {
  padding: 20px 0;
}
::v-deep .el-tabs--border-card > .el-tabs__content {
  padding: 0 35px;
}
::v-deep .el-tabs--border-card > .el-tabs__header,
::v-deep .el-tabs--border-card > .el-tabs__header .el-tabs__item:active {
  border: none;
  height: 40px;
}
::v-deep .el-tabs--border-card > .el-tabs__header .el-tabs__item.is-active {
  border: none;
  border-top: 2px solid var(--prev-color-primary);
  font-size: 13px;
  font-weight: 500;
  color: #303133;
  line-height: 16px;
}
::v-deep .el-tabs--border-card > .el-tabs__header .el-tabs__item {
  border: none;
}
::v-deep .el-tabs--border-card > .el-tabs__header .el-tabs__item {
  margin-top: 0;
  transition: none;
  height: 40px !important;
  line-height: 40px !important;
  min-width: 92px;
  width: auto !important;
  padding: 0 16px !important;
  font-size: 13px;
  font-weight: 400;
  color: #303133;
  line-height: 16px;
}
::v-deep .el-tabs--border-card {
  border: none;
  box-shadow: none;
}

.logistics {
  align-items: center;
  padding: 10px 20px;

  .logistics_img {
    width: 45px;
    height: 45px;
    margin-right: 12px;

    img {
      width: 100%;
      height: 100%;
    }
  }

  .logistics_cent {
    span {
      display: block;
      font-size: 12px;
    }
  }
}
.trees-coadd {
  width: 100%;
  height: 400px;
  border-radius: 4px;
  overflow: hidden;

  .scollhide {
    width: 100%;
    height: 100%;
    overflow: auto;
    margin-left: 18px;
    padding: 10px 0 10px 0;
    box-sizing: border-box;

    .content {
      font-size: 12px;
    }

    .time {
      font-size: 12px;
      color: #2d8cf0;
    }
  }
}
</style>
