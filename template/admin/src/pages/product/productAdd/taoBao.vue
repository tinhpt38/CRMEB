<template>
  <div class="Box" v-loading="spinShow">
    <div>
      <div class="tips">
        {{ $t('message.productList.generatedProductNotOnSale') }}
        <a href="https://doc.crmeb.com/single/v5/7785" v-if="copyConfig.copy_type == 2" target="_blank">{{ $t('message.productList.howToConfigureKey') }}</a>
        <span v-else
          >{{ $t('message.productList.currentRemaining') }}{{ copyConfig.copy_num }}{{ $t('message.productList.collectionTimes') }}<span class="add" v-db-click @click="mealPay()"
            >{{ $t('message.productList.increaseCollectionTimes') }}</span
          ></span
        >
      </div>
      <div>{{ $t('message.productList.productCollectionSettings') }}</div>
    </div>
    <el-form
      class="formValidate mt20"
      ref="formValidate"
      label-width="80px"
      label-position="right"
      @submit.native.prevent
    >
      <el-form-item :label="$t('message.productList.linkAddress')">
        <el-input clearable v-model="soure_link" :placeholder="$t('message.productList.pleaseInputLinkAddress')" class="numPut" />
        <el-button type="primary" class="ml15" v-db-click @click="add">{{ $t('message.productList.confirm2') }}</el-button>
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
    // 生成表单
    add() {
      if (this.soure_link) {
        var reg = /(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?/;
        if (!reg.test(this.soure_link)) {
          return this.$message.warning(this.$t('message.productList.pleaseInputHttpAddress'));
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
        this.$message.warning(this.$t('message.productList.pleaseInputLinkAddress2'));
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
