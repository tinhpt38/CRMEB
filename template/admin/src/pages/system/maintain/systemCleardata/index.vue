<template>
  <div>
    <div class="i-layout-page-header header-title">
      <span class="ivu-page-header-title">{{ $t($route.meta.title) }}</span>
      <span class="clear_tit">
        <i class="el-icon-info" style="color: #ed4014" />
        <span>{{ $t('message.systemMaintain.clearDangerTip') }}</span>
      </span>
    </div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-row :gutter="24">
        <el-col v-bind="grid" class="mb20" v-for="(item, index) in tabList" :key="index">
          <div class="clear_box">
            <span class="clear_box_sp1" v-text="item.title"></span>
            <span class="clear_box_sp2" v-text="item.tlt"></span>
            <el-button
              :type="item.typeName"
              v-text="item.typeName === 'primary' ? $t('message.systemMaintain.replaceNow') : $t('message.systemMaintain.clearNow')"
              v-db-click
              @click="onChange(item)"
            ></el-button>
          </div>
        </el-col>
      </el-row>
    </el-card>
    <!-- 更换域名-->
    <el-dialog :visible.sync="modals" class="tableBox" :title="$t('message.systemMaintain.replaceDomain')" width="540px" :close-on-click-modal="false">
      <div class="acea-row row-column">
        <span>{{ $t('message.systemMaintain.replaceDomainInputTip') }}</span>
        <span>{{ $t('message.systemMaintain.replaceDomainRuleTip') }}</span>
        <span class="mb15">{{ $t('message.systemMaintain.replaceDomainSuccessTip') }}</span>
        <el-input v-model="value6" type="textarea" :rows="4" :placeholder="$t('message.systemMaintain.enterDomainPlaceholder')" />
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button v-db-click @click="modals = false">{{ $t('customDesign.cancel') }}</el-button>
        <el-button type="primary" v-db-click @click="changeYU">{{ $t('customDesign.confirm') }}</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { replaceSiteUrlApi } from '@/api/system';
export default {
  name: 'systemCleardata',
  data() {
    return {
      value6: '',
      modals: false,
      grid: {
        xl: 6,
        lg: 8,
        md: 12,
        sm: 24,
        xs: 24,
      },
      tabList: [
        {
          title: this.$t('systemCommon.replaceDomain'),
          tlt: this.$t('message.systemMaintain.replaceUploadedImageDomain'),
          typeName: 'primary',
          type: '11',
        },
        {
          title: this.$t('message.systemMaintain.clearUserTempAttachment'),
          tlt: this.$t('message.systemMaintain.clearUserTempAttachmentTip'),
          typeName: 'error',
          type: 'temp',
        },
        {
          title: this.$t('message.systemMaintain.clearRecycleProducts'),
          tlt: this.$t('message.systemMaintain.clearRecycleProductsTip'),
          typeName: 'error',
          type: 'recycle',
        },
        {
          title: this.$t('message.systemMaintain.clearUserData'),
          tlt: this.$t('message.systemMaintain.clearUserDataTip'),
          typeName: 'error',
          type: 'user',
        },
        {
          title: this.$t('message.systemMaintain.clearMallData'),
          tlt: this.$t('message.systemMaintain.clearMallDataTip'),
          typeName: 'error',
          type: 'store',
        },
        {
          title: this.$t('message.systemMaintain.clearProductCategory'),
          tlt: this.$t('message.systemMaintain.clearProductCategoryTip'),
          typeName: 'error',
          type: 'category',
        },
        {
          title: this.$t('message.systemMaintain.clearOrderData'),
          tlt: this.$t('message.systemMaintain.clearOrderDataTip'),
          typeName: 'error',
          type: 'order',
        },
        {
          title: this.$t('message.systemMaintain.clearCustomerServiceData'),
          tlt: this.$t('message.systemMaintain.clearCustomerServiceDataTip'),
          typeName: 'error',
          type: 'kefu',
        },
        {
          title: this.$t('message.systemMaintain.clearWechatData'),
          tlt: this.$t('message.systemMaintain.clearWechatDataTip'),
          typeName: 'error',
          type: 'wechat',
        },
        {
          title: this.$t('message.systemMaintain.clearContentCategory'),
          tlt: this.$t('message.systemMaintain.clearContentCategoryTip'),
          typeName: 'error',
          type: 'article',
        },
        {
          title: this.$t('message.systemMaintain.clearAllAttachments'),
          tlt: this.$t('message.systemMaintain.clearAllAttachmentsTip'),
          typeName: 'error',
          type: 'attachment',
        },
        {
          title: this.$t('message.systemMaintain.clearSystemRecord'),
          tlt: this.$t('message.systemMaintain.clearSystemRecordTip'),
          typeName: 'error',
          type: 'system',
        },
      ],
    };
  },
  methods: {
    // 清除数据
    onChange(item) {
      if (item.type === '11') {
        this.modals = true;
      } else {
        this.clearFroms(item);
      }
    },
    clearFroms(item) {
      let delfromData = {
        title: item.title,
        url: `system/clear/${item.type}`,
        method: 'get',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 更换域名
    changeYU() {
      replaceSiteUrlApi({ url: this.value6 })
        .then((res) => {
          this.modals = false;
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.clear_tit {
  align-items: center;
  margin: 15px;
  span {
    font-size: 14px;
    color: #ed4014;
  }
}
.clear_box {
  border: 1px solid #dadfe6;
  border-radius: 3px;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 30px 10px;
  box-sizing: border-box;
  .clear_box_sp1 {
    font-size: 16px;
    color: #000000;
    display: block;
  }
  .clear_box_sp2 {
    font-size: 14px;
    color: #808695;
    display: block;
    margin: 12px 0;
  }
}
.clear_box ::v-deep .ivu-btn-error {
  color: #fff;
  background-color: #ed4014;
  border-color: #ed4014;
}
.product_tabs ::v-deep .ivu-page-header-title {
  margin-bottom: 0 !important;
}
</style>
