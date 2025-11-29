<template>
  <el-row :gutter="16" class="dashboard-console-grid">
    <el-col v-bind="grid" class="ivu-mb" v-auth="['admin-user-user-index']">
      <el-card shadow="never">
        <router-link :to="{ path: $routeProStr + '/user/list' }">
          <div class="icon">
            <i class="iconfont iconyonghuguanli" style="color: #0256ff"></i>
          </div>
          <p>{{ $t('message.common.userManagement') }}</p>
        </router-link>
      </el-card>
    </el-col>
    <el-col v-bind="grid" class="ivu-mb" v-auth="['setting-system-config']">
      <el-card shadow="never">
        <router-link :to="{ path: $routeProStr + '/setting/system_config' }">
          <div class="icon">
            <i class="iconfont iconxitongshezhi" style="color: #9fdb1d"></i>
          </div>
          <p>{{ $t('message.common.systemSettings') }}</p>
        </router-link>
      </el-card>
    </el-col>
    <el-col v-bind="grid" class="ivu-mb" v-auth="['admin-store-storeProuduct-index']">
      <el-card shadow="never">
        <router-link :to="{ path: $routeProStr + '/product/product_list' }">
          <div class="icon">
            <i class="iconfont iconshangpin" style="color: #0fc6c2"></i>
          </div>
          <p>{{ $t('message.common.product') }}</p>
        </router-link>
      </el-card>
    </el-col>
    <el-col v-bind="grid" class="ivu-mb" v-auth="['admin-order-storeOrder-index']">
      <el-card shadow="never">
        <router-link :to="{ path: $routeProStr + '/order/list' }">
          <div class="icon">
            <i class="iconfont icondingdanguanli" style="color: #b37feb"></i>
          </div>
          <p>{{ $t('message.common.orderManagement') }}</p>
        </router-link>
      </el-card>
    </el-col>
    <el-col v-bind="grid" class="ivu-mb" v-auth="['setting-sms']">
      <el-card shadow="never">
        <router-link :to="{ path: $routeProStr + '/setting/sms/sms_config/index' }">
          <div class="icon">
            <i class="iconfont iconduanxinpeizhi" style="color: #f7ba1e"></i>
          </div>
          <p>{{ $t('message.common.smsConfig') }}</p>
        </router-link>
      </el-card>
    </el-col>
    <el-col v-bind="grid" class="ivu-mb" v-auth="['cms-article-index']">
      <el-card shadow="never">
        <router-link :to="{ path: $routeProStr + '/cms/article/index' }">
          <div class="icon">
            <i class="iconfont iconwenzhangguanli" style="color: #7da2ff"></i>
          </div>
          <p>{{ $t('message.common.articleManagement') }}</p>
        </router-link>
      </el-card>
    </el-col>
    <el-col v-bind="grid" class="ivu-mb" v-auth="['agent-agent-manage']">
      <el-card shadow="never">
        <router-link :to="{ path: $routeProStr + '/agent/agent_manage/index' }">
          <div class="icon">
            <i class="iconfont iconfenxiaoguanli" style="color: #ff7d00"></i>
          </div>
          <p>{{ $t('message.common.distributionManagement') }}</p>
        </router-link>
      </el-card>
    </el-col>
    <el-col v-bind="grid" class="ivu-mb" v-auth="['marketing-store_coupon-index']">
      <el-card shadow="never">
        <router-link :to="{ path: $routeProStr + '/marketing/store_coupon_issue/index' }">
          <div class="icon">
            <i class="iconfont iconyouhuiquan4" style="color: #0fc6c2"></i>
          </div>
          <p>{{ $t('message.common.coupon') }}</p>
        </router-link>
      </el-card>
    </el-col>
  </el-row>
</template>
<script>
export default {
  data() {
    return {
      grid: {
        xl: 3,
        lg: 3,
        md: 6,
        sm: 6,
        xs: 12,
      },
    };
  },
};
</script>
<style lang="scss" scoped>
.dashboard-console-grid {
  text-align: center;
  .ivu-card-body {
    padding: 0;
  }
  .icon {
    width: 48px;
    height: 48px;
    background-color: #f6f6f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  i {
    font-size: 24px;
  }
  a {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    color: inherit;
    padding: 16px;
  }
  p {
    margin-top: 8px;
    font-size: 14px;
    font-weight: 400;
    color: #333333;
    line-height: 22px;
    white-space: nowrap;
  }
}
</style>
