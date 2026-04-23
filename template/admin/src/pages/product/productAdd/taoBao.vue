<template>
  <div class="Box" v-loading="spinShow">
    <div>
      <div class="tips">
        Theo mặc định, các sản phẩm được tạo ra không được đưa lên kệ. Hãy đặt sản phẩm lên kệ một cách thủ công.！
        <a href="https://doc.crmeb.com/single/v5/7785" v-if="copyConfig.copy_type == 2" target="_blank">Cách cấu hình khóa</a>
        <span v-else
          >Hiện tại bạn có{{ copyConfig.copy_num }}Số lượng bộ sưu tập，<span class="add" v-db-click @click="mealPay()"
            >Tăng thời gian thu thập</span
          ></span
        >
      </div>
      <div>Cài đặt bộ sưu tập sản phẩm: Cài đặt > Cài đặt hệ thống > Cài đặt giao diện của bên thứ ba > Thu thập cấu hình sản phẩm</div>
    </div>
    <el-form
      class="formValidate mt20"
      ref="formValidate"
      label-width="80px"
      label-position="right"
      @submit.native.prevent
    >
      <el-form-item label="Địa chỉ liên kết：">
        <el-input clearable v-model="soure_link" placeholder="Vui lòng nhập địa chỉ liên kết" class="numPut" />
        <el-button type="primary" class="ml15" v-db-click @click="add">Chắc chắn</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { crawlFromApi, copyConfigApi } from '@/api/product';

export default {
  name: 'taoBao',
  data() {
    return {
      soure_link: '',
      spinShow: false,
      grid: {
        xl: 8,
        lg: 8,
        md: 12,
        sm: 24,
        xs: 24,
      },
      grid2: {
        xl: 12,
        lg: 12,
        md: 12,
        sm: 24,
        xs: 24,
      },
      copyConfig: {
        copy_type: 2,
        copy_num: 0,
      },
      artFrom: {
        type: 'taobao',
        url: '',
      },
    };
  },
  computed: {},
  created() {},
  mounted() {
    this.getCopyConfig();
  },
  methods: {
    mealPay() {
      this.$router.push({ path: this.$routeProStr + '/setting/sms/sms_config/index' });
    },
    getCopyConfig() {
      copyConfigApi().then((res) => {
        this.copyConfig.copy_type = res.data.copy_type;
        this.copyConfig.copy_num = res.data.copy_num;
      });
    },
    // Tạo biểu mẫu
    add() {
      if (this.soure_link) {
        var reg = /(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?/;
        if (!reg.test(this.soure_link)) {
          return this.$message.warning('Vui lòng nhập địa chỉ bắt đầu bằng http！');
        }
        this.spinShow = true;
        this.artFrom.url = this.soure_link;
        crawlFromApi(this.artFrom)
          .then((res) => {
            let info = res.data.productInfo;
            this.$emit('on-close', info);
            this.spinShow = false;
          })
          .catch((res) => {
            this.spinShow = false;
            this.$message.error(res.msg);
          });
      } else {
        this.$message.warning('Vui lòng nhập địa chỉ liên kết！');
      }
    },
  },
};
</script>

<style lang="scss" scoped>
::v-deep .ivu-form-item-content {
  line-height: unset !important;
}
.Box .ivu-radio-wrapper {
  margin-right: 25px;
}
.add {
  color: #2d8cf0;
  cursor: pointer;
}
.Box .numPut {
  width: 414px !important;
}
</style>
