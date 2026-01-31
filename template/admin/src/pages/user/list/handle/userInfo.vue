<template>
  <div class="user-info">
    <div class="section">
      <div class="section-hd">{{ $t('message.pages.user.list.infoSectionBasic') }}</div>
      <div class="section-bd">
        <div class="item">
          <div>{{ $t('message.pages.user.list.labelUserId') }}</div>
          <div class="value">{{ psInfo.uid }}</div>
        </div>
        <div class="item">
          <div>{{ $t('message.pages.user.list.labelRealName') }}</div>
          <div class="value">{{ psInfo.real_name || '-' }}</div>
        </div>
        <div class="item">
          <div>{{ $t('message.pages.user.list.labelPhone') }}</div>
          <div class="value">{{ psInfo.phone || '-' }}</div>
        </div>
        <div class="item">
          <div>{{ $t('message.pages.user.list.labelBirthday') }}</div>
          <div class="value">{{ psInfo.birthday | timeFormat('birthday') }}</div>
        </div>
        <div class="item">
          <div>{{ $t('message.pages.user.list.labelCardId') }}</div>
          <div class="value">{{ psInfo.card_id || '-' }}</div>
        </div>
        <div class="item">
          <div>{{ $t('message.pages.user.list.labelAddress') }}</div>
          <div class="value">{{ `${psInfo.addres}` || '-' }}</div>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="section-hd">{{ $t('message.pages.user.list.infoSectionPwd') }}</div>
      <div class="section-bd">
        <div class="item">
          <div>{{ $t('message.pages.user.list.labelLoginPwd') }}</div>
          <div class="value">********</div>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="section-hd">{{ $t('message.pages.user.list.infoSectionProfile') }}</div>
      <div class="section-bd">
        <div class="item">
          <div>{{ $t('message.pages.user.list.labelPromotion') }}</div>
          <div class="value">{{ psInfo.spread_open ? $t('message.pages.user.list.valueOn') : $t('message.pages.user.list.valueOff') }}</div>
        </div>
        <div class="item">
          <div>{{ $t('message.pages.user.list.labelUserStatus') }}</div>
          <div class="value">{{ psInfo.status ? $t('message.pages.user.list.valueOn') : $t('message.pages.user.list.valueLock') }}</div>
        </div>
        <div class="item">
          <div>{{ $t('message.pages.user.list.labelUserLevel') }}</div>
          <div class="value">{{ psInfo.vip_name || '-' }}</div>
        </div>
        <div class="item">
          <div>{{ $t('message.pages.user.list.labelUserLabel') }}</div>
          <div class="value">{{ psInfo.label_list || '-' }}</div>
        </div>
        <div class="item">
          <div>{{ $t('message.pages.user.list.labelUserGroup') }}</div>
          <div class="value">{{ psInfo.group_name || '-' }}</div>
        </div>
        <div class="item">
          <div>{{ $t('message.pages.user.list.labelPromoter') }}</div>
          <div class="value">{{ psInfo.spread_uid_nickname || '-' }}</div>
        </div>
        <div class="item">
          <div>{{ $t('message.pages.user.list.labelRegTime') }}</div>
          <div class="value">{{ psInfo.add_time | timeFormat }}</div>
        </div>
        <div class="item">
          <div>{{ $t('message.pages.user.list.labelLoginTime') }}</div>
          <div class="value">{{ psInfo.last_time | timeFormat }}</div>
        </div>
        <div v-if="psInfo.is_money_level" class="item">
          <div>{{ $t('message.pages.user.list.labelPaidMember') }}</div>
          <div class="value">
            {{
              psInfo.is_ever_level == 1 ? $t('message.pages.user.list.valueForeverMember') : psInfo.overdue_time ? `${psInfo.overdue_time} ${$t('message.pages.user.list.valueDueDate')}` : $t('message.pages.user.list.valueExpired')
            }}
          </div>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="section-hd">{{ $t('message.pages.user.list.infoSectionRemark') }}</div>
      <div class="section-bd">
        <div class="item">
          <div>{{ $t('message.pages.user.list.labelRemark') }}</div>
          <div class="value">{{ psInfo.mark || '-' }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import dayjs from 'dayjs';

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
      if (value == 1) {
        return this.$t('message.pages.user.list.valueMale');
      } else if (value == 2) {
        return this.$t('message.pages.user.list.valueFemale');
      } else {
        return this.$t('message.pages.user.list.valueUnknown');
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
