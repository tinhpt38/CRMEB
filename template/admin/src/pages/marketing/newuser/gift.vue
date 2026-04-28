<template>
  <div v-loading="spinShow">
    <div class="article-manager">
      <el-card :bordered="false" shadow="never" class="ivu-mt fromBox">
        <el-form ref="formRef" :model="formData" label-width="100px">
          <el-form-item label="Số dư quà tặng(Nhân dân tệ)：">
            <el-input-number
              class="form-width"
              v-model="formData.reward_money"
              placeholder="Vui lòng nhập số dư quà tặng"
              :min="0"
            ></el-input-number>
            <div class="tips-info">Số tiền thưởng của người dùng mới phải lớn hơn hoặc bằng 0, 0 nghĩa là không có quà tặng</div>
          </el-form-item>
          <el-form-item label="Tặng điểm：">
            <el-input-number
              class="form-width"
              v-model="formData.reward_integral"
              placeholder="Vui lòng nhập số điểm thưởng"
              :min="0"
            ></el-input-number>
            <div class="tips-info">Điểm thưởng của người dùng mới phải lớn hơn hoặc bằng 0, 0 nghĩa là không có quà.</div>
          </el-form-item>
          <el-form-item label="Tặng phiếu giảm giá：">
            <div v-if="formData.reward_coupon.length" class="mb10">
              <el-tag
                class="mr10"
                closable
                v-for="(item, index) in formData.reward_coupon"
                :key="index"
                @close="handleClose(index)"
                >{{ item.title }}</el-tag
              >
            </div>
            <el-button v-db-click @click="addCoupon">Chọn phiếu giảm giá</el-button>
          </el-form-item>
          <el-form-item label="">
            <el-button type="primary" v-db-click @click="submitForm">Xác nhận</el-button>
          </el-form-item>
        </el-form>
      </el-card>
    </div>
    <coupon-list ref="couponTemplates" :updateIds="updateIds" @nameId="nameId"></coupon-list>
  </div>
</template>

<script>
import couponList from '@/components/couponList';
import { editNewbie, getNewbie } from '@/api/marketing';
export default {
  name: 'NewUserGift',
  components: { couponList },
  data() {
    return {
      spinShow: false,
      formData: {
        reward_money: 0,
        reward_integral: 0,
        reward_coupon: [],
        updateIds: [],
      },
    };
  },
  created() {
    this.getInfo();
  },
  methods: {
    //Sao chép mảng đối tượng；
    uniqueArray(arr) {
      const seen = {};
      return arr.filter((item) => {
        item.title =
          item.use_min_price !== '0.00'
            ? `${item.title} | Đầy${item.use_min_price}trừ nhân dân tệ ${item.coupon_price}Nhân dân tệ`
            : `${item.title} | ${item.coupon_price}Mã giảm giá nhân dân tệ không có ngưỡng`;
        delete item.use_min_price;
        delete item.coupon_price;
        const key = JSON.stringify(item); // Tạo khóa duy nhất bằng JSON.stringify
        if (seen[key]) {
          return false;
        } else {
          seen[key] = true;
          return true;
        }
      });
    },
    // Nhận dữ liệu id phiếu giảm giá
    nameId(id, names) {
      this.formData.reward_coupon = this.uniqueArray(names);
    },
    // thêm phiếu giảm giá
    addCoupon() {
      this.$refs.couponTemplates.isTemplate = true;
      this.$refs.couponTemplates.tableList();
    },
    handleClose(index) {
      this.formData.reward_coupon.splice(index, 1);
    },
    getInfo() {
      this.spinShow = true;
      getNewbie()
        .then((res) => {
          this.spinShow = false;
          this.formData = res.data;
          this.updateIds = res.data.reward_coupon.map((item) => item.id);
        })
        .catch((err) => {
          this.spinShow = false;
          this.$message.error('Không thể lấy được');
        });
    },
    // Gửi biểu mẫu
    submitForm() {
      this.spinShow = true;
      editNewbie(this.formData)
        .then((res) => {
          this.spinShow = false;
          this.$message.success('Gửi thành công');
        })
        .catch((err) => {
          this.spinShow = false;
          this.$message.error('Gửi không thành công');
        });
    },
  },
};
</script>
<style lang="scss" scoped></style>
