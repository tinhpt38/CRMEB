<template>
  <div class="user-info">
    <div class="section">
      <div class="section-hd">Thông tin cơ bản</div>
      <div class="section-bd">
        <div class="item">
          <div>ID khách hàng：</div>
          <div class="value">{{ psInfo.uid }}</div>
        </div>
        <div class="item">
          <div>Tên thật：</div>
          <div class="value">{{ psInfo.real_name || '-' }}</div>
        </div>
        <div class="item">
          <div>Số điện thoại：</div>
          <div class="value">{{ psInfo.phone || '-' }}</div>
        </div>
        <div class="item">
          <div>Sinh nhật：</div>
          <div class="value">{{ psInfo.birthday | timeFormat('birthday') }}</div>
        </div>
        <!-- <div class="item">
          <div>Giới tính：</div>
          <div v-if="psInfo.sex" class="value">{{ psInfo.sex == 1 ? 'nam giới' : 'nữ giới' }}</div>
          <div v-else class="value">Bảo mật</div>
        </div> -->
        <div class="item">
          <div>Số CMND：</div>
          <div class="value">{{ psInfo.card_id || '-' }}</div>
        </div>
        <div class="item">
          <div>Địa chỉ người dùng：</div>
          <div class="value">{{ `${psInfo.addres}` || '-' }}</div>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="section-hd">Mật khẩu</div>
      <div class="section-bd">
        <div class="item">
          <div>Mật khẩu đăng nhập：</div>
          <div class="value">********</div>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="section-hd">Hồ sơ người dùng</div>
      <div class="section-bd">
        <div class="item">
          <div>Cộng tác viên：</div>
          <div class="value">{{ psInfo.spread_open ? 'Bật' : 'Tắt' }}</div>
        </div>
        <div class="item">
          <div>Trạng thái người dùng：</div>
          <div class="value">{{ psInfo.status ? 'Hoạt động' : 'Khóa' }}</div>
        </div>
        <div class="item">
          <div>Hạng khách hàng：</div>
          <div class="value">{{ psInfo.vip_name || '-' }}</div>
        </div>
        <div class="item">
          <div>Thẻ khách hàng：</div>
          <div class="value">{{ psInfo.label_list || '-' }}</div>
        </div>
        <div class="item">
          <div>Nhóm khách hàng：</div>
          <div class="value">{{ psInfo.group_name || '-' }}</div>
        </div>
        <div class="item">
          <div>Người giới thiệu：</div>
          <div class="value">{{ psInfo.spread_uid_nickname || '-' }}</div>
        </div>
        <div class="item">
          <div>Thời gian đăng ký：</div>
          <div class="value">{{ psInfo.add_time | timeFormat }}</div>
        </div>
        <div class="item">
          <div>Thời gian đăng nhập：</div>
          <div class="value">{{ psInfo.last_time | timeFormat }}</div>
        </div>
        <div v-if="psInfo.is_money_level" class="item">
          <div>Gói thẻ VIP：</div>
          <div class="value">
            {{
              psInfo.is_ever_level == 1 ? 'Thành viên vĩnh viễn' : psInfo.overdue_time ? `${psInfo.overdue_time} Hết hạn` : 'Hết hạn'
            }}
          </div>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="section-hd">Nhận xét của người dùng</div>
      <div class="section-bd">
        <div class="item">
          <div>Nhận xét：</div>
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
        return 'Nam';
      } else if (value == 2) {
        return 'Nữ';
      } else {
        return 'Không xác định';
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
