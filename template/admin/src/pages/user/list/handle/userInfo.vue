<template>
  <div class="user-info">
    <div class="section">
      <div class="section-hd">{{ $t('userList.basicInfo') }}</div>
      <div class="section-bd">
        <div class="item">
          <div>{{ $t('userList.userId') }}：</div>
          <div class="value">{{ psInfo.uid }}</div>
        </div>
        <div class="item">
          <div>{{ $t('userList.realName') }}：</div>
          <div class="value">{{ psInfo.real_name || '-' }}</div>
        </div>
        <div class="item">
          <div>{{ $t('userList.mobile') }}：</div>
          <div class="value">{{ psInfo.phone || '-' }}</div>
        </div>
        <div class="item">
          <div>{{ $t('userList.birthday') }}：</div>
          <div class="value">{{ psInfo.birthday | timeFormat('birthday') }}</div>
        </div>
        <!-- <div class="item">
          <div>性别：</div>
          <div v-if="psInfo.sex" class="value">{{ psInfo.sex == 1 ? '男' : '女' }}</div>
          <div v-else class="value">保密</div>
        </div> -->
        <div class="item">
          <div>{{ $t('userList.idCardNo') }}：</div>
          <div class="value">{{ psInfo.card_id || '-' }}</div>
        </div>
        <div class="item">
          <div>{{ $t('userList.userAddress') }}：</div>
          <div class="value">{{ `${psInfo.addres}` || '-' }}</div>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="section-hd">{{ $t('userList.password') }}</div>
      <div class="section-bd">
        <div class="item">
          <div>{{ $t('userList.loginPassword') }}：</div>
          <div class="value">********</div>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="section-hd">{{ $t('userList.userOverview') }}</div>
      <div class="section-bd">
        <div class="item">
          <div>{{ $t('userList.promotionEligibility') }}：</div>
          <div class="value">{{ psInfo.spread_open ? $t('userList.enabled') : $t('userList.disabled') }}</div>
        </div>
        <div class="item">
          <div>{{ $t('userList.userStatus') }}：</div>
          <div class="value">{{ psInfo.status ? $t('userList.enabled') : $t('userList.locked') }}</div>
        </div>
        <div class="item">
          <div>{{ $t('userList.userLevel') }}：</div>
          <div class="value">{{ psInfo.vip_name || '-' }}</div>
        </div>
        <div class="item">
          <div>{{ $t('userList.userTags') }}：</div>
          <div class="value">{{ psInfo.label_list || '-' }}</div>
        </div>
        <div class="item">
          <div>{{ $t('userList.userGroup') }}：</div>
          <div class="value">{{ psInfo.group_name || '-' }}</div>
        </div>
        <div class="item">
          <div>{{ $t('userList.promoter') }}：</div>
          <div class="value">{{ psInfo.spread_uid_nickname || '-' }}</div>
        </div>
        <div class="item">
          <div>{{ $t('userList.registerTime') }}：</div>
          <div class="value">{{ psInfo.add_time | timeFormat }}</div>
        </div>
        <div class="item">
          <div>{{ $t('userList.loginTime') }}：</div>
          <div class="value">{{ psInfo.last_time | timeFormat }}</div>
        </div>
        <div v-if="psInfo.is_money_level" class="item">
          <div>{{ $t('userList.paidMember') }}：</div>
          <div class="value">
            {{
              psInfo.is_ever_level == 1
                ? $t('userList.permanentMember')
                : psInfo.overdue_time
                ? `${psInfo.overdue_time} ${$t('userList.expireAt')}`
                : $t('userList.expired')
            }}
          </div>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="section-hd">{{ $t('userList.userRemark') }}</div>
      <div class="section-bd">
        <div class="item">
          <div>{{ $t('userList.remark') }}：</div>
          <div class="value">{{ psInfo.mark || '-' }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import dayjs from 'dayjs';
import { i18n } from '@/i18n/index.js';

export default {
  name: 'userInfo',
  props: {
    psInfo: Object,
  },
  filters: {
    timeFormat(value, birthday) {
      let i = birthday ? 'YYYY-MM-DD' : 'YYYY-MM-DD HH:mm:ss';
      if (!value) {
        return '-';
      }
      return dayjs(value * 1000).format(i);
    },
    gender(value) {
      const t = (key) => i18n.t(key);
      if (value == 1) {
        return t('userList.male');
      } else if (value == 2) {
        return t('userList.female');
      } else {
        return t('userList.unknown');
      }
    },
  },
  computed: {
    hasExtendInfo() {
      //   return this.psInfo.extend_info.some((item) => item.value);
    },
  },
};
</script>

<style lang="scss" scoped>
.width-add {
  width: 40px;
}
.mr30 {
  margin-right: 30px;
}

.user-info {
  .section {
    padding: 25px 0;
    border-bottom: 1px dashed #eeeeee;

    &-hd {
      padding-left: 10px;
      border-left: 3px solid var(--prev-color-primary);
      font-weight: 500;
      font-size: 14px;
      line-height: 16px;
      color: #303133;
    }

    &-bd {
      display: flex;
      flex-wrap: wrap;
    }

    .item {
      flex: 0 0 calc((100% - 60px) / 3);
      display: flex;
      margin: 16px 30px 0 0;
      font-size: 13px;
      color: #666;

      &:nth-child(3n + 3) {
        margin: 16px 0 0;
      }
    }

    .value {
      flex: 1;
    }
    .avatar {
      width: 60px;
      height: 60px;
      overflow: hidden;
      img {
        width: 100%;
        height: 100%;
      }
    }
  }
}
</style>
