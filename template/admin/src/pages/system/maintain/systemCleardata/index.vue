<template>
  <div>
    <div class="i-layout-page-header header-title">
      <span class="ivu-page-header-title">{{ $route.meta.title }}</span>
      <span class="clear_tit">
        <i class="el-icon-info" style="color: #ed4014" />
        <span>{{ $t('message.systemMenus.clearDataWarning') }}</span>
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
              :v-text="item.typeName === 'primary' ? $t('message.systemMenus.immediatelyReplace') : $t('message.systemMenus.immediatelyClean')"
              v-db-click
              @click="onChange(item)"
            >{{ item.typeName === 'primary' ? $t('message.systemMenus.immediatelyReplace') : $t('message.systemMenus.immediatelyClean') }}</el-button>
          </div>
        </el-col>
      </el-row>
    </el-card>
    <!-- 更换域名-->
    <el-dialog :visible.sync="modals" class="tableBox" :title="$t('message.systemMenus.replaceDomain')" width="540px" :close-on-click-modal="false">
      <div class="acea-row row-column">
        <span>{{ $t('message.systemMenus.pleaseInputDomainToReplace') }}</span>
        <span>{{ $t('message.systemMenus.replaceRule') }}</span>
        <span class="mb15">{{ $t('message.systemMenus.replaceSuccessThenChange') }}</span>
        <el-input v-model="value6" type="textarea" :rows="4" :placeholder="$t('message.systemMenus.pleaseInputWebsiteDomain')" />
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button v-db-click @click="modals = false">{{ $t('message.systemMenus.cancel') }}</el-button>
        <el-button type="primary" v-db-click @click="changeYU">{{ $t('message.systemMenus.confirm') }}</el-button>
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
      tabList: [],
    };
  },
  created() {
    // Initialize tabList with i18n
    this.tabList = [
      {
        title: this.$t('message.systemMenus.replaceDomain'),
        tlt: this.$t('message.systemMenus.replaceAllLocalImageDomain'),
        typeName: 'primary',
        type: '11',
      },
      {
        title: this.$t('message.systemMenus.clearUserTempAttachments'),
        tlt: this.$t('message.systemMenus.clearUserTempAttachmentsTip'),
        typeName: 'error',
        type: 'temp',
      },
      {
        title: this.$t('message.systemMenus.clearRecycleBinProducts'),
        tlt: this.$t('message.systemMenus.clearRecycleBinProductsTip'),
        typeName: 'error',
        type: 'recycle',
      },
      {
        title: this.$t('message.systemMenus.clearUserData'),
        tlt: this.$t('message.systemMenus.clearUserDataTip'),
        typeName: 'error',
        type: 'user',
      },
      {
        title: this.$t('message.systemMenus.clearStoreData'),
        tlt: this.$t('message.systemMenus.clearStoreDataTip'),
        typeName: 'error',
        type: 'store',
      },
      {
        title: this.$t('message.systemMenus.clearProductCategory'),
        tlt: this.$t('message.systemMenus.clearProductCategoryTip'),
        typeName: 'error',
        type: 'category',
      },
      {
        title: this.$t('message.systemMenus.clearOrderData'),
        tlt: this.$t('message.systemMenus.clearOrderDataTip'),
        typeName: 'error',
        type: 'order',
      },
      {
        title: this.$t('message.systemMenus.clearServiceData'),
        tlt: this.$t('message.systemMenus.clearServiceDataTip'),
        typeName: 'error',
        type: 'kefu',
      },
      {
        title: this.$t('message.systemMenus.clearWechatData'),
        tlt: this.$t('message.systemMenus.clearWechatDataTip'),
        typeName: 'error',
        type: 'wechat',
      },
      {
        title: this.$t('message.systemMenus.clearContentCategory'),
        tlt: this.$t('message.systemMenus.clearContentCategoryTip'),
        typeName: 'error',
        type: 'article',
      },
      {
        title: this.$t('message.systemMenus.clearAllAttachments'),
        tlt: this.$t('message.systemMenus.clearAllAttachmentsTip'),
        typeName: 'error',
        type: 'attachment',
      },
      {
        title: this.$t('message.systemMenus.clearSystemRecords'),
        tlt: this.$t('message.systemMenus.clearSystemRecordsTip'),
        typeName: 'error',
        type: 'system',
      },
    ];
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
